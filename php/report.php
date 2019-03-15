<?php

include_once("query.php");
include_once("connect.php");

function location($lan,$lng){
    return array('lan'=>$lan,'lng'=>$lng);    
}

function note($message,$date,$team){
    return array('message'=>$message,'date'=>$date,'team'=>$team);    
}

abstract class ReportState
{
    const Waiting = 'In attesa';
    const InCharge = 'In lavorazione';
    const Done = 'Finito';
    
}

class Report{
    private $id;
    private $city;
    private $address;
    private $description;
    private $state;
    private $grade;
    private $location;
    private $user;
    private $type;
    private $team;
    private $cdt;
    private $date;
    private $photos = array();
    private $history = array();

    function __construct(array $data){
        if(count($data) == 1){
            $this->id = $data[0];
            return;
        }
        
        $data['location'] = location($data['lan'],$data['lng']);
        unset($data['lan']);
        unset($data['lng']);

        foreach($data as $key=>$value){
            $this->{$key} = $value;
        }

        // $this->fetchPhotos();
        // $this->fetchHistory();
    }

    static public function newReport(){
        global $conn;
        // var_dump($_POST);
        $cdt = Report::newCDT();

        

        $stmt = $conn->prepare(QUERY_TEAM_MIN_REPORT);
        $stmt->bind_param("ss", $_POST['typeReport'], $_POST['city']);
        $stmt->execute();
        $idTeam = $stmt->get_result()->fetch_assoc()['id'];
        
        // var_dump($idTeam);
        // var_dump($_POST);
        $stmt = $conn->prepare(QUERY_NEW_REPORT);
        $stmt->bind_param("ssssiidds",
                                    $_POST['city'],             //string
                                    $_POST['description'],      //string
                                    $_POST['address'],          //string
                                    $_POST['grade'],            //string
                                    $_POST['typeReport'],       //int
                                    $idTeam,                    //int
                                    $_POST['lan'],              //double
                                    $_POST['lng'],              //double
                                    $cdt                        //string
                                );
        $stmt->execute();
        
        $id = $conn->insert_id;
        // var_dump($id);
        $newReport = new Report([$conn->insert_id]);
        $newReport->pushPhotos($id);
        
        $newReport->fetchInfo();

        
        
        
        if($_POST['email'] != "")
            Report::sendEmailToUser($_POST['email'], $_POST['description'], $cdt);
        
        return $newReport;
    }

    static private function generateRandomString($length = 11) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    static private function newCDT(){
        global $conn;
    
        $cdtExisted = true;
        $newCDT = "";
        $stmt = $conn->prepare(QUERY_FETCH_CDT);
        while($cdtExisted){
    
            $newCDT  = Report::generateRandomString();
            // var_dump($newCDT);
            $stmt->bind_param("s",$newCDT);
            $stmt->execute();
            $n = $stmt->get_result()->num_rows;
            if($n == 0){
                $cdtExisted = false;
                break;
            }
    
        }
        
        return $newCDT;
    }
    
    
    private function pushPhotos($idReport){
        global $conn;
        // var_dump($_FILES);
        if(isset($_FILES['photos']['name'])){
            $count = count($_FILES['photos']['name']);
            for ($i = 0; $i < $count; $i++) {
            
                try{
                    move_uploaded_file($_FILES['photos']['tmp_name'][$i],"../". UPLOAD_PATH . $_FILES['photos']['name'][$i]);
                    $stmt = $conn->prepare(QUERY_NEW_PHOTOS);
                    $stmt->bind_param("si", $_FILES['photos']['name'][$i], $idReport);
                    $stmt->execute();
                    
                }catch(Exception $e){
                    
                }
                
            }
        }
    }

    public function fetchPhotos(){
        global $conn;
        $stmt = $conn->prepare(QUERY_FETCH_PHOTOS);
        $stmt->bind_param("i",$this->id);
        $stmt->execute();
        $result = $stmt->get_result();


        while($row = $result->fetch_assoc())
            foreach($row as $value)
                array_push($this->photos,UPLOAD_PATH.$value);
        
    }

