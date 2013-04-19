<?php
use Powerstack\Plugins\Authentication;
$registry = Powerstack\Core\Registry::getInstance();
$registry->set('auth', new Authentication());

/**
* Auth
* Authenicate a user
*
* @see Powerstack\Plugins\Authenication::auth()
*/
function auth($username, $password) {
    $registry = Powerstack\Core\Registry::getInstance();
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
    $registry = Powerstack\Core\Registry::getInstance();
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
    $registry = Powerstack\Core\Registry::getInstance();
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
    $registry = Powerstack\Core\Registry::getInstance();
    $auth = $registry->get('auth');
    return $auth->hashPassword($password);
}
?>
