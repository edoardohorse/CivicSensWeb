<?php

$teams = array( 'Team1'=>2,
                'Team2'=>5,
                'Team3'=>6,
                'Team4'=>9,
                'Team5'=>10);

$teams2 = array( 'Team1'=>2,
                'Team2'=>2,
                'Team3'=>2,
                'Team4'=>2,
                'Team5'=>2);

$nReportToAssign = 20;
$teamsIndex = $teams2;
asort($teams);
sort($teamsIndex);
// var_dump($teams);
var_dump($teamsIndex);


function regroup($teamsIndex,$indexStart=0){
    $a = array();
    array_push($a,$teamsIndex[$indexStart]);
    for($i=$indexStart+1;$i<count($teamsIndex);$i++){
        if($teamsIndex[$i] == $teamsIndex[$i-1]){
            array_push($a,$teamsIndex[$i]);
        }
        else{
            break;
        }
    }

    return [$a,$i];
}



function sumToGroup($a, $toValue,$available){
        $i=0;   
        $toSum = abs($a[$i] - $toValue) *count($a);

        if($toSum > $available){
            $toSum = $available;
        }

        while($toSum > 0 ){
            
            $a[$i]++;
            $toSum--;
            $available--;

            if($i+1 == count($a)){
                $i=0;
            }

            else
                $i++;       
    }    
    return [$a,$available];
}
    


while($nReportToAssign > 0){

    [$a,$indexEndGroup] = regroup($teamsIndex);
    
    
    if(count($teamsIndex) == $indexEndGroup)
        $toValue = $nReportToAssign + $teamsIndex[0];
    else
        $toValue = $teamsIndex[$indexEndGroup];

    [$a, $nReportToAssign] = sumToGroup($a, $toValue,$nReportToAssign);
    
    $a = array_replace($teamsIndex,$a);
    $teamsIndex = $a;
    var_dump($teamsIndex);
    var_dump($nReportToAssign);
}
?>