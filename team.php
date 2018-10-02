<?php

$teams = array( 'Team2'=>2,
                'Team1'=>5,
                'Team4'=>6,
                'Team3'=>9,
                'Team5'=>10);

$nReportToAssign = 20;
$teams = distruteInteger($teams,$nReportToAssign);
var_dump($teams);



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
    
function distruteInteger($array, $toDistribute){

    $copy = $array;
    asort($array);
    sort($copy);

    while($toDistribute > 0){

        [$tmp,$indexEndGroup] = regroup($copy);
        
        
        if(count($copy) == $indexEndGroup)
            $toValue = $toDistribute + $copy[0];
        else
            $toValue = $copy[$indexEndGroup];

        [$tmp, $toDistribute] = sumToGroup($tmp, $toValue,$toDistribute);
        
        $copy = array_replace($copy,$tmp);
    }

    $arrayCombined = array_combine($array, $copy);

    foreach($array as $key=>$value){
        $array[$key] = $arrayCombined[$value];
    }

    return $array;
}

?>