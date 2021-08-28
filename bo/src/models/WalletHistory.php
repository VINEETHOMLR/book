<?php

/**
 * @author 
 * @desc <Core>Auto Generated model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;
use src\lib\Router;
use src\inc\transactionArray;


class WalletHistory extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);

        global $transactionArray, $creditArray;
        $this->tableName = "wallet_log";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->transactionArray = $transactionArray;
        $this->creditType = $creditArray;
        $this->perPage = 50;

        $this->assignAttrs();
    }

    /**
     * 
     * @return Array
     */
    public static function attrs() {
        return ["id", "user_id", "transaction_type", "credit_type", "value", "before_bal", "after_bal", "created_at", "created_by", "created_ip", "remarks","coin_id"];
    }

    /**
     * 
     * @return $this
     */
    public function assignAttrs($attrs = []) {
        $isExternal = !empty($attrs);
        foreach (($isExternal ? $attrs : self::attrs()) as $eAttr => $attr) {
            $aAttr = $isExternal ? $eAttr : $attr;
            $this->{$aAttr} = $isExternal ? $attr : (Router::post($attr) !== "" ? Router::post($attr) : "");
        }
        return $this;
    }

    /**
     * 
     * @param INT $pk
     */
    public function findByPK($pk) {
        $dtAry = parent::findByPK($pk);
        foreach ($dtAry as $attr => $val) {
            $this->{$attr} = $val;
        }
        return $this;
    }

   
    public function history($filter){

      $coinId = $this->callsql("SELECT id FROM coin WHERE coin_code='$filter[coin_code]'",'value');
      
        $search = " WHERE id!=0 AND coin_id='$coinId'";

        if(!empty($filter['username'])){

            $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$filter[username]%' ","rows");
            $users = array_column($user, 'id');
            $list = empty($users) ? 0 : implode(',', $users);

            $search .= " AND user_id IN ($list) ";  
        }

        if($filter['creditType']!=""){

            $search .= " AND credit_type = '$filter[creditType]' ";
        }
       
        
        if($filter['txn_type']!=""){
            $search .= " AND transaction_type = '$filter[txn_type]' ";
        }

        if(!empty($filter['datefrom']) && !empty($filter['dateto'])){
            $search .= " AND created_at BETWEEN '$filter[datefrom]' AND '$filter[dateto]' ";
        }

        if(empty($filter['username']) && empty($filter['datefrom']) && empty($filter['dateto']) && empty($filter['txn_type']) && ($filter['creditType']==""))
        {
           $search = "where DATE(FROM_UNIXTIME(created_at)) = CURDATE() AND coin_id=$coinId";
        }


        $curPage = !empty($filter['page']) ? $filter['page'] : 1;
       
        $pagecount = ($curPage - 1) * $this->perPage;

        $count = $this->callsql("SELECT count(*) FROM $this->tableName $search",'value');
        if(empty($filter['export'])){
          $search .= " ORDER BY id DESC LIMIT $pagecount,$this->perPage ";

          $data['data']=$this->callsql("SELECT * FROM $this->tableName $search ","rows");

          $data['count']=$count;
          $data['curPage']=$curPage;
          $data['perPage']=$this->perPage;

        }else{
            $data['data']=$this->callsql("SELECT * FROM $this->tableName $search ORDER BY id DESC",'rows');
        }
        
        foreach ($data['data'] as $key => $value) {
            $uid = $value['user_id'];
            $userDetails=$this->callsql("SELECT username FROM user WHERE id='$uid'",'row');
            $data['data'][$key]['username']      = $userDetails['username'];

            $data['data'][$key]['txn_date'] = date("d-m-Y", $value['created_at']);  
            $data['data'][$key]['txn_type'] =$value['transaction_type']? $this->transactionArray[$value['transaction_type']]:'-';
            $data['data'][$key]['credit_type'] = $this->creditType[$value['credit_type']];
                
        }
        if($count==0){
            $data['data'] = array();
        }
            
        
      
        return $data;
    }
    public function historySum($filter){
      $coinId = $this->callsql("SELECT id FROM coin WHERE coin_code='$filter[coin_code]'",'value');

      $search='';

      if(!empty($filter['username'])){

        $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$filter[username]%' ","rows");
        $users = array_column($user, 'id');
        $list = empty($users) ? 0 : implode(',', $users);
        $search .= " AND user_id IN ($list) ";  
        }

        if($filter['creditType']!=""){
            $search .= " AND credit_type = '$filter[creditType]' ";
        }

        if($filter['txn_type']!=""){
            $search .= " AND transaction_type = '$filter[txn_type]' ";
        }

        if(!empty($filter['datefrom']) && !empty($filter['dateto'])){
            $search .= " AND created_at BETWEEN '$filter[datefrom]' AND '$filter[dateto]' ";
        }

        if(empty($filter['username']) && empty($filter['datefrom']) && empty($filter['dateto']) && empty($filter['txn_type']) &&($filter['creditType']==""))
        {
           $search = "AND DATE(FROM_UNIXTIME(created_at)) = CURDATE()";
        }

        if($filter['creditType']!=""){
            $sum = $this->callsql("SELECT SUM(value) FROM $this->tableName WHERE id!=0 AND coin_id='$coinId' $search",'value');
        }else{
          
          $sum1 = $this->callsql("SELECT SUM(value) FROM $this->tableName WHERE credit_type=0 AND coin_id='$coinId' $search",'value');
          $sum2 = $this->callsql("SELECT SUM(value) FROM $this->tableName WHERE credit_type=1 AND coin_id='$coinId' $search",'value');

          $sum = $sum1-$sum2;
        }
        return $sum;

    }

    
}
 
