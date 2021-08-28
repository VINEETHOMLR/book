<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Admin;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */

class AdminController extends Controller {

    /**
     * 
     * @return Mixed
     */
    public function __construct(){

        $this->mdl = (new Admin);
        $this->pag    = new Pagination(new Admin(),''); 
        
        $this->adminId          = $_SESSION['INF_adminID'];
    }

    public function actionIndex() { 

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username = $this->cleanMe(Router::post('username')); 
         $status   = $this->cleanMe(Router::post('status')); 
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 

         $adminData =$this->mdl->getAdmin(); 

         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "username" => $username,
                  "status"   => $status,
                  "page"     => $page];
        
        $data = $this->mdl->getadminList($filter);

        $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$status."','".$username."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('admin/adminList',['adminData'=>$adminData,'status'=>$status,'datefrom'=>$datefrom,'dateto'=>$dateto,'username'=>$username,'data' => $data, 'pagination'=> $pagination]);
    }

    public function actionAdd() {

        $this->mdl->query("SELECT * FROM admin_services"); 
        $service=$this->mdl->resultset();

        return $this->render('admin/create_admin',['ServicesAr'=>$service]);
    }

    public function actionProfile() {
        
        $id = $this->cleanMe(Router::req('admin'));
        $aID = base64_decode($id);
        $this->mdl->query("SELECT * FROM admin_user WHERE id='$aID'");
        $admin_user = $this->mdl->single();  
        $this->mdl->query("SELECT * FROM admin_services"); 
        $service=$this->mdl->resultset();
        $privilege = '';

        if($admin_user['status']=="1"){ $option1="selected"; $option2=""; }
        else{ $option1=""; $option2="selected"; }

        $service_sel_arr =json_decode($admin_user['privileged_group']); $checked='';
        
        if($_SESSION['INF_adminID']==$aID){ //loggined admin cant edit services
          $statusField = "";

          $privilege = '<td>';

          foreach ($service_sel_arr as $sVal) {
               $gpName = $this->mdl->callsql("SELECT group_name FROM `admin_services` WHERE id='$sVal'","value");
               $privilege .= '<div style="float:left;padding: 0px 20px;"><div class="dot"></div>'.ucwords($gpName).'</div>';
          }

          $privilege .= '</td>';

          $headPrivilege = 'Selected Privilege Group';

        }else{
            if($admin_user['status']=="1"){
                $statusField = '<label class="switch s-primary mb-0 pull-right"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$aID.','.$admin_user['status'].');"></span></label>';
            }else{
                $statusField = '<label class="switch s-primary mb-0 pull-right"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$aID.','.$admin_user['status'].');"></span></label>';
            }

            $privilege = '<td style="width: 20%">
                              <div class="n-chk col-12">
                                  <label class="new-control new-checkbox checkbox-danger" style="color:#515365;">
                                  <input type="checkbox" class="new-control-input" id="CheckBoxAll">
                                  <span class="new-control-indicator"></span>&nbsp;'.Raise::t('app','all_txt').'
                                  </label>
                              </div>
                          </td>
                          <td>';

            foreach ($service as $key => $memval) {
                                 
                  $privilege.= '';
                  if(!empty($service_sel_arr)){ 
                       $checked = (in_array($memval['id'], $service_sel_arr)) ? 'checked' : '';
                  }
                     
                  $privilege.='<div class="n-chk col-md-4" style="margin-top: 5px;float: left;">
                                    <label class="new-control new-checkbox checkbox-info" style="color:#515365;">
                                        <input type="checkbox" name="serviceEditarr[]" class="checkEditmem pull-left new-control-input"  value="'.$memval['id'].'" '.$checked.'>
                                            <span class="new-control-indicator"></span>&nbsp;'.$memval['group_name'].'
                                    </label>
                                </div>
                  ';
            }

            $privilege .= '</td>';

            $headPrivilege = Raise::t('subadmin','selservice_dash');
        }

        return $this->render('admin/admin_edit',['info'=>$admin_user,'privilege'=>$privilege,'statusField'=>$statusField,'headPrivilege'=>$headPrivilege]);
    }

    public function actionUpdateStatus() { 

          $id      = $this->cleanMe(Router::post('id'));
          $status  = $this->cleanMe(Router::post('status'));

          $this->mdl->UpdateSlotStatus($id,$status);

          $activity="Status Updated Admin Id-".$id;
          $this->mdl->adminActivityLog($activity);

          $msg=Raise::t('appointment','S4');
          $this->sendMessage('success',$msg);

          return false;
    }

    public function actionCreateAdmin(){
             
             $fullname = $this->cleanMe(Router::post('name'));
             $username = $this->cleanMe(Router::post('username'));
             $password = $this->cleanMe(Router::post('password'));
             $ConPwd   = $this->cleanMe(Router::post('ConfirmPassword'));
             $email    = $this->cleanMe(Router::post('email'));
             $mobile   = $this->cleanMe(Router::post('mobile'));
             $admnSer  = $_POST['servicearr'];

             $this->emptyCheck($fullname,'Fullname');
             $this->checkExists('name',$fullname);
             $this->usernameCheck($username,'Username');
             $this->checkExists('username',$username);
             $this->emptyCheck($password,'Password');
             $this->emptyCheck($ConPwd,'Confirm Password');
             $this->CheckPwd($password,$ConPwd);
             if(!empty($email)){
               $this->emailvalidate($email,'Email');
               $this->checkExists('email',$email); 
             }
             if(!empty($mobile)){
               $this->validate_mobile($mobile);
               $this->checkExists('mobile',$mobile); 
             }

             $services  = array();

             if(empty($admnSer)){
                $msg=Raise::t('subadmin','sel_service');
                $this->sendMessage('error',$msg);
                return false;
             }  

             foreach ($admnSer as $serviceVal) {
                $services[] =$serviceVal;
             }
             
            $params=array('name'     => $fullname,
                          'username' => $username,
                          'password' => md5($password),
                          'email'    => $email,
                          'mobile'   => $mobile,
                          'services' => json_encode($services));

            $is_updated =$this->mdl->insertAdmin($params); 
            if ($is_updated === true) {
                 $msg=Raise::t('subadmin','admin_suc');
                 $this->sendMessage("success",$msg);
            }else{
                 $msg=Raise::t('subadmin','edit_err_text');
                return $this->sendMessage("error",$msg);
            }

          return false;  
    }

    public function emptyCheck($var,$key){
        if(empty($var)){
         $msg = Raise::t('user','E01',array('key'=>$key));
         $this->sendMessage("error",$msg);
         die();
        }
    }

    private function usernameCheck($var,$key){
       
        if(empty($var)){
          echo $this->sendMessage("error",Raise::t('user','E01',array('key'=>$key))); exit;
        }
        if (count(explode(' ', $var)) > 1) {
          echo $this->sendMessage("error",Raise::t('user','E31',array('key'=>$key))); exit;
        }
        if(strlen($var)<3){
           echo $this->sendMessage("error",Raise::t('user','E33',array('key'=>$key))); exit;
        }

         if (preg_match('/^\d+$/',$var)) {
           echo $this->sendMessage("error",Raise::t('user','E39',array('key'=>$key))); exit();
        }

        // if (!preg_match('/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/',$var)) {
        //    echo $this->sendMessage("error",Raise::t('user','E39',array('key'=>$key))); exit();
        // }
    }

    private function emailvalidate($var,$attr) {

        if (!filter_var($var, FILTER_VALIDATE_EMAIL)) {
         echo $this->sendMessage("error",Raise::t('user','E40',array('key'=>$attr))); exit();
        }

    }

    private function validate_mobile($mobile){
       
       if (!preg_match('/^[0-9]+$/', $mobile)) {
         echo $this->sendMessage("error",Raise::t('subadmin','E1')); exit();
        }

        if(strlen($mobile) != 8){
           echo $this->sendMessage("error",Raise::t('subadmin','E2')); exit;
        }
    }

    public function CheckPwd($pwd,$conPwd){

        if($pwd != $conPwd){
          $msg=Raise::t('subadmin','pwd_missmatch');
          $this->sendMessage("error",$msg);
          die();  
        }
    }

    public function checkExists($key,$checkData){  
    
       $this->mdl->query("SELECT * FROM admin_user WHERE $key='$checkData'");
       $res=$this->mdl->single(); 
       if(!empty($res)>0){
         $msg = Raise::t('user','E17',array('key'=>$key));
         $this->sendMessage("error",$msg);
         die();
       }
    }

    public function actionEditinformation(){

    $admin_name      = $this->cleanMe(Router::post('admin_name')); 
    $admin_user_name = $this->cleanMe(Router::post('admin_user_name'));
    $email           = $this->cleanMe(Router::post('adminEmail'));
    $mobile          = $this->cleanMe(Router::post('admin_mobile')); 
    $status          = $this->cleanMe(Router::post('adminStatus'));
    $admin_id        = $this->cleanMe(Router::post('admin_id')); 


    if($_SESSION['INF_adminID']!=$admin_id){
        if(empty($_POST['serviceEditarr'])){
          return $this->sendMessage('error',Raise::t('subadmin','sel_service'));
        }
    }

    $services = [];

    $this->emptyCheck($admin_name,'Fullname');

    $unique=$this->mdl->callsql("SELECT id FROM admin_user WHERE username='$admin_user_name' AND id!='$admin_id ' ","value");  
    if(!empty($unique)){
        $msg=Raise::t('subadmin','userExist_err'); 
        $this->sendMessage('error',$msg);
        return false;
    }

    if(!empty($email)){
        $this->emailvalidate($email,'Email');
        $unique=$this->mdl->callsql("SELECT * FROM admin_user WHERE email='$email' AND id!='$admin_id ' ","value");  
        if(!empty($unique)){ 
           $msg=Raise::t('subadmin','emailExist_err'); 
           $this->sendMessage('error',$msg);
           return false;
        } 
    }
    if(!empty($mobile)){
        $this->validate_mobile($mobile);
        $uniqueM=$this->mdl->callsql("SELECT * FROM admin_user WHERE mobile='$mobile' AND id!='$admin_id ' ","value");  
        if(!empty($uniqueM)){ 
           $msg=Raise::t('subadmin','mobExist_err'); 
           $this->sendMessage('error',$msg);
           return false;
        } 
    }
   
    if(!empty($_POST['serviceEditarr'])){
      foreach ($_POST['serviceEditarr'] as $serviceVal) {
        $services[] =($serviceVal);
      }
    } 
    $newServiceVal = json_encode($services);
    
    $params=array('name'=>$admin_name,
                  'username'=>$admin_user_name,
                  'services'=>$newServiceVal,
                  'email'   => $email,
                  'mobile'  => $mobile,
                  'status'  => $status ,
                  'admin_id'=>$admin_id);

    $res=$this->mdl->memberInfoEdit($params);

        if($res=="true"){
            $msg=Raise::t('subadmin','edit_success');
            $response=  $this->sendMessage('success',$msg);
        }else{
            $msg=Raise::t('subadmin','edit_err_text');
            $response=  $this->sendMessage('error', $msg );
        }

    echo $response;     
  }

    public function actionLoginData() {

       $id = $this->cleanMe(Router::req('admin'));
       $page = $this->cleanMe(Router::post('page')); 
       $aID = base64_decode($id);

       $page = (!empty($page)) ? $page : '1'; 

       $filter=["admin" => $aID,
                "page" => $page];

        $data = $this->mdl->getLoginLog($filter);

        $response ='';  
        $perPage = 10;
        $slno = ($page-1) * $perPage + 1;

        $logoutArray=array('0'=>'Normal','1'=>'TimeOut');

        if(!empty($data['data'])){
          foreach($data['data'] as $key => $val){
              $login = (!empty($val['login_time']))  ? date("d-m-Y H:i:s",$val['login_time'])   : "-";
              $logout = (!empty($val['logout_time'])) ? date("d-m-Y H:i:s",$val['logout_time']) : "-";
              $response .= '<tr role="row" class="odd">
                              <td>'.$slno++.'</td>
                              <td>'.$val['login_ip'].'</td>
                              <td>'.$login.'</td>
                              <td>'.$logout.'</td>
                              <td>'.$logoutArray[$val['logout_type']].'</td>
                            </tr>';
          }
        }else{
            $response = '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
        }   

        $onclick = "onclick=pageLogin('***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring'); 
        
        if(!empty($pagination)){
           $response .= '<tr> <td colspan="8"><div class="row text-center">
                                <form class="col-md-12" id="user_pagination" method="post">
                                    <div class="dataTables_paginate paging_simple_numbers">
                                      <ul class="pagination">
                                          '.$pagination.'
                                      </ul>
                                    </div>
                                </form>
                        </div></td></tr>';
        }

        echo $response;
    }

    public function actionActivityLog(){

       $id = $this->cleanMe(Router::req('admin'));
       $page = $this->cleanMe(Router::post('page')); 
       $aID = base64_decode($id);

       $page = (!empty($page)) ? $page : '1'; 

        $filter=["admin" => $aID,
                 "page" => $page];

        $activity = $this->mdl->getActivity($filter);

        $response ='';  
        $perPage = 10;
        $slno = ($page-1) * $perPage + 1;

        if(!empty($activity['data'])){
          foreach($activity['data'] as $key => $val){
              $response .= '<tr role="row" class="odd">
                              <td>'.$slno++.'</td>
                              <td>'.$val['created_ip'].'</td>
                              <td>'.date("d-m-Y H:i:s",$val['created_at']).'</td>
                              <td>'.$val['action'].'</td>
                            </tr>';
          }
        }else{
            $response = '<tr><td colspan="4" class="text-center">No Data Found</td></tr>';
        }   

        $onclick = "onclick=pageHistory('***')";
        $pagination = $this->pag->getPaginationString($activity['curPage'],$activity['count'],$activity['perPage'],1, $onclick,'pagestring'); 
        
        if(!empty($pagination)){
           $response .= '<tr> <td colspan="8"><div class="row text-center">
                                <form class="col-md-12" id="user_pagination" method="post">
                                    <div class="dataTables_paginate paging_simple_numbers">
                                      <ul class="pagination">
                                          '.$pagination.'
                                      </ul>
                                    </div>
                                </form>
                        </div></td></tr>';
        }

        echo $response;
    }

    public function actionDelete() { 

          $id      = $this->cleanMe(Router::post('id'));
        
          $this->mdl->deleteAdmin($id);
          $name = $this->mdl->callsql("SELECT username FROM admin_user WHERE id='$id'","value");
          $activity="Admin removed from system, Admin username-".$name;
          $this->mdl->adminActivityLog($activity);

          $this->sendMessage('success','');

          return false;
    }

    public function actionResetpass(){

        $n = $this->cleanMe(Router::post('newp'));
        $c = $this->cleanMe(Router::post('con'));
        $user = $this->cleanMe(Router::post('user'));

        if(empty($n)){ 
           $new = Raise::t('user','newpwd_txt');
           return $this->sendMessage("error",Raise::t('user','E01',array('key'=>$new)));
        }
        if(empty($c)){
           $conf = Raise::t('user','user_reg_Conpass');
           return $this->sendMessage("error",Raise::t('user','E01',array('key'=>$conf)));
        }

        $new = md5($n);  $con = md5($c);  

        if($new != $con){
           return $this->sendMessage("error",Raise::t('user','E05'));
        }

        if($new == $con){

           $this->mdl->query("UPDATE `admin_user` SET  `password`='$new' WHERE id='$user'");
           $this->mdl->execute();

           $name=$this->mdl->callsql("SELECT username FROM admin_user WHERE id='$user'","value");

           $time=time(); $ip=$_SERVER['REMOTE_ADDR']; 
           $activity=$name." (Admin) Password Reseted";
           $this->mdl->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminId ' , action ='$activity' , created_at= '$time' , created_ip='$ip' ");
           $this->mdl->execute();
    
           return $this->sendMessage("success",Raise::t('user','pwd_update1'));  
        }else{
           return $this->sendMessage("error",Raise::t('user','E20'));
        }
    }

    public function actionSubadminActivity() { 

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username = $this->cleanMe(Router::post('username')); 
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 

         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $admin = $this->mdl->getSubadmin();

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "username" => $username,
                  "page"     => $page];
        
        $data = $this->mdl->getadminActivity($filter);

        $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$username."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('admin/subAdmin',['list'=>$admin,'datefrom'=>$datefrom,'dateto'=>$dateto,'username'=>$username,'data' => $data, 'pagination'=> $pagination]);
    }
}