    public function fetchHistory(){
        global $conn;
        $stmt = $conn->prepare(QUERY_FETCH_HISTORY);
        $stmt->bind_param("i",$this->id);
        $stmt->execute();
        $result = $stmt->get_result();

        $history = array();
        while($row = $result->fetch_assoc())
            array_push($history,$row);

        $this->history = $history;
        
    }

    public function fetchInfo(){
        global $conn;
        $stmt = $conn->prepare(QUERY_REPORT_BY_ID);
        // var_dump($this->id);
        $stmt->bind_param("i",$this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        // var_dump($row);die();
        foreach($row as $key=>$value){
            $this->{$key} = $value;
        }

    }

    
    static public function sendEmailToUser($email, $description, $cdt){
        // var_dump($email);
        // var_dump($description);
        // var_dump($cdt);
        $to = $email;
        $subject = "Report ricevuto";
        $message = "
            <html>
                <head>
                    <title>Report ricevuto</title>
                </head>
                <body>
                    <h4>Abbiamo ricevuto il tuo report con descrizione: <i>$description</i></h4>
                    <h4>Il codice di tracking è qui riportato: <i>$cdt</i></h4>
                
                    <h3>Ti ringraziamo per il tuo sostegno.</h3>    
                </body>
            </html>
            ";
    
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
    
        $headers[] = 'From: Team GERCS <civicsens@altervista.org>';
    
        mail($to,$subject,$message, implode("\r\n", $headers));
        

    }

    public function editTeam($idNewTeam){
        if($idNewTeam == $this->team)
            return;

        global $conn;
        $stmt = $conn->prepare(QUERY_EDIT_REPORT_TEAM_BY_ID);
        $stmt->bind_param("si",$idNewTeam, $this->id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $this->fetchInfo();
            return true;
        }
        else{
            return false;
        }

    }

    public function editState($newState){
        if($newState == $this->state )
            return;
        switch($newState){
            case ReportState::Waiting:
            case ReportState::InCharge:
            case ReportState::Done:{
                break;}
            default:{
                return;
                break;
            }
        }

        global $conn;
        $stmt = $conn->prepare(QUERY_EDIT_REPORT_STATE);
        $stmt->bind_param("si",$newState, $this->id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $this->fetchInfo();
            return true;
        }
        else{
            return false;
        }

        
    }

    public function deleteReport(){
        global $conn;

        $this->fetchPhotos();
        $stmt =  $conn->prepare(QUERY_DELETE_REPORT);
        $stmt->bind_param("i",$this->id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $this->deletePhotosFromFileSystem();
            return true;
        }
        else{
            return false;
        }
    }

    private function deletePhotosFromFileSystem(){
        foreach($this->photos as $value){
            // var_dump($value);
            $path = UPLOAD_PATH.$value;
            if(file_exists($path)){
                unlink($path);
            }
        }
    }
    
    public function updateHistory($message, $idTeam){
        global $conn;

        $stmt = $conn->prepare(QUERY_ADD_HISTORY_REPORT_BY_NAME_TEAM);
        $stmt->bind_param("sii",$message, $this->id, $idTeam);
        $stmt->execute();    
        if($stmt->affected_rows > 0){
            $this->fetchInfo();
            return true;
        }
        else{
            return false;
        }
    }

    public function serialize(){
        $serialize = array();
        foreach($this as $key=>$value){
            $serialize[$key] = $value;
        }
        return $serialize;
    }


    public function __toString(){
        return json_encode($this->serialize());
    }

    public function __call($function, $args){
        // Definisci tutti i getter anche se inesistenti
        if(substr($function,0,3) == 'get'){
            $method = strtolower(substr($function,3));
            if(isset($this->{$method})){
                return $this->{$method};
            }
        }
    }
        
}



?>