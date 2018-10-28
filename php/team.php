<?php

include_once("connect.php");
include_once("query.php");
include_once("admin.php");
include_once("report.php");


class Team extends Admin{
    

    private $id;
    private $typeReport;
    private $nMember;

    public function __construct($email){
        $this->email = $email;
        $this->fetchInfo();
        parent::__construct($this->name);

    }
    public function getId(){return $this->id;}
    public function getTypeReport(){return $this->typeReport;}
    public function getNMember(){return $this->nMember;}
    public function setNMember($n){ $this->nMember = $n;}

    private function fetchInfo(){
        global $conn;

        $stmt = $conn->prepare(QUERY_FETCH_TEAM_BY_EMAIL);
        $stmt->bind_param("s", $this->email);
        $stmt->bind_result($id, $typeReport,$nMember ,$name);
        $stmt->execute();
        $stmt->fetch();

        $this->id = $id;
        $this->name = $name;
        $this->typeReport = $typeReport;
        $this->nMember = $nMember;
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

    public function serialize(){
        return array('nMember'=> $this->nMember,
                    'name'=>$this->name,
                    'typeReport'=>$this->typeReport);
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