<?php

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','root');
define('DB_NAME','my_civicsens');

define('UPLOAD_PATH', 'uploads/');


$conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die ('Could not connect to the database server' . mysqli_connect_error());
$conn->set_charset("utf8");
// var_dump($_GET);
// var_dump($_POST);
// var_dump($_FILES);


$request =explode('/',$_SERVER['REQUEST_URI']);

if($_SERVER["SERVER_NAME"] == "192.168.1.181" ||
    $_SERVER["SERVER_NAME"] == "localhost" ){
    array_shift($request);   
}
// var_dump($request);
// var_dump($_SERVER);
// echo '<pre>' . var_export($request, true) . '</pre>';
array_shift($request);
array_shift($request);
// echo '<pre>' . var_export($request, true) . '</pre>';
// var_dump($request);
// $response = array();





//$con->close();

//$con->close();



?>