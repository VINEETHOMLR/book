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
use src\models\Category;
use src\models\ClickCount;




class BookController extends Controller
{
    
    protected $needAuth = true;
    protected $authExclude = [];

    public function __construct()
    {
        parent::__construct();
        $this->usermdl = (new User);
    }

    public function actionBookList(){


        $input   = $_POST;
        $userObj = Raise::$userObj;
        $userId  = $userObj['id'];
        $category_id    = issetGet($input,'category_id','');
        if(empty($userId)) {
            return $this->renderAPIError('Userid cannot be empty','');  
        }
        if(empty($category_id)) {
            return $this->renderAPIError('Please select a category','');  
        }

        $params = [];
        $params['category_id'] = $category_id;

        $bookList       = (new Book)->getList($params);
        $category       = (new Category)->findByPK($category_id);
        $category_name  = !empty($category->name) ? $category->name : '';

        $data = [];
        $data['bookList'] = $bookList;
        $data['category'] = $category_name;

        
        return $this->renderAPI($data, 'Book List', 'false', 'S01', 'true', 200);


        

    }

    public function actionBookDetails(){

        
        $input   = $_POST;
        $userObj = Raise::$userObj;
        $userId  = $userObj['id'];
        $book_id    = issetGet($input,'book_id','');
        if(empty($userId)) {
            return $this->renderAPIError('Userid cannot be empty','');  
        }
        if(empty($book_id)) {
            return $this->renderAPIError('Please select a book ','');  
        }

        $bookDetails       = (new Book)->getDetails($book_id);
        $data = [];
        $data['bookDetails'] = $bookDetails;
        return $this->renderAPI($data, 'Book Details', 'false', 'S01', 'true', 200);



    }


    public function actionReadBook()
    {

        $input   = $_POST;
        $userObj = Raise::$userObj;
        $userId  = $userObj['id'];
        $book_id    = issetGet($input,'book_id','');
        if(empty($userId)) {
            return $this->renderAPIError('Userid cannot be empty','');  
        }
        if(empty($book_id)) {
            return $this->renderAPIError('Please select a book ','');  
        }
        $bookDetails       = (new Book)->getDetails($book_id);
        $data = [];
        $data['bookDetails'] = $bookDetails;
        if(!empty($bookDetails) && $bookDetails['author_id']!=$userId) {
            
            $this->updateCount($book_id,$userId);
        }
        return $this->renderAPI($data, 'Book Details', 'false', 'S01', 'true', 200);

    }


    public function actionUploadBook(){

        $input          = $_POST;
        $userObj        = Raise::$userObj;
        $userId         = $userObj['id']; 
        $title          = issetGet($input,'title','');
        $category_id    = issetGet($input,'category_id','');
        $synopsis       = issetGet($input,'synopsis','');

        $log = ['user_id'=>$userId,'request'=>json_encode($_FILES)];
        $request = json_encode($_FILES);
       


        $this->usermdl->query(" INSERT INTO `api_log` SET `user_id`='$userId',`request`='$request'");
         
            
            $this->usermdl->execute();
        
        if(empty($userId)) {

            return $this->renderAPIError('Userid cannot be empty','');  
        } 
        if(empty($title)) {

            return $this->renderAPIError('Title cannot be empty','');  
        } 
        if(empty($category_id)) {

            return $this->renderAPIError('Please select category to proceed','');  
        }
        if(empty($synopsis)) {

            return $this->renderAPIError('Please enter synopsis to proceed','');  
        }
        $cover_image = "";
        if(empty($_FILES['cover_image'])){

            return $this->renderAPIError('Please upload cover image to proceed','');      
        }
        if(!empty($_FILES['cover_image'])) {

            $title = trim($title); // Trims both ends
            $title = str_replace(' ', '_', $title);
            
            $path           = 'web/upload/cover/';
            $file_name      = 'cover_'.$title.$userId.'_'.time();
            $uploadResponse = $this->uploadImage($_FILES['cover_image'],$path,$file_name); 
            $response = $uploadResponse['status'];
            if($response == 'false') {
                
                return $this->renderAPIError($uploadResponse['message'],''); 
            }
            $cover_image = $uploadResponse['filename']; 

        } 
        if(empty($_FILES['pdf_file'])){

            return $this->renderAPIError('Please upload pdf file  to proceed','');      
        }
        $pdf_file = '';
        if(!empty($_FILES['pdf_file'])) {

            $title = trim($title); // Trims both ends
            $title = str_replace(' ', '_', $title);
            
            $path           = 'web/upload/pdf/';
            $file_name      = 'book_'.$title.$userId.'_'.time();
            $uploadResponse = $this->uploadPdf($_FILES['pdf_file'],$path,$file_name); 
            $response = $uploadResponse['status'];
            if($response == 'false') {
                
                return $this->renderAPIError($uploadResponse['message'],''); 
            }
            $pdf_file = $uploadResponse['filename']; 

        }

        $data = [];
        $data['user_id']     = $userId;
        $data['title']       = $title;
        $data['category_id'] = $category_id;
        $data['synopsis']    = $synopsis;
        $data['cover_photo'] = $cover_image;
        $data['pdf_file']    = $pdf_file;

        if((new Book)->insertBook($data)){
            
            return $this->renderAPI([], 'Successfully uploaded the book', 'false', 'S01', 'true', 200);

        }else{
            
            return $this->renderAPI([], 'Failed to upload the book', 'false', 'E01', 'false', 200);
        }

        return $this->renderAPI([], 'Something went wrong', 'false', 'E01', 'false', 200);



        


    }

    public function actionAboutauthor(){

        $input          = $_POST;
        $userObj        = Raise::$userObj;
        $userId         = $userObj['id']; 
        $author_id          = issetGet($input,'author_id',''); 
        if(empty($userId)) {

            return $this->renderAPIError('Userid cannot be empty','');  
        } 
        if(empty($author_id)) {

            return $this->renderAPIError('Please select author to proceed','');  
        }  


        $userDetails = [];
        $userDetails = $this->usermdl->getUserDetails($userId);
        if(empty($userDetails)) {
            return $this->renderAPI($data, 'Invalid author', 'false', 'S01', 'false', 200);
        }

        $data = [];
        $data['name']  = !empty($userDetails['fullname']) ? $userDetails['fullname'] : '';
        $data['about'] = !empty($userDetails['about']) ? $userDetails['about'] : '';
        $data['profile_pic'] = !empty($userDetails['profile_pic']) ? BASEURL.'web/upload/profile/'.$userDetails['profile_pic'] : '';
        
      
        
        return $this->renderAPI($data, 'Author Data', 'false', 'S01', 'true', 200); 


    }

    function updateCount($book_id,$userId){
        
        $alreadyClicked = (new ClickCount)->checkAlreadyClicked($book_id,$userId);
        if(empty($alreadyClicked)) {
            
            (new ClickCount)->updateCount($book_id,$userId);
        }
    

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



function uploadPdf($file,$path,$file_name){
  

   
        $file_tmp =$file['tmp_name'];
        $file_type=$file['type'];
        $file_ext=explode('/',$file_type);
        $file_ext = strtolower($file_ext[1]);
        $extensions= array("PDF","pdf");
        $status = 'false';
        $message = "Something went wrong";
        $response = [];
        if(!in_array($file_ext,$extensions)) {
            
            $status  = 'false';
            $message = 'Only allowed pdf file';
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
