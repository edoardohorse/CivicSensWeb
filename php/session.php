<?php 
    include_once('user.php');
    session_start();

    global $user;

    $_SESSION['user'] = new User();
    $user = &$_SESSION['user'];

    // var_dump($_SESSION);
?>