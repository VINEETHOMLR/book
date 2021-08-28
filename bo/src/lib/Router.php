<?php

namespace src\lib;

use inc\Raise;

/**
 * 
 */
class Router {

    /**
     * 
     * @param String $queryString
     * @return String
     */
    public static function get($queryString = '') {
        return isset($_GET[$queryString]) ? cleanMe($_GET[$queryString]) : '';
    }

    /**
     * 
     * @param String $queryString
     * @return String
     */
    public static function post($queryString = '') {
        return isset($_POST[$queryString]) ? cleanMe($_POST[$queryString]) : '';
    }

    /**
     * 
     * @param String $queryString
     * @return String
     */
    public static function req($queryString = '') {
        return isset($_REQUEST[$queryString]) ? cleanMe($_REQUEST[$queryString]) : '';
    }

    /**
     * Function to return the data that are received by OPTION or POST without WWW-
     * @return Array
     */
    public static function getINPost() {
        $postdata = file_get_contents("php://input");
        $res = json_decode($postdata);
        return json_last_error() === JSON_ERROR_NONE ? $res : ['post' => 'iNVALID_pOST_dATA'];
    }

    /**
     * 
     * @return String
     */
    public static function getReqMethod() {
        return isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : '';
    }

    /**
     * 
     * @return Boolean
     */
    public static function isAjaxReq() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    /**
     * 
     * @param String $url
     * @return Mixed
     */
    public static function cGet($url, $isRaw = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: ' . self::getHash()
        ));
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
//                throw new Exception(curl_error($ch));
            $result = null;
        }
        curl_close($ch);
        return $result !== null ? (($isRaw === true) ? $result : self::rJSON($result)) : $result;
    }

    /**
     * 
     * @param String $res
     * @return Mixed
     */
    public static function rJSON($res) {
        $response = json_decode($res, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $response : $res;
    }

    /**
     * 
     * @param Array $data
     * @param String $url
     * @return Mixed
     */
    public static function cPost($data = [], $url = '', $isRaw = false) {
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
            'Authorization: ' . self::getHash()
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return ($isRaw === true) ? $result : self::rJSON($result);
    }

    /**
     * 
     * @return Hashed String 
     */
    public static function getHash() {
        $password = Raise::params()['authKey'];
        return password_hash(
                base64_encode(
                        hash('sha256', $password, true)
                ), PASSWORD_DEFAULT
        );
    }

}
