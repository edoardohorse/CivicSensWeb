// ============== GET
const URL_FETCH_REPORTS_BY_CITY =  'apiReport/report/city/{#}/'
const URL_FETCH_REPORTS_BY_TEAM =  'apiReport/report/team/{#}/'
const URL_FETCH_REPORT_BY_ID    =  'apiReport/report/id/{#}/'
const URL_FETCH_PHOTOS_REPORT   =  'apiReport/report/photos/{#}/'
const URL_FETCH_HISTORY_REPORT  =  'apiReport/report/history/{#}/'
const URL_DELETE_REPORT         =  'apiReport/report/delete/{#}/'
const URL_DELETE_REPORTS         =  'apiReport/report/delete'


// ============== POST
const URL_EDIT_TEAM_REPORT      =  'apiReport/report/{#}/team/'
const URL_UPDATE_HISTORY_REPORT =  'apiReport/report/{#}/history/'
const URL_EDIT_STATE_REPORT     =  'apiReport/report/{#}/state/'


const ReportState = {Waiting:'In attesa', InCharge:'In lavorazione', Done:'Finito'}
Object.freeze(ReportState)

const CLASS_ROW_HIDDEN = 'row--hide'

const tbodyReportNotFinished    = document.getElementById('report--notfinished').querySelectorAll('table')[1]
const tbodyReportFinished       = document.getElementById('report--finished').querySelectorAll('table')[1]
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

    get nReportSelected(){ return this.checkboxes.filter(this.checkSelected) }

    constructor(url, name){
        this.reports = []
        this.hub = new Hub(substitute( url, [name]), "GET") 
        this.reportsSelected = []
        this.checkboxes = []
        this.reportLastSelected = false
        this.isMultipleSelection = false
        
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
                                        disabled:null
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

        refreshButton.addEventListener('click',this.fetchAllReports.bind(this))
        searchBar.addEventListener('keyup', this.searchBy.bind(this))
    }

    fetchAllReports(){
        this.deleteAllRows()
        this.reports = []
        this.hub.onsuccess = (result) => {
            let reports

            let res  = JSON.parse(result.response)
            if(res.error){
                console.log('errore: '+res.message)
            }
            else{
                reports = res.data
            }
            reports.forEach(report=>{
                this.reports.push(new Report(report))
                this.addRow(this.reports[this.reports.length-1])
            })
            
        }
        
        this.hub.connect()
        
    }

    searchBy(){
        const filter = selectSearch.options[selectSearch.selectedIndex].value
        this.showAllRow()
        if(searchBar.value == "")
            return 

        switch(filter){
            case 'Indirizzo':{                
                this.hideRowButThese(this.reports.filter(this.checkAddress))
                break;}
            case 'Città':{
                this.hideRowButThese(this.reports.filter(this.checkCity))
                break;}
            case 'Tipo':{
                this.hideRowButThese(this.reports.filter(this.checkType))
                break;}
            case 'Grado':{
                this.hideRowButThese(this.reports.filter(this.checkGrade))
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

    
    deleteReport(id){
        reportToDelete = this.reports[id]
        Hub.connect(`${URL_DELETE_REPORT}/${reportToDelete.id}`, 'POST',{
            onsuccess: (result) => { 
                reportToDelete.deleteReport
             }
        })
    }

    addRow(report){
        
        let parent = this.getParent(report)
        let row = parent.insertRow(parent.children[0].children.length)

         
        row.classList.add('row100')
        row.classList.add('body')

        let gradeText = "";
        switch(report.grade){
            case 'LOW': {gradeText='bassa';break;}
            case 'INTERMEDIATE': {gradeText='media';break;}
            case 'HIGH':{gradeText='alta';break;}
        }
        
        row.innerHTML += `<td class="cell100 column1"><input type="checkbox"></td>`
        row.innerHTML += `<td class="cell100 column2">${report.address}</td>`
        row.innerHTML += `<td class="cell100 column3">${report.date}</td>`
        row.innerHTML += `<td class="cell100 column4">${report.state}</td>`
        row.innerHTML += `<td class="cell100 column5">${report.type}</td>`
        row.innerHTML += `<td class="cell100 column6"><i class="report__grade__ball" title="Gravità ${gradeText}" data-grade=${report.grade}></i></td>`

        row.report = report
        report.el = row
        report.el.hide = false

        this.checkboxes.push(row.children[0].querySelector('input'))
        row.addEventListener('click', this.selectLastReport.bind(this,report))
        row.children[0].querySelector('input').addEventListener('click',(event)=>{
            
            this.deselectLastReport()
            

            if(event.target.checked){
                this.isMultipleSelection = true
                this.selectReport(event.target.parentElement.parentElement.report)
            }
            else{
                if(this.nReportSelected == 0)
                    this.isMultipleSelection = false
                    this.deselectReport(event.target.parentElement.parentElement.report)
                    
            }

            event.stopPropagation()
            
        })
        
    }

    deleteRow(report){
        this.getParent(report).deleteRow(this.reports.indexOf(report))
    }

    hideRowButThese(reportsToShow){
        this.reports.forEach(rep=>{
            if(reportsToShow.indexOf(rep) == -1 ){
                this.hideRow(rep)
            }
        })
    }

    toggleRow(report){
        if(report.row.hide){
            this.showRow(report)
        }
        else{
            this.hideRow(report)
        }
    }

    hideRow(report){
        if(report.el.hide == true)
            return

        report.el.hide = true
        report.el.classList.add(CLASS_ROW_HIDDEN)
        report.el.removeEventListener('click', this.showReport.bind(this,report))
    }

    showRow(report){
        if(report.el.hide == false)
            return

        report.el.hide = false
        report.el.classList.remove(CLASS_ROW_HIDDEN)
        report.el.removeEventListener('click', this.showReport.bind(this,report))
    }

    showAllRow(){
        this.reports.forEach(rep=>this.showRow(rep))
    }

    deleteAllRows(){
        tbodyReportNotFinished.tBodies[0].innerHTML = ""
        tbodyReportFinished.tBodies[0].innerHTML = ""
    }

    getParent(report){
        return (report.state == 'In attesa' || report.state == 'In lavorazione')? tbodyReportNotFinished: tbodyReportFinished
    }

    deselectLastReport(){
        this.reportLastSelected.el.classList.remove('tr--selected')
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
        this.reportsSelected.push(report)
        report.el.classList.add('tr--selected')    
        this.showReport(report)
    }

    deselectReport(report){
        let index =  this.reportsSelected.indexOf(report)
        this.reportsSelected[index].el.classList.remove('tr--selected') 
        this.reportsSelected.splice(index, 1)
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
        
        managerDet.show(this.detail)
        
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
                
                newEl('span,, report__history__date', reportHistory).call('textContent', report.history[i].date)
                newEl('span,, report__history__team', reportHistory).call('textContent', report.history[i].team)
                newEl('p,, report__history__note', reportHistory).call('textContent', report.history[i].note)
            }


        })

        

        // console.log(report)
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
    

    fetchInfo(){
        Hub.connect(substitute(URL_FETCH_REPORT_BY_ID,[this.id]), 'GET',{
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
             }
        })
    }
    fetchPhotos(){
        Hub.connect(substitute(URL_FETCH_PHOTOS_REPORT,[this.id]), 'GET',{
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
        Hub.connect(substitute(URL_FETCH_HISTORY_REPORT,[this.id]), 'GET',{
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
        this.tmpHub.addParam('state',newMessage)
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