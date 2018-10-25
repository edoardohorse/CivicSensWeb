<?php 
    session_start();
    include_once('user.php');
    // var_dump($_SESSION);

    global $user;
    global $manger;

    if(!isset($_SESSION['user']))
        $_SESSION['user'] = new User();
    
    $user = &$_SESSION['user'];

    if(isset($_SESSION['manager']))
        $manager = &$_SESSION['manager'];
    // var_dump($user);

?>