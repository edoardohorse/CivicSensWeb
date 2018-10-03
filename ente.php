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
	array_push($reports, new Report($row));
}

?>

<html>
    <head>
    <title>Report</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/perfect-scrollbar.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">

    </head>
    <body>
        
        <main class="list__report__wrapper">
            <nav class="nav__report">
                <div class="chooser__list__report chooser__list__report--selected" id="chooser--notfinished" onclick="showReportNotFinished(this)">Aperte</div>
                <div class="chooser__list__report"  id="chooser--finished" onclick="showReportFinished(this)">Chiuse</div>
            </nav>

		<div class="list__report" id="report--notfinished">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table100 ver1 m-b-110">
					<div class="table100-head">
						<table>
							<thead>
								<tr class="row100 head">
									<th class="cell100 column1">Città</th>
									<th class="cell100 column2">Indirizzo</th>
									<th class="cell100 column3">Descrizione</th>
									<th class="cell100 column4">Stato</th>
									<th class="cell100 column5">Tipo</th>
									<th class="cell100 column6">Grado</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="table100-body js-pscroll">
						<table>
							<tbody>
								
									
                                    

        <?php

            
            
            // Report da finire
            
            ;
            // echo '<aside class="list__report" id="report--notfinished">';
            for($i=0; $i< count($reports); $i++){
                echo '<tr class="row100 body">';
                $report= $reports[$i];
                // var_dump($report);
                if($report->getState() == ReportState::InLavorazione ||
                    $report->getState() == ReportState::InAttesa){
                    
                    include('resultEnte.php');

                }
                
                // var_dump($result['photo']);
                echo '</tr>';
			}
			
		
		?>
		
		</tbody>
</table>
</div>
</div>
</div>
</div>
		</div>
		
		<div class="list__report list__report--hide" id="report--finished"">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table100 ver1 m-b-110">
					<div class="table100-head">
						<table>
							<thead>
								<tr class="row100 head">
									<th class="cell100 column1">Città</th>
									<th class="cell100 column2">Indirizzo</th>
									<th class="cell100 column3">Descrizione</th>
									<th class="cell100 column4">Stato</th>
									<th class="cell100 column5">Tipo</th>
									<!-- <th class="cell100 column6">Team</th> -->
									<th class="cell100 column6">Grado</th>
									<!-- <th class="cell100 column8">Email</th> -->
								</tr>
							</thead>
						</table>
					</div>

					<div class="table100-body js-pscroll">
						<table>
							<tbody>
		


		<?php
		
		for($i=0; $i< count($reports); $i++){
			echo '<tr class="row100 body">';
			$report= $reports[$i];
			if($report->getState() == ReportState::Finito){                    
				include('resultEnte.php');
			}
			echo '</tr>';
		}
		
		?>

                            
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
       
</main>
</body>

<script src="js/script.js"></script>
</html>