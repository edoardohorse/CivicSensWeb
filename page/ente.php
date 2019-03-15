<?php
    include_once('../php/user.php');

    User::checkLogIn();


    include_once('../php/pageStart.php');
?>

        
    
    <aside id="nav__side">
        <p class="selected" onclick="showReportsEnte(this)" >Segnalazioni</p>
        <p                  onclick="showTeamsEnte(this)"   >Team</p>
        <p                  onclick="showEditTypeReport(this)"   >Tipo report</p>
        <form action="../php/destroy.php">
            <input type="submit" value="Esci">
        </form>
    </aside>
    <main class="list__wrapper list--hide" id="list__report__wrapper">
        <nav class="nav__report">
                <select id="select__search" onchange="adjustFilter(this)">
                <option value="Indirizzo">Indirizzo</option>
                <option value="Grado">Grado</option>
                <option value="Tipo">Tipo</option>
            </select>
            <select id="select__search-grade" hidden>
                <option value="ALL">Tutti</option>
                <option value="LOW">Basso</option>
                <option value="INTERMEDIATE">Media</option>
                <option value="HIGH">Alta</option>
            </select>
            <select id="select__search-type" hidden>
            </select>
            <input type="search" id="search__bar">
            <div class="chooser__list__report chooser__list__report--selected" id="chooser--notfinished" onclick="showReportNotFinished(this)">Aperte</div>
            <div class="chooser__list__report"  id="chooser--finished" onclick="showReportFinished(this)">Chiuse</div>
            <i id="refresh__button"></i>
        </nav>

    

                                        
                                
    </main>
    <main class="list__wrapper list--hide" id="list__team__wrapper">
        
    </main>
    
    <main class="list__wrapper list--hide" id="list__type-report__wrapper">
        
    </main>

    <main id="overlay"></main>

    <footer>
        <div class="recap" id="report__recap">
            <span class="recap__text"></span>
            <div class="recap__actions">
                <button >Elimina selezionate</button>
            </div>
        </div>

        <div class="recap" id="team__recap">
            <span class="recap__text"></span>
            <div class="recap__actions">
                <button >Nuovo team</button>
            </div>
        </div>
        <div class="recap" id="type__recap">
            <span class="recap__text"></span>
            <div class="recap__actions">
                <button id="addTypeReport">Aggiungi tipo</button>
                <button id="removeTypeReport">Rimuovi tipo</button>
            </div>
        </div>
    </footer>


    <?php include_once('../php/pageEndEnte.php');?>