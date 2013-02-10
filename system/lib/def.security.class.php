<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: def.security.class.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * @subpackage class
 * 
 */
class Security extends kernel {

    protected static $instance;
    protected $magic_quotes_gpc = FALSE;

    private function security_escape_string($tekst) {
        if ((int) $tekst)
            return $tekst;
        else {

            $tekst = @mysql_real_escape_string($tekst);

            return $tekst;
        }
    }

    public function instance() {
        if (!get_magic_quotes_gpc()) {


            $_GET = array_map("addslashes", $_GET);

            $_POST = array_map('addslashes', $_POST);

            $_COOKIE = array_map("addslashes", $_COOKIE);
            $_COOKIE = array_map("addslashes", $_COOKIE);
            $_SERVER = array_map("addslashes", $_SERVER);
        }

        $_GET = array_map("strip_tags", $_GET);

        $_GET = array_map('mysql_real_escape_string', $_GET);

        $_POST = array_map("mysql_real_escape_string", $_POST);
#$_SESSION = array_map("mysql_real_escape_string", $_SESSION);
        $_SESSION = array_map("strip_tags", $_SESSION);
        $_COOKIE = array_map("strip_tags", $_COOKIE);
        $_COOKIE = array_map("mysql_real_escape_string", $_COOKIE);
        $_SERVER = array_map("mysql_real_escape_string", $_SERVER);
    }

}

?>