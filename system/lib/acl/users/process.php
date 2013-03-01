<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: process.php 115 2013-02-08 16:27:29Z fire $
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
if (!defined('FIRE1_INIT')) {
    @header_remove();
    header("location:error400");
    exit();
}

class users_process {

    var $db;
    var $cook_user = array();
    var $fields_user = array();
    var $fields_err = array();
    var $fields_err_message = array();

    /**
     * CONNECT TO MYSQL
     * param $db
     */
    public function users_db() {
        global $db;
        $this->db = $db;
        if (!$this->db) {
            $this->db = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
            mysql_select_db(DB_NAME, $this->db) or die(mysql_error());
        }
    }

    /**
     *   LOGIN EXECUTION
     *
     * @param string $username   // username or mail
     * @param string $password   // password
     * @return mixed
     */
    private function _signIn($username, $password) {
        if (!get_magic_quotes_gpc() && !defined("MRES")) {
            $username = addslashes($username);
            $password = addslashes($password);
        }
        if (defined("USERS_MAIL_LOGIN")) {
            $sql = "SELECT `" . USERS_SQLTBL_COL_UID . "`,`" . USERS_SQLTBL_COL_UNAME . "`,`" . USERS_SQLTBL_COL_UPASS . "`,`" . USERS_SQLTBL_COL_UMAIL . "` 

                FROM `" . USERS_SQLTBL_MAIN . "` WHERE `" . USERS_SQLTBL_COL_UMAIL . "`='$username'   OR  `" . USERS_SQLTBL_COL_UNAME . "`='$username' LIMIT 1;";
        } else {
            $sql = "SELECT `" . USERS_SQLTBL_COL_UID . "`,`" . USERS_SQLTBL_COL_UNAME . "`,`" . USERS_SQLTBL_COL_UPASS . "`,`" . USERS_SQLTBL_COL_UMAIL . "`  

                FROM `" . USERS_SQLTBL_MAIN . "` WHERE `" . USERS_SQLTBL_COL_UNAME . "`='$username' LIMIT 1;";
        }
        $sql_request = mysql_query($sql) or die(mysql_error());
        if (!$sql_request || (mysql_numrows($sql_request) < 1)) {
            return 1;
            ; //Indicates username failure
        }
        if ($this->cook_user = mysql_fetch_array($sql_request)) {
            $this->cook_user[USERS_SQLTBL_COL_UNAME] = stripslashes($this->cook_user[USERS_SQLTBL_COL_UNAME]);
            $this->cook_user[USERS_SQLTBL_COL_UPASS] = stripslashes($this->cook_user[USERS_SQLTBL_COL_UPASS]);
            $username = stripslashes($username);
            $password = stripslashes($password);
        } else {
            return 2;
            ;
        }
        if ($this->cook_user[USERS_SQLTBL_COL_UPASS] != password($password)) {
            return 3;
            ;
        }
        kernel::ctrl_cookie("set", $this->cook_user[USERS_SQLTBL_COL_UNAME], true);
        return 0;
        ;
    }

    /**
     *   LOGIN MESSAGE RETURN
     *
     * @param string $username   // username or mail
     * @param string $password   // password
     * @return mixed
     */
    public function signIn($username, $password) {
        if ($_POST['signin']) {
            $outcase_num = $this->_signIn($username, $password);
            if (!$outcase_num) {
                if (isset($_GET['callback'])) {
                    $uri = $_GET['callback'];
                } else {
                    $uri = null;
                }
                header_location($uri);
            } else {
                $output = array("1" => USR_LAN_ERR_LOG_1, "2" => USR_LAN_ERR_LOG_2, "3" => USR_LAN_ERR_LOG_3);
                return $output[$outcase_num];
            }
        }
    }

