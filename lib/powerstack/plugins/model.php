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
* Model
* Model class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Plugins
*/
namespace Powerstack\Plugins;

class Model {
    /**
    * The sql query that represents a model
    * @access private
    * @var string
    */
    private $query = null;

    /**
    * Powerstack\Plugins\Database object
    * @access private
    * @var Powerstack\Plugin\Database
    */
    private $db;

    /**
    * __construct()
    * Create a new instance of Powerstack\Plugins\Model
    *
    * Configuration:
    *   app/config.yml:
    *       settings:
    *           modelsdir: app/models
    *
    * @throws Powerstack\Core\Exception
    */
    function __construct() {
        $conf = registry('config');

        if (!isset($conf->settings->modelsdir)) {
            throw new PluginException("No models directory in config.yml");
        }

        if (!isset($conf->plugins->database)) {
            throw new PluginException("The model plugin depends on the database plugin");
        }

        if (empty($this->query)) {
            throw new PluginException("No query is defined for model");
        }

        $this->db = new Powerstack\Plugins\Database();

        spl_autoload_register(function($class) {
            $conf = registry('config');
            $path = realpath($conf->settings->modelsdir) . '/' . strtolower($class) . '.php';

            if (file_exists($path)) {
                require_once($path);
            }
        });
    }

    /**
    * Find One
    * Find one record
    *
    * @access public
    * @param array  $where  Array of where clause info. eg array('id' => 1, 'name' => 'jim', 'id' => array(1,2,3,4), 'created' => array('op' => 'gt', 'value' => 13056784))
    * @return mixed object of row otherwise false.
    */
    function findOne($where) {
        $whereinfo = $this->processWhere($where);
        $query = $this->query . $whereinfo['sql'];

        $result = $this->db->executeSql($query, $whereinfo['params']);

        if (!$result) {
            return false;
        }

        return $this->db->fetch();
    }

    /**
    * Find Many
    * Find many records
    *
    * @access public
    * @param array  $where  Array of where clause info. eg array('id' => 1, 'name' => 'jim', 'id' => array(1,2,3,4), 'created' => array('op' => 'gt', 'value' => 13056784))
    * @param array  $order  Array of order by info. eg array('id' => 'desc')
    * @param int    $limit  Limit records
    * @param int    $offset Offset records
    * @return mixed object of row otherwise false.
    */
    function findMany($where, $order = array(), $limit = null, $offset = null) {
        $whereinfo = $this->processWhere($where);
        $query = $this->query . $whereinfo['sql'];

        if (!empty($order)) {
            $ordersql = $this->processOrder($order);
            $query .= $ordersql;
        }

        if (!is_null($limit)) {
            $limitsql = $this->processLimit($limit);
            $query .= $limitsql;
        }

        if (!is_null($offset)) {
            $offsetsql = $this->processOffset($offset);
            $query .= $offsetsql;
        }

        $result = $this->db->executeSql($query, $whereinfo['params']);

        if (!$result) {
            return false;
        }

        return $this->db->fetchAll();
    }

    /**
    * All
    * Get all records
    *
    * @access public
    * @return mixed array of all records otherwise false
    */
    function all() {
        $result = $this->db->executeSql($this->query);

        if (!$result) {
            return false;
        }

        return $this->db->fetchAll();
    }

    /**
    * Process Where
    * Process array with where info.
    *
    * @access private
    * @param array $where   Array of where clause info.
    * @return array with sql string and params array.
    */
    private function processWhere($where) {
        $sql = "";
        $params = array();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                if (isset($value['op']) && isset($value['value'])) {
                    $op = strtolower($value['op']);
                    $sql .= $field;

                    switch ($op) {
                        case 'gt':
                            $sql .= ">";
                            break;

                        case 'lt':
                            $sql .= "<";
                            break;

                        case 'ne':
                            $sql .= "<>";
                            break;

                        case 'eq':
                            $sql .= "=";
                            break;

                        case 'like':
                            $sql .= 'LIKE';
                            break;
                    }

                    $sql .= "? AND ";

                    if ($op == 'like') {
                        $params[] = '%'.$value['value'].'%';
                    } else {
                        $params[] = $value['value'];
                    }
                } else {
                    $sql .= $field . " IN(";

                    foreach ($value as $val) {
                        $sql .= "?,";
                        $params[] = $val;
                    }

                    $sql = rtrim($sql, ",") . ") AND ";
                }
            } else {
                $sql .= $field . "=? AND ";
                $params[] = $value;
            }
        }

        $sql = rtrim($sql, " AND ");
        $query = strtolower($this->query);

        if (strpos($query, 'where') !== false) {
            return array(
                'sql' => " AND " . $sql,
                'params' => $params,
            );
        } else {
            return array(
                'sql' => " WHERE " . $sql,
                'params' => $params,
            );
        }
    }

    /**
    * Process Order
    * Process order array
    *
    * @access private
    * @param array  $order  Array of order by info
    * @return string order sql string
    */
    private function processOrder($order) {
        $sql = " ORDER BY ";

        foreach ($order as $field => $value) {
            $sql .= $field . " " . strtoupper($value) . ",";
        }

        $sql = rtrim($sql, ",");
        return $sql;
    }

    /**
    * Process Limit
    *
    * @access private
    * @param int $limit Limit records
    * @return string limit sql string
    */
    private function processLimit($limit) {
        return " LIMIT " . (int) $limit;
    }

    /**
    * Process Offset
    *
    * @access private
    * @param int $offset Offset records
    * @return string offset sql string
    */
    private function processOffset($offset) {
        return " OFFSET " . (int) $offset;
    }
}
?>
