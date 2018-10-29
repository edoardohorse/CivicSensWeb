<?php

include_once("connect.php");
include_once("query.php");
include_once("team.php");



class Ente extends Admin{

    public $reports = array();
    public $teams = array();

    const DOMAIN = '@a';

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

    // param data.member, data.name, data.type
    public function newTeam($data){
        global $conn;
        $email = $data['name'].Ente::DOMAIN;       // TODO: Gestire email
        $pass = MD5($data['pass']);
        $member = (int)$data['member'];
        $nameTypeReport   = $data['type'];
        $type = 'Team';

        $listTypeReport = [];

        // Lista tipi report per ottenere gli id
        $stmt = $conn->prepare(QUERY_LIST_TYPE_REPORT);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $listTypeReport[$row['id']] = $row['name'];
        }

        $idTypeReport = array_search($nameTypeReport, $listTypeReport);

        // var_dump($pass);
        // die();

        // Registra nuovo utente come team
        $stmt = $conn->prepare(QUERY_USER_SIGN_UP);
        $stmt->bind_param('sss', $email, $type, $pass);
        $stmt->execute();
        $idUser = $conn->insert_id;
        
        $stmt = $conn->prepare(QUERY_ADD_TEAM);
        $stmt->bind_param('siii', $data['name'], $idTypeReport, $member, $idUser);
        $stmt->execute();
        

        if($stmt->affected_rows > 0){
            $this->fetchTeams();
            return true;
        }
        else{
            return false;
        }

        
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