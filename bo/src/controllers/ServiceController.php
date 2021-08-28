<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Admin;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */
class ServiceController extends Controller {

    /**
     * 
     * @return Mixed
     */
    public function __construct(){

        $this->mdl        = (new Admin); 
        $this->ServiceArr = $_SESSION['INF_serArry'];
        $this->adminId    = $_SESSION['INF_adminID'];
    }
    
    public function actionIndex() {  

      if(isset($_REQUEST['id'])){
        $servid=base64_decode($this->cleanMe($_REQUEST['id']));
        $ser=$this->mdl->getService($servid); 
        $servicegrpinfoid = $ser['0']['id'];
        $servicenameedit  = $ser['0']['group_name'];
        $servicesedit     = $ser['0']['services'];
      }else{
        $servicenameedit = $servicesedit= $servicegrpinfoid= '';
      }
       
      $servicesArray=$this->ServiceArr;
       
       return $this->render('admin/create_service',['servicesArray' => $servicesArray,'servicenameedit'=>$servicenameedit,'servicegrpinfoid'=>$servicegrpinfoid,'servicesedit'=>$servicesedit]);
    }

    public function actionGetServiceList(){
        $this->mdl->query("SELECT * FROM admin_services");
        $data = ['data' => $this->mdl->resultset()];
        foreach ($data['data'] as $key => $value) {
            $services = explode(",", $value['services']);
            $selected = '<div class="row col-md-12">';
            foreach ($services as $val) {
                 $selected .= '<div class="col-md-6 col-xl-3 col-sm-12 col-12"><div class="dot"></div>'.$this->ServiceArr[$val].'</div>';
            }
            $selected.= "</div>";
            $data['data'][$key]['service'] = $selected;
            $data['data'][$key]['action'] = '<a href="'.BASEURL.'Service/Index/?id='.(base64_encode($value['id'])).'"><button class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>&nbsp;'.Raise::t('app','edit_text').'</button></a>';
        }
        return $this->renderJSON($data);
    }
    
    public function actionAddService() {
         
        $servicename = $this->cleanMe($_POST['servicename']);
        
        if(empty($servicename)){
           $this->sendMessage ('error',Raise::t('servicegroup','servicenameerror'));
           return false;
        }

        $servegrpid = $this->cleanMe($_POST['servegrpid']);

        $check = $this->mdl->checkServiceName($servicename,$servegrpid);

        if(!empty($check)){
          return  $this->sendMessage ('error',Raise::t('servicegroup','service_err')); 
        }

        if(empty($_POST['servicearr'])){
           $this->sendMessage ('error',Raise::t('servicegroup','serv_err'));
           return false;
        }
    
        $services  = array();

        $servicesArray=$this->ServiceArr;

        $countMax = count($servicesArray);

        foreach ($_POST['servicearr'] as $serviceVal) {
         
           if($serviceVal < 1)
              $this->sendMessage ('error',Raise::t('servicegroup','servicevalid_error')); 
              $services[] = cleanMe($serviceVal);
        }
        $newServiceVal = implode(',', $services);

        if($servegrpid !=''){

           $data = [
            'servegrpid'=>$servegrpid,
            'servicegrpname'=>$servicename,
            'services' => $services
           ];
           $update=$this->mdl->serviceUpdate($data);
           $msg=Raise::t('servicegroup','edit_succ');

        }
        else{
           $data = [
            'servicegrpname'=>$servicename,
            'services' => $services
           ];

        $add =$this->mdl->serviceAdd($data);
        $msg=Raise::t('servicegroup','add_succ');
    }

    return $this->sendMessage('success',$msg );

   }

}
