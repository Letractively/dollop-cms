<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: form.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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

if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
//STEP 1
if (file_exists(self::$KERNEL['MAIN']['dp.db']) && self::SESSION('installation_dollop')) {
    // in set up sql connection
    if (!defined("db.INIT")) {
        define("db.INIT", true);
    }
    require_once (self::$KERNEL['MAIN']['dp.db']);
}
if (!$_POST && !file_exists(self::$KERNEL['MAIN']['dp.db']) && self::SESSION('installation_dollop')) {
    $txt = '      <h2><span>Set-up</span>  MySQL</h2>
        <form id="form1" name="form1" method="post" action="">
        <div class="td">
        <strong>Database</strong> host location:<br />
        <input type="text" name="host" />
        </div>

        <div class="td">
        <strong>Database</strong> username:<br />
        <input type="text" name="user" />
        </div>
        <div class="td">
        <strong>Database</strong> password:<br />
        <input type="password" name="pass" />
        </div>
        <div class="td"><strong>Database</strong> to select:<br />
        <input type="text" name="db" />
        </div>
        <div class="td">
        <strong>Database</strong> prefix:<br />
        <input type="text" name="prefix" />
        </div>
         <input name="button" type="image" src="design/buttons/EN/submit-white.gif" alt="submit" align="middle" />
        </form>';
    $sect = '<span>1</span> <a href="#">2</a>  <a href="#">3</a> <a href="#">4</a>';
    //STEP 2
} elseif ($_POST && !file_exists(self::$KERNEL['MAIN']['dp.db'])) {
    $sect = '<a href="#">1</a> <span>2</span>  <a href="#">3</a> <a href="#">4</a>';
    if (!$_POST['host'] | !$_POST['user'] | !$_POST['pass'] | !$_POST['db']) {
        die(" Empty field! <br /> Go Back!");
    }
    require_once (self::$CONFIGURATION['install'] . "/createdbconn.php");
    $fp = fopen(self::$KERNEL['MAIN']['dp.db'], "w+");
    fwrite($fp, $data_db);
    chmod(self::$KERNEL['MAIN']['dp.db'], 0600);
    $data_db = "";
    $fp = "";
    if (file_exists(self::$KERNEL['MAIN']['dp.db'])) {
        $txt = '      <h2><span>Successfully</span> created the website MySQL file</h2>
            <p>
            MySQL  configuration file is created successfully.
            </p>
            <p>
            Go to the next step  for installation of  mysql tables.</p><br />
            <a href="" style="padding:10px; background-color:#E0E0E0;"> NEXT >> </a>
            <p> &nbsp;</p>
            <p>
            <div class="comment"><b>In case of problem:</b><br />
            <small> You do not have Apache write permission on this server. Please fix this to contionue.</small>
            </div>
            </p>
            ';
    } else {
        $txt = '      <h2><span>installation</span> PROBLEM!</h2>
            Cannot Write ' . self::$KERNEL['MAIN']['dp.db'] . ' file!    <br />
            <div class="comment"> Please check for write rights of php/apache user and try agen (refresh the page).    </div>

            ';
    }
    //STEP 3
} elseIf (file_exists(self::$KERNEL['MAIN']['dp.db']) && empty($_POST['superadmin'])) {
    $sect = '<a href="#">1</a>  <a href="#">2</a> <span>3</span> <a href="#">4</a>';
    require_once (self::$KERNEL['MAIN']['dp.db']);
    #system
    $handle = self::$CONFIGURATION['install'] . "/install.sql";
    global $CONFIGURATION;
    $CONFIGURATION = self::$CONFIGURATION;
    if ((bool)$handle_back = $this->read_sql($handle)) {
        $hashed = md5(HEX);
        $txt = <<<eol
            <script>
            $(document).ready(function(){
            $('#resize-dataq small').hide();
            $('#resize-dataq').delay(3000).animate({ height: '300px'}, { queue: true, duration: 1000,
            complete: function() {

                        $('#resize-dataq small').each(function (i) {

                $(this).show(i * 300);
            });
            $('#resize-dataq').delay(3000).animate({ height: '45px'}, { queue: true, duration: 1000 });
            }
            });

            });
            </script>
            <h2><span>installation</span> of website MySQL </h2>
            Inserting MySQL tables in server.
            <p>
            Go to next step  if all sql data is executed! <br />
            <div class="handle_back" id="resize-dataq" > <pre>{$handle_back}</pre> </div>
            </p>

            <p>
            <h2><span>Creating</span> super admin:<br /> </h2>
            <form id="form1" name="form1" method="post" action="">
            <div class="td">
            <strong>Super Admin</strong> name<br />
            <input type="text" name="username" />
            </div>

            <div class="td">
            <strong>Super Admin</strong> password:<br />
            <input type="password" name="password" />
            </div>

            <div class="td">
            <strong>Super Admin</strong> email address:<br />
            <input type="text" name="email" />
            </div>



            <input name="superadmin" type="image" src="design/buttons/EN/submit-white.gif" alt="submit" value="true" align="middle" />
            <input type="hidden" name="superadmin" value="true">
            </form>
            </p>


            <p>
            <div class="comment"><b>In case of problem:</b><br />
            <small> Check "install.sql" file in "install" folder to fix the problem
            and delete "dp.php" file from server and then try agen.</small></div>
            </p>
eol;
    } else {
        $txt = '      <h2><span>installation</span> PROBLEM! </h2>
            Can not find:  ' . self::$CONFIGURATION['install'] . "/install.sql" . ' file.
            <p> Please check folder and restore the file to continue... instalation</p>
            <a href="' . $_SERVER['PHP_SELF'] . '" style="padding:10px; background-color:#E0E0E0;"> NEXT >> </a>
            ';
    }
    //STEP 4
} elseIf (file_exists(self::$KERNEL['MAIN']['dp.db']) && self::SESSION('installation_dollop') && mysql_query(" SELECT `ID`  FROM `" . PREFIX . "preferences` WHERE " . PREFIX . "preferences.ID='1'; ") && isset($_POST['superadmin']) && !file_exists("config.php")) {
    $sect = '<a href="#">1</a>  <a href="#">2</a> <a href="#">3</a> <span>4</span> ';
    $txt = '<h1> <span>Installation</span> of the configuration in Dollop is Successful ! </h1>
        ';
    if (isset($_POST['superadmin']) && !empty($_POST['superadmin']) && !empty($_POST['password'])) {
        // Hashig User data
        $hex = self::$CONFIGURATION['HEX'];
        $password = password($_POST['password'], $hex);
        $hash_key = hash_key($_POST['username'], $hex);
        $sql = "INSERT INTO `" . PREFIX . "users`
            (`username`, `password`, `userid`, `userlevel`, `usermail`, `valid`, `hash`, `hash_generated`)
            VALUES
            ('{$_POST['username']}','{$password}','1','9','{$_POST['email']}','1',

            PASSWORD( '{$hash_key}' + UNIX_TIMESTAMP( NOW() ) ), UNIX_TIMESTAMP( NOW() )

            );";
        /// Set def mail
        if (!empty($_POST['email'])) {
            mysql_query("UPDATE `" . PREFIX . "preferences` SET `site_mail`='{$_POST['email']}' WHERE `ID`=1; ");
        }
        if (mysql_query($sql)) {
            $txt.= <<<eol
        <p>&nbsp;</p>
        <p>
        <h2><span>Super</span> admin is created Successfully!</h2>
        <br />

        <h1>All done here ! </h1>
        <p>
        Thank you {$_POST['username']} for the installation of Dollop and we wish you a productive and flawless use of your new website.
        </p>
        </p>
eol;
        } else {
            $mysql_error = mysql_error();
            $txt.= <<<eol
            <p>{$_POST['username']}, we have BAD news .... for you.</p>
            It was the last step of installation and the process cannot reversed to process that can make a fix.<br />
            You must do this step manually.  <br />
            Register a new user and must set-up col of user field`userlevel` to 9.      <br />
         <p> MySQL log:<br />
         <div class="handle_back">{$mysql_error} </div>
         </p>
eol;
        }
    }
    $txt.= '<br />      <a href="" style="padding:10px; background-color:#E0E0E0;"> NEXT >> </a>  ';
    require_once (self::$CONFIGURATION['install'] . "/createdbconn.php");
    $fp = fopen("config.php", "w+");
    fwrite($fp, $data_confINIT);
    chmod(self::$KERNEL['MAIN']['dp.db'], 0600);
    $data_confINIT = "";
    $fp = "";
    //close instalation
    unlink(self::$KERNEL['MAIN']['dp.boot']);
    self::SESSION_CILL("installation_dollop");
}
$php_info = array();
$php_info['phpversion'] = phpversion();
$php_info['display_errors'] = ini_get('display_errors');
$php_info['register_globals'] = ini_get('register_globals');
$php_info['post_max_size'] = ini_get('post_max_size');
$php_info['memory_limit'] = ini_get("memory_limit");
$php_info['gc_maxlifetime'] = ini_get("session.gc_maxlifetime");
$php_info['cookie_path'] = ini_get("session.cookie_path");
$php_info['output_buffering'] = ini_get("output_buffering");
$php_info['find_SQL_Version'] = find_sql_version();
// Checking extensions
$error_install = "";
if ($error_install = installation_extension_check(self::$CONFIGURATION)) {
    $txt = <<<text
  <div class="error">   $error_install</div>
text;
}
// get codname of system
$script_codename = self::$CONFIGURATION['codname'];
$refresh = "";
if (empty($txt)) {
    $txt = <<<eol
        <h2>Build system dependency...</h2>

        <p>
        Page will be reloadet automatically ... <br />
         »Click <a href="/">here</a> to manually reload.
        </p>

eol;
    $refresh = '<meta http-equiv="refresh" content="1" />';
}
echo <<<html
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>dollop Installation</title>
<script type="text/javascript" src="/jquery/jquery.js"></script>
{$refresh}

