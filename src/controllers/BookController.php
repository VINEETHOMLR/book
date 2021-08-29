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
        return $this->renderAPI($data, 'Book Details', 'false', 'S01', 'true', 200);

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
