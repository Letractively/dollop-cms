<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: install.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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

if (file_exists("db.php")) {
    require_once("db.php");

#system

    $handle = @fopen(".source/install.sql", "r");
    if ($handle) {
        While (!feof($handle)) {
            $query .=fgets($handle, 4096);
            if (substr(rtrim($query), -1) == ';') {
                $arrKeys = array('".PREFIX."', '$host', '$sega_data', '$ipaddress');
                $arrCont = array(PREFIX, $host, $sega_data, $ipaddress);
                $query = str_replace($arrKeys, $arrCont, $query);
                mysql_query($query) or die(mysql_error());
                $query = '';
            }
        }
        fclose($handle);
    }



    Echo "Successfully instal C.M.S.
<META HTTP-EQUIV='Refresh' CONTENT='2; URL=?'>";
}
?>