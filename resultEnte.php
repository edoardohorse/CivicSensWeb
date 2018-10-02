<?php
    $grade = "";
    switch($result['grade']){
        case 'LOW': {$grade='bassa';break;}
        case 'INTERMEDIATE': {$grade='media';break;}
        case 'HIGH':{$grade='alta';break;}
    }
?>



        

            <article class="report" id="report<?php echo $result['id']?>">
            
            <p class="report__address">
                <label>Indirizzo :</label>
                <?php echo $result['address'] ?>
            </p>

            <p class="report__email">
                <label>Email :</label>
                <?php echo $result['email'] ?>
            </p>

            <p class="report__team">
                <label>Team :</label>
                <?php echo $result['typeReport'] ?>
            </p>

            <p class="report__description">
                <label>Descrizione :</label>
                <?php echo $result['description'] ?>
            </p>

            <p class="report__city">
                <label>Città :</label>
                <?php echo $result['city']['name'] ?>
            </p>

            <p class="report__state">
                <label>Stato :</label>
                <?php echo $result['state'] ?>
            </p>

            <p class="report__grade">
                <label>Grado :</label>
                <i class="report__grade__ball" title="Gravità <?php echo $grade?>" data-grade=<?php echo $result['grade'] ?>></i>
            </p>

            <a class="report__photo__opener" onclick="toggleReportPhoto('report<?php echo $result['id']?>')" href="#report<?php echo $result['id']?>" >Vedi foto</a>
            <div class="report__photo__wrapper">
                <div class="report__photo report__photo--hide">
                    
                    <?php
                        foreach($result['photos'] as $img){
                            echo "<img src=".$img['url'].">";
                        }
                    ?>
                </div>
            </div>
            </article>
