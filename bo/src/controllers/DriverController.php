<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Driver;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */

class DriverController extends Controller {

    /**
     * 
     * @return Mixed
     */
    public function __construct(){

        $this->mdl = (new Driver);
        $this->pag    = new Pagination(new Driver(),''); 
        
        $this->admin  = $_SESSION['INF_adminID'];
    }

    public function actionIndex() {

      
         $status   = $this->cleanMe(Router::post('driverstatus'));
         $driver_name = $this->cleanMe(Router::post('driver_name'));
         $vehicle_number = $this->cleanMe(Router::post('vehicle_number'));
         
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 

         

         $filter=["status"   => $status,
                  "driver_name" => $driver_name,
                  "vehicle_number" => $vehicle_number,
                  "page" => $page];



        $data=$this->mdl->getUserList($filter); 

        $onclick = "onclick=pageHistory('".$status."','".$driver_name."','".$vehicle_number."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('driver/driverList',['status'=>$status,'username'=>$driver_name,'vehicle_number'=>$vehicle_number,'pagination'=> $pagination,'data'=>$data]);
      
    }
   
    public function actionAccount() { 
 
        $id=$this->cleanMe(Router::get('driver'));
        $details = $this->mdl->getuserdetails($id); 


        
        return $this->render('driver/driverEdit',['user'=>$details,'userid'=>$id]);
    }

       public function actionUpdateStatus() { 

          $id      = $this->cleanMe(Router::post('id'));
          $status  = $this->cleanMe(Router::post('status'));

          $this->mdl->UpdateSlotStatus($id,$status);

          $activity="Status Updated Driver Id-".$id;
          $this->mdl->adminActivityLog($activity);

          $msg="Successfully updated the driver status";
          $this->sendMessage('success',$msg);

          return false;
    }

    public function actionDeleteDriver() { 

          $id      = $this->cleanMe(Router::post('uid'));
          $this->mdl->deleteDriver($id);

          $activity="Deleted Driver Id-".$id;
          $this->mdl->adminActivityLog($activity);

          $msg="Successfully deleted the driver";
          $this->sendMessage('success',$msg);

          return false;
    }

    

    public function actionDriverEdit(){

        $id             = $this->cleanMe(Router::post('editId')); 
        $driver_name       = $this->cleanMe(Router::post('driver_name'));
        $vehicle_number       = $this->cleanMe(Router::post('vehicle_number'));
        $phone          = $this->cleanMe(Router::post('phone'));
        

        if(empty($driver_name)){
            $msg='Please Enter Drivername To Proceed';
            $this->sendMessage("error",$msg); 
            die();
        }

        if(empty($vehicle_number)){
            $msg='Please Enter Vehicle Number To Proceed';
            $this->sendMessage("error",$msg); 
            die();
        }

        $vehiclenumberChk =$this->mdl->callsql("SELECT id FROM driver_user WHERE vehicle_number='$vehicle_number' AND id!='$id' AND status!=3","rows");
          if(sizeof($vehiclenumberChk) > 0){
              $msg='This Vehicle Number Already Used';
              $this->sendMessage("error",$msg); 
              die();  
          }

        

        
        if(empty($phone)){
            $msg='Please Enter Phone To Proceed';
            $this->sendMessage("error",$msg); 
            die();
        }

        

                   
        $data=array( 'driver_name'=>$driver_name,'vehicle_number'=>$vehicle_number,'phone'=>$phone,'edit'=>$id);

        $this->mdl->updateUser($data); 

        $this->sendMessage('success',Raise::t('user','update_suc'));
    
        return false;
    }

