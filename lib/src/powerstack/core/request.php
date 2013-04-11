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

class Request {
    public $request_method;
    public $request_uri;
    public $https;
    public $remote_address;
    public $http_referer;
    public $user_agent;
    public $query_string;
    public $base_uri;
    public $cookie;
    public $session;

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
