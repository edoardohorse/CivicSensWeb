// ============== GET
const URL_FETCH_ENTE            =  'apiReport/ente/'
const URL_FETCH_REPORTS_BY_ENTE =  'apiReport/ente/reports/'
const URL_FETCH_TEAMS_BY_ENTE   =  'apiReport/ente/teams/'

class Ente extends Admin{
    constructor(name){
        super(name)

        this.teams = []
        this.fetchTeams()
        // this.fetchInfo()
       
        // manager.drawTable()
    }

    fetchTeams(){
        Hub.connect(URL_FETCH_TEAMS_BY_ENTE, 'GET', null, {
            onsuccess: (result)=>{
                result = JSON.parse(result.response)
                // this.typeReports = result.map(t=>t.name)
                for(let team of result.data){
                    // debugger
                    this.teams.push( new Team(team.name, team.nMember, team.typeReport) )
                    
                }
                
               
            }
        })
        this.fetchInfo()
        
    }

    fetchInfo(){
        let callback = (reports)=>{
            this.teams.forEach(team=>{
                team.reports = reports.filter(rep=>rep.team == team.name)
                // console.log(this.teams.filter(p=>p.name == reports[0].team))
            })
            
        }
        manager.fetchAllReports(callback)
    }

    editTeam(){} // nome e numero di componenti

    addTeam(){}

    deleteTeam(){}  

   
}