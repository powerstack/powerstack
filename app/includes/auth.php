<?php
use Powerstack\Plugins\Authentication;
use Powerstack\Core\Registry;

/**
* Auth
* Authenicate a user
*
* @see Powerstack\Plugins\Authenication::auth()
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
