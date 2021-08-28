<?php

error_reporting(E_ALL);    
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Singapore');


$BASEURL = 'http://localhost/infinite_app/bo/';
define('BASEPATH', $_SERVER['DOCUMENT_ROOT'] . '/infinite_app/bo/');
define('FILEUPLOADPATH', $_SERVER['DOCUMENT_ROOT'] . '/infinite_app/bo/web/upload/');
define('PROFILEPICUPLOAD', $_SERVER['DOCUMENT_ROOT'] . '/infinite_app/web/upload/');
define('BASEURL', $BASEURL);

$FrontEnd = 'http://localhost/infinite_app/';
 define('FrontEnd', $FrontEnd);

define('WEB_PATH', $BASEURL . 'web/');
define('IMG_PATH', WEB_PATH . 'img/');
define('VIEW_PATH', BASEPATH);

$raiseParams = [];
$fileName = BASEPATH . '/inc/params.php';
if (file_exists($fileName)) {
    $raiseParams = include_once $fileName;
}

$file_Name = 'Country.php'; $countryArray=[];
if (file_exists(BASEPATH . 'inc/' . $file_Name)) {
    $countryArray = include_once $file_Name;
}

$file_Name = 'ServiceArray.php'; 
if (file_exists(BASEPATH . 'inc/' . $file_Name)) {
   include_once $file_Name; 
}

$file_Name = 'transactionArray.php'; $transactionArray=[];
if (file_exists(BASEPATH . 'inc/' . $file_Name)) {
    include_once $file_Name;
}

/**
 * 
 * @param String $input
 * @return Mixed
 */
function cleanMe($input) {
    $inputStiped = stripslashes(trim($input));
    $inputHtml = htmlspecialchars($inputStiped, ENT_IGNORE, 'utf-8');
    return strip_tags($inputHtml);
}


spl_autoload_register(function($class) {
    $exactClass = explode('\\', $class);
    //List of files to be skipped
    $vendorInc = ['Aws' => '/vendor/aws/aws-autoloader.php', 'JmesPath' => '/vendor/aws/aws-autoloader.php',
        'Psr' => '/vendor/aws/aws-autoloader.php', 'GuzzleHttp' => '/vendor/aws/aws-autoloader.php'];
    if (in_array(end($exactClass), ['PDO'])) {
        return;
    }
    if (!file_exists(str_replace('\\', '/', $class) . '.php')) {
        if (isset($vendorInc[$exactClass[0]])) {
            @require_once $vendorInc[$exactClass[0]];
        } else {
            throw new Exception($class . " Route Not Found", 404);
        }
    } else {
        include_once str_replace('\\', '/', $class) . '.php';
    }
});
//HEX2RGB 
if (!function_exists('hex2rgb')) {

    function hex2rgb($hex_str, $return_string = false, $separator = ',') {
        $hex_str = preg_replace("/[^0-9A-Fa-f]/", '', $hex_str); // Gets a proper hex string
        $rgb_array = array();
        if (strlen($hex_str) == 6) {
            $color_val = hexdec($hex_str);
            $rgb_array['r'] = 0xFF & ($color_val >> 0x10);
            $rgb_array['g'] = 0xFF & ($color_val >> 0x8);
            $rgb_array['b'] = 0xFF & $color_val;
        } elseif (strlen($hex_str) == 3) {
            $rgb_array['r'] = hexdec(str_repeat(substr($hex_str, 0, 1), 2));
            $rgb_array['g'] = hexdec(str_repeat(substr($hex_str, 1, 1), 2));
            $rgb_array['b'] = hexdec(str_repeat(substr($hex_str, 2, 1), 2));
        } else {
            return false;
        }
        return $return_string ? implode($separator, $rgb_array) : $rgb_array;
    }

}

function isMobi() {
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (preg_match("/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent)) {
        // these are the most common
        return true;
    } else if (preg_match("/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent)) {
        // these are less common, and might not be worth checking
        return true;
    }
    return false;
}
