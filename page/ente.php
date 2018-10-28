<?php
    include_once('../php/user.php');

    User::checkLogIn();


    include_once('../php/pageStart.php');
?>

        
    <nav>
            <span  onclick="showNavSide()">prova</span>
            <form action="../php/destroy.php">
            <input type="submit" value="Esci">
        </form>
    </nav>
    <aside id="nav__side">
        <p class="selected" onclick="showReportsEnte(this)">Segnalazioni</p>
        <p onclick="showTeamsEnte(this)">Team</p>
    </aside>
    <main class="list__wrapper list--hide" id="list__report__wrapper">
        <nav class="nav__report">
                <select id="select__search">
                <option value="Indirizzo">Indirizzo</option>
                <option value="Città">Data</option>
                <option value="Grado">Grado</option>
                <option value="Tipo">Tipo</option>
            </select>
            <input type="search" id="search__bar">
            <div class="chooser__list__report chooser__list__report--selected" id="chooser--notfinished" onclick="showReportNotFinished(this)">Aperte</div>
            <div class="chooser__list__report"  id="chooser--finished" onclick="showReportFinished(this)">Chiuse</div>
            <i id="refresh__button"></i>
        </nav>

    

                                        
                                
    </main>
    <main class="list__wrapper list--hide" id="list__team__wrapper">
        
    </main>

    <main id="overlay"></main>

    <footer>
        <div class="report__recap">
            <span class="report__recap__text"></span>
            <div class="report__recap__actions">
                <button >Elimina selezionate</button>
            </div>
        </div>
    </footer>


    <?php include_once('../php/pageEndEnte.php');?>