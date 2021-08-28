<?php

namespace src\controllers;

use inc\Raise;
use inc\Controller;
use src\lib\Router;
use src\models\CoinSwap;
use src\lib\withdrawClass;
use src\lib\walletClass;


class CronController extends Controller {

    protected $needAuth = false;
    public function __construct()
    {
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }
    public function actionExport(){

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');

        // include_once '../inc/config.php';
        $model = new CoinSwap();

        $report_file_map = array(
            '1' => 'ExportCoinSwap/export/',
            '2' => 'ExportDeposit/export/',
            '3' => 'ExportWithdraw/export/',
            '4' => 'ExportDaywiseReport/export/',
            '5' => 'ExportConsolidateReport/export/',
            '6' => 'ExportBtcWallet/export/',
            '7' => 'ExportUsdtWalletHistory/export/',
            '8' => 'ExportEthWalletHistory/export/',
            '9' => 'ExportTransfer/export/',
            '10' => 'ExportIttWalletHistory/export/',
            
        );

        $url = BASEURL;

        $lists = $model->callsql("SELECT id,report FROM `export_request_list` WHERE status=0 ORDER BY id",'rows');//print_r($lists);exit;
        $n = 0;
        $mh = curl_multi_init();
        $curl_array =array();

        foreach($lists as $list){

            $ip_array['id'] = $list['id'];
            $report = $list['report'];

            $export_file = isset($report_file_map[$report])?$report_file_map[$report]:"";

            if(empty($export_file)){
                continue;
            }

            $url1 = $url.$export_file;

            $curl_array[$n] = curl_init($url1); 
            curl_setopt($curl_array[$n], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_array[$n], CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl_array[$n], CURLOPT_SSL_VERIFYPEER, 0);

            //curl_setopt($curl_array[$n], CURLOPT_HTTPHEADER, 0);
            curl_setopt($curl_array[$n], CURLOPT_POSTFIELDS, $ip_array);
            curl_setopt($curl_array[$n], CURLOPT_TIMEOUT, 1);
            curl_multi_add_handle($mh, $curl_array[$n]);
            $n++;

        }

        $running = NULL;

        session_write_close();

        do {
            curl_multi_exec($mh,$running);
            
        } while($running > 0);

        session_start();

        $res = array();
        for($i=0;$i<$n;$i++){
            $res[] = curl_multi_getcontent($curl_array[$i]);
        }

        for($i=0;$i<$n;$i++){
            curl_multi_remove_handle($mh, $curl_array[$i]);
        }

        curl_multi_close($mh); 

        die('completed');

    }

    public function actionRemoveExport(){ 

        
        $model = new CoinSwap();

        $fileList = $model->callsql("SELECT id,excel_generated FROM `export_request_list` WHERE status=2 AND DATE(FROM_UNIXTIME(create_time)) < ( CURDATE() - INTERVAL 7 DAY )",'rows');
        
        if(!empty($fileList)){

            foreach ($fileList as $key => $info) {
               
                $Deletefile = FILEUPLOADPATH."export/".$info['excel_generated']; 
           
                if (file_exists($Deletefile)) {
                   unlink($Deletefile); 
                } 

                $model->callsql("DELETE FROM `export_request_list` WHERE id ='$info[id]'"); 
            }
        }
    }

    public function actionWithdrawStatusUpdate(){
        
        $model = new CoinSwap();

        $this->withdraw  = new withdrawClass();
        $this->wallet    = new walletClass;

        $time = time();

        $pendingList = $model->callsql("SELECT id,user_id,coin_id,coin_code,amount FROM `coin_withdrawal` WHERE api_status=0 ",'rows');

        if(!empty($pendingList)){

            foreach ($pendingList as $key => $req) {

                $refID = $req['id'];
               
                $with_Info = $this->withdraw->withdrawStatus($refID,$req['user_id'],$req['coin_id']);
                $with_Info = json_decode($with_Info, true);

                if($with_Info['status'] == "error"){
                    continue;
                }else{

                    $with_Status =  $with_Info['message']['status'];

                    if($with_Status !=''){

                        if($with_Status == 2){ //reject

                           $transType  = 2; //withdraw
                           $remarks    = "Withdrawal Request Rejected";
                           $walletName = $req['coin_code']."_wallet";

                           $credit = $this->wallet->updateWallet($req['user_id'], 0, $transType, $req['amount'], '' ,$remarks,$walletName,$req['coin_id']);

                           if($credit){

                               $model->callsql("UPDATE `coin_withdrawal` SET `status`='3',`api_status`='$with_Status', `updated_time`='$time' WHERE `id`='$refID' ");
                            }

                        }else

                            $transhash =  $with_Info['message']['remarks'];
                            $remarks   = "Withdraw Request Approved, $transhash";
                    
                            $model->callsql("UPDATE `coin_withdrawal` SET `trans_hash`='$transhash',`api_status`='$with_Status', remarks='$remarks', `updated_time`='$time' WHERE `id`='$refID' ");

                    }
                }
            }
           
        }

    }


}
?>
