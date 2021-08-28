<?php

namespace src\controllers;

use inc\Controller;
use inc\Raise;
use src\lib\Router;
use src\models\Login;
use src\models\Admin;
use src\lib\Pagination;
use src\lib\ethClass;

/**
 * @author 
 */

class IndexController extends Controller {

    /**
     * Action to test the Index
     * @return Mixed
     */
    public function __construct(){

        $this->raise=new Raise();
        $this->adminId=$_SESSION['INF_adminID'];
        $this->login = (new Login);
        $this->mdl = (new Admin);
        $this->eth       = new ethClass();
        $this->purchase_type = ["1"=>"Registration", "2"=>"Upgrade Package", "3"=>"ReEntry" ];
        $this->pag = new Pagination(new Admin(),''); 
    }

    public function actionIndex() {


        header("Location: ".BASEURL."Shipment");
        die();

        $users = $this->mdl->getUsers();
        $data['walletBalance'] = $this->mdl->walletBalance();
        //$data['walletBalance'] = "string";
        $data['eth_address'] = $this->mdl->getSiteDate('eth_masteraddress');
        $data['btc_address'] = $this->mdl->getSiteDate('btc_masteraddress');

        $data['totalDeposit']  = $this->mdl->totalDeposit();
        $data['totalWithdraw'] = $this->mdl->totalWithdraw();

        //return $this->render('index/index', ['users'=>$users,'data'=>$data]);
        return $this->render('Shipping/index', ['users'=>$users,'data'=>$data]);
    }

    public function actionMasterWallet(){
        global $wallet_decimal_limits;
        $master_address = $this->mdl->getSiteDate('eth_masteraddress');

        $ETH = $this->eth->getBalance($master_address,'ETH');
        $ETH = json_decode($ETH,true);
        if($ETH['status']=='success'){
            $eth = number_format($ETH['message'],$wallet_decimal_limits['btc']);
        }else{
            $eth = '0.00000000';
        }

        // $btc = $this->btc->getBalance();
        // $btc = json_decode($btc,true);
        // if($btc['status']=='success'){
        //     $btc = number_format($btc['message'],$wallet_decimal_limits['btc']);
        // }else{
        //     $btc = '0.00000000';
        // }
        $btc = '0.00000000';

        $array = array('eth'=>$eth,'btc'=>$btc);

        $this->sendMessage('success',$array);
    }

    public function actionLogin() { 
        
        return $this->renderlogin('login');

    } 

    public function actionLoginCheck(){

        $user=$this->cleanMe($_POST['username']);
        $pass=$this->cleanMe($_POST['password']);  

            if($user ==""){
                $this->sendMessage('error',Raise::t('login', 'user_err'));
                return false;
            }
            if($pass ==""){

                $this->sendMessage('error',Raise::t('login', 'pass_err')); 
                return false;
            }
            $login = $this->login->login($user,$pass); 
            if($login=="true"){ 

                $this->sendMessage('success',Raise::t('login', 'suc_msg'));
                return false;
            }else{
               $this->sendMessage('error',Raise::t('login', 'err')); 
            }
        return false;
    }

    public function actionLogout() { 
        
        $login = $this->login->logout();
        if($login!=""){
            unset($_SESSION['INF']);
            session_destroy();  
            $this->sendMessage('success',Raise::t('login', 'logout_suc'));
            return false;
        }else

        return $this->sendMessage('error',"Something went wrong...");  
    }

    public function actionLanguage() {
        $this->raise->siteLang($_POST['language']);
        return $_SESSION['INF_lang']; 

    }

}
