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


class CoinSwap extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);

        global $transactionArray, $creditArray;

        $this->tableName = "coin_trade";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];

        $this->transactionArray = $transactionArray;
        $this->creditType = $creditArray;

        $this->assignAttrs();
    }

    /**
     * 
     * @return Array
     */
    public static function attrs() {
        return ["id", "user_id", "coin_swap_from", "coin_swap_to", "swap_out_amout", "swap_out_coin_price", "swap_out_service_fee", "swap_in_amout", "swap_in_coin_price", "swap_in_service_fee", "created_at","created_by", "created_ip","updated_at", "updated_by","updated_ip"];
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
      
        $search = ' WHERE id!=0';

        $searchCoin1 = empty($filter['swapCoin1']) ? $this->getCoinId('btc') : $filter['swapCoin1'];
        $searchCoin2 = empty($filter['swapCoin2']) ? $this->getCoinId('usdt') : $filter['swapCoin2'];

        if(!empty($filter['datefrom']) && !empty($filter['dateto'])){
            $search .= " AND created_at BETWEEN '$filter[datefrom]' AND '$filter[dateto]' ";
        }

        if(!empty($filter['username'])){

            $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$filter[username]%' ","rows");
            $users = array_column($user, 'id');
            $list = empty($users) ? 0 : implode(',', $users);

            $search .= " AND user_id IN ($list) ";  
        }

        if(!empty($filter['swapCoin1']) && !empty($filter['swapCoin2'])){

            $search .= " AND coin_swap_from IN ($searchCoin1,$searchCoin2) AND coin_swap_to IN ($searchCoin1,$searchCoin2) ";  
        }

        if(empty($filter['datefrom']) && empty($filter['dateto']) && empty($filter['username']) && empty($filter['swapCoin1']) && empty($filter['swapCoin2']))
        {  
           $search = "where DATE(FROM_UNIXTIME(created_at)) = CURDATE() AND coin_swap_from IN ($searchCoin1,$searchCoin2) AND coin_swap_to IN ($searchCoin1,$searchCoin2) ";
        }


        $curPage = !empty($filter['page']) ? $filter['page'] : 1;
        $perPage = 50;
        $pagecount = ($curPage - 1) * $perPage;

        $data['total'] = $this->callsql("SELECT SUM(executed_amount) as execAmt, SUM(exceuted_value) as execValue, SUM(swap_out_amout) as totOrder FROM $this->tableName $search ", "row");
        
        $count = $this->callsql("SELECT count(*) FROM $this->tableName $search",'value');
        if(empty($filter['export'])){
          $search .= " ORDER BY id DESC LIMIT $pagecount,$perPage ";

          $data['data']=$this->callsql("SELECT * FROM $this->tableName $search ","rows");

          $data['count']=$count;
          $data['curPage']=$curPage;
          $data['perPage']=$perPage;

        }else{
            $data['data']=$this->callsql("SELECT * FROM $this->tableName $search ORDER BY id DESC",'rows');
        }

        foreach ($data['data'] as $key => $value) {
            $uid = $value['user_id'];
            $userDetails=$this->callsql("SELECT username FROM user WHERE id='$uid'",'row');
            $data['data'][$key]['username']      = $userDetails['username'];


            $data['data'][$key]['date'] = date("d-m-Y", $value['created_at']);
            $data['data'][$key]['exec_time'] = empty($value['updated_at']) ? '-' : date("d-m-Y H:i:s", $value['updated_at']); 

            $coinfrom = $value['coin_swap_from'];
            $coinswapfrom=$this->callsql("SELECT coin_name FROM coin WHERE id='$coinfrom'",'value'); 
            $data['data'][$key]['coinswapfrom'] = $coinswapfrom;

            $cointo = $value['coin_swap_to'];
            $coinswapto=$this->callsql("SELECT coin_name FROM coin WHERE id='$cointo'",'value'); 
            $data['data'][$key]['coinswapto'] = $coinswapto;

            $side = ($coinswapfrom == 'USDT') ? 'Buy' : 'Sell' ;
            $data['data'][$key]['coinSide'] = $side;
                
        }
        if($count==0){
            $data['data'] = array();
        }
            
        
      
        return $data;
    }

    public function getCoinId($coin_code){

        return $this->callsql("SELECT id FROM `coin` WHERE coin_code='$coin_code' ",'value');
    }

   
}
 
