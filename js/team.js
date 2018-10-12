class Team{
    constructor(name){
        this.name = name
        this.manager = new ManagerReport(URL_FETCH_REPORTS_BY_TEAM, this.name)
        this.manager.fetchAllReports()

    }

    setReportAsInCharge(){
        let report  = this.manager.reportLastSelected
        report.tmpHub.onsuccess = (result)=>{
            result = JSON.parse(result.response)
            vex.dialog.alert({
                message: result.message                
            })

            this.manager.fetchAllReports()
        }
        report.editState( ReportState.InCharge )
    }

    setReportAsDone(){
        let report  = this.manager.reportLastSelected
        report.tmpHub.onsuccess = (result)=>{
            result = JSON.parse(result.response)
            vex.dialog.alert({
                message: result.message                
            })

            this.manager.fetchAllReports()
        }
        report.editState( ReportState.Done )

    }

    reSetReportAsInCharge(){
        this.setReportAsInCharge()
    }

    updateHistoryOfReport(){
        let report  = this.manager.reportLastSelected
        report.tmpHub.onsuccess = (result)=>{
            result = JSON.parse(result.response)
            vex.dialog.alert({
                message: result.message                
            })

            this.manager.fetchAllReports()
        }
        
        vex.dialog.open({
            message:'Inserisci una nuova nota',
            input: newEl({el:'textarea',data:{name:'message'}}),
            callback: function(data){
                if(data && data.message){
                    this.manager.reportLastSelected.addToHistory(data.message)            
                }
                
            }.bind(this)
        })
        
    }

    
}


