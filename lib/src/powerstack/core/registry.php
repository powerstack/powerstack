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
* Registry
* Registry class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/

namespace Powerstack\Core;

class Registry {
    /**
    * @access protected
    * @var array
    */
    protected $store = array();

    /**
    * @access protected
    * @var Powerstack\Core\Registry Object
    */
    protected $instance = null;

    /**
    * Get Instance
    * Get instance of Powerstack\Core\Registry
    *
    * @return Powerstack\Core\Registry
    */
    static function getInstance() {
        if (is_null($this->instance)) {
            $this->instance = new Powerstack\Core\Registry();
        }

        return $this->instance;
    }

    /**
    * Get
    * Get a value from the registry
    *
    * @param string $name   Name of registry item to get
    * @return mixed null if item not found, otherwise value of item
    */
    function get($name) {
        if (!$this->exists($name)) {
            return $this->store[$name];
        }

        return null;
    }

    /**
    * Set
    * Set a item in the registry
    *
    * @param sting $name    Name of item to store
    * @param moxed $value   Value of item
    * @return void
    */
    function set($name, $value) {
        $this->store[$name] = $value;
    }

    /**
    * Exists
    * See if an item exists in the registry
    *
    * @access public
    * @param string $name   Name of item to check
    * @return bool true if exists, false otherwise
    */
    function exists($name) {
        return isset($this->store[$name]);
    }

    /**
    * __construct
    *
    * @access private
    */
    private function __construct() {
    }

    /**
    * __clone
    *
    * @access private
    */
    private function __clone() {
    }
}
?>
