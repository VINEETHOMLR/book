<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\SalesShipmentHeader;
use src\models\SalesShipmentLine;
use src\models\Driver;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */
class ShipmentController extends Controller {

    /**
     * 
     * @return Mixed
     */
    public function __construct(){

        $this->mdl        = (new SalesShipmentHeader); 
        $this->pag =  new Pagination(new SalesShipmentHeader(),''); 
        $this->adminId    = $_SESSION['INF_adminID'];
    }
    
    public function actionIndex() {

      $this->checkPageAccess(12);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $status   = $this->cleanMe(Router::post('status'));
         $delivery_status   = $this->cleanMe(Router::post('delivery_status'));
         $assigned_driver_id   = $this->cleanMe(Router::post('assigned_driver_id'));
         $salesshipmentno   = $this->cleanMe(Router::post('salesshipmentno'));
         $name = $this->cleanMe(Router::post('name')); 
         $type = $this->cleanMe(Router::post('type')); 
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 
        
         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "status"   => $status,
                  "delivery_status"   => $delivery_status,
                  "assigned_driver_id"   => $assigned_driver_id,
                  "salesshipmentno"   => $salesshipmentno,
                  "page"     => $page];

         $data=$this->mdl->getShipmentList($filter);
         $driverList =  (new Driver)->getDriverList();  



        
         $onclick = "onclick=pageHistory('".$assigned_driver_id."','".$status."','".$salesshipmentno."','".$delivery_status."','***')";
         $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        return $this->render('shipment/index',['datefrom'=>$datefrom,'dateto'=>$dateto,'status'=>$status,'salesshipmentno'=>$salesshipmentno,'delivery_status' => $delivery_status,'assigned_driver_id'=>$assigned_driver_id,'salesshipmentno'=>$salesshipmentno,'data' => $data, 'pagination'=> $pagination,'driverList'=>$driverList]);
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


    public function actionChangeStatus(){

        $id      = $this->cleanMe(Router::post('id'));
        $status  = $this->cleanMe(Router::post('status'));
        $this->mdl->UpdateSlotStatus($id,$status);
        $activity="Status Updated shipment Id-".$id;
        $this->mdl->adminActivityLog($activity);
        $msg="Successfully updated the shipment status";
        $this->sendMessage('success',$msg);
        return false;

    }

    public function actionGetDriverList()
    {
        
        $data=(new Driver)->getDriverList();  
        $html = '<option value="0">Select Driver</option>';

        foreach($data as $k=>$v){
            $html .='<option value="'.$v['id'].'">'.$v['driver_name'].'/'.$v['vehicle_number'].'</option>';
        }

        return $this->renderJSON($html);
        
        
        
        
    }


    public function actionAssignDriver()
    {

      $id      = $this->cleanMe(Router::post('shipment_id'));
      $assigned_driver_id = $this->cleanMe(Router::post('assigned_driver_ids'));

      if(empty($id)) {
          
          $msg="Please select a shipment to proceed";
          $this->sendMessage("error",$msg); 
          die();
      }

      if(empty($assigned_driver_id)) {
          
          $msg="Please select a driver to proceed";
          $this->sendMessage("error",$msg); 
          die();
      }
      
      $driverDetails = (new Driver)->getuserdetails($assigned_driver_id);
      if($driverDetails['data']['status'] != '1') {
          
          $msg="Selected driver is not active";
          $this->sendMessage("error",$msg); 
          die();
      }
      

      if($this->mdl->assignDriver($id,$assigned_driver_id))
      {
          $msg="Successfully assigned driver";
          $this->sendMessage("success",$msg); 
          die();
      }else{

          $msg="Failed to assign driver";
          $this->sendMessage("error",$msg); 
          die();  
      }



    }

    public function actionGetSalesLines()
    {

      $id      = $this->cleanMe(Router::post('id'));

      $shipmentDetails = $this->mdl->getDetails($id);


      $list = (new SalesShipmentLine)->getList($id);

      $html = "<table class='table'>
              <thead>
                <tr>
                  <th>Order No</th>
                  <th>Item No</th>
                  <th>line No</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Description</th>
                  <th>Customer Weight</th>
                  <th>Shipment Date</th>
                  <th>Shipment Time</th>
                  <th>Delivery Status</th>
                </tr>
              </thead>";
      $tr = "<tbody>";        
      foreach($list as $k=>$v){
          $weight = $v['delivery_status']==1 ? $v['customerweight']: 'Not Delivered';
          $deliveryDate = !empty($v['shipmentdate'])? $v['shipmentdate']: '-';
          $deliveryTime = !empty($v['shipmenttime']) ? date('h:i A',strtotime($v['shipmenttime'])): '-';
          $delivery_status = $v['delivery_status'] == '1' ? 'Delivered' :'Not Delivered';
          $tr .= "<tr>";
          $tr .= "<td>".$v['salesordernumber']."</td>";
          $tr .= "<td>".$v['itemnumber']."</td>";
          $tr .= "<td>".$v['linenumber']."</td>";
          $tr .= "<td>".$v['quantity']."</td>";
          $tr .= "<td>".$v['unitofmeasurecode']."</td>";
          $tr .= "<td>".$v['itemdescription']."</td>";
          $tr .= "<td>".$weight."</td>";
          $tr .= "<td>".$deliveryDate."</td>";
          $tr .= "<td>".$deliveryTime."</td>";
          $tr .= "<td>".$delivery_status."</td>";
          $tr .= "</tr>";
          

      }
      $tr .="</tbody>";   
      $html .= $tr;     
      $html .= "</table>";     
      return $this->renderJSON($html);

    }
    public function actionGetDeliveryDetails()
    {
       $id      = $this->cleanMe(Router::post('id'));
       $shipmentDetails = $this->mdl->getDetails($id);

       $html  = "<table class='table'>";
     //  $html .= "<tbody>";
       $html .= "<tr>";
       $html .= "<td>Weight Ticket</td>";
       $html .= "<td><img src=".FrontEnd.'web/upload/weight/'.$shipmentDetails['customerweightticket']." style='width:100px;height:100px;' ></td>";
       $html .= "</tr>";

       $html .= "<tr>";
       $html .= "<td>Sign</td>";
       $html .= "<td><img src=".FrontEnd.'web/upload/sign/'.$shipmentDetails['customersigneddo']." style='width:100px;height:100px;' ></td>";
       $html .= "</tr>";


       $html .= "<tr>";
       $html .= "<td>Picture Url1</td>";
       $html .= "<td>".$shipmentDetails['pictureurl1']."</td>";
       $html .= "</tr>";


       $html .= "<tr>";
       $html .= "<td>Picture Url2</td>";
       $html .= "<td>".$shipmentDetails['pictureurl2']."</td>";
       $html .= "</tr>";


       $html .= "<tr>";
       $html .= "<td>Picture Url3</td>";
       $html .= "<td>".$shipmentDetails['pictureurl3']."</td>";
       $html .= "</tr>";


       $html .= "<tr>";
       $html .= "<td>Location</td>";
       $html .= "<td>".$shipmentDetails['geolocation']."</td>";
       $html .= "</tr>";




       //$html .= "</tbody>";
       $html .= "</table>";

       return $this->renderJSON($html);
          
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
