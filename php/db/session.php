<?php 
include_once('../classes/user.php');
session_start();


// $_SESSION['user'] = new User('edoardohorse@gmail.com');
$_SESSION['user'] = new User();

switch($_SESSION['user']->getType()){
    case TypeUser::User:{
        $_SESSION['isAdmin'] = false;
        break;
    }
    case TypeUser::Ente:
    case TypeUser::Team:{
        $_SESSION['isAdmin'] = true;
        break;
    }
}

function isAdmin(){return $_SESSION['isAdmin'];}

var_dump($_SESSION);

function closeSession(){
    session_unset();
    session_destroy();
}





// include_once('../db/session.php');
/* include_once('../classes/user.php');

if(isset($_POST['distruggi'])){
    distruggiSessione();
}

if(isset($_POST['crea'])){
    creaSessione();
}

function creaSessione(){
    session_start();

    
    $_SESSION['user'] = new User('edoardohorse@gmail.com');
    // $_SESSION['user'] = new User();

    switch($_SESSION['user']->getType()){
        case TypeUser::User:{
            $_SESSION['isAdmin'] = false;
            break;
        }
        case TypeUser::Ente:
        case TypeUser::Team:{
            $_SESSION['isAdmin'] = true;
            break;
        }
    }

    function isAdmin(){return $_SESSION['isAdmin'];}



    header('Location: '.'../try/prova.php');
}

function distruggiSessione(){
    creaSessione();
    session_unset();
    session_destroy();

    header('Location: '.'../try/prova.php');
} */



?>