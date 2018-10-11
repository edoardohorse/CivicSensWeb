<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php

$files = scandir("../../uploads");
unset($files[0]);
unset($files[1]);
var_dump($files);

for($i =2; $i < count($files)+2;$i++){
    $item = $files[$i];
    // var_dump($item);
    echo '<img src="uploads/'.$item.'">';
}

?>