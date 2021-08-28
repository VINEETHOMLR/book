<?php

/**
 * Core controller File
 *
 *
 */

namespace inc;

use src\lib\Router;
use src\lib\Helper;

class Controller {

    /**
     *
     * @var String represents the layout file name 
     */
    public $layout = 'main';

    /**
     *
     * @var represents the Title of the page 
     */
    public $title = '';

    /**
     *
     * @var Array
     */
    public $params = [];

    /**
     *
     * @var String, HTML Based
     */
    public $content = "";

    /**
     *
     * @var String
     */
    public $lang;

    /**
     *
     * @var String
     */
    public $controller = '';

    /**
     *
     * @var String
     */
    public $action = '';

    protected $needAuth = true;

    public function __construct() {
        $this->lang = $_SESSION['INF_lang'];
        if (Router::getReqMethod() === 'post' && isset($_SESSION['_CS_IS_']) && Helper::isValidCSRF($_SESSION['_CS_IS_']) === false) {
            return $this->render('error/error', ['code' => 400, 'title' => 'Invalid Request', 'text' => 'Oops! Looks like problem with the request! Refresh your cache #ERRCSRF001']);
        }

        $this->isLogin = isset($_SESSION['INF']) ? true : false;
        $this->checkAuth();

    }

    protected function checkAuth()
    {

        if (isset($this->needAuth) && $this->needAuth && !$this->isLogin) {

           header("location:" . BASEURL); 
        }
    }

    /**
     * 
     * @param String $ctlr
     * @param String $action
     * @return boolean
     */
    private function divertConn($ctlr, $action) {
        $headers = apache_request_headers();
        if (isset($headers['Cors-API'])) {
            $method = Router::getReqMethod();
            $url = Raise::params()[$headers['Cors-API']] . $ctlr . '/' . $action . '?' . $_SERVER['QUERY_STRING'];
            switch ($method) {
                case 'post':
                    header('Cors-Res-Post: ' . $url);
                    return Router::cPost($_REQUEST, $url, true);
                case 'get':
                    header('Cors-Res-Get: ' . $url);
                    return Router::cGet($url, true);
                default :
                    return Router::cGet($url, true);
            }
        }
        return $this->verifyAuth($headers);
    }

    /**
     * 
     * @param Array $headers
     * @return Boolean Authentication
     */
    private function verifyAuth($headers) {
        //$password = Raise::params()['authKey'];
        if (array_key_exists('Authorization', $headers)) {
            if (password_verify('anand', 'anand')) {
                return true;
            } else {
                return $this->render('error/error', ['code' => 401, 'title' => 'Unauthorized', 'text' => 'Oops! Unauthorized Request #ERRAUTH002']);
            }
        }
        return true;
    }

    /**
     * 
     * @param String $ctlr
     * @param String $action
     * @return boolean
     */
    public function checkAccess($ctlr, $action) {
        $this->controller = $ctlr;
        $this->action = $action;
        $isDivert = $this->divertConn($ctlr, $action);
        if ($isDivert !== true) {
            echo $isDivert;
            die;
        }
        return true;
    }
      /**
     * 
     * @param type $viewFile
     * @param type $params
     */

     public function renderlogin($viewFile = '', $params = []) { 
        //$view = isMobi() ? 'src/m/' : 'src/views/'; 
        $view = 'src/views/'; 
        $this->params = $params;
        $viewInclude = BASEPATH . $view . $viewFile . '.php';
        if (file_exists($viewInclude)) { 
            $this->content = $this->renderPhpFile($viewInclude, $params);
        }

        include_once BASEPATH . $view . 'layouts/login.php';
    }
    /**
     * 
     * @param type $viewFile
     * @param type $params
     */
    public function render($viewFile = '', $params = []) {  

        if(!isset($_SESSION['INF'])){ 
            header("location:" . BASEURL); 
        }

        //$view = isMobi() ? '/src/m/' : '/src/views/';
        $view = '/src/views/';
        $this->params = $params;
        $viewInclude = BASEPATH . $view . $viewFile . '.php';
        if (file_exists($viewInclude)) { 
            $this->content = $this->renderPhpFile($viewInclude, $params);
        }

        include_once BASEPATH . $view . 'layouts/' . $this->layout . '.php';
    }

    /**
     * 
     * @param String $viewFile
     * @param String $params
     */
    public function renderAjax($viewFile = '', $params = []) {
        $view = isMobi() ? '/src/m/' : '/src/views/';
        $this->params = $params;
        $viewInclude = BASEPATH . $view . $viewFile . '.php';
        if (file_exists($viewInclude)) {
            echo $this->renderPhpFile($viewInclude, $params);
        }
    }

    /**
     * 
     * @param String $viewFile
     * @param Array $params
     * @return JS String
     */
    public function renderJS($viewFile = '', $params = []) {
        header("Content-Type: application/javascript");
        header("Cache-Control: max-age=604800, public");
        return $this->renderAjax($viewFile, $params);
    }

    /**
     * 
     * @param Array $results
     * @param Integer $statusCode
     */
    public function renderJSON($results, $statusCode = 200) {
        header("Content-Type: application/json");
        $json = json_encode($results);
        http_response_code($statusCode);
        echo $json;
    }

    /*     * * 
     * @param string $_file_ the view file.
     * @param array $_params_ the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return string the rendering result
     * @throws \Exception
     * @throws \Throwable
     */

    public function renderPhpFile($_file_, $_params_ = []) {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require $_file_;
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            } throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            } throw $e;
        }
    }

    /**
     * 
     * @param String $page
     */
    public function redirect($page = 'index/index') {
        header("location:" . BASEURL . $page);
    }

    /**
     * Flush all once the request has completed
     */
    public function __destruct() {
        ob_flush();
    }


    public function sendMessage($type, $msg, $flag = 0) {
    if ($type == "success") {
        $response = array("status" => $type, "response" => $msg);
        $response = json_encode($response);
    } else {
        $response = array("status" => $type, "response" => $msg);
        $response = json_encode($response);
    }
    if ($flag == 0)
        echo $response;
    else
        die($response);
    }

    public function cleanMe($input) {
       $input = trim($input);
       $input = htmlspecialchars($input, ENT_QUOTES, "ISO-8859-1");
       $input = stripslashes($input);
       $input = strip_tags($input);
       return $input;
    }

}
