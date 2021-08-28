<?php

namespace src\controllers;

use inc\Controller;
use src\models\KycList;
use src\lib\Router;
use src\lib\Pagination;
use inc\Raise;
/**
 * To handle the users data models
 * 
 */

class KycController extends Controller {

    /**
     * 
     * @return Mixed
     */
    public function __construct(){

        $this->mdl = (new KycList);
        $this->adminId = $_SESSION['INF_adminID'];
        $this->pag =  new Pagination(new KycList(),'');
        $this->KycStatusArry = array('0'=>'Pending','1'=>'Approved','2'=>'Rejected');
    }

    public function actionIndex() {

     // $this->checkPageAccess(7);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $username   = $this->cleanMe(Router::post('username'));
         $status   = $this->cleanMe(Router::post('status'));
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 
        
         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "username" => $username,
                  "status"   => $status,
                  "page"     => $page];

          $data=$this->mdl->getKYCList($filter); 
         /* print_r($data);
          die();*/
         
          $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$username."','".$status."','***')";
          $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        return $this->render('users/KycVerification',['data'=>$data,'pagination'=>$pagination,'datefrom'=>$datefrom,'dateto'=>$dateto,'status'=>$status,'username'=>$username]);
    }

    public function actionKycVerify(){ 

         $id  = $this->cleanMe(Router::post('id')); 
         $type = $this->cleanMe(Router::post('type'));
         $rmak = $this->cleanMe(Router::post('remark'));

         if(empty($rmak)){
            $msg="Please Enter Remarks";
            $this->sendMessage("error",$msg); 
            die();
         }

         $verify=$this->mdl->Verify($id,$type,$rmak);

         if($verify==true){
            if($type==2){
              $msg="Verification Successfull";
            }else{
              $msg="Rejected Successfully";
            }
            $this->sendMessage('success',$msg); 
         }
    }
        public function actionLoadKycDetails()
    {
        $r_id= $this->cleanMe(Router::post('request_id'));
        $result = array();

        $data =$this->mdl->getKycdetails($r_id);  
      
        $result['status'] = 'success';
        $result['html'] = $this->getKycDetailHTML($data);

        $this->renderJSON($result);
    }
    public function getKycDetailHTML($ip)
    {

        $rows = !empty($ip['data']['data'])?$ip['data']['data']:array();
            
        $table = '';
        if (!empty($rows)) {
            foreach ($rows as $info) {

                
                
                $table .= '<tr role=\'row\' class=\'odd\'>';
                $table .= '<td>Request Id</td><td>'.(!empty($info['id'])?$info['id']:'').'</td></tr>';
                $table .= '<tr><td>UserName</td><td>'.(!empty($info['username'])?$info['username']:'-').'</td></tr>';
                $table .= '<tr><td>FullName</td><td>'.(!empty($info['fullname'])?$info['fullname']:'-').'</td></tr>';
                $table .= '<tr><td>ID Number</td><td>'.(!empty($info['kyc_id_number'])?$info['kyc_id_number']:'-').'</td></tr><tr>';
                $table .= '<tr><td>ID Real Name</td><td>'.(!empty($info['kyc_id_real_name'])?$info['kyc_id_real_name']:'-').'</td></tr><tr>'; 
                $table .= '<tr><td>Identity Card</td><td>'.$info['id_img'].'</td></tr><tr>'; 
                $table .= '<tr><td>ID Document</td><td>'.$info['id_doc'].'</td></tr><tr>'; 
                $table .= '<td>Passport Number</td><td>'.(!empty($info['kyc_passport_number'])?$info['kyc_passport_number']:'-').'</td></tr>';
                $table .= '<td>Passport Real Name</td><td>'.(!empty($info['kyc_passport_real_name'])?$info['kyc_passport_real_name']:'-').'</td></tr>';
                $table .= '<td>Passport </td><td>'.$info['passport'].'</td></tr>';
                $table .= '<td>Passport Photo</td><td>'.$info['passport_photo'].'</td></tr>';
                $table .= '<td>Passport Document</td><td>'.$info['passport_document'].'</td></tr>';
                $table .= '<td>Status</td><td>'.(!empty($info['status'])?$info['status']:'-').'</td></tr>';
                $table .= '<td>Request Time</td><td>'.$info['time'].'</td></tr>';
                $table .= '<td>Remarks</td><td>'.$info['remarks'].'</td></tr>';
            }       
        } else {
            $table .= '<tr><td colspan=\'1\' class=\'text-center\'>No Data Found</td></tr>';
        }

        
        

        return ['table' => $table];
    }

}
