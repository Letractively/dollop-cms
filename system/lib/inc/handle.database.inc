<?php

/**
  ============================================================
 * Last committed:      $Revision$
 * Last changed by:     $Author$
 * Last changed date:   $Date$
 * ID:                  $Id$
  ============================================================
  Copyright Angel Zaprianov [2009] [INFOHELP]
  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at
  http://www.apache.org/licenses/LICENSE-2.0
  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 * --------------------------------------
 *       See COPYRIGHT and LICENSE
 * --------------------------------------
 *
 * @filesource Database function
 * @package Dollop
 * @subpackage Dollop Database
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

class db {

    /**
     * @param string $db_type Database type PDO,MySQLi,Default
     *
     */
    var $dbType = "";

    /**
     * @param array $db_info Database connection information
     *
     */
    var $dbInfo = array();

    /**
     * @param string $ch_path Path to cache
     *
     */
    var $chPath = null;

    /**
     * @param global $dbConn Database link
     *
     */
    public $dbLink = null;

    /**
     * @param bool $isConn Is connected to database
     *
     */
    public $isConn = false;

    /**
     * @param init $idData Is connected to database
     *
     */
    public $idData = 0;
    private static $db_instance;

    /**
     * @param string $qResult Retruns the query result
     *
     */
    var $aResult = array();
    var $aFetch = array();
    var $Current = null;
    //
    // Private variables
    private $dbServ = null;
    private $dbUses = null;
    private $dbUser = null;
    private $dbPass = null;
    private $errlog = null;
    private $Query = null;
    private $charset = null;
    private $error = array();

    /**
     * Create Connection to Database
     *
     * @author Angel Zaprianov <fire1@abv.bg>
     * @example $a = new db("PDO",array("dbServ" => "localhost","dbUses" => "tests","dbUser" => "root","dbPass" => "pass0rd"));
     *
     * @param string $type Type of Mysql Extension to use. Cases for switch are [sqli,pdo,null]
     * @param array  $aConf Array with Connection information.
     *
     * */
    function __construct($type = null, $aConf = null) {
        $this->charset = kernel::base_tag("{sqlcharset}");
        if (!is_null($type) && is_array($aConf)) {
            $this->dbInfo = $aConf;
            $this->dbType = strtolower($type);
            $this->make_connection();
            //
            // Creating connection
            $this->new_instance();
        }
    }

    public function make_connection($dbServ = null, $dbUses = null, $dbUser = null, $dbPass = null) {

        if (is_array($this->dbInfo) && !empty($this->dbInfo)) {
            extract($this->dbInfo);
        }
        //
        // Store the information to class variables
        $this->dbServ = $dbServ;
        $this->dbUses = $dbUses;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
    }

    /**
     * New Connection instance
     * */
    public function new_instance() {

        try {
            //
            // Maker connection
            $this->make_conn();
        } catch (Exception $e) {
            //
            // Seand error
            throw new Exception("Cannot connect to Database as {$this->dbType}", 0, $e);
        }
        //
        // Store Databese link in class
        if (!(bool) $this->dbLink) {
            $this->dbLink = self::$db_instance;
        }
        //
        // retrun the new database instance
        return self::$db_instance;
    }

    // Connection
    // -----------------------------------------------------
    //
    private function make_conn() {

        switch ($this->dbType) {
            case "sqli":
                $this->mysqli_conn();
                break;
            case "pdo":
                $this->pdosql_conn();
                break;
            default:
                $this->defsql_conn();
                break;
        }
    }

    /**
     * MySQLi extension  Connection
     *
     */
    private function mysqli_conn() {

        try {
            //
            // Store the link in string
            self::$db_instance = new mysqli($this->dbServ, $this->dbUser, $this->dbPass, $this->dbUses);
            mysqli_character_set_name(self::$db_instance);
            //
            // Set charsete in mysqli type
            mysqli_set_charset(self::$db_instance, $this->charset);
            //
            // Set connection boolean
            $this->isConn = true;
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    /**
     * PDO_MySQL extension Connection
     *
     */
    private function pdosql_conn() {
        try {
            //
            // Store the link in string
            self::$db_instance = new PDO("mysql:host={$this->dbServ};dbname={$this->dbUses}", $this->dbUser, $this->dbPass,
                            //
                            // Set charsete in PDO type
                            array(
                                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
                            )
            );
            //
            // Set connection boolean
            $this->isConn = true;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Default MySQL extension Connection
     *
     */
    private function defsql_conn() {
        try {
            //
            // Store the link in string
            self::$db_instance = mysql_connect($this->dbServ, $this->dbUser, $this->dbPass);
            mysql_select_db($this->dbUses, self::$db_instance);
            //
            // Set charsete in mysql type
            mysql_set_charset($this->charset, self::$db_instance);
            //
            // Set connection boolean
            if (!(bool) self::$db_instance) {
                throw new Exception('MySQL Connection Database Error: ' . mysql_error());
            } else {
                $this->isConn = true;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create positive crc32
     * */
    private function crc($str) {

        sscanf(crc32($str), "%u", $var);
        return $var;
    }

    /**
     * Make a Database query
     * */
    public function db_query($Query) {
        if (!(bool) $this->isConn) {
            $this->new_instance();
        }
        //
        // Send the query to class string
        $this->Query = $Query;
        //
        // Generate result id
        $id = $this->crc($Query);
        //
        // Store the result to string
        $this->aResult[$id] = $this->make_query();
        if (!(bool) $this->aResult[$id]) {
            $this->trigger_error();
        }
        //
        // Set current key
        $this->Current = $id;
        //
        // Return the result
        return $this->aResult[$id];
    }

    // Query
    // -----------------------------------------------------
    //
    private function make_query() {
        switch ($this->dbType) {
            case "sqli":
                return $this->mysqli_query();
                break;
            case "pdo":
                return $this->pdosql_query();
                break;
            default:
                return $this->defsql_query();
                break;
        }
    }

    protected function mysqli_query() {
        return $this->dbLink->query($this->Query);
    }

    protected function pdosql_query() {
        return $this->dbLink->prepare($this->Query);
    }

    protected function defsql_query() {
        return mysql_query($this->Query, $this->dbLink);
    }

    public function db_fetch($Result = null, $type = "both") {
        $type = strtolower($type);
        if (is_null($Result)) {
            $Result = end($this->aResult);
        }
        //
        // Store the output array in class array
        return $this->make_fetch($Result, $type);
        //
        // Returns the array result after is stored in class
        return $this->aFetch[$catch_result];
    }

    // Fetch
    // -----------------------------------------------------
    //
    private function make_fetch($result, $type) {
        if(!(bool)$result) return array();
        switch ($this->dbType) {
            case "sqli":
                return $this->mysqli_fetch($result, $type);
                break;
            case "pdo":
                return $this->pdosql_fetch($result, $type);
                break;
            default:
                return $this->defsql_fetch($result, $type);
                break;
        }
    }

    protected function mysqli_fetch($result, $type) {
        $use_type = ($type == "assoc") ? MYSQLI_ASSOC : MYSQLI_BOTH;
        if (method_exists('mysqli_result', 'fetch_all')) {
            return $result->fetch_all($use_type); //faster
        } else {
            $rows = array();
            while ($row = $result->fetch_array($use_type)) {
                array_push($rows, $row);
            }
        }
        $result->close();
        return $rows;
    }

    protected function pdosql_fetch($result, $type) {
        $use_type = ($type == "assoc") ? PDO::FETCH_ASSOC : PDO::FETCH_BOTH;
        $result->execute();
        return $result->fetch($use_type);
    }

    protected function defsql_fetch($result, $type) {
        $use_type = ($type == "assoc") ? MYSQL_ASSOC : MYSQL_BOTH;
        return mysql_fetch_array($result, $use_type);
    }

    public function db_numrows($result = null) {
        if (is_null($result)) {
            $result = end($this->aResult);
        }
        $catch_result = array_search($result, $this->aResult);
        //
        // Check for result
        if (!(bool) $catch_result) {
            //
            // Send the error if cannot find the result
            throw new Exception('MySQL Database Result Error.');
        }
        //
        // Store the output array in class array
        return $this->make_nrows($this->aResult[$catch_result]);
    }

    // Rows Count
    // -----------------------------------------------------
    //
    public function make_nrows($result) {
        switch ($this->dbType) {
            case "sqli":
                return $this->mysqli_nrows($result);
                break;
            case "pdo":
                return $this->pdosql_nrows($result);
                break;
            default:
                return $this->defsql_nrows($result);
                break;
        }
    }

    protected function mysqli_nrows($result) {
        return $result->num_rows;
    }

    protected function pdosql_nrows($result) {
        return (int) $result->fetchColumn();
    }

    protected function defsql_nrows($result) {
        return mysql_numrows($result);
    }

    public function make_close($link = null) {
        $link = (is_null($link)) ? $this->dbLink : $link;
        switch ($this->dbType) {
            case "sqli":
                return mysqli_close($link);
                break;
            case "pdo":
                return $link = null;
                break;
            default:
                return mysql_close($link);
                break;
        }
    }

    // Escape string
    // -----------------------------------------------------
    //
    //
    /**
     * Real Escape String
     *
     * @param type $string String to escape
     * @return type
     */
    public function make_escape($string) {
        switch ($this->dbType) {
            case "sqli":
                return $this->dbLink->real_escape_string($string);
                break;
            case "pdo":
                // pdo using prepare no need for escaping strings
                return $string;
                break;
            default:
                return mysql_real_escape_string($string, $this->dbLink);
                break;
        }
    }

    // Errors
    // -----------------------------------------------------
    //
    private function trigger_error() {
        array_push($this->error, $this->make_error());
    }

    public function db_error() {
        return implode("\n", $this->error);
    }

    public function make_error() {
        switch ($this->dbType) {
            case "sqli":
                return $this->dbLink->error;
                break;
            case "pdo":
                return $this->dbLink->errorInfo();
                break;
            default:
                return mysql_error($this->dbLink);
                break;
        }
    }

    public function __destruct() {
        $this->make_close();
    }

}

/**
 * Dollop Mysql Query Function
 *
 * @global link $dpdb         Database link
 * @param string $query     Query for MySQL
 * @param bool $fetch       For returning the Array or Result from function
 * @return result / array   Returns the Array or Result
 */
function db_query($query, $fetch = false) {
    global $dpdb;

    if ((bool) $fetch) {
        $r = $dpdb->db_query($query);
        return $dpdb->db_fetch($r);
    }
    return $dpdb->db_query($query);
}

/**
 * Alternative to mysql featch_array
 *
 * @global link $db
 * @param string $result result from query
 * @return array
 */
function db_fetch($result = null, $type = "both", $param = null) {
    global $dpdb;

    if (!(bool)($result)) {
        return array();
    } else {
        if (!is_null($param)) {
            if (is_numeric($param)) {
                $r = $dpdb->db_fetch($result, $type);
                return $r[$param];
            } else {
                return call_user_func($param, $dpdb->db_fetch($result, $type));
            }
        }
        return $dpdb->db_fetch($result, $type);
    }
}

function db_numrows($result) {
    global $dpdb;

    return $dpdb->make_nrows($result);
}

/**
 * Returns the error from Mysql
 *
 * @global link $dpdb
 * @return type
 */
function db_error() {
    global $dpdb;
    return $dpdb->db_error();
}

/**
 * Make escape string
 *
 * @global link $dpdb
 * @param type $string String to escape
 * @return type Clean String
 */
function db_escape_string($string) {
    global $dpdb;
    return $dpdb->make_escape($string);
}
