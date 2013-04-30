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
    * @param string $conf_file  Path to config xml file
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
    * @param string $conf_file  Path to config xml file
    * @throws Exception
    * @return void
    */
    protected function load_config($conf_file) {
        $xml = simplexml_load_file($conf_file);

        if (empty($xml)) {
            throw new Exception("Unable to read config file: " . $conf_file . ", file maybe be empty.");
        }

        // Processing the application configuration
        if (!isset($xml->application)) {
            throw new Exception("There is no application information in " . $conf_file . ", this is required.");
        }

        if (!isset($xml->application->name)) {
            throw new Exception("There is no application name information in " . $conf_file . ", this is required.");
        }

        if (!isset($xml->application->version)) {
            throw new Exception("There is no application version information in " . $conf_file . ", this is required.");
        }

        if (!isset($xml->application->author)) {
            throw new Exception("There is no application author information in " . $conf_file . ", author name is required.");
        }

        $this->application = (object) array(
            'name' => strval($xml->application->name),
            'version' => doubleval($xml->application->version),
            'author' => (object) array(
                'name' => strval($xml->application->author->name),
                'email' => empty($xml->application->author->email) ? null : strval($xml->application->author->email),
                'website' => empty($xml->application->author->website) ? null : strval($xml->application->author->website),
            ),
        );

        // Processing the template configuration
        if (!isset($xml->template)) {
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
            if (!isset($xml->template->engine)) {
                throw new Exception("There is no template engine information in " . $conf_file .", if unsure use core.");
            }

            if (!isset($xml->template->viewsdir)) {
                throw new Exception("There is no template viewsdir information in " . $conf_file .", if unsure use app/views.");
            }

            if (!isset($xml->template->layouts)) {
                throw new Exception("There is no template layouts information in " . $conf_file . ", this is required.");
            }

            $layouts = array();

            foreach ($xml->template->layouts->layout as $layout) {
                $layouts[strval($layout->name)] = (object) array(
                    'file' => strval($layout->file),
                );
            }

            if (!isset($layouts['default'])) {
                throw new Exception("A layout woth the name default is required in " . $conf_file );
            }

            $this->template = (object) array(
                'engine' => strval($xml->template->engine),
                'viewsdir' => strval($xml->template->viewsdir),
                'layouts' => (object) $layouts,
            );
        }

        // Process session configuration
        if (!isset($xml->session)) {
            $this->session = (object) array(
                'savepath' => '/tmp/powerstack-sessions',
                'engine' => 'simple',
            );
        } else {
            if (!isset($xml->session->engine)) {
                throw new Exception("Session engine must be set. Default is simple");
            }

            if (!isset($xml->session->savepath)) {
                throw new Exception("Session savepath must be set. Default is /tmp/powerstack-sessions");
            }

            $this->session = (object) array(
                'savepath' => $xml->session->savepath,
                'engine' => $xml->session->engine,
            );
        }

        // Processing any plugin configuration
        if (isset($xml->plugins) && !empty($xml->plugins)) {
            $plugins = array();

            foreach ($xml->plugins as $plugin) {
                foreach ($plugin as $name => $conf) {
                    foreach ($conf as $key => $value) {
                        if (!isset($plugins[$name])) {
                            $plugins[$name] = new \stdClass();
                        }

                        $plugins[$name]->{$key} = strval($value);
                    }
                }
            }

            $this->plugins = (object) $plugins;
        }

        // Process misc settings
        if (isset($xml->settings) && !empty($xml->settings)) {
            $this->settings = $this->get_misc_settings($xml->settings);
        }
    }

    /**
    * Get Misc Settings
    * Get the misc settings form the config file
    *
    * @access protected
    * @param SimpleXML Object   $xml    The settings SimpleXML object
    * @param mixed  $settings   The current value of the settings object. (optional)
    * @param mixed  $parentname The name of the parent object
    * @return stdclass settings
    */
    protected function get_misc_settings($xml, $settings = null, $parentname = null) {
        if (is_null($settings)) {
            $settings  = new \stdClass();
        }

        if (!empty($parentname)) {
            if (strpos($parentname, ',') !== false) {
                $names = explode(',', $parentname);
                $search = $names[0];

                for ($i = 0; $i < count($names); $i++) {
                    if ($i > 0) {
                        $search .= "->" . $names[$i];
                    }

                    if (!isset($settings->{$search})) {
                        $settings->{$search} = new \stdClass();
                    }
                }
            } else {
                if (!isset($settings->{$parentname})) {
                    $settings->{$parentname} = new \stdClass();
                }
            }
        }

        if (!empty($parentname)) {
            if (strpos($parentname, ',') !== false) {
                $search = implode('->', explode(',', $parentname));
            } else {
                $search = $parentname;
            }
        }

        if (empty($parentname)) {
            foreach ($xml as $setting) {
                foreach ($setting as $name => $value) {
                    $children = $value->children();
                    if (!empty($children)) {
                        if (!empty($parentname)) {
                            $settings = $this->get_misc_settings($value, $settings, $parentname . "," . $name);
                        } else {
                            $settings = $this->get_misc_settings($value, $settings, $name);
                        }
                    } else {
                        $settings->{$name} = strval($value);
                    }
                }
            }
        } else {
            foreach ($xml as $name => $value) {
                $settings->{$search}->{$name} = strval($value);
            }
        }

        return $settings;
    }
}
?>
