<?php

/*
 * To handle the safe and secure encryptions and Decryptions Example usage as follows
  $encKey = 'MIAW';
  $cipherMethod = 'aes-128-cfb'; //'aes-128-gcm';
  $secure = new Secure($encKey, $cipherMethod);
  $encryptedToken = $secure->encrypt('The quick brown fox jumps over the lazy dog.');

  $dec = new Secure($encKey, $cipherMethod);
  $decryptedToken = $dec->decrypt($encryptedToken);
 */

namespace src\lib;

use inc\Raise;
use src\lib\RRedis;

/**
 * 
 * 
 */
class Secure {

    /**
     *
     * @var String default cipher method if none supplied
     */
    protected $method = 'aes-128-ctr';

    /**
     *
     * @var String
     */
    private $key;

    /**
     * 
     * @return String
     */
    protected function iv_bytes() {
        return openssl_cipher_iv_length($this->method);
    }

    public function __construct($key = FALSE, $method = FALSE) {
        if (!$key) {
            $key = php_uname(); // default encryption key if none supplied
        }
        if (ctype_print($key)) {
            // convert ASCII keys to binary format
            $this->key = openssl_digest($key, 'SHA256', TRUE);
        } else {
            $this->key = $key;
        }
        if ($method) {
            if (in_array(strtolower($method), openssl_get_cipher_methods())) {
                $this->method = $method;
            } else {
                die(__METHOD__ . ": unrecognised cipher method: {$method}");
            }
        }
    }

    /**
     * 
     * @param String $data
     * @return String Encrypted
     */
    public function encrypt($data) {
        $iv = openssl_random_pseudo_bytes($this->iv_bytes());
        return bin2hex($iv) . openssl_encrypt($data, $this->method, $this->key, 0, $iv);
    }

    /**
     * 
     * @param String $data Encrypted Data
     * @return boolean | Decrypted Text
     */
    public function decrypt($data) {
        $iv_strlen = 2 * $this->iv_bytes();
        if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
            list(, $iv, $crypted_string) = $regs;
            if (ctype_xdigit($iv) && strlen($iv) % 2 == 0) {
                return openssl_decrypt($crypted_string, $this->method, $this->key, 0, hex2bin($iv));
            }
        }
        return FALSE; // failed to decrypt
    }

    /**
     * 
     * @param Boolean $raw
     * @param String $name
     * @return String
     */
    public static function CSRF($raw = false, $name = 'rForm') {
        $exToken = (isset(Raise::$csrf_token) && !empty(Raise::$csrf_token)) ? Raise::$csrf_token : '';
        $sec = new Secure($name);
        $token = $sec->encrypt($exToken);
        return ($raw === true) ? ['name' => 'rf_cs_' . $name . '_', 'token' => $token] : '<input type="hidden" value="' . $token . '" name="rf_cs_' . $name . '_" />';
    }

    /**
     * 
     * @param String $content
     * @param Boolean $doubleEncode
     * @return String encoded content
     */
    public static function encode($content, $doubleEncode = true) {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }

    /**
     * Decodes special HTML entities back to the corresponding characters.
     * This is the opposite of [[encode()]].
     * @param string $content the content to be decoded
     * @return string the decoded content
     */
    public static function decode($content) {
        return htmlspecialchars_decode($content, ENT_QUOTES);
    }

    /**
     * Method to produce Unique ID - Alpha Numeric
     * @return String
     */
    public static function uniqueID($len = 8) {
        return strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $len));
    }

    /**
     * generate private key and public key
     * stored in redis for 5 minute
     * @return public key
     */
    public static function generateKey() {
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        // Create the keypair
        $key = openssl_pkey_new($config);
        if (!$key) {
            return 'Unable to generate RSA key pair';
         }

        // Get private key
        openssl_pkey_export($key, $private);
        if (!$private) {
            return 'Unable to extract private key';
        }

        // Get public key
        $public = openssl_pkey_get_details($key)['key'] ?? null;
        if (!$public) {
            return 'Unable to extract public key';
        }
        openssl_get_publickey($public);

        $headers = getallheaders_new();
        if (!isset($headers['Deviceid']) || empty($headers['Deviceid'])) {
            return 'Invalid Device ID';
        }

        $redisObj = new RRedis;
        $redisObj->del($headers['Deviceid']);
        $redisObj->set($headers['Deviceid'], [
            'key' => $public,
            'private' => $private
        ], 43200);

        return [
            'key' => $public
        ];
    }
}
