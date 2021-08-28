<?php

namespace src\controllers;

use inc\Controller;
use src\models\CoinSwap;
use src\lib\Router;
use src\lib\Pagination; 
use inc\Raise;

class DownloadController extends Controller {
    
    public function __construct(){

        $this->adminId = $_SESSION['INF_adminID'];
    }

    public function actionIndex() {

        $this->checkPageAccess(10);

        $mdl = (new CoinSwap);

        $report_all = array(
            '1' => 'Coin Swap History',
            '2' => 'Deposit History',
            '3' => 'Withdraw History',
            '4' => 'Daywise Report',
            '5' => 'Consolidated Report',
            '6' => 'BTC Wallet History',
            '7' => 'Usdt Wallet History',
            '8' => 'Eth Wallet History',
            '9' => 'Transfer History',
            '10' => 'ITT Wallet History',


        );

        $statusArr = array(0=>'Pending',1=>'Processing',2=>'Completed');
       
        $data['data'] = $mdl->callsql("SELECT * FROM export_request_list  WHERE admin=$this->adminId ORDER BY id desc",'rows');

    
        return $this->render('export/download', ['data'=>$data, 'report_all'=>$report_all, 'statusArr'=>$statusArr]);
    }

    private function checkPageAccess($service){

        $servicesArray = $_SESSION['INF_privilages'];
        $servicesArray = array_values($servicesArray);
        $servicesArray = explode(",", $servicesArray[0]);
        $role          = $_SESSION['INF_role'];
        
        if( !in_array($service, $servicesArray) ) {
            if($role !=1){
              header("Location: ".BASEURL."");
              exit;
            }
        }else
            return true;
    }

}
