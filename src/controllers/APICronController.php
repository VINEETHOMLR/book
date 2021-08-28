<?php

namespace src\controllers;

use inc\Controller;
use inc\Raise;
use src\lib\Router;
use src\lib\Helper;
use src\lib\Secure;
use src\lib\RRedis;
use src\lib\btcClass;
use src\lib\walletClass;
use src\models\Coin;
use src\models\CoinWalletAddress;
use src\models\SalesShipmentHeader;
use src\models\SalesShipmentLine;
use src\models\Driver_user;
use src\models\Cron_log;


/**
 * To handle the users data models
 * @author 
 */

class APICronController extends Controller
{
    protected $needAuth = false;

    public function __construct()
    {
        parent::__construct(true);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->mdl = (new SalesShipmentHeader);
        $this->mdlShipmentLine = (new SalesShipmentLine);

    }

    public function actionTest()
    {

        $this->mdl->testlog();
    }
    public function actionCallApi(){

       /* $list =  (new Cron_log)->getAll(['lock_status'=>'1']);
        if(!empty($list)) {
            
            return false;
        }*/

        $insert = [];
        $insert['starttime']     = date('Y-m-d H:i:s');
        $insert['lock_status']   = '1';

        $log_id = (new Cron_log)->assignAttrs($insert)->save();
        $url = 'https://175.139.150.193:12048/SPCI2018_TEST/api/beta/companies(8fb36481-633d-4e96-a3d3-eb8467981c38)/navstagingentities?$expand=navstaginglines';
        $username = 'LEXA\MOBILE';
        $password = 'Qwerty.1q@z2wsx';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        
        $response = curl_exec($ch);
        $response = json_decode($response,true);
        $result = $response['value'];
         echo "<pre>";
         print_r($result);exit;


       /* foreach($result as $k=>$v){

            if(count($v['navstaginglines'])==5){

                echo "<pre>";
                print_r($v['navstaginglines']);exit;

            }

        }

        exit;*/

        foreach($result as $k=>$v){
           
            $checkExist = $this->mdl->checkExist($v['salesshipmentno']);
            if(empty($checkExist)) { //create a new record
                
                $driver_id = '';
                $driverDetails = (new Driver_user)->getDriverDetails($v['vehicle']);
                if(!empty($driverDetails)) {
                    
                    $driver_id = $driverDetails['id'];
                }
                $insert = [];
                $insert['salesshipmentno']      = $v['salesshipmentno'];
                $insert['customernumber']       = $v['customernumber'];
                $insert['salesperson']          = $v['salesperson'];
                $insert['contactperson']        = $v['contactperson'];
                $insert['contactnumber']        = $v['contactnumber'];
                $insert['customername']         = $v['customername'];
                $insert['shiptoname']           = $v['shiptoname'];
                $insert['salesordernumber']     = $v['salesordernumber'];
                $insert['shiptoaddress']        = $v['shiptoaddress'];
                $insert['shiptoaddress2']       = $v['shiptoaddress2'];
                $insert['postingdate']          = $v['postingdate'];
                $insert['assigned_driver_id']   = $driver_id;
                $id = $this->mdl->createRecord($insert); 
                if(!empty($v['navstaginglines'])) {

                    foreach($v['navstaginglines'] as $key=>$value){
                        
                        $insert = [];
                        $insert['sales_shipment_header_id'] = $id;
                        $insert['salesshipmentnumber']      = $value['salesshipmentnumber'];
                        $insert['linenumber']               = $value['linenumber'];
                        $insert['salesordernumber']         = $value['salesordernumber'];
                        $insert['itemnumber']               = $value['itemnumber'];
                        $insert['itemdescription']          = $value['itemdescription'];
                        $insert['unitofmeasurecode']        = $value['unitofmeasurecode'];
                        $insert['quantity']                 = $value['quantity'];
                        $insert['shipmentdate']             = $value['shipmentdate'];
                        $insert['shipmenttime']             = $value['shipmenttime'];
                        $insert['postingdate']             = $value['postingdate'];
                        $insert['delivery_status']          = '2';
                        $this->mdlShipmentLine->createRecord($insert);

                    }         

                }      
            
            }else{ //update existing data
                
                $driver_id = '';
                $driverDetails = (new Driver_user)->getDriverDetails($v['vehicle']);
                if(!empty($driverDetails)) {
                    
                    $driver_id = $driverDetails['id'];
                }

                $where  = [];
                $update = [];
                $update['salesshipmentno']      = $v['salesshipmentno'];
                $update['customernumber']       = $v['customernumber'];
                $update['salesperson']          = $v['salesperson'];
                $update['contactperson']        = $v['contactperson'];
                $update['contactnumber']        = $v['contactnumber'];
                $update['customername']         = $v['customername'];
                $update['shiptoname']           = $v['shiptoname'];
                $update['salesordernumber']     = $v['salesordernumber'];
                $update['shiptoaddress']        = $v['shiptoaddress'];
                $update['shiptoaddress2']       = $v['shiptoaddress2'];
                $update['postingdate']       = $v['postingdate'];
                $update['assigned_driver_id']   = $driver_id;
                $where['id'] = $checkExist['id'];
                $this->mdl->updateRecord($update,$where);
                $shipmentLineList = $this->mdlShipmentLine->getList($checkExist['id']);




                if(!empty($v['navstaginglines'])) {

                    foreach($v['navstaginglines'] as $key=>$value){


                        if($shipmentLineList[$key]['delivery_status'] == '2') {
                            
                            $where  = [];
                            $update = [];
                            $update['sales_shipment_header_id'] = $checkExist['id'];
                            $update['salesshipmentnumber']      = $value['salesshipmentnumber'];
                            $update['linenumber']               = $value['linenumber'];
                            $update['salesordernumber']         = $value['salesordernumber'];
                            $update['itemnumber']               = $value['itemnumber'];
                            $update['itemdescription']          = $value['itemdescription'];
                            $update['unitofmeasurecode']        = $value['unitofmeasurecode'];
                            $update['quantity']                 = $value['quantity'];
                            $update['shipmentdate']             = $value['shipmentdate'];
                            $update['shipmenttime']             = $value['shipmenttime'];
                            $update['postingdate']             = $value['postingdate'];
                            $where['id']                        = $shipmentLineList[$key]['id'];

                            $this->mdlShipmentLine->updateRecord($update,$where);
                        }
                        
                        

                    }         

                } 


                


            }




          
            

            

        }

            $update = [];
            $update['endtime'] = date('Y-m-d H:i:s');
            $update['lock_status'] = '2';
            (new Cron_log)->updateLog($log_id,$update);

       
    }


    public function actionGetResponse()
    {
        $list = $this->mdl->getListApi();
        echo json_encode($list,true);
    }




    
}
