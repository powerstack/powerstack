<?php
/*
* Copyright (c) 2013 onwards Christopher Tombleson <chris@powerstack-php.org>
*
* Permission is hereby granted, free of charge, to any person obtaining a copy of this
* software and associated documentation files (the "Software"), to deal in the Software
* without restriction, including without limitation the rights to use, copy, modify, merge,
* publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
* to whom the Software is furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
* BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
* IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
* IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE
* OR OTHER DEALINGS IN THE SOFTWARE.
*/
/**
* Authentication
* Authentication class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Plugins
*/
namespace Powerstack\Plugins;

class Authentication {
    /**
    * @access private
    * @var stdclass
    */
    private $conf;

    /**
    * @access private
    * @var Powerstack\Plugins\Database
    */
    private $db;

    /**
    * __construct
    * Create a new Powerstack\Plugins\Authenication object
    *
    * Depends on Powerstack\Plugins\Database
    *
    * Configuration:
    *   app/config.xml:
    *       <plugins>
    *           <authenication>
    *               <salt>[A 22 character salt of a-z A-Z 0-9]</salt>
    *           </authenication>
    *       </plugins>
    *
    *   Database:
    *       mysql:
    *            CREATE TABLE users(
    *                id integer not null primary key auto_increment, 
    *                username varchar(32) not null unique, 
    *                password text not null, 
    *                roles text not null
    *            );
    *        sqlite:
    *            CREATE TABLE users(
    *                id integer not null primary key autoincrement,
    *                username varchar(32) not null unique,
    *                password text not null.
    *                roles text not null
    *            );
    *        postrgesql:
    *            CREATE TABLE users(
    *                id serial not null primary key,
    *                username varchar(32) not null unique,
    *                password text not null,
    *                roles text not null
    *            );
    */
    function __construct() {
        $conf = config('plugins');

        if (!isset($conf->authenication)) {
            throw new \Exception("Please configure authenication plugin in config.xml");
        }

        $this->conf = $conf->authenication;
        $this->db = new Database();
        $this->db->connect();
    }

    /**
    * Auth
    * Authenicate a user based on a userrname and password
    *
    * @access public
    * @param string $username   Username
    * @param string $password   Password
    * @return bool true if user is authenicated, false otherwise
    */
    function auth($username, $password) {
        $app = registry('app');

        $hash = $this->hashPassword($password);
        $result = $this->db->select('users', array('username' => $username, 'password' => $hash));

        if (!$result) {
            throw new \Exception("Unable to query database.");
        }

        $user = $this->db->fetch();

        if (empty($user)) {
            return false;
        }

        $app->request->session->set('user', (object) array(
            'uid' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'roles' => explode(',', $user->roles),
        ));

        return true;
    }

    /**
    * Authd
    * Check if the current user is authenicated
    *
    * @access public
    * @return bool true if user is authenicated, false otherwise
    */
    function authd() {
        $app = registry('app');
        $sess = $app->request->session->get('user');

        if ($sess === false) {
            return false;
        }

        return true;
    }

    /**
    * Has Role
    * Check if a user has a role assigned
    *
    * @param string $role   Role to check
    * @return bool true if user has role, false otherwise
    */
    function hasRole($role) {
        $app = registry('app');
        $sess = $app->request->session->get('user');

        if ($sess === false) {
            return false;
        }

        if (!in_array($role, $sess->roles)) {
            return false;
        }

        return true;
    }

    /**
    * Hash Password
    * Hash a password
    *
    * @access public
    * @param string $password   Password to hash
    * @return string hashed password
    */
    function hashPassword($password) {
        return crypt($password, '$2y$07$'.$this->conf->salt.'$');
    }
}
?>
