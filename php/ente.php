<?php

include_once("connect.php");
include_once("query.php");
include_once("team.php");
include_once("algoritm_team.php");


class Ente extends Admin{

    public $reports = array();
    public $teams = array();
    private $domain;
    
    static function getDomain($city){return "@".strtolower($city);}

    public function __construct($name, $city = null){
        $this->email = $name;
        if($city == null){
            global $conn;
            
            $stmt = $conn->prepare(QUERY_FETCH_CITY_FROM_ENTE);
            $stmt->bind_param("s", $this->email);
            $stmt->bind_result($city);
            $stmt->execute();
            $stmt->fetch();
            
            $this->city = $city;
        }
        $this->domain = Ente::getDomain($this->city);
        parent::__construct($name, $city);

        // $this->fetchTeams();
    }

    public function fetchTeams(){
        global $conn;
        
        $this->teams = [];
        $this->reports = [];
        $stmt = $conn->prepare(QUERY_FETCH_LIST_TEAM);
        $stmt->bind_param("s",$this->city);
        $stmt->execute();
        $res = $stmt->get_result();
        $i=0;
        $nameTypeReport = '';
        while($row = $res->fetch_assoc()){
            $team = new Team( $row['email'], $this->city );
            array_push( $this->teams, $team);
        }

        // var_dump($this->reports);
    }

    public function fetchReports($city = null){
        global $conn;
        if($city){
            $this->setCity($city);
        }
        else{
            $city = $this->getCity();
        }

        // $this->fetchTeams();
        // $this->teams = [];
        $this->reports = [];
        $stmt = $conn->prepare(QUERY_REPORT_BY_ENTE);
        $stmt->bind_param("s", $city);
        $stmt->execute();
        $res = $stmt->get_result();
        while($row = $res->fetch_assoc()){
            $report = new Report( $row );
            // var_dump($report);
            // array_filter($this->teams[])
            // if()
            array_push( $this->reports, $report);            
        }

        if(count($this->reports) > 0)
            return true;
        
        return false;

    }

    public function editTeam($idReport, $nameNewTeam){
        $teamToAssign = null;
        $reportToEdit = null;
        foreach($this->teams as $team){
            if($team->getName() == $nameNewTeam){
                $teamToAssign = $team;
                break;
            }
        }
        $reportToEdit = $this->getReportFromId($idReport);
        
        return $reportToEdit->editTeam( $teamToAssign->getId() );

        
    }

    public function deleteTeam($nameTeamToDelete){
        $this->fetchReports();
        
        $teamToDelete = array_filter($this->teams, function($t) use($nameTeamToDelete){return $t->getName() == $nameTeamToDelete;});
        // echo var_dump($this->teams);die();

        $teamToDelete = $teamToDelete[array_keys($teamToDelete)[0]];

        
        $teams = array_filter($this->teams, function($t) use($teamToDelete)
            {return $t->getTypeReport() == $teamToDelete->getTypeReport(); });

        $list = [];
        foreach($teams as $team){
            $team->fetchReports();
            if($team->getName() == $nameTeamToDelete){
                $key = array_search($team, $teams);
                unset($teams[$key]);
            }
            else{
                $list[$team->getName()] = count($team->reports);
            }
        }

        $nReportToAssign = count($teamToDelete->reports);
        //var_dump($nReportToAssign);
        // var_dump($list);
        $list = distributeInteger($list,$nReportToAssign);
        // var_dump($list);

        foreach($list as $name=>$value){
            if($nReportToAssign <= 0){
                break;
            }
            // echo "<br><br>Team a cui aggiungere: $name".PHP_EOL;
            
            $team = array_filter($teams, function($t) use($name){return $t->getName() == $name;});
            $team = $team[array_keys($team)[0]];

            
            $nReAssigned = abs($value - count($team->reports));
            
            // var_dump($nReAssigned);
            $i=0;
            // var_dump($teamToDelete);
            foreach($teamToDelete->reports as $report){
                
                if($i++ < $nReAssigned){
                    
                    // echo "<br>ID Report di cui cambiare il team: {$report->getId()}";
                    // echo "<br>Team del report di cui cambiare il team: {$report->getTeam()}";
                    // var_dump($report->getId());
                    $this->editTeam($report->getId(), $name);
                    // var_dump($report->getTeam());

                    // echo " ==> {$report->getTeam()}";
                    
                    unset($teamToDelete->reports[array_search($report, $teamToDelete->reports)]);
                }
                else{
                    break;
                }
                
            }
            

            $nReportToAssign -= $nReAssigned;
            // var_dump($team);
        }

        
        $this->fetchTeams();

        global $conn;
        $stmt = $conn->prepare(QUERY_DELETE_TEAM);
        $stmt->bind_param("ss",$nameTeamToDelete, $this->city);
        $stmt->execute();

        return $list;

    }

    // param data.member, data.name, data.type
    public function newTeam($data){
        global $conn;
        $email = $data['name'].Ente::getDomain($this->city);       // TODO: Gestire email
        $pass = MD5($data['pass']);
        $member = (int)$data['member'];
        $nameTypeReport   = $data['type'];
        $type = 'Team';

        $listTypeReport = [];

        // Lista tipi report per ottenere gli id
        $stmt = $conn->prepare(QUERY_LIST_TYPE_REPORT);
        $stmt->bind_param("s",$this->city);
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
        $stmt->bind_param('ssss', $email, $type, $pass, $this->city);
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

    public function newTypeReport($data){
        global $conn;
       

        $name = $data['name'];
       
        

        $stmt = $conn->prepare(QUERY_ADD_TYPE_REPORT);
        $stmt->bind_param('ss', $name, $this->city);
        $stmt->execute();
        

        if($stmt->affected_rows > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function deleteTypeReport($data){
        global $conn;
        $name = $data['name'];
        
        $rep = array_filter($this->reports, function($t) use($name){return $t->getType() == $name;});
        $city = $this->city;
        // var_dump($this->city);die();
        $stmt = $conn->prepare(QUERY_DELETE_TYPE_REPORT);
        $stmt->bind_param('ss', $name, $city);
        
        if(count($rep) == 0){
            $stmt->execute();
        }
        

        if($stmt->affected_rows > 0){
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
        // foreach($this->teams as $key=>$tmpTeam){   
        //     // var_dump($tmpTeam);
        //     foreach($tmpTeam->reports as $key=>$rep){   
        //         $result[$c++] = $rep->serialize();
        //     }              
        // }
       foreach($this->reports as $key=>$rep){   
            $result[$c++] = $rep->serialize();
        }              
        
            
        // var_dump($result);die();
        return $result;
    }
}


?>