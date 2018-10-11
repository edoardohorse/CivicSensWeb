class Team{
    constructor(name){
        this.name = name
        this.manager = new ManagerReport(URL_FETCH_REPORTS_BY_TEAM, this.name)
        this.manager.fetchAllReports()

    }
}