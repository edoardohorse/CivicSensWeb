class ManagerDetails{
    constructor(el){
        this.el = el
        this.details = []
        this.detailsSelected = null
    }

    addDetails(detail){
        this.details.push(detail)
    }

    clean(){
        this.el.removeChild(this.detailsSelected)
        this.detailsSelected = null
    }
    
    show(detail){
        if(this.details.indexOf(detail) > -1){
            this.el.appendChild(detail.el)
            this.detailsSelected = detail
            this.detailsSelected.el.classList.remove('details--hide')
            this.detailsSelected.el.querySelector('.details__close').addEventListener('click',this.hide.bind(this))
        }
    }

    hide(){
        this.detailsSelected.el.classList.add('details--hide')
    }
}

class Details{
    constructor(){
        this.titleEl    = null
        this.bodyEl     = null
        this.footerEl   = null
        
        this.el = newEl('aside,,details')
        this.bodyEl = newEl('main,,details__body col-12')
        this.footerEl = newEl('footer,,details__chooser')
    }

    build(){
        this.el.innerHTML = ""
        this.titleEl    ? this.el.appendChild(this.titleEl)   :null
        this.bodyEl     ? this.el.appendChild(this.bodyEl)    :null
        this.footerEl   ? this.el.appendChild(this.footerEl)  :null
    }

    toggleOption(el){
        el.classList.toggle("show");
    }

    setTitle(title, options = null){
        // debugger 
        this.titleEl = newEl('nav,,details__title col-12')
        newEl('span', this.titleEl).call('textContent',title)
        newEl('i,,details__close', this.titleEl)

        
        let optionsEl = newEl('div,,details__options', this.titleEl)
        let button  = newEl('button,, dropbtn', optionsEl )
        button.classList.add('dropbtn')     

        let content  = newEl('div,,dropdown-content',optionsEl)
        for(let value of options){
            newEl('a',content).call('textContent',value)
        }



        button.addEventListener('click',this.toggleOption.bind(this, content))
    }

    setFooter(){}

    addItem(row, item){
        let {label, content, col = 12, divided = false, slider = false} = item
        
        let itemEl = newEl(`article,, details__item col-${col}, data-slider=${slider}`, row)

        if(divided)
            itemEl.setAttribute('divided','')

        if(typeof label == 'string')
            newEl('label', itemEl).call('textContent', label)
        else
            newEl('label', itemEl).appendChild(label)


        if(typeof content == 'string')
            newEl('div', itemEl).call('textContent', content)
        else
            newEl('div', itemEl).appendChild(content)

        
    }

    addRow(...items){
        let row = newEl('div,, details__row', this.bodyEl)
        
        for(let item of items){
            this.addItem(row, item)
        }

    }
}
