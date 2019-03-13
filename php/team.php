<?php

include_once("connect.php");
include_once("query.php");
include_once("admin.php");
include_once("report.php");


class Team extends Admin{
    

    private $id;
    private $typeReport;
    private $nMember;
    public $reports;
    
    public function __construct($email,$city){
        $this->email = $email;
        $this->city = $city;
        $this->fetchInfo();
        parent::__construct($this->name,$this->city);

    }
    public function getId(){return $this->id;}
    public function getTypeReport(){return $this->typeReport;}
    public function getNMember(){return $this->nMember;}
    public function setNMember($n){ $this->nMember = $n;}

    private function fetchInfo(){
        global $conn;

        $stmt = $conn->prepare(QUERY_FETCH_TEAM_BY_EMAIL);
        $stmt->bind_param("ss", $this->email, $this->city);
        $stmt->bind_result($id, $typeReport,$nMember ,$name, $city, $n_report);
        $stmt->execute();
        $stmt->fetch();

        $this->id           = $id;
        $this->name         = $name;
        $this->typeReport   = $typeReport;
        $this->nMember      = $nMember;
        $this->city         = $city;
        $this->n_report     = $n_report;
    }

    public function fetchReports(){
        global $conn;
        $this->reports = [];
        
        $stmt = $conn->prepare(QUERY_REPORT_BY_TEAM_BY_ID);
        $stmt->bind_param("i",$this->id);
        
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $this->reports[ $row['id'] ] = new Report($row);
        }

        // var_dump($this->reports);
    }

    public function setReportAsInCharge($id){
        return $this->reports[$id]->editState(ReportState::InCharge);
    }

    public function setReportAsDone($id){
        return $this->reports[$id]->editState(ReportState::Done);
    }

    public function updateHistoryOfReport($id, $message){
        if($this->reports[$id]->getState() == ReportState::InCharge)
            return $this->reports[$id]->updateHistory($message);
    }

    public function changeName($newName){
        global $conn;
       
        $stmt = $conn->prepare(QUERY_CHANGE_NAME_TEAM);
        $stmt->bind_param('ss', $newName, $this->name);
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
        return array('nMember'=> $this->nMember,
                    'name'=>$this->name,
                    'typeReport'=>$this->typeReport,
                    'nReport'=>$this->n_report);
    }

    public function serializeReports(){
        $result = array();
        foreach($this->reports as $key=>$value){
            array_push($result, $this->reports [$key]->serialize());
        }
        return $result;
    }
}



?>