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
* Config
* Config class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/
namespace Powerstack\Core;

class Config {
    /**
    * Config object
    *
    * @access protected
    * @var stdclass
    */
    protected $config;

    /**
    * __construct
    * Create a new Powerstack\Core\Config object
    *
    * @access public
    * @param string $conf_file  Path to config yaml file
    * @throws Exception
    */
    function __construct($conf_file) {
        if (!is_string($conf_file)) {
            throw new Exception("Powerstack\Core\Config::__construct() take a string a parameter not a " . gettype($conf_file) . ".");
        }

        if (!file_exists($conf_file)) {
            throw new Exception("Config file: " . $conf_file . " doesn't exist please create it.");
        }

        $this->load_config($conf_file);
    }

    /**
    * __get
    * Get a class variable
    *
    * @param string $name   Name of class var to get
    * @return mixed null if class var doesn't exist or the value of class var if it does exist
    */
    function __get($name) {
        if (isset($name)) {
            return $this->{$name};
        }

        return null;
    }

    /**
    * __set
    * Set a class variable
    *
    * @param string $name   Name of class var to set
    * @param mixed  $value  Value to set class var to
    * @return void
    */
    function __set($name, $value) {
        $this->{$name} = $value;
    }

    /**
    * Load Config
    * Load the config file into class variables
    *
    * @access protected
    * @param string $conf_file  Path to config file
    * @throws Exception
    * @return void
    */
    protected function load_config($conf_file) {
        $yaml = \Spyc::YAMLLoad($conf_file);
        $config = $this->arrayToObj($yaml);

        // Processing the application configuration
        if (!isset($config->application)) {
            throw new Exception("There is no application information in " . $conf_file . ", this is required.");
        }

        if (!isset($config->application->name)) {
            throw new Exception("There is no application name information in " . $conf_file . ", this is required.");
        }

        if (!isset($config->application->version)) {
            throw new Exception("There is no application version information in " . $conf_file . ", this is required.");
        }

        if (!isset($config->application->author)) {
            throw new Exception("There is no application author information in " . $conf_file . ", author name is required.");
        }

        $this->application = $config->application;

        // Process template config
        if (!isset($config->template)) {
            $this->template = (object) array(
                'engine' => 'core',
                'viewsdir' => 'app/views',
                'layouts' => (object) array(
                    'default' => (object) array(
                        'file' => 'app/views/layouts/default.tpl',
                    ),
                ),
            );
        } else {
            if (!isset($config->template->engine)) {
                throw new Exception("There is no template engine information in " . $conf_file .", if unsure use core.");
            }

            if (!isset($config->template->viewsdir)) {
                throw new Exception("There is no template viewsdir information in " . $conf_file .", if unsure use app/views.");
            }

            if (!isset($config->template->layouts)) {
                throw new Exception("There is no template layouts information in " . $conf_file . ", this is required.");
            }

            $this->template = $config->template;
        }

        // Process session configuration
        if (!isset($config->session)) {
            $this->session = (object) array(
                'savepath' => '/tmp/powerstack-sessions',
                'engine' => 'simple',
            );
        } else {
            if (!isset($config->session->engine)) {
                throw new Exception("Session engine must be set. Default is simple");
            }

            if (!isset($config->session->savepath)) {
                throw new Exception("Session savepath must be set. Default is /tmp/powerstack-sessions");
            }

            $this->session = $config->session;
        }

        // Process plugin config
        if (isset($config->plugins) && !empty($config->plugins)) {
            $this->plugins = $config->plugins;
        }

        if (isset($config->settings) && !empty($config->settings)) {
            $this->settings = $config->settings;
        }
    }

    /**
    * Array To Obj
    * Convert an array to a object
    *
    * @access protected
    * @param array $array   Array to convert to object
    * @return object
    */
    protected function arrayToObj($array) {
        return is_array($array) ? (object) array_map(array($this, __FUNCTION__), $array) : $array;
    }
}
?>
