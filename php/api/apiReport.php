<?php
    // echo "debug";
    include_once("../db/query.php");
    include_once("../db/connect.php");
    include_once("../classes/report.php");
    include_once("../api/responseReport.php");
    include_once('../classes/ente.php');
    
    $response = array();

    // var_dump($_POST);
    // var_dump($request);

    abstract class Permission{
        const Admin = 'Admin';
        const Ente = 'Ente';
        const Team = 'Team';
        const User = 'User';
    }


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
            call_user_func($this->callback,$this->params[0]);
        }

        private function checkPermission(){  // TODO:
            // return $this->permission ==
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
  
    
    new Request('GET','report/team/{#}',       $getReportsByTeam,  Permission::Team, $request[2]);       //  /report/team/{nameTeam}
    new Request('GET','report/id/{#}',         $getReportById,     Permission::Admin, $request[2]);      //  /report/id/{id}
    new Request('GET','report/photos/{#}',     $getPhotosOfReport, Permission::Admin, $request[2]);      //  /report/photos/{id}
    new Request('GET','report/history/{#}',    $getHistoryOfReport,Permission::Admin, $request[2]);      //  /report/history/{id}
    new Request('GET','report/delete/{#}',     $deleteReport,      Permission::Admin, $request[2]);      //  /report/delete/{id}
    
    
    new Request('GET','team',                  $getListOfTeams,    Permission::Ente);                      //  /report/team


    new Request('GET','ente',                  $getEnte, Permission::Ente);                            // apiReport/ente/reports
    new Request('GET','ente/teams',            $getTeams,     Permission::Ente);                       // apiReport/ente/teams
    new Request('GET','ente/reports',          $getAllReports,  Permission::Ente);                     // apiReport/ente
    
    $found = Request::search($request);
    // var_dump($found);
    $found->execute();
    // var_dump($requests);

   
 
    // else{                                                   // push info
    //     switch($request[0]){
    //         case 'report':{                                 //  apiReport/report    [POST]
    //             if(isset($request[2])){
    //                 switch($request[2]){
    //                     case 'team':{                       //  apiReport/report/{id}/team    => editTeam [POST] {newTeam}
    //                         editTeam($request[1], $_POST[$request[2]]);
    //                         break;
    //                     };
    //                     case 'state':{                      //  apiReport/report/{id}/state  => editState [POST] {newState}
    //                         editState($request[1], $_POST[$request[2]]);
    //                         break;
    //                     };
    //                     case 'history':{                    //  apiReport/report/{id}/history  => addToHistory [POST] {newNote}
    //                         updateHistory($request[1], $_POST[$request[2]]);
    //                         break;
    //                     };   
    //                 }
    //             }
    //             else{
    //                 switch($request[1]){
    //                     case 'new':{                            // apiReport/report/new => newReport [POST] {report data}
    //                         newReport($_POST);                            
    //                         break;
    //                     };
    //                     case 'delete':{                         // apiReport/report/delete => newReport [POST] {ids of reports}
    //                         deleteReports($_POST);                            
    //                         break;
    //                     };
    //                 }
    //             }

    //         }
    //     }
    // }


    // var_dump($response);

    header('Content-Type: application/json');
    echo json_encode($response);


?>