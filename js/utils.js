/*
 * Example usage newEl
 * el:"div",
 * id:"theid",
 * theclass:"box expanded",
 * data:{
    "data-init": "prova",
    "href": provava"
    Object.prova = function(){
    *  }
    debugger;
    }
 * !!! Respect the uppercase of attributes such as viewBox and not viewbox !!!
 */

function newEl(node, toWrite) {
    // Crea Html element per comodità
    if( typeof node == "string"){
        var string = node.split(",");

        var el = document.createElement( string[0].trim() );
        if( string[1] != undefined )
            string[1].trim() == "" ? false : el.id = string[1].trim();
        if ( string[2] != undefined) {
            string[2].indexOf(" ") == 0 ? string[2] = string[2].substr(1) : false;
            var theclass = string[2].split(" ");
            for (var i in theclass)
                classie.add(el, theclass[i]);
        }
        if ( string[3] != undefined) {
            string[3].indexOf(" ") == 0 ? string[3] = string[3].substr(1) : false;
            var dataset = []
            if(string[3].includes('"')){                //'type=text placeholder="Nome Team" value="prova prov2" hidden=true'
                var a = string[3].split('=')
                a.forEach(function(tk){
                    if(tk.includes('"')){
                        let index = tk.lastIndexOf('"')

                        let value = tk.substring(1, index)
                        
                        this[this.length-1]+=value

                        let t = tk.split(' ')

                        if(a[a.length-1] != tk)
                            this.push(t[t.length-1]+="=")
                    }
                    else if(tk.includes(' ')){
                        let t = tk.split(' ')
                        this[this.length-1]+=t[0]
                        this.push(t[1]+="=")
                    }
                    else{
                        if(this.length == 0)
                            this.push(tk+="=")
                        else{
                            this[this.length-1]+=tk
                        }
                    }
                }.bind(dataset))
            }
            else{
                dataset = string[3].split(" ");
            }
            for (var i in dataset) {
                // debugger
                var s = dataset[i].split("=")
                el[s[0]] = s[1]
                // var attr = document.createAttribute(s[0]);
                // // Don't use setAttribute( string, string) cause
                // // doesn't respect the uppercase
                // if(s[1])
                //     attr.value = s[1]
                // el.setAttributeNode(attr);
            }
                
        }
    }

    if( typeof node == "object" ){

        if (node.el == null ) {
            console.error("--- Need a name of HTMLElement at least");
            return;
        }
        else {
            var el = document.createElement(node.el);

            node.id === undefined ? false : el.id = node.id;

            if (node.theclass != undefined) {
                node.theclass = node.theclass.split(" ");
                for (var i in node.theclass)
                    classie.add(el, node.theclass[i]);
            }

            if (node.data != undefined) {
                for (var i in node.data) {
                    el[Object.keys(node.data)[0]] = node.data[i]
                    // var attr = document.createAttribute(i);
                    // // Don't use setAttribute( string, string) cause
                    // // doesn't respect the uppercase
                    // if(node.data[i])
                    //     attr.value = node.data[i];
                    // el.setAttributeNode(attr);
                }
            }


        }
    }
    if( typeof toWrite != "undefined" )
        toWrite.appendChild( el );
        

    /*
        Permette di chiamare i setter degli HTMLElement
        Es.: newEl('div').call('textContent','testo di esempio')
    */
    el.call = function(prop,value){
        prop in el? (el[prop] = value): console.log('Key not defined')
        return el
    }

    /*
        Permette di inserire un HTMLElement più elementi in un volta.
        È usato insieme al repeatEl
        Es.:
        newEl('div,,photo')
            .appendChildren(repeatEl('img',3,
                {
                    src:[
                        "uploads/IMG_20180607_185856.jpg",
                        "uploads/IMG_20180611_135255.jpg",
                        "uploads/prova1.jpg"
                    ]
                }
            )),
    */
    el.appendChildren = function(array){
        array.forEach(element => {
            this.appendChild(element)    
        });

        return el
    }

    return el;
}



/*
    Permette di ripete la funzione newEl per n volte
    rispettando i data-set. Restituisce un array ricavato
    da una HTMLCollection (Array.from())
    
    repeatEl('a',2, {"data-init": ["prova","prova2"], "href": ["link1","link2"]})
    ↓       ↓           ↓
    [
        <a data-init="prova" href="link1"></a>,
        <a data-init="prova2" href="link2"></a>
    ]
*/
function repeatEl(el, n, data = null, toWrite = undefined){
    if(data){
        var props = Object.keys(data)
    }
    let d = document.createElement('div')
    for(let i=0;i<n; i++){
        let attrs = {}
        if(props){
            for(c=0;c<props.length;c++){
                let key = props[c]
                attrs[key] = data[key][i]
            }
        }

        let e = newEl({el:el, data: attrs}, toWrite)
        d.appendChild(e)
    }
    return [].slice.call(d.children)
}



/*
 * classie - class helper functions
 * from bonzo https://github.com/ded/bonzo
 * 
 * classie.has( elem, 'my-class' ) -> true/false
 * classie.add( elem, 'my-new-class' )
 * classie.remove( elem, 'my-unwanted-class' )
 * classie.toggle( elem, 'my-class' )
 * Multiple classes are accepted in add, remove and toggle methods if they
 * are separated from ',' (comma)
 */

window.classie = undefined;

//if( CLASSIE_ENABLE ){
window.classie = {
    //     if( 'classList' in document.documentElement )
    add : function( el, classes ){
        if( !( el instanceof HTMLElement) )
            console.error(`--- Need an HTMLelement to add class`);
        
        if( this.has(el, classes) )
            return;
        
        classes=classes.trim();
        if( classes.indexOf(",") > -1 ){
            classes = classes.split(",");
            for( let i=0; i< classes.length; i++){
                if( !this.has( el, classes[i] ) )
                    el.className += classes[i];
            }
        }
        else
            el.className += ' ' + classes;
        
        el.className = el.className.trim();
        return el;  
    },

    remove: function(el, classes){
        if( !( el instanceof HTMLElement) )
            console.error(`--- Need an HTMLelement to add class`);
        
        if( !this.has(el, classes) )
            return;
        
        classes=classes.replace(' ','')
        if( classes.indexOf(",") > -1 ){
            classes = classes.split(",");
            for( let i=0; i< classes.length; i++){
                if( this.has( el, classes[i] ) ){
                    let classReg = new RegExp("(^|\\s+)" + classes[i] + "(\\s+|$)");
                    el.className = el.className.replace(classReg, ' ');
                }
            }
            el.className = el.className.trim();
        }
        else{
            let classReg = new RegExp("(^|\\s+)" + classes + "(\\s+|$)");
            el.className = el.className.replace(classReg, ' ');
        }

        return el;
    },

    has: function( el, theclass ){
        if( !( el instanceof HTMLElement) )
            console.error(`--- Need an HTMLelement to add class`);

        let classReg = new RegExp("(^|\\s+)" + theclass + "(\\s+|$)");
        return classReg.test(el.className);

    },

    toggle: function(el, classes){
        if( !( el instanceof HTMLElement) )
            console.error(`--- Need an HTMLelement to add class`);

        classes = classes.replace(' ','')
        if( classes.indexOf(",") > -1 ){
            classes = classes.split(",");
            for( let i=0; i< classes.length; i++){
                if( this.has( el, classes[i] ) )
                    this.remove(el, classes[i]);
                else
                    this.add(el, classes[i]);                  

            }
            el.className = el.className.trim();
        }
        else{
            if( this.has(el, classes) )
                this.remove(el, classes)
                else
                    this.add(el, classes)
                    }

        return el;
    }
}

