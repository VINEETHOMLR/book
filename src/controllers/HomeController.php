<?php

namespace src\controllers;

use inc\Controller;
use inc\Raise;
use src\lib\Router;
use src\lib\Helper;
use src\lib\Secure;
use src\lib\RRedis;
use src\lib\ValidatorFactory;
use src\models\SalesShipmentHeader;
use src\models\SalesShipmentLine;



class HomeController extends Controller
{
    
    protected $needAuth = true;
    protected $authExclude = [];

    public function __construct()
    {
        parent::__construct();
        $this->mdl = (new SalesShipmentHeader);
    }

    public function actionGetShipmentList(){

       //ALTER TABLE `sales_shipment_line` ADD `delivery_status` INT NOT NULL DEFAULT '2' COMMENT '1-delivered,2-not delivered' AFTER `customerweight`;
        $input      = $_POST;
        $userObj = Raise::$userObj;
        $userId = $userObj['id'];



        
        if(empty($userId)) {
            
            return $this->renderAPIError('Userid cannot be empty','E04'); 
        }

        //get shipment for current day
        /*$startdate = date('Y-m-d').' 00:00:00';
        $enddate = date('Y-m-d').' 23:59:59';
        $filter['startdate']  =  strtotime($startdate);
        $filter['enddate']    =  strtotime($enddate);*/
        //$filter['shipmentdate'] = date('Y-m-d');
        $filter['shipmentdate'] = '0001-01-01';
        $filter['driver_id']     =  $userId;

       /* echo "<pre>";
        print_r($filter);exit;*/
        $shipmentList = (new SalesShipmentLine)->getShipmentList($filter);

       

        if(!empty($shipmentList)) {
            
            $message = "Shipment list";
            $status  = 'true'; 
            $error_code = 'S01';

        }else{

            
            
            $message = "No data found";
            $status  = 'false';
            $error_code = 'E00';
        }

        $data = array('List'=>$shipmentList);
        
        
        return $this->renderAPI($data, $message, 'false', $error_code, $status, 200);
      
    }

    public function actionGetShipmentListbkp(){

        //$input      = $_POST;
        $userObj = Raise::$userObj;
        $userId = $userObj['id'];

        if(empty($userId)) {
            
            return $this->renderAPIError('Userid cannot be empty','E04'); 
        }

        //get shipment for current day
        $startdate = date('Y-m-d').' 00:00:00';
        $enddate = date('Y-m-d').' 23:59:59';
        $filter['startdate']  =  strtotime($startdate);
        $filter['enddate']    =  strtotime($enddate);
        $filter['userId']     =  $userId;
        $shipmentList = $this->mdl->getShipmentList($filter);

       

        if(!empty($shipmentList)) {
            
            $message = "Shipment list";
            $status  = 'true'; 
            $error_code = 'S01';

        }else{

            
            
            $message = "No data found";
            $status  = 'false';
            $error_code = 'E00';
        }

        $data = array('List'=>$shipmentList);
        
        return $this->renderAPI($data, $message, 'false', $error_code, $status, 200);
      
    }

    

  


}
