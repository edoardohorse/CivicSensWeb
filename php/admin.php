<?php



abstract class Admin{
    protected $name;
    protected $city;
    protected $reports = array();

    public function getName(){return $this->name;}
    public function setName($name){ return $this->name = $name;}
    public function getCity(){return $this->city;}
    public function setCity($city){ return $this->city = $city;}
    public function getReports(){return $this->reports;}
    


    function __construct($name,$city = null){
        $this->name = $name;
        $this->city = $city;
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