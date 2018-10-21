// ====== Actions for ente
const ACTION_OPTION_DELETE_REPORT               = new ActionOption('Elimina segnalazione',      ()=>{console.log('Elimina segnalazione')}, "Cancella la segnalazione permanentemente",true)
const ACTION_OPTION_CHANGE_TEAM                 = new ActionOption('Cambia team',               ()=>{console.log('Cambio team')}, "Assegna un'altro team a questa segnalazione")


const ACTIONS_TEAM_REPORT_AS_WAITING = [
    ACTION_OPTION_DELETE_REPORT
]

const ACTIONS_TEAM_REPORT_AS_INCHARGE = [
    ACTION_OPTION_DELETE_REPORT
]
const ACTIONS_TEAM_REPORT_AS_DONE = [
    ACTION_OPTION_DELETE_REPORT
]