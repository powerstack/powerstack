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
namespace Powerstack\Core;

class Params {
    /**
    * __construct
    * Create a new Powerstack\Core\Params object
    */
    function __construct() {
        $this->init();
    }

    /**
    * __get
    * Get a class var value
    *
    * @param string $name   Name of class var to get
    * @return mixed class var value
    */
    function __get($name) {
        if (isset($this->{$name})) {
            return $this->{$name};
        }

        return null;
    }

    /**
    * Parse Uri
    * Parse a requested uri for any placeholder values and add them to the class vars
    *
    * @access public
    * @param string $routeuri       The route uri
    * @param string $requesturi     The request uri
    * @return void
    */
    function parse_uri($routeuri, $requesturi) {
        preg_match_all('#:([^/]+|.+)#', $routeuri, $names);
        $reguri = preg_replace('#:([^/]+|.+)#', '(.+)', $routeuri);
        preg_match('#^' . $reguri . '$#', $requesturi, $values);

        if (!empty($names) && !empty($values)) {
            foreach ($names[1] as $key => $name) {
                $this->{$name} = $values[$key + 1];
            }
        }
    }

    /**
    * Init
    * Initalize the object and load anything for $_GET & $_POST into the class vars
    *
    * @access protected
    * @return void
    */
    protected function init() {
        $params = array_merge($_GET, $_POST);

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
?>
