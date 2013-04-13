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

class Database {
    private $conf;
    private $db;
    private $connected = false;

    function __construct() {
        $conf = config('plugins');

        if (!isset($conf->database)) { 
            throw new \Exception("Please configure the database in config.xml");
        }

        $this->conf = $conf->database;
    }

    function connect() {
        global $hooks;
        try {
            $dsn = $this->buildDsn();

            if ($this->conf->driver == 'sqlite') {
                $this->db = new \PDO($dsn);
            } else {
                $this->db = new \PDO($dsn, $this->conf->user, $this->conf->pass);
            }

            $connhooks = $hooks->get('database_connected');

            if (!empty($connhooks)) {
                foreach ($connhooks as $function) {
                    $function($this->db);
                }
            }

            $this->connected = true;

        } catch (\PDOException $e) {
            throw $e;
        }
    }

    function isConnected() {
        return $this->connected;
    }

    function executeSql($sql, $params=array()) {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    function select($table, $where=array(), $like=array(), $order=array(), $limit=null, $offset=null) {
        $sql = "SELECT * FROM " . $table;
        $params = array();

        if (!empty($where)) {
            $sql .= " WHERE ";

            foreach ($where as $field => $value) {
                if (is_array($value)) {
                    $sql .= $field . " IN (";

                    foreach ($value as $val) {
                        $sql .= "?,";
                        $params[] = $val;
                    }

                    $sql .= rtrim($sql, ",") . ") AND ";
                } else {
                    $sql .= $field . "=? AND ";
                    $params[] = $value;
                }
            }

            $sql = rtrim($sql, " AND ");
        }

        if (!empty($like)) {
            $sql .= (!empty($where)) ? " AND " : "";

            foreach ($like as $field => $value) {
                $sql .= $field . " LIKE %?% AND ";
                $params[] = $value;
            }

            $sql = rtrim($sql, " AND ");
        }

        if (!empty($order)) {
            $sql .= " ORDER BY ";

            foreach ($order as $field => $value) {
                $sql .= $field . " " . $value . ", ";
            }

            $sql = rtrim($sql, ", ");
        }

        if (!is_null($limit)) {
            $sql .= " LIMIT " . (int) $limit;
        }

        if (!is_null($offset)) {
            $sql .= " OFFSET " . (int) $offset;
        }

        return $this->executeSql($sql, $params);
    }

    function insert($table, $record) {
        $sql = "INSERT INTO " . $table . "(";
        $params = array();

        foreach (array_keys($record) as $field) {
            $sql .= $field . ", ";
        }

        $sql = rtrim($sql, ", ");

        $sql .= ") VALUES (";

        foreach (array_values($record) as $value) {
            $sql .= "?, ";
            $params[] = $value;
        }

        $sql = rtrim($sql, ", ");
        return $this->executeSql($sql, $params);
    }

    function update($table, $record, $where) {
        $sql = "UPDATE " . $table . " SET ";
        $params = array();

        foreach ($record as $field => $value) {
            $sql .= $field . "=?, ";
            $params[] = $value;
        }

        $sql = rtrim($sql, ", ");

        $sql .= " WHERE ";

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                $sql .= $field . " IN (";

                foreach ($value as $val) {
                    $sql .= "?,";
                    $params[] = $val;
                }

                $sql .= rtrim($sql, ",") . ") AND ";
            } else {
                $sql .= $field . "=? AND ";
                $params[] = $value;
            }
        }

        $sql = rtrim($sql, " AND ");
        return $this->executeSql($sql, $params);
    }

    function delete($table, $where) {
        $sql = "DELETE FROM " . $table . " WHERE ";
        $params = array();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                $sql .= $field . " IN (";

                foreach ($value as $val) {
                    $sql .= "?,";
                    $params[] = $val;
                }

                $sql .= rtrim($sql, ",") . ") AND ";
            } else {
                $sql .= $field . "=? AND ";
                $params[] = $value;
            }
        }

        $sql = rtrim($sql, " AND ");
        return $this->executeSql($sql, $params);
    }

    private function buildDsn() {
        if (!in_array($this->conf->driver, \PDO::getAvailableDrivers())) {
            throw new \Exception("PDO driver: " . $this->conf->driver . " is not installed.");
        }

        if (!in_array($this->conf->driver, array('mysql', 'sqlite', 'pgsql'))) {
            throw new \Exception("Database is not supported. MySQL, SQLite and PostgreSQL are aupported");
        }

        switch ($this->conf->driver) {
            case 'mysql':
                $dsn = 'mysql:host='.$this->conf->host.';dbname='.$this->conf->name;
                $dsn .= (isset($this->conf->port)) ? ';port='.$this->conf->port : ';port=3306';
                return $dsn;
                break;

            case 'pgsql':
                $dsn = 'pgsql:host='.$this->conf->host.';dbname='.$this->conf->name;
                $dsn .= (isset($this->conf->port)) ? ';port='.$this->conf->port : ';port=5432';
                return $dsn;
                break;

            case 'sqlite':
                $dsn = 'sqlite:'.$this->conf->name;
                return $dsn;
                break;
        }
    }
}
?>
