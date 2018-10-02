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
    listReportFinished.classList.add(CLASS_LIST_REPORT_HIDDEN)
    listReportNotFinished.classList.remove(CLASS_LIST_REPORT_HIDDEN)

    chooserReportNotFinished.classList.add(CLASS_CHOOSER_SELECTED)
    chooserReportFinished.classList.remove(CLASS_CHOOSER_SELECTED)
}

function showReportFinished(){
    listReportNotFinished.classList.add(CLASS_LIST_REPORT_HIDDEN)
    listReportFinished.classList.remove(CLASS_LIST_REPORT_HIDDEN)

    chooserReportFinished.classList.add(CLASS_CHOOSER_SELECTED)
    chooserReportNotFinished.classList.remove(CLASS_CHOOSER_SELECTED)
}

function disable(el){
    el.style.display ="none"
}

function enable(el){
    el.style.display ="block"
}

function init(){
    [].slice.call(document.querySelectorAll(".report__photo")).forEach(wrapper=>{
        let n = wrapper.childElementCount
        wrapper.style.width = `calc( ${ n * WIDTH_PHOTO}px + ${n}em`;
    })
}


window.addEventListener("DOMContentLoaded",init)