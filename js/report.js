// ============== GET
const URL_FETCH_REPORTS_BY_CITY =  '../apiReport/report/city/{#}/'
const URL_FETCH_REPORTS_BY_TEAM =  '../apiReport/report/team/{#}/'
const URL_FETCH_LIST_TEAM       =  '../apiReport/team/'
const URL_FETCH_REPORT_BY_ID    =  '../apiReport/report/id/{#}/'
const URL_FETCH_PHOTOS_REPORT   =  '../apiReport/report/photos/{#}/'
const URL_FETCH_HISTORY_REPORT  =  '../apiReport/report/history/{#}/'
const URL_DELETE_REPORT         =  '../apiReport/report/delete/{#}/'
const URL_DELETE_REPORTS         =  '../apiReport/report/delete'


// ============== POST
const URL_EDIT_TEAM_REPORT      =  '../apiReport/report/{#}/team/'
const URL_UPDATE_HISTORY_REPORT =  '../apiReport/report/{#}/history/'
const URL_EDIT_STATE_REPORT     =  '../apiReport/report/{#}/state/'


const ReportState = {Waiting:'In attesa', InCharge:'In lavorazione', Done:'Finito'}
Object.freeze(ReportState)

const CLASS_ROW_HIDDEN = 'row--hide'


const searchBar                 = document.getElementById('search__bar')
const selectSearch              = document.getElementById('select__search')
const refreshButton             = document.getElementById('refresh__button')
const detailsAside              = document.getElementById('details')


function substitute(str,param = []){
    param.forEach(p=>{
        str = str.replace('{#}',p.toString())
    })
    return str
}


class ManagerReport{

    set tableSelected(table){
        if(table === this.tableReportFinished){
            this._tableSelected = this.tableReportFinished
            this._tableSelected.show()
            this.tableReportNotFinished.hide()
        }
        else{
            this._tableSelected = this.tableReportNotFinished
            this._tableSelected.show()
            this.tableReportFinished.hide()
        }
    }

    get nReportSelected(){ return this.checkboxes.filter(this.checkSelected) }

    constructor(url = null){
        this.reports = []
        this.hub = new Hub(url, "GET") 
        this.reportsSelected = null
        this.checkboxes = []
        this.reportLastSelected = null
        this.isMultipleSelection = false
        this.tableReportNotFinished = new TableReport('report--notfinished')
        this.tableReportFinished = new TableReport('report--finished')
        this.recapText = document.querySelector('.report__recap__text')
        
        
        this.tableSelected = this.tableReportNotFinished

        this.detail = new Details()
        // this.detail.setTitle("Via Socrate, 15",[1,2])
        this.detail.addRow(             // report__date
            {
                label   :'Creato il',
                content :newEl('span, report__date'),
                divided : true,
                col     : 6
            },
            {
                label   :'Stato',
                content : newEl('div, report__state,report__badge'),
                divided : true,
                col     : 6,
                divided : false
            }
    
        )
        this.detail.addRow({
                label   :'Tipo',
                content :newEl('span, report__type'),
                divided : true,
                col     : 12
            })
    
        this.detail.addRow({
                label   :'Team',
                content :newEl('span,report__team'),
                divided : true,
                col     : 12
                })
        
        this.detail.addRow({
                label   :'Descrizione',
                content : newEl({
                                    el:'textarea',
                                    id:'report__description',
                                    thieclass:'col-12',
                                    data:{
                                        disabled:true
                                    }
                                }),
                divided : true,
                col     : 12
            },)
        this.detail.addRow({
                label   :'Foto',
                content : newEl('div, report__photos,photo'),
                divided : true,
                col     : 12,
                slider  : true
            })

        this.detail.addRow({
                label   :'Storico',
                content : newEl('div,report__history'),
                divided : true,
                col     : 12
            })
    

        
        this.detail.build()
        this.detail.el.setAttribute('data-grade','LOW')

        managerDet.addDetails(this.detail)

        refreshButton.addEventListener('click',this.fetchAllReports.bind(this,null,event))
        searchBar.addEventListener('keyup', this.searchBy.bind(this))
    }

