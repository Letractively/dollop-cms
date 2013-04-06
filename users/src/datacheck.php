<?php

/**
  ============================================================
 * Last committed:      $Revision: 134 $
 * Last changed by:     $Author: fire1 $
 * Last changed date:   $Date: 2013-04-05 12:49:50 +0300 (ïåò, 05 àïð 2013) $
 * ID:                  $Id: datacheck.php 134 2013-04-05 09:49:50Z fire1 $
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
 * @filesource  Dollop Users
 * @package dollop
 * @subpackage Module
 *
 */
If (isset($_GET['check_message'])) {
    if (!(bool) session_id()) {
        session_start();
    }
    if (!isset($_SESSION['datacheck_messages'])) {
        $_SESSION['datacheck_messages'] = false;
    }
    if (!function_exists("mysql_charset")) {

        function mysql_charset($db) {
            return false;
        }

    }

    define("FIRE1_INIT", true);
    if (is_numeric($_COOKIE['useruid'])) {
        $uid = $_COOKIE['useruid'];

        class kernel {

            function base_tag() {

            }

        }

        class db {

            var $conn;

            public function db($type, $Conf) {
                unset($type);
                extract($Conf);
                @date_default_timezone_set('UTC');
                $this->conn = new mysqli($dbServ, $dbUser, $dbPass, $dbUses);
            }

            public function db_query($query) {
                return $this->conn->query($query);
            }

            public function db_error() {
                return null;
            }

            public function db_numrows($result) {
                return $result->num_rows;
            }

        }

        global $dpdb;
        include("../../db.php");

        if ((bool) $result = $dpdb->db_query("CALL UnreadedMessages({$uid}) ")) {
            $num = $dpdb->db_numrows($result);
        } else {
            if (
                    !$dpdb->db_query("DROP PROCEDURE IF EXISTS UnreadedMessages") ||
                    !$dpdb->db_query("CREATE PROCEDURE UnreadedMessages(IN `id_val` INT UNSIGNED) BEGIN SELECT ID FROM `" . PREFIX . "messages` WHERE `" . PREFIX . "messages`.`id_receiver` = `id_val` AND readed=1; END;")
            ) {
                die("Stored procedure creation failed: (" . $dpdb->db_error() . ")! Perhaps the version of MySQL is quite old... ");
            }
        }
    }

    if ($num == "0" OR $_SESSION['datacheck_messages'] == $num) {
        header('HTTP/1.0 204 No Content', true, 204);
    } elseIf ($_SESSION['datacheck_messages'] <= $num) {
        header("content-type: text/plain;");
        $_SESSION['datacheck_messages'] = $num;
    } else {
        exit(0);
    }
    echo($num);
    exit();
} else {

    if (!defined('FIRE1_INIT')) {
        exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
    }

    if ($_SESSION["datacheck"] == kernel::uni_session_name()) {

        if (isset($_GET['socnet'])) {

            $usr = new users_socnet;
            if ((bool) $_POST['check_username'])
                $usr->mysql_check_username();
            if ((bool) $_POST['check_usermail']) {
                $usr->mysql_check_usermail();
            }
        } elseIf (isset($_GET['user_list'])) {
            datacheck_user_list();
        }
    }
}