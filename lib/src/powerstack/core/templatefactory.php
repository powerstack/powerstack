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
use Powerstack\Plugins\Template;

class TemplateFactory {
    private $template;

    function __construct() {
        $conf = config('template');
        $engine = $conf->engine;

        if ($engine != 'simple') {
            $class = 'Template'.ucfirst($engine);

            if (class_exists($class)) {
                $this->template = new $class();
            } else {
                throw new \Exception("Could not find template engine: " . $engine . ", no " . $class . " class was found");
            }
        } else {
            $this->template = new TemplateSimple();
        }
    }

    function init() {
        return $this->template->init();
    }

    function render($template, $params=array(), $layout='default') {
        return $this->template->render($template, $params, $layout);
    }
}
?>
