<?php
include_once('../db/connect.php');
include_once('../db/query.php');

abstract class TypeUser{
    const Ente = 'Ente';
    const Team = 'Team';
    const User = 'User';
}

class  User{

    private $email;
    private $type;

    public function getType(){return $this->type;}
    public function getEmail(){return $this->email;}

    function __construct($email = null){
        if($email != null){
            $this->email =  $email;
            $this->fetchInfo();
        }

        
        $this->type = TypeUser::User;
    }

    public function fetchInfo(){
        global $conn;

        $stmt = $conn->prepare(QUERY_LOGIN);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();


        $res = $stmt->get_result();
        // var_dump($res);
        if($res->num_rows > 0){
            $row = $res->fetch_assoc();
            $this->email = $row['email'];
            $this->type = $row['type'];
        }
    }

    public function signUp(){}
    public function signIn(){}
    public function changePassword(){}
    
        

}

?>