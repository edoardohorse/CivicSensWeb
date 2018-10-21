class Admin{

    constructor(name){
        this.name = name
    }

    deleteReports(){
        
        if(!manager.reportsSelected)
            return

        var ids =  manager.reportsSelected.map(a=>a.id)
        
        let hub = new Hub(URL_DELETE_REPORTS,'POST', null,{id:JSON.stringify(ids)},{
            onsuccess: this.refresh.bind(this)
        })
        
        
        hub.connect()
        
        

    }

    deleteReport(){
        let reportToDelete =manager.reportLastSelected

        reportToDelete.tmpHub.onsuccess = this.refresh.bind(this)
        reportToDelete.deleteReport();
    }
}