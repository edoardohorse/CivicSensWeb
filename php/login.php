<?php

    require_once('session.php');
    include_once('ente.php');
    include_once('team.php');

    define('PATH_ENTE','../page/ente.php');
    define('PATH_TEAM','../page/team.php');
    $page = null;


    
    // var_dump($_POST);
    

    if(!$user->isLogged()){


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
                $_SESSION['manager'] = new Ente($user->getEmail());
                $manager = &$_SESSION['manager'];
                break;
            }
            case TypeUser::Team:{
                $page = PATH_TEAM;
                $_SESSION['manager'] = new Team($user->getEmail());
                // var_dump($_SESSION);
                $manager = &$_SESSION['manager'];
                break;
            }
        }
        
        // var_dump($user);
        // var_dump($manager);


        if($page)
            header('location: '.$page);
    }
    else{
        header('location: destroy.php');
    }

?>