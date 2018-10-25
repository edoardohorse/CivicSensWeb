<?php
include_once('connect.php');
include_once('query.php');

class MessageLogin{
    const PASSWORD_WRONG = 'La password è sbagliata';
    const USER_LOGGED_IN = 'Accesso eseguito';
    const USER_LOGGED_OUT = 'Log out eseguito';
    const USER_NOT_LOGGED = 'Sei un area riservata. Fare l\'accesso';
    const NOT_ENOUGH_PERMISSIONS = 'Non hai i permessi per questa sezione';

    function __construct($m){
        $this->message = $m;
    }

    public function __toString(){
        return $this->message;
    }
}

abstract class TypeUser{
    const Ente = 'Ente';
    const Team = 'Team';
    const User = 'User';
}

abstract class Permission extends TypeUser{
    const Admin = 'Admin';
    const Common = 'Common';
}

class User{

    private $email;
    private $type;
    private $pass;
    private $isLogged = false;
    private $isAdmin = false;

    public function getEmail(){return $this->email;}
    public function getType(){return $this->type;}    
    public function getPass(){return $this->pass;}
    public function isAdmin(){return $this->isAdmin;}
    
    public function setAdmin($admin){$this->isAdmin = $admin;}
    public function setEmail($email){
        $this->email = $email;
        $this->fetchInfo();
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
            $this->pass = $row['password'];
        }

        switch($this->type){
            case TypeUser::User:{
                $this->isAdmin = false;
                break;
            }
            case TypeUser::Ente:{
                $this->isAdmin = true;
                break;
            }
            case TypeUser::Team:{
                $this->isAdmin = true;
                break;
            }
        }
    }

    public function isLogged(){
        return $this->isLogged;
    }
    
    public function signIn(){
        $this->isLogged = true;
    }
    public function logOut(){
        include_once('../try/destroy.php');
    }
    public function changePassword(){}
    
    public function checkPass($pass){
        return $this->pass == MD5($pass);
    }

    static function checkLogIn(){
        session_start();
        if(!isset($_SESSION['user']) || !$_SESSION['user']->isLogged()){
            User::expel( new MessageLogin(MessageLogin::USER_NOT_LOGGED) );
        }
        
    }

    public function checkPermission($perm){
        $m = new MessageLogin(MessageLogin::NOT_ENOUGH_PERMISSIONS);

        $b = $perm == Permission::Admin?
                    $this->type == (Permission::Ente || Permission::Team):
                    $this->type == $perm;
        
        $b? false: $this->expel($m);
    }

    private static function expel(MessageLogin $m){
        echo '<br><br>'.$m.'
        <script>setTimeout(function(){location.href="../../login.html"},3000)</script>
        ';
        die();
    }
}

?>