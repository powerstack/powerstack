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
namespace Powerstack\Plugins;

class Memcached {
    private $conf;
    private $memcached;

    function __construct() {
        $conf = config('plugins');

        if (!isset($conf->memcached)) {
            $this->conf = (object) array(
                'servers' => '127.0.0.1:11211',
            );
        } else {
            $this->conf = $conf->memcached;
        }

        $this->memcached = new \Memcached();

        if (strpos($this->conf->servers, ',')) {
            $servers = explode(',', $this->conf->servers);
        } else {
            $servers = array($this->conf->servers);
        }

        foreach ($servers as $server) {
            $parts = explode(':', $server);
            $this->addServer($parts[0], $parts[1]);
        }
    }

    function addServer($host, $port, $weight=0) {
        return $this->memcached->addServer($host, $port, $weight);
    }

    function addServers($servers) {
        return $this->memcached->addServers();
    }

    function add($key, $value, $expire=0) {
        return $this->memcached->add($key, $value, $expire);
    }

    function append($key, $value) {
        return $this->memcached->append($key, $value);
    }

    function delete($key, $time=0) {
        return $this->memcached->delete($key, $time);
    }

    function deleteMulti($keys, $time=0) {
        return $this->memcached->deleteMulti($keys, $time);
    }

    function flush($delay=0) {
        return $this->memcached->flush($delay);
    }

    function get($key) {
        return $this->memcached->get($key);
    }

    function getAllKeys() {
        return $this->memcached->getAllKeys();
    }

    function getMulti($keys) {
        return $this->memcached->getMulti($keys);
    }

    function replace($key, $value, $expire=0) {
        return $this->memcached->replace($key, $value, $expire);
    }

    function set($key, $value, $expire=0) {
        return $this->memcached->set($key, $value, $expire);
    }

    function setMulti($items, $expire=0) {
        return $this->memcached->setMulti($items, $expire);
    }
}
?>
