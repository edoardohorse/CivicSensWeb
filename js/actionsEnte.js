// ====== Actions for ente
const ACTION_OPTION_CHANGE_TEAM                 = new ActionOption('Cambia team',               ente.editTeam.bind(ente), "Assegna un'altro team a questa segnalazione")
const ACTION_OPTION_DELETE_REPORT               = new ActionOption('Elimina segnalazione',      ente.deleteReport.bind(ente), "Cancella la segnalazione permanentemente",true)


const ACTIONS_TEAM_REPORT_AS_WAITING = [
    ACTION_OPTION_CHANGE_TEAM,
    ACTION_OPTION_DELETE_REPORT
]

const ACTIONS_TEAM_REPORT_AS_INCHARGE = [
    ACTION_OPTION_CHANGE_TEAM,
    ACTION_OPTION_DELETE_REPORT
]
const ACTIONS_TEAM_REPORT_AS_DONE = [
    ACTION_OPTION_DELETE_REPORT
]