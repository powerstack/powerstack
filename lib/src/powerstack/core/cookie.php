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

class Cookie {
    /**
    * @access private
    * @var string
    */
    private $cookie_name;

    /**
    * @access private
    * @var int
    */
    private $expire;

    /**
    * @access private
    * @var string
    */
    private $path;

    /**
    * @access private
    * @var string
    */
    private $domain;

    /**
    * @access private
    * @var bool
    */
    private $secure;

    /**
    * @access private
    * @var bool
    */
    private $httponly;

    /**
    * __construct
    * Create a new Powerstack\Core\Cookie object
    */
    function __construct() {
        $defaults = array(
            'cookie_name' => "powerstack",
            'expire' => 3600,
            'path' => "/",
            'domain' => $_SERVER['SERVER_NAME'],
            'secure' => false,
            'httponly' => false,
        );

        foreach ($defaults as $key => $value) {
            $conf = config('settings');

            if (!empty($conf) && isset($conf->cookie->{$key})) {
                $this->{$key} = $conf->cookie->{$key};
            } else {
                $this->{$key} = $value;
            }
        }
    }

    /**
    * Get
    * Get the value of a cookie
    *
    * @access public
    * @param string $name   Name of cookie to get
    * @return mixed null if cookie doesn't exist or the value if it does exist
    */
    function get($name) {
        $cookie = json_decode($_COOKIE[$this->cookie_name]);

        if (isset($cookie[$name])) {
            return $cookie[$name];
        }

        return null;
    }

    /**
    * Set
    * Set a cookie
    *
    * @access public
    * @link http://php.net/set_cookie
    * @param string $name       Name of cookie
    * @param string $value      Value of cookie
    * @param int    $expire     The time the cookie expires. (optional, default is an hour from creation)
    * @param string $path       The path on the server in which the cookie will be available on.  (optional, default is /)
    * @param string $domian     The domain that the cookie is available to. (optional, default is '')
    * @param bool   $secure     Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client. (optional, default is false)
    * @param bool   $httponly   When TRUE the cookie will be made accessible only through the HTTP protocol. (optional, default is false)
    * @return bool true or false based on success
    */
    function set($name, $value, $expire=3600, $path='/', $domain='', $secure=false, $httponly=false) {
        if (isset($_COOKIE[$this->cookie_name])) {
            $cookie = json_decode($_COOKIE[$this->cookie_name]);
            $cookie[$name] = $value;

            return setcookie($this->cookie_name, json_encode($cookie), (time() + $expire), $path, $domain, $secure, $httponly);
        } else {
            $cookie = array();
            $cookie[$name] = $value;

            return setcookie($this->cookie_name, json_encode($cookie), (time() + $expire), $path, $domain, $secure, $httponly);
        }
    }
}
?>
