<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Announcement;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */
class AnnouncementController extends Controller {

    /**
     * 
     * @return Mixed
     */
    public function __construct(){

        $this->mdl        = (new Announcement); 
        $this->pag =  new Pagination(new Announcement(),''); 
        $this->adminId    = $_SESSION['INF_adminID'];
    }
    
    public function actionIndex() {

      $this->checkPageAccess(5);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $status   = $this->cleanMe(Router::post('status'));
         $language = $this->cleanMe(Router::post('language')); 
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 
        
         $LanguageArray=$this->mdl->getLanguageArray();
         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "status"   => $status,
                  "language" => $language,
                  "page"     => $page];

         $data=$this->mdl->getAnnouncementList($filter); 
         
         $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$status."','".$language."','***')";
         $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        return $this->render('announcement/index',['LanguageArray'=>$LanguageArray,'datefrom'=>$datefrom,'dateto'=>$dateto,'status'=>$status,'lan'=>$language,'data' => $data, 'pagination'=> $pagination]);
    }

    public function actionCreate() {

        $LanguageArray=$this->mdl->getLanguageArray();
         
        return $this->render('announcement/create',['LanguageArray'=>$LanguageArray]);
    }

    public function actionAdd() {

        $title=cleanMe($_POST['title']);
        $AnnounceLan=cleanMe($_POST['language']);
        $announcementId   = $this->cleanMe(Router::post('id'));
        $status   = $this->cleanMe(Router::post('status'));
        $message   = $this->cleanMe(Router::post('message'));

        $message          = strip_tags(htmlspecialchars_decode($message));
        $message = str_replace("'", "''", "$message");
       
        if(empty($AnnounceLan)){
            $msg=Raise::t('announcement','er06');
            $this->sendMessage("error",$msg); 
            die();
        }
       
        if(empty($title)){
            $msg=Raise::t('announcement','er01');
            $this->sendMessage("error",$msg); 
            die();
        }

         if(empty($message)){
            $msg='Please Enter Message';
            $this->sendMessage("error",$msg); 
            die();
        }
       
         if(empty($announcementId) && sizeof($_FILES) == 0){
           return $this->sendMessage("error","Please Select File To Proceed");  
        } 



        $newFile_org =""; 

         if(!empty($_FILES['filename'])){     
            $filename   = $_FILES['filename']['name'];
            $temp_name  = $_FILES['filename']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('pdf','JPG','png','JPEG','jpeg','jpg');

            if(!in_array($extension, $image_array)){
                
                return $this->sendMessage("error",'Please Select Valid File Format');
            }

            $newFile_org = 'A'.time().'.'.$extension;
            $target_file = FILEUPLOADPATH."announcement/".$newFile_org; 
            $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }

            if(!empty($announcementId)){ // edit

                $oldPic = $this->mdl->getAnnouncement($announcementId);

                $Deletefile = FILEUPLOADPATH."announcement/".$oldPic; 
           
                if (file_exists($Deletefile)) {
                    unlink($Deletefile); 
                }
            }

            if(!move_uploaded_file ($temp_name, $target_file)){
               return $this->sendMessage("error","Something Went Wrong...","error");
            }

        }
        


         $data = array ('id'=>$announcementId,'title'=>$title,'status'=>$status,'filename'=>$newFile_org,'lang_id'=>$AnnounceLan,'message'=>$message);
       
        if($announcementId){
            $update = $this->mdl->updateAnnouncement($data);
            return $this->sendMessage('success','Announcement Updated Successfully');
        }else{
            $insert = $this->mdl->addAnnouncement($data);
            return $this->sendMessage('success','Announcement Added Successfully');
        }
    } 


    public function actiongetEdit(){

        $ID   = $this->cleanMe(Router::post('AnnId'));
        $data = $this->mdl->callsql("SELECT * FROM `announcement` WHERE id='$ID' ","row");

        return  $this->renderJSON($data);
    }

    public function actionDelete(){
        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteAnnouncement($ID);

        if($delete){
            return $this->sendMessage('success',"Announcement Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
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
