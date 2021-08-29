<?php

namespace src\controllers;

use inc\Controller;
use inc\Raise;
use src\lib\Router;
use src\lib\Helper;
use src\lib\Secure;
use src\lib\RRedis;
use src\lib\ValidatorFactory;
use src\models\Category;




class RegisterController extends Controller
{
    
    protected $needAuth = false;
    protected $authExclude = [];

    public function __construct()
    {
        parent::__construct();
        $this->categorymdl = (new Category);
    }

    public function actionIndex(){
        
    }

    public function actionGetCategoryList(){

        $categoryList = $this->categorymdl->getList();
        $data         = ['categoryList'=>$categoryList];
        return $this->renderAPI($data, 'Category List', 'false', '', 'true', 200); 


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

 

  

    

  


}
