<?php
    include_once("query.php");

    function getReportByCity($city){
        global $conn;
        
        $stmt = $conn->prepare(QUERY_REPORT_BY_CITY);
        $stmt->bind_param("s",$city);

        fetchReports($stmt);
    }

    function getReportByCDT($cdt){
        global $conn,$response;
        
        $stmt = $conn->prepare(QUERY_REPORT_BY_CDT);
        $stmt->bind_param("s",$cdt);

        fetchReports($stmt);

        if($response['error'] == true){
            $response['message'] = '0 Report found';
        }
    }



    function fetchReports($stmt){
        global $response;
        $reports = array();
        $stmt->execute();
        $stmt->bind_result($id, $address, $description, $state, $grade, $user, $typeReport,$lan, $lng, $city,$team);
        
        
        
        while ($stmt->fetch()){
            
            $temp       = array();
            $_city      = array();
            $_location  = array();

            $_city['name'] = $city;
            $_location['lan'] = $lan;
            $_location['lng'] = $lng;
            
            
            $temp['id']             = $id;
            $temp['address']        = $address;
            $temp['description']    = $description;
            $temp['state']          = $state;
            $temp['grade']          = $grade;
            $temp['email']          = $user == null? 'Email non fornita':$user;
            $temp['typeReport']     = $typeReport;
            $temp['location']       = $_location;
            $temp['city']           = $_city;
            $temp['team']           = $team;
            
            array_push($reports, $temp);
        }

        
        
        if(count($reports) == 0){
            $response['error'] = true;
            $response['message'] = 'No report for this city';
        }
        else{
            $response['error'] = false;
            $response['report'] = $reports;
        }
    }

    function newReport($idCity, $description, $lan, $lng, $address,$grade){
        global $conn, $response;


        $location = newLocation($lan, $lng);
        
        $stmt = $conn->prepare(QUERY_NEW_REPORT);
        $stmt->bind_param("isiss",$idCity, $description, $location, $address,$grade);
        // $stmt->bind_result($idReport);
        $stmt->execute();
        $idReport = $conn->insert_id;

        // var_dump($idReport);
        pushPhotos($idReport);

        newCDT($idReport);
    }


    function newLocation($lan, $lng){
        global $conn;

        $stmt = $conn->prepare(QUERY_NEW_LOCATION);
        $stmt->bind_param("dd",$lan, $lng);
        $stmt->execute();
        // $stmt->bind_result($id);
        return $conn->insert_id;
        
    }

    function getCity($nameCity){            
        global $conn, $response;

        $bounds = getBoundsCity($nameCity);
        // var_dump($bounds);
        $stmt = $conn->prepare(QUERY_CITY_BY_NAME);
        $stmt->bind_param("s",$nameCity);
        $stmt->execute();
        $stmt->bind_result($id, $nameCity, $lan, $lng);
        
        $temp = array();
        if($stmt->fetch()){
            
            $temp['id'] = $id;
            $temp['name'] = $nameCity;
            $temp['location'] = array("lan"=>$lan, "lng"=>$lng);
            // $temp['lan'] = $lng;
            $temp['bound_south'] = array("lan"=>$bounds['south']['lan'], "lng"=>$bounds['south']['lng']);
            $temp['bound_north'] = array("lan"=>$bounds['north']['lan'], "lng"=>$bounds['north']['lng']);
            
        }

        $response['error'] = false;
        $response['city'] = $temp;
        
    }

    function getBoundsCity($nameCity){          
        global $conn;
        
        
        $bounds = array();
        $bound_south = array();
        $bound_north = array();

        $stmt = $conn->prepare(QUERY_BOUND_SOUTH_CITY);
        $stmt->bind_param("s",$nameCity);
        $stmt->execute();
        $stmt->bind_result($slan, $slng);

        if($stmt->fetch()){
            $bound_south['lan'] = $slan;
            $bound_south['lng'] = $slng;
        }
        $stmt->close();
        // var_dump($bound_south);
        

        $stmt = $conn->prepare(QUERY_BOUND_NORTH_CITY);
        $stmt->bind_param("s",$nameCity);
        $stmt->execute();
        $stmt->bind_result($nlan, $nlng);

        if($stmt->fetch()){
            $bound_north['lan'] = $nlan;
            $bound_north['lng'] = $nlng;
        }

        // var_dump($bound_north);

        $bounds['south'] = $bound_south;
        $bounds['north'] = $bound_north;
        // var_dump($bounds);
        return $bounds;
    }
        
    function getUser($email){
        global $conn, $response;

        $stmt = $conn->prepare(QUERY_USER_BY_EMAIL);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->bind_result($id, $email, $name, $surname);
        
        $user = array();
        if($stmt->fetch()){
            $temp = array();
            $temp['id'] = $id;
            $temp['email'] = $email;
            $temp['name'] = $name;
            $temp['surname'] = $surname;

            array_push($user,$temp);
        }
        
        if(count($user) == 0){
            $response['error'] = true;
            $response['message'] = 'User not found';
        }
        else{
            $response['error'] = false;
            $response['user'] = $user;
        }

        
    }

    function pushPhotos($idReport){
        global $conn;
        // var_dump($_FILES);
        if(isset($_FILES['photos']['name'])){
            $count = count($_FILES['photos']['name']);
            for ($i = 0; $i < $count; $i++) {
            
                try{
                    move_uploaded_file($_FILES['photos']['tmp_name'][$i], UPLOAD_PATH . $_FILES['photos']['name'][$i]);
                    $stmt = $conn->prepare(QUERY_NEW_PHOTOS);
                    $stmt->bind_param("si", $_FILES['photos']['name'][$i], $idReport);
                    if($stmt->execute()){
                        $response['error'] = false;
                        $response['message'] = 'File uploaded successfully';
                    }else{
                        throw new Exception("Could not upload file");
                    }
                }catch(Exception $e){
                    $response['error'] = true;
                    $response['message'] = 'Could not upload file';
                }
                
            }
        }
        else{
            $response['error'] = true;
            $response['message'] = "Required params not available";
        }
    }

    function signUpUser($email, $name, $surname){
        global $conn;

        $stmt = $conn->prepare(QUERY_USER_SIGN_UP);
        $stmt->bind_param("sss", $email, $name, $surname);
        $stmt->execute();
        
        getUser($email);
    }

    function getPhotosByReport($reportId){
        global $conn, $response;

        $stmt = $conn->prepare(QUERY_PHOTOS_BY_REPORT);
        $stmt->bind_param("i",$reportId);
        $stmt->execute();
        $stmt->bind_result($name);
        
        $photos = array();
        while($stmt->fetch()){
            $temp = array();
            if($_SERVER["SERVER_NAME"] == "192.168.1.181"){
                $temp['url'] = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/civic'.'/uploads' .'/'.$name;
            }
            else{
                $temp['url'] = 'https://'.$_SERVER["HTTP_HOST"].'/uploads' .'/'.$name;
            }
            // var_dump($temp);
            array_push($photos,$temp);
        }
        
        if(count($photos) == 0){
            $response['error'] = true;
            $response['message'] = 'Photos not found';
        }
        else{
            $response['error'] = false;
            $response['photos'] = $photos;
        }
        
    }

    function generateRandomString($length = 11) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function newCDT($idReport){
        global $conn, $response;

        $cdtExisted = true;
        $newCDT = "";
        $stmt = $conn->prepare(QUERY_FETCH_CDT);
        while($cdtExisted){

            
            $newCDT  = generateRandomString();
            // var_dump($newCDT);
            $stmt->bind_param("s",$newCDT);
            $stmt->execute();
            
            if(count($stmt->fetch()) == 0){
                $cdtExisted = false;
                break;
            }

        }

        // var_dump("qui");
        $stmt = $conn->prepare(QUERY_NEW_CDT);
        $stmt->bind_param("ss",$newCDT,$idReport);
        $stmt->execute();
        
        $response['error'] = false;
        $response['message'] = $newCDT;
     
    }


?>