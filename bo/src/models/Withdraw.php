<?php

/**
 * @author 
 * @desc <Core>Auto Generated model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;
use src\lib\Router;
use src\lib\withdrawClass;
use src\lib\walletClass;

class Withdraw extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "coin_withdrawal";
        $this->assignAttrs();
		$this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];

        global $wallet_decimal_limits;

        $this->decimal_limit = $wallet_decimal_limits;

        $this->withdraw  = new withdrawClass();
        $this->wallet = new walletClass;
    }

    /**
     * 
     * @return Array
     */
    public static function attrs() {
        return ["id","user_id","coin_id","coin_code","amount","service_charge","to_address","trans_hash","status","remarks","created_at","created_by","created_ip","updated_time","updated_by","updated_ip"];
    }

    /**
     * 
     * @return $this
     */
    public function assignAttrs($attrs = []) {
        $isExternal = !empty($attrs);
        foreach (($isExternal ? $attrs : self::attrs()) as $eAttr => $attr) {
            $aAttr = $isExternal ? $eAttr : $attr;
            $this->{$aAttr} = $isExternal ? $attr : (Router::post($attr) !== "" ? Router::post($attr) : "");
        }
        return $this;
    }

    /**
     * 
     * @param INT $pk
     */
    public function findByPK($pk) {
        $dtAry = parent::findByPK($pk);
        foreach ($dtAry as $attr => $val) {
            $this->{$attr} = $val;
        }
        return $this;
    }

    public function history($filter){
      
      $search = 'WHERE id!=0';
      
        if($filter['status']!=""){
            $search .= " AND status = '$filter[status]' ";
        }else{
          if($filter['status']== "0"){
            $search .= " AND status = '0 ";

          }

        }

        if($filter['coin']!=""){
            $search .= " AND coin_id = '$filter[coin]' ";
        }

        if(!empty($filter['username'])){

            $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$filter[username]%' ","rows");
            $users = array_column($user, 'id');
            $list = empty($users) ? 0 : implode(',', $users);

            $search .= " AND user_id IN ($list) ";  
        }
       
        if(!empty($filter['datefrom']) && !empty($filter['dateto'])){
            $search .= " AND created_at BETWEEN '$filter[datefrom]' AND '$filter[dateto]' ";
        }

        if(empty($filter['datefrom']) && empty($filter['dateto']) && ($filter['status']=="") && empty($filter['coin']) && empty($filter['username']))
        {
          $search = "where DATE(FROM_UNIXTIME(created_at)) = CURDATE() ";
        }

        $data['total']['btc'] = $this->callsql("SELECT sum(amount) FROM $this->tableName $search AND coin_id = 1","value");
        $data['total']['eth'] = $this->callsql("SELECT sum(amount) FROM $this->tableName $search AND coin_id = 2","value");
        $data['total']['usdt']= $this->callsql("SELECT sum(amount) FROM $this->tableName $search AND coin_id = 3","value");
     
        $curPage = !empty($filter['page']) ? $filter['page'] : 1;
        $perPage = 50;
        $pagecount = ($curPage - 1) * $perPage;

        $count = $this->callsql("SELECT count(*) FROM $this->tableName $search",'value');

        if(empty($filter['export'])){
          $search .= " ORDER BY id DESC LIMIT $pagecount,$perPage ";

          $data['data']=$this->callsql("SELECT * FROM $this->tableName $search ","rows");

          $data['count']=$count;
          $data['curPage']=$curPage;
          $data['perPage']=$perPage;
        }else{
            $data['data']= $this->callsql("SELECT * FROM $this->tableName $search ORDER BY id DESC",'rows');
        }
          $statusArr= array(0=>'Pending',1=>'Processing',2=>'Approved',3=>'Rejected');
        foreach ($data['data'] as $key => $value) {
            $uid = $value['user_id'];
            $coin_id = $value['coin_id'];
            $data['data'][$key]['coin_name']=$this->callsql("SELECT coin_name FROM coin WHERE id='$coin_id'",'value');
            
            $userDetails=$this->callsql("SELECT username FROM user WHERE id='$uid'",'row');
            $data['data'][$key]['username']      = $userDetails['username'];
            $data['data'][$key]['create_time'] = date("d-m-Y", $value['created_at']); 
             $data['data'][$key]['status'] = $statusArr[$value['status']];

            $coin_code = $this->callsql("SELECT coin_code FROM coin WHERE id='$coin_id'",'value');
           
            $data['data'][$key]['coin_code']=$coin_code;
            $data['data'][$key]['amount'] = $value['amount'];
			if($value['status'] !=2 && $value['status']!=3 ){
			$data['data'][$key]['action'] = '<button class="btn btn-info" onclick="approveThis('.$value['id'].',2)">Approve</button>&nbsp;<br/><button class="btn btn-warning" onclick="approveThis('.$value['id'].',3)">Reject</button>';
			}else
			{
			$data['data'][$key]['action'] = '';	
			}
                
        }
        if($count==0){
            $data['data'] = array();
        }
        
       
        return $data;
    }


    public function getCoinlist(){
      return $clist=$this->callsql("SELECT `id`, `coin_name`,coin_code FROM `coin`","rows");
    }
	
	public function approveWithdrawal($ID){

        $details = $this->callsql("SELECT user_id,coin_id,coin_code,amount,service_charge,to_address FROM $this->tableName WHERE `id`='$ID' ",'row');
        if (!empty($details)) {
            
            $time = time();
            $userId    = $details['user_id'];
            $coin_code = $details['coin_code'];
            $coin_id   = $details['coin_id'];
            $toAddress = $details['to_address'];

            $amount = bcsub($details['amount'] , $details['service_charge'],$this->decimal_limit[$coin_code]);

            $coin_code = strtoupper($coin_code);
            $eth_trans = $this->withdraw->withdraw($userId,$coin_id,$coin_code, $toAddress, $amount,$ID);
            $eth_trans = json_decode($eth_trans, true);
                

                if ($eth_trans['status'] == "error") {

                    $message = $eth_trans['message'];

                    $remark = "Withdrawal Request Failed ";

                    $activity = "Coin Withdrawal Request Failed Amount " . $amount . "  for ID " . $ID . " - " . $message;
                    $this->adminActivityLog($activity);

                    $err_msg = ['status' => "error",'message'=>$message];
                              
                    return $err_msg;

                }else {

                    $trans_status = $eth_trans['message']['status'];
                    $trans_id     = $eth_trans['message']['id'];

                    if($trans_status == 2){ //reject

                        $this->rejectWithdraw($ID);
                        $this->callsql("UPDATE $this->tableName SET `api_status`='$trans_status' WHERE `id`='$ID' ");
                        return true;

                    }else{

                        $hash_Info = $this->withdraw->withdrawStatus($trans_id,$userId,$coin_id);
                        $hash_Info = json_decode($hash_Info, true);
                        
                        $hash_Status =  $hash_Info['message']['status'];

                        if($hash_Status == 2){ //reject

                              $this->rejectWithdraw($ID);
                              $this->callsql("UPDATE $this->tableName SET `api_status`='$hash_Status' WHERE `id`='$ID' ");
                              return true;

                        }else{

                              $transhash =  $hash_Info['message']['remarks'];
                              $remarks   = "Withdraw Request Approved, $transhash";
                    
                              $this->callsql("UPDATE $this->tableName SET `trans_hash`='$transhash', `status`='2',`api_status`='$hash_Status', remarks='$remarks', `updated_by`='$this->adminID', `updated_time`='$time', `updated_ip`='$this->IP' WHERE `id`='$ID' ");

                              $remark = "Withdrawal Request Approved";

                              $activity = "Coin Approved Withdrawal Request Amount " . $amount . "  for ID " . $ID;
                              $this->adminActivityLog($activity);
                              
                              $suc_msg = ['status' => "success",'message'=>$remark];
                              return $suc_msg;
                        }
                    }
                }
        }
    }

    public function rejectWithdraw($ID){

       $details = $this->callsql("SELECT user_id,coin_id,coin_code,amount FROM $this->tableName WHERE `id`='$ID' ",'row');
       if (!empty($details)) {

                $time = time();
                $transType  = 2; //withdraw
                $remarks    = "Withdrawal Request Rejected";
                $walletName = $details['coin_code']."_wallet";

                $credit = $this->wallet->updateWallet($details['user_id'], 0, $transType, $details['amount'], $this->adminID ,$remarks,$walletName,$details['coin_id']);

                if($credit){

                     $this->callsql("UPDATE $this->tableName SET `status`='3', `updated_by`='$this->adminID', `updated_time`='$time', `updated_ip`='$this->IP' WHERE `id`='$ID' ");

                     $act="Reject withdrawal ".$ID;

                     $this->adminActivityLog($act);

                     return 'rejected';
                }
       }
    }

	 public function adminActivityLog($activity){


        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
  
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , created_at= '$time' , created_ip='$ip' ";

        $this->query($stmt);
        $this->execute();

        return true;
    }

}
