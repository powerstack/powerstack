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
namespace Powerstack\Plugins\Template;

define('TWIGPATH', dirname(__FILE__) . '/lib/twig/lib/Twig/');
require_once(TWIGPATH . 'Autoloader.php');

class TemplateTwig {
    private $twig;
    private $conf;
    private $viewsdir;

    function init() {
        $this->conf = config('template');
        $this->viewsdir = rtrim($this->conf->viewsdir, '/') . '/';
        Twig_Autoloader::register();

        if (!file_exists($this->viewsdir)) {
            throw new \Exception("View directory: " . $this->viewsdir . ", could not be found.");
        }

        $loader = new Twig_Loader_Filesystem($this->viewsdir);
        $this->twig = new Twig_Environment($loader, array(
            'cache' => (isset($this->conf->cachedir)) ? $this->conf->cachedir : '/tmp/twigcache',
        ));
    }

    function render($tpl, $params=array(), $layout='default') {
        global $hooks;

        if (!file_exists($this->viewsdir . $tpl)) {
            throw new \Exception("Template: " . $tpl . ", was not found in " . $this->viewsdir);
        }

        if (!isset($params['SITENAME'])) {
            $conf = config('application');
            $params['SITENAME'] = $conf->name;
        }

        $prerender = $hooks->get('before_template_render');

        if (!empty($prerender)) {
            foreach ($prerender as $function) {
                $params = $function($params);
            }
        }

        $template = $this->twig->render($this->viewsdir . $tpl, $params);
        $params['CONTENT'] = $template;

        $layout = $this->twig->render($this->conf->layouts->{$layout}->file, $params);
        return $layout;
    }
}
?>
