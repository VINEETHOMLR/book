<?php

/**
 * @author 
 * @desc To describe an example of Model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;

/**
 * @author 
 */
class SalesShipmentLine extends Database {

    /**
     *
     * @var Resource
     */
     public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "sales_shipment_line";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }

      public function getList($sales_shipment_header_id){

       $response = $this->callSql("SELECT * FROM $this->tableName WHERE  sales_shipment_header_id = '$sales_shipment_header_id' ORDER BY id asc","rows");


     


        if(!empty($response)) {
            
            return $response;
        }else{
            return [];
        }



    }


    public function getRecords($filter)
    {
        $where = " where id!=0";

        if(!empty($filter['sales_shipment_header_id'])) {
            $where .= " AND sales_shipment_header_id = $filter[sales_shipment_header_id]";
        }
        $salesLineList = $this->callSql("SELECT * FROM $this->tableName $where ORDER BY id asc","rows");
        $data = [];
        if(!empty($salesLineList)) {

            foreach($salesLineList as $k=>$v){
                
                $data[] = array(  
                                  'shipment_number' => $v['salesshipmentnumber'],
                                  'order_number'    => $v['salesordernumber'],
                                  'item_number'     => $v['itemnumber'],
                                  'line_number'     => $v['linenumber'],
                                  'description'     => $v['itemdescription'],
                                  'uom'             => $v['unitofmeasurecode'],
                                  'quantity'        => $v['quantity'],
                               );   
            }

            return $data;

        }else{
            return [];
        }

    }


    



}
