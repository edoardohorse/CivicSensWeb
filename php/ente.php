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
        $this->reports = [];
        $stmt = $conn->prepare(QUERY_FETCH_LIST_TEAM);
        $stmt->execute();
        $res = $stmt->get_result();
        $i=0;
        $nameTypeReport = '';
        while($row = $res->fetch_assoc()){
            $team = new Team( $row['email'] );
            $team->fetchReports();
            
            // var_dump($team->reports);
            $keys = array_keys($team->reports);
            foreach($keys as $key){
                // var_dump($key);
                $this->reports[$key]  =$team->reports[$key];
                //  = $value;
            }

            array_push( $this->teams, $team);
        }

        // var_dump($this->reports);
    }

    public function editTeam($idReport, $nameNewTeam){
        $teamToAssign = null;
        foreach($this->teams as $team){
            if($team->getName() == $nameNewTeam){
                $teamToAssign = $team;
                break;
            }
        }
        
        return $this->reports[$idReport]->editTeam( $teamToAssign->getName() );

        
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