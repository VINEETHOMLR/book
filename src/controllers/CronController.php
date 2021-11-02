<?php

namespace src\controllers;
//namespace Sample;
use inc\Controller;
use inc\Raise;
use src\lib\Router;
use src\lib\Helper;
use src\lib\Secure;
use src\lib\RRedis;
use src\lib\ValidatorFactory;
use src\models\User;
use src\lib\mailer\Mailer;

class CronController extends Controller
{
    protected $needAuth = false;
    protected $authExclude = [];

    public function __construct()
    {
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
    }


    public function actionPayout()
    {

        $model          = new User();
        $payoutDetails  = $model->callsql("SELECT *  from payout WHERE status=1 ",'row'); //take running payout 

        if(!empty($payoutDetails)) { //do the steps required for payout


            $payout_id = $payoutDetails['id'];
            
            //insert new payout entry starts
            /*$starttime = date('Y-m-d 00:00:00');
            $endtime   = date('Y-m-d 23:59:59',strtotime("+7 day", strtotime($starttime)));
            $starttime = strtotime($starttime);
            $endtime   = strtotime($endtime);

            $sql   = "INSERT INTO payout SET start_time='$starttime',end_time='$endtime',status='1'";
            $model->query($sql);
            $model->execute();*/

            //insert new payout entry ends


            //change the status of cuurent running payout
            /*$sql   = "UPDATE payout SET status=2 WHERE id=$payout_id";
            $model->query($sql);
            $model->execute();*/


            $total_amount = !empty($payoutDetails['amount']) ? $payoutDetails['amount'] : '0';
            if(!empty($total_amount)) { //do the payout steps

                
                $start_time              = $payoutDetails['start_time'];
                $end_time                = $payoutDetails['end_time'];
                //$total_click_entire_app  = $model->callsql("SELECT count(DISTINCT(book_id)) from click_log WHERE created_at BETWEEN '$start_time' AND '$end_time'",'rows');

                

               

                $user_wise_click          = $model->callsql("SELECT count(DISTINCT(book_id)) as click_count,beneficiery_id FROM `click_log` WHERE created_at BETWEEN '$start_time' AND '$end_time'group by beneficiery_id ",'rows');

                $total_click_entire_app = '0';                 
                if(!empty($user_wise_click)) {

                    foreach($user_wise_click as $k=>$v){
                        
                        $total_click_entire_app += $v['click_count'];

                    }
                }

                

                if(!empty($user_wise_click)) {

                   
                    
                    foreach($user_wise_click as $k=>$v){

                        $user_revenue = ($v['click_count']/$total_click_entire_app)*$total_amount; //amount calculation

                        $user_revenue = number_format($user_revenue,2);

                        $params = [];
                        /*$params['user_id']            = $v['beneficiery_id'];
                        $params['total_amount']       = $total_amount;
                        $params['entire_app_click']   = $total_click_entire_app;
                        $params['total_user_click']   = $v['click_count'];
                        $params['amount_transfered']  = $user_revenue;
                        $params['status']             = 1;
                        $params['payout_id']          = $payoutDetails['id'];*/

                        $sql   = "INSERT INTO transaction SET user_id=$v[beneficiery_id],total_amount='$total_amount',entire_app_click='$total_click_entire_app',total_user_click=$v[click_count],amount_transfered='$user_revenue',status='1',payout_id=$payoutDetails[id]";

                        
                        $model->query($sql);
                        $model->execute();

                        $transaction_id  = $model->lastInsertId();
                        $this->sendMoney($transaction_id,$user_revenue);

                    }
                }
                



                



            }


            exit;


            //change the status of the current calculating payout 
            $sql   = "UPDATE payout SET status=3 WHERE id=$payout_id";
            $model->query($sql);
            $model->execute();





            

        


        }else{

            
            $starttime = date('Y-m-d 00:00:00');
            $endtime   = date('Y-m-d 23:59:59',strtotime("+7 day", strtotime($starttime)));
            $starttime = strtotime($starttime);
            $endtime   = strtotime($endtime);

            $sql   = "INSERT INTO payout SET start_time='$starttime',end_time='$endtime',status='1'";
            $model->query($sql);
            $model->execute();


        }


        echo "<pre>";

    }


    public function sendMoney($transaction_id,$amount)
    {
        

        $model               = new User();
        $transactionDetails  =  $model->callsql("SELECT *  from transaction WHERE id=$transaction_id ",'row');
        $userDetails         =  $model->callsql("SELECT *  from user WHERE id=$transactionDetails[user_id] ",'row');
        


        $sender_batch_id  = mt_rand(100000000000000,999999999999999);
        $sender_item_id   = mt_rand(100000000000000,999999999999999);

        $PAYPAL_CLIENT_ID = 'AcsrvEWcV_6BMpLI-RzgVV1DitWS68VgvT2kYxrSJUnVy7wS9iQrKL901gJ9COpQScfzYxH2AcLKWo0F';
        $PAYPAL_SECRET    = 'EIXbd2ZM9k9vVQCNWr-22QZ3tJ5yDXG5KTvu2jjNE5XfMd2w3mHvCUs7_OAKgdgJIFKO2pH7fymp2UxS';


        $token_request = array(
            CURLOPT_URL => "https://api.sandbox.paypal.com/v1/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_USERPWD => $PAYPAL_CLIENT_ID.":".$PAYPAL_SECRET,
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Accept-Language: en_US"
            ),
        );

