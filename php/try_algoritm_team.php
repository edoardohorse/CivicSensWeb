<?php

    include_once('team.php');
    include_once('ente.php');

    $ente = new Ente('ente');
    $ente->fetchTeams();

    $teams = array_filter($ente->teams, function($t){return $t->getTypeReport() == 'Guasti elettrici' ;});
    

    $nameTeamToRemove = 'Enel4';
    $teamToDelete = null;
    $list = [];
    foreach($teams as $team){
        if($team->getName() == $nameTeamToRemove && $teamToDelete == null){
            $teamToDelete = $team;
            $key = array_search($team, $teams);
            unset($teams[$key]);
        }
        else{
            $list[$team->getName()] = count($team->reports);
        }
    }

        

    $nReportToAssign = count($teamToDelete->reports);
    //var_dump($nReportToAssign);
    // var_dump($list);
    $list = distributeInteger($list,$nReportToAssign);
    //var_dump($list);
    applyToTeams($list,$nReportToAssign);

    function applyToTeams($list,$nReportToAssign){
        foreach($list as $name=>$value){
            if($nReportToAssign <= 0){
                break;
            }
            // echo "<br><br>Team a cui aggiungere: $name".PHP_EOL;
            
            $team = array_filter($teams, function($t) use($name){return $t->getName() == $name;});
            $team = $team[array_keys($team)[0]];

            
            $nReAssigned = abs($value - count($team->reports));
            
            // var_dump($nReAssigned);
            $i=0;
            // var_dump($teamToDelete);
            foreach($teamToDelete->reports as $report){
                
                if($i++ < $nReAssigned){
                    
                    // echo "<br>ID Report di cui cambiare il team: {$report->getId()}";
                    // echo "<br>Team del report di cui cambiare il team: {$report->getTeam()}";
                    // var_dump($report->getId());
                    $ente->editTeam($report->getId(), $name);
                    // var_dump($report->getTeam());

                    // echo " ==> {$report->getTeam()}";
                    
                    unset($teamToDelete->reports[array_search($report, $teamToDelete->reports)]);
                }
                else{
                    break;
                }
                
            }
            

            $nReportToAssign -= $nReAssigned;
            // var_dump($team);
        }
    }



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
    
    function distributeInteger($array, $toDistribute){
        
        $copy = $array;
        asort($array);
        sort($copy);

        // var_dump($array);
        // var_dump($copy);
        
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