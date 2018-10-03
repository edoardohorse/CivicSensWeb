<?php
    $grade = "";
    switch($report->getGrade()){
        case 'LOW': {$grade='bassa';break;}
        case 'INTERMEDIATE': {$grade='media';break;}
        case 'HIGH':{$grade='alta';break;}
    }

?>



        

            <article class="report" id="report<?php echo $report->getId()?>">
            
            <p class="report__address">
                <label>Indirizzo :</label>
                <?php echo $report->getAddress() ?>
            </p>

            <p class="report__email">
                <label>Email :</label>
                <?php echo $report->getUser() ?>
            </p>

            <p class="report__team">
                <label>Team :</label>
                <?php echo $report->getType() ?>
            </p>

            <p class="report__description">
                <label>Descrizione :</label>
                <?php echo $report->getDescription() ?>
            </p>

            <p class="report__city">
                <label>Città :</label>
                <?php echo $report->getCity()?>
            </p>

            <p class="report__state">
                <label>Stato :</label>
                <?php echo $report->getState() ?>
            </p>

            <p class="report__grade">
                <label>Grado :</label>
                <i class="report__grade__ball" title="Gravità <?php echo $grade?>" data-grade=<?php echo $report->getGrade()?>></i>
            </p>

            <a class="report__photo__opener" onclick="toggleReportPhoto('report<?php echo $report->getId() ?>')" href="#report<?php echo $report->getId()?>" >Vedi foto</a>
            <div class="report__photo__wrapper">
                <div class="report__photo report__photo--hide">
                    
                    <?php
                        foreach($report->getPhotos() as $img){
                            echo "<img src=uploads/".$img.">";
                        }
                    ?>
                </div>
            </div> 
            </article>
