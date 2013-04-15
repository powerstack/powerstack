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
* Template Simple
* Simple Template class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/

namespace Powerstack\Core;

class TemplateSimple {
    /**
    * @access private
    * @var string
    */
    private $viewsdir;

    /**
    * @access private
    * @var stdclass
    */
    private $conf;

    /**
    * Init
    * Initialize template engine
    *
    * Configuration:
    *   app/config.xml:
    *       <template>
    *           <engine>simple</engine>
    *           ...
    *       </template>
    *
    * @return bool true
    */
    function init() {
        $this->conf = config('template');
        $this->viewsdir = rtrim($this->conf->viewsdir, '/') . '/';
        return true;
    }

    /**
    * Render
    * Render a template
    *
    * Hook before_template_render, function takes the $params as an argument for
    * changing values on the fly, function must return the $params array.
    *
    * @access public
    * @param string $tpl    Template to render
    * @param array  $params A key => value array of template replacement vars. (optional, default empty array)
    * @param string $layout Layout to use. (optional, default is default layout)
    * @return string template
    */
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
            foreach ($prerender as $hook) {
                $params = $hook($params);
            }
        }

        $layout = file_get_contents($this->conf->layouts->{$layout}->file);
        $template = file_get_contents($this->viewsdir . $tpl);

        preg_match_all('#\{\{\s+?([a-zA-z0-9_\-]+)\s+?\}\}#', $template, $matches);

        if (!empty($matches)) {
            $count = count($matches[0]);

            for ($i = 0; $i < $count; $i++) {
                if (isset($params[$matches[1][$i]])) {
                    $template = preg_replace('#' . preg_quote($matches[0][$i]) . '#', $params[$matches[1][$i]], $template);
                }
            }
        }

        preg_match_all('#\{\{\s+?([a-zA-Z0-9_\-]+)\s+?\}\}#', $layout, $matches);

        if (!empty($matches)) {
            $count = count($matches[0]);

            for ($i = 0; $i < $count; $i++) {
                if (isset($params[$matches[1][$i]])) {
                    $layout = preg_replace('#' . preg_quote($matches[0][$i]) . '#', $params[$matches[1][$i]], $layout);
                }

                if ($matches[1][$i] == 'CONTENT') {
                    $layout = preg_replace('#' . preg_quote($matches[0][$i]) . '#', $template, $layout);
                }
            }
        }

        return $layout;
    }
}
?>
