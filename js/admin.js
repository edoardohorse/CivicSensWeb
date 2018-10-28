class Admin{

    constructor(name){
        this.name = name


        let button = document.querySelector('.report__recap button')
        button.onclick = this.deleteReports.bind(this)
        button.title = "Cancella le segnalazioni permanentemente"
    }

    

    deleteReports(){
        let confirm = false
        if(!manager.reportsSelected)
            return

        vex.dialog.open({
            message: 'Eliminare i report selezionati definitivamente?',
            callback: function (value) {
                if(value){
                    var ids =  manager.reportsSelected.map(a=>a.id)
        
                    let hub = new Hub(URL_DELETE_REPORTS,'POST', {id:JSON.stringify(ids)},{
                        onsuccess: this.refresh.bind(this)
                    })
                    
                    
                    hub.connect()
                }
                return value
            }.bind(this)
        })

        
        
        

    }

    deleteReport(){
        let reportToDelete =manager.reportLastSelected

        reportToDelete.tmpHub.onsuccess = this.refresh.bind(this)
        reportToDelete.deleteReport();
    }

}