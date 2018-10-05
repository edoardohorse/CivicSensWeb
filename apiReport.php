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
                    case 'city':{               // api/report/city/{cityName}
                        getReportByCity($request[2]);
                        break;
                    };
                    case 'id':{               // api/report/id/{id}
                        getReportById($request[2]);
                        break;
                    };
                    case 'photos':{               // api/report/photos/{id}
                        getPhotosOfReport($request[2]);
                        break;
                    };
                    case 'history':{               // api/report/history/{id}
                        getHistoryOfReport($request[2]);
                        break;
                    };
                    case 'delete':{               // api/report/id/{id} => deleteReport
                        deleteReport($request[2]);
                        break;
                    };
    
                }
                break;
            }           
    
        }
    }
    else{                                           // push info
        switch($request[0]){
            case 'report':{                         // api/report    [POST]
                if(isset($request[2]))
                    switch($request[2]){
                        case 'team':{                   // api/report/{id}/team    => editTeam [POST] {newTeam}
                            editTeam($request[1], $_POST[$request[2]]);
                            break;
                        };
                        case 'state':{                  // api/report/{id}/state  => editState [POST] {newState}
                            editState($request[1], $_POST[$request[2]]);
                            break;
                        };
                        case 'history':{               // api/report/{id}/history  => addToHistory [POST] {newNote}
                            updateHistory($request[1], $_POST[$request[2]]);
                            break;
                        };   
                    }
                else
                    newReport($_POST);                            // api/report => newReport [POST] {report data}

            }
        }
    }


    // var_dump($response);

    header('Content-Type: application/json');
    echo json_encode($response);


?>