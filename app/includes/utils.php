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
* @subpackage App
*/


/**
* Config
* Gets a config information from the global $config variable
*
* @see Powerstack\Core\Config
* @param string $name   Name of config variable to get or set.
* @param mixed  $value  Value to set config variable to. (optional, only use to set a config variable)
* @return mixed Config variable value
*/
function config($name, $value = null) {
    $config = registry('config');

    if (is_null($value)) {
        return $config->{$name};
    } else {
        $config->{$name} = $value;
        return $config->{$name};
    }
}

/**
* Template
* Render and display a template
* 
* @see Powerstack\Core\TemplateFactory
* @param string $tpl        Template to render
* @param array  $params     Array of key => value template replacements. (optional)
* @param string $layout     Name of layout to use. (optional, the default is the default layout)
* @return void
*/
function template($tpl, $params = array(), $layout = 'default') {
    $template = registry('template');
    $template->init();
    echo $template->render($tpl, $params, $layout);
}

/**
* Session
* Get or set a session
*
* @see Powerstack\Core\SessionFactory
* @param string $name   Name of session to get or set.
* @param mixed  $value  Value to set session to. (optional, only use if setting a session)
* @return mixed true if setting a session, if getting a session it will return the value or false if no session exists
*/
function session($name, $value = null) {
    $app = registry('app');

    if (is_null($value)) {
        return $app->request->session->get($name);
    } else {
        return $app->request->session->set($name, $value);
    }
}

/**
* Cookie
* Get or set a cookie
*
* @see Powerstack\Core\Cookie
* @param string $name       Name of cookie to get or set.
* @param string $value      Value to set cookie to. (optional, only use if setting a cookie)
* @param int    $expires    Length of time a cookie is valid for in seconds. (optional, default is 3600)
* @param string $path       The path on the server in which the cookie will be available on. (optional, default is /)
* @param string $domain     The domain that the cookie is available to. (optional, default is '')
* @param bool   $secure     Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client. (optional, default is false)
* @param bool   $httponly   When TRUE the cookie will be made accessible only through the HTTP protocol.(optional, default is false)
* @return mixed cookie value if getting cookie or true/false when setting a cookie
*/
function cookie($name, $value = null, $expires = 3600, $path='/', $domain='', $secure=false, $httponly=false) {
    $app = registry('app');

    if (is_null($value)) {
        return $app->request->cookie->get($name);
    } else {
        return $app->request->cookie->set($name, $value, $expires, $path, $domain, $secure, $httponly);
    }
}

/**
* Hook
* Register a hook.
*
* @see Powerstack\Core\Hooks
* @param string     $name       Name of hook your registering
* @param callback   $function   Callback function that is executed when the hook is run
* @return void
*/
function hook($name, $function) {
    $hooks = registry('hooks');
    $hooks->register($name, $function);
}

/**
* Registry
* Get or set item in the registry
*
* @see Powerstack\Core\Registry
* @param string $name   Name of item to get or set
* @param mixed  $value  Value of item to set. (optional, only use when setting a item)
* @return mixed value or null if getting item, void on set
*/
function registry($name, $value=null) {
    $registry = Powerstack\Core\Registry::getInstance();

    if (is_null($value)) {
        return $registry->get($name);
    }

    return $registry->set($name, $value);
}

/**
* Redirect
* Redirect user to another page.
*
* @param string $url     Url to rediret to.
*/
function redirect($url) {
    header('Location: ' . $url);
    exit;
}
?>
