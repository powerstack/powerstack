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
* SessionFactory
* SessionFactory class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/
namespace Powerstack\Core;

class SessionFactory {
    /**
    * Session object
    *
    * @access private
    * @var Session object
    */
    private $session;

    /**
    * Config object
    *
    * @access private
    * @var stdclass
    */
    private $conf;

    /**
    * __construct
    * Create a new Powerstack\Core\SessionFactory object
    *
    * Configuration:
    *   app/config.xml:
    *       <session>
    *           <engine>[session engine]</engine>
    *           ...
    *       </session>
    *
    * @thorws Exception
    */
    function __construct() {
        $this->conf = config('session');

        if ($this->conf->engine != 'simple') {
            $class = 'Powerstack\Plugins\Session\Session' . ucfirst($this->conf->engine);

            if (class_exists($class)) {
                $this->session = new $class();
            } else {
               throw new Exception("Could not find session engine: " . $this->conf->engine . ", no " . $class . " class was found");
            }
        } else {
            $this->session = new SessionSimple();
        }
    }

    /**
    * Init
    * Initalize a session class
    *
    * @access public
    * @return bool true on success, false otherwise
    */
    function init() {
        return $this->session->init();
    }

    /**
    * Set
    * Set a session
    *
    * @acccess public
    * @param string $name   Name of session
    * @param mixed  $value  Value of session
    * @return bool true
    */
    function set($name, $value) {
        return $this->session->set($name, $value);
    }

    /**
    * Get
    * Get a session value
    *
    * @access public
    * @param string $name   Name of session to get
    * @return mixed value of session if it exists, false otherwise
    */
    function get($name) {
        return $this->session->get($name);
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
        return $this->session->delete($name);
    }
}
?>
