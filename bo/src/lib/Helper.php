<?php

namespace src\lib;

/**
 * 
 */
class Helper {

     protected $method = 'aes-128-ctr';

      private $key;


      public function __construct($key = FALSE, $method = FALSE) {
        if (!$key) {
            $key = php_uname(); // default encryption key if none supplied
        }
        // convert ASCII keys to binary format
        $this->key = (ctype_print($key)) ? openssl_digest($key, 'SHA256', TRUE) : $key;

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
     * @param String $type
     * @return boolean
     */
    public static function checkUserAgent($type = NULL) {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if ($type == 'bot') {
            if (preg_match("/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent)) {
                return true;
            }
        } else if ($type == 'browser') {
            if (preg_match("/mozilla\/|opera\//", $user_agent)) {
                return true;
            }
        } else if ($type == 'mobile') {
            if (preg_match("/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent)) {
                return true;
            } else if (preg_match("/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param String $msg
     */
    public static function setInfo($msg) {
        $unID = uniqid() . rand(1, 99);
        $_SESSION['alertInfo'] = '<div id="' . $unID . '" class="alert alert-primary" role="alert">' . $msg . '</div><script>setTimeout(function(){ document.getElementById("' . $unID . '").style.display = "none"; }, 5000);</script>';
    }

    /**
     * 
     * @return String
     */
    public static function getInfo() {
        if (isset($_SESSION['alertInfo'])) {
            $info = $_SESSION['alertInfo'];
            unset($_SESSION['alertInfo']);
            return $info;
        } else {
            return '';
        }
    }

    /**
     * 
     * @param String $name
     * @return String
     */
    public static function CSRF($name = 'rForm', $raw = false) {
        $token = (function_exists('mcrypt_create_iv')) ? bin2hex(random_bytes(32)) : bin2hex(openssl_random_pseudo_bytes(32));
        $_SESSION[$name] = $token;
        $_SESSION['_CS_IS_'] = $name;
        return ($raw === true) ? ['name' => 'rf_cs_' . $name . '_', 'token' => $token] : '<input type="hidden" value="' . $_SESSION[$name] . '" name="rf_cs_' . $name . '_" />';
    }

    /**
     * 
     * @param String $name
     * @param String $value
     * @return boolean
     */
    public static function isValidCSRF($name) {
        $value = Router::req('rf_cs_' . $name . '_');
        if (isset($_SESSION[$name]) && $_SESSION[$name] === $value) {
            unset($_SESSION[$name]);
            return true;
        }
        return false;
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


     protected function iv_bytes() {
        return openssl_cipher_iv_length($this->method);
    }


}
