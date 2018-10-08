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

class ManagerDetails{
    constructor(el){
        this.el = el
        this.details = []
        this.detailsSelected = null
    }

    addDetails(detail){
        this.details.push(detail)
    }

    clean(){
        this.el.removeChild(this.detailsSelected)
        this.detailsSelected = null
    }
    
    show(detail){
        if(this.details.indexOf(detail) > -1){
            this.el.appendChild(detail.el)
            this.detailsSelected = detail
            this.detailsSelected.el.classList.remove('details--hide')
            this.detailsSelected.el.querySelector('.details__close').addEventListener('click',this.hide.bind(this))
        }
    }

    hide(){
        this.detailsSelected.el.classList.add('details--hide')
    }
}

class Details{
    constructor(){
        this.titleEl    = null
        this.bodyEl     = null
        this.footerEl   = null
        
        this.el = newEl('aside,,details')
        this.bodyEl = newEl('main,,details__body col-12')
        this.footerEl = newEl('footer,,details__chooser')
    }

    build(){
        this.el.innerHTML = ""
        this.titleEl    ? this.el.appendChild(this.titleEl)   :null
        this.bodyEl     ? this.el.appendChild(this.bodyEl)    :null
        this.footerEl   ? this.el.appendChild(this.footerEl)  :null
    }

    setTitle(title, options = null){
        this.titleEl = newEl('nav,,details__title col-12', this.el)
        
        newEl('span', this.titleEl).call('textContent',title)
        newEl('i,,details__close', this.titleEl)

        /* let options = document.createElement('div')
        div.classList.add('details__options') */        
    }

    setFooter(){}

    addItem(row, item){
        let {label, content, col = 12, divided = false, slider = false} = item
        
        let itemEl = newEl(`article,, details__item col-${col}, data-slider=${slider}`, row)

        if(divided)
            itemEl.setAttribute('divided','')

        if(typeof label == 'string')
            newEl('label', itemEl).call('textContent', label)
        else
            newEl('label', itemEl).appendChild(label)


        if(typeof content == 'string')
            newEl('div', itemEl).call('textContent', content)
        else
            newEl('div', itemEl).appendChild(content)

        
    }

    addRow(...items){
        let row = newEl('div,, details__row', this.bodyEl)
        
        for(let item of items){
            this.addItem(row, item)
        }

    }
}

class ManagerReport{


    constructor(){
        this.reports = []
        this.hub = new Hub(substitute(URL_FETCH_REPORTS_BY_CITY,['Grottaglie']), "GET") 
        this.reportSelected = null
        
        this.detail = new Details()
        this.detail.setTitle("Via Socrate, 15")
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
                manager.addRow(this.reports[this.reports.length-1])
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
        let row = parent.insertRow(parent.children)

         
        row.classList.add('row100')
        row.classList.add('body')

        let gradeText = "";
        switch(report.grade){
            case 'LOW': {gradeText='bassa';break;}
            case 'INTERMEDIATE': {gradeText='media';break;}
            case 'HIGH':{gradeText='alta';break;}
        }
        
        row.innerHTML += `<td class="cell100 column1">${report.city}</td>`
        row.innerHTML += `<td class="cell100 column2">${report.address}</td>`
        row.innerHTML += `<td class="cell100 column3">${report.description}</td>`
        row.innerHTML += `<td class="cell100 column4">${report.state}</td>`
        row.innerHTML += `<td class="cell100 column5">${report.type}</td>`
        row.innerHTML += `<td class="cell100 column6"><i class="report__grade__ball" title="Gravità ${gradeText}" data-grade=${report.grade}></i></td>`

        row.report = report
        report.el = row
        report.el.hide = false

        row.addEventListener('click',this.showReport.bind(this,report))
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

    showReport(report){
        this.reportSelected = report

        this.detail.setTitle(this.reportSelected.address)
        this.detail.el.setAttribute('data-grade', this.reportSelected.grade)
        this.detail.build()
        
        managerDet.show(this.detail)
        
        let reportDate          = document.getElementById('report__date')
        let reportState         = document.getElementById('report__state')
        let reportType          = document.getElementById('report__type')
        let reportTeam          = document.getElementById('report__team')
        let reportDescription   = document.getElementById('report__description')
        
        

        reportDate.textContent = this.reportSelected.date
        reportState.textContent = this.reportSelected.state
        reportType.textContent = this.reportSelected.type
        reportTeam.textContent = this.reportSelected.team
        reportDescription.textContent = this.reportSelected.description

        this.reportSelected.fetchPhotos();
        document.addEventListener("onHubSuccess", (result)=>{
            let reportPhotos   = document.getElementById('report__photos')
            reportPhotos.innerHTML =""
            reportPhotos
                .appendChildren(
                    repeatEl('img',
                        this.reportSelected.photos.length,
                        { src: this.reportSelected.photos}
            ))
            calcPhoto()
        })


        this.reportSelected.fetchHistory();
        document.addEventListener("onHubSuccess", (result)=>{
            let reportHistory   = document.getElementById('report__history')
            reportHistory.innerHTML =""
            
            for(let i=0; i< this.reportSelected.history.length;i++){
                
                newEl('span,, report__history__date', reportHistory).call('textContent', this.reportSelected.history[i].date)
                newEl('span,, report__history__team', reportHistory).call('textContent', this.reportSelected.history[i].team)
                newEl('p,, report__history__note', reportHistory).call('textContent', this.reportSelected.history[i].note)
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