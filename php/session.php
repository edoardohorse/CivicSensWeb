<?php 
    require_once('user.php');
    session_start();
    // var_dump($_SESSION);

    global $user;
    global $manger;

    
    if(!isset($_SESSION['user']))
        $_SESSION['user'] = new User();
    
    $user = &$_SESSION['user'];

    if(isset($_SESSION['manager']))
        $manager = &$_SESSION['manager'];

    
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(stripos($ua,'okhttp') !== false) { // && stripos($ua,'mobile') !== false) {
        $_SESSION['user']->setType(TypeUser::User);
    }
    // echo json_encode($_SERVER['HTTP_USER_AGENT']);die();
    

?>