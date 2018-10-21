// const tbodyReportNotFinished    = document.getElementById('report--notfinished').querySelectorAll('table')[1]
// const tbodyReportFinished       = document.getElementById('report--finished').querySelectorAll('table')[1]


class Table{
    constructor(id, theclass){
        this.el = newEl({el:'div',theclass:theclass,id:id}, document.body.querySelector('main'))
        

        this.header = newEl('tr,, row100 head')
        this.body   = newEl('tbody')
    }

    addHeader(header){
        for(let i=0;i< header.length;i++){
            let th = newEl('th,, cell100 column'+i+1,this.header)
            th.innerHTML = header[i]
        }
    }

    createRow(content){
        let row = this.body.insertRow(0)
        row.classList.add('row100')
        row.classList.add('body')

        for(let i=0;i< content.length;i++){
            row.innerHTML += `<td class="cell100 column${i+1}">${content[i]}</td>`
        }

        return row
    }

    

    cleanBody(){
        this.body.innerHTML = ""
    }

    build(){
        this.el.innerHTML = `
            <div class="container-table100">
                <div class="wrap-table100">
                    <div class="table100 ver1 m-b-110">
                        <div class="table100-head">
                            <table>
                            <thead>
                                ${this.header.outerHTML}
                            </thead>
                            </table>
                        </div>

                        <div class="table100-body js-pscroll">
                            <table>
                                <tbody>
                                    ${this.body.outerHTML}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>`

        this.body = this.el.querySelector('tbody')
        this.header = this.el.querySelector('thead')
    }

    hide(){
        this.el.classList.add('list__report--hide')
    }

    show(){
        this.el.classList.remove('list__report--hide')
    }
}


class TableReport extends Table{

    constructor(id){
        const HEADER = [
            '',
            'Indirizzo',
            'Data creazione',
            'Stato',
            'Tipo',
            'Grado'
        ]
        super(id, 'list__report')
        super.addHeader(HEADER)
        super.build()
    }

    
    addRow(report){
        
        
        let gradeText = "";
        switch(report.grade){
            case 'LOW': {gradeText='bassa';break;}
            case 'INTERMEDIATE': {gradeText='media';break;}
            case 'HIGH':{gradeText='alta';break;}
        }
        
        let row = super.createRow([
                `<input type="checkbox"></input>`,
                report.address,
                report.date,
                report.state,
                report.type,
                `<i class="report__grade__ball" title="Gravità ${gradeText}" data-grade=${report.grade}></i>`
        ])       

        row.report = report
        report.el = row
        report.el.hide = false

        return row
        
    }

    deleteRow(report){
        this.getParent(report).deleteRow(this.reports.indexOf(report))
    }

    hideRowButThese(reports, reportsToShow){
        reports.forEach(rep=>{
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
    }

    showRow(report){
        if(report.el.hide == false)
            return

        report.el.hide = false
        report.el.classList.remove(CLASS_ROW_HIDDEN)
    }

    showAllRow(reports){
        reports.forEach(rep=>this.showRow(rep))
    }

    deleteAllRows(){
        super.cleanBody()
    }   
}