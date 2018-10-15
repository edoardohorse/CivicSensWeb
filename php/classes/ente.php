<?php

include_once("../db/connect.php");
include_once("../db/query.php");
include_once("team.php");

class TypeReport{
    public function __construct($name, $id){
        $this->name = $name;
        $this->id = $id;
        $this->teams = array();
    }
}

class Ente extends Admin{

    public $allReports = array();

    public function __construct($name){
        parent::__construct($name);

        $this->fetchTeams();
    }

    public function fetchTeams(){
        global $conn;

        $stmt = $conn->prepare(QUERY_FETCH_LIST_TEAM);
        $stmt->execute();
        $res = $stmt->get_result();
        $i=0;
        $nameTypeReport = '';
        while($row = $res->fetch_assoc()){
            if($nameTypeReport == '' || $nameTypeReport != $row['type_report']){
                $nameTypeReport = $row['type_report'];
                $this->allReports[$i] = new TypeReport( $nameTypeReport, $row['type_report_id']);
                $i++;
                // var_dump($nameTypeReport);
            }
            
            $team = new Team( $row['name'] );
            $team->fetchReports();
            array_push( $this->allReports[$i-1]->teams, $team);
        }

        // var_dump($this->allReports[0]);
    }

    public function serialize(){
        $result = array();
        
        for($i = 0; $i< count($this->allReports); $i++){
            
            $teams = array();
            foreach($this->allReports[$i]->teams as $key=>$value){
                $teamSerialized =  $value->serializeReports();
                array_push($teams, $teamSerialized);
            }
            array_push($result, $this->allReports[$i]);
            $result[$i]->teams = $teams;
            // var_dump(json_encode($result));
            
        }
        return $result;
    }
}


?>