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
class Coin extends Database {

    /**
     *
     * @var Resource
     */
     public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "coin";//coin table
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }

    public function getCoinList($data)
    {
        $where = ' WHERE id!=0 ';

        if($data['status']!=""){
            $where .= " AND status = '$data[status]' ";
        }else{
            $where .= " AND status != '2' ";
        }
		 
		if($data['coin_name_search']!=""){
            $where .= " AND coin_name LIKE '%$data[coin_name_search]%' ";
        }
		
		if($data['wallet_group_search']!=""){
            $where .= " AND wallet_group ='$data[wallet_group_search]' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT count(id) FROM coin $where",'value');
        $userStatus = array(0=>"Not Active", 1=>"Active", 2=>"Removed");
        $this->query("SELECT * FROM coin $where ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {
            
            $result['data'][$key]['title'] =$value['coin_name'];
            $result['data'][$key]['id'] =$value['id'] ;
            $result['data'][$key]['coin_code'] =$value['coin_code'];
			$result['data'][$key]['coin_value'] =$value['value'];
			$result['data'][$key]['coin_transfer_value'] =$value['transfer_out_value'];

                if($value['status']==0 || $value['status']==1 || $value['status']==2){
                    $result['data'][$key]['status'] = $userStatus[$value['status']];
                }else{
                    $result['data'][$key]['status'] = "-";
                }
					
                $result['data'][$key]['action'] = '<button class="btn btn-info" id="btn'.$value['id'].'" data-lang="" onclick="showModal('.$value['id'].')">Edit</button>';
                                                
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;
    }

	public function checkCoinexist($data)
    {
       

        $count = $this->callsql("SELECT coin_code FROM coin WHERE coin_code='$data'",'value');
        return $count;
    }

    public function addCoin($data){
	
          
            $this->query(" INSERT INTO `coin` SET `coin_name`='$data[title]',`coin_code`='$data[coin_code]',`status`='1',`value`='$data[value]',`transfer_out_value`='$data[transfer_out_value]',`master_address`='$data[master_address]',`wallet_group`='$data[wallet_group]'");
         
            $new_feild = $data['coin_code']."_wallet";
            $this->execute();
			
			$coinid = $this->lastInsertId();
			
			$act="Added new Coin ,ID ".$coinid;
            $this->adminActivityLog($act);
			
			$this->query("ALTER TABLE `user_wallet` ADD `".$new_feild."` DECIMAL(17,8) NOT NULL AFTER `user_id`");
			
			$this->execute();
           

         return $coinid; 
    }

     public function updateCoin($data){

               $this->query(" UPDATE `coin` SET `coin_name`='$data[title]',`status`='$data[status]',`value`='$data[value]',`transfer_out_value`='$data[transfer_out_value]',`master_address`='$data[master_address]',`wallet_group`='$data[wallet_group]' WHERE `id`='$data[id]'");

        
        if($this->execute()){

           $act="Updated Coin ,ID ".$data['id'];
          $this->adminActivityLog($act);

           return true; 
        }else
          return false; 
    }

    public function deleteCoin($ID){

      $time=time();
      $this->query("UPDATE `coin` SET status='2' WHERE id='$ID'");
      if($this->execute()){
           
         $activity ="Coin ID-".$ID." Deleted";
        
         $this->adminActivityLog($activity);
         return true;
      }else
         return false;
   }

    public function adminActivityLog($activity){


        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
  
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , created_at= '$time' , created_ip='$ip' ";

        $this->query($stmt);
        $this->execute();

        return true;
    }


    



}