    public function emailvalidate($var,$attr) {

        if (!filter_var($var, FILTER_VALIDATE_EMAIL)) {
          $msg = Raise::t('user','E40',array('key'=>$attr)); 
         echo $this->sendMessage("error",$msg,"error");
         exit();
        }

    }
     public function actionResetpass(){

          $n = $this->cleanMe(Router::post('newpass'));
          $c = $this->cleanMe(Router::post('confpass'));
          //$ntrans = $this->cleanMe(Router::post('newtrans'));
          //$ctrans = $this->cleanMe(Router::post('contrans'));
          $uid = $this->cleanMe(Router::post('editId'));

          $new  = md5($n);      $con  = md5($c);

          $t = time();

          $id=$this->mdl->callsql("SELECT id FROM driver_user WHERE id='$uid'","value");

          // if(empty($ntrans) && empty($ctrans)){

          //       $this->PrimaryPwdValidation($n,$c);

          //       if($new == $con){

          //          $this->mdl->query("UPDATE `driver_profile` SET  `password`='$new' WHERE driver_id='$uid'");
          //          $this->mdl->execute();
          //          $activity='Updated Primary Password of DriverId '.$id;
          //          $this->mdl->adminActivityLog($activity);
          //          return $this->sendMessage("success",Raise::t('user','pwd_update1'));  
          //       }else{
          //          return $this->sendMessage("error",'Something went wrong..');
          //       }
          // }
         
          if( (!empty($n) || !empty($c))){

                $this->PrimaryPwdValidation($n,$c);

                if($new == $con ){

                   $this->mdl->query("UPDATE `driver_user` SET  `password`='$new' WHERE id='$uid'");
                   $this->mdl->execute();
                   $activity='Updated Password of Driver '.$id;
                   $this->mdl->adminActivityLog($activity);
                   return $this->sendMessage("success",Raise::t('user','pwd_update1'));  
                }else{
                   return $this->sendMessage("error",'Something went wrong..');
                }

          }
    }
    private function PrimaryPwdValidation($new,$conf){

        if(empty($new)){
            $this->sendMessage("error",Raise::t('user','E01',array('key'=>'New Primary Password')));
            exit();
        }

        $this->passwordLengthCheck($new,'Password');

        if(empty($conf)){
            $this->sendMessage("error",Raise::t('user','E01',array('key'=>'Confirm Password')));
            exit();
        } 
        $newPwd  = md5($new);   $conPwd  = md5($conf);
        if($newPwd != $conPwd){
            $this->sendMessage("error",Raise::t('user','E05'));
            exit;
        }
        return true;
    }
    private function passwordLengthCheck($var,$key){

        if(strlen($var)<6){
           echo $this->sendMessage("error",Raise::t('user','E32',array('key'=>$key))); exit;
        }

        if(strlen($var) > 12){
           echo $this->sendMessage("error",Raise::t('user','E38',array('key'=>$key))); exit;
        }
    }

    public function actionCreate() {

        return $this->render('driver/create');
    }

    public function actionAdd(){

        $driver_name            = $this->cleanMe(Router::post('driver_name'));
        $vehicle_number           = $this->cleanMe(Router::post('vehicle_number'));
        $phone           = $this->cleanMe(Router::post('phone'));
        $status          = $this->cleanMe(Router::post('status'));
        $password        = $this->cleanMe(Router::post('password'));
        $cpassword       = $this->cleanMe(Router::post('cpassword'));
        $id       = $this->cleanMe(Router::post('editId'));

        $pwd = md5($password);
        $cpwd = md5($cpassword);

      

        if(empty($driver_name)){
            $msg='Please Enter Driver Name';
            $this->sendMessage("error",$msg); 
            die();
        }


        if(empty($vehicle_number)){
            $msg='Please Enter Vehicle Number';
            $this->sendMessage("error",$msg); 
            die();
        }
   

         if(empty($phone)){
            $msg='Please Enter Contact Number';
            $this->sendMessage("error",$msg); 
            die();
        }

     

        $countArr =$this->mdl->callsql("SELECT id FROM driver_user WHERE vehicle_number='$vehicle_number'","rows");
        if(sizeof($countArr) > 0){
            $msg='This Vehicle number Already Exist';
            $this->sendMessage("error",$msg); 
            die();  
        }

   

        // $isValid = $this->validate_mobile($phone);
        // if($isValid == 0){
        //     $msg='Please Enter Valid Phone To Proceed';
        //     $this->sendMessage("error",$msg); 
        //     die();
        // }

        if(empty($id)){
          if(empty($password)){
                $msg='Please Enter Password To Proceed';
                $this->sendMessage("error",$msg); 
                die();
            }
            if(strlen($password) < 6){
                $msg='Minimum Password Length Requires 6';
                $this->sendMessage("error",$msg); 
                die();
            }
            if(empty($cpassword)){
                $msg='Please Enter Confirm Password To Proceed';
                $this->sendMessage("error",$msg); 
                die();
            }
            if($pwd != $cpwd){
               $msg='Password And Confirm Password Are Not Matching';
                $this->sendMessage("error",$msg); 
                die(); 
            }

            
        }else{

            if((!empty($password)) && (!empty($cpassword))){

                if(strlen($password) < 6){
                    $msg='Minimum Password Length Requires 6';
                    $this->sendMessage("error",$msg); 
                    die();
                }
                if($pwd != $cpwd){
                   $msg='Password And Confirm Password Are Not Matching';
                    $this->sendMessage("error",$msg); 
                    die(); 
                }
                
            }
          
        }
      

  $data=array('driver_name'=>$driver_name,'vehicle_number'=>$vehicle_number,'phone'=>$phone,'status'=>$status,'password'=>$pwd); 

  $inserid = $this->mdl->addDriver($data);
  $msg='Driver Added Successfully'; 

        
        $this->sendMessage('success',$msg);
    
        return false;
    }

