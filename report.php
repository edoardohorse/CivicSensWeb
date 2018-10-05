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
    const InAttesa = 'In attesa';
    const InLavorazione = 'In lavorazione';
    const Finito = 'Finito';
    
}

function newLocation($lan, $lng){
    global $conn;

    $stmt = $conn->prepare(QUERY_NEW_LOCATION);
    $stmt->bind_param("dd",$lan, $lng);
    $stmt->execute();
    // $stmt->bind_result($id);
    return $conn->insert_id;
    
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
    private $photos = array();
    private $history = array();

    public function getId(){return $this->id;}
    public function getCity(){return $this->city;}
    public function getAddress(){return $this->address;}
    public function getDescription(){return $this->description;}
    public function getState(){return $this->state;}
    public function getGrade(){return $this->grade;}
    public function getLocation(){return $this->location;}
    public function getUser(){return $this->user;}
    public function getType(){return $this->type;}
    public function getTeam(){return $this->team;}
    public function getCdt(){return $this->cdt;}
    public function getPhotos(){return $this->photos;}
    public function getHistory(){return $this->history;}

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

    static public function newReport(array $data){
        global $conn;

        $location = newLocation($_POST['lan'], $_POST['lng']);

        $stmt = $conn->prepare(QUERY_TEAM_MIN_REPORT);
        $stmt->execute();
        $idTeam = $stmt->get_result()->fetch_assoc()['id'];

        // var_dump($idTeam);
        // var_dump($data);
        $stmt = $conn->prepare(QUERY_NEW_REPORT);
        $stmt->bind_param("isisssi",$_POST['idCity'],
                                    $_POST['description'],
                                    $location,
                                    $_POST['address'],
                                    $_POST['grade'],
                                    $_POST['typeReport'],
                                    $idTeam);
        $stmt->execute();
        
        $id = $conn->insert_id;
        
        $newReport = new Report([$conn->insert_id]);
        $newReport->pushPhotos($id);
        $newReport->newCDT($id);
        $newReport->fetchInfo();

        
        return $newReport;
        
    }

    private function generateRandomString($length = 11) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    private function newCDT($idReport){
        global $conn;
    
        $cdtExisted = true;
        $newCDT = "";
        $stmt = $conn->prepare(QUERY_FETCH_CDT);
        while($cdtExisted){
    
            $newCDT  = $this->generateRandomString();
            // var_dump($newCDT);
            $stmt->bind_param("s",$newCDT);
            $stmt->execute();
            $n = $stmt->get_result()->num_rows;
            if($n == 0){
                $cdtExisted = false;
                break;
            }
    
        }
        
        $stmt = $conn->prepare(QUERY_NEW_CDT);
        $stmt->bind_param("ss",$newCDT,$idReport);
        $stmt->execute();    
    }
    
    
    private function pushPhotos($idReport){
        global $conn;
        // var_dump($_FILES);
        if(isset($_FILES['photos']['name'])){
            $count = count($_FILES['photos']['name']);
            for ($i = 0; $i < $count; $i++) {
            
                try{
                    move_uploaded_file($_FILES['photos']['tmp_name'][$i], UPLOAD_PATH . $_FILES['photos']['name'][$i]);
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
                array_push($this->photos,$value);
        
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
        $stmt->bind_param("i",$this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        // var_dump($row);die();
        foreach($row as $key=>$value){
            $this->{$key} = $value;
        }

    }

    // TODO
    public function sendEmailToUser(){
        if($this->user != null){
            $to = $this->user;
            $subject = "My subject";
            $txt = "Hello world!";
            $headers = "From: civicsens@altervista.org" . "\r\n";

            mail($to,$subject,$txt,$headers);
        }

    }

    public function editTeam($newTeam){
        if($newTeam == $this->team)
            return;

        global $conn;
        $stmt = $conn->prepare(QUERY_EDIT_REPORT_TEAM);
        $stmt->bind_param("si",$newTeam, $this->id);
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
            case ReportState::InAttesa:
            case ReportState::InLavorazione:
            case ReportState::Finito:{
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
    
    public function updateHistory($message){
        global $conn;
        
        /* $stmt = $conn->prepare(QUERY_FETCH_TEAM_BY_NAME);
        $stmt->bind_param("s",$this->team);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $idTeam = $row['id'];


        $stmt = $conn->prepare(QUERY_ADD_HISTORY_REPORT);
        $stmt->bind_param("sii",$message, $idTeam, $this->id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $this->fetchInfo();
            return true;
        }
        else{
            return false;
        } */

        $stmt = $conn->prepare(QUERY_ADD_HISTORY_REPORT_BY_NAME_TEAM);
        $stmt->bind_param("sis",$message, $this->id, $this->team);
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
        
}



?>