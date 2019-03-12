<?php
    include_once('../php/user.php');
    
    User::checkLogIn();
    // var_dump($_SESSION);

    include_once('../php/pageStart.php');
?>


    <aside id="nav__side">
        <p class="selected" >Segnalazioni</p>
        <form action="../php/destroy.php">
            <input type="submit" value="Esci">
        </form>
    </aside>
    <main class="list__wrapper" id="list__report__wrapper">
        <nav class="nav__report">
            <select id="select__search" onchange="adjustFilter(this)">
                <option value="Indirizzo">Indirizzo</option>
                <option value="Grado">Grado</option>
            </select>
            <input type="search" id="search__bar">
            <select id="select__search-grade" hidden>
                <option value="ALL">Tutti</option>
                <option value="LOW">Basso</option>
                <option value="INTERMEDIATE">Media</option>
                <option value="HIGH">Alta</option>
            </select>
            <div class="chooser__list__report chooser__list__report--selected" id="chooser--notfinished" onclick="showReportNotFinished(this)">Aperte</div>
            <div class="chooser__list__report"  id="chooser--finished" onclick="showReportFinished(this)">Chiuse</div>
            <i id="refresh__button"></i>
        </nav>

    </main>
    
    <main id="overlay"></main>


    <footer>
        <div class="recap" id="report__recap">
            <span class="recap__text"></span>
            <div class="recap__actions">
                <button >Elimina selezionate</button>
            </div>
        </div>
    </footer>
        
    <?php include_once('../php/pageEndTeam.php');?>