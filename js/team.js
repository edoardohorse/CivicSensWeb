class Team extends Admin{
    constructor(name){
        super(name)
        this.nMember = null
        this.manager = new ManagerReport(URL_FETCH_REPORTS_BY_TEAM, this.name)
        this.manager.fetchAllReports()

        this.refresh = (result)=>{
            result = JSON.parse(result.response)
            vex.dialog.alert({
                message: result.message                
            })

            this.manager.fetchAllReports()
        }
    }

    fetchInfo(){}

    setReportAsInCharge(){
        let report  = this.manager.reportLastSelected
        report.tmpHub.onsuccess = this.refresh.bind(this)
        
        report.editState( ReportState.InCharge )
    }

    setReportAsDone(){
        let report  = this.manager.reportLastSelected
        report.tmpHub.onsuccess = this.refresh.bind(this)

        report.editState( ReportState.Done )

    }

    reSetReportAsInCharge(){
        this.setReportAsInCharge()
    }

    updateHistoryOfReport(){
        let report  = this.manager.reportLastSelected
        report.tmpHub.onsuccess = this.refresh.bind(this)
        
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