        $token_request = json_encode($token_request);
        $type          = '1';
        $request       = $token_request;
        $request_time  = time();
        $request_ip    = $_SERVER['REMOTE_ADDR'];


        $sql   = "INSERT INTO payout_api_log SET transaction_id='$transaction_id',type='$type',request='$request',request_time='$request_time',request_ip='$request_ip'";
        $model->query($sql);
        $model->execute();
        $log_id  = $model->lastInsertId();



        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sandbox.paypal.com/v1/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_USERPWD => $PAYPAL_CLIENT_ID.":".$PAYPAL_SECRET,
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Accept-Language: en_US"
            ),
        ));

        $result= curl_exec($curl);
        $array=json_decode($result, true);


        $response_time = time();
        $response_ip   = $_SERVER['REMOTE_ADDR'];
        $sql   = "UPDATE payout_api_log SET response='$result',response_time='$response_time',response_ip='$response_ip' WHERE id=$log_id";
        $model->query($sql);
        $model->execute();

        $access_token = !empty($array['access_token']) ? $array['access_token'] : '';
        
        if(!empty($access_token)) {

        }




        




    }

    public function actionVin(){


       $sender_batch_id = mt_rand(100000000000000,999999999999999);
$sender_item_id = mt_rand(100000000000000,999999999999999);


$PAYPAL_CLIENT_ID = 'AcsrvEWcV_6BMpLI-RzgVV1DitWS68VgvT2kYxrSJUnVy7wS9iQrKL901gJ9COpQScfzYxH2AcLKWo0F';
$PAYPAL_SECRET = 'EIXbd2ZM9k9vVQCNWr-22QZ3tJ5yDXG5KTvu2jjNE5XfMd2w3mHvCUs7_OAKgdgJIFKO2pH7fymp2UxS';

$curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sandbox.paypal.com/v1/oauth2/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_USERPWD => $PAYPAL_CLIENT_ID.":".$PAYPAL_SECRET,
    CURLOPT_POSTFIELDS => "grant_type=client_credentials",
    CURLOPT_HTTPHEADER => array(
    "Accept: application/json",
    "Accept-Language: en_US"
    ),
    ));

    $result= curl_exec($curl);

    $array=json_decode($result, true);




//$access_token = 'A21AAGpxXlEt3iIG7fKiriFDdLwIW_JJvpOa9IwVb8XXJbeVjL9MkHvmYWbWkHgVReeiuEQaYZTbi6xSWbXGMlLaabJPwkdYg';  //Dummy
echo $access_token = $array['access_token'];  //Dummy


/*

$ch = curl_init();

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/payments/payouts?sync_mode=false");    //DUMMY

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"sender_batch_header\":{\"sender_batch_id\":\"$sender_batch_id\",\"email_subject\":\"You have a payout!\",\"recipient_type\":\"EMAIL\"},\"items\":[{\"recipient_type\":\"EMAIL\",\"amount\":{\"value\":\"3.3\",\"currency\":\"USD\"},\"note\":\"Thanks for your patronage!\",\"sender_item_id\":\"$sender_item_id\",\"receiver\":\"sb-j6axr8352514@personal.example.com\" }]}");  //DUMMY


curl_setopt($ch, CURLOPT_POST, 1);

$headers = array();
$headers[] = "Content-Type: application/json";
$headers[] = "Authorization: Bearer $access_token";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
print_r(json_decode($result));
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);

exit;*/


$ch = curl_init();

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/payments/payouts/3DED57YS94L32");    //DUMMY

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"sender_batch_header\":{\"sender_batch_id\":\"$sender_batch_id\",\"email_subject\":\"You have a payout!\",\"recipient_type\":\"EMAIL\"},\"items\":[{\"recipient_type\":\"EMAIL\",\"amount\":{\"value\":\"1.0\",\"currency\":\"USD\"},\"note\":\"Thanks for your patronage!\",\"sender_item_id\":\"$sender_item_id\",\"receiver\":\"buy1911@gmail.com\" }]}");  //DUMMY


curl_setopt($ch, CURLOPT_POST, 0);

$headers = array();
$headers[] = "Content-Type: application/json";
$headers[] = "Authorization: Bearer $access_token";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
print_r(json_decode($result));

curl_close ($ch);

//https://api.sandbox.paypal.com/v1/payments/payouts/LFAVDTTPVGHS8

    }

    public function actionTest321(){



        echo "hai";

        $clientId = "123";
        $clientSecret = "321";

        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $client = new PayPalHttpClient($environment);
        $request = new PayoutsPostRequest();


        $body= json_decode(
            '{
                "sender_batch_header":
                {
                  "email_subject": "SDK payouts test txn"
                },
                "items": [
                {
                  "recipient_type": "EMAIL",
                  "receiver": "payouts2342@paypal.com",
                  "note": "Your 1$ payout",
                  "sender_item_id": "Test_txn_12",
                  "amount":
                  {
                    "currency": "USD",
                    "value": "1.00"
                  }
                }]
              }',             
            true);

