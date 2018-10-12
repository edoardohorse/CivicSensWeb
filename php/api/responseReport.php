<?php

include_once("../db/query.php");
include_once("../db/connect.php");
include_once("../classes/report.php");
include_once("../classes/team.php");

const teamName = 'Enel2';

abstract class MessageSuccess{
    const EditTeam         = 'Modifica del gruppo avvenuta con successo';
    const EditState        = 'Modifica dello stato avvenuta con successo';
    const DeleteReport     = 'Segnalazione eliminata';
    const DeleteReports    = 'Segnalazioni eliminate';
    const UpdateHistory    = 'Nota aggiunta alla segnalazione';    
    const AddedReport      = 'Report aggiunto con successo';    
    const NoMessage        = '';
}

abstract class MessageError{
    const EditTeam           = 'Modifica del gruppo fallita';
    const EditState          = 'Modifica dello stato fallita';
    const DeleteReport       = 'È stato risconstrato un errore, la segnalazione non è stata eliminata';
    const UpdateHistory      = 'Errore! Nota non aggiunta alla segnalazione';
}

function reply($message, $isInError, $data = null){
    global $response;
    $response = array('error'=>$isInError, 'message'=>$message, 'data'=>$data);
}

function getReportsByCity($city){
    global $conn;
   
    $stmt = $conn->prepare(QUERY_REPORT_BY_CITY);
    $stmt->bind_param("s",$city);
    
    $result = getReports($stmt);

    reply(MessageSuccess::NoMessage,false,$result);

}

function getReportsByTeam($team){
    $team = new Team(teamName);
    $team->fetchReports();      // TODO: to remove
    reply(MessageSuccess::NoMessage,false,$team->serializeReports());

}

function getReports($stmt){
    global $conn;
    $reports = array();
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        array_push($reports, new Report($row));
    }

    $result = array();
    foreach($reports as $key=>$value){
        array_push($result, $reports[$key]->serialize());
    }
    
    return $result;
}


function getReportById($id){
    global $conn;
    $stmt = $conn->prepare(QUERY_REPORT_BY_ID);
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $report = new Report($row);
    $reportStr = $report->serialize();

    reply(MessageSuccess::NoMessage,false,$reportStr);

    return $report;
}

function getPhotosOfReport($id){
    $report = getReportById($id);
    $report->fetchPhotos();
    // $reportStr = $report->serialize();

    reply(MessageSuccess::NoMessage,false,$report->getPhotos());
}

function getHistoryOfReport($id){
    $report = getReportById($id);
    $report->fetchHistory();
    // $reportStr = $report->serialize();

    reply(MessageSuccess::NoMessage,false,$report->getHistory());
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
    $team = new Team(teamName);
    $team->fetchReports(); 
    
    $res = null;

    switch($newState){
        case ReportState::InCharge:{
            $res = $team->setReportAsInCharge($id); break;}

        case ReportState::Done:{ 
            $res = $team->setReportAsDone($id); break;}
    }

    if($res){
        reply(MessageSuccess::EditState,false);
    }
    else{
        reply(MessageError::EditState,true);
    }
}

function deleteReport($id){
    $team = new Team(teamName);
    $team->fetchReports();
    
    if($team->deleteReport($id)){
        reply(MessageSuccess::DeleteReport,false);
    }
    else{
        reply(MessageError::DeleteReport,true);
    }
}

function updateHistory($id, $message){
    $team = new Team(teamName);
    $team->fetchReports(); 
    
    if($team->updateHistoryOfReport($id,$message)){
        reply(MessageSuccess::UpdateHistory,false);
    }
    else{
        reply(MessageError::UpdateHistory,true);
    }
}

function newReport(array $data){
   $report = Report::newReport($data);
   reply(MessageSuccess::AddedReport,
            false,
            array('cdt'=>$report->getCdt())
        );
   
}

function deleteReports(array $data){
    global $response;
    $ids = json_decode($data['id']);

    foreach($ids as $value){
        deleteReport($value);
    }

    reply(MessageSuccess::DeleteReports,false);
}


?>