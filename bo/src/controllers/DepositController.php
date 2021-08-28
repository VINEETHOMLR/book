<?php

namespace src\controllers;

use inc\Controller;
use src\models\Deposit;
use src\lib\Router;
use src\lib\Pagination;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */
class DepositController extends Controller {

    /**
     * 
     * @return Mixed
     */ 



    public function __construct(){

        $this->mdl   = (new Deposit);
        $this->pag = new Pagination(new Deposit(),''); 
        $this->statusArray = ["Pending", "Processing", "Approved", "Rejected"];
    }

    public function actionIndex() {

        $servicesArray = $_SESSION['INF_privilages'];
        $servicesArray = array_values($servicesArray);
        $servicesArray = explode(",", $servicesArray[0]);
        $role          = $_SESSION['INF_role'];
        
        if( !in_array(11, $servicesArray) ) {
            if($role !=1){
              header("Location: ".BASEURL."");
              exit;
            }
        }
     
        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto')); 
        $status = $this->cleanMe(Router::post('status')); 
        $coin = $this->cleanMe(Router::post('coin')); 
        $username = $this->cleanMe(Router::post('username')); 
        $page = (!empty($_POST['page'])) ? $this->cleanMe($_POST['page']) : '1'; 

        
        $date_from = $date_to = '';
        if(!empty($datefrom)){
            $date_from = strtotime($datefrom." 00:00:00");
        }
        if(!empty($dateto)){
            $date_to = strtotime($dateto." 23:59:59");
        }

        $filter=["datefrom" => $date_from,
                "dateto"   =>  $date_to,
                'page' => $page,
                'status' =>$status,
                'coin'   =>$coin,
                'username'=>$username
            ];

      
          
        $data=$this->mdl->history($filter); 
        $coinArray=$this->mdl->getCoinlist();
        
        $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$coin."','".$status."','".$username."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('Deposit/history',['datefrom'=>$datefrom,'dateto'=>$dateto,'data' => $data, 'pagination'=> $pagination,'status'=>$status,'coin'=>$coin,'coinArray'=>$coinArray,'username'=>$username]);
    } 



}

