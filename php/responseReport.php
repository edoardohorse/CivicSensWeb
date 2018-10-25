<?php

include_once("query.php");
include_once("connect.php");
include_once("report.php");
include_once("team.php");

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

$getListOfTeams = function(){
    global $conn;

    $stmt = $conn->prepare(QUERY_FETCH_LIST_TEAM);
    $teams = array();
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        array_push($teams, $row['name']);
    }


    reply('',false, $teams);

};

$getReportsByCity = function($city){
    global $conn;
   
    $stmt = $conn->prepare(QUERY_REPORT_BY_CITY);
    $stmt->bind_param("s",$city);
    
    $result = getReports($stmt);

    reply(MessageSuccess::NoMessage,false,$result);

};

$getReportsByTeam = function($teamName){
    $team = new Team($teamName);
    $team->fetchReports();      // TODO: to remove
    reply(MessageSuccess::NoMessage,false,$team->serializeReports());

};

$getReports = function($stmt){
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
};


$getReportById = function($id){
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
};

$getPhotosOfReport = function($id){
    $report = getReportById($id);
    $report->fetchPhotos();
    // $reportStr = $report->serialize();

    reply(MessageSuccess::NoMessage,false,$report->getPhotos());
};

$getHistoryOfReport = function($id){
    $report = getReportById($id);
    $report->fetchHistory();
    // $reportStr = $report->serialize();

    reply(MessageSuccess::NoMessage,false,$report->getHistory());
};


$editTeam = function($id, $newTeam){
    $report = getReportById($id);
    if($report->editTeam($newTeam)){
        reply(MessageSuccess::EditTeam,false);
    }
    else{
        reply(MessageError::EditTeam,true);
    }
};

$editState = function($id, $newState){
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
};

$deleteReport = function($id){ //TODO: da togliere il team
    $team = new Team(teamName);
    $team->fetchReports();
    
    if($team->deleteReport($id)){
        reply(MessageSuccess::DeleteReport,false);
    }
    else{
        reply(MessageError::DeleteReport,true);
    }
};

$updateHistory = function($id, $message){
    $team = new Team(teamName);
    $team->fetchReports(); 
    
    if($team->updateHistoryOfReport($id,$message)){
        reply(MessageSuccess::UpdateHistory,false);
    }
    else{
        reply(MessageError::UpdateHistory,true);
    }
};

$newReport = function(array $data){
   $report = Report::newReport($data);
   reply(MessageSuccess::AddedReport,
            false,
            array('cdt'=>$report->getCdt())
        );
   
};

$deleteReports = function(array $data){
    global $response;
    $ids = json_decode($data['id']);

    foreach($ids as $value){
        deleteReport($value);
    }

    reply(MessageSuccess::DeleteReports,false);
};

$getEnte = function(){
    $e = new Ente('ente');      //TODO: da rimuover
    reply('',false,$e->serialize());
};
$getTeams = function(){
    $e = new Ente('ente');      //TODO: da rimuover
    reply('',false,$e->serializeTeams());
};

$getAllReports = function(){
    $e = new Ente('ente');      //TODO: da rimuover
    reply('',false,$e->serializeReports());
};

?>