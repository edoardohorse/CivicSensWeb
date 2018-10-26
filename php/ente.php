<?php

include_once("connect.php");
include_once("query.php");
include_once("team.php");



class Ente extends Admin{

    public $reports = array();
    public $teams = array();

    public function __construct($name){
        parent::__construct($name);

        // $this->fetchTeams();
    }

    public function fetchTeams(){
        global $conn;
        $this->teams = [];

        $stmt = $conn->prepare(QUERY_FETCH_LIST_TEAM);
        $stmt->execute();
        $res = $stmt->get_result();
        $i=0;
        $nameTypeReport = '';
        while($row = $res->fetch_assoc()){
            $team = new Team( $row['email'] );
            $team->fetchReports();
            array_push( $this->teams, $team);
        }

        // var_dump($this->allReports[0]);
    }



    public function serialize(){
        $result = array();
        
        foreach($this->teams as $key=>$tmpTeam){   
            $team = $tmpTeam->serialize();
            $team['reports'] = $tmpTeam->serializeReports();
            array_push($result, $team);                
        };
            
        
        return $result;
    }
    public function serializeTeams(){
        $result = array();
        
        foreach($this->teams as $key=>$tmpTeam){   
            array_push($result, $tmpTeam->serialize());                
        };
            
        
        return $result;
    }

    public function serializeReports(){
        $result = array();
        $c=0;
        // var_dump($this->teams);die();
        foreach($this->teams as $key=>$tmpTeam){   
            // var_dump($tmpTeam);
            foreach($tmpTeam->reports as $key=>$rep){   
                $result[$c++] = $rep->serialize();
            }              
        }
            
        // var_dump($result);die();
        return $result;
    }
}


?>