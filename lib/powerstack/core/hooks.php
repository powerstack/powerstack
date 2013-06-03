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
* Hooks
* Hooks class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/
namespace Powerstack\Core;

class Hooks {
    /**
    * Array that stores hooks
    *
    * @access private
    * @var array
    */
    private $hooks;

    /**
    * Register
    * Register a hook to be executed
    *
    * @acess public
    * @param string     $name       Name of hook to register
    * @param callback   $function    Function to be executed when hook is run
    * @return bool true
    */
    function register($name, $function) {
        if (!isset($this->hooks[$name])) {
            $this->hooks[$name] = array();
        }

        $this->hooks[$name][] = $function;
        return true;
    }

    /**
    * Exists
    * Check if a hook exists
    *
    * @access public
    * @param string $name   Name of hook to check
    * @return bool true if exists, false if it doesn't exist
    */
    function exists($name) {
        if (isset($this->hooks[$name])) {
            return true;
        }

        return false;
    }

    /**
    * Get
    * Get all the functions that have been registered for a hook
    *
    * @access public
    * @param string $name   Name of hook to get functions for
    * @return array empty array if no functions found, array of function if there are functions
    */
    function get($name) {
        if ($this->exists($name)) {
            return $this->hooks[$name];
        }

        return array();
    }

    /**
    * Run
    * Run a hook
    *
    * @access public
    * @param string $name   Name of hook to run
    * @return bool false if any errors
    */
    function run($name) {
        if (func_num_args() > 1) {
            $args = array_shift(func_get_args());
        }

        if (!$this->exists($name)) {
            return false;
        }

        $hooks = $this->get($name);

        if (!empty($hooks)) {
            foreach ($hooks as $hook) {
                if (is_array($hook)) {
                    if (empty($args)) {
                        call_user_func_array($hook);
                    } else {
                        call_user_func_array($hook, $args);
                    }
                } else {
                    if (empty($args)) {
                        $hook();
                    } else {
                        $hook($args);
                    }
                }
            }
        }

        return false;
    }
}
?>
