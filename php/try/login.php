<?php

include_once('../classes/user.php');
session_start();


$email = isset($_POST['email'])? $_POST['email'] : null;
$pass = isset($_POST['pass'])? $_POST['pass'] : null;


$user = new User( $email );

if($user->getEmail()){      // Se ha un account...

    if($user->getType() != TypeUser::User){     // ... ed è un team o un ente


       if($user->checkPass($pass)){             // controllo la password
            $user->signIn();                    // logga se è giusta
            echo MessageLogin::USER_LOGGED_IN;
        }
        else{
            echo MessageLogin::PASSWORD_WRONG;
        }
    }

}



$_SESSION['user'] = $user;

// var_dump($_POST);
var_dump($_SESSION);


?>