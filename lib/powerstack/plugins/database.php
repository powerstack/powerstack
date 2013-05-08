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
* Database
* PDO database class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Plugins
*/

namespace Powerstack\Plugins;

class Database {
    /**
    * @access private
    * @var stdclass
    */
    private $conf;

    /**
    * @access private
    * @var PDO
    */
    private $db;

    /**
    * @access private
    * @var PDOStatment
    */
    private $statement;

    /**
    * @access private
    * @var bool
    */
    private $connected = false;

    /**
    * __construct
    * Create a new Powerstack\Plugins\Database object
    *
    * Configuration:
    *   app/config.yml:
    *       plugins:
    *           database:
    *               driver: [pgsql | mysql | sqlite]
    *               host: [Database Host]
    *               name: [Database Name]
    *               user: [Database Username]
    *               pass: [Database Password]
    *               port: [Database Port]
    *
    */
    function __construct() {
        $conf = config('plugins');

        if (!isset($conf->database)) {
            throw new Powerstack\Core\Exception("Please configure the database in config.yml");
        }

        $this->conf = $conf->database;
    }

    /**
    * Connect
    * Connect to the database
    *
    * Hook database_connected is called after a successful connection is made
    * to the database. It passes the a reference to the new PDO object.
    *
    * @access public
    * @throws PDOException
    */
    function connect() {
        $hooks = registry('hooks');
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
                    if (is_array($function)) {
                        call_user_func_array($function, array(&$this->db));
                    } else {
                        $function($this->db);
                    }
                }
            }

            $this->connected = true;

        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
    * Is Connected
    * Are you connected to the database
    *
    * @access public
    * @return bool true if connected, false otherwise
    */
    function isConnected() {
        return $this->connected;
    }

    /**
    * Execute Sql
    * Execute a sql command
    *
    * @access public
    * @param string $sql    Sql query to execute
    * @param array  $params Array of replacements values for the query. (optional)
    * @throws PDOException
    * @return bool true on success, false otherwise
    */
    function executeSql($sql, $params=array()) {
        if (!$this->connected) {
            $this->connect();
        }

        $this->statement = $this->db->prepare($sql);
        return $this->statement->execute($params);
    }

    /**
    * Select
    * Run a select query on the database
    *
    * @access public
    * @param string $table  Name of table to query
    * @param array  $where  Field => value array to be used as where statements. (optional)
    * @param array  $like   Field => value array to be used as like statements. (optional)
    * @param array  $order  Field => order array to be used as order by statement. (optional)
    * @param int    $limit  Limit number of records. (optional)
    * @param int    $offset Offset the reccords. (optional)
    * @throws PDOException
    * @return bool true on success, false otherwise
    */
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

    /**
    * Insert
    * Insert a record into the database
    *
    * @access public
    * @param string $table      Name of table to insert into
    * @param array  $record     Field => value array for data to be inserted
    * @throws PDOException
    * @return bool true on success, false otherwise
    */
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

        $sql = rtrim($sql, ", ") . ")";
        return $this->executeSql($sql, $params);
    }

    /**
    * Update
    * Update a record into the database
    *
    * @access public
    * @param string $table      Name of table to update
    * @param array  $record     Field => value array for data to be inserted
    * @param array  $where  Field => value array to be used as where statements
    * @throws PDOException
    * @return bool true on success, false otherwise
    */
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

    /**
    * Delete
    * Delete a record from the database
    *
    * @access public
    * @param string $table  Name of table to delete from
    * @param array  $where  Field => value array to be used as where statements
    * @throws PDOException
    * @return bool true on success, false otherwise
    */
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

    /**
    * Fetch
    * Fetch a row from the resultset
    *
    * @access public
    * @param int $fetch     PDO Fetch style. (optional, default is PDO::FETCH_OBJ)
    * @returns mixed row from resultset
    */
    function fetch($fetch=\PDO::FETCH_OBJ) {
        return $this->statement->fetch($fetch);
    }

    /**
    * FetchAll
    * Fetch all rows from the resultset
    *
    * @access public
    * @param int $fetch     PDO Fetch style. (optional, default is PDO::FETCH_OBJ)
    * @returns array rows from resultset
    */
    function fetchAll($fetch=\PDO::FETCH_OBJ) {
        return $this->statement->fetchAll($fetch);
    }

    /**
    * Row Count
    * Gets number of rows selected or row affected
    *
    * @access public
    * @return int number of rows selected or row affected
    */
    function rowCount() {
        return $this->statement->rowCount();
    }

    /**
    * Statement Error
    * Get error info about the last statement run
    *
    * @access public
    * @return array with error info
    */
    function statementError() {
        return array(
            'code' => $this->statement->errorCode(),
            'info' => $this->statement->errorInfo(),
        );
    }

    /**
    * Database Error
    * Get error info from database handle
    *
    * @access public
    * @return array with error info
    */
    function databaseError() {
        return array(
            'code' => $this->db->errorCode(),
            'info' => $this->db->errorInfo(),
        );
    }

    /**
    * Build Dsn
    * Builds the PDO Dsn string
    *
    * @access private
    * @return string PDO Dsn
    */
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
