class Team{
    constructor(name){
        this.name = name
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


    deleteReports(){
        
        if(!this.manager.reportsSelected)
            return

        var ids =  this.manager.reportsSelected.map(a=>a.id)
        
        let hub = new Hub(URL_DELETE_REPORTS,"POST",{id:JSON.stringify(ids)},{
            onsuccess: this.refresh.bind(this)
        })
        
        
        hub.connect()
        
        

    }

    deleteReport(){
        let reportToDelete =this.manager.reportLastSelected

        reportToDelete.tmpHub.onsuccess = this.refresh.bind(this)
        reportToDelete.deleteReport();
    }
}


