<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: users.const.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 */
if (!defined('FIRE1_INIT')) {
    @header_remove();
    header("location:error400");
    exit();
}
define("BASEURL", "http://" . constant('HOST') . constant('MODULE_DIR'));
/**
 * Database Constants - these constants are required
 * in order for there to be a successful connection
 * to the MySQL database. Make sure the information is
 * correct.
 */
define("DB_SERVER", constant('sqlHOST'));
define("DB_USER", constant('sqlUSER'));
define("DB_PASS", constant('sqlPASS'));
define("DB_NAME", constant('DATABASE'));
class user_constats extends kernel {
    function __construct() {
        global $KERNEL_PROP_MAIN;
        $constants_file = $KERNEL_PROP_MAIN['kernel.users.depend.constants'];
        $constants_folder = $KERNEL_PROP_MAIN['kernel.users.depend.folder'];
        $etc_folder = $KERNEL_PROP_MAIN['dollop.etc'];
        $prop = ROOT . SRCDR . $etc_folder . "/" . $constants_folder . "/" . $constants_file;
        $const = kernel::loadprop($prop, 1);
        define("TBL_USERS_MAIN", constant('PREFIX') . @$const['users.constants.sqltbl.main']);
        define("TBL_USERS_ROW_UNAME", $const['users.constants.sqltbl.row.uname']);
        define("TBL_USERS_ROW_UPASS", $const['users.constants.sqltbl.row.upass']);
        define("TBL_USERS_ROW_UMAIL", $const['users.constants.sqltbl.row.umail']);
        define("TBL_USERS_ROW_UPRIV", $const['users.constants.sqltbl.row.upriv']);
        define("TBL_USERS_ROW_UID", $const['users.constants.sqltbl.row.uid']);
        define("COOKIE_UNAME", $const['users.constants.cookie.uname']);
        define("COOKIE_UID", $const['users.constants.cookie.uid']);
        define("COOKIE_USESS", $const['users.constants.cookie.uses']);
    }
}
new user_constats;
/**
 * Database Table Constants - these constants
 * hold the names of all the database tables used
 * in the script.
 */
define("TBL_USERS", constant('TBL_USERS_MAIN'));
define("TBL_ACTIVE_USERS", constant('PREFIX') . "active_users");
define("TBL_ACTIVE_GUESTS", constant('PREFIX') . "active_guests");
define("TBL_BANNED_USERS", constant('PREFIX') . "banned_users");
define("TBL_MAIL", constant('PREFIX') . "mail");
define("TBL_USERS_FIELDS", constant('PREFIX') . "users_fields");
/**
 * Special Names and Level Constants - the admin
 * page will only be accessible to the user with
 * the admin name and also to those users at the
 * admin user level. Feel free to change the names
 * and level constants as you see fit, you may
 * also add additional level specifications.
 * Levels must be digits between 0-9.
 */
define("ADMIN_NAME", "admin");
define("GUEST_NAME", "Guest");
define("ADMIN_LEVEL", 9);
define("AUTHOR_LEVEL", 5);
define("USER_LEVEL", 1);
define("GUEST_LEVEL", 0);
/**
 * This boolean constant controls whether or
 * not the script keeps track of active users
 * and active guests who are visiting the site.
 */
define("TRACK_VISITORS", true);
/**
 * Timeout Constants - these constants refer to
 * the maximum amount of time (in minutes) after
 * their last page fresh that a user and guest
 * are still considered active visitors.
 */
define("USER_TIMEOUT", 10);
define("GUEST_TIMEOUT", 5);
/**
 * Cookie Constants - these are the parameters
 * to the setcookie function call, change them
 * if necessary to fit your website. If you need
 * help, visit www.php.net for more info.
 * <http://www.php.net/manual/en/function.setcookie.php>
 */
define("COOKIE_EXPIRE", 60 * 60 * 24 * 100); //100 days by default
define("COOKIE_PATH", ""); //Avaible in whole domain

/**
 * Email Constants - these specify what goes in
 * the from field in the emails that the script
 * sends to users, and whether to send a
 * welcome email to newly registered users.
 */
define("EMAIL_FROM_NAME", "Ivan Novak");
define("EMAIL_FROM_ADDR", "fire1@abv.bg");
define("EMAIL_WELCOME", false);
/**
 * This constant forces all users to have
 * lowercase usernames, capital letters are
 * converted automatically.
 */
define("ALL_LOWERCASE", false);
/**
 * This defines the absolute path
 */
define("ABSPATH", dirname(__FILE__) . '/');
/**
 * This boolean constant controls wheter or
 * not the user to user mail function is active
 */
define("VALIDATE_EMAIL", false);
define("USERS_MAIL_LOGIN", true);
define("USERNAME_USE", true);
?>
