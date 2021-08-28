<?php

/**
 * @author 
 * @desc <Core>Auto Generated model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;
use src\lib\Router;

class KycList extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "user_info";
        $this->assignAttrs();
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }

    /**
     * 
     * @return Array
     */
    public static function attrs() {
        return ["id", "user_id", "force_update_password", " profile_pic", "kyc_id_number", "kyc_passport_number", "kyc_id_front_image", "kyc_id_back_image", "kyc_passoprt_image","kyc_handheld_image","kyc_passport_handheld_image","kyc_admin_remarks","kyc_status","forgot_pass_token","forgot_pass_time","forgot_pass_ip","security_pin","enable_fingerprint","remember_token","remember_expiry_at","is_deposit_allowed","is_withdrawal_allowed","is_swap_allowed","is_financial_allowed","created_at","created_by","created_ip","updated_at","updated_by","updated_ip"];
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

    public function getusername($id){
        $data = $this->callsql("SELECT username FROM user WHERE id='$id'",'value');
        return $data;
    }

    public function getKYCList($data){

        $where = ' WHERE id!=0 ';


        if(!empty($data['username'])){
            $usr = $this->callsql("SELECT id FROM user WHERE username LIKE '%$data[username]%' ",'rows');
            $uid = 0;
            foreach($usr as $key => $value){
                $uid = $uid . ',' . $usr[$key]['id'];
            }
            $where .= " AND user_id IN ($uid) ";
        }

        if($data['status'] !=""){
            $where .= " AND kyc_status = '$data[status]' ";
        }

        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $where .= " AND created_at BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;


        $count = $this->callsql("SELECT count(*) FROM $this->tableName $where",'value');
        
        $this->query("SELECT * FROM user_info $where ORDER BY id DESC LIMIT $pagecount,$this->perPage");

        $Status = array(0=>"Not Updated",1=>"Pending", 2=>"Approved", 3=>"Rejected");

        

        $result = ['data' => $this->resultset()];
        
        foreach ($result['data'] as $key => $value) {

            /*$this->urlid=FrontEnd.'web/upload/kyc/'.$value['user_id'].'/'; 
            $this->urlp=FrontEnd.'web/upload/kyc/'.$value['user_id'].'/';*/

            $this->urlid=FrontEnd.'web/upload/kyc/'; 
            $this->urlp=FrontEnd.'web/upload/kyc/';

            
            $username=$this->callsql("SELECT username FROM user WHERE id='$value[user_id]'",'value');
            $fullname=$this->callsql("SELECT fullname FROM user WHERE id='$value[user_id]'",'value');
            
                $result['data'][$key]['username'] =$username ;

                $result['data'][$key]['fullname'] =$fullname ;

                $result['data'][$key]['passport'] = (empty($value['kyc_passport_image'])) ? "-" :

                '<a target="_blank" href="'.$this->urlp.$value['kyc_passport_image'].'" data-lightbox="1"  data-lightbox="pic" data-title=""><img src="'.$this->urlp.$value['kyc_passport_image'].'" width="50px" height="50px"></a>
                ';

                $result['data'][$key]['id'] = (empty($value['kyc_id_front_image'])) ? "-" :
                
                '<a target="_blank" href="'.$this->urlid.$value['kyc_id_front_image'].'" data-lightbox="1"  data-lightbox="pic" data-title=""><img src="'.$this->urlid.$value['kyc_id_front_image'].'" width="50px" height="50px"></a>';

                $result['data'][$key]['id'].=(!empty($value['kyc_id_back_image'])) ? '<a target="_blank" href="'.$this->urlid.$value['kyc_id_back_image'].'" data-lightbox="1"  data-lightbox="pic" data-title=""><img src="'.$this->urlid.$value['kyc_id_back_image'].'" width="50px" height="50px"></a>
                ':'';

                $result['data'][$key]['status'] = $Status[$value['kyc_status']];
                $result['data'][$key]['time']   = (empty($value['created_at'])) ? "-" : date("d-m-Y H:i:s",$value['created_at']);
                $result['data'][$key]['remarks']   = (empty($value['kyc_admin_remarks'])) ? "-" : $value['kyc_admin_remarks'];

                
               if($value['kyc_status']==1){
                   $result['data'][$key]['action'] = '
                       <button class="btn btn-success" onclick="actionReq('.$value['id'].',2)">Approve</button>
                       <button class="btn btn-danger"  onclick="actionReq('.$value['id'].',3)">Reject</button>';
                }else{
                   $result['data'][$key]['action'] ="";
                }
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;

    }

    public function Verify($id,$type,$remrk){
        $time=time();

        $user = $this->callsql("SELECT user_id FROM $this->tableName WHERE id='$id'",'value');

        if($type==2){

           $this->query("UPDATE $this->tableName SET kyc_status='2' , updated_by='$this->adminID', updated_ip='$this->IP',
                                                     updated_at='$time',kyc_admin_remarks='$remrk' WHERE id='$id' ");
           $this->execute(); 
           
           if($this->rowCount()>0){

              $act="Verified Documents of user ".$user;
              $this->adminActivityLog($act);
              return true;
            }else
               return false;
        }else{

            $this->query("UPDATE $this->tableName SET kyc_status='3' , updated_by='$this->adminID', updated_ip='$this->IP',
                                                     updated_at='$time',kyc_admin_remarks='$remrk' WHERE id='$id' ");
            $this->execute();

            if($this->rowCount()>0){
              $act="Rejected documents of user ".$user;
              $this->adminActivityLog($act);
              return true;
            }else
               return false;
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
