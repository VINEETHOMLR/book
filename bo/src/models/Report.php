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

class Report extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);

        global $transactionArray, $creditArray;

        $this->tableName = "user_wallet";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->transactionArray = $transactionArray;
        $this->creditType = $creditArray;
        $this->assignAttrs();

        $this->perPage = 50;
    }

    /**
     * 
     * @return Array
     */
    public static function attrs() {
        return ["id", "user_id","asa_wallet","btc_wallet", "eth_wallet", "created_at", "created_by", "created_ip", "updated_at ", "updated_by", "updated_ip"];
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
    
    public function Daywise($filter){

      $search = ' WHERE status !=3 ';
      $search1 ='';

    
      if(!empty($filter['username'])){
        $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$filter[username]%' ","rows");
          $users = array_column($user, 'id');
          $list = empty($users) ? 0 : implode(',', $users);

          $search .= " AND id IN ($list) ";
          $search1 .= " AND user_id IN ($list) ";    
      }
      $where ="";
      $where1 ="";
      if(!empty($filter['datefrom']) && !empty($filter['dateto'])){
        $where .= " AND created_at BETWEEN '$filter[datefrom]' AND '$filter[dateto]' ";
      }

      $curPage = !empty($filter['page']) ? $filter['page'] : 1;
      $pagecount = ($curPage - 1) * $this->perPage;

      $count = $this->callsql("SELECT count(*) FROM user $search",'value');

      if(empty($filter['export'])){
        $search .= " ORDER BY id DESC LIMIT $pagecount,$this->perPage ";
        $data['data']=$this->callsql("SELECT id,username FROM user $search ","rows");
      }else{
          $data['data']= $this->callsql("SELECT id,username FROM user $search ORDER BY id DESC",'rows');
      }

      $coin_id_BTC = $this->callsql("SELECT id FROM coin WHERE coin_code='btc'",'value');
      $coin_id_USDT = $this->callsql("SELECT id FROM coin WHERE coin_code='usdt'",'value');
      $coin_id_ETH = $this->callsql("SELECT id FROM coin WHERE coin_code='eth'",'value');
      
      foreach ($data['data'] as $key => $value) {
        $data['data'][$key]['username'] = $value['username'];
        $uid = $value['id'];
        
        $depositBTC=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM deposit WHERE user_id='$uid' AND status=2 AND coin_id=$coin_id_BTC $where",'value');
        $data['data'][$key]['depositBTC'] = $depositBTC;

        $depositUSDT=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM deposit WHERE user_id='$uid' AND status=2 AND coin_id=$coin_id_USDT $where",'value');
        $data['data'][$key]['depositUSDT'] = $depositUSDT;

        $depositETH=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM deposit WHERE user_id='$uid' AND status=2 AND coin_id=$coin_id_ETH $where",'value');
        $data['data'][$key]['depositETH'] = $depositETH;

          

        $withdrawlBTC=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM coin_withdrawal WHERE user_id='$uid' AND status=2 AND coin_code='btc' $where",'value');
        $data['data'][$key]['withdrawlBTC']   = $withdrawlBTC;

        $withdrawlUSDT=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM coin_withdrawal WHERE user_id='$uid' AND status=2 AND coin_code='usdt' $where",'value');
        $data['data'][$key]['withdrawlUSDT']   = $withdrawlUSDT;

        $withdrawlETH=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM coin_withdrawal WHERE user_id='$uid' AND status=2 AND coin_code='eth' $where",'value');
        $data['data'][$key]['withdrawlETH']   = $withdrawlETH;
      }
      $data1['depositBTC']=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM deposit WHERE status=2 AND coin_id=$coin_id_BTC $where $search1",'value');
      $data1['depositUSDT']=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM deposit WHERE   status=2 AND coin_id=$coin_id_USDT $where $search1",'value');
      $data1['depositETH']=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM deposit WHERE status=2 AND coin_id=$coin_id_ETH $where $search1",'value');

      $data1['withdrawlBTC']=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM coin_withdrawal WHERE status=2 AND coin_code='btc' $where $search1",'value');
      $data1['withdrawlUSDT']=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM coin_withdrawal WHERE status=2 AND coin_code='usdt' $where $search1",'value');
      $data1['withdrawlETH']=$this->callsql("SELECT IFNULL(SUM(amount),0) as amt FROM coin_withdrawal WHERE status=2 AND coin_code='eth' $where $search1",'value');

      $data['count']=$count;
      $data['total']=$data1;
      $data['curPage']=$curPage;
      $data['perPage']=$this->perPage;
       
        
      return $data;
    }

    public function Consolidate($filter){

      $search = ' WHERE id!=0 ';
      if(!empty($filter['username'])){
        $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$filter[username]%' ","rows");
        $users = array_column($user, 'id');
        $list = empty($users) ? 0 : implode(',', $users);
        $search .= " AND user_id IN ($list) ";
           
      }

      $curPage = !empty($filter['page']) ? $filter['page'] : 1;
      $pagecount = ($curPage - 1) * $this->perPage;
      $count = $this->callsql("SELECT count(*) FROM $this->tableName $search",'value');

      if(empty($filter['export'])){
        $data['total']=$this->callsql("SELECT IFNULL(SUM(btc_wallet),0) as btc_wallet, IFNULL(SUM(usdt_wallet),0) as usdt_wallet, IFNULL(SUM(eth_wallet),0) as eth_wallet FROM $this->tableName $search ","row");
            
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
        $userDetails=$this->callsql("SELECT username, fullname FROM user WHERE id='$uid'",'row');
            $data['data'][$key]['username']      = $userDetails['username'];
                
      }
      if($count==0){
            $data['data'] = array();
      }

      return $data;
    
    }
}
