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
* Application
* Application class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/
namespace Powerstack\Core;

class Application {
    /**
    * Powerstack\Core\Config object
    *
    * @acess public
    * @var Powerstack\Core\Config
    */
    public $config;

    /**
    * Powerstack\Core\Request object
    *
    * @access public
    * @var Powerstack\Core\Request
    */
    public $request;

    /**
    * Powerstack\Core\Params object
    *
    * @access public
    * @var Powerstack\Core\Params
    */
    public $params;

    /**
    * Array that holds all the route info
    *
    * @access private
    * @var array
    */
    private $routes = array(
        'get' => array(),
        'post' => array(),
        'put' => array(),
        'delete' => array(),
    );

    /**
    * __construct
    * Create a new instance of Powerstack\Core\Application
    *
    * @access public
    * @param Powerstack\Core\Config $config Powerstack config object
    */
    function __construct(Powerstack\Core\Config $config) {
        $this->config = $config;
        $this->request = new Request();
        $this->params = new Params();
    }

    /**
    * Any
    * Create a route handler that handles more than one HTTP request type
    *
    * @access public
    * @param array      $methods     An array of HTTP request methods. (Methods are get, post, put, delete)
    * @param string     $uri         The route. ('/' for index page)
    * @param callback   $function    The function that is executed when this route is used.
    * @throws Exception
    * @return void
    */
    function any($methods, $uri, $function) {
        if (!is_array($methods)) {
            throw new Exception("The any function requires an array for methods not a " . gettype($methods) . ".");
        }

        if (!is_string($uri)) {
            throw new Exception("The any function requires an string for the uri not a " . gettype($uri) . ".");
        }

        if (!is_callable($function)) {
            throw new Exception("The any function requires a callable function for the function, make sure it's callable.");
        }

        $allowed_methods = array('get', 'post', 'put', 'delete');

        foreach ($methods as $method) {
            if (!in_array(strtolower($method), $allowed_methods)) {
                throw new Exception("HTTP Method: " . $method . " is not valid.");
            } else {
                $this->routes[strtolower($method)][$uri] = $function;
            }
        }
    }

    /**
    * Get
    * Create a route handler for HTTP GET request
    *
    * @access public
    * @param string     $uri        The route. ('/' for index page)
    * @param callback   $function   The function that is executed when this route is used.
    * @throws Exception
    * @return void
    */
    function get($uri, $function) {
        if (!is_string($uri)) {
            throw new Exception("The get function requires an string for the uri not a " . gettype($uri) . ".");
        }

        if (!is_callable($function)) {
            throw new Exception("The get function requires a callable function for the function, make sure it's callable.");
        }

        $this->routes['get'][$uri] = $function;
    }

    /**
    * Post
    * Create a route handler for HTTP POST request
    *
    * @access public
    * @param string     $uri        The route. ('/' for index page)
    * @param callback   $function   The function that is executed when this route is used.
    * @throws Exception
    * @return void
    */
    function post($uri, $function) {
        if (!is_string($uri)) {
            throw new Exception("The post function requires an string for the uri not a " . gettype($uri) . ".");
        }

        if (!is_callable($function)) {
            throw new Exception("The post function requires a callable function for the function, make sure it's callable.");
        }

        $this->routes['post'][$uri] = $function;
    }

    /**
    * Put
    * Create a route handler for HTTP PUT request
    *
    * @access public
    * @param string     $uri        The route. ('/' for index page)
    * @param callback   $function   The function that is executed when this route is used.
    * @throws Exception
    * @return void
    */
    function put($uri, $function) {
        if (!is_string($uri)) {
            throw new Exception("The put function requires an string for the uri not a " . gettype($uri) . ".");
        }

        if (!is_callable($function)) {
            throw new Exception("The put function requires a callable function for the function, make sure it's callable.");
        }

        $this->routes['put'][$uri] = $function;
    }

    /**
    * Delete
    * Create a route handler for HTTP Delete request
    *
    * @access public
    * @param string     $uri        The route. ('/' for index page)
    * @param callback   $function   The function that is executed when this route is used.
    * @throws Exception
    * @return void
    */
    function delete($uri, $function) {
        if (!is_string($uri)) {
            throw new Exception("The delete function requires an string for the uri not a " . gettype($uri) . ".");
        }

        if (!is_callable($function)) {
            throw new Exception("The delete function requires a callable function for the function, make sure it's callable.");
        }

        $this->routes['delete'][$uri] = $function;
    }

    /**
    * Run
    * This is the main function that runs the routes.
    *
    * @access public
    * @throws Exception
    * @return void
    */
    function run() {
        if (!empty($this->routes[$this->request->request_method])) {
            foreach (array_keys($this->routes[$this->request->request_method]) as $uri) {
                $muri = preg_replace('#:([^/]+|.+)#', '(.+)', $uri);
                if (preg_match('#^' . $muri . '$#', $this->request->request_uri, $matches)) {
                    $this->params->parse_uri($uri, $this->request->request_uri);
                    if (is_array($this->routes[$this->request->request_method][$uri])) {
                        return call_user_func_array($this->routes[$this->request->request_method][$uri], array($this->request, $this->params));
                    } else {
                        return $this->routes[$this->request->request_method][$uri]($this->request, $this->params);
                    }
                }
            }

            throw new NotFoundException("No route found for: " . $this->request->request_uri);
        } else {
            throw new NotFoundException("No routes found for HTTP " . $this->request->request_method . ".");
        }
    }
}
?>
