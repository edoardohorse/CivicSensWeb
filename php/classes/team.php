<?php

include_once("../db/connect.php");
include_once("../db/query.php");
include_once("admin.php");
include_once("report.php");


class Team extends Admin{
    

    private $id;
    private $typeReport;
    private $nMember;

    public function __construct($name){
        parent::__construct($name);

        $this->fetchInfo();
    }

    public function getNMember(){return $this->nMember;}
    public function setNMember($n){ $this->nMember = $n;}

    private function fetchInfo(){
        global $conn;

        $stmt = $conn->prepare(QUERY_FETCH_TEAM_BY_NAME);
        $stmt->bind_param("s", $this->name);
        $stmt->bind_result($id, $typeReport,$nMember);
        $stmt->execute();
        $stmt->fetch();

        $this->id = $id;
        $this->typeReport = $typeReport;
        $this->nMember = $nMember;

    }

    public function fetchReports(){
        global $conn;
        
        
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

    public function serializeTeam(){
        return array('nMember'=> $this->nMember,
                    'name'=>$this->name,
                    'typeReport'=>$this->typeReport,
                    'reports'=>$this->serializeReports());
    }
}



?>