<?php

namespace src\controllers;

use inc\Controller;
use src\models\Home;
use src\models\Category;

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

        $input        = $_POST; 
        $search       = issetGet($input,'search_keyword','');
        $bookDetail   = $this->mdl->getTrending($search);
        $categoryList = $this->categorymdl->getList();

        $data         = ['bookDetail'=>$bookDetail, 'categoryList'=>$categoryList];
        return $this->renderAPI($data, 'Trending Books and Category List', 'false', '', 'true', 200);

    }

}
