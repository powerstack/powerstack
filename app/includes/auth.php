<?php
use Powerstack\Plugins\Authenication;
$auth = new Authenication();

/**
* Auth
* Authenicate a user
*
* @see Powerstack\Plugins\Authenication::auth()
*/
function auth($username, $password) {
    global $auth;
    return $auth->auth($username, $password);
}

/**
* Authd
* Check if user is authenicated
*
* @see Powerstack\Plugins\Authenicated::authd()
*/
function authd() {
    global $auth;
    return $auth->authd();
}

/**
* hasRole
* Check if user has a role assigned
*
* @see Powerstack\Plugins\Authenicated::hasRole()
*/
function hasRole($role) {
    global $auth;
    return $auth->hasRole($role);
}

/**
* Hash Password
* Hash a password
*
* @see Powerstack\Plugins\Authenicated::hashPassword()
*/
function hashPassword($password) {
    global $auth;
    return $auth->hashPassword($password);
}
?>
