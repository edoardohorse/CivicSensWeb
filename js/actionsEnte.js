// ====== Actions for report of ente
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

// ====== Actions for team of ente

const ACTION_OPTION_CHANGE_NAME_TEAM                 = new ActionOption('Modifica nome',      ente.changeTeamName.bind(ente), "Modifica il nome del team selezionato")
const ACTION_OPTION_DELETE_TEAM                 = new ActionOption('Elimina team',       ente.deleteTeam.bind(ente),        "Elimina definitivamente il team ridistribuendo i report associati", true)

const ACTION_OPTION_TEAM = [
    ACTION_OPTION_CHANGE_NAME_TEAM,
    ACTION_OPTION_DELETE_TEAM
]