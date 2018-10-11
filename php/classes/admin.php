<?php



class Admin{
    public $name;
    private $reports = array();


    function __constructor($name){
        $this->name = name;
    }


    function deleteReport($id){
        
        $tmp = $reports[$id];
        unset($repors[$id]);
        return $tmp->deleteReport();
    }


    
}





?>