<?php

/**
 * @author 
 * @desc <RaiseGen>Auto Generated model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;
use src\lib\Router;

/**
 * @property int(10) $id
 * @property varchar(20) $name
 * @property varchar(20) $description
 * @property int(10) $status
 * */
class Admin extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "admin_user";
        $this->assignAttrs();
        $this->admin=$_SESSION['INF_adminID'];
        $this->perPage = 10;
    }

    /**
     * 
     * @return Array
     */
    public static function attrs() {
        return ["id", "username", "name","email","mobile","country","password","role","allowed_ip","privileged_group","current_logintime","last_login_time","fail_login_count","create_time","create_by","create_ip","update_time","update_by","update_ip","status"];
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
            $values{$aAttr}= $isExternal ? $attr : (Router::post($attr) !== "" ? Router::post($attr) : "");
        }
        //return $this;
        return $values;
    }

    public function getLogin($id){

        $data=$this->callsql("SELECT login_time,login_ip FROM admin_login_log WHERE admin_id='$id' ORDER BY id DESC LIMIT 1","row");
        return $data;
    }

    public function getAdmin() {   
       
       return $this->callsql( "SELECT id,username,name,email,current_logintime,createip as current_ip,last_logintime,last_login_ip FROM $this->tableName WHERE id='$this->admin'","row");
    }

    public function getadminList($data){

        $where = " WHERE id!='$this->admin' AND status !=3 "; 

        if(empty($_SESSION['INF_role'])){
            $where .=" AND createby='$this->admin'";
        }

        if($data['status'] !=""){
           
            $where .= " AND status = '$data[status]' ";
        }

        if(!empty($data['username'])){
           
            $where .= " AND username LIKE '%$data[username]%' ";
        }

        if(!empty($data['datefrom']) && !empty($data['dateto'])){ 

            $where .= " AND current_logintime  BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        } 

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM $this->tableName $where ","value");

        $result['data'] = $this->callsql("SELECT id,username,name,email,status,createtime,last_logintime,last_login_ip FROM $this->tableName $where ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");

        foreach ($result['data'] as $key => $value) {

            $loginData=$this->getLogin($value['id']);
            $login=(date("d-m-Y H:i:s",$loginData['login_time']))."<br>".$loginData['login_ip'];
            $login = (empty($loginData['login_time'])) ? "-" : $login;
            $createTime = (empty($value['create_time'])) ? "-" : date("d-m-Y H:i:s",$value['createtime']);
            $lastSeen = (empty($value['last_logintime'])) ? "-" : date("d-m-Y H:i:s",$value['last_logintime']);

            $checked = empty($value['status']) ? '' : 'checked';
            
            $result['data'][$key]['login']  = $login;
            $result['data'][$key]['create'] = $createTime;
            $result['data'][$key]['lastSeen'] = $lastSeen."<br>".$value['last_login_ip'];
            $result['data'][$key]['action'] = '<ul class="table-controls">
                                                <li><a href="'.BASEURL.'Admin/Profile/?admin='.base64_encode($value['id']).'" data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                                <li><a onclick="DeleteAdmin('.$value['id'].')" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                            </ul>';
            $result['data'][$key]['Status'] = '<label class="switch s-primary mb-0">
                                                   <input type="checkbox" '.$checked.'><span class="slider round" onclick="switchStatus('.$value["id"].','.$value['status'].');"></span>
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

    public function insertAdmin($params){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR'];
        
        $query=$this->query("INSERT INTO $this->tableName SET username='$params[username]',name='$params[name]',password='$params[password]',email='$params[email]',role='0',createtime='$time',createby='$this->admin',createip='$ip',status='1',privileged_group='$params[services]'");

        $this->execute($query);
        if($this->rowCount()>0){

            $adminuseranme = $this->callsql("SELECT username FROM admin_user WHERE id='$this->admin' ",'value');
            $act=$adminuseranme." created new admin ".$params['username'];
            $this->adminActivityLog($act);
            return true;
        }else
            return false;
    }
    public function memberInfoEdit($post){

         $time=time(); $ip=$_SERVER['REMOTE_ADDR']; 

         if(($_SESSION['INF_adminID']==$post['admin_id'])){

            $this->query("UPDATE admin_user SET username='$post[username]', name='$post[name]',email='$post[email]',updatetime='$time',updateby='$this->admin',updateip='$ip' WHERE id='$post[admin_id]'");
            $this->execute();

         }else{
            $this->query("UPDATE admin_user SET username='$post[username]', name='$post[name]',email='$post[email]',privileged_group='$post[services]',updatetime='$time',updateby='$this->admin',updateip='$ip' WHERE id='$post[admin_id]'");
            $this->execute();
         }

        $activity=$post['username']." Details Edited"; 
        $this->adminActivityLog($activity);
          
        return true;

    }

    public function getService($ser_id){ 
          
        $sql = "SELECT * FROM admin_services WHERE id='$ser_id' ";
        $this->query($sql);
        return $this->resultset();
    }

    public function checkServiceName($name,$sId){
      if(empty($sId)){
        $data = $this->callsql("SELECT group_name FROM `admin_services` WHERE  group_name='$name'","value") ;
      }else{
        $data = $this->callsql("SELECT group_name FROM `admin_services` WHERE  group_name='$name' AND id!='$sId'","value") ;
      }
      return $data;
    }

    public function getServiceArray(){

        $this->query("SELECT * FROM admin_services");
        $this->execute(); 
         $res = array();
        foreach($this->resultset() as $row){  
          
            $res['rows'][] = 
            array( 
                'group_name'=>$row['group_name'],
                'id' => $row['id']);
          
        }

        return $res;
    }


    public function serviceAdd($data){
        
           $newServiceVal = implode(',', $data['services']);
           $stmt   = "INSERT INTO admin_services SET group_name ='$data[servicegrpname]' , services='$newServiceVal'  ";
           $this->query($stmt);
           $this->execute();

           $ServiceId = $this->lastInsertId();

           $adminSer = $this->callsql("SELECT privileged_group FROM `admin_user` WHERE role=1 ","value");
           $ad_services = json_decode($adminSer,true);
           array_push($ad_services, $ServiceId);

           $this->callsql("UPDATE `admin_user` SET privileged_group='".json_encode($ad_services)."' WHERE role=1 ");
           
           $adminuseranme = $this->callsql("SELECT username FROM admin_user WHERE id='$this->admin' ",'value');
           $act=$adminuseranme." Service added ";
           $this->adminActivityLog($act);
           
          return true;
    }

    public function serviceUpdate($data){
           
           $newServiceVal = implode(',', $data['services']);
           $stmt   = "UPDATE admin_services SET group_name ='$data[servicegrpname]' , services='$newServiceVal' WHERE id='$data[servegrpid]' ";
           $this->query($stmt);
           $this->execute();
           
           $adminuseranme = $this->callsql("SELECT username FROM admin_user WHERE id='$this->admin' ",'value');
           $act=$adminuseranme." Service Edited ";
           $this->adminActivityLog($act);

          return true;
    }

    public function adminActivityLog($activity){


        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->admin;
  
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , created_at= '$time' , created_ip='$ip' ";

        $this->query($stmt);
        $this->execute();

        return true;
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

    public function getUsers()
    {
        $total = $this->callsql("SELECT COUNT(*) AS TC  FROM user WHERE status IN (0,1)",'value');
        $inactive = $this->callsql("SELECT COUNT(*) AS AC FROM user WHERE status=0",'value');
        $active = $this->callsql("SELECT COUNT(*) AS IC FROM user WHERE status=1",'value');

        $data = array('total' => $total,'active' => $active,'inactive' => $inactive);
        return $data;

    }
    public function walletBalance(){
      
        return $this->callsql("SELECT IFNULL(SUM(btc_wallet),0) as btc_wallet, IFNULL(SUM(usdt_wallet),0) as usdt_wallet, IFNULL(SUM(eth_wallet),0) as eth_wallet FROM user_wallet","row");

    }

    public function UpdateSlotStatus($id,$status){

      $this->query("UPDATE `admin_user` SET `status` = '$status' WHERE `id`='$id'");
      $this->execute();

      return true;
    }

    public function deleteAdmin($id){

        $this->query("UPDATE `admin_user` SET `status` = 3 WHERE `id`='$id'");
        $this->execute();

        return true;
    }

    public function getActivities()
    {
        $data = $this->callsql("SELECT action,createtime FROM admin_activity_log WHERE admin_id=$this->admin",'rows');

        return $data;

    }

    public function totalDeposit(){
       
      $coinArr = ['btc','usdt','eth'];

      foreach ($coinArr as $key => $coin) {
         $coinId = $this->callsql("SELECT `id` FROM `coin` WHERE coin_code='$coin'","value");

         $deposit[$coin] =  $this->callsql("SELECT IFNULL(SUM(amount),0)  FROM `deposit` WHERE coin_id = '$coinId' AND status=2 ",'value');
      }

      return $deposit;
    }

    public function totalWithdraw(){
      
      $coinArr = ['btc','usdt','eth'];

      foreach ($coinArr as $key => $coin) {

          $coinId = $this->callsql("SELECT `id` FROM `coin` WHERE coin_code='$coin'","value");
       
          $withdraw[$coin] = $this->callsql("SELECT IFNULL(SUM(amount),0)  FROM `coin_withdrawal` WHERE status=2 AND coin_id = '$coinId' ",'value');
      }

      return $withdraw;
    }

    // public function getPurchasePercentage(){

    //   $data =  $this->callsql("SELECT * FROM `purchase_summary` WHERE `date` BETWEEN ( CURDATE() - INTERVAL 30 DAY ) and CURDATE() ORDER BY `date` ASC",'rows');

    //   foreach ($data as $key => $value) {
        
    //       $jec  = ($value['jac'] / $value['total']) * 100 ;
    //       $cash = ($value['cash'] / $value['total']) * 100 ;
    //       $aqn  = ($value['aqn'] / $value['total']) * 100 ;
    //       $usd  = ($value['usd'] / $value['total']) * 100 ;

    //       $resp['date'][] = $value['date'];

    //       $resp['data']['cash'][$key]['x'] = 'Cash' ;
    //       $resp['data']['cash'][$key]['y'] = round($cash,2) ;
    //       $resp['data']['cash'][$key]['description'] = number_format($value['cash']);

    //       $resp['data']['aqn'][$key]['x'] = 'Aqn' ;
    //       $resp['data']['aqn'][$key]['y'] = round($aqn,2) ;
    //       $resp['data']['aqn'][$key]['description'] = number_format($value['aqn']) ;

    //       $resp['data']['jec'][$key]['x'] = 'Jec' ;
    //       $resp['data']['jec'][$key]['y'] = round($jec,2) ;
    //       $resp['data']['jec'][$key]['description'] = number_format($value['jac']) ;

    //       $resp['data']['usd'][$key]['x'] = 'Usd' ;
    //       $resp['data']['usd'][$key]['y'] = round($usd,2) ;
    //       $resp['data']['usd'][$key]['description'] = number_format($value['usd']) ;

    //   }

    //   if(empty($data)){
    //      $resp['date'] =  $resp['data']['cash'] = $resp['data']['aqn'] = $resp['data']['jec'] = $resp['data']['usd']= array();
    //   }

    //   return $resp;
    // }

    
    public function getSiteDate($field){
        $data =  $this->callsql("SELECT data FROM `site_data` WHERE `keyvalue` = '$field'",'value'); 
        return empty($data)?0:$data; 
    }

    public function getadminActivity($data){

        $where = " WHERE admin_id!='$this->admin' "; 

        if(!empty($data['username'])){

            $where .= " AND admin_id = '$data[username]' ";
        }

        if(!empty($data['datefrom']) && !empty($data['dateto'])){ 

            $where .= " AND created_at  BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        } 

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM `admin_activity_log` $where ","value");

        $result['data']=$this->callsql("SELECT * FROM `admin_activity_log` $where ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");

        foreach ($result['data'] as $key => $value) {
           $username = $this->callsql("SELECT username FROM `admin_user` WHERE `id` = '$value[admin_id]'",'value'); 
           $result['data'][$key]['subAdmin'] = ucwords($username);
           $result['data'][$key]['time'] = date("d-m-Y H:i:s",$value['created_at']);
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        
        return $result;
    }

    public function getSubadmin(){

       return $this->callsql("SELECT username,id FROM `admin_user` WHERE `role` != '1' ",'rows'); 
    }

    public function getActivity($filter){

        $pagecount = ($filter['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM `admin_activity_log` WHERE admin_id='$filter[admin]' ","value");

        $this->query("SELECT * FROM admin_activity_log WHERE admin_id='$filter[admin]' ORDER BY id DESC LIMIT $pagecount,$this->perPage");

        $data = ['data' => $this->resultset()]; 

        if($count==0){
            $data['data'] = array();
        }
        $data['count']   = $count;
        $data['curPage'] = $filter['page'];
        $data['perPage'] = $this->perPage;
        
        return $data;
    } 

    public function getLoginLog($filter){

        $pagecount = ($filter['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM `admin_login_log` WHERE admin_id='$filter[admin]' ","value");

        $this->query("SELECT * FROM admin_login_log WHERE admin_id='$filter[admin]' ORDER BY id DESC LIMIT $pagecount,$this->perPage");

        $data = ['data' => $this->resultset()]; 

        if($count==0){
            $data['data'] = array();
        }
        $data['count']   = $count;
        $data['curPage'] = $filter['page'];
        $data['perPage'] = $this->perPage;
        
        return $data;
    }

}
