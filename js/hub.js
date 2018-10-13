//const ROOT = "../../"

//const READYSTATE = [0,1,2,3,4]
//const STATUS

class Hub  {

    static get EVENTS(){ return {
        onsuccess:"onHubSuccess", onstart:"onHubStart",
        ondone:"onHubDone", onerror:"onHubError" } }

    get url() { return this._url }
    set url(url) {
        if (typeof url != "string") {
            console.error("URL is not valid")
            return
        }
        else
            this._url = url

            }


    get method() { return this._method; }
    set method(m) {

        if (m === "GET" || m === "POST")
            this._method = m
            else
                console.error("Method can be set to GET or POST value");
    }

    get queryString() { return this._queryString }
    set queryString(param) {
        //this._queryString = {}
        //debugger
        if (param != null) {

            if (typeof param === "string") {

                //if (param.startsWith("?")) {
                if (param.indexOf("?") == 0) {
                    console.info("Not need to start the queryString with '?'");
                    param = param.substring(1)
                }
                param = param.split("&")
                //debugger
                param.forEach((a) => {
                    let b = a.split("=")
                    this._queryString[b[0]] = b[1]
                })
            }
            else {
                this._queryString = Object.assign(this._queryString, param)
            }



        }

        this.queryString.serialize = () => {
            var ampersand = "";
            let temp = this.method == "GET" ? "?" : ""

            for (let i in this._queryString) {
                if (i != "serialize") {
                    temp += `${ampersand}${i}=${this._queryString[i]}`
                    var ampersand = "&";
                }
            }
            return temp
        }
        return this._queryString;


    }

    get async() { return this._async }
    set async(isAsync) { isAsync ? this._async = true : this._async = false }

    get onerror() { return this._onerror }
    set onerror(fn) { this._onerror = fn }

    get onsuccess() { return this._onsuccess }
    set onsuccess(fn) { this._onsuccess = fn }

    get onstart() { return this._onstart }
    set onstart(fn) { this.req.onloadstart = this._onstart = fn }

    get ondone() { return this._ondone }
    set ondone(fn) { this._ondone = fn }

    get onprogress() { return this._onprogress }
    set onprogress(fn) { this.req.onprogress = this._onprogress = fn }


    constructor(url, method, param, callbacks = {}, _async) {

        //super()
        this.req = new XMLHttpRequest()
        this._onerror = null
        this._onsuccess = null
        this._onstart = null
        this._ondone = null
        this._queryString = {}
        this.req.onreadystatechange = this.statechange.bind(this)
        this.isHeaderSet = false
        this._events = Hub.EVENTS
        for( let i in this._events  ){
            let temp = document.createEvent("Event")
            temp.initEvent( this._events[ i ], true, true )
            temp.page = this
            this._events[ i ] = temp
        }

        checkCallbacks.call(this,callbacks)

        this.url = url || null
        this.method = method || "GET"
        this.queryString = param || null
        this.async = _async || true




        function checkCallbacks(obj) {
            let arr = Object.keys(obj)
            //if (typeof Object.keys(arr)[0] == "function") {
            arr.forEach((callback) => {
                if (callback in Hub.EVENTS)
                    if( typeof obj[callback] == "function" )
                        this[callback] = obj[callback];
                    else
                        return console.error("Only functions are permitted as callback ");

                else
                    return console.error(`Only ${Object.keys(Hub.EVENTS)} are permitted as callback`)
                    }
                       )
            /*}
            else
                return obj*/

        }

    }



    statechange(e) {
        //debugger
        let req = e.target;
        if (req.readyState == req.DONE) {
            this.result = {
                response: this.req.response,
                responseText: this.req.responseText,
                responseType: this.req.responseType,
                responseURL: this.req.responseURL,
                responseXML: this.req.responseXML
            }
            this.result.responseText.indexOf("debug") == 0 ?
                document.body.innerHTML = this.result.responseText.replace("debug", "DEBUG<br>")
            : false

            if (req.status == 200){
                if( this.onsuccess )
                    this.onsuccess(this.result)

                document.dispatchEvent( this._events.onsuccess )
            }
            if (req.status == 404){
                if(this.onerror)
                    this.onerror(this.result)

                document.dispatchEvent( this._events.onerror )
            }
            if (this.ondone){
                this.ondone(this.result)
            }
            document.dispatchEvent( this._events.ondone )

        }


    }

