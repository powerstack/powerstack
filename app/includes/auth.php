<?php
use Powerstack\Plugins\Authentication;
use Powerstack\Core\Registry;

/**
* Auth
* Authenicate a user
*
* @see Powerstack\Plugins\Authenication::auth()
* @param string $username   Username from form
* @param string $password   Password form form
* @return bool true on success, false otherwise
*/
function auth($username, $password) {
    $registry = Registry::getInstance();

    if (!$registry->exists('auth')) {
        $registry->set('auth', new Authentication());
    }

    $auth = $registry->get('auth');
    return $auth->auth($username, $password);
}

/**
* Authd
* Check if user is authenicated
*
* @see Powerstack\Plugins\Authenicated::authd()
* @return true if authenicated, false otherwise
*/
function authd() {
    $registry = Registry::getInstance();

    if (!$registry->exists('auth')) {
        $registry->set('auth', new Authentication());
    }

    $auth = $registry->get('auth');
    return $auth->authd();
}

/**
* hasRole
* Check if user has a role assigned
*
* @see Powerstack\Plugins\Authenicated::hasRole()
* @param string $role   Role to check
* @return bool true if user has role, false otherwise
*/
function hasRole($role) {
    $registry = Registry::getInstance();

    if (!$registry->exists('auth')) {
        $registry->set('auth', new Authentication());
    }

    $auth = $registry->get('auth');
    return $auth->hasRole($role);
}

/**
* Hash Password
* Hash a password
*
* @see Powerstack\Plugins\Authenicated::hashPassword()
* @param string $password   Password to hash
* @return string hashed password
*/
function hashPassword($password) {
    $registry = Registry::getInstance();

    if (!$registry->exists('auth')) {
        $registry->set('auth', new Authentication());
    }

    $auth = $registry->get('auth');
    return $auth->hashPassword($password);
}
?>
