<?php
    // echo "debug";
    include_once("query.php");
    include_once("connect.php");
    include_once("report.php");
    include_once("responseReport.php");
    include_once("user.php");
    include_once("session.php");
    
    $response = array();

    // var_dump($_POST);
    // var_dump($request);
    
    // var_dump($_SESSION);

    

    global $requests;
    $requests = array();


    class Request{
        function __construct($method,$names,$callback,$permission, $param = null){
            global $requests;
            
            $this->method = $method;
            $this->names = $names;
            $this->callback = $callback;
            
            $this->param = null;
            
            if($param){
                
                $arr = explode('/',$this->names);
                
                // var_dump($arr);
                $n = array_search( '{#}', $arr );
                if(isset($param[$n]))
                    $this->param  = $param[$n];
                    
                // var_dump($this->param);
            }

            $this->permission = $permission;            

            $requests[$this->names] = $this;

        }

        public function execute(){
            if($this->checkPermission()){
                $callback = $this->callback;  // Creo handle dalla stringa
                // var_dump($callback);
                // var_dump($this->param);
                $callback($this->param);                
            }
            else{
                reply('Non hai abbastanza permessi per ottenere questa informazione',true);
            }
            
        }

        private function checkPermission(){
            global $user;
            return $user->checkPermission($this->permission);
        }

        static function search($url){
            global $requests;
            $found = null;
            // var_dump($url);
            foreach($requests as $key=>$req){
                $key = explode('/', $key);
                
                // var_dump($key);
                // var_dump($url);

                if(count($url) > count($key)){
                    for($i=0;$i<count($url);$i++){
                        // var_dump($url[$i]);
                        // var_dump($key[$i]);
                        
                        // Se la chiave è più corta dell'url allora non è trovata
                        if(!isset($key[$i])){
                            $found = null;
                            break;
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
                }
                if(count($url) <= count($key)){
                    for($i=0;$i<count($key);$i++){
                        // var_dump($url[$i]);
                        // var_dump($key[$i]);
                        
                        // Se la chiave è più corta dell'url allora non è trovata
                        if(!isset($url[$i])){
                            $found = null;
                            break;
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
                }

                // var_dump($found);
                if($found)
                    return $found;
            }

            return $found;
        }
    }
  
    // ==== COMMON Requests: User, Ente, Team 
        // [GET]
            new Request('GET','report/id/{#}',          $getReportById_handler,         Permission::Common, $request);          // api/report/id/{id}
            new Request('GET','report/photos/{#}',      $getPhotosOfReport_handler,     Permission::Common, $request);          // api/report/photos/{id}
            new Request('GET','report/history/{#}',     $getHistoryOfReport_handler,    Permission::Common, $request);          // api/report/history/{id}
            new Request('GET','ente/{#}/reports',       $getAllReports_handler,         Permission::Common, $request);          // api/ente/reports
            new Request('GET','ente/reports',           $getAllReports_handler,         Permission::Common, $request);          // api/ente/reports
            new Request('GET','report/types/{#}',           $getListTypeOfReport_handler,   Permission::Common, $request);                    // api/report/types
    
    // ==== ADMIN Requests
        // [POST]
            new Request('POST','report/delete/{#}',      $deleteReport_handler,          Permission::Admin,  $request);          // api/report/delete/{id}
            new Request('POST','report/delete',         $deleteReports_handler,          Permission::Admin);            // api/report/delete => newReport [POST] {ids of reports}
            new Request('POST','team/name',             $changeTeamName_handler,         Permission::Admin);            // api/team/name => changeNameTeam [POST] {newName}
    



    // ==== TEAM Requests
        // [GET]
            new Request('GET','report/team/{#}',        $getReportsByTeam_handler,      Permission::Team,   $request);          // api/report/team/{nameTeam}
        
        // [POST]
            new Request('POST','report/{#}/state',      $editState_handler,             Permission::Team,   $request);  // api/report/{id}/state  => editState [POST] {newState}
            new Request('POST','report/{#}/history',    $updateHistory_handler,         Permission::Team,   $request);  // api/report/{id}/history  => addToHistory [POST] {newNote}
    

    // ==== ENTE Requests
        //  [GET]
            // new Request('GET','team',                   $getListOfTeams_handler,        Permission::Ente,   $request);  // api/team    
            new Request('GET','ente',                   $getEnte_handler,               Permission::Ente,   $request);  // api/ente
            new Request('GET','ente/teams',             $getTeams_handler,              Permission::Ente);  // api/ente/teams
            //   ente/reports    ↑↑↑↑↑↑↑ defined in COMMON
            
            //  [POST]
            new Request('POST','report/{#}/team',       $editTeam_handler,              Permission::Ente,   $request);  // api/report/{id}/team    => editTeam [POST] {newTeam}
            new Request('POST','ente/team/new',         $newTeam_handler,               Permission::Ente);             // api/ente/team/neam
            new Request('POST','ente/team/delete',      $deleteTeam_handler,            Permission::Ente);             // api/ente/team/delete
    

    // ==== USER Requests
        //  [GET]
            //   ente/reports    ↑↑↑↑↑↑↑ defined in COMMON
        //  [POST]
            new Request('POST','report/new',            $newReport_handler,             Permission::User,   $request);            // api/report/new => newReport [POST] {report data}



    
    $found = Request::search($request);
    // var_dump($found);
    if($found)
        $found->execute();
    // var_dump($requests);

    
    header('Content-Type: application/json');
    echo json_encode($response);


?>