<?php

namespace src\controllers;

use inc\Controller;
use src\models\Btc;
use src\models\WalletHistory;
use src\lib\Router;
use src\lib\Pagination;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */
class WalletController extends Controller {

    /**
     * 
     * @return Mixed
     */

    public function __construct(){

        global $transactionArray, $creditArray;

        
        $this->mdl   = (new WalletHistory);
        $this->admin = $_SESSION['INF_adminID'];
        $this->transactionArray = $transactionArray;
        $this->creditType = $creditArray; 
        $this->pag = new Pagination(new WalletHistory(),'');       
    }

    public function actionBtc() {
        $this->checkPageAccess(16);

        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto'));
       
        $creditType = $this->cleanMe(Router::post('creditType')); 
        $txn_type = $this->cleanMe(Router::post('txn_type')); 
        $username = $this->cleanMe(Router::post('username')); 
        
       
        $page = (!empty($_POST['page'])) ? $this->cleanMe($_POST['page']) : '1'; 
        $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
        $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

        $filter=["datefrom" => $date_from,
                "dateto"   =>  $date_to,
                "creditType" => $creditType,
                'txn_type'=>$txn_type,
                'page' => $page,
                'username'=>$username,
                'coin_code'=>'btc',
            ];

            

        $data=$this->mdl->history($filter);
        $sum=$this->mdl->historySum($filter); 
        
        $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$username."','".$txn_type."','".$creditType."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('wallet/btc',['datefrom'=>$datefrom,'dateto'=>$dateto,'creditType'=>$creditType, 'txn_type'=>$txn_type, 'username'=>$username,'data' => $data, 'pagination'=> $pagination,'sum'=>$sum]);
    } 

    public function actionUsdt() {
        $this->checkPageAccess(17);
        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto'));
        $creditType = $this->cleanMe(Router::post('creditType')); 
        $txn_type = $this->cleanMe(Router::post('txn_type')); 
        $username = $this->cleanMe(Router::post('username')); 
        
       
        $page = (!empty($_POST['page'])) ? $this->cleanMe($_POST['page']) : '1'; 
        $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
        $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

        $filter=["datefrom" => $date_from,
                "dateto"   =>  $date_to,
                "creditType" => $creditType,
                'txn_type'=>$txn_type,
                'page' => $page,
                'username'=>$username,
                'coin_code'=>'usdt'
            ];


        
        $data=$this->mdl->history($filter);
        $sum=$this->mdl->historySum($filter); 
        

         $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$username."','".$txn_type."','".$creditType."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('wallet/usdt',['datefrom'=>$datefrom,'dateto'=>$dateto,'creditType'=>$creditType, 'txn_type'=>$txn_type, 'username'=>$username, 'data' => $data, 'pagination'=> $pagination,'sum'=>$sum]);
    }
    public function actionETH() {
        $this->checkPageAccess(18);
        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto'));
        $creditType = $this->cleanMe(Router::post('creditType')); 
        $txn_type = $this->cleanMe(Router::post('txn_type')); 
        $username = $this->cleanMe(Router::post('username')); 
        
       
        $page = (!empty($_POST['page'])) ? $this->cleanMe($_POST['page']) : '1'; 
        $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
        $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

        $filter=["datefrom" => $date_from,
                "dateto"   =>  $date_to,
                "creditType" => $creditType,
                'txn_type'=>$txn_type,
                'page' => $page,
                'username'=>$username,
                'coin_code'=>'eth'
            ];


        
        $data=$this->mdl->history($filter);
        $sum=$this->mdl->historySum($filter); 
        

         $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$username."','".$txn_type."','".$creditType."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('wallet/eth',['datefrom'=>$datefrom,'dateto'=>$dateto,'creditType'=>$creditType, 'txn_type'=>$txn_type, 'username'=>$username, 'data' => $data, 'pagination'=> $pagination,'sum'=>$sum]);
    }

    public function actionITT() {

        $this->checkPageAccess(19);

        $datefrom   = $this->cleanMe(Router::post('datefrom')); 
        $dateto     = $this->cleanMe(Router::post('dateto'));
        $creditType = $this->cleanMe(Router::post('creditType')); 
        $txn_type   = $this->cleanMe(Router::post('txn_type')); 
        $username   = $this->cleanMe(Router::post('username')); 
        
        $page = (!empty($_POST['page'])) ? $this->cleanMe($_POST['page']) : '1'; 
        $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
        $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

        $filter=["datefrom"    => $date_from,
                "dateto"       => $date_to,
                "creditType"   => $creditType,
                'txn_type'     => $txn_type,
                'page'         => $page,
                'username'     => $username,
                'coin_code'    => 'itt'
            ];

        $data=$this->mdl->history($filter);
        $sum=$this->mdl->historySum($filter); 
        
        $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$username."','".$txn_type."','".$creditType."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('wallet/itt',['datefrom'=>$datefrom,'dateto'=>$dateto,'creditType'=>$creditType, 'txn_type'=>$txn_type, 'username'=>$username, 'data' => $data, 'pagination'=> $pagination,'sum'=>$sum]);
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
