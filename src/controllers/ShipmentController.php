<?php

namespace src\controllers;

use inc\Controller;
use inc\Raise;
use src\lib\Router;
use src\lib\Helper;
use src\lib\Secure;
use src\lib\RRedis;
use src\lib\ValidatorFactory;
use src\models\SalesShipmentHeader;
use src\models\SalesShipmentLine;



class ShipmentController extends Controller
{
    
    protected $needAuth = true;
    protected $authExclude = ['UpdateShipment'];

    public function __construct()
    {
        parent::__construct();
        $this->mdl = (new SalesShipmentHeader);
        $this->saleslinemdl = (new SalesShipmentLine);
    }

    public function actionGetShipmentDetails()
    {
        
        $input      = $_POST;
        $shipment_id  = issetGet($input,'shipment_id','');
        $userObj = Raise::$userObj;
        $userId = $userObj['id'];

        if(empty($userId)) {
            
            return $this->renderAPIError('Userid cannot be empty','E04'); 
        }

        if(empty($shipment_id)) {
            
            return $this->renderAPIError('Shipment id cannot be empty','E05');
        }

        //$filter['delivery_status']    = '2';
        $filter['assigned_driver_id'] = $userId;
        $filter['id']                 = $shipment_id;

        $shipmentHeaderDetails = $this->mdl->getDetails($filter);

       
        $filter = [];
        $filter['sales_shipment_header_id'] = $shipment_id;
        $filter['delivery_status']          = '2';
        $filter['shipmentdate']             = date('Y-m-d');
        $shipmentSalesLineList              = $this->saleslinemdl->getRecords($filter);
        if(empty($shipmentHeaderDetails)) {
            $shipmentSalesLineList = [];
        }


        $data = array('shipmentDetails' => $shipmentHeaderDetails,'salesLineList'=>$shipmentSalesLineList,'count'=>count($shipmentSalesLineList));
        
        return $this->renderAPI($data, 'Shipment Details', 'false', 'S01', 'true', 200); 
        


    }


