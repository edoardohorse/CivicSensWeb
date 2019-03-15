class Team extends Admin{
    constructor(name, nMember = null, typeReport = null, nReport = null, email =null, reports = null){
        super(name)
        this.nMember = nMember
        this.typeReport = typeReport
        this.reports = reports || []
        this.nReport = nReport        
        this.email = email        
        // manager = new ManagerReport(URL_FETCH_REPORTS_BY_TEAM, this.name)
        
        // this.fetchInfo(reports)
        

        this.refresh = (result)=>{
            result = JSON.parse(result.response)
            vex.dialog.alert({
                message: result.message                
            })

            manager.fetchAllReports()
        }
    }

    fetchInfo(reports){
        let callback = (reports)=>{
            this.reports = reports
        }
        manager.fetchAllReports(callback, reports)
    }

    setReportAsInCharge(){
        let report  = manager.reportLastSelected
        report.tmpHub.onsuccess = this.refresh.bind(this)
        
        report.editState( ReportState.InCharge )
    }

    setReportAsDone(){
        let report  = manager.reportLastSelected
        report.tmpHub.onsuccess = this.refresh.bind(this)

        report.editState( ReportState.Done )

    }

    reSetReportAsInCharge(){
        this.setReportAsInCharge()
    }

    updateHistoryOfReport(){
        let report  = manager.reportLastSelected
        report.tmpHub.onsuccess = this.refresh.bind(this)
        
        vex.dialog.open({
            message:'Inserisci una nuova nota',
            input: newEl({el:'textarea',data:{name:'message'}}),
            callback: function(data){
                if(data && data.message){
                    manager.reportLastSelected.addToHistory(data.message)            
                }
                
            }.bind(this)
        })
        
    }

    changeTeamName(){
        
    }

    deleteReport(){
        let reportToDelete =manager.reportLastSelected
        
        reportToDelete.tmpHub.onsuccess = this.refresh.bind(this)
        reportToDelete.deleteReport();
        managerDet.hide()
    }

}


