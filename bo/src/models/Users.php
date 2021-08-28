<?php

/**
 * @author 
 * @desc <Core>Auto Generated model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;
use src\lib\Router;
//use src\lib\RRedis;

class Users extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "user";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;

       // $this->redis  = new RRedis();
    }

     public function getUserList($data){

        $where = ' WHERE id!=0 ';

        if(!empty($data['username'])){

            $where .= " AND username LIKE '%$data[username]%'";
        }

        if($data['status']!=""){
            $where .= " AND status = '$data[status]' ";
        }

      
        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $where .= " AND created_at BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM $this->tableName $where ","value");

        $result['data'] = $this->callsql("SELECT id,username,fullname,status,created_at,email_verification_status,last_login_time FROM $this->tableName $where ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");

        foreach ($result['data'] as $key => $value) {

            $checked = (empty($value['status'])) ? '' : 'checked';
           
            $result['data'][$key]['id'] = $value['id'];
            $result['data'][$key]['time'] = date("d-m-Y H:i:s",$value['created_at']);
            $result['data'][$key]['lasttime'] = date("d-m-Y H:i:s",$value['last_login_time']);
            $result['data'][$key]['username'] = '<a class="badge outline-badge-primary" href="'.BASEURL.'Users/Account/?user='.$value['id'].'">'.$value['username'].'</a>';
            $result['data'][$key]['userStatus'] =  '<label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                                            <input type="checkbox" '.$checked.'>
                                                            <span class="slider round" id="swId'.$value["id"].'" onclick="switchStatus('.$value["id"].','.$value["status"].');"></span>
                                                        </label>';
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        
        return $result;
    }


    public function getuserdetails($id){

          $user['data'] = $this->callsql("SELECT * FROM user WHERE id='$id'",'row');
          $user['info'] = $this->callsql("SELECT * FROM user_info WHERE user_id='$id'",'row'); 
          $user['wallet'] = $this->callsql("SELECT * FROM user_wallet WHERE user_id='$id'",'row');  
          return $user;
    }

      public function updateUser($data){

        $time=time();

       $this->query("UPDATE user SET  fullname='$data[fullName]',username='$data[userName]',updated_at='$time',updated_ip='$this->IP',updated_by='$this->adminID' WHERE id='$data[edit]'");

        if($this->execute()){

          $id = $data['edit'];
          $activity="Edited Details of User ID ".$id;
          $this->adminActivityLog($activity);

          return true;
        }else
          return false;
    }
      
    
    public function adminActivityLog($activity){

        $time=time();

        $this->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminID' , action ='$activity' , created_at= '$time' , created_ip='$this->IP' ");
        $this->execute();

        return true;
    }

    public function getUserActivity($data){

        $where = ' WHERE id!=0 ';

        if(!empty($data['userID'])){

            $user = $this->callsql("SELECT id FROM `user` WHERE id LIKE '$data[userID]%' ","rows");
            $users = array_column($user, 'id');
            $list = empty($users) ? 0 : implode(',', $users);

            $where .= " AND user_id IN ($list) ";  
        }

        if(!empty($data['fullname'])){

            $user = $this->callsql("SELECT id FROM `user` WHERE fullname LIKE '%$data[fullname]%' ","rows");
            $users = array_column($user, 'id');
            $list = empty($users) ? 0 : implode(',', $users);

            $where .= " AND user_id IN ($list) ";  
        }

        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $where .= " AND (created_at BETWEEN '$data[datefrom]' AND '$data[dateto]') ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM `user_activity_log` $where ","value");

        $result['data']=$this->callsql("SELECT * FROM `user_activity_log` $where ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");

        foreach ($result['data'] as $key => $value) {

              $user = $this->callsql("SELECT id,fullname FROM `user` WHERE id = '$value[user_id]' ","row");

              $result['data'][$key]['fname'] = ucfirst($user['fullname']);
              $result['data'][$key]['time'] = date("d-m-Y H:i:s",$value['created_at']);
              $result['data'][$key]['id'] = $user['id'];
        }

        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        
        return $result;
  }

  public function getDepositData($user){
      $fromDate = strtotime(date("Y-m-d 00:00:00",strtotime("-7 days")));
      $endDate  = time();

      return $this->callsql("SELECT * FROM `deposit` WHERE user_id='$user' AND created_at BETWEEN $fromDate AND $endDate ORDER BY created_at DESC",'rows');
    }

    public function getWithdrawData($user){
      $fromDate = strtotime(date("Y-m-d 00:00:00",strtotime("-7 days")));
      $endDate  = time();

      return $this->callsql("SELECT * FROM `coin_withdrawal` WHERE user_id='$user' AND created_at BETWEEN $fromDate AND $endDate ORDER BY created_at DESC",'rows');
    }
	
	public function getCoinWalletbalance($user){

      $result['data'] = $this->callsql("SELECT id,coin_name,coin_code,value FROM `coin` WHERE status=1 ",'rows');
	  
	  $resp = array();
	  
	  foreach ($result['data'] as $key => $value) {

              $balance = $this->callsql("SELECT `".$value['coin_code']."_wallet` FROM `user_wallet` WHERE user_id = '$user' ","value");
			  
			  $resp['data'][$key]['id'] 		= $value['id'];
              $resp['data'][$key]['coin_name'] 	= $value['coin_name'];
              $resp['data'][$key]['coin_code'] 	= $value['coin_code'];
              $resp['data'][$key]['value'] 		= $value['value'];
			  $resp['data'][$key]['balance'] 	= $balance;
        }
		
		return $resp;
    }

    public function getSum($user){

      $total['withdraw'] = $this->callsql("SELECT IFNULL(SUM(amount),0) FROM `coin_withdrawal` WHERE user_id='$user' AND status=2",'value');
      $total['deposit'] = $this->callsql("SELECT IFNULL(SUM(amount),0) FROM `deposit` WHERE user_id='$user' AND status=2",'value');

      return $total;
    }

    public function getuniqueUser($user){
     
        $data = $this->callsql("SELECT username  FROM user WHERE `id`='$user'",'value');
        return $data;
    }

    
}