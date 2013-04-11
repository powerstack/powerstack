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
function config($name, $value = null) {
    global $config;

    if (is_null($value)) {
        return $config->{$name};
    } else {
        $config->{$name} = $value;
        return $config->{$name};
    }
}

function template($tpl, $params = array(), $layout = 'default') {
    global $template;
    $template->init();
    echo $template->render($tpl, $params, $layout);
}

function session($name, $value = null) {
    global $app;

    if (is_null($value)) {
        return $app->request->session->get($name);
    } else {
        return $app->request->session->set($name, $value);
    }
}

function cookie($name, $value = null, $expires = 3600, $path='/', $domain='', $secure=false, $httponly=false) {
    global $app;

    if (is_null($value)) {
        return $app->request->cookie->get($name);
    } else {
        return $app->request->cookie->set($name, $value, $expires, $path, $domain, $secure, $httponly);
    }
}

function hook($name, $function) {
    global $hooks;
    $hooks->register($name, $function);
}
?>
