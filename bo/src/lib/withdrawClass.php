<?php

namespace src\lib;

use src\lib\Helper;
use inc\Raise;
use src\models\ApiCallLog;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class withdrawClass
{
    public function __construct()
    {

        $this->api_log = new ApiCallLog();

        if(apiMedium == "live"){

            $this->apiUser   = "990002";
            $this->auth_code = 'KdXT_Wk2q';
            $this->apiurl    = 'https://fin.infinite-market.io/api';

        }else{

            $this->apiUser   = "990002";
            $this->auth_code = 'CgAny7_Dqm';
            $this->apiurl    = 'https://fin.sgcomp-tech.com/api'; 
        }
        
        //$this->apiToken = $this->authentication();

    }

    public function authentication(){
        
        $cmd            = 'auth.login';

        $end_api_url    = 'auth';
        
        $request        =  array(
                                    'cmd' => $cmd,
                                    'user_id'=>$this->apiUser,
                                    'auth_code'=>$this->auth_code
                            );  

        $req_id         = $this->api_log->insertLog(1, 0, 12, json_encode($request));

        $response       = $this->callCurl($request,$end_api_url);

        $this->api_log->updateLog($req_id, $response, $response);

        $decoded_response = json_decode($response,true);

        $error          = $decoded_response['error'];

        if(is_array($error)){ // login token failed 
            
            return false;
        }   

        $result         = $decoded_response['result'];

        return  $result['api_token']; 
    }


    public function withdraw($userID,$coin_id,$coin_code,$toAddress,$amount,$transID)
    {
        $cmd         = 'withdraw';

        $end_api_url = 'account';

        $token = $this->authentication();

        if(empty($token)){
            $error  = ['status' => 'error','message'=> 'API Token Error'];
            return json_encode($error);
        }

        $request  = array("cmd"=>$cmd,"user_id"=>$this->apiUser,"asset"=>$coin_code,"to"=>$toAddress,"amount"=>$amount,"ref"=>$transID,"api_token"=>$token);

        $req_id = $this->api_log->insertLog($coin_id,$userID, 12, json_encode($request)); 

        $response = $this->callCurl($request,$end_api_url);

        $res_decode = json_decode($response, true);

        if ($res_decode['error'] == null) {

            $array = array('status' => 'success', 'message' => $res_decode['result']);
        } else {
            $array = array('status' => 'error', 'message' => 'API Error -'.$res_decode['error']['message']);
        }

        $this->api_log->updateLog($req_id, $response, json_encode($array));

        return json_encode($array);
    }

    public function withdrawStatus($withID,$userID,$coin_id)
    {
        $cmd         = 'withdraw.query';

        $end_api_url = 'account';

        $token = $this->authentication();

        if(empty($token)){
            $error  = ['status' => 'error','message'=> 'API Token Error'];
            return json_encode($error);
        }

        $request  = array("cmd"=>$cmd,"user_id"=>$this->apiUser,"id"=>$withID,"api_token"=>$token);

        $req_id = $this->api_log->insertLog($coin_id,$userID, 12, json_encode($request));

        $response = $this->callCurl($request,$end_api_url);

        $res_decode = json_decode($response, true);

        if ($res_decode['error'] == null) {

            $array = array('status' => 'success', 'message' => $res_decode['result']);
        } else {
            $array = array('status' => 'error', 'message' => 'API Error -'.$res_decode['error']['message']);
        }

        $this->api_log->updateLog($req_id, $response, json_encode($array));

        return json_encode($array);
    }



    public function callCurl($request,$urlParam)
    {
        $url = $this->apiurl .'/'.$urlParam;
    
        $ch         = curl_init($url);
        $request    = http_build_query($request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); //Timeout
        $return = curl_exec($ch);
       
        return  $return;
        
    }
}
