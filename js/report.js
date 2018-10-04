const URL_FETCH_REPORTS = 'fetchReport.php'
const URL_FETCH_REPORT = 'fetchReport.php'
const URL_FETCH_PHOTOS_REPORT = 'fetchReport.php'

const CLASS_ROW_HIDDEN = 'row--hide'

const tbodyReportNotFinished    = document.getElementById('report--notfinished').querySelectorAll('table')[1]
const tbodyReportFinished       = document.getElementById('report--finished').querySelectorAll('table')[1]
const searchBar                 = document.getElementById('search__bar')
const selectSearch              = document.getElementById('select__search')
const refreshButton             = document.getElementById('refresh__button')


class ManagerReport{


    constructor(){
        this.reports = []
        this.hub = new Hub(URL_FETCH_REPORTS, "GET")
        this.reportSelected = null

        refreshButton.addEventListener('click',this.fetchAllReports.bind(this))
        searchBar.addEventListener('keyup', this.searchBy.bind(this))
    }

    fetchAllReports(){
        this.deleteAllRows()
        this.hub.onsuccess = (result) => {
            let reports  = JSON.parse(result.response)
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
        return report.address.startsWith(searchBar.value)
    }

    checkCity(report){
        return report.city.startsWith(searchBar.value)
    }

    checkType(report){
        return report.type.startsWith(searchBar.value)
    }

    checkGrade(report){
        return report.grade.startsWith(searchBar.value)
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
        console.log(report)
    }
}

class Report{

    constructor(obj){
        for(let value in obj){
            this[value] = obj[value]
        }

        
    }
    

    fetchInfo(){
        Hub.connect(`${URL_FETCH_REPORT}/${this.id}`, 'GET',{
            onsuccess: (result) => { 
                obj = JSON.parse(result.response)
                for(let value in obj){
                    this[value] = obj[value]
                }       
             }
        })
    }
    fetchPhotos(){
        Hub.conncet(`${URL_FETCH_PHOTOS_REPORT}/photos/${this.id}`, 'GET',{
            onsuccess: (result) => { 
                this.photos = JSON.parse(result.response)                       
             }
        })
    }
    fetchHistory(){
        Hub.connect(`${URL_FETCH_HISTORY_REPORT}/history/${this.id}`, 'GET',{
            onsuccess: (result) => { 
                this.history = JSON.parse(result.response)                       
             }
        })
    }

    editTeam(newTeam){
       Hub.connect(`${URL_EDIT_TEAM_REPORT}/${this.id}/${newTeam}`, 'POST',{
            onsuccess: (result) => { 
                this.fetchInfo()
             }
        })
    }

    editState(){
        Hub.connect(`${URL_EDIT_STATE_REPORT}/${this.id}/${newTeam}`, 'POST',{
            onsuccess: (result) => { 
                this.fetchInfo()
             }
        })
    }

    deleteReport(){
        this.el.parentEl.removeChild(this.el)
    }

    draw(){

    }
    

}