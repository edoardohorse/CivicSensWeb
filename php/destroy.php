<?php


session_start();
session_destroy();

?>

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