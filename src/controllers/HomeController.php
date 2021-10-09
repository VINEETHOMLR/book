<?php

namespace src\controllers;

use inc\Controller;
use inc\Raise;
use src\lib\Router;
use src\lib\Helper;
use src\lib\Secure;
use src\lib\RRedis;
use src\lib\ValidatorFactory;
use src\models\Home;
use src\models\Category;
use src\models\UserTokenList;

class HomeController extends Controller
{
    
    protected $needAuth = true;
    protected $authExclude = [];

    public function __construct()
    {
        parent::__construct();
        $this->mdl         = (new Home);
        $this->categorymdl = (new Category);
    }


     public function actionGetHome(){

        $input         = $_POST; 
        $search        = issetGet($input,'search_keyword','');
        $userObj        = Raise::$userObj;
        $userId         = $userObj['id']; 
        if(empty($userId)) {

            return $this->renderAPIError('Userid cannot be empty','');  
        }
        $bookDetail    = $this->mdl->getTrending($search);
        $categoryList  = $this->categorymdl->getList();
        $search_status = empty($search) ? 'false':'true';
        $data          = ['bookDetail'=>$bookDetail, 'categoryList'=>$categoryList,'search'=>$search_status];
        return $this->renderAPI($data, 'Trending Books and Category List', 'false', '', 'true', 200);

    }

    public function actionLogout(){

        $input         = $_POST;  
        $userObj        = Raise::$userObj;
        $userId         = $userObj['id']; 
        if(empty($userId)) {

            return $this->renderAPIError('Userid cannot be empty','');  
        }  

        if((new UserTokenList)->expireUserToken($userId)){
            
            return $this->renderAPI([], 'Successfully logout', 'true', '', 'true', 200);

        } else{
            
            return $this->renderAPI([], 'Failed to logout', 'true', '', 'false', 200);
        }

        return $this->renderAPIError('Something went wrong','');  

    }

}
