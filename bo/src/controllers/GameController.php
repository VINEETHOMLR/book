<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Game;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */
class GameController extends Controller {

    /**
     * 
     * @return Mixed
     */
    public function __construct(){

        $this->mdl        = (new Game); 
        $this->pag =  new Pagination(new Game(),''); 
        $this->adminId    = $_SESSION['INF_adminID'];
    }
    
    public function actionIndex() {

      $this->checkPageAccess(12);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $status   = $this->cleanMe(Router::post('status'));
         $name = $this->cleanMe(Router::post('name')); 
         $type = $this->cleanMe(Router::post('type')); 
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 
        
         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "status"   => $status,
                  "name" => $name,
                  "type" =>$type,
                  "page"     => $page];

         $data=$this->mdl->getGameList($filter); 
         
         $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$status."','".$name."','".$type."','***')";
         $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        return $this->render('game/index',['datefrom'=>$datefrom,'dateto'=>$dateto,'status'=>$status,'name'=>$name,'type' => $type,'data' => $data, 'pagination'=> $pagination]);
    }

    public function actionCreate() {

         
        return $this->render('game/create');
    }

    public function actionAdd() {

        $name=cleanMe($_POST['name']);
        $code=cleanMe($_POST['code']);
        $vendor=cleanMe($_POST['vendor']);
        $orderNo=cleanMe($_POST['orderNo']);
        $type=cleanMe($_POST['type']);

        $gameId   = $this->cleanMe(Router::post('id'));
        $status   = $this->cleanMe(Router::post('status'));
       
        if(empty($name)){
            $msg="Please Enter Name";
            $this->sendMessage("error",$msg); 
            die();
        }

        $checkName = $this->mdl->validateName($name,$gameId);
        if(!$checkName){
          return $this->sendMessage("error",'This Name Already Exist'); 
        }
       
        if(empty($code)){
            $msg="Please Enter Code";
            $this->sendMessage("error",$msg); 
            die();
        }

        if(empty($vendor)){
            $msg="Please Enter Vendor";
            $this->sendMessage("error",$msg); 
            die();
        }

        if(empty($orderNo)){
            $msg="Please Enter Order No";
            $this->sendMessage("error",$msg); 
            die();
        }

        $checkOrderNo = $this->mdl->validateOrderNo($orderNo,$gameId);
        if(!$checkOrderNo){
          return $this->sendMessage("error",'Please Enter Another Order No'); 
        }

        if(empty($gameId) && sizeof($_FILES) == 0){
           return $this->sendMessage("error","Please Select File To Proceed");  
        } 



        $newFile_org =""; 

         if(!empty($_FILES['filename'])){     
            $filename   = $_FILES['filename']['name'];
            $temp_name  = $_FILES['filename']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('JPG','png','JPEG','jpeg','jpg');

            if(!in_array($extension, $image_array)){
                
                return $this->sendMessage("error",'Please Select Valid File Format');
            }

            $newFile_org = 'A'.time().'.'.$extension;
            $target_file = FILEUPLOADPATH."game/".$newFile_org; 
            $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }

            if(!empty($gameId)){ // edit

                $oldPic = $this->mdl->getGame($gameId);

                $Deletefile = FILEUPLOADPATH."game/".$oldPic; 
           
                if (file_exists($Deletefile)) {
                    unlink($Deletefile); 
                }
            }

            if(!move_uploaded_file ($temp_name, $target_file)){
               return $this->sendMessage("error","Something Went Wrong...","error");
            }

        }
        


         $data = array ('id'=>$gameId,'name'=>$name,'game_code'=>$code,'image_url'=>$newFile_org,'game_vendor'=>$vendor,'order_num'=>$orderNo,'is_hot_game'=>$type,'status'=>$status);
       
        if($gameId){
            $update = $this->mdl->updateGame($data);
            return $this->sendMessage('success','Game Updated Successfully');
        }else{
            $insert = $this->mdl->addGame($data);
            return $this->sendMessage('success','Game Added Successfully');
        }
    } 


    public function actiongetEdit(){

        $ID   = $this->cleanMe(Router::post('gameid'));
        $data = $this->mdl->callsql("SELECT * FROM `game_list` WHERE id='$ID' ","row");

        return  $this->renderJSON($data);
    }

    public function actionDelete(){
        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteGame($ID);

        if($delete){
            return $this->sendMessage('success',"Game Deleted");
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
