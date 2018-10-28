// ====== Actions for team

const ACTION_OPTION_SET_REPORT_AS_INCHARGE      = new ActionOption('Prendi in carica',        team.setReportAsInCharge.bind(team),      "Imposta la segnalazione come in lavorazione")
const ACTION_OPTION_SET_REPORT_AS_DONE          = new ActionOption('Chiudi segnalazione',     team.setReportAsDone.bind(team),          "Imposta la segnalazione come finita")
const ACTION_OPTION_UPDATE_HISTORY_REPORT       = new ActionOption('Aggiorna progresso nota', team.updateHistoryOfReport.bind(team),    "Inserisci una nota allo storico della segnalazione")
const ACTION_OPTION_RE_SET_REPORT_AS_INCHARGE   = new ActionOption('Riporta in lavorazione',  team.reSetReportAsInCharge.bind(team),    "Reimposta la segnalzione come in lavorazione")
const ACTION_OPTION_DELETE_REPORT               = new ActionOption('Elimina segnalazione',      team.deleteReport.bind(team), "Cancella la segnalazione permanentemente",true)
const ACTION_OPTION_DELETE_REPORTS              = new ActionOption('Elimina le segnalazione selezionate',      team.deleteReports.bind(team), "Cancella le segnalazioni permanentemente",true)

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
        ACTION_OPTION_RE_SET_REPORT_AS_INCHARGE,
        ACTION_OPTION_DELETE_REPORT
]