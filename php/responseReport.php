<?php

include_once("query.php");
include_once("connect.php");
include_once("report.php");
include_once("team.php");
include_once("ente.php");

// const teamName = 'Enel1'; TODO: remove

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

function getListOfTeams(){
    global $conn;

    $stmt = $conn->prepare(QUERY_FETCH_LIST_TEAM);
    $teams = array();
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        array_push($teams, $row['name']);
    }


    reply('',false, $teams);

}

function getReportsByCity($city){
    global $conn;
   
    $stmt = $conn->prepare(QUERY_REPORT_BY_CITY);
    $stmt->bind_param("s",$city);
    
    $result = getReports($stmt);

    reply(MessageSuccess::NoMessage,false,$result);

}

function getReportsByTeam($teamName){
    global $manager;
    $manager->fetchReports();
    reply(MessageSuccess::NoMessage,false,$manager->serializeReports());

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


function editTeam($id){     // FIXME:
    $newTeam = $_POST['newTeam'];

    $report = getReportById($id);
    if($report->editTeam($newTeam)){
        reply(MessageSuccess::EditTeam,false);
    }
    else{
        reply(MessageError::EditTeam,true);
    }
}

function editState($id){
    $newState = $_POST['state'];
    global $manager;
    $manager->fetchReports();
    
    $res = null;

    switch($newState){
        case ReportState::InCharge:{
            $res = $manager->setReportAsInCharge($id); break;}

        case ReportState::Done:{ 
            $res = $manager->setReportAsDone($id); break;}
    }

    if($res){
        reply(MessageSuccess::EditState,false);
    }
    else{
        reply(MessageError::EditState,true);
    }
}

function deleteReport($id){ 
    global $manager;
    if($manager->deleteReport($id)){
        reply(MessageSuccess::DeleteReport,false);
    }
    else{
        reply(MessageError::DeleteReport,true);
    }
}

function updateHistory($id){
    $message = $_POST['history'];

    global $manager;
    $manager->fetchReports();
    
    if($manager->updateHistoryOfReport($id,$message)){
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

function getEnte(){ 
    global $manager;
    $manager->fetchTeams();
    // var_dump($manager);
    reply('',false,$manager->serialize());
}
function getTeams(){ 
    global $manager;
    $manager->fetchTeams();
    reply('',false,$manager->serializeTeams());
}

function getAllReports(){ 
    global $manager;
    $manager->fetchTeams();
    reply('',false,$manager->serializeReports());
}

$getListOfTeams_handler     = 'getListOfTeams';
$getReportsByCity_handler   = 'getReportsByCity';
$getReportsByTeam_handler   = 'getReportsByTeam';
$getReports_handler         = 'getReports';
$getReportById_handler      = 'getReportById';
$getPhotosOfReport_handler  = 'getPhotosOfReport';
$getHistoryOfReport_handler = 'getHistoryOfReport';
$editTeam_handler           = 'editTeam';
$editState_handler          = 'editState';
$deleteReport_handler       = 'deleteReport';
$updateHistory_handler      = 'updateHistory';
$newReport_handler          = 'newReport';
$deleteReports_handler      = 'deleteReports';
$getEnte_handler            = 'getEnte';
$getTeams_handler           = 'getTeams';
$getAllReports_handler      = 'getAllReports';

?>