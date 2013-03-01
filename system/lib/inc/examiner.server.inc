<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: examiner.server.inc 115 2013-02-08 16:27:29Z fire $
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

/**
 * Finds in server mysql version
 * @return mixed Mysql Version
 */
function find_sql_version() {
    $output = shell_exec('mysql -V');
    preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
    return $version[0];
}

/**
 * Check for available required extensions in
 * server to run properly a dollop script.
 *
 */
function installation_extension_check($cnf) {
    $info_array = "";
    // Check PHP safe mode
    if (ini_get('safe_mode')) {
        return " PHP running on safe mode! Please disable safe mode to continue installation. ";
    }
    // Check for image support
    if (function_exists('gd_info')) {
        $info_array = gd_info();
        if ($info_array['GD Version'] < "2.0")
            $data_array = " GD library old version! ";;
        if ($info_array['GIF Read Support'])
            $data_array = " G.D. do not support GIF Read!";;
        if ($info_array['GIF Create Support'])
            $data_array = " G.D. do not support GIF Create!";;
        if ($info_array['JPEG Support'])
            $data_array = " G.D. do not support JPEG!";;
        if ($info_array['PNG Support'])
            $data_array = " G.D. do not support PNG!";;
        if ($info_array['WBMP Support'])
            $data_array = " G.D. do not support WBMP!";;
    } else {
        return " GD library extension do not exists! ";
    }
    // check for XML support
    if (!class_exists("SimpleXMLElement")) {
        return " \"SimpleXMLElement\" class do not exists! Please install `php5-cli` (for Debian). ";
    }
    // check for Mysql support
    if ($info_array = find_sql_version()) {
        /* it seems so far no need the use this version
          if( (int)$info_array  < (int)$cnf['mysqlversion']  ){


          return " Mysql version is too old to work with this script. ";


          }

         */
    } else {
        return "PHP failed to find a version of MySQL! Please check server for existing service `mysql`. ";
    }
    // Check writable
    if (!is_writable(__FILE__)) {
        return " PHP script do not have permission to write in this  server directory! Please fix Apache user rights to write in this folder. ";
    }
    // check MCrypt
    if (!function_exists('mcrypt_module_open')) {
        return " MCrypt extension is required and missing on this server! Cannot continue installation before installing this PHP extension. ";
    }
    // check Serialize
    if (!function_exists("serialize")) {
        return " PHP `serialize` function do not exists. ";
    }
    $info_array = phpversion();
    if ((int) $info_array > (int) $cnf['phpversion']) {
        return " PHP  version is too old to work with this script. ";
    }
    return false;
    ;
}
