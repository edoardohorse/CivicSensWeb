<?php
    // echo "debug";
    include_once("query.php");
    include_once("report.php");
    include_once("connect.php");
    include_once("responseReport.php");

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
    
        }
    }
    else{                                                   // push info
        switch($request[0]){
            case 'report':{                                 //  apiReport/report    [POST]
                if(isset($request[2]))
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
                else
                    newReport($_POST);                            // apiReport/report => newReport [POST] {report data}

            }
        }
    }


    // var_dump($response);

    header('Content-Type: application/json');
    echo json_encode($response);


?>