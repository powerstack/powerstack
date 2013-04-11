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
    private $cookie_name;
    private $expire;
    private $path;
    private $domain;
    private $secure;
    private $httponly;

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

    function get($name) {
        $cookie = json_decode($_COOKIE[$this->cookie_name]);

        if (isset($cookie[$name])) {
            return $cookie[$name];
        }

        return null;
    }

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
