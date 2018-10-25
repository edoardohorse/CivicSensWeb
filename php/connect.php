<?php

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','my_civicsens');

define('UPLOAD_PATH', 'uploads/');


$conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die ('Could not connect to the database server' . mysqli_connect_error());
$conn->set_charset("utf8");
// var_dump($_GET);
// var_dump($_POST);
// var_dump($_FILES);


$request =explode('/',$_SERVER['REQUEST_URI']);

if(substr($_SERVER["SERVER_NAME"],0,3) == "192" ||
    $_SERVER["SERVER_NAME"] == "localhost" ){
    array_shift($request);   
}

array_shift($request);
array_shift($request);


?>