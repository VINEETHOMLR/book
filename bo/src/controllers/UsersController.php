<?php

namespace src\controllers;

use inc\Controller;
use src\models\Users;
use src\lib\Router;
use src\lib\Pagination;
use src\lib\walletClass;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */
class UsersController extends Controller {

    /**
     * 
     * @return Mixed
     */

    public function __construct(){

        $this->mdl    = (new Users);
        $this->admin  = $_SESSION['INF_adminID'];
        $this->wallet = (new walletClass);
        $this->pag    = new Pagination(new Users(),''); 

        global $userStatusArr,$wallet_decimal_limits;

        $this->userArr = ($_SESSION['INF_lang']=="en") ?  $userStatusArr : '';

        $this->walletArray = array('BTC Wallet'=>0,'USDT Wallet'=>1,'ETH Wallet'=>2);

        // $package = $this->mdl->siteData();
        // $this->package = json_decode($package);

        $this->decimal = $wallet_decimal_limits;
    }

    public function actionIndex() {

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $status   = $this->cleanMe(Router::post('status'));
         $username = $this->cleanMe(Router::post('username'));
         
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 

         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "status"   => $status,
                  "username" => $username,
                  "page" => $page];

        $data=$this->mdl->getUserList($filter);

        $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$status."','".$username."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('users/index',['datefrom'=>$datefrom,'dateto'=>$dateto,'status'=>$status,'username'=>$username,'pagination'=> $pagination,'data'=>$data]);
    }

    public function actionAccount() { 
 
        $uid=$this->cleanMe(Router::get('user'));

        $details = $this->mdl->getuserdetails($uid);

        $wallet = $details['wallet'];

        $walletArr = array("BTC Wallet" => number_format($wallet['btc_wallet'],$this->decimal['btc']),
                           "USDT Wallet" => number_format($wallet['usdt_wallet'],$this->decimal['usdt']),
                           "ETH Wallet" => number_format($wallet['eth_wallet'],2)
                           );

        $total = $this->mdl->getSum($details['data']['id']); 
    
        return $this->render('users/UserEdit',['user'=>$details,'userid'=>$uid,'total'=>$total,'walletArr'=>$walletArr]);
    }

    public function actionRefreshWallet(){

        $uid=$this->cleanMe(Router::post('id'));

        $wallet = $this->mdl->callsql("SELECT * FROM user_wallet WHERE user_id='$uid'",'row');  

        $walletArr = array("BTC Wallet" => number_format($wallet['btc_wallet'],$this->decimal['btc']),
                           "USDT Wallet" => number_format($wallet['usdt_wallet'],$this->decimal['usdt']),
                           "ETH Wallet" => number_format($wallet['eth_wallet'],2)
                           );
        $response = '';

        foreach ($walletArr as $key => $value) {
                $response.= '<div class="row col-12">
                        <div class="col-5" style="float:left;"><label>'.$key.'</label></div>
                        <div class="" style="float:left;">-</div>
                        <div class="col-5" style="float:left;"><label>'.$value.'</label></div>
                      </div>';
        }

        return $this->renderJSON($response);
    }

      public function actionUserEdit(){

        $id             = $this->cleanMe(Router::post('editId')); 
        
        $userName       = $this->cleanMe(Router::post('userName'));

        $fullName       = $this->cleanMe(Router::post('fullName'));

        $this->fullnameCheck($fullName,Raise::t('app','full_name'));
        $this->editcheckExists('username',Raise::t('app','username'),$userName,$id);
        //$this->checkExists('fullname',Raise::t('app','fullname'),$fullName);
        if(!empty($userName)){
          $this->emailvalidate($userName,Raise::t('subadmin','email_txt'));
        }
                   
        $data=array( 'userName'=>$userName,'fullName'=>$fullName,'edit'=>$id);

        $this->mdl->updateUser($data); 

        $this->sendMessage('success',Raise::t('user','update_suc'));
    
        return false;
    }


     public function actionBlockUSer(){
            
            $value = $this->cleanMe(Router::post('uid')); 

            $user=$this->mdl->callsql("SELECT fullname FROM user WHERE id='$value'","value");

            $this->mdl->callsql("UPDATE user SET status=0 WHERE id='$value'");

            $activity='Blocked Username '.$user;
            $this->mdl->adminActivityLog($activity);

            $this->sendMessage('success', Raise::t('user','block_suc'));
    }

    public function actionUnBlockUSer(){
            
            $value = $this->cleanMe(Router::post('uid')); 

            $user=$this->mdl->callsql("SELECT fullname FROM user WHERE id='$value'","value");

            $this->mdl->callsql("UPDATE user SET status=1 WHERE id='$value'");

            $activity='Unblocked Username '.$user;
            $this->mdl->adminActivityLog($activity);

            $this->sendMessage('success', Raise::t('user','unblock_suc'));
    }

    public function actionIsAllowDeposit(){

            $userid = $this->cleanMe(Router::post('uid'));
            $status = $this->cleanMe(Router::post('status')); 

            $user=$this->mdl->callsql("SELECT fullname FROM user WHERE id='$userid'","value");

            $this->mdl->callsql("UPDATE user_info SET is_deposit_allowed='$status' WHERE user_id='$userid'");

            if($status == 1){
              $textStatus = 'Enabled';
            }else{
              $textStatus = 'Disabled';
            }

            $activity= $textStatus .' deposit for user ID '.$userid;
            $this->mdl->adminActivityLog($activity);

            $msg = $textStatus . ' Deposit Successfully';

            $this->sendMessage('success', $msg);

    }

    public function actionIsAllowWithdraw(){

            $userid = $this->cleanMe(Router::post('uid'));
            $status = $this->cleanMe(Router::post('status')); 

            $user=$this->mdl->callsql("SELECT fullname FROM user WHERE id='$userid'","value");

            $this->mdl->callsql("UPDATE user_info SET is_withdrawal_allowed='$status' WHERE user_id='$userid'");

            if($status == 1){
              $textStatus = 'Enabled';
            }else{
              $textStatus = 'Disabled';
            }

            $activity= $textStatus .' withdrawal for user ID '.$userid;
            $this->mdl->adminActivityLog($activity);

            $msg = $textStatus . ' Withdrawal Successfully';

            $this->sendMessage('success', $msg);

    }

    public function actionIsAllowSwap(){

            $userid = $this->cleanMe(Router::post('uid'));
            $status = $this->cleanMe(Router::post('status')); 

            $user=$this->mdl->callsql("SELECT fullname FROM user WHERE id='$userid'","value");

            $this->mdl->callsql("UPDATE user_info SET is_swap_allowed='$status' WHERE user_id='$userid'");

            if($status == 1){
              $textStatus = 'Enabled';
            }else{
              $textStatus = 'Disabled';
            }

            $activity= $textStatus .' swap for user ID '.$userid;
            $this->mdl->adminActivityLog($activity);

            $msg = $textStatus . ' Swap Successfully';

            $this->sendMessage('success', $msg);

    }

    public function actionIsAllowFinancial(){

            $userid = $this->cleanMe(Router::post('uid'));
            $status = $this->cleanMe(Router::post('status')); 

            $user=$this->mdl->callsql("SELECT fullname FROM user WHERE id='$userid'","value");

            $this->mdl->callsql("UPDATE user_info SET is_financial_allowed='$status' WHERE user_id='$userid'");

            if($status == 1){
              $textStatus = 'Enabled';
            }else{
              $textStatus = 'Disabled';
            }

            $activity= $textStatus .' financial for user ID '.$userid;
            $this->mdl->adminActivityLog($activity);

            $msg = $textStatus . ' Financial Successfully';

            $this->sendMessage('success', $msg);

    }

    private function fullnameCheck($var,$key){

        if(empty($var)){
        
          $msg = Raise::t('user','E01',array('key'=>$key));
          echo $this->sendMessage("error",$msg); exit;
        }
        if(strlen($var) > 30){
          $msg=Raise::t('user','E34',array('key'=>$key));
          echo $this->sendMessage("error",$msg);
          exit();
        }
    }
    public function checkExists($checkField,$key,$checkData){

        $this->mdl->callsql("SELECT * from user WHERE status !=0 AND ".$checkField."='$checkData'","row");  
        $res= $this->mdl->single(); 

        if(!empty($res)>0){
          $this->sendMessage("error",Raise::t('user','E17',array('key'=>$key))); die();
        }
    }
    public function emailvalidate($var,$attr) {

        if (!filter_var($var, FILTER_VALIDATE_EMAIL)) {
          $msg = Raise::t('user','E40',array('key'=>$attr)); 
         echo $this->sendMessage("error",$msg,"error");
         exit();
        }

    }

    public function editcheckExists($checkField,$key,$checkData,$id,$type=1){  
        
        if($type==1){
            $this->mdl->callsql("SELECT * from user WHERE id!='$id' AND  ".$checkField."='$checkData'","row");  
        }
        // else{
        //    $this->mdl->callsql("SELECT * from user as U LEFT JOIN user_extra as UE ON U.id=UE.user_id WHERE U.user_status !=3 AND U.id !='$id' AND  UE.".$checkField."='$checkData'","row");  
        // }
        $res= $this->mdl->single(); 
        if(!empty($res)>0){
          $this->sendMessage("error",Raise::t('user','E17',array('key'=>$key)));
           die();
        }
    }

   
    public function actionResetpass(){

          $n = $this->cleanMe(Router::post('newpass'));
          $c = $this->cleanMe(Router::post('confpass'));
          $ntrans = $this->cleanMe(Router::post('newtrans'));
          $ctrans = $this->cleanMe(Router::post('contrans'));
          $uid = $this->cleanMe(Router::post('editId'));

          $new  = md5($n);      $con  = md5($c);
          $newT = $ntrans; $conT = $ctrans;

          $t = time();

          $id=$this->mdl->callsql("SELECT id FROM user WHERE id='$uid'","value");

          if(empty($ntrans) && empty($ctrans)){

                $this->PrimaryPwdValidation($n,$c);

                if($new == $con){

                   $this->mdl->query("UPDATE `user` SET  `password`='$new' WHERE id='$uid'");
                   $this->mdl->execute();
                   $activity='Updated Primary Password of UserId '.$id;
                   $this->mdl->adminActivityLog($activity);
                   return $this->sendMessage("success",Raise::t('user','pwd_update1'));  
                }else{
                   return $this->sendMessage("error",'Something went wrong..');
                }
          }
          if(empty($n) && empty($c)){

                $this->TransactionPwdValidation($ntrans,$ctrans);

                if($newT == $conT){

                   $this->mdl->query("UPDATE `user_info` SET  `security_pin`='$newT' WHERE user_id='$uid'");
                   $this->mdl->execute();
                   $activity='Updated Transaction Password of UserId '.$id;
                   $this->mdl->adminActivityLog($activity);
                   return $this->sendMessage("success",Raise::t('user','pwd_update2'));  
                }else{
                   return $this->sendMessage("error",'Something went wrong..');
                }

          }
          if( (!empty($n) || !empty($c)) && ( !empty($ntrans) || !empty($ctrans)) ){

                $this->PrimaryPwdValidation($n,$c);

                $this->TransactionPwdValidation($ntrans,$ctrans);

                if($new == $con && $newT == $conT){

                   $this->mdl->query("UPDATE `user` SET  `password`='$new' WHERE id='$uid'");
                   $this->mdl->execute();
                   $this->mdl->query("UPDATE `user_info` SET `security_pin`='$newT' WHERE user_id='$uid'");
                   $this->mdl->execute();
                   $activity='Updated Primary and Transaction Password of user '.$id;
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
    private function TransactionPwdValidation($ntrans,$ctrans){

        if(empty($ntrans)){
            $this->sendMessage("error",Raise::t('user','E01',array('key'=>'Transaction Password')));
            exit;
        }

        if(strlen($ntrans) !=6){
             $this->sendMessage('error',Raise::t('user','E43')); exit;
        }

        if(!is_numeric($ntrans)){
            $this->sendMessage('error',Raise::t('user','E45')); exit;
        }

        if(empty($ctrans)){
            $this->sendMessage("error",Raise::t('user','E01',array('key'=>'Transaction Confirm Password')));
            exit;
        }

        $newT = md5($ntrans); $conT = md5($ctrans);

        if($newT != $conT){
            $this->sendMessage("error",Raise::t('user','E06'));
            exit;
        } 
      return true;
    }
    private function passwordLengthCheck($var,$key){

        if(strlen($var)<6){
           echo $this->sendMessage("error",Raise::t('user','E32',array('key'=>$key))); exit;
        }

        if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/',$var)) {
           echo $this->sendMessage("error",Raise::t('user','E39',array('key'=>$key))); exit();
        }

        if(strlen($var) > 12){
           echo $this->sendMessage("error",Raise::t('user','E38',array('key'=>$key))); exit;
        }
    }

     public function actionActivity() {

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $fullname = $this->cleanMe(Router::post('fullname')); 
         $userID   = $this->cleanMe(Router::post('userID')); 
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 

         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "fullname" => $fullname,
                  "userID"   => $userID,
                  "page"     => $page
                ];

          $data = $this->mdl->getUserActivity($filter); 

          $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$userID."','".$fullname."','***')";
          $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

         return $this->render('users/activity',['userID'=>$userID,'datefrom'=>$datefrom,'dateto'=>$dateto,'fullname'=>$fullname,'data' => $data, 'pagination'=> $pagination]);
    }

     public function actionDepositData(){

        $user = $this->cleanMe(Router::post('id'));
        $data['data'] = $this->mdl->getDepositData($user); 

        $statusArray = array (  0=>'New', 1=>'Processing', 2=>'Approved', 3=>'Rejected' );
        $colorArray  = array (  0=>'black', 1=>'blue', 2=>'green', 3=>'red' );
        
        foreach ($data['data'] as $key => $value) {
           $data['data'][$key]['status'] = '<label style="color:'.$colorArray[$value['status']].'">'.$statusArray[$value['status']].'</label>';
           $data['data'][$key]['time'] = date("d-m-Y H:i:s",$value['created_at']);
        }

        return $this->renderJSON($data);
    }

    public function actionWithdrawData(){

        $user = $this->cleanMe(Router::post('id'));
        $data['data'] = $this->mdl->getWithdrawData($user); 

        $statusArray = array (  0=>'New', 1=>'Processing', 2=>'Approved', 3=>'Rejected' );
        $colorArray  = array (  0=>'black', 1=>'blue', 2=>'green', 3=>'red' );
        
        foreach ($data['data'] as $key => $value) {
           if(!empty($value['updated_by'])){
              $approve = $this->mdl->callsql("SELECT username FROM `admin_user` WHERE id='$value[updated_by]'");
           }else{
              $approve = '-';
           }
           $data['data'][$key]['status'] = '<label style="color:'.$colorArray[$value['status']].'">'.$statusArray[$value['status']].'</label>';
           $data['data'][$key]['approve'] = $approve;
           $data['data'][$key]['receive'] = $value['amount'] - $value['service_charge'];
           $data['data'][$key]['time'] = date("d-m-Y H:i:s",$value['created_at']);
        }

        return $this->renderJSON($data);
    }

    public function actionCoinWalletbalance(){

        $user = $this->cleanMe(Router::post('id'));
        $data['data'] = $this->mdl->getCoinWalletbalance($user); 
		
		//print_r($data['data']);exit;

       
        //foreach ($data['data'] as $key => $value) {
          
        //   $data['data'][$key]['coin_name'] = $value['coin_name'];
        //   $data['data'][$key]['coin_code'] = $value['coin_code'];
       //    $data['data'][$key]['value'] = $value['value'];
        //   $data['data'][$key]['balance'] = $value['balance'];
        //}

        return $this->renderJSON($data['data']);
    } 

    public function actionImageUpload(){

        $user_id   = $this->cleanMe(Router::post('user_id'));
        
        $newFile_org =""; 

         if(!empty($_FILES['file'])){     
            $filename   = $_FILES['file']['name'];
            $temp_name  = $_FILES['file']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('JPG','png','JPEG','jpeg','jpg');

            if(!in_array($extension, $image_array)){
                
                return $this->sendMessage("error",'Please Select Valid Image Format');
            }

            $newFile_org = 'pro_'.$user_id;
            $target_file = PROFILEPICUPLOAD."profile/".$newFile_org; 
            $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }

            if(!empty($user_id)){ // delete

                $oldPic=$this->mdl->callsql("SELECT profile_pic FROM user_info WHERE user_id='$user_id'","value");

                if($oldPic){

                  $Deletefile = PROFILEPICUPLOAD."profile/".$oldPic; 
           
                  if (file_exists($Deletefile)) {
                      unlink($Deletefile); 
                  }
                }

                
            }

            if(!move_uploaded_file ($temp_name, $target_file)){
               return $this->sendMessage("error","Something Went Wrong...","error");
            }

        }

       
        $this->mdl->callsql("UPDATE user_info SET profile_pic='$newFile_org' WHERE user_id='$user_id'");

        return $this->sendMessage('success',$newFile_org);
        
    }  


    public function actionTransferAmt(){

        $wallet    = $this->cleanMe(Router::post('walletType'));
        $creditType = $this->cleanMe(Router::post('creditType'));
        $transAmt  = $this->cleanMe(Router::post('amount'));
        $id        = $this->cleanMe(Router::post('user'));
        $remarks   = $this->cleanMe(Router::post('remarks'));

        if(empty($transAmt) || $transAmt==0){
            return $this->sendMessage("error",Raise::t('user','E01',array('key'=>'Amount')));   
        }
        if(!is_numeric($transAmt) || ($transAmt < 0)){
            return $this->sendMessage("error",Raise::t('user','amt_valid'));   
        }
        if(empty($remarks)){
            return $this->sendMessage("error",Raise::t('user','E01',array('key'=>'Remarks'))); 
        }

        $DbTableWallet = array(0=>'btc_wallet',1=>'usdt_wallet',2=>'eth_wallet');
        
        $transType = '31';

        if(!empty($creditType)){

           $valid = $this->wallet->checkBalance($DbTableWallet[$wallet], $id, $transAmt);
           if(empty($valid)){
              return $this->sendMessage("error",Raise::t('user','balance_err'));   
              exit;
           }

           $transType = '32';
        }

        $doneBy=$this->admin;

        $username=$this->mdl->getuniqueUser($id);

        $walletName = $DbTableWallet[$wallet];

        $walletNameArr = explode('_',$walletName);

        $coinName = $walletNameArr[0];

        $coinId = $this->mdl->callsql("SELECT id FROM `coin` WHERE coin_code = '$coinName' ","value");
        

        $transfer = $this->wallet->updateWallet($id, $creditType, $transType, $transAmt, $doneBy, $remarks,$walletName,$coinId);


        if($transfer){
        
            $type = empty($creditType) ? 'Credited' : 'Debited' ;
            $activity = $type." amount $transAmt to ".array_search($wallet, $this->walletArray)." (User : $username)";
    
            $this->mdl->adminActivityLog($activity);
        
            $this->sendMessage('success',"Amount $type Successfully");
            return false;
        }else
           return $this->sendMessage('error',"Something went wrong..");
    } 

    
}