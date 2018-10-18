// ============== GET
const URL_FETCH_REPORTS_BY_ENTE =  'apiReport/ente/'

class Ente extends Admin{
    constructor(name){
        super(name)

        this.teams = []
        this.fetchTeams()
    }

    fetchTeams(){
        Hub.connect(URL_FETCH_REPORTS_BY_ENTE, 'GET', null, {
            onsuccess: (result)=>{
                result = JSON.parse(result.response)
                // this.typeReports = result.map(t=>t.name)
                for(let type of result.data){

                    for(let team of type.teams){
                        this.teams.push( new Team(team.name, team.nMember, team.reports) )
                    }

                }
            }
        })
    }

    editTeam(){} // nome e numero di componenti

    addTeam(){}

    deleteTeam(){}  


}