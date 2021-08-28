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
use src\models\Driver_user;
use src\models\UserTokenList;
use src\models\UserActivityLog;



class LoginController extends Controller
{
     protected $needAuth = false;
    protected $authExclude = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function actionTest(){
        $userObj = Raise::$userObj;
exit;
        echo "<pre>";
        print_r($userObj);
    }

    public function actionLogin()
    {
      
        
       // $input      = Router::postAll();
        $input      = $_POST;
        $vehicle_no  = issetGet($input,'vehicle_no','');
        $password  = issetGet($input,'password','');

        if(empty($vehicle_no)) {
            return $this->renderAPIError('Vehicle number cannot be empty','E01');  
        }

        if(empty($password)) {
            return $this->renderAPIError('Password cannot be empty','E02');
        }

        $driver = (new Driver_user)->checkLogin($vehicle_no,$password);



        if (empty($driver)) {
            return $this->renderAPIError('Invalid credentials','E03');
        } 




       // $user_info = (new UserInfo)->findByPK($user['id'])->convertArray();
       /* $driver_info = (new Driver_user)->getDetails($driver['id']);


        echo "<pre>";
        print_r($driver_info);exit;
*/


        $token = $this->generateToken($driver['id']);




        $userSystemInfo = Helper::getUserSystemInfo();




        $insert['user_id']          =  $driver['id'];             
        $insert['token']            =  $token;                   
        $insert['device_id']        =  $userSystemInfo['device_id'];          
        $insert['device_model']     =  $userSystemInfo['device_model'];        
        $insert['device_os']        =  $userSystemInfo['device_os'];          
        $insert['device_imei']      =  $userSystemInfo['device_imei'];      
        $insert['device_manufacturer']   =  $userSystemInfo['device_manufacturer'];
        $insert['device_appversion']     =  $userSystemInfo['device_appversion'];
        $insert['language']         =    $userSystemInfo['language'];         
        $insert['medium']           =    "1";            
        $insert['created_at']       =  time();         
        $insert['created_ip']       =    $userSystemInfo['ip'];       
        $insert['status']           =  '1';            
        $insert['last_seen']        =  time();      

        (new UserTokenList)->assignAttrs($insert)->save();




        $updateUser['last_login_time']        =  time();  
        $updateUser['last_login_ip']          =  $userSystemInfo['ip'];        
        $updateUser['last_login_os']          =  $userSystemInfo['device_os'];        
        $updateUser['last_login_device']      =  $userSystemInfo['device_model'];        
        $updateUser['id']      =  $driver['id'];        

        (new Driver_user)->assignAttrs($updateUser)->update();




        $ip['module']   = 'Login';
        $ip['action']   = 'login';
        $ip['activity'] = "User login";
        $ip['user_id']  = $driver['id'];
        (new UserActivityLog)->saveUserLog($ip);

       
        $redisKey = 'ut-'.$token;

        /*$redis = (new RRedis);

        $redisKey = 'ut-'.$token;

        if ($redis->exists($redisKey)){
            $redis->del($redisKey);
        }

        $redis->set($redisKey,$player_arr,7200);*/

        $data = array(
                    "id"=> (string)$driver['id'],
                    "driver_name"=> (string)$driver['driver_name'],
                    "status"=> "1",
                    "last_login_time"=> (string)$driver['last_login_time'],
                    "last_login_ip"=> (string)$driver['last_login_ip'],
                    "token"=> (string)$redisKey);
        
        return $this->renderAPI($data, 'Successfully logined', 'false', 'S01', 'true', 200);
    
    }

    public function generateToken($user_id)
    {

        (new UserTokenList)->expireUserToken($user_id);

        do {

            $token = (function_exists('mcrypt_create_iv')) ? bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)) : bin2hex(openssl_random_pseudo_bytes(32));

            $isTokenExist = (new UserTokenList)->isTokenExist($token);

        } while ($isTokenExist);

        return $token;
    }


    function checkEmail($email) {
         return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? false : true;
    }


}
