<?php

namespace src\lib;

/**
 * RBAC Control File
 *
 * 
 */
class Role {

    const SA = 1; //Define each role as a constant here
    const R1 = 2;
    const R2 = 3;
    const L2 = 4;
    const L3 = 5;

    /**
     * 
     * @param Array $roles
     * @return Boolean
     */
    public static function hasAccess($roles = []) {
        if (!empty($roles)) {
            return isset($_SESSION['identity']) && in_array((int) $_SESSION['identity']->role, $roles);
        }
        return false;
    }

    /**
     * 
     * @return Boolean
     */
    public static function isGuest($roles = []) {
        return isset($_SESSION['identity']) && in_array((int) $_SESSION['identity']->role, $roles);
    }

}