    /**
     * Fields generated from mysql
     *
     */
    public function signUp_fields() {
        global $usrconf;
        $sql = "SELECT * FROM `" . USERS_SQLTBL_FIELD . "` WHERE `fld_require`!='hidden'  ORDER BY `fld_order` ASC;";
        $sqlrequest = mysql_query($sql) or die(mysql_error());
        $i = 0;
        while ($row = mysql_fetch_array($sqlrequest)) {
            $this->fields_user[$i] = $row;
            $i++;
        }
        if (!function_exists('html_form_input')) {
            kernel::ERROR('[!] PHP function <b>html_form_input</b> DO NOT EXIST', 'php', 'This function need to generate html input/s do not exist in dollop library!' . " \n FILE:[users/(incude)/]database.inc.php \n LINE:" . __LINE__);
            return false;
        } else {
            $html = "<table border='0'>";
            //////////////////////////////////////
            foreach ($this->fields_user as $field) {
                $html_field = html_form_input($field['fld_type'], $field['fld_name'], $field['fld_value'], $field['fld_attr'], $field['fld_rowscols']);
                if ($field['fld_require'] == "required") {
                    $rqr = $usrconf['require_image'] = "*";
                } else {
                    $rqr = null;
                }
                $html.= <<<html
<tr>
<td class="fld-title"><font face="Arial" size="+2" color="Red">{$rqr}</font> {$field['fld_title']}  </td> 
</tr> 
<tr>        
<td class="fld-content" ><div id="{$field['fld_name']}"> {$html_field} <div class="{$field['fld_name']}"> </div></div> </td>
</tr> 
<tr> 
<td class="fld-descr">{$field['fld_descr']}</td> 
</tr> 
<tr> <td class="fld-space">&nbsp; </td> </tr>       
html;
            }
            //////////////////////////////////////
        }
        $html.= '</table>';
        return $html;
    }

