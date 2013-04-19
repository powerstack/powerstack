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
* Recaptcha
* Recaptcha class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Plugins
*/

namespace Powerstack\Plugins\Captcha;
require_once(dirname(__FILE__) . '/lib/recaptcha/recaptchalib.php');

class Recaptcha {
    /**
    * @access private
    * @var stdclass
    */
    private $conf;

    /**
    * @access private
    * @var ReCaptchaResponse
    */
    private $error = null;

    /**
    * __construct
    * Create a new Powerstack\Plugins\Captcha\Recaptcha object
    *
    * Configuration:
    *   app/config.xml:
    *       <plugins>
    *           <recaptcha>
    *               <publickey>[recaptcha public key]</publickey>
    *               <privatekey>[recaptcha private key]</privatekey>
    *           </recaptcha>
    *       </plugins>
    */
    function __construct() {
        $conf = config('plugins');

        if (!isset($conf->recaptcha)) {
            throw new \Exception("Please configure the recaptcha plugin in config.xml");
        }

        $this->conf = $conf->recaptcha;

        if (!isset($this->conf->ssl)) {
            $this->conf->ssl = false;
        }
    }

    /**
    * Get
    * Get the captcha html
    *
    * @access public
    * @return string HTML for recaptcha
    */
    function get() {
        return \recaptcha_get_html($this->conf->publickey, $this->error, $this->conf->ssl);
    }

    /**
    * Check
    * Check validates the input to recaptcha
    *
    * @access public
    * @return bool true on success, false otherwise
    */
    function check() {
        $app = registry('app');
        $resp = \recaptcha_check_answer(
            $this->conf->privatekey,
            $app->request->remote_address,
            $app->params->recaptcha_check_answer,
            $app->params->recaptcha_response_field
        );

        if (!$resp->valid) {
            $this->error = $resp->error;
            return false;
        } else {
            $this->error = null;
            return true;
        }
    }
}
?>