echo "<pre>";
        print_r($body);


$request->body = $body;

require $_SERVER['DOCUMENT_ROOT'].'/book/vendor/paypal/paypal-payouts-sdk/samples/PayPalClient.php';

$test = new PayPalClient();

print_r($test);exit;

$client = $test->client();
$response = $client->execute($request);

echo "<pre>";
print_r($response);exit;

        exit;
    }

    public function actionLogin()
    {
      
        
       // $input      = Router::postAll();
        $input      = $_POST;
        $email  = issetGet($input,'email','');
        $password  = issetGet($input,'password','');

        if(empty($email)) {
            return $this->renderAPIError('Email cannot be empty','');  
        }

        if(empty($password)) {
            return $this->renderAPIError('Password cannot be empty','');
        }

        $userDetails = (new User)->checkLogin($email,$password);




        if (empty($userDetails)) {
            return $this->renderAPIError('Invalid credentials','');
        } 




       // $user_info = (new UserInfo)->findByPK($user['id'])->convertArray();
       /* $driver_info = (new Driver_user)->getDetails($driver['id']);


        echo "<pre>";
        print_r($driver_info);exit;
*/


        $token = $this->generateToken($userDetails['id']);





        $userSystemInfo = Helper::getUserSystemInfo();




        $insert['user_id']               =  $userDetails['id'];             
        $insert['token']                 =  $token;                   
        $insert['device_id']             =  $userSystemInfo['device_id'];          
        $insert['device_model']          =  $userSystemInfo['device_model'];        
        $insert['device_os']             =  $userSystemInfo['device_os'];          
        $insert['device_imei']           =  $userSystemInfo['device_imei'];      
        $insert['device_manufacturer']   =  $userSystemInfo['device_manufacturer'];
        $insert['device_appversion']     =  $userSystemInfo['device_appversion'];
        $insert['language']              =    $userSystemInfo['language'];         
        $insert['medium']                =    "1";            
        $insert['created_at']            =  time();         
        $insert['created_ip']            =    $userSystemInfo['ip'];       
        $insert['status']                =  '1';            
        $insert['last_seen']             =  time();      

        (new UserTokenList)->assignAttrs($insert)->save();




        $updateUser['last_login_time']        =  time();  
        $updateUser['last_login_ip']          =  $userSystemInfo['ip'];        
        $updateUser['last_login_os']          =  $userSystemInfo['device_os'];        
        $updateUser['last_login_device']      =  $userSystemInfo['device_model'];        
        $updateUser['id']      =  $userDetails['id'];        

        (new User)->assignAttrs($updateUser)->update();




        $ip['module']   = 'Login';
        $ip['action']   = 'login';
        $ip['activity'] = "User login";
        $ip['user_id']  = $userDetails['id'];
        (new UserActivityLog)->saveUserLog($ip);

       
        $redisKey = 'ut-'.$token;

        /*$redis = (new RRedis);

        $redisKey = 'ut-'.$token;

        if ($redis->exists($redisKey)){
            $redis->del($redisKey);
        }

        $redis->set($redisKey,$player_arr,7200);*/

        $data = array(
                    "id"=> (string)$userDetails['id'],
                    "name"=> (string)$userDetails['fullname'],
                    "status"=> "1",
                    "last_login_time"=> (string)$userDetails['last_login_time'],
                    "last_login_ip"=> (string)$userDetails['last_login_ip'],
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


    public function actionresetPassword()
    {

        $input  = $_POST;    
        $email  = issetGet($input,'email','');

        if(empty($email)) {
            return $this->renderAPIError('Email cannot be empty','');  
        }
       
        $checkemail = (new User)->callsql("SELECT fullname FROM user WHERE username='$email'","value");

        if(empty($checkemail)) {

            return $this->renderAPIError('Email does not Exist',''); 

        } else {
            

            $rand_pass = rand();
            $pass      = md5($rand_pass);
            $this->mdl->query("UPDATE `user` SET `password`='$pass' WHERE username='$email'");
            $this->mdl->execute();
            $title = 'Reset Password';
            $subject = 'Reset Password';
            $message = 'Hi '.$checkemail.',Successfully submited the password reset request.This is your new password '.$rand_pass.'.Thank you.';
            $this->sendMail($email,$title,$subject,$message);
    
            return $this->renderAPI([], 'Password sent to Mail', 'true', '', 'true', 200);

        }
        return $this->renderAPIError('Something went wrong','');
    }

    public function sendMail($email,$title,$subject,$message)
    {

        $mail = new Mailer();
        $send = $mail->send($email,$title,$subject,$message);


        if ($send) {
            return true;
        }else{
            return false;
        }

    }


}
