<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Coin;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */
class CoinController extends Controller {

    /**
     * 
     * @return Mixed
     */
    public function __construct(){

        $this->mdl        = (new Coin); 
        $this->pag =  new Pagination(new Coin(),''); 
        $this->adminId    = $_SESSION['INF_adminID'];
    }
    
    public function actionIndex() {

      $this->checkPageAccess(5);

         $status   			 	= $this->cleanMe(Router::post('status'));
		 $coin_name_search   	= $this->cleanMe(Router::post('coin_name_search'));
		 $wallet_group_search   = $this->cleanMe(Router::post('wallet_group_search'));
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 
        
         
         $filter=["status"   				=> $status,
				  "coin_name_search"    	=> $coin_name_search,
				  "wallet_group_search"   	=> $wallet_group_search,
                  "page"     				=> $page];

         $data=$this->mdl->getCoinList($filter); 
         
         $onclick = "onclick=pageHistory('".$status."','".$coin_name_search."','".$wallet_group_search."','***')";	
         $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
			
        return $this->render('coin/index',['status'=>$status,'coin_name_search'=>$coin_name_search,'wallet_group_search'=>$wallet_group_search,'data' => $data, 'pagination'=> $pagination]);
    }

    public function actionCreate() {

        //$LanguageArray=$this->mdl->getLanguageArray();
         
        return $this->render('coin/create');
    }

    public function actionAdd() {

        $title				  =cleanMe($_POST['title']);
        $coinId  			  = $this->cleanMe(Router::post('id'));
        $status   			  = $this->cleanMe(Router::post('status'));
		$coin_code   		  = $this->cleanMe(Router::post('coin_code'));
		$value    			  = $this->cleanMe(Router::post('value'));
		$transfer_out_value   = $this->cleanMe(Router::post('transfer_out_value'));
		$master_address		  = $this->cleanMe(Router::post('master_address'));
		$wallet_group		  = $this->cleanMe(Router::post('wallet_group'));

        
        if(empty($title)){
            $msg=Raise::t('coin','er01');
            $this->sendMessage("error",$msg); 
            die();
        }   
		if(empty($coinId) && empty($coin_code)){
			$msg=Raise::t('coin','er02');
            $this->sendMessage("error",$msg); 
            die();
		}
		if(empty($coinId) && empty($coin_code)){
			$msg=Raise::t('coin','er02');
            $this->sendMessage("error",$msg); 
            die();
		}
		if(empty($coinId) && !empty($coin_code)){
			$check_code = $this->mdl->checkCoinexist($coin_code);
			if(!empty($check_code)){
				$msg=Raise::t('coin','er07');
				$this->sendMessage("error",$msg); 
				die();
			}
		}
		if(empty($value)){
			$msg=Raise::t('coin','er03');
            $this->sendMessage("error",$msg); 
            die();
		}
		if(empty($transfer_out_value)){
			$msg=Raise::t('coin','er04');
            $this->sendMessage("error",$msg); 
            die();
		}
		if(empty($master_address)){
			$msg=Raise::t('coin','er08');
            $this->sendMessage("error",$msg); 
            die();
		}
		if(!is_numeric($value)){
			$msg=Raise::t('coin','er05');
            $this->sendMessage("error",$msg); 
            die();
		}
		if(!is_numeric($transfer_out_value)){
			$msg=Raise::t('coin','er06');
            $this->sendMessage("error",$msg); 
            die();
		}
			

        $data = array ('id'=>$coinId,'title'=>$title,'status'=>$status,'coin_code'=>$coin_code,'value'=>$value,'transfer_out_value'=>$transfer_out_value,'master_address'=>$master_address,'wallet_group'=>$wallet_group);
       
        if($coinId){
            $update = $this->mdl->updateCoin($data);
            return $this->sendMessage('success','Coin Updated Successfully');
        }else{
            $insert = $this->mdl->addCoin($data);
            return $this->sendMessage('success','Coin Added Successfully');
        }
    } 


    public function actiongetEdit(){

        $ID   = $this->cleanMe(Router::post('AnnId'));
        $data = $this->mdl->callsql("SELECT * FROM `coin` WHERE id='$ID' ","row");

        return  $this->renderJSON($data);
    }

    public function actionDelete(){
        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteCoin($ID);

        if($delete){
            return $this->sendMessage('success',"Coin Deleted");
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