    /**
     * Check email in mysql
     *
     * @param mixed $usermail
     * @param bool $protect // use mysql_real_escape_string
     * @return bool
     */
    public function select_check_mail($usermail, $protect = true) {
        if ((bool) $protect)
            $usermail = mysql_real_escape_string($usermail);;
        $result = mysql_query("SELECT 

                    `" . USERS_SQLTBL_COL_UMAIL . "` 

                    FROM `" . USERS_SQLTBL_MAIN . "`

                    WHERE `" . USERS_SQLTBL_MAIN . "`.`" . USERS_SQLTBL_COL_UMAIL . "`='{$usermail}' LIMIT 1;") or die(mysql_error());
        return $result;
    }

    /**
     * Check username in mysql
     *
     * @param mixed $username
     * @param bool $protect // use mysql_real_escape_string
     * @return bool
     */
    public function select_check_uname($username, $protect = true) {
        if ((bool) $protect)
            $username = mysql_real_escape_string($username);;
        return mysql_query("SELECT 
                `" . USERS_SQLTBL_COL_UNAME . "` FROM 
                `" . USERS_SQLTBL_MAIN . "` WHERE 
                `" . USERS_SQLTBL_COL_UNAME . "`='{$username}' LIMIT 1;");
    }

    /**
     * Insert user in mysql
     *
     * @param array $post
     */
    public function _signUp($post) {
        $sql_cols = array();
        $err = false;
        if (!get_magic_quotes_gpc() && !defined("MRES")) {
            $post = array_map('addslashes', $post);
        }
        if (ALL_LOWERCASE) {
            $post['username'] = strtolower($post['username']);
            $post['usermail'] = strtolower($post['usermail']);
        }
        // check email
        if (empty($post['usermail'])) {
            $this->fields_err['require'][] = 'usermail';
            $err = true;
        } else {
            $sql_cols[] = USERS_SQLTBL_COL_UMAIL;
        }
        // valid email
        if (!$this->check_email($post['usermail'])) {
            $this->fields_err['valid'][] = 'usermail';
            $err = true;
        } else {
            $sqlrequest = self::select_check_mail($post['usermail'], false);
            if (!isset($sqlrequest)) {
                $this->fields_err['error'][] = 'usermail';
                $err = true;
            } else {
                if (mysql_num_rows($sqlrequest)) {
                    $this->fields_err['take'][] = 'usermail';
                    $err = true;
                }
            }
        }
        if (constant("USERNAME_USE")) {
            // check username
            if (empty($post['username'])) {
                $this->fields_err['require'][] = 'username';
                $err = true;
            } else {
                // taked username?
                $sqlrequest = self::select_check_uname($post['username'], false);
                if ((bool) $sql_request) {
                    $this->fields_err['error'][] = 'username';
                    $err = true;
                }
                if ($sql_request || (mysql_numrows($sqlrequest) > 0)) {
                    $this->fields_err['take'][] = 'username';
                    $err = true;
                }
            }
            $user_name_row = USERS_SQLTBL_COL_UNAME . "," . USERS_SQLTBL_COL_DNAME . ",";
            $user_name_val = "'" . $post['username'] . "'," . "'" . $post['username'] . "',";
        }
        if (empty($post['userpass'])) {
            $this->fields_err['require'][] = 'userpass';
            $err = true;
        }
        $sql_cols = "";
        $sql_val = "";
        // execute   signUp_fields if is  not before
        if (!is_array($this->fields_user)) {
            $this->signUp_fields();
        }
        // check sql filds
        foreach ($this->fields_user as $fild) {
            if (empty($post[$fild['fld_name']]) AND ($fild['fld_require'] == "required")) {
                $this->fields_err['require'][] = $fild['fld_name'];
                $err = true;
            }
            $sql_cols.= ",`{$fild['fld_name']}`";
            $sql_val.= ",'{$post[$fild['fld_name']]}'";
        }
        // this will fail Sign Up
        if ($err)
            return false;;
        $hex = md5(HEX);
        $hash_key = "$hex-{$post['username']}-";
        $post['userpass'] = password($post['userpass']);
        $sql = "INSERT INTO `" . USERS_SQLTBL_MAIN . "` 



            ({$user_name_row} `" . USERS_SQLTBL_COL_UMAIL . "`,`" . USERS_SQLTBL_COL_UPASS . "` {$sql_cols}, 



            `" . USERS_SQLTBL_COL_HASH . "`,`" . USERS_SQLTBL_COL_HASH_GENERATED . "` )



            VALUES



            ({$user_name_val} '{$post['usermail']}', '{$post['userpass']}'  {$sql_val},

            PASSWORD( '{$hash_key}' + UNIX_TIMESTAMP( NOW() ) ), UNIX_TIMESTAMP( NOW() )  );";
        if (mysql_query($sql)) {
            return true;
        } else {
            $this->fields_err['error'][] = 'insert';
            ECHO mysql_error();
            $err = true;
            return false;
        }
    }

    /**
     *
     *
     */
    public function signUp() {
        if ($_POST['signup']) {
            if ($this->_signUp($_POST)) {
                return USR_LAN_OK_REG;
                ;
            } else {
                $text = "";
                foreach ($this->fields_err as $key => $fields) {
                    foreach ($fields as $field) {
                        $end_const = strtoupper($key) . "_" . strtoupper($field);
                        if (defined("USR_LAN_ERR_REG_" . $end_const)) {
                            $text.= '<div class="usr-err">' . constant("USR_LAN_ERR_REG_" . $end_const) . "</div>";
                        } else {
                            $te = constant("USR_LAN_ERR_REG_REGULAR");
                            foreach ($this->fields_user as $arr_fieldUsr) {
                                $key = array_search($field, $arr_fieldUsr);
                                $title_fld = $this->fields_user[$key]['fld_title'];
                            }
                            $text.= <<<eol

<script type="text/javascript"> 

    $(document).ready(function() {        

             jQuery("#{$field}").css('backgroundColor', 'rgba(245,100,100,0.5)');    

      });       

             </script>

             

             <div class="usr-err">{$te}  <b>{$title_fld}</b> </div>            

eol;
                        }
                    }
                }
                return $text;
            }
        }
    }

    /**
     * Return array of user row that is logged in
     *
     */
    public function my_user() {
        $sql = "SELECT * FROM `" . USERS_SQLTBL_MAIN . "` WHERE `" . USERS_SQLTBL_COL_UID . "`='" . USER_ID . "' LIMIT 1;";
        $request = mysql_query($sql) or die(mysql_error());
        if ($row = mysql_fetch_array($request)) {
            return $row;
            ;
        }
    }

    public function user_content() {
        
    }

    /**
     * check mail
     *
     * @param mixed $email
     */
    private function check_email($email) {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}
