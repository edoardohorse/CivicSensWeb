<?php
    // echo "debug";
    include_once("query.php");
    include_once("connect.php");
    include_once("report.php");
    include_once("responseReport.php");
    include_once("user.php");
    
    $response = array();

    // var_dump($_POST);
    // var_dump($request);



    global $requests;
    $requests = array();


    class Request{
        function __construct($method,$names,$callback,$permission, ...$params){
            global $requests;
            
            $this->method = $method;
            $this->names = $names;
            $this->callback = $callback;
       
            $this->params  = $params;
            $this->permission = $permission;            

            $requests[$this->names] = $this;
        }

        public function execute(){
            // if($this->checkPermission())
                call_user_func($this->callback,$this->params);
        }

        private function checkPermission(){  // TODO:
            // $_SESSIONs
        }

        static function search($url){
            global $requests;
            $found = null;
            foreach($requests as $key=>$req){
                $key = explode('/', $key);
                // var_dump($key);
                for($i=0;$i<count($url);$i++){
                    // var_dump($url[$i]);
                    // var_dump($key[$i]);
                    
                    // Se la chiave è più corta dell'url allora non è trovata
                    if(!isset($key[$i])){
                        $found = null;
                        continue;
                    }

                    // Continua se trovato il segnaposto
                    if($key[$i] == '{#}'){
                        continue;
                    }
                    
                    if($url[$i] == $key[$i])    // Setta come trovato la richiesta e continua il ciclo
                        $found = $req;
                    else{                       // Setta come non trovato ed esci dal ciclo
                        $found = null;
                        break;
                    }
                    
                }
                if($found)
                    return $found;
            }

            return $found;
        }
    }
  
    // ==== COMMON Requests: User, Ente, Team 
        // [GET]
            new Request('GET','report/id/{#}',          $getReportById,         Permission::Common, $request);          // apiReport/report/id/{id}
            new Request('GET','report/photos/{#}',      $getPhotosOfReport,     Permission::Common, $request);          // apiReport/report/photos/{id}
            new Request('GET','report/history/{#}',     $getHistoryOfReport,    Permission::Common, $request);          // apiReport/report/history/{id}
            new Request('GET','ente/reports',           $getAllReports,         Permission::Common);                    // apiReport/ente
    
    // ==== ADMIN Requests
        // [GET]
            new Request('GET','report/delete/{#}',      $deleteReport,          Permission::Admin,  $request);          // apiReport/report/delete/{id}
        // [POST]
            new Request('POST','report/delete',         $deleteReports,         Permission::Admin,  $_POST);            // apiReport/report/delete => newReport [POST] {ids of reports}
    



    // ==== TEAM Requests
        // [GET]
            new Request('GET','report/team/{#}',        $getReportsByTeam,      Permission::Team,   $request);          // apiReport/report/team/{nameTeam}
        
        // [POST]
            new Request('POST','report/{#}/state',      $editState,             Permission::Team,   $request, $_POST);  // apiReport/report/{id}/state  => editState [POST] {newState}
            new Request('POST','report/{#}/history',    $updateHistory,         Permission::Team,   $request, $_POST);  // apiReport/report/{id}/history  => addToHistory [POST] {newNote}
    

    // ==== ENTE Requests
        //  [GET]
            new Request('GET','team',                   $getListOfTeams,        Permission::Ente);                      // apiReport/team    
            new Request('GET','ente',                   $getEnte,               Permission::Ente);                      // apiReport/ente/reports
            new Request('GET','ente/teams',             $getTeams,              Permission::Ente);                      // apiReport/ente/teams
            //   ente/reports    ↑↑↑↑↑↑↑ defined in COMMON
        
        //  [POST]
            new Request('POST','report/{#}/team',       $editTeam,              Permission::Ente,   $request, $_POST);  // apiReport/report/{id}/team    => editTeam [POST] {newTeam}
    

    // ==== USER Requests
        //  [GET]
            //   ente/reports    ↑↑↑↑↑↑↑ defined in COMMON
        //  [POST]
            new Request('POST','report/new',            $newReport,             Permission::User,   $_POST);            // apiReport/report/new => newReport [POST] {report data}



    
    $found = Request::search($request);
    // var_dump($found);
    $found->execute();
    // var_dump($requests);


    header('Content-Type: application/json');
    echo json_encode($response);


?>