<script type="text/javascript">
$(document).ready(function(){
    $('.page').hide();$('.body').hide();$('.gadget').hide();
var new_height = $(window).height();


    $('.page').height(new_height);
    $('.body').height(new_height -433);
    $('.gadget').height(new_height -430);

    $('.page').fadeIn(2000);
    $('.body').delay(2000).show(1200);
    $('.gadget').delay(2000).show(1200);
});
</script>
<link href="design/install/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="page">

<div class="top"></div>

<div class="content">
<div class="gadget">
<enter>
<img src="design/ads/dollop.png" alt="dollop" width="160" height="45" border="0" align="right" /><br />
<div class="codename"><small>code name:</small>{$script_codename}</div>
</center>
<div class="clr"></div>
<p> &nbsp;</p>
<p>
          <!--~ Server information -->

     <div class="php_inf"><span>PHP</span> version:             {$php_info['phpversion']} </div>
     <div class="php_inf"><span>PHP</span> display errors :     {$php_info['display_errors']} </div>
     <div class="php_inf"><span>PHP</span> register globals :   {$php_info['register_globals']} </div>
     <div class="php_inf"><span>PHP</span> post max size :      {$php_info['post_max_size']} </div>
     <div class="php_inf"><span>PHP</span> memory limit :       {$php_info['memory_limit']} </div>
     <div class="php_inf"><span>PHP</span> gc maxlifetime:      {$php_info['gc_maxlifetime']} </div>
     <div class="php_inf"><span>PHP</span> cookie path:         {$php_info['cookie_path']} </div>
     <div class="php_inf"><span>PHP</span> output buffering:    {$php_info['output_buffering']} </div>
     <div class="php_inf"><span>MySQL</span>  version is:       {$php_info['find_SQL_Version']} </div>
</p>
<br />
<br />
<p class="pages"> <b>{$sect}</b> </p>
</div>
<div class="body">{$txt} </div>
<div class="clr"></div>
<div class="bottom"></div>
</body>
</html>

html;
exit();
?>
