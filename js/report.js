const URL_FETCH_REPORTS = 'fetchReport.php'
const URL_FETCH_REPORT = 'fetchReport.php'
const URL_FETCH_PHOTOS_REPORT = 'fetchReport.php'

const tbodyReportNotFinished = document.getElementById('report--notfinished').querySelector('tbody')
const tbodyReportFinished = document.getElementById('report--finished').querySelector('tbody')

class ManagerReport{


    constructor(){
        this.reports = []
        // this.$ = document.getElementById('manager')
        this.hub = new Hub(URL_FETCH_REPORTS, "GET")

        
    }

    fetchAllReports(){

        this.hub.onsuccess = (result) => {
            let reports  = JSON.parse(result.response)
            reports.forEach(report=>{
                this.reports.push(new Report(report))
            })
        }
        
        this.hub.connect()

    }

    searchBy(filter){
        switch(selectEl.options[selectEl.selectedIndex].value){
            case 'Indirizzo':{
                write(reports.filter(checkAddress))
                break;}
            case 'Città':{
                write(reports.filter(checkCity))
                break;}
            case 'Cdt':{
                write(reports.filter(checkCdt))
                break;}
            case 'Team':{
                write(reports.filter(checkTeam))
                break;}
        }
    }
    
    
    checkAddress(report){
        return report.address.startsWith(searchEl.value)
    }

    checkCity(report){
        return report.city.startsWith(searchEl.value)
    }

    checkCdt(report){
        return report.cdt.startsWith(searchEl.value)
    }

    checkTeam(report){
        return report.team.startsWith(searchEl.value)
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

        if(report.state == 'In Attesa' ||
            report.state == 'In Lavorazione')
            let row = tbodyReportNotFinished.insertRow(row)
        else
        let row = tbodyReportFinished.insertRow(row)

         = document.createElement('tr')
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