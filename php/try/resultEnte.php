<?php
    $grade = "";
    switch($report->getGrade()){
        case 'LOW': {$grade='bassa';break;}
        case 'INTERMEDIATE': {$grade='media';break;}
        case 'HIGH':{$grade='alta';break;}
    }

?>

<td class="cell100 column1"><?php echo $report->getCity()?></td>
<td class="cell100 column2"><?php echo $report->getAddress() ?></td>
<td class="cell100 column3"><?php echo $report->getDescription() ?></td>
<td class="cell100 column4"><?php echo $report->getState() ?></td>
<td class="cell100 column5"><?php echo $report->getType() ?></td>
<!-- <td class="cell100 column6"><?php echo $report->getTeam() ?></td> -->
<td class="cell100 column6"><i class="report__grade__ball" title="Gravità <?php echo $grade?>" data-grade=<?php echo $report->getGrade()?>></i></td>
<!-- <td class="cell100 column8"><?php echo ($report->getUser() == "")?"Non fornita": $report->getUser() ?></td> -->
