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
    

    if(count($_POST) == 0){                 // fetch info
        switch($request[0]){                    // api/   0   / 1

            case 'report':{                 
    
                switch($request[1]){
                    case 'city':{                       // apiReport/report/city/{cityName}
                        getReportsByCity($request[2]);
                        break;
                    };
                    case 'team':{                       // apiReport/report/team/{nameTeam}
                        getReportsByTeam($request[2]);
                        break;
                    };
                    case 'id':{                         // apiReport/report/id/{id}
                        getReportById($request[2]);
                        break;
                    };
                    case 'photos':{                     // apiReport/report/photos/{id}
                        getPhotosOfReport($request[2]);
                        break;
                    };
                    case 'history':{                    // apiReport/report/history/{id}
                        getHistoryOfReport($request[2]);
                        break;
                    };
                    case 'delete':{                     // apiReport/report/delete/{id} => deleteReport
                        deleteReport($request[2]);
                        break;
                    };
    
                }
                break;
            }
            case 'team':{
                getListOfTeams();
                break;
            }           
            case 'ente':{
                $e = new Ente('ente');
                switch($request[1]){
                    case 'reports':{                       // apiReport/ente/reports/
                    reply('',false,$e->serializeReports());
                        break;
                    };
                    case 'teams':{                       // apiReport/ente/teams/
                        reply('',false,$e->serializeTeams());
                        break;
                    };
                    default:{                            // apiReport/ente/
                        reply('',false,$e->serialize());
                    }
                }
            }
    
        }
    }
    else{                                                   // push info
        switch($request[0]){
            case 'report':{                                 //  apiReport/report    [POST]
                if(isset($request[2])){
                    switch($request[2]){
                        case 'team':{                       //  apiReport/report/{id}/team    => editTeam [POST] {newTeam}
                            editTeam($request[1], $_POST[$request[2]]);
                            break;
                        };
                        case 'state':{                      //  apiReport/report/{id}/state  => editState [POST] {newState}
                            editState($request[1], $_POST[$request[2]]);
                            break;
                        };
                        case 'history':{                    //  apiReport/report/{id}/history  => addToHistory [POST] {newNote}
                            updateHistory($request[1], $_POST[$request[2]]);
                            break;
                        };   
                    }
                }
                else{
                    switch($request[1]){
                        case 'new':{                            // apiReport/report/new => newReport [POST] {report data}
                            newReport($_POST);                            
                            break;
                        };
                        case 'delete':{                         // apiReport/report/delete => newReport [POST] {ids of reports}
                            deleteReports($_POST);                            
                            break;
                        };
                    }
                }

            }
        }
    }


    // var_dump($response);

    header('Content-Type: application/json');
    echo json_encode($response);


?>