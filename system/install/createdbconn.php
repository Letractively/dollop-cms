<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: createdbconn.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * @filesource Main Dollop
 * @package dollop kernel
 * @subpackage functions
 * 
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}





$data_db = '<?php
/* Dollop
 * Generated file
 * With Configurations
 * 
 */
if (!defined("FIRE1_INIT")) { exit(\'<div style="background-color: #FFAAAA;"> error..1001</div>\'); } 

define("PREFIX", "' . $_POST['prefix'] . '_");
define("DATABASE", "' . $_POST['db'] . '");


define("sqlHOST", "' . $_POST['host'] . '");
define("sqlUSER", "' . $_POST['user'] . '");
define("sqlPASS", "' . $_POST['pass'] . '");

// convert string to global
    global $db;

// Connects to your Database
$db = mysql_connect(sqlHOST, sqlUSER, sqlPASS) or die(\'fire1 error: no connection to database: <br><br> 
<font size="-1"  face="Arial" color="Purple" style="margin: 3px;padding:5px;background-color: #FCF;">\'.mysql_error().\'</font>"\');
mysql_select_db(DATABASE,$db) or die(\'fire1 error: no selected database: <br><br> 
<font size="-1"  face="Arial" color="Purple" style="margin: 3px;padding:5px;background-color: #FCF;">\'.mysql_error().\'</font>"\');

// set mysql charset
mysql_charset($db);

';
$trunker = self::SESSION('main_trunk');
$data_confINIT = '<?php
  if (!defined("FIRE1_INIT")) { exit(\'<div style="background-color: #FFAAAA;"> error..1001</div>\'); }   
define("SRCDR","' . self::$CONFIGURATION['source'] . '/");
define("HEX","' . self::$CONFIGURATION['HEX'] . '");
define("SSSDR","' . self::$CONFIGURATION['session_dir'] . '/");
define("TRUNK","' . self::$CONFIGURATION['trunk'] . '/");
define("HKEYS","' . self::$CONFIGURATION['hkey'] . '/");
define("SLICK","' . self::$CONFIGURATION['SlickFlashFile'] . '/");
';
?>
