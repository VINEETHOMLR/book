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
class Settings extends Database {

    /**
     *
     * @var Resource
     */
     public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "site_data";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
    }
   public function adminActivityLog($activity){

       $time=time();

        $this->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminID' , action ='$activity' , created_at= '$time' , created_ip='$this->IP' ");
        $this->execute();

        return true;
    }
    public function updateSettings($btc,$usdt,$eth)
    {
        $array= array('btc'=>$btc,'usdt'=>$usdt,'eth'=>$eth);
        $array=json_encode($array);
        
        $this->query("UPDATE `site_data` SET `data`='$array' WHERE keyvalue='withdrawal_fee'");
        $this->execute();
      
        if($this->execute()){
            
          
            $act  = "withdrawal Fee Updated";
            $this->adminActivityLog($act);

            return true;
        }
        else
        
            return false; 
    }
    public function updateAddress($btc,$eth)
    {
		$this->query("UPDATE `site_data` SET `data`='$btc' WHERE keyvalue='btc_masteraddress'");
        $this->execute();
		
		$this->query("UPDATE `site_data` SET `data`='$eth' WHERE keyvalue='eth_masteraddress'");
        $this->execute();
        
        if($this->execute()){
            $act="Master Address updated";
            $this->adminActivityLog($act);

            return true;
        }
        else
            return false; 
	} 

	
    public function getSiteData($keyvalue){
		return $this->callsql("SELECT data FROM `site_data` WHERE keyvalue='$keyvalue'",'value');
	}
    public function getsiteKeys(){
        return $this->callsql("SELECT keyvalue,data FROM site_data WHERE keyvalue IN ('game_service','deposit_service','withdrawal_service','swap_service','financial_service')",'rows');
    }
    public function SwitchStatus($keyvalue)
    {
        $data=$this->callsql("SELECT data FROM site_data WHERE keyvalue='$keyvalue'",'value');
        if($data=='1'){
        $this->query("UPDATE `site_data` SET `data`='2' WHERE keyvalue='$keyvalue'");
        $this->execute();
        
        if($this->execute()){

            $act= ucfirst(str_replace('_', ' ',$keyvalue))." Disabled";
            $this->adminActivityLog($act);
            $update=array('result'=>"true",'message'=>$act);
            return $update;
        }
        
        else
            return false; 
        }
        else{
        $this->query("UPDATE `site_data` SET `data`='1' WHERE keyvalue='$keyvalue'");
        $this->execute();
        
        if($this->execute()){

            $act=ucfirst(str_replace('_', ' ',$keyvalue))." Enabled";
            $this->adminActivityLog($act);
            $update=array('result'=>"true",'message'=>$act);
            return $update;
            
        }
        
        else
            return false; 

        }
    }
}
