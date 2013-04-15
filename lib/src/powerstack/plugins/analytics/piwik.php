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
* Piwik
* Piwik class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Plugins
*/
namespace Powerstack\Plugins\Analytics;

class Piwik {
    /**
    * @access private
    * @var stdclass
    */
    private $conf;

    /**
    * __construct
    * Create a new Powerstack\Plugins\Analytics\Piwik object
    */
    function __construct() {
        $conf = config('plugins');
        $this->conf = $conf->piwik;

        if (!isset($this->conf)) {
            throw new \Exception("Please configure Piwik in config.xml");
        }
    }

    /**
    * Get
    * Get the javscript tracking code for you site
    *
    * @access public
    * @return string javascript tracking code for piwik
    */
    function get() {
        $js  = '<!-- Piwik -->'."\n";
        $js .= '<script type="text/javascript">'."\n";
        $js .= "\t".'var _paq = _paq || [];'."\n";
        $js .= "\t".'_paq.push(["trackPageView"]);'."\n";
        $js .= "\t".'_paq.push(["enableLinkTracking"]);'."\n";
        $js .= "\t".'(function() {'."\n";
        $js .= "\t\t".'var u=(("https:" == document.location.protocol) ? "https" : "http") + "://' . $this->conf->domain . '/";'."\n";
        $js .= "\t\t".'_paq.push(["setTrackerUrl", u+"piwik.php"]);'."\n";
        $js .= "\t\t".'_paq.push(["setSiteId", "'. $this->conf->siteid .'"]);'."\n";
        $js .= "\t\t".'var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";'."\n";
        $js .= "\t\t".'g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);'."\n";
        $js .= "\t".'})();'."\n";
        $js .= '</script>'."\n";
        $js .= '<!-- End Piwik Code -->'."\n";
        return $js;
    }
}
?>
