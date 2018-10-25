<?php

    require_once('session.php');

    define('PATH_ENTE','../page/ente.php');
    define('PATH_TEAM','../page/team.php');
    $page = null;

    
    // var_dump($_POST);
    // var_dump($_SESSION);

    $email = isset($_POST['email'])? $_POST['email'] : null;
    $pass = isset($_POST['pass'])? $_POST['pass'] : null;
    

    $user->setEmail($email);


    if($user->getEmail()){      // Se ha un account...

        if($user->getType() != TypeUser::User){     // ... ed è un team o un ente

        if($user->checkPass($pass)){             // controllo la password
                $user->signIn();                    // logga se è giusta
                echo MessageLogin::USER_LOGGED_IN;
            }
            else{
                echo MessageLogin::PASSWORD_WRONG;
                die();
            }
        }

    }

    switch($user->getType()){
        case TypeUser::Ente:{
            $page = PATH_ENTE;
            break;
        }
        case TypeUser::Team:{
            $page = PATH_TEAM;
            break;
        }
    }
    
    // var_dump($user);


    if($page)
        header('location: '.$page);



?>