    addParam(...param) {
        if (typeof param[0] == "string")
            param = `${param[0]}=${param[1]}`
            else
                param = param[0]

                this.queryString = param

                return this
    }

    cleanParam(){
        this.queryString = {}
    }

    setRequestHeader(header, value) {
        this.isHeaderSet = true
        this.req.requestHeader = [header,value]
        this.req.setRequestHeader(header, value);
        return this;
    }

    connect() {
        if (this.url) {


            if (this.method == "GET") {
                this.req.open("GET", this.url + this.queryString.serialize(), this.async);
                this.req.send();
                document.dispatchEvent( this._events.onstart )
            }
            else {
                this.req.open("POST", this.url, this.async)
                if (this.isHeaderSet) {
                    this.setRequestHeader(
                        this.req.requestHeader[0], this.req.requestHeader[1])
                }
                else{
                    this.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                }
                this.req.send(this.queryString.serialize())
                document.dispatchEvent( this._events.onstart )


            }
        }
        else
            console.warn("Needs an URL to make a request");

        return this

    }

    static connect(...param) {
        //debugger
        var [url, method, param, _async] = param
        let temp = new this(url, method, param, _async).connect()

        return temp

    }





}

/*let hub = new Hub( `${ROOT}index2.php`,"POST",{
    onstart: result=>{console.log("inizio con la callback")},
    ondone: result=>{console.log("fine con la callback")},
    onsuccess: result=>{console.log("tutto OK con la callback")},
    onerror: result=>{console.error("errore con la callback")}
} );
document.addEventListener("onHubStart", result=>{
    console.log("inizio con il listener");
})
document.addEventListener("onHubDone", result=>{
    console.log("fine con il listener");
})
document.addEventListener("onHubSuccess", result=>{
    console.log("tutto OK con il listener");
})
document.addEventListener("onHubError", result=>{
    console.error("errore con il listener");
})

hub.connect()*/
/*hub.onerror = (result)=>{
    console.error( "Errore" );
}
hub.onsuccess = ( result ) =>{
    console.log( "Successo" );
}
hub.onstart = () =>{
    console.info( "Iniziato" );
}
hub.ondone = (result) =>{
    console.info( "Finito");
}
hub.connect()*/
//let hub = new Hub( "prova","GET",{data:"",t:"c"} );
/*let hub = new Hub( `${ROOT}inde.php`,"POST", "data=&t=c" );
hub.onerror = (result)=>{
    console.error( "Errore" );
}
hub.onsuccess = ( result ) =>{
    console.log( "Successo" );
}
hub.onstart = () =>{
    console.info( "Iniziato" );
}
hub.ondone = (result) =>{
    console.info( "Finito");
}*/



/*var i=1;
hub.onprogress = () =>{
    console.info( `Progress ${i++}` );
}*/

//hub.connect()
/*let hub = new Hub( `${ROOT}index.php`,"POST", {
                                                onsuccess : ( result ) =>{ console.log(result.response) },
                                                ondone : ( result ) =>{ console.log("end") },
                                                onstart : ( result ) =>{ console.log("start") }
                                                })
*/
/*let hub2 = Hub.connect(`${ROOT}index.php`,
    {
        onsuccess: (result) => { console.log(result.response) },
        ondone: (result) => { console.log("end") },
        onstart: (result) => { console.log("start") }
    })*/

/*let hub2 = new Hub(`${ROOT}index.php`, "POST"
    {
        onsuccess: (result) => { console.log(result.response) },
        ondone: (result) => { console.log("end") },
        onstart: (result) => { console.log("start") }
    }
    ).addParam("data","value").addParam({testo:"testo"}).connect()

hub2.debug;*/