<?php

    include_once("../db/query.php");
    include_once("../db/connect.php");
    include_once("report.php");



    $reports = array();
    $param = 'Grottaglie';
    $stmt = $conn->prepare(QUERY_REPORT_BY_CITY);
    // $param = 'quijgj6bIz1';
    // $stmt = $conn->prepare(QUERY_REPORT_BY_CDT);
    $stmt->bind_param("s",$param);

    $stmt->execute();

    $result = $stmt->get_result();


    while($row = $result->fetch_assoc()){
        array_push($reports, new Report($row));
    }

    $result = array();
    foreach($reports as $key=>$value){
        array_push($result, $reports[$key]->serialize());
    }

    // var_dump($result);
    header('Content-Type: application/json');
    echo json_encode($result);

?>