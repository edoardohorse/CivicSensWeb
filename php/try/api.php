<?php
    include_once("../db/query.php");
    include_once("../db/connect.php");
    include_once("response.php");



    $response = array();

    if(count($_POST) == 0){                 // fetch info
        switch($request[0]){                    // api/   0   / 1

            case 'report':{                 
    
                switch($request[1]){
                    case 'city':{               // api/report/city/{cityName}
                        getReportByCity($request[2]);
                        break;
                    };
                    case 'cdt':{               // api/report/cdt/{cdt}
                        getReportByCDT($request[2]);    
                        // echo "qui";
                        // $i=0;
                        // while($i<32){
                        //  echo generateRandomString(11)."<br>";
                        //  $i++;
                        // } 
                        // newCDT($request[2]);    
                        break;
                    };
    
                }
                break;
            }           
    
            case 'user':{                       // api/user/{email}
                if(isset($request[1]))
                    getUser($request[1]);
                break;
            }

            case 'city':{                       // api/city/{name}
                if(isset($request[1]))
                    getCity($request[1]);
                break;
            }

            case 'photos':{                     // api/photos/{reportId}
                if(isset($request[1]))
                    getPhotosByReport($request[1]);
                break;
            }
            
        }
    }
    else{                                       // push info
        switch($request[0]){
            case 'user':{                       // api/user     [POST]
                $email      = $_POST["email"];
                $name       = $_POST["name"];
                $surname    = $_POST["surname"];
                
                signUpUser($email, $name, $surname);
                break;
            }

            case 'report':{                    // api/report    [POST]
               
                $idCity = $_POST["idCity"];
                $description = $_POST["description"];
                $address = $_POST["address"];
                $lan = $_POST["lan"];
                $lng = $_POST["lng"];
                $grade = $_POST["grade"];
                // var_dump($_POST);
                // var_dump($_FILES);
                
                newReport($idCity, $description, $lan, $lng, $address, $grade);

            }
        }
    }


    // var_dump($response);

    header('Content-Type: application/json');
    echo json_encode($response);

?>
