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
* Session Memcached
* Memcached session handler for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Plugins
*/
namespace Powerstack\Plugins\Session;

class SessionMemcached {
    /**
    * Init
    * Initalize session storage
    *
    * Configuration:
    *   app/config.yml:
    *       session:
    *           engine: memcached
    *           savepath: [memcached server (host:port[,host:port])]
    *
    * @access public
    * @return bool true on success, false otherwise
    */
    function init() {
        $conf = config('session');
        ini_set('session.save_handler', 'memcached');
        return ini_set('session.save_path', $conf->savepath);
    }

    /**
    * Set
    * Set a session
    *
    * @access public
    * @param string $name   Name of session
    * @param mixed  $value  Value of session
    * @return bool true
    */
    function set($name, $value) {
        @session_start();
        $_SESSION[$name] = $value;
        @session_write_close();
        return true;
    }

    /**
    * Get
    * Get a session
    *
    * @access public
    * @param string $name   Name of session to get
    * @return mixed value of session on success, false otherwise
    */
    function get($name) {
        @session_start();
        $sess = (isset($_SESSION[$name])) ? $_SESSION[$name] : false;
        @session_write_close();
        return $sess;
    }

    /**
    * Delete
    * Delete a session
    *
    * @access public
    * @param string $name   Name of session to delete
    * @return bool true
    */
    function delete($name) {
        @session_start();
        unset($_SESSION[$name]);
        @session_write_close();
        return true;
    }
}
?>
