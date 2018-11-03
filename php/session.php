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

    
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(stripos($ua,'android') !== false) { // && stripos($ua,'mobile') !== false) {
        $_SESSION['user']->setType(TypeUser::User);
    }
    // var_dump($_SERVER['HTTP_USER_AGENT']);die();
    

?>