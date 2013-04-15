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
* Session Simple
* Simple Session class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/

namespace Powerstack\Core;

class SessionSimple {
    /**
    * Init
    * Initalize the session storage
    *
    * Configuration:
    *   app/config.xml:
    *       <session>
    *           <engine>simple</engine>
    *           [<savepath>[path to directory to store session]</savepath>]
    *       </session>
    *
    * @access public
    * @return bool true on success, false otherwise
    */
    function init() {
        $conf = config('session');

        if (isset($conf->savepath)) {
            $savepath = rtrim($conf->savepath, '/');
            return ini_set('session.save_path', $savepath);
        }

        return true;
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
    * @param string $name Name of session to get
    * @return mixed session value or false
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
