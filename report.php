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

        $data['location'] = location($data['lan'],$data['lng']);
        unset($data['lan']);
        unset($data['lng']);

        foreach($data as $key=>$value){
            $this->{$key} = $value;
        }

        $this->fetchPhotos();
        $this->fetchHistory();
    }

    private function fetchPhotos(){
        global $conn;
        $stmt = $conn->prepare(QUERY_FETCH_PHOTOS);
        $stmt->bind_param("i",$this->id);
        $stmt->execute();
        $result = $stmt->get_result();


        while($row = $result->fetch_assoc())
            foreach($row as $value)
                array_push($this->photos,$value);
        
    }

    private function fetchHistory(){
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

    private function fetchInfo(){
        global $conn;
        $stmt = $conn->prepare(QUERY_REPORT_BY_ID);
        $stmt->bind_param("i",$this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        foreach($row as $key=>$value){
            $this->{$key} = $value;
        }

    }

    // TODO
    private function sendEmailToUser(){
        if($this->user != null){
            $to = $this->user;
            $subject = "My subject";
            $txt = "Hello world!";
            $headers = "From: civicsens@altervista.org" . "\r\n";

            mail($to,$subject,$txt,$headers);
        }

    }

    public function editTeam($newTeam){
        global $conn;
        $stmt = $conn->prepare(QUERY_EDIT_REPORT_TEAM);
        $stmt->bind_param("si",$newTeam, $this->id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $this->fetchInfo();
            // echo "Fatto";
        }

    }

    public function editState($newState){
        // if($newState == $this->state)
        //     return;
        // switch($newState){
        //     case ReportState::InAttesa:{
        //         break;}
        //     case ReportState::InLavorazione:{
        //         break;}
        //     case ReportState::Finito:{
        //         break;}
        // }

        global $conn;
        $stmt = $conn->prepare(QUERY_EDIT_REPORT_STATE);
        $stmt->bind_param("si",$newState, $this->id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $this->fetchInfo();
            // echo "Fatto";
        }

        $this->fetchInfo();
    }

    public function deleteReport(){}
    
    public function updateHistory($message){
        global $conn;
        $stmt = $conn->prepare(QUERY_EDIT_REPORT_STATE);
        $stmt->bind_param("si",$newState, $this->id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $this->fetchInfo();
            // echo "Fatto";
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