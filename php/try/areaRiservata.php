<?php

include_once('../classes/user.php');

User::checkLogIn();

echo 'Sei nell\'area riservata';

privata(Permission::Ente);

function privata($perm){
    $_SESSION['user']->checkPermission($perm);
    echo '<br>Funzione privata: '.$perm;
}

?>