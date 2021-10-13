<?php

namespace src\controllers;

use inc\Controller;
use inc\Raise;
use src\lib\Router;
use src\lib\Helper;
use src\lib\Secure;
use src\lib\RRedis;
use src\lib\ValidatorFactory;
use src\models\User;
use src\models\Book;



class ProfileController extends Controller
{
    
    protected $needAuth = true;
    protected $authExclude = [];

    public function __construct()
    {
        parent::__construct();
        $this->usermdl = (new User);
    }

    public function actionIndex(){


        $input   = $_POST;
        $userObj = Raise::$userObj;
        $userId  = $userObj['id'];
        
        if(empty($userId)) {
            return $this->renderAPIError('Userid cannot be empty','');  
        }

       
        $userDetails = [];
        $userDetails = $this->usermdl->getUserDetails($userId);

        $data = [];
        $data['name']  = !empty($userDetails['fullname']) ? $userDetails['fullname'] : '';
        $data['about'] = !empty($userDetails['about']) ? $userDetails['about'] : '';
        $data['profile_pic'] = !empty($userDetails['profile_pic']) ? BASEURL.'web/upload/profile/'.$userDetails['profile_pic'] : '';
        
        $params = [];
        $params['user_id'] = $userId;
        $data['bookList']  = (new Book)->getList($params);
        
        return $this->renderAPI($data, 'Profile Data', 'false', 'S01', 'true', 200);


        

    }

    public function actionUpdateProfile(){


        $input   = $_POST;
        $userObj = Raise::$userObj;
        $userId  = $userObj['id'];
        $name    = issetGet($input,'name','');
        $about   = issetGet($input,'about','');
        if(empty($userId)) {
            return $this->renderAPIError('Userid cannot be empty','');  
        }
        if(empty($name)) {
            return $this->renderAPIError('Name cannot be empty','');  
        }
        
        $userDetails = $this->usermdl->getUserDetails($userId);
        $profile_pic = $userDetails['profile_pic'];
        if(!empty($_FILES['profile_pic'])) {
            
            $path           = 'web/upload/profile/';
            $file_name      = 'profile_'.$userId.'_'.time();
            $uploadResponse = $this->uploadImage($_FILES['profile_pic'],$path,$file_name); 
            $response = $uploadResponse['status'];
            if($response == 'false') {
                
                return $this->renderAPIError($uploadResponse['message'],''); 
            }
            $profile_pic = $uploadResponse['filename']; 

        }


        $params = [];
        $params['name']        = $name;
        $params['profile_pic'] = $profile_pic;
        $params['about']       = $about;
        $params['user_id']     = $userId;

        if($this->usermdl->updateProfile($params)){

            $userDetails = [];
            $userDetails = $this->usermdl->getUserDetails($userId);

            $data = [];
            $data['name']  = !empty($userDetails['fullname']) ? $userDetails['fullname'] : '';
            $data['about'] = !empty($userDetails['about']) ? $userDetails['about'] : '';
            $data['profile_pic'] = !empty($userDetails['profile_pic']) ? BASEURL.'web/upload/profile/'.$userDetails['profile_pic'] : '';
            
            $params = [];
            $params['user_id'] = $userId;
            $data['bookList']  = (new Book)->getList($params);
            
            return $this->renderAPI($data, 'Profile Data', 'false', 'S01', 'true', 200);    
        }else{

            return $this->renderAPIError('Failed to updated profile','');     
        }

        return $this->renderAPIError('Something went wrong','');  




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

   

   


 

  

    

  


}
