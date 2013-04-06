<?php

/**
  ============================================================
  Last committed:     $Revision: 124 $
  Last changed by:    $Author: fire $
  Last changed date:    $Date: 2013-03-19 15:00:12 +0200 (âò, 19 ìàðò 2013) $
  ID:            $Id: ai.php 124 2013-03-19 13:00:12Z fire $
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
 *      [This file is modified for to be used with Dollop properties]
 *
 * Continue with Second License of main author:
 *
 */
/*
 *  Copyright (C) 2011
 *     Ed Rackham (http://github.com/a1phanumeric/PHP-MySQL-Class)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

// MySQL Class
class mysql_ai extends kernel {

    // Base variables
    var $sLastError; // Holds the last error
    var $sLastQuery; // Holds the last query
    var $aResult; // Holds the MySQL query result
    var $iRecords; // Holds the total number of records returned
    var $iAffected; // Holds the total number of records affected
    var $aRawResults; // Holds raw 'arrayed' results
    var $aArrayedResult; // Holds a single 'arrayed' result
    var $aArrayedResults; // Holds multiple 'arrayed' results (usually with a set key)
    var $row; // Holds multiple 'arrayed' results (usually with a set key)
    var $iSecure = true; // Boolean
    var $sHostname = sqlHOST; // MySQL Hostname
    var $sUsername = sqlUSER; // MySQL Username
    var $sPassword = sqlPASS; // MySQL Password
    var $sDatabase = DATABASE; // MySQL Database
    var $sDBLink; // Database Connection Link
    //
    // Count exec of Filter
    private $ExecCount = 0;

    /**
     *
     * @var string $pLister - page lister sql end
     */
    //public $pLister = null;
    //
    // Properties of class
    private $aiProp = null; // Properties

    // Class Constructor
    // Assigning values to variables

    function __construct() {
        //
        // Load properties file if is not loaded yet
        if (!is_array($this->aiProp)) {
            $this->aiProp = kernel::loadprop(__FILE__, true);
        }
        //
        // Connect to mysql
        $this->Connect();
    }

    /**
     * Connects class to database
     * @param boolean $bPersistant - Use persistant connection?
     * @return boolean
     */
    function Connect($bPersistant = false) {
        if ($this->sDBLink) {
            mysql_close($this->sDBLink);
        }
        if ($bPersistant) {
            $this->sDBLink = mysql_pconnect($this->sHostname, $this->sUsername, $this->sPassword);
        } else {
            $this->sDBLink = mysql_connect($this->sHostname, $this->sUsername, $this->sPassword);
        }
        if (!$this->sDBLink) {
            $this->sLastError = 'Could not connect to server: ' . mysql_error($this->sDBLink);
            return false;
        }
        if (!$this->UseDB()) {
            $this->sLastError = 'Could not connect to database: ' . mysql_error($this->sDBLink);
            return false;
        }
        return true;
    }

    /**
     * Select database to use
     * @return boolean
     */
    function UseDB() {
        if (!mysql_select_db($this->sDatabase, $this->sDBLink)) {
            $this->sLastError = 'Cannot select database: ' . mysql_error($this->sDBLink);
            return false;
        } else {
            mysql_charset($this->sDBLink);
            return true;
        }
    }

    /**
     * Executes MySQL query, like mysql_query();
     * @param type $sSQLQuery
     * @return boolean
     */
    function ExecuteSQL($sSQLQuery) {
        $this->sLastQuery = $sSQLQuery;
        if ((bool)$this->aResult = mysql_query($sSQLQuery, $this->sDBLink)) {
            $this->iRecords = @mysql_num_rows($this->aResult);
            $this->iAffected = @mysql_affected_rows($this->sDBLink);
            return true;
        } else {
            $this->sLastError = mysql_error($this->sDBLink);
            return false;
        }
    }

    /**
     *  Fix PREFIX from MySQL Table
     * @param string $sTable Table name
     * @return string Clear from prefix
     */
    private function fix_pefix($sTable) {
        //
        // Get Prefix of table an removed
        return str_replace(constant("PREFIX"), "", $sTable);
    }

    /**
     * Adds a record to the database based on the array key names
     * @example Insert(array(key=>val), 'table', array('submit') );
     * @param array $aVars
     * @param string $sTable
     * @param array $aExclude
     * @param boolean $bFilterText
     * @param null $aKeys
     * @param boolean $iMerge
     * @return boolean
     */
    function Insert($aVars, $sTable, $aExclude = '', $bFilterText = true, $aKeys = null, $iMerge = true) {
        //
        // Clear the prefix
        $sTable = $this->fix_pefix($sTable);
        // Catch Exceptions
        if ($aExclude == '') {
            $aExclude = array();
        }
        array_push($aExclude, 'MAX_FILE_SIZE');
        // Prepare Variables
        if ((bool) $bFilterText) {
            $aVars = $this->ExecuteTextFields($aVars, $aKeys = null, $iMerge = true);
        } else {
            $aVars = $this->SecureData($aVars);
        }
        $sSQLQuery = 'INSERT INTO `' . PREFIX . $sTable . '` SET ';
        foreach ($aVars as $iKey => $sValue) {
            if (in_array($iKey, $aExclude)) {
                continue;
            }
            $sSQLQuery.= '`' . $iKey . '` = "' . $sValue . '", ';
        }
        $sSQLQuery = substr($sSQLQuery, 0, -2);
        if ($this->ExecuteSQL($sSQLQuery)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deletes a record from the database
     * @param string $sTable // Table name with or without prefix
     * @param array $aWhere // Where to Drop
     * @param string $sLimit // Limit of rows
     * @param boolean $bLike  // Using LIKE
     * @return boolean     // Execute or Not
     */
    function Delete($sTable, $aWhere = '', $sLimit = '', $bLike = false) {
        //
        // Clear the prefix
        $sTable = $this->fix_pefix($sTable);
        //
        $sSQLQuery = 'DELETE FROM `' . PREFIX . $sTable . '` WHERE ';
        if (is_array($aWhere) && $aWhere != '') {
            // Prepare Variables
            $aWhere = $this->SecureData($aWhere);
            foreach ($aWhere as $iKey => $sValue) {
                if ($bLike) {
                    $sSQLQuery.= '`' . $iKey . '` LIKE "%' . $sValue . '%" AND ';
                } else {
                    $sSQLQuery.= '`' . $iKey . '` = "' . $sValue . '" AND ';
                }
            }
            $sSQLQuery = substr($sSQLQuery, 0, -5);
        }
        if ($sLimit != '') {
            $sSQLQuery.= ' LIMIT ' . $sLimit;
        }
        if ($this->ExecuteSQL($sSQLQuery)) {
            return true;
        } else {
            return false;
        }
    }

    // Gets a single row from $1
    // where $2 is true

    /**
     * Gets a single row from $1
     * where $2 is true
     * @param mixed $sFrom
     * @param mixed $aWhere
     * @param mixed $sOrderBy
     * @param mixed $sLimit
     * @param mixed $bLike
     * @param mixed $sOperand
     */
    function Select($sFrom, $aWhere = '', $sOrderBy = '', $sLimit = '', $bLike = false, $sOperand = 'AND') {
        //
        // Clear the prefix
        $sFrom = $this->fix_pefix($sFrom);
        //
        // Catch Exceptions
        if (trim($sFrom) == '') {
            return false;
        }
        $sSQLQuery = 'SELECT * FROM `' . PREFIX . $sFrom . '` WHERE ';
        if (is_array($aWhere) && $aWhere != '') {
            // Prepare Variables
            $aWhere = $this->SecureData($aWhere);
            foreach ($aWhere as $iKey => $sValue) {
                if ($bLike) {
                    $sSQLQuery.= '`' . $iKey . '` LIKE "%' . $sValue . '%" ' . $sOperand . ' ';
                } else {
                    $sSQLQuery.= '`' . $iKey . '` = "' . $sValue . '" ' . $sOperand . ' ';
                }
            }
            $sSQLQuery = substr($sSQLQuery, 0, -5);
        } else {
            $sSQLQuery = substr($sSQLQuery, 0, -7);
        }
        if ($sOrderBy != '') {
            $sSQLQuery.= ' ORDER BY ' . $sOrderBy;
        }
        if ($sLimit != '') {
            $sSQLQuery.= ' LIMIT ' . $sLimit;
        } elseIf (!is_null($this->page_lister)) {
            $sSQLQuery .=$this->pLister;
        }
        if ($this->ExecuteSQL($sSQLQuery)) {
            if ($this->iRecords > 0) {
                $this->ArrayResults();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Updates a record in the database
     * based on WHERE
     * @param mixed $sTable
     * @param array $aSet
     * @param array $aWhere
     * @param mixed $aExclude
     */
    function Update($sTable, $aSet, $aWhere, $aExclude = '', $bFilterText = true, $aKeys = null, $iMerge = true) {
        //
        // Clear the prefix
        $sTable = $this->fix_pefix($sTable);
        //
        //
        // Catch Exceptions
        if (trim($sTable) == '' || !is_array($aSet) || !is_array($aWhere)) {
            return false;
        }
        if ($aExclude == '') {
            $aExclude = array();
        }
        array_push($aExclude, 'MAX_FILE_SIZE');
        //
        // Filter text fields
        if ((bool) $bFilterText) {
            $aSet = $this->ExecuteTextFields($aSet, $aKeys = null, $iMerge = true);
        } else {
            $aSet = $this->SecureData($aSet);
        }
        $aWhere = $this->SecureData($aWhere);
        // SET
        $sSQLQuery = 'UPDATE `' . PREFIX . $sTable . '` SET ';
        foreach ($aSet as $iKey => $sValue) {
            if (in_array($iKey, $aExclude)) {
                continue;
            }
            $sSQLQuery.= '`' . $iKey . '` = "' . $sValue . '", ';
        }
        $sSQLQuery = substr($sSQLQuery, 0, -2);
        // WHERE
        $sSQLQuery.= ' WHERE ';
        foreach ($aWhere as $iKey => $sValue) {
            $sSQLQuery.= '`' . $iKey . '` = "' . $sValue . '" AND ';
        }
        $sSQLQuery = substr($sSQLQuery, 0, -5);
        if ($this->ExecuteSQL($sSQLQuery)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 'Arrays' a single result
     *  Value is not recordert in string
     */
    function ArrayResult() {
        return mysql_fetch_array($this->aResult);
    }

    /**
     * 'Numbers' a single result
     *  Value is not recordert in string
     */
    function ArrayNumResult() {
        return mysql_num_rows($this->aResult);
    }

    /**
     * 'Arrays' multiple result
     *
     */
    function ArrayResults() {
        $this->aArrayedResults = array();
        while ($aData = mysql_fetch_assoc($this->aResult)) {
            $this->aArrayedResults[] = $aData;
            $this->row[] = $aData;
        }
        return $this->aArrayedResults;
    }

    /**
     * 'Arrays' multiple results with a key
     *
     * @param mixed $sKey array key
     */
    function ArrayResultsWithKey($sKey = 'ID') {
        if (isset($this->aArrayedResults)) {
            unset($this->aArrayedResults);
        }
        $this->aArrayedResults = array();
        while ($aRow = mysql_fetch_assoc($this->aResult)) {
            foreach ($aRow as $sTheKey => $sTheValue) {
                $this->aArrayedResults[$aRow[$sKey]][$sTheKey] = $sTheValue;
            }
        }
        return $this->aArrayedResults;
    }

    /*
     * Fix prop names
     *
     * @param array $array // Array prop sector
     *
     * @return array clear names
     * */

    public function fix_prop_name($array) {
        $ext = "ai.txtfld.";
        $newArr = array();
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $newArr[str_replace($ext, "", $key)] = $this->fix_prop_name($val);
            } else {
                $newArr[str_replace($ext, "", $key)] = $val;
            }
        }
        return $newArr;
    }

    /**
     * Function will use function for field to process the data in field
     * The default function are in ai.prop file and you cand insert yours.
     * Im creating this function becose dont want to use evry time stripslashes
     * For TiniMCE and etc. Dollop also adds slashes.
     *
     * @example ExecuteTextFields("textarea"=>"stripslashes"); //This case will execute $_POST['textarea']=stripslashes($_POST['textarea']);
     * @license GNU 3
     * @author fire1@abv.bg
     *
     * @param array $aKeys   Array with custom execution
     * @param boolen $iMerge TRUE to attach default property
     * @param string $method Method string name [$_POST,$_GET...]
     */
    function ExecuteTextFields($aData, $aKeys = null, $iMerge = true) {
        if ($this->ExecCount == 0) {
            $this->ExecCount++;
        } elseif ($this->ExecCount >= 1) {
            return false;
        }
        $aData = stripallslashes_deep($aData);
        //
        // Check is posted this field.
        $this->iSecure = false;
        $aKeysDef = $this->fix_prop_name($this->aiProp['TEXT_FIELDS']);
        //
        // Check for custom array
        if (!is_array($aKeys)) {
            $aKeys = $aKeysDef;
        } else {
            //
            // Merge prop array
            if ($iMerge === true) {
                $aKeys = array_merge((array) $aKeysDef, (array) $aKeys);
            }
        }
        //
        // Loop array
        foreach ($aKeys as $field => $function) {
            if (isset($aData[$field])) {
                //
                // Execute the function
                if (is_array($function)) {
                    foreach ($function as $other) {
                        $eval = "";
                        $aData[$field] = call_user_func($other, $aData[$field]);
                        //
                        // Creating a filter
                    }
                } else {
                    $aData[$field] = call_user_func($function, $aData[$field]);
                    //
                    // Creating a filter
                    //eval($eval);
                }
            }
        }

        $aData = array_map("mysql_real_escape_string", $aData);
        //$aData = addslashes_once_deep($aData);
        /*
          echo "<pre>";
          print_r($aData);
          $this->iSecure = false;
          exit(); */
        // $_POST = addslashes_once_deep($_POST);
        // $this->iSecure =false;
        return $aData;
    }

    /**
     * Performs a 'mysql_real_escape_string' on
     * the entire array/string
     *
     */
    function SecureData($aData) {
        if (is_array($aData)) {
            foreach ($aData as $iKey => $sVal) {
                if (!is_array($aData[$iKey])) {
                    $aData[$iKey] = mysql_real_escape_string($aData[$iKey], $this->sDBLink);
                }
            }
        } else {
            $aData = mysql_real_escape_string($aData, $this->sDBLink);
        }
        return $aData;
    }

    /**
     * This function will record all errors from mysql
     * @author Angel Zaprianov fire1@abv.bg
     * @license GNU 3
     */
    private function recording_errors() {
        if (!is_array($this->aiProp))
            return false;;
        if ((bool) $this->aiProp['AI_MAIN']['ai.recording.err']) {
            if (isset($this->sLastError)) {
                $file = kernel::base_tag($this->aiProp['AI_MAIN']['ai.erors.logfile']);
                if (empty($file))
                    return false;
                @file_put_contents($file, $this->sLastError . "\n", FILE_APPEND | LOCK_EX);
            }
        }
    }

    function __destruct() {
        //
        // Recording errors from MySQL
        $this->recording_errors();
    }

}

//
// Goodbay
