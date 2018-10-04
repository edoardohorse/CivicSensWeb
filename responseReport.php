<?php

include_once("query.php");
include_once("connect.php");
include_once("report.php");

abstract class MessageSuccess{
    const EditTeam         = 'Modifica del gruppo avvenuta con successo';
    const EditState        = 'Modica dello stato avvenuta con successo';
    const DeleteReport     = 'Segnalazione eliminata';
    const UpdateHistory    = 'Nota aggiunta alla segnalazione';    
}

abstract class MessageError{
    const EditTeam           = 'Modifica del gruppo avvenuta fallita';
    const EditState          = 'Modica dello stato avvenuta fallita';
    const DeleteReport       = 'È stato risconstrato un errore, la segnalazione non è stata eliminata';
    const UpdateHistory      = 'Errore! Nota non aggiunta alla segnalazione';
}

function reply($result, $isInError){
    $response = array('error'=>$isInError, 'result'=>$result)
}

function getReportById($id){

    $stmt = $conn->prepare(QUERY_REPORT_BY_ID);
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc()

    $report = new Report($row);
    $reportStr = $report->serialize();

    reply($reportStr,false);

    return $report;
}

function getPhotosOfReport($id){
    $report = getReportById($id);
    $report->fetchPhotos();
}

function getHistoryOfReport($id){
    $report = getReportById($id);
    $report->fetchHistory();
}


function editTeam($id, $newTeam){
    $report = getReportById($id);
    if($report->editTeam($newTeam)){
        reply(MessageSuccess::EditTeam,false);
    }
    else{
        reply(MessageError::EditTeam,true);
    }
}

function editState($id, $newState){
    $report = getReportById($id);
    if($report->editState($newState)){
        reply(MessageSuccess::EditState,false);
    }
    else{
        reply(MessageError::EditState,true);
    }
}

function deleteReport($id){
    $report = getReportById($id);
    if($report->deleteReport()){
        reply(MessageSuccess::DeleteReport,false);
    }
    else{
        reply(MessageError::DeleteReport,true);
    }
}

function updateHistory($id, $message){
    $report = getReportById($id);
    if($report->updateHistory($message)){
        reply(MessageSuccess::UpdateHistory,false);
    }
    else{
        reply(MessageError::UpdateHistory,true);
    }
}


?>