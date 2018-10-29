const CLASS_REPORT_PHOTO_HIDDEN = 'report__photo--hide'
const CLASS_LIST_REPORT_HIDDEN = 'list__report--hide'
const CLASS_CHOOSER_SELECTED = 'chooser__list__report--selected'
const WIDTH_PHOTO = 350

let listReportFinished = document.getElementById("report--finished")
let listReportNotFinished = document.getElementById("report--notfinished")
let chooserReportFinished = document.getElementById("chooser--finished")
let chooserReportNotFinished = document.getElementById("chooser--notfinished")


function toggleReportPhoto(id){
    if(document.getElementById(id).querySelector(".report__photo").classList.contains(CLASS_REPORT_PHOTO_HIDDEN))
        openReportPhoto(id)
    else
        closeReportPhoto(id)
}

function openReportPhoto(id){
    document.getElementById(id).querySelector(".report__photo").classList.remove(CLASS_REPORT_PHOTO_HIDDEN)
}

function closeReportPhoto(id){
    document.getElementById(id).querySelector(".report__photo").classList.add(CLASS_REPORT_PHOTO_HIDDEN)
}

function showReportNotFinished(){
    manager.tableSelected = manager.tableReportNotFinished
    
    chooserReportNotFinished.classList.add(CLASS_CHOOSER_SELECTED)
    chooserReportFinished.classList.remove(CLASS_CHOOSER_SELECTED)
}

function showReportFinished(){
    manager.tableSelected = manager.tableReportFinished

    chooserReportFinished.classList.add(CLASS_CHOOSER_SELECTED)
    chooserReportNotFinished.classList.remove(CLASS_CHOOSER_SELECTED)
}

function disable(el){
    el.style.display ="none"
}

function enable(el){
    el.style.display ="block"
}


// (function(){
//     window.managerDet = new ManagerDetails(document.body)
//     window.team = new Team('Enel1')

   

// })()
function calcPhoto(){
    [].slice.call(document.querySelectorAll(".photo")).forEach(wrapper=>{
        let n = wrapper.childElementCount
        wrapper.style.width = `calc( ${ n * 300}px + ${n+.7}em`;
    })
}



function showReportsEnte(el){
    hideNavSide()

    if(el){
    document.querySelector('p.selected').classList.remove('selected')
    el.classList.add('selected')
    }

    document.querySelector('#list__report__wrapper').classList.remove('list--hide')
    try{
        document.querySelector('#list__team__wrapper').classList.add('list--hide')
    }
    catch{

    }
    managerDet.hide();

    try{
        ente.hideTeamRecap()
    }
    catch{}
}

function showTeamsEnte(el){
    hideNavSide()

    if(el){
        document.querySelector('p.selected').classList.remove('selected')
        el.classList.add('selected')
    }

    document.querySelector('#list__team__wrapper').classList.remove('list--hide')
    document.querySelector('#list__report__wrapper').classList.add('list--hide')
    managerDet.hide();

    manager.hideReportRecap()
    ente.showTeamRecap()
}

function showNavSide(){
    document.querySelector('#nav__side').classList.add('nav__side--show')
    document.querySelector('#overlay').classList.add('overlay--show')
    document.body.style.overflow  = 'hidden';

    document.querySelector('#overlay').addEventListener('click', hideNavSide)
}

function hideNavSide(){
    document.querySelector('#nav__side').classList.remove('nav__side--show')
    try{
        document.querySelector('#overlay').classList.remove('overlay--show')
        document.querySelector('#overlay').removeEventListener('click', hideNavSide)
    }catch{

    }
    document.body.style.overflow  = 'auto';

    
}



vex.dialog.buttons.YES.text = 'Ok'
vex.dialog.buttons.NO.text = 'Annulla'

vex.defaultOptions.className = 'vex-theme-default'