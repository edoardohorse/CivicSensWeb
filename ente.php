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
            include_once("connect.php");
            include_once("report.php");

            $reports = array();
            $city = 'Grottaglie';
            $stmt = $conn->prepare(QUERY_REPORT_BY_CITY);
            $stmt->bind_param("s",$city);
            
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            
            while($row = $result->fetch_assoc()){
                // var_dump($row);
                array_push($reports, new Report($row));
            }
            
            // Report da finire
            
            ;
            echo '<aside class="list__report" id="report--notfinished">';
            for($i=0; $i< count($reports); $i++){
                $report= $reports[$i];
                // var_dump($report);
                if($report->getState() == ReportState::InLavorazione ||
                    $report->getState() == ReportState::InAttesa){
                    
                    include('resultEnte.php');

                }
                
                // var_dump($result['photo']);
            }
            echo '</aside>';

            echo '<aside class="list__report list__report--hide" id="report--finished">';
            for($i=0; $i< count($reports); $i++){
                $report= $reports[$i];
                if($report->getState() == ReportState::Finito){                    
                    include('resultEnte.php');
                }
            }
            echo '</aside>';

        ?>

       
</main>
</body>
<script src="script.js"></script>
</html>