    public function actionImageUpload(){

        $user_id   = $this->cleanMe(Router::post('user_id'));
        $type   = $this->cleanMe(Router::post('type'));
        
        $newFile_org =""; 

        if($type == '1') {
            
            if(!empty($_FILES['file'])){     
            $filename   = $_FILES['file']['name'];
            $temp_name  = $_FILES['file']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('JPG','png','JPEG','jpeg','jpg');

            if(!in_array($extension, $image_array)){
                
                return $this->sendMessage("error",'Please Select Valid Image Format');
            }

            $size = getimagesize($temp_name);
            $sizeArr = explode('"',$size[3]);
            $width = trim($sizeArr[1]);
            $height = trim($sizeArr[3]);

            if($width != "75" || $height != "75"){
              return $this->sendMessage("error","Image Dimension is 75px x 75px","error"); 
            } 

            $newFile_org = 'P'.time().'.'.$extension;
            $target_file = FILEUPLOADPATH."profile/".$newFile_org; 
            $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }

            if(!empty($user_id)){ // delete

                $oldPic=$this->mdl->callsql("SELECT profile_pic FROM driver_profile WHERE driver_id='$user_id'","value");

                if($oldPic){

                  $Deletefile = FILEUPLOADPATH."profile/".$oldPic; 
           
                  if (file_exists($Deletefile)) {
                      unlink($Deletefile); 
                  }
                }

                
            }

            if(!move_uploaded_file ($temp_name, $target_file)){
               return $this->sendMessage("error","Something Went Wrong...","error");
            }

         }
        }else{
          if(!empty($_FILES['file'])){     
            $filename   = $_FILES['file']['name'];
            $temp_name  = $_FILES['file']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('JPG','png','JPEG','jpeg','jpg');

            if(!in_array($extension, $image_array)){
                
                return $this->sendMessage("error",'Please Select Valid Image Format');
            }

            $size = getimagesize($temp_name);
            $sizeArr = explode('"',$size[3]);
            $width = trim($sizeArr[1]);
            $height = trim($sizeArr[3]);

            if($width != "75" || $height != "75"){
              return $this->sendMessage("error","Image Dimension is 75px x 75px","error"); 
            } 

            $newFile_org = 'L'.time().'.'.$extension;
            $target_file = FILEUPLOADPATH."license/".$newFile_org; 
            $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }

            if(!empty($user_id)){ // delete

                $oldPic=$this->mdl->callsql("SELECT ic_driving_license_pic FROM driver_profile WHERE driver_id='$user_id'","value");

                if($oldPic){

                  $Deletefile = FILEUPLOADPATH."license/".$oldPic; 
           
                  if (file_exists($Deletefile)) {
                      unlink($Deletefile); 
                  }
                }

                
            }

            if(!move_uploaded_file ($temp_name, $target_file)){
               return $this->sendMessage("error","Something Went Wrong...","error");
            }

        }
        }

        if($type == '1') {
            
            $this->mdl->callsql("UPDATE driver_profile SET profile_pic='$newFile_org' WHERE driver_id='$user_id'");
        }else{
            $this->mdl->callsql("UPDATE driver_profile SET ic_driving_license_pic='$newFile_org' WHERE driver_id='$user_id'");
        }

         

       
        

        return $this->sendMessage('success',$newFile_org);
        
    }

    

     

    private function validate_mobile($mobile)
    {
        return preg_match('/^[0-9]{8}+$/', $mobile);
    }

}
