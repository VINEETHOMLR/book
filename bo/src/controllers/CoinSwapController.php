<?php

namespace src\controllers;

use inc\Controller;
use src\models\CoinSwap;
use src\lib\Router;
use src\lib\Pagination;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */
class CoinSwapController extends Controller {

    /**
     * 
     * @return Mixed
     */

    public function __construct(){

        global $transactionArray, $creditArray;

        $this->mdl   = (new CoinSwap);
        $this->admin = $_SESSION['INF_adminID'];
        $this->transactionArray = $transactionArray;
        $this->creditType = $creditArray; 
        $this->pag = new Pagination(new CoinSwap(),'');       
    }

    public function actionIndex() {
        $this->checkPageAccess(9);

        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto'));
        $username = $this->cleanMe(Router::post('username')); 
        $swapOption = $this->cleanMe(Router::post('market')); 

        $datefrom = empty($datefrom) ? date("d-m-Y") : $datefrom ;
        $dateto   = empty($dateto) ? date("d-m-Y") : $dateto ;

        $page = (!empty($_POST['page'])) ? $this->cleanMe($_POST['page']) : '1'; 
        $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
        $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

        $swapOption = empty($swapOption) ? 'btc-usdt' : $swapOption ;

        $split = explode('-', $swapOption);
        $swapFrom = $this->mdl->getCoinId($split[0]);
        $swapTo = $this->mdl->getCoinId($split[1]);

        $tableCode = ucwords($split[0]);

        $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "username"   => $username,
                  "page"     => $page,
                  "swapCoin1" => $swapFrom,
                  "swapCoin2" => $swapTo,
              ];

        $data=$this->mdl->history($filter);
        
        $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$username."','".$swapOption."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('coinswap/index',['datefrom'=>$datefrom,'dateto'=>$dateto,'username'=>$username,'data' => $data, 'pagination'=> $pagination,'swapOption'=>$swapOption,'tableCode'=>$tableCode]);
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
