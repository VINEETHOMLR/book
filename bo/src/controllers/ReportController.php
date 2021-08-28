<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Report;
/**
 * To handle the users data models
 * @author 
 */
class ReportController extends Controller {

    /**
     * 
     * @return Mixed
     */

    public function __construct(){

      
        $this->admin = $_SESSION['INF_adminID'];
        $this->pag = new Pagination(new Report(),''); 
        $this->mdl   = (new Report);
    }

   public function actionDaywise() {

    $this->checkPageAccess(15);

        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto'));
        $username  = $this->cleanMe(Router::post('username'));
        
        $page = (!empty($_POST['page'])) ? $this->cleanMe($_POST['page']) : '1'; 
        $check = (!empty($_POST['check'])) ? $_POST['check'] : '0';
        $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
        $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

        $filter=["datefrom" => $date_from,
                 "dateto"   =>  $date_to,
                 'username'=>$username,
                 'page' => $page,
                 
                 
            ]; 

        $data=$this->mdl->Daywise($filter);
        $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$username."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('report/daywise',['datefrom'=>$datefrom,'dateto'=>$dateto, 'username'=>$username,'data' => $data,'pagination'=>$pagination]);
    }
    public function actionConsolidate(){

        $this->checkPageAccess(14);

        $username = $this->cleanMe(Router::post('username')); 
        $page = (!empty($_POST['page'])) ? $this->cleanMe($_POST['page']) : '1'; 
        $filter=['username'=>$username,
                'page' => $page,
                
            ];

        $data=$this->mdl->Consolidate($filter); 
        $onclick = "onclick=pageHistory('".$username."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('report/consolidate',['username'=>$username,'data' => $data, 'pagination'=> $pagination]);
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

