// ====== Actions for ente
const ACTION_OPTION_DELETE_REPORT               = new ActionOption('Elimina segnalazione', team.manager.deleteReport.bind(team.manager), "Cancella la segnalazione permanentemente",true)
const ACTION_OPTION_CHANGE_TEAM                 = new ActionOption('Cambia team',               ()=>{console.log('Cambio team')}, "Assegna un'altro team a questa segnalazione")


// ====== Actions for team
// const ACTION_OPTION_DELETE_REPORT  => already defined ↑ ↑
const ACTION_OPTION_SET_REPORT_AS_INCHARGE      = new ActionOption('Prendi in carica',          ()=>{console.log('Prendi in carica')}, "Imposta la segnalazione come in lavorazione")
const ACTION_OPTION_SET_REPORT_AS_DONE          = new ActionOption('Chiudi segnalazione',       ()=>{console.log('Chiudi segnalazione')}, "Imposta la segnalazione come finita")
const ACTION_OPTION_UPDATE_HISTORY_REPORT       = new ActionOption('Aggiorna progresso nota',   ()=>{console.log('Aggiorna progresso nota')}, "Inserisci una nota allo storico della segnalazione")
const ACTION_OPTION_RE_SET_REPORT_AS_INCHARGE   = new ActionOption('Riporta in lavorazione',    ()=>{console.log('Riporta in lavorazione')}, "Reimposta la segnalzione come in lavorazione")

const ACTIONS_TEAM_REPORT_AS_WAITING = [
        ACTION_OPTION_SET_REPORT_AS_INCHARGE,
        ACTION_OPTION_DELETE_REPORT
]

const ACTIONS_TEAM_REPORT_AS_INCHARGE = [
        ACTION_OPTION_UPDATE_HISTORY_REPORT,
        ACTION_OPTION_SET_REPORT_AS_DONE,
        ACTION_OPTION_DELETE_REPORT
]
const ACTIONS_TEAM_REPORT_AS_DONE = [
        ACTIONS_TEAM_REPORT_AS_INCHARGE,
        ACTION_OPTION_DELETE_REPORT
]
