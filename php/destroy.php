<?php


session_start();
session_destroy();
foreach($_COOKIE AS $key => $value) {
    SETCOOKIE($key,$value,TIME()-10000,"/");
}

?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<script>

    redirect('Reindirizzamento', '../index.html',500, 0)

    function redirect(str, url, interval, delay){
        document.writeln(str);
    
        setInterval(()=>{
            document.body.innerHTML +='.'
        },interval)

        setTimeout(() => {
            location.href = url
        }, delay);
    }

    
</script>