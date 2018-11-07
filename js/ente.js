// ============== GET
const URL_FETCH_ENTE            =  '../api/ente/'
const URL_FETCH_REPORTS_BY_ENTE =  '../api/ente/reports/'
const URL_FETCH_TEAMS_BY_ENTE   =  '../api/ente/teams/'

// ============== POST
const URL_ADD_TEAM           =  '../api/ente/team/new'
const URL_DELETE_TEAM           =  '../api/ente/team/delete'
const URL_CHANGE_NAME_TEAM   =  '../api/team/name'

class Ente extends Admin{
    constructor(name){
        super(name)

        this.tableTeam = new TableTeam('list__team__wrapper')
        
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

        this.init()
        this.fetchTeams()

        this.refresh = (result)=>{
            result = JSON.parse(result.response)
            vex.dialog.alert({
                message: result.message                
            })

            manager.fetchAllReports()
        }

        let button = document.querySelector('.team__recap button')
        button.onclick = this.addTeam.bind(this)
        button.title = "Crea un nuovo team"
    }

    init(){
        this.teams = []
        
        this.teamsSelected = null
        this.teamsLastSelected = null
        this.checkboxes = []
    }

    fetchTeams(){

        this.init()
        this.tableTeam.deleteAllRows()

        Hub.connect(URL_FETCH_TEAMS_BY_ENTE, 'GET', null, {
            onsuccess: (result)=>{
                result = JSON.parse(result.response)
                // this.typeReports = result.map(t=>t.name)
                for(let team of result.data){
                    // debugger
                    this.teams.push( new Team(team.name, team.nMember, team.typeReport) )
                    
                }

                let listTypeReport = this.teams.map(m=>m.typeReport)

                listTypeReport = listTypeReport.filter(function(item, pos) {
                    return listTypeReport.indexOf(item) == pos;
                })

                searchType.innerHTML = ""
                newEl('option,,, value="ALL" textContent="Tutti"',searchType)
                listTypeReport.forEach((type)=>{
                    newEl(`option,,, value="${type}" textContent="${type}"`, searchType)
                    
                })
                
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

    addTeam(){

        let listNameTeams = this.teams.map(m=>m.name)

        let listTypeReport = this.teams.map(m=>m.typeReport)

        listTypeReport = listTypeReport.filter(function(item, pos) {
            return listTypeReport.indexOf(item) == pos;
        })

        let form = newEl('div') 
        newEl('input,,, name=name type=text placeholder="Nome team"', form)
        newEl('input,,, name=pass type=password placeholder="Password"', form)
        newEl('select,,, name=type', form).
                appendChildren(
                    repeatEl('option', listTypeReport.length ,
                    {
                        textContent:listTypeReport,
                        value:listTypeReport
                    })
                )
        newEl('input,,, name="member" type=number value=1 min=1 max=15', form)

        vex.dialog.open({
            message: 'Aggiungi team',
            input:form,
            callback:function(data){
                
                if(data){
                    if(data.member && data.name && data.type && data.pass){
                        if(listNameTeams.indexOf(data.name) > -1){
                            vex.dialog.alert('Nome team già usato')    
                        }
                        else{
                            let team = new Team(data.name, data.type, data.member)
                            Hub.connect(URL_ADD_TEAM, 'POST',{name:data.name, type:data.type, member:data.member, pass:data.pass},{
                                onsuccess:(result)=>{
                                    
                                    result = JSON.parse(result.response)
                                    vex.dialog.alert(result.message)

                                    this.fetchTeams();
                                }
                            })
                        }
                    }
                    else{
                        vex.dialog.alert('Compila tutti i campi')
                    }
                }
            }.bind(this)
        })
    }

    deleteTeam(){

        // debugger
        let teamToDelete = this.teamsLastSelected
        let listTeamsByType = this.teams.filter(t=>{ return teamToDelete != t? t.typeReport == teamToDelete.typeReport: false})
        // let form = newEl('div')
        // newEl('select,,, name=name', form).listTeamsByType
        //         appendChildren(
        //             repeatEl('option', listNameTeams.length ,
        //             {
        //                 textContent:listNameTeams,
        //                 value:listNameTeams
        //             })
        //         )

        vex.dialog.confirm({
            message:'Sicuro di voler eliminare il team definitivamente?',
            callback:function(data){
                if(data){
                    // debugger
                    
                    Hub.connect(URL_DELETE_TEAM, 'POST',{team:teamToDelete.name},{
                        onsuccess:(result)=>{
                            
                            result = JSON.parse(result.response)
                            console.log(result.data)

                            // debugger

                            let div = newEl('div')
                            let tableResult = new TableTeamResult('list__result', div)
                            for(let team of listTeamsByType){
                                
                                let nOldReport = team.reports.length
                                let nNewReport = result.data[team.name]
                                if(nOldReport == nNewReport)
                                    tableResult.addRow(team.name, nOldReport, nNewReport  )
                                else
                                    tableResult.addRow(team.name,
                                        newEl('span,,, style="color:red;"').call('textContent',nOldReport),
                                        newEl('span,,, style="color:green;"').call('textContent',nNewReport)  )
                            }
                            

                            vex.dialog.alert({
                                message: 'Modifica effettuate',
                                input: div
                            })

                            


            
                            this.fetchTeams();
                        }
                    })
                    


                }
            }.bind(this)
        })

        

    }  

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


    deselectTeam(team){
        let index =  this.teamsSelected.indexOf(team)
        this.teamsSelected[index].el.classList.remove('tr--selected') 
        this.teamsSelected.splice(index, 1)
        if(this.teamsSelected.length == 0){
            this.teamsSelected = null
            this.hideTeamRecap()
        }
        
        if(this.teamsSelected)
            this.recapText.textContent = `Selezionate: ${this.teamsSelected.length}`}

    drawTableTeam(){
        this.teams.forEach(team=>{
            
            let row = this.tableTeam.addRow(team)
            // debugger
            
           row.addEventListener('click', this.selectLastTeam.bind(this,team))
            
            

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

    hideTeamRecap(){
        document.querySelector('footer').style.display="none"
        document.querySelector('.team__recap').style.display="none"
    }

    showTeamRecap(){
        document.querySelector('footer').style.display="block"
        document.querySelector('.team__recap').style.display="block"
    }
   
    changeTeamName(){
        let teamToChangeName = this.teamsLastSelected
        let listNameTeams = this.teams.map(m=>m.name)
        let form = newEl('div')
        newEl('span', form).call('textContent', 'Attuale:  '+teamToChangeName.name)
        newEl('input,,, type=text name=name placeholder="Nuovo nome"', form)

        vex.dialog.open({
            message:'Modifica nome',
            input:form,
            callback:function(data){
                if(data && data.name){
                    // debugger
                    if(listNameTeams.indexOf(data.name) > -1){
                        vex.dialog.alert('Nome team già usato')    
                    }
                    else{
                        Hub.connect(URL_CHANGE_NAME_TEAM, 'POST',{newName:data.name, name:teamToChangeName.name},{
                            onsuccess:(result)=>{
                                
                                result = JSON.parse(result.response)
                                vex.dialog.alert(result.message)
            
                                this.fetchTeams();
                            }
                        })
                    }


                }
            }.bind(this)
        })

        
    }

    deleteReport(){
        
        let reportToDelete =manager.reportLastSelected

        reportToDelete.tmpHub.onsuccess = this.fetchInfo.bind(this)
        reportToDelete.deleteReport();

        managerDet.hide();
    }
}