class Admin{

    constructor(name){
        this.name = name
    }

    deleteReports(){
        
        if(!this.manager.reportsSelected)
            return

        var ids =  this.manager.reportsSelected.map(a=>a.id)
        
        let hub = new Hub(URL_DELETE_REPORTS,'POST', null,{id:JSON.stringify(ids)},{
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