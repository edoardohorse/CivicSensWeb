<html>
    <head>
    <title>Report</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    </head>
    <body>
        
        <main class="list__report__wrapper">
            <nav class="nav__report">
                <div class="chooser__list__report chooser__list__report--selected" id="chooser--notfinished" onclick="showReportNotFinished(this)">Aperte</div>
                <div class="chooser__list__report"  id="chooser--finished" onclick="showReportFinished(this)">Chiuse</div>
            </nav>

        <?php

            include_once("query.php");
            include_once("response.php");
            include_once("connect.php");


            
            
            getReportByCity('Grottaglie');
            

            // Report da finire
            echo '<aside class="list__report" id="report--notfinished">';
            foreach($response['report'] as $key=>$value){
                $result = $response['report'][$key];
                if($result['state'] == ReportState::InLavorazione ||
                    $result['state'] == ReportState::InAttesa){
                    
                    getPhotosByReport($result['id']);
                    $result['photos'] = $response['photos'];

                    include('resultEnte.php');

                }
                // var_dump($result);
                // var_dump($result['photo']);
            }
            echo '</aside>';

            echo '<aside class="list__report list__report--hide" id="report--finished">';
            foreach($response['report'] as $key=>$value){
                $result = $response['report'][$key];
                if($result['state'] == ReportState::Finito){
                    
                    getPhotosByReport($result['id']);
                    $result['photos'] = $response['photos'];

                    include('resultEnte.php');
                }

                // var_dump($result);
                // var_dump($result['photo']);
            }
            echo '</aside>';
            // var_dump($response);








        ?>

       
</main>
</body>
<script src="script.js"></script>
</html>