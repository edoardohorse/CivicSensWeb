<?php 
    include_once('../classes/user.php');
    session_start();

    global $user;

    $_SESSION['user'] = new User();
    $user = &$_SESSION['user'];

    // var_dump($_SESSION);
?>