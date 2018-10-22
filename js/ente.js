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

        this.refresh = (result)=>{
            result = JSON.parse(result.response)
            vex.dialog.alert({
                message: result.message                
            })

            manager.fetchAllReports()
        }
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

    
    editTeam(id = null){ 
        
        if(id == null){
            id = manager.reportLastSelected.id
        }

        let reportToEdit = manager.reports.find(rep=> rep.id==id)

        let callback = (result)=>{
            result = JSON.parse(result.response)
                vex.dialog.alert({
                        message: result.message
                })
            
            manager.fetchInfoOfReport(manager.reportLastSelected)
        }

        let teamFiltered = this.teams.filter(team=>team.typeReport==reportToEdit.type).map(team=>team.name)
        teamFiltered.splice(teamFiltered.indexOf(reportToEdit.team),1)

        
        
        let chooser = newEl('select,,,name=select')
                    .appendChildren(
                        repeatEl('option', teamFiltered.length ,
                            {
                            textContent:teamFiltered,
                            value:teamFiltered
                            }
                            ))
            

        vex.dialog.open({
            message:'Scegli un nuovo gruppo',
            input: chooser,
            callback: function(data){
                if(data && data.select){
                    manager.reportLastSelected.editTeam(data.select)
                }
                
            }.bind(this)
        })

        reportToEdit.tmpHub.onsuccess = callback.bind(this)
    }

    addTeam(){}

    deleteTeam(){}  


   
}