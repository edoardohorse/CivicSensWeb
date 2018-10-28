<?php



abstract class Admin{
    protected $name;
    protected $reports = array();

    public function getName(){return $this->name;}
    public function setName($name){ return $this->name = $name;}
    public function getReports(){return $this->reports;}
    


    function __construct($name){
        $this->name = $name;
    }


    public function deleteReport($id){
        $tmp = $this->reports[$id];
        unset($this->reports[$id]);
        return $tmp->deleteReport();
    }

    abstract public function serialize();
    abstract public function serializeReports();

}





?>