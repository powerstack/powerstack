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
define('BASE_DIR', dirname(__FILE__) . '/');
define('APP_DIR', dirname(__FILE__) . '/app/');

require_once(BASE_DIR . 'autoloader.php');
require_once(APP_DIR . 'includes/utils.php');

use Powerstack\Core\Config;
use Powerstack\Core\Application;
use Powerstack\Core\Hooks;
use Powerstack\Core\TemplateFactory;
use Powerstack\Core\Registry;

registry('BASEDIR', BASEDIR);
registry('APPDIR', APPDIR);

$config = new Config(APP_DIR . 'config.xml');
registry('config', $config);

$app = new Application($config);

$hooks = new Hooks();
registry('hooks', $hooks);

$template = new TemplateFactory();
registry('template', $template);

require_once(APP_DIR . 'app.php');

registry('app', $app);
$app->run();
?>
