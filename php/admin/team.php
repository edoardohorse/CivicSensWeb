<?php
    include_once('../classes/user.php');

    User::checkLogIn();

    include_once('pageStart.php');
?>



    <nav>
        prova
        <form action="session.php">
    </nav>
    <aside class="nav__side">
        <p>Segnalazioni</p>
        <p>Team</p>
    </aside>
    <main class="list__report__wrapper">
        <nav class="nav__report">
            <select id="select__search">
                <option value="Indirizzo">Indirizzo</option>
                <option value="Città">Città</option>
                <option value="Grado">Grado</option>
                <option value="Tipo">Tipo</option>
            </select>
            <input type="search" id="search__bar">
            <div class="chooser__list__report chooser__list__report--selected" id="chooser--notfinished" onclick="showReportNotFinished(this)">Aperte</div>
            <div class="chooser__list__report"  id="chooser--finished" onclick="showReportFinished(this)">Chiuse</div>
            <i id="refresh__button"></i>
        </nav>

    </main>
       
        
        
    <?php include_once('pageEndEnte.php');?>