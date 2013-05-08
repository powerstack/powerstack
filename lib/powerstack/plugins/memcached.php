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
* Memcached
* Memcached wrapper for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Plugins
*/

namespace Powerstack\Plugins;

class Memcached {
    /**
    * @access private
    * @var stdclass
    */
    private $conf;

    /**
    * @access private
    * @var Memcached
    */
    private $memcached;

    /**
    * __construct
    * Create a new Powerstack\Plugins\Memcached object
    *
    * Configuration:
    *   app/config.yml:
    *       plugins:
    *           memcached:
    *               servers: [Memcached servers (host:port[,host:port])]
    *
    */
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

    /**
    * Add Server
    * Add a server to the server pool
    *
    * @access public
    * @param string $host   Hostname/IP of memcached server
    * @param int    $port   Port memcached is running on
    * @param int    $weight Server weight. (optional, default is 0)
    * @return bool true on success, false otherwise
    */
    function addServer($host, $port, $weight=0) {
        return $this->memcached->addServer($host, $port, $weight);
    }

    /**
    * Add Servers
    * Add multiple servers to the pool
    *
    * @access public
    * @param array $servers     Array of server connection details
    * @return bool true on success, false otherwise
    */
    function addServers($servers) {
        return $this->memcached->addServers();
    }

    /**
    * Add
    * Add an item to memcached
    *
    * @access public
    * @param string $key    Key to store item under
    * @param mixed  $value  Value to store
    * @param int    $expire How long is the item valid for. (optional, default is 0)
    * @return bool true on success, false otherwise
    */
    function add($key, $value, $expire=0) {
        return $this->memcached->add($key, $value, $expire);
    }

    /**
    * Append
    * Append a string to a item already stored
    *
    * @access public
    * @param string $key    Key item is stored under
    * @param string $value  String to append
    * @return bool true on success, false otherwise
    */
    function append($key, $value) {
        return $this->memcached->append($key, $value);
    }

    /**
    * Delete
    * Delete an item from memcached
    *
    * @access public
    * @param string $key    Key to delete
    * @param int    $time   How long to wait before deleting item. (optional, default is 0)
    * @return bool true on success, false otherwise
    */
    function delete($key, $time=0) {
        return $this->memcached->delete($key, $time);
    }

    /**
    * Delete Multi
    * Delete multiple items from memcached
    *
    * @access public
    * @param array  $key    Array of keys to delete
    * @param int    $time   How long to wait before deleting item. (optional, default is 0)
    * @return bool true on success, false otherwise
    */
    function deleteMulti($keys, $time=0) {
        return $this->memcached->deleteMulti($keys, $time);
    }

    /**
    * Flush
    * Flush all values from memcached
    *
    * @access public
    * @param int $delay     Time to wait before flushing memcached. (optional, default is 0)
    * @return bool true on success, false otherwise
    */
    function flush($delay=0) {
        return $this->memcached->flush($delay);
    }

    /**
    * Get
    * Get an item from memcached
    *
    * @access public
    * @param string $key    Key to get
    * @return mixed value of key on success, false otherwise
    */
    function get($key) {
        return $this->memcached->get($key);
    }

    /**
    * Get All Keys
    * Gets the keys stored on all the servers
    *
    * @access public
    * @return array keys stored on all the servers on success, false otherwise
    */
    function getAllKeys() {
        return $this->memcached->getAllKeys();
    }

    /**
    * Get Multi
    * Get multiple items from memcached
    *
    * @access public
    * @param array $key    Array of keys to get
    * @return mixed array of found values on success, false otherwise
    */
    function getMulti($keys) {
        return $this->memcached->getMulti($keys);
    }

    /**
    * Get Result Code
    * Return the result code of the last operation
    *
    * @access public
    * @return int code of the last Memcached operation
    */
    function getResultCode() {
        return $this->memcached->getResultCode();
    }

    /**
    * Get Result Message
    * Return the message describing the result of the last operation
    *
    * @access public
    * @return string message describing the result of the last Memcached operation
    */
    function getResultMessage() {
        return $this->memcached->getResultCode();
    }

    /**
    * Replace
    * Replace the item under an existing key
    *
    * @access public
    * @param string $key    Key to replace
    * @param mixed  $value  Value to replace with
    * @param int    $expire How long the item is valid for. (optional, default is 0)
    * @return bool true on success, false otherwise
    */
    function replace($key, $value, $expire=0) {
        return $this->memcached->replace($key, $value, $expire);
    }

    /**
    * Set
    * Store an item
    *
    * @access public
    * @param string $key        Key to dtore item under
    * @param mixed  $value      Value of item
    * @param int    $expire     How long the item s valid for. (optional, default is 0)
    * @return bool true on success, false otherwise
    */
    function set($key, $value, $expire=0) {
        return $this->memcached->set($key, $value, $expire);
    }

    /**
    * Set Multi
    * Store multiple items
    *
    * @access public
    * @param array  $items      Key => value array of items to be stored
    * @param int    $expire     How long items are valid for. (optional, default is 0)
    * @return bool true on success, false otherwise
    */
    function setMulti($items, $expire=0) {
        return $this->memcached->setMulti($items, $expire);
    }
}
?>
