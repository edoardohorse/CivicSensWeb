<!-- <form metohd="POST" action= "<?php $_SERVER['PHP_SELF'] ?>">
    <input type="email" name="email" value="edoardohorse@gmail.com">
    <input type="submit">
</form> -->
<?php
    
    $description = "descrizione";
    $cdt ="rfòknfàfbn";
    
    $to = "edoardohorse@gmail.com";
    $subject = "Report ricevuto";
    $message = "
        <html>
            <head>
                <title>Report ricevuto</title>
            </head>
            <body>
                <h4>Abbiamo ricevuto il tuo report con descrizione: <i>$description</i></h4>
                <h4>Il codice di tracking è qui riportato: <i>$cdt</i></h4>
            
                <h3>Ti ringraziamo per il tuo sostegno.</h3>    
            </body>
        </html>
        ";

    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';

    $headers[] = 'From: Team GERCS <civicsens@altervista.org>';

    mail($to,$subject,$message, implode("\r\n", $headers));


?>