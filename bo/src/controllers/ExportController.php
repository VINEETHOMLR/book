<?php

namespace src\controllers;

use inc\Controller;
use src\models\CoinSwap;
use src\lib\Router;

/**
 * To handle the users data models
 * @author
 */



class ExportController extends Controller {

     public function __construct(){

        $this->adminId          = $_SESSION['INF_adminID'];
    }
    

    public function actionCoinSwap() {

        $mdl = (new CoinSwap);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username')); 
         $swapType   = $this->cleanMe(Router::post('swapType')); 

         $swapType = empty($swapType) ? 'btc-usdt' : $swapType ;

         $split = explode('-', $swapType);
         $swapFrom = $mdl->getCoinId($split[0]);
         $swapTo = $mdl->getCoinId($split[1]);

         $tableCode = ucwords($split[0]);
       
        $time = time();
        $array = array('from'=>$datefrom,'to'=>$dateto,'username'=>$username,"swapCoin1" => $swapFrom,"swapCoin2" => $swapTo,"coinCode"=>$tableCode);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='1', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }

    public function actionDeposit() {

        $mdl = (new CoinSwap);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username'));
         $status   = $this->cleanMe(Router::post('status')); 
         $coin   = $this->cleanMe(Router::post('coin'));
       
        $time = time();
        $array = array('from'=>$datefrom,'to'=>$dateto,'username'=>$username,'coin'=>$coin,'status'=>$status);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='2', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }
    

    public function actionWithdraw() {

        $mdl = (new CoinSwap);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username'));
         $status   = $this->cleanMe(Router::post('status')); 
         $coin   = $this->cleanMe(Router::post('coin'));
       
        $time = time();
        $array = array('from'=>$datefrom,'to'=>$dateto,'username'=>$username,'coin'=>$coin,'status'=>$status);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='3', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }
    public function actionDaywise() {

        $mdl = (new CoinSwap);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username'));
         
       
        $time = time();
        $array = array('from'=>$datefrom,'to'=>$dateto,'username'=>$username);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='4', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }
    public function actionConsolidate() {

        $mdl = (new CoinSwap);
        $username   = $this->cleanMe(Router::post('username'));
        $time = time();
        $array = array('username'=>$username);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='5', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }
     public function actionBtc() {

        $mdl = (new CoinSwap);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username'));
         $creditType   = $this->cleanMe(Router::post('creditType')); 
         $txn_type   = $this->cleanMe(Router::post('txn_type'));
         $coin_code   = $this->cleanMe(Router::post('coin_code'));

       
        $time = time();
        $array = array('from'=>$datefrom,'to'=>$dateto,'username'=>$username,'creditType'=>$creditType,'txn_type'=>$txn_type,'coin_code'=>$coin_code);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='6', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }
    public function actionUsdt() {

        $mdl = (new CoinSwap);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username'));
         $creditType   = $this->cleanMe(Router::post('creditType')); 
         $txn_type   = $this->cleanMe(Router::post('txn_type'));
         $coin_code   = $this->cleanMe(Router::post('coin_code'));
       
        $time = time();
        $array = array('from'=>$datefrom,'to'=>$dateto,'username'=>$username,'creditType'=>$creditType,'txn_type'=>$txn_type,'coin_code'=>$coin_code);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='7', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }
    public function actionEth() {

        $mdl = (new CoinSwap);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username'));
         $creditType   = $this->cleanMe(Router::post('creditType')); 
         $txn_type   = $this->cleanMe(Router::post('txn_type'));
         $coin_code   = $this->cleanMe(Router::post('coin_code'));
       
        $time = time();
        $array = array('from'=>$datefrom,'to'=>$dateto,'username'=>$username,'creditType'=>$creditType,'txn_type'=>$txn_type,'coin_code'=>$coin_code);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='8', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }

    public function actionTransfer() {

        $mdl = (new CoinSwap);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username'));
         $status   = $this->cleanMe(Router::post('status')); 
         $coin   = $this->cleanMe(Router::post('coin'));
         $creditType   = $this->cleanMe(Router::post('creditType')); 
        $time = time();
        $array = array('from'=>$datefrom,'to'=>$dateto,'username'=>$username,'coin'=>$coin,'status'=>$status,'creditType'=>$creditType);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='9', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }
    
    public function actionItt() {

        $mdl = (new CoinSwap);

         $datefrom   = $this->cleanMe(Router::post('datefrom')); 
         $dateto     = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username'));
         $creditType = $this->cleanMe(Router::post('creditType')); 
         $txn_type   = $this->cleanMe(Router::post('txn_type'));
         $coin_code  = $this->cleanMe(Router::post('coin_code'));
       
        $time = time();
        $array = array('from'=>$datefrom,'to'=>$dateto,'username'=>$username,'creditType'=>$creditType,'txn_type'=>$txn_type,'coin_code'=>$coin_code);
        $json = json_encode($array);
        $mdl->query("INSERT INTO export_request_list SET report='10', params='$json', admin=' $this->adminId', status='0', create_time='$time' ");
        $mdl->execute();
        return $this->sendMessage('success',"Export Request Sent Successfully");
    }

}

