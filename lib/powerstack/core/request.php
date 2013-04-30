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
* Request
* Request class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/
namespace Powerstack\Core;

class Request {
    /**
    * Request Method
    *
    * @access public
    * @var string
    */
    public $request_method;

    /**
    * Request URI
    *
    * @access public
    * @var string
    */
    public $request_uri;

    /**
    * HTTPS
    *
    * @access public
    * @var bool
    */
    public $https;

    /**
    * Remote Address
    *
    * @access public
    * @var string
    */
    public $remote_address;

    /**
    * HTTP Referer
    *
    * @access public
    * @var string
    */
    public $http_referer;

    /**
    * User Agent
    *
    * @access public
    * @var string
    */
    public $user_agent;

    /**
    * Query String
    *
    * @access public
    * @var string
    */
    public $query_string;

    /**
    * Base Uri
    *
    * @access public
    * @var string
    */
    public $base_uri;

    /**
    * Powerstack\Core\Cookie object
    *
    * @access public
    * @var Powerstack\Core\Cookie
    */
    public $cookie;

    /**
    * Powerstack\Core\Session object
    *
    * @access public
    * @var Powerstack\Core\SessionFactory
    */
    public $session;

    /**
    * __construct
    * Create a new Powerstack\Core\Request object
    */
    function __construct() {
        $this->request_method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->query_string = $_SERVER['QUERY_STRING'];
        $this->http_referer = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
        $this->http_user_agent = $_SERVER['HTTP_USER_AGENT'];
        $this->https = empty($_SERVER['HTTPS']) ? false : true;
        $this->remote_address = $_SERVER['REMOTE_ADDR'];
        $this->request_uri = $this->get_requesturi();
        $this->base_uri = $_SERVER['SERVER_NAME'];
        $this->cookie = new Cookie();
        $this->session = new SessionFactory();
    }

    /**
    * Get Request Uri
    * Get the requet uri either for $_SERVER['REQUEST_URI'] or $_GET['q']
    *
    * @access protected
    * @return string the request uri
    */
    protected function get_requesturi() {
        $uri = empty($_GET['q']) ? $_SERVER['REQUEST_URI'] : $_GET['q'];

        if (strpos($uri, '?') !== false) {
            $parts = explode('?', $uri);
            $uri = $parts[0];
        }

        return $uri;
    }
}
?>
