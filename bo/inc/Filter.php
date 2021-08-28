<?php

namespace inc;

use src\lib\Role;

/**
 * Description of Filter
 *
 * 
 */
trait Filter {

    /**
     *
     * @var String
     */
    private $action;

    /**
     *
     * @var String
     */
    private $ctlr;

    /**
     * 
     * @param Array $rules
     */
    public function accessControl($rules = []) {
        // Auth Users
        if ($this->isAuth() !== false) {
            if (array_key_exists('@', $rules)) {
                $this->authAccess($rules['@']);
            }
        } else {
            if (array_key_exists('*', $rules) && !in_array($this->action, $rules['*'])) {
                $this->redirect('index/login');
            }
        }
    }

    /**
     * 
     * @param Array $rules
     * @return Boolean
     */
    private function authAccess($rules) {
        foreach ($rules as $rule) {
            $curAction = (array_key_exists('allow', $rule) && in_array($this->action, $rule['allow']));
            $isRole = (array_key_exists('roles', $rule) && in_array((int) $this->isAuth(), $rule['roles']));
            if ($curAction === true && $isRole === true) {
                return true;
            }
        }
        $this->redirect('index/noaccess');
    }

    /**
     * 
     * @return Mixed
     */
    public function isAuth() {
        if (isset($_SESSION['identity'])) {
            return $_SESSION['identity']->role;
        }
        return false;
    }

    /**
     * 
     * @param String $ctlr
     * @param String $action
     */
    public function setup($ctlr, $action) {
        $this->action = $action;
        $this->ctlr = $ctlr;
    }

    /**
     * 
     * @param String $ctlr
     * @param String $action
     * @return boolean
     */
    public function checkAccess($ctlr, $action) {
        $access = Role::access();
        if (isset($_SESSION['identity'])) {
            if ($ctlr . '/' . $action === 'index/noaccess') {
                return true;
            }
            $role = $_SESSION['identity']->role;
            if (array_key_exists((int) $role, $access) && in_array($ctlr . '/' . $action, $access[$role])) {
                return true;
            } else {
                $this->redirect('index/noaccess');
            }
        }
        return true;
    }

}
