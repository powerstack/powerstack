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
* Template Factory
* Template Factory class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/

namespace Powerstack\Core;

class TemplateFactory {
    /**
    * @access private
    * @var Template class
    */
    private $template;

    /**
    * __construct
    * Create a new Powerstack\Core\TemplateFactory object
    */
    function __construct() {
        $conf = config('template');
        $engine = $conf->engine;

        if ($engine != 'simple') {
            $class = 'Powerstack\Plugins\Template\Template'.ucfirst($engine);

            if (class_exists($class)) {
                $this->template = new $class();
            } else {
                throw new \Exception("Could not find template engine: " . $engine . ", no " . $class . " class was found");
            }
        } else {
            $this->template = new TemplateSimple();
        }
    }

    /**
    * Init
    * Initalize the template class
    *
    * @access public
    * @return bool true on success, false otherwise
    */
    function init() {
        return $this->template->init();
    }

    /**
    * Render
    * Render a template
    *
    * @access public
    * @param string $tpl    Template to render
    * @param array  $params A key => value array of template replacement vars. (optional, default empty array)
    * @param string $layout Layout to use. (optional, default is default layout)
    * @return string template
    */
    function render($template, $params=array(), $layout='default') {
        return $this->template->render($template, $params, $layout);
    }
}
?>
