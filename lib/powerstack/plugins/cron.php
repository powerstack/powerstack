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
* Cron
* Cron class for Powerstack
*
* Uses PCNTL FORK this class will not work on windows.
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Plugins
*/
namespace Powerstack\Plugins;

class Cron {
    /**
    * Run
    * Run all cron hooks
    *
    * @return void
    */
    function run() {
        if (strtolower(PHP_OS) != 'linux') {
            throw new PluginException("Cron only works on Linux");
        }

        $hooks = registry('hooks');
        $basedir = registry('BASEDIR');

        $pid = pcntl_fork();

        if ($pid) {
            pcntl_waitpid($pid, $status, WUNTRACED);

            if ($status > 0) {
                if (!file_exists($basedir . 'logs')) {
                    @mkdir($basedir . 'logs');
                }

                $error = "[" . date('Y-m-d H:i:s') . "] ERROR: CRON exited with status, " . $status . "\n";
                $logpath = $basedir . 'logs/cron.' . date('Y-m-d') . '.log';

                if (!file_exists($log_path)) {
                    Powerstack\Core\Filesystem::writeFile($logpath, $error);
                } else {
                    Powerstack\Core\Filesystem::appendFile($logpath, $error);
                }
            } else if ($status  == 0) {
                $error = "[" . date('Y-m-d H:i:s') . "] Successful Cron Run \n";
                $logpath = $basedir . 'logs/cron.' . date('Y-m-d') . '.log';

                if (!file_exists($log_path)) {
                    Powerstack\Core\Filesystem::writeFile($logpath, $error);
                } else {
                    Powerstack\Core\Filesystem::appendFile($logpath, $error);
                }
            }
        } else {
            if ($hooks->exists('cron')) {
                $cronhooks = $hooks->get('cron');

                if (!empty($cronhooks)) {
                    foreach ($cronhooks as $hook) {
                        if (is_array($hook)) {
                            call_user_func($hook);
                        } else {
                            $hook();
                        }
                    }
                }

                exit(0);
            } else {
                exit(0);
            }
        }
    }
}
?>
