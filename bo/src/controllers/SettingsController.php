<?php
namespace src\controllers;

use inc\Controller;
use src\models\Settings;
use src\lib\Router;
use src\lib\Pagination; 
use inc\Raise;

class SettingsController extends Controller {
    
   public function __construct(){

        $this->mdl   = (new Settings);
        $this->adminId = $_SESSION['INF_adminID'];
        
        
    }

    public function actionIndex(){
		//die('here');
		$data = $this->mdl->getSiteData('withdrawal_fee');
		$data = json_decode($data,true);
		$btc_masteraddress = $this->mdl->getSiteData('btc_masteraddress');
		$eth_masteraddress = $this->mdl->getSiteData('eth_masteraddress');
        return $this->render('settings/index',['btc_val'=>$data['btc'],'usdt_val'=>$data['usdt'],'eth_val'=>$data['eth'],'btc_masteraddress'=>$btc_masteraddress,'eth_masteraddress'=>$eth_masteraddress]);
    }
    public function actionserviceIndex()
    {
        $data = $this->mdl->getSiteKeys();
        //$data = json_decode($data,true);
        return $this->render('settings/serviceindex',['key'=>$data]);                    
    }
    
    public function isCheck($var,$coin){
		if((!(is_float($var) || is_numeric($var))) || ($var < 0))
		{
			$this->sendMessage("error",strtoupper($coin)." should be a valid value");
			die();
		}
    }
     public function actionUpdate(){

        $btc= $this->cleanMe(Router::post('btc'));
        $usdt= $this->cleanMe(Router::post('usdt'));
        $eth = $this->cleanMe(Router::post('eth'));
        
        
        $this->isCheck($btc,'btc');
        $this->isCheck($usdt,'usdt');
        $this->isCheck($eth,'eth');
        
        $update = $this->mdl->updateSettings($btc,$usdt,$eth);
        if($update)
                return $this->sendMessage('success',"Withdrawal Fee Updated Successfully");
        else
                return $this->sendMessage('error',"Something went Wrong");
        
        
	}

	public function actionUpdateAddress(){
		
		$btc= $this->cleanMe(Router::post('btc_master'));
		$eth= $this->cleanMe(Router::post('eth_master'));
		$update = $this->mdl->updateAddress($btc,$eth);
		if($update)
                return $this->sendMessage('success',"Master Address Updated Successfully");
        else
                return $this->sendMessage('error',"Something went Wrong");
	}
    public function actionswitchStatus(){

        $keyvalue= $_POST['value'];
        $update = $this->mdl->SwitchStatus($keyvalue);
        if($update['result'])
                return $this->sendMessage('success',$update['message']);
        else
                return $this->sendMessage('error',"Something went Wrong");
    }

}