    fetchAllReports(...param){
        let [callback = null, reports = null] = param
        this.tableReportFinished.deleteAllRows()
        this.tableReportNotFinished.deleteAllRows()
        this.reports = []

        var importRep = (reports)=>{
            reports.forEach(report=>{
                this.reports.push(new Report(report))
            })
            if(callback)
                callback(this.reports)
            this.drawTable()
        }

        this.hub.onsuccess = (result) => {
            let reports

            let res  = JSON.parse(result.response)
            if(res.error){
                console.log('errore: '+res.message)
            }
            else{
                reports = res.data
            }
                    
            importRep(reports)
            
            if(this.reportLastSelected){
                // Aggiorno anche quello segnalato
                this.reportLastSelected = this.reports.find(rep=>rep.id==this.reportLastSelected.id)
                this.showReport.call(this,this.reportLastSelected)
            }
                
            
        }
        
        if(reports){
            importRep(reports)
        }
        else{
            this.hub.connect()
        }
       
        
    }

    fetchInfoOfReport(report){
        
        
        
        let callback = (result)=>{
            if(this.reportLastSelected){
                // Aggiorno anche quello segnalato
                this.reportLastSelected = this.reports.find(rep=>rep.id==this.reportLastSelected.id)
                this.showReport.call(this,this.reportLastSelected)
            }

        
        }
        
        report.fetchInfo(callback)
    }

    searchBy(){
        const filter = selectSearch.options[selectSearch.selectedIndex].value
        this._tableSelected.showAllRow(this.reports)
        if(searchBar.value == "")
            return 

        switch(filter){
            case 'Indirizzo':{                
                this._tableSelected.hideRowButThese(this.reports,this.reports.filter(this.checkAddress))
                break;}
            case 'Città':{
                this._tableSelected.hideRowButThese(this.reports,this.reports.filter(this.checkCity))
                break;}
            case 'Tipo':{
                this._tableSelected.hideRowButThese(this.reports,this.reports.filter(this.checkType))
                break;}
            case 'Grado':{
                this._tableSelected.hideRowButThese(this.reports,this.reports.filter(this.checkGrade))
                break;}
        }
    }
    
    checkAddress(report){
        return report.address.includes(searchBar.value)
    }

    checkCity(report){
        return report.city.includes(searchBar.value)
    }

    checkType(report){
        return report.type.includes(searchBar.value)
    }

    checkGrade(report){
        return report.grade.includes(searchBar.value)
    }

    checkSelected(checkbox){
        return checkbox.checked == true
    }


    deselectLastReport(){
        this.reportLastSelected.el.classList.remove('tr--selected')
        this.reportLastSelected = null
    }   

    selectLastReport(report){
        if(!this.isMultipleSelection){
            if(this.reportLastSelected)
                this.deselectLastReport()
    
            this.reportLastSelected = report
            this.reportLastSelected.el.classList.add('tr--selected')    
            this.showReport(this.reportLastSelected)
        }
    }

    selectReport(report){
        if(!this.reportsSelected)
            this.reportsSelected = []

        this.reportsSelected.push(report)
        report.el.classList.add('tr--selected')    
        this.showRecap()        
        this.recapText.textContent = `Selezionate: ${this.reportsSelected.length}`
    }

    deselectReport(report){
        let index =  this.reportsSelected.indexOf(report)
        this.reportsSelected[index].el.classList.remove('tr--selected') 
        this.reportsSelected.splice(index, 1)
        if(this.reportsSelected.length == 0){
            this.reportsSelected = null
            this.hideRecap()
        }

        this.recapText.textContent = `Selezionate: ${this.reportsSelected.length}`
    }

    showReport(report){
        
        var options = []
        switch(report.state){
            case ReportState.Waiting:{
                options = ACTIONS_TEAM_REPORT_AS_WAITING
                break;
            }
            case ReportState.InCharge:{
                options = ACTIONS_TEAM_REPORT_AS_INCHARGE
                break;
            }
            case ReportState.Done:{
                options = ACTIONS_TEAM_REPORT_AS_DONE
                break;
            }
        }

        this.detail.setTitle(report.address,options)
        this.detail.el.setAttribute('data-grade', report.grade)
        this.detail.build()
        
        managerDet.show(this.detail, this.deselectLastReport.bind(this))
        
        let reportDate          = document.getElementById('report__date')
        let reportState         = document.getElementById('report__state')
        let reportType          = document.getElementById('report__type')
        let reportTeam          = document.getElementById('report__team')
        let reportDescription   = document.getElementById('report__description')
        
        

        reportDate.textContent = report.date
        reportState.textContent = report.state
        reportType.textContent = report.type
        reportTeam.textContent = report.team
        reportDescription.textContent = report.description

        report.fetchPhotos();
        document.addEventListener("onHubSuccess", (result)=>{
            let reportPhotos   = document.getElementById('report__photos')
            reportPhotos.innerHTML =""
            reportPhotos
                .appendChildren(
                    repeatEl('img',
                        report.photos.length,
                        { src: report.photos}
            ))
            calcPhoto()
        })


        report.fetchHistory();
        document.addEventListener("onHubSuccess", (result)=>{
            let reportHistory   = document.getElementById('report__history')
            reportHistory.innerHTML =""
            
            for(let i=0; i< report.history.length;i++){
                
                newEl('span,, report__history__team', reportHistory).call('textContent', report.history[i].team)
                newEl('span,, report__history__date', reportHistory).call('textContent', report.history[i].date)
                newEl('p,, report__history__note', reportHistory).call('textContent', report.history[i].note)
            }


        })

    
        // console.log(report)
    }

