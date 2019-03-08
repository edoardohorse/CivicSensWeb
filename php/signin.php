<?php 
include_once("connect.php");
include_once("query.php");
global $conn;


$type = "Ente"; 
$pass = md5($_POST['pass']);

$stmt = $conn->prepare(QUERY_USER_SIGN_UP);
$stmt->bind_param("ssss", 
                    $_POST['email'],
                    $type,
                    $pass,
                    $_POST['city']);
$stmt->execute();

?>