    public function actionUpdateShipment()
    {
        
        $input      = $_POST;
        $shipment_id  = issetGet($input,'shipment_id','');
        //$customerweightticket  = issetGet($input,'customerweightticket','');
        //$customersigneddo  = issetGet($input,'customersigneddo','');
        $geolocation  = issetGet($input,'geolocation','');
        //$pictureurl1  = issetGet($input,'pictureurl1','');
        //$pictureurl2  = issetGet($input,'pictureurl2','');
        //$pictureurl3  = issetGet($input,'pictureurl3','');
        $weight  = issetGet($input,'weight',[]);
        $userObj = Raise::$userObj;
        $userId = $userObj['id'];

       /* $post = $_POST;
        $file = $_FILES;
        $merge = json_encode(array_merge($post,$file),true);*/

       // $this->mdl->addlog($merge);


        if(empty($userId)) {
            
            return $this->renderAPIError('Userid cannot be empty','E04'); 
        }

        if(empty($shipment_id)) {
            
            return $this->renderAPIError('Shipment id cannot be empty','E05');
        }


        if(!isset($_FILES['customerweightticket'])){
            
            return $this->renderAPIError('Weight Ticket Image cannot be empty','E06'); 
        }

        
        //upload weight image
        $path = 'web/upload/weight/';
        $file_name = 'weight_'.$shipment_id.'_'.time();
        $uploadcustomerweightticketrespnse = $this->uploadImage($_FILES['customerweightticket'],$path,$file_name);
        $response = $uploadcustomerweightticketrespnse['status'];
        if($response == 'false') {
            
            return $this->renderAPIError($uploadcustomerweightticketrespnse['message'],'E09'); 
        }

        $customerweightticket = $uploadcustomerweightticketrespnse['filename'];

        //upload signed image

        if(!isset($_FILES['customersigneddo'])){
            
            return $this->renderAPIError('Customer Signed Image cannot be empty','E07'); 
        }


        $path = 'web/upload/sign/';
        $file_name = 'sign_'.$shipment_id.'_'.time();
        $uploadcustomersigneddorespnse = $this->uploadImage($_FILES['customersigneddo'],$path,$file_name);
        $response = $uploadcustomersigneddorespnse['status'];
        if($response == 'false') {
            
            return $this->renderAPIError($uploadcustomersigneddorespnse['message'],'E09'); 
        }

        $customersigneddo = $uploadcustomersigneddorespnse['filename'];

        
        $pictureurl1 = '';
        $pictureurl2 = '';
        $pictureurl3 = '';

        if(!empty($_FILES['pictureurl1'])){
            
            $path = 'web/upload/picture/';
            $file_name = 'picture1_'.$shipment_id.'_'.time();
            $uploadpicture1Response= $this->uploadImage($_FILES['pictureurl1'],$path,$file_name);
            $response = $uploadpicture1Response['status'];
            if($response == 'false') {
                
                return $this->renderAPIError($uploadpicture1Response['message'],'E09'); 
            }

            $pictureurl1 = $uploadpicture1Response['filename'];
        }



        

        if(!empty($_FILES['pictureurl2'])){

            $path = 'web/upload/picture/';
            $file_name = 'picture2_'.$shipment_id.'_'.time();
            $uploadpicture2Response= $this->uploadImage($_FILES['pictureurl2'],$path,$file_name);
            $response = $uploadpicture2Response['status'];
            if($response == 'false') {
                
                return $this->renderAPIError($uploadpicture2Response['message'],'E09'); 
            }

            $pictureurl2 = $uploadpicture2Response['filename'];
       }



        if(!empty($_FILES['pictureurl3'])){
            $path = 'web/upload/picture/';
            $file_name = 'picture3_'.$shipment_id.'_'.time();
            $uploadpicture3Response= $this->uploadImage($_FILES['pictureurl3'],$path,$file_name);
            $response = $uploadpicture3Response['status'];
            if($response == 'false') {
                
                return $this->renderAPIError($uploadpicture3Response['message'],'E09'); 
            }

            $pictureurl3 = $uploadpicture3Response['filename'];
       }


















        


        /*if(empty($customerweightticket))
        {
            
            return $this->renderAPIError('Weight Ticket Image cannot be empty','E06'); 
        }

        if(!$this->checkimage($customerweightticket)) {
            
            return $this->renderAPIError('Only allowed jpg,jpeg,png images','E09');  
        }

        if(empty($customersigneddo))
        {
            
            return $this->renderAPIError('Customer Signed DO Image cannot be empty','E07'); 
        }
        if(!$this->checkimage($customersigneddo)) {
            
            return $this->renderAPIError('Only allowed jpg,jpeg,png images','E10');  
        }*/
        if(empty($geolocation))
        {
            
            return $this->renderAPIError('Geolocation cannot be empty','E08'); 
        }



       /* if(empty($pictureurl1)) {
           
           return $this->renderAPIError('Picture url1 cannot be empty','E11'); 
        }*/
        /*if(!empty($pictureurl1) && !$this->is_url($pictureurl1)) {
            
            return $this->renderAPIError('Picture url1 is not a valid url','E12'); 
        }*/
        /*if(empty($pictureurl2)) {
           
           return $this->renderAPIError('Picture url2 cannot be empty','E13'); 
        }*/
        /*if(!empty($pictureurl2) &&!$this->is_url($pictureurl2)) {
            
            return $this->renderAPIError('Picture url2 is not a valid url','E14'); 
        }*/
        /*if(empty($pictureurl3)) {
           
           return $this->renderAPIError('Picture url3 cannot be empty','E15'); 
        }*/
        /*if(!empty($pictureurl3) &&!$this->is_url($pictureurl3)) {
            
            return $this->renderAPIError('Picture url3 is not a valid url','E16'); 
        }*/

        if(empty($weight)) {
            
            return $this->renderAPIError('Weight cannot be empty','E17'); 
        }

        $weight = json_decode($weight,true);
        if(!is_array($weight)) {
            
            return $this->renderAPIError('Weight is not in the correct format','E18');
        }

        

        /*//weight ticket image section
        $upload_path = 'web/upload/weight/';
        $appendphotofilename='weight_'.$shipment_id.'_'.time();
        $customerweightticket = $this->base64_to_jpeg($customerweightticket,$appendphotofilename,$upload_path);
        //weight ticket section ends

        //sign image image section
        $upload_path = 'web/upload/sign/';
        $appendphotofilename='sign_'.$shipment_id.'_'.time();
        $customersigneddo = $this->base64_to_jpeg($customersigneddo,$appendphotofilename,$upload_path);
        //sign image section ends*/

        $updateArr = [];
        $updateArr['customerweightticket'] = $customerweightticket ;
        $updateArr['customersigneddo']     = $customersigneddo ;
        $updateArr['geolocation']          = $geolocation;
        $updateArr['pictureurl1']          = $pictureurl1;
        $updateArr['pictureurl2']          = $pictureurl2;
        $updateArr['pictureurl3']          = $pictureurl3;
        $updateArr['delivery_status']      = '1';

       
        
        //update shipment 
        $response1 = $this->mdl->update_shipment($updateArr,$shipment_id);
        if(!$response1) {
            
            return $this->renderAPIError('Failed to update the shipment','E19');
        }



        $response2 = $this->saleslinemdl->update_sales($weight);
        if($response1 && $response2) {
            
            return $this->renderAPI([], 'Successfully updated ', 'true', 'S01', 'true', 200); 
        }

        return $this->renderAPIError('Something went wrong','E20');



        












    }



function checkimage($base64_string){

    $allowed = ['jpeg','jpg','png'];
    $imageInfo = explode(";base64,", $base64_string);
    $imgExt = str_replace('data:image/', '', $imageInfo[0]); 
    if(in_array($imgExt, $allowed)){
        
       return true;
    }
    return false;
    

}

function uploadImage($file,$path,$file_name){
  

   
        $file_tmp =$file['tmp_name'];
        $file_type=$file['type'];
        $file_ext=explode('/',$file_type);
        $file_ext = strtolower($file_ext[1]);
        $extensions= array("jpeg","jpg","png");
        $status = 'false';
        $message = "Something went wrong";
        $response = [];
        if(!in_array($file_ext,$extensions)) {
            
            $status  = 'false';
            $message = 'Only allowed jpg,jpeg,png images';
            return $response = ['status'=>$status,'message'=>$message];
        }
        
        if(move_uploaded_file($file_tmp,$path.$file_name.'.'.$file_ext))
        {
            $status = 'true';
            $message = '';
            return $response = ['status'=>$status,'message'=>$message,'filename'=>$file_name.'.'.$file_ext];
        }

        return $response = ['status'=>$status,'message'=>$message];



}







public function is_url($url){

       if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        } 
        return false;
}


