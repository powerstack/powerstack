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
* Error Handler
* Error Handler class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/
namespace Powerstack\Core;

class ErrorHandler {
    /**
    * Handler
    * Handle errors
    *
    * @access public
    * @param int    $errno      Error Number
    * @param string $errstr     Error message
    * @param string $errfile    File where error happened
    * @param int    $errline    Line in file where error happened
    * @return mixed void if not needed or true
    */
    function handler($errno, $errstr, $errfile, $errline) {
        if (error_reporting() == 0) {
            return;
        }

        $message = "";

        $errorType = array (
               E_ERROR              => 'ERROR',
               E_WARNING            => 'WARNING',
               E_PARSE              => 'PARSING ERROR',
               E_NOTICE             => 'NOTICE',
               E_CORE_ERROR         => 'CORE ERROR',
               E_CORE_WARNING       => 'CORE WARNING',
               E_COMPILE_ERROR      => 'COMPILE ERROR',
               E_COMPILE_WARNING    => 'COMPILE WARNING',
               E_USER_ERROR         => 'USER ERROR',
               E_USER_WARNING       => 'USER WARNING',
               E_USER_NOTICE        => 'USER NOTICE',
               E_STRICT             => 'STRICT NOTICE',
               E_RECOVERABLE_ERROR  => 'RECOVERABLE ERROR'
        );


        $message .= empty($errorType[$errno]) ? '' : $errorType[$errno] . ': ';
        $message .= $errstr . ' in ' . $errfile . ' on line ' . $errline;

        $this->log("[" . date('Y-m-d H:i:s') . "]" . $message . "\n");

        $conf = config('settings');
        if (isset($conf->debug) && $conf->debug == 1) {
            if (substr(php_sapi_name(), 0, 3) == 'cgi') {
                header("Status: 500 Internal Server Error");
            } else {
                header("HTTP/1.0 500 Internal Server Error");
            }

            template('powerstack/error.tpl', array('error' => $message));
            exit;
        }

        return true;
    }

    /**
    * Log
    * Log an error
    *
    * @access public
    * @param string $message    Error to log
    * @return void
    */
    function log($message) {
        $basedir = registry('BASEDIR');

        if (!file_exists($basedir . 'logs/')) {
            Filesystem::mkdir($basedir . 'logs/');
        }

        $date = date('Y-m-d');

        if (!file_exists($basedir . 'logs/error.' . $date . '.log')) {
            Filesystem::writeFile($basedir . 'logs/error.' . $date . '.log', $message);
        } else {
            Filesystem::appendFile($basedir . 'logs/error.' . $date . '.log', $message);
        }
    }
}
?>
