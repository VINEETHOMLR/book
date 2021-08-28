<?php

/**
 * @author 
 * @desc <Core>Auto Generated model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;
use src\lib\Router;

class Transfer extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        global $transactionArray, $creditArray;
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "finance_trade";
        $this->assignAttrs();

        $this->btc_link = 'https://www.blockchain.com/btc/tx/';
        $this->usdt_link = 'https://etherscan.io/tx/';
        $this->creditType = $creditArray;
        $this->decimal_limit = array("btc" => "8","usdt" => "6","aqn" => "3");
    }

    /**
     * 
     * @return Array
     */
    public static function attrs() {
        return ["id","user_id","amount","from_address","to_address","coin_id","current_coin_price","service_charge","trans_hash","status","created_at","created_by","created_ip","updated_at","updated_by","updated_ip"];
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
      
      $search = 'WHERE id!=0';
      //FOR DEPOSIT 
      
        if($filter['status']!=""){
            $search .= " AND status = '$filter[status]' ";
        }else{
          if($filter['status']== "0"){
            $search .= " AND status = '0 ";

          }

        }

        if($filter['coin']!=""){
            $search .= " AND coin_id = '$filter[coin]' ";
        }
        if($filter['creditType']!=""){

            $search .= " AND type = '$filter[creditType]' ";
        }
       

        if(!empty($filter['username'])){

            $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$filter[username]%' ","rows");
            $users = array_column($user, 'id');
            $list = empty($users) ? 0 : implode(',', $users);

            $search .= " AND user_id IN ($list) ";  
        }
       
        if(!empty($filter['datefrom']) && !empty($filter['dateto'])){
            $search .= " AND created_at BETWEEN '$filter[datefrom]' AND '$filter[dateto]' ";
        }

        if(empty($filter['datefrom']) && empty($filter['dateto']) && ($filter['status']=="") && empty($filter['coin']) && empty($filter['username']) &&($filter['creditType']==""))
        {
          $search = "where DATE(FROM_UNIXTIME(created_at)) = CURDATE() ";
        }

     
     
        $curPage = !empty($filter['page']) ? $filter['page'] : 1;
        $perPage = 50;
        $pagecount = ($curPage - 1) * $perPage;

        $count = $this->callsql("SELECT count(*) FROM $this->tableName $search",'value');

        if(empty($filter['export'])){
          $search .= " ORDER BY id DESC LIMIT $pagecount,$perPage ";

          $data['data']=$this->callsql("SELECT * FROM $this->tableName $search ","rows");

          $data['count']=$count;
          $data['curPage']=$curPage;
          $data['perPage']=$perPage;
        }else{
            $data['data']= $this->callsql("SELECT * FROM $this->tableName $search",'rows');
        }
          $statusArr= array(0=>'Pending',1=>'Success',2=>'Failed');
          $creditArray= array(0=>'Debit',1=>'Credit');
        foreach ($data['data'] as $key => $value) {
            $uid = $value['user_id'];
            $coin_id = $value['coin_id'];
            $data['data'][$key]['coin_code']=$this->callsql("SELECT coin_code FROM coin WHERE id='$coin_id'",'value');
            $userDetails=$this->callsql("SELECT username FROM user WHERE id='$uid'",'row');
            $data['data'][$key]['username']      = $userDetails['username'];
            $data['data'][$key]['create_time'] = date("d-m-Y", $value['created_at']); 
            $data['data'][$key]['status'] = $statusArr[$value['status']];
            $data['data'][$key]['amount'] = $value['amount'];
            $data['data'][$key]['creditType']=empty($value['type'])?"-":$creditArray[$value['type']];
                
        }
        if($count==0){
            $data['data'] = array();
        }
        
       
        return $data;
    }


    public function getCoinlist(){
      return $clist=$this->callsql("SELECT `id`, `coin_name`,coin_code FROM `coin`","rows");
    }
    public function historyTotals($filter){

      $search = 'WHERE id!=0';
      //FOR DEPOSIT 
      
        if($filter['status']!=""){
            $search .= " AND status = '$filter[status]' ";
        }else{
          if($filter['status']== "0"){
            $search .= " AND status = '0 ";

          }

        }

        if($filter['coin']!=""){
            $search .= " AND coin_id = '$filter[coin]' ";
        }
        if($filter['creditType']!=""){

            $search .= " AND type = '$filter[creditType]' ";
        }
       

        if(!empty($filter['username'])){

            $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$filter[username]%' ","rows");
            $users = array_column($user, 'id');
            $list = empty($users) ? 0 : implode(',', $users);

            $search .= " AND user_id IN ($list) ";  
        }
       
        if(!empty($filter['datefrom']) && !empty($filter['dateto'])){
            $search .= " AND created_at BETWEEN '$filter[datefrom]' AND '$filter[dateto]' ";
        }

        if(empty($filter['datefrom']) && empty($filter['dateto']) && ($filter['status']=="") && empty($filter['coin']) && empty($filter['username'])&&($filter['creditType']==""))
        {
          $search = "where DATE(FROM_UNIXTIME(created_at)) = CURDATE() ";
        }

        if($filter['creditType']!=""){

        $data['total']['btc'] = $this->callsql("SELECT sum(amount) FROM $this->tableName $search AND coin_id=1 ","value");
        $data['total']['eth'] = $this->callsql("SELECT sum(amount) FROM $this->tableName $search AND coin_id=2","value");
        $data['total']['usdt']= $this->callsql("SELECT sum(amount) FROM $this->tableName $search AND coin_id=3","value");
        $data['total']['itt']= $this->callsql("SELECT sum(amount) FROM $this->tableName $search AND coin_id=4","value");
      }else{
          

          $btc1 = $this->callsql("SELECT SUM(amount) FROM $this->tableName $search AND type=1 AND coin_id=1",'value');
          $btc2 = $this->callsql("SELECT SUM(amount) FROM $this->tableName $search AND type=0 AND coin_id=1",'value');

           $data['total']['btc'] = $btc1-$btc2;

           $eth1 = $this->callsql("SELECT SUM(amount) FROM $this->tableName $search AND type=1 AND coin_id =2",'value');
          $eth2 = $this->callsql("SELECT SUM(amount) FROM $this->tableName $search AND type=0 AND coin_id=2",'value');

           $data['total']['eth'] = $eth1-$eth2;

           $usdt1 = $this->callsql("SELECT SUM(amount) FROM $this->tableName $search AND type=1 AND coin_id=3",'value');
          $usdt2 = $this->callsql("SELECT SUM(amount) FROM $this->tableName $search AND type=0 AND coin_id =3" ,'value');

           $data['total']['usdt'] = $usdt1-$usdt2;

           $itt1 = $this->callsql("SELECT SUM(amount) FROM $this->tableName $search AND type=1 AND coin_id=4",'value');
          $itt2 = $this->callsql("SELECT SUM(amount) FROM $this->tableName $search AND type=0 AND coin_id =4",'value');

           $data['total']['itt'] = $itt1-$itt2;
        }
        return $data['total'];
    }


}
