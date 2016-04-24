<?php
/**
 * @Author: prpr
 * @Date:   2016-04-24 08:33:05
 * @Last Modified by:   prpr
 * @Last Modified time: 2016-04-24 09:09:29
 */

/**
 * Custom Database Class
 *
 * Provides universal methods for MySQL and SQLite
 */
class Database
{
    private $connection = null;
    // 0 for sqlite, 1 for mysql
    private $type = 1;

    function __construct($db_type) {
        if ($db_type == "mysql") {
            $this->type = 1;
        } else if ($db_type == "sqlite") {
            $this->type = 0;
        } else {
            throw new Exception('Unsupported database type.');
        }

        $this->connect();

        $sqls[0] = "SELECT count(*) FROM sqlite_master WHERE type='table' AND name='post_views'";
        $sqls[1] = "SELECT table_name FROM `INFORMATION_SCHEMA`.`TABLES` WHERE table_name ='post_views' AND TABLE_SCHEMA='ghost'";

        // Create `post_views` table if not exists
        if (!$this->checkRecordExist($sqls[$this->type])) $this->createTable();
    }

    /**
     * Connect to database using specific driver
     *
     * @return null
     */
    public function connect() {
        if ($this->type == 1) {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_NAME, DB_PORT);
            if ($error = $this->connection->connect_error) die($error);
            $this->connection->query("SET names UTF8");
        } else {
            $this->connection = new SQLite3(DB_DIR);
            if ($error = $this->connection->lastErrorMsg()) die($error);
        }
    }

    /**
     * Do SQL query
     * @param  string $sql, SQL statement
     * @return SQLite3Result|mysqli_result
     */
    public function query($sql) {
        return $this->connection->query($sql);
    }

    /**
     * Fetch a result row as an array
     * @param  SQLite3Result|mysqli_result $result
     * @return array
     */
    public function fetchArray($result) {
        if ($this->type == 1) {
            return $result->fetch_array();
        } else {
            return $result->fetchArray();
        }
    }

    /**
     * Check if record exists
     * @param  string $sql
     * @return bool
     */
    public function checkRecordExist($sql) {
        $result = $this->query($sql);
        if ($this->type == 1) {
            return ($result->num_rows != 0) ? true : false;
        } else {
            return ($result->fetchArray()) ? true : false;
        }
    }

    /**
     * Create `post_views` table
     * @return bool
     */
    public function createTable() {
        $sqls[0] = "CREATE TABLE IF NOT EXISTS `post_views` (`id` INTEGER PRIMARY KEY NOT NULL, `slug` CHAR(150) NOT NULL, `pv` INT NOT NULL)";
        $sqls[1] = "CREATE TABLE IF NOT EXISTS `post_views` (`id` int(10) NOT NULL AUTO_INCREMENT, `slug` varchar(150) NOT NULL, `pv` int(10) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        if ($this->query($sqls[$this->type])) {
            return true;
        } else {
            die(($this->type == 1) ? $this->connection->connect_error : $this->connection->lastErrorMsg());
        }
    }

    /**
     * Close database connection
     * @return bool
     */
    public function close() {
        return $this->connection->close();
    }
}
