
<?php

// include_once('../db/session.php');
// include_once('../classes/user.php');

// var_dump(ini_get('upload_max_filesize'));

// var_dump($_POST);
// var_dump($_GET);
// var_dump($_FILES);

$a = 3;
$b = &$a;

echo 'a = '.$a.'<br>';
echo 'b = '.$b.'<br>';

$b = 5;

echo 'a = '.$a.'<br>';
echo 'b = '.$b.'<br>';




?>
<!-- <form method="POST" action="../db/session.php">
    <button name="distruggi" value="Distruggi">Elimina sessione</button>
    <button name="crea" value="Distruggi">Crea sessione</button>
</form> -->