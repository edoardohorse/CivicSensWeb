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
    
    show(detail, onClose = null){
        if(this.details.indexOf(detail) > -1){
            this.el.appendChild(detail.el)
            this.detailsSelected = detail
            this.detailsSelected.el.classList.remove('details--hide')
            setTimeout(()=>{
                this.detailsSelected.el.classList.add('details--show')
            },200)
            this.detailsSelected.el.querySelector('.details__close').addEventListener('click',this.hide.bind(this, onClose))
        }
    }

    hide(callback){
        if(this.detailsSelected){
            this.detailsSelected.el.classList.add('details--hide')
            this.detailsSelected.el.classList.remove('details--show')
        }

        if(callback)
            callback()
    }
}

class ActionOption{
    constructor(title, callback, description = "" ,isCritic = false){ 
        this.el = newEl('li,, option')
        this.title = title,
        this.description = description
        this.isCritic = isCritic
        this.action = callback

        this.el.textContent = this.title
        this.el.title = this.description
        this.el.addEventListener('click',_=>{callback()})

        if(this.isCritic)
            this.el.classList.add('option--critic')
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
        
        el.addEventListener('blur',this.closeOption.bind(this,el))
        el.focus()
        el.classList.toggle("show");
    }

    closeOption(el){
        el.removeEventListener('blur',this.closeOption)
        el.classList.remove("show");

        
    }

    

    setTitle(title, options = null){
        this.titleEl = newEl('nav,,details__title col-12')
        let titleText = newEl('span,, details__title__text', this.titleEl)
        newEl('span,,ellipsis',titleText).call('textContent',title)
        
        
        
        let optionsEl = newEl('div,,details__options', this.titleEl)
        let button  = newEl('button,, dropbtn', optionsEl )
        newEl('i,,details__close', optionsEl)
        button.classList.add('dropbtn')     

        let content  = newEl('div,,dropdown-content',optionsEl)
        content.tabIndex = 0
        for(let action of options){
            content.appendChild(action.el)
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

    swapItem(item, newItem){

    }

    addRow(...items){
        let row = newEl('div,, details__row', this.bodyEl)
        
        for(let item of items){
            this.addItem(row, item)
        }

    }
}