    getParent(report){
        return (report.state == 'In attesa' || report.state == 'In lavorazione')? this.tableReportNotFinished: this.tableReportFinished
    }

    drawTable(){
        this.reports.forEach(report=>{
            
            let row = this.getParent(report).addRow(report)

            this.checkboxes.push(row.children[0].querySelector('input'))
            row.addEventListener('click', this.selectLastReport.bind(this,report))
            row.children[0].querySelector('input').addEventListener('click',(event)=>{
                
                if(this.reportLastSelected)
                    this.deselectLastReport()
                

                if(event.target.checked){
                    this.isMultipleSelection = true
                    this.selectReport(event.target.parentElement.parentElement.report)
                }
                else{
                    if(this.nReportSelected == 0)
                        this.isMultipleSelection = false
                        let index = this.reportsSelected.indexOf(event.target.parentElement.parentElement.report)
                        this.deselectReport(this.reportsSelected[index])
                        
                }

                event.stopPropagation()
                
            })

        })
    }



    hideRecap(){
        document.querySelector('footer').style.display="none"
    }

    showRecap(){
        document.querySelector('footer').style.display="block"
    }
}

class Report{

    constructor(obj){
        for(let value in obj){
            this[value] = obj[value]
        }

        this.tmpHub = new Hub(" ", 'POST',{
            onsuccess: (result) => { 
                let res = JSON.parse(result.response)    
                if(res.error){
                    console.log('errore: '+res.message)
                }
                else{
                    this.fetchInfo()
                }              
             }
        })
    }
    

    fetchInfo(callback = null){

        Hub.connect(substitute(URL_FETCH_REPORT_BY_ID,[this.id]), 'GET', null,{
            onsuccess: (result) => { 
                let res = JSON.parse(result.response)
                if(res.error){
                    console.log('errore: '+res.message)
                }
                else{
                    var report = res.data
                }
                for(let value in report){
                    this[value] = report[value]
                }       

                if(callback)
                    callback()
             }
        })
    }
    
    fetchPhotos(){
        Hub.connect(substitute(URL_FETCH_PHOTOS_REPORT,[this.id]), 'GET', null,{
            onsuccess: (result) => { 
                let res = JSON.parse(result.response)    
                if(res.error){
                    console.log('errore: '+res.message)
                }
                else{
                    this.photos = res.data
                }                   
             }
        })
    }

    fetchHistory(){
        Hub.connect(substitute(URL_FETCH_HISTORY_REPORT,[this.id]), 'GET', null,{
            onsuccess: (result) => { 
                let res = JSON.parse(result.response)    
                if(res.error){
                    console.log('errore: '+res.message)
                }
                else{
                    this.history = res.data
                }              
             }
        })
    }

    editTeam(newTeam){
        this.tmpHub.method = "POST"
        this.tmpHub.url = substitute(URL_EDIT_TEAM_REPORT,[this.id])
        this.tmpHub.cleanParam()
        this.tmpHub.addParam('team',newTeam)
        this.tmpHub.connect()
    }

    editState(newState){
        this.tmpHub.method = "POST"
        this.tmpHub.url = substitute(URL_EDIT_STATE_REPORT,[this.id])
        this.tmpHub.cleanParam()
        this.tmpHub.addParam('state',newState)
        this.tmpHub.connect()
    }

    addToHistory(newMessage){
        this.tmpHub.method = "POST"
        this.tmpHub.url = substitute(URL_UPDATE_HISTORY_REPORT,[this.id])
        this.tmpHub.cleanParam()
        this.tmpHub.addParam('history',newMessage)
        this.tmpHub.connect()
    }

    deleteReport(){
        this.tmpHub.method = "GET"
        this.tmpHub.url = substitute(URL_DELETE_REPORT,[this.id])
        this.tmpHub.cleanParam()
        this.tmpHub.connect()

        // this.el.parentEl.removeChild(this.el)
    }

    

}