function base64_to_jpeg($base64_string, $output_file,$upload_path) {

            //$upload_path='web/upload/profile/'; 
            $allowed = ['jpeg','jpg','png'];

            $imageInfo = explode(";base64,", $base64_string);
            $imgExt = str_replace('data:image/', '', $imageInfo[0]);      
            $image = str_replace(' ', '+', $imageInfo[1]);
            $imageName = $upload_path.$output_file.".".$imgExt;
            $ifp = fopen( $imageName, 'wb' ); 

            fwrite( $ifp, base64_decode( $image ) );
            fclose( $ifp );
            return $output_file.".".$imgExt;

}

  public function actionGetShipmentfromQr()
    {
        $input      = $_POST;
        $shipment_no  = issetGet($input,'id','');
        $userObj = Raise::$userObj;
        $userId = $userObj['id'];


        if(empty($shipment_no)){
            
            return $this->renderAPIError('Id cannot be empty','E21');
        }
        if(empty($userId)) {
            
            return $this->renderAPIError('Userid cannot be empty','E04'); 
        }

        $shipmentHeaderDetails = $this->mdl->GetShipmentfromQr($shipment_no);
        $shipment_id = !empty($shipmentHeaderDetails) ? $shipmentHeaderDetails['id'] : '';
        if(empty($shipmentHeaderDetails) || empty($shipment_id)){
            
            return $this->renderAPIError('Invalid shipment','E24'); 
        }

        
    
        $driver_id = $shipmentHeaderDetails['assigned_driver_id'];

        if($shipmentHeaderDetails['delivery_status']==1){

            return $this->renderAPIError('Shipment already Delivered','E22');
       }

        if($userId !=  $driver_id){
           return $this->renderAPIError('You have no Permission to view this Shipment','E23');
        }


        //$filter['delivery_status']    = '2';
        $filter['assigned_driver_id'] = $userId;
        $filter['id']                 = $shipment_id;

        $shipmentHeaderDetails = $this->mdl->getDetails($filter);
        $filter = [];
        $filter['delivery_status']          = '2';
        $filter['shipmentdate']             = date('Y-m-d');
        $filter['sales_shipment_header_id'] = $shipment_id;
        $shipmentSalesLineList = $this->saleslinemdl->getRecords($filter);
        if(empty($shipmentHeaderDetails)) {
            $shipmentSalesLineList = [];
        }

       

        $data = array('shipmentDetails' => $shipmentHeaderDetails,'salesLineList'=>$shipmentSalesLineList,'count'=>count($shipmentSalesLineList));
        
        return $this->renderAPI($data, 'Shipment Details', 'false', 'S01', 'true', 200); 


    }

  

    

  


}
