// ============== GET
const URL_FETCH_ENTE            =  '../apiReport/ente/'
const URL_FETCH_REPORTS_BY_ENTE =  '../apiReport/ente/reports/'
const URL_FETCH_TEAMS_BY_ENTE   =  '../apiReport/ente/teams/'

class Ente extends Admin{
    constructor(name){
        super(name)

        this.teams = []
        this.fetchTeams()
        this.tableTeam = new TableTeam('list__team__wrapper')
        this.teamsSelected = null
        this.teamsLastSelected = null
        this.checkboxes = []
        this.recapText = document.querySelector('.report__recap__text')
        this.detail = new Details()
        this.detail.addRow(
            {
                label   :'Tipo',
                content :newEl('span, team__type'),
                divided :true,
                col     :12,
            },
            {
                label   :'Numero membri',
                content :newEl('span,team__member'),
                divided :true,
                col     :12,
            }
        )

        this.detail.build()
        managerDet.addDetails(this.detail)


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
                
               this.drawTableTeam();
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

    deselectLastTeam(){
        this.teamsLastSelected.el.classList.remove('tr--selected')
        this.teamsLastSelected = null
    } 

    selectLastTeam(team){
        if(!this.isMultipleSelection){
        if(this.teamsLastSelected)
            this.deselectLastTeam()

        this.teamsLastSelected = team
        this.teamsLastSelected.el.classList.add('tr--selected')    
        this.showTeam(this.teamsLastSelected)
    }}

    selectTeam(team){
        if(!this.teamsSelected)
        this.teamsSelected = []

        this.teamsSelected.push(team)
        team.el.classList.add('tr--selected')    
        this.showRecap()        
        this.recapText.textContent = `Selezionate: ${this.teamsSelected.length}`
    }

    deselectTeam(team){
        let index =  this.teamsSelected.indexOf(team)
        this.teamsSelected[index].el.classList.remove('tr--selected') 
        this.teamsSelected.splice(index, 1)
        if(this.teamsSelected.length == 0){
            this.teamsSelected = null
            this.hideRecap()
        }
        
        if(this.teamsSelected)
            this.recapText.textContent = `Selezionate: ${this.teamsSelected.length}`}

    drawTableTeam(){
        this.teams.forEach(team=>{
            
            let row = this.tableTeam.addRow(team)
            // debugger
            this.checkboxes.push(row.children[0].querySelector('input'))
            let cells = Array.from(row.children)
            cells.shift();

            cells.forEach(cell=>{
                cell.addEventListener('click', this.selectLastTeam.bind(this,team))
            })
            row.children[0].addEventListener('click',(event)=>{
                // debugger
                let target = null
                

                if(this.teamLastSelected)
                    this.deselectLastteam()
                
                if(event.target.checked != null){       // Trigger sull'input
                    target = event.target
                }
                else{
                    target = event.target.querySelector('input')
                    target.checked = !target.checked
                }

                if(target.checked){
                    this.isMultipleSelection = true
                    this.selectTeam(target.parentElement.parentElement.team)
                }
                else{
                    if(this.nTeamSelected == 0)
                        this.isMultipleSelection = false
                        let index = this.teamsSelected.indexOf(target.parentElement.parentElement.team)
                        this.deselectTeam(this.teamsSelected[index])
                        
                }

                event.stopPropagation()
                
            })

        })
    }

    showTeam(team){
        var options = ACTION_OPTION_TEAM

        this.detail.setTitle(team.name,options)
        this.detail.el.setAttribute('data-team',true)
        this.detail.build()
        
        managerDet.show(this.detail, this.deselectLastTeam.bind(this))
        
        let teamMember          = document.getElementById('team__member')
        let teamType            = document.getElementById('team__type')

        teamType.textContent    = team.typeReport
        teamMember.textContent  = team.nMember
      
        // console.log(team)
    }

    hideRecap(){
        document.querySelector('footer').style.display="none"
    }

    showRecap(){
        document.querySelector('footer').style.display="block"
    }
   
    changeTeamName(){

    }
}