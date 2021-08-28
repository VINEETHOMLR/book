<?php

/**
 * @author 
 * @desc To describe an example of Model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;

/**
 * @author 
 */
class Login extends Database {

    /**
     *
     * @var Resource
     */
    public $conn;

    public function __construct() {
        parent::__construct(Raise::params()['db']);
        $this->conn = $this->getConnection();
        $this->tableName = 'admin_user';
    }

    public function login($user,$pass){
          
        $pass=md5($pass); 

        $login=$this->callsql("SELECT * FROM $this->tableName WHERE username='$user' && password='$pass' AND status='1' ","row");

        if(!empty($login)){
      
           $_SESSION['INF_adminID']   = $login['id'];
           $_SESSION['INF_userName']  = $login['username'];
           $_SESSION['INF_role']      = $login['role'];
           $_SESSION['INF_status']    = $login['status'];

           $_SESSION['INF']      = "Admin";

           $serv_group=$login['privileged_group'];
           $serv_arr=json_decode($serv_group); 
           $privilage=array();
           $privilage[0]="";
            if(!empty($serv_arr)){
              foreach ($serv_arr as $value) { 
                 $privilage[0].=$this->callsql("SELECT services FROM admin_services WHERE id = '$value'","value").","; 
              }
              $privilage=array_values($privilage);
            }
           
           $_SESSION['INF_privilages'] = array_unique($privilage); 


           $r=session_id(); $time=time(); $ip=$_SERVER['REMOTE_ADDR'] ; 

           $this->callsql("UPDATE $this->tableName SET current_logintime='$time',createip='$ip' WHERE id='$login[id]'");

           $stmt   = "INSERT INTO admin_login_log SET admin_id ='$login[id]' , session_id ='$r' ,login_time= '$time' , login_ip='$ip', login_status='0' ,last_active_time='$time' ";

           $this->query($stmt);
           $this->execute();

            return true;
        }else{
            return false;
        }
    }

    public function logout(){

        $id=$_SESSION['INF_adminID']; $time=time(); $ip=$_SERVER['REMOTE_ADDR'] ; 

        $this->callsql("UPDATE $this->tableName SET last_logintime='$time',last_login_ip='$ip' WHERE id='$id'");

        $stmt   = "UPDATE admin_login_log SET logout_type ='0' ,logout_time= '$time' , logout_ip='$ip', login_status='1' ,last_active_time='$time' WHERE admin_id='$id' order by id DESC limit 1 ";

        $this->query($stmt);
        $this->execute();

        return true;
    }



}
