<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: socnet.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

/**
 *
 * 
 * @package users_socnet
 * @author Angel Zaprianov <fire1@abv.bg>
 */
class users_socnet extends kernel {

    var $db = null;
    var $fl = null;
    var $require = null;
    var $fields_name = null;
    var $fields_value = null;
    var $fields_user = null;
    var $html_output = null;
    var $messages = null;
    // ataching data from etc folder
    var $upload_prop;

    const tbl = USERS_SQLTBL_MAIN;
    const tbl_aid = "auth_uid";
    const tbl_prv = "auth_provider";
    const tbl_dnm = USERS_SQLTBL_COL_DNAME;
    const tbl_uid = USERS_SQLTBL_COL_UID;
    const tbl_eml = USERS_SQLTBL_COL_UMAIL;
    const tbl_unm = USERS_SQLTBL_COL_UNAME;
    const tbl_fnm = USERS_SQLTBL_COL_FNAME;
    const tbl_pct = USERS_SQLTBL_COL_AVATAR;
    const tbl_tmz = USERS_SQLTBL_COL_TZONE;
    const tbl_lan = USERS_SQLTBL_COL_LANG;
    const tbl_hsh = USERS_SQLTBL_COL_HASH;
    const tbl_hsg = USERS_SQLTBL_COL_HASH_GENERATED;

    /**
     * Execute all class
     *
     * @param array $arr //values
     */
    public function users_socnet($arr = null) {
        // start using mysql class
        $this->db = new mysql_ai();
        if (is_null($arr))
            return false;;
        $pic = $arr['picture'];
        // fixing image
        $arr['picture'] = $pic;
        if (!empty($arr['error'])) {
            $this->html_out($arr['error']);
        } else {
            if ((bool) $usr = $this->check_user($arr['id'], $arr['provider'], $arr['email'])) {
                global $kernel;
                if (!empty($arr['email'])) {
                    // set with e-mail
                    $kernel->ctrl_cookie("set", $usr[self::tbl_eml], false);
                    header_location("main");
                } else {
                    // set with username
                    $kernel->ctrl_cookie("set", $usr[self::tbl_unm], false);
                    header_location("main");
                }
            } else {
                if (!session_id()) {
                    session_start();
                }
                if (!empty($arr['id']) && !empty($arr['username']) && !empty($arr['email'])) {
                    $_SESSION['userdata'] = $arr;
                }
                $checkinsert = $this->insert_user($arr);
                if ((bool) $checkinsert) {
                    global $kernel;
                    $kernel->ctrl_cookie("set", $checkinsert, false);
                    // clear session
                    unset($_SESSION['usermail_social_insert']);
                    unset($_SESSION['username_social_insert']);
                    unset($_SESSION['userdata']);
                    header_location("main");
                } else {
                    $this->html_out(mysql_error());
                }
            }
        }
    }

    /**
     * Ceck and select user
     *
     * @param mixed $uid
     * @param mixed $provider
     * @param mixed $username
     * @param mixed $mail
     */
    public function check_user($uid, $provider, $mail = null) {

        $table = str_replace(PREFIX, "", self::tbl);
        if (isset($mail)) {
            //
            // Google Facebook login
            $this->db->Select($table, array(self::tbl_eml => $mail));
        } elseif (is_numeric(trim($uid)) && isset($provider)) {
            //
            // Twitter login
            $this->db->Select($table, array(self::tbl_aid => $uid, self::tbl_prv => $provider));
        }
        //
        // Cech the results
        if ($this->db->iRecords == 1) {
            return $this->db->aArrayedResults[0];
        } else {
            return false;
        }
    }

    /**
     * Geting data from provider and load it in mysql
     *
     * @param mixed $uid Provider User ID
     * @param mixed $provider Provider name
     * @param mixed $username In provider UserName
     * @param mixed $email  In provider User E-mail
     * @param mixed $token   Provider Token
     * @param mixed $token_secret Provider Token secret
     * @param mixed $arrOther Thish Array will fill additional information for user if exist in Mysql User fields
     */
    public function insert_user($arr = array()) {
        if (empty($arr['id']) OR empty($arr['username']) OR empty($arr['email']) OR empty($arr['picture'])) {
            $arr = array();
            $arr = array_merge($arr, $_SESSION['userdata']);
        }

        // this is for  TWITTER thing!
        $uid = (int) $arr['id'];
        $provider = $arr['provider'];
        $username = $arr['username'];
        $email = $arr['email'];
        $fullname = $arr['fullname'];
        $picture = $arr['picture'];
        $timezone = $arr['timezone'];
        $language = $arr['language'];
        $arrOther = $arr['other'];
        if (empty($_SESSION['username_social_insert'])) {
            // attach unic username
            if ($this->check_username($username, $provider) AND empty($_POST['username'])) {
                $this->html_out(mysql_error());
                return false;
            } elseIf (isset($_POST['username'])) {
                $_SESSION['username_social_insert'] = $_POST['username'];
                $_SESSION['userdata']['username'] = $_POST['username'];
                // insert username
                $username = ($_POST['username']);
            }
            if (isset($_SESSION['username_social_insert'])) {
                $username = $_SESSION['username_social_insert'];
                $_SESSION['userdata']['username'] = $_SESSION['username_social_insert'];
            }
        }
        if (empty($_SESSION['usermail_social_insert'])) {
            // attach unic usermail
            if ($this->check_usermail($email, $provider) AND empty($_POST['usermail'])) {
                $this->html_out(mysql_error());
                return false;
            } elseIf (isset($_POST['usermail'])) {
                $_SESSION['usermail_social_insert'] = $_POST['usermail'];
                $_SESSION['userdata']['email'] = $_POST['usermail'];
                // insert email
                $email = ($_POST['usermail']);
            } elseif (isset($_SESSION['usermail_social_insert'])) {
                $email = $_SESSION['usermail_social_insert'];
                $_SESSION['userdata']['email'] = $_SESSION['usermail_social_insert'];
            }
        }
        // check name
        $display_name = $this->display_name($fullname);
        // execute filds in users

        if ((bool) $this->select_signUp_fields()) {
            $this->generate_user_fields();

            return false;
        }


        // upload image to our server
        $picture = $this->insert_picture($picture, $username);
        $sql = "INSERT INTO `" . self::tbl . "` 
            (
            `" . self::tbl . "`.`" . self::tbl_aid . "`,
            `" . self::tbl_prv . "`,
            `" . self::tbl_unm . "`,
            `" . self::tbl_eml . "`,
            `" . self::tbl_fnm . "`,
            `" . self::tbl_pct . "`,
            `" . self::tbl_tmz . "`,
            `valid`,
            `" . self::tbl_dnm . "`,
            `" . self::tbl_lan . "`,
            `" . self::tbl_hsh . "`,
            `" . self::tbl_hsg . "`
            {$this->fields_name}   
            )
            VALUES
            (
            '{$uid}',
            '{$provider}',
            '{$username}',
            '{$email}',
            '{$fullname}',
            '{$picture}',
            '{$this->get_timezone($timezone, $language) }',
            '1',
            '{$display_name}',
            '{$language}',
            " . mysql_hash($username) . ",
            " . mysql_utime() . "
            {$this->fields_value}
            );";


        if ((bool) $this->db->ExecuteSQL($sql) or die(mysql_error())) {
            return $email;
        }
    }

    /**
     * Returns first name
     *
     * @param mixed $fullname
     */
    private function display_name($fullname) {
        $nameParts = explode(" ", $fullname);
        return ucfirst($nameParts[0]);
    }

    /**
     * This function check additional user fields in mysql and will
     * perform another function select_signUp_fields" to generate
     * fields that are Required for users.
     *
     * @param mixed $arrOther
     */
    private function select_signUp_fields() {
        $sql = "SELECT * FROM `" . USERS_SQLTBL_FIELD . "`  ORDER BY `fld_order` ASC;";

        $res = mysql_query($sql) or die(mysql_error());

        if (mysql_num_rows($res) == "0") {
            return false;
        }
        $this->fields_name = "";
        $this->fields_value = "";
        $this->fields_user = array();
        $i = 0;
        while ($fild = mysql_fetch_array($res)) {
            if ((!empty($_POST[$fild['fld_name']]) && ($fild['fld_require'] == 'required')) OR (empty($_POST[$fild['fld_name']]) && ($fild['fld_require'] != 'required'))) {
                $this->fields_name .= ", `{$fild['fld_name']}` ";
                $this->fields_value .= ", '" . ($_POST[$fild['fld_name']]) . "'";
            } elseif ($fild['fld_require'] == 'required') {
                $this->require[] = $fild['fld_name'];
                $i++;
                $this->fields_user[] = $fild;
            }
        }
        if ($i >= 1)
            return true;
        else
            return false;
    }

    /**
     * This function generate fields that are Required for users.
     *
     */
    private function generate_user_fields() {
        $this->html_output = "";

        if (!is_array($this->require))
            return false;
        $html = <<<eol
            <!--//[form for additional information]
            * This is not a template
            //-->
            <table border='0' width="80%" align="center">
            <form name="additional" method="post" enctype="multipart/form-data">
eol;
        //////////////////////////////////////
        foreach ($this->fields_user as $field) {
            $html_field = html_form_input($field['fld_type'], $field['fld_name'], $field['fld_value'], $field['fld_attr'], $field['fld_rowscols']);
            if ($field['fld_require']) {
                $rqr = $usrconf['require_image'] = "<font color='red'>*</font>";
            }
            $html.= <<<html
<tr>
<td class="fld-title">{$field['fld_title']} {$rqr} </td> 
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
        global $language;
        $html.= <<<eol
            </table>
            <p> <input type="submit" name="submit" value="{$language['lan.submit']}"> </p>
            </form>

eol;
        $this->html_output.= $html;
    }

    /**
     * This function will check login username in database
     * if username exists will generate form for suggesting other username.
     *
     * @param mixed $login_name
     * @param mixed $provider
     */
    private function check_username($login_name, $provider) {
        $_SESSION["datacheck"] = kernel::uni_session_name();
        $this->db->aArrayedResults = false;
        $table = str_replace(PREFIX, "", self::tbl);
        $this->db->Select($table, array(self::tbl_unm => $login_name));
        if ((bool) $this->db->aArrayedResults[0][self::tbl_unm]) {
            $this->html_output = null;
            $postRequest = kernel::base_tag("{host}{module_dir}datacheck?socnet");
            $images_fld = kernel::base_tag("{host}{module_dir}images/");
            global $language;
            $html = <<<eol
            
            <!--//[form for additional information]
            * This is not a template
            //-->

<script type="text/javascript">           
$(document).ready(function(){
 $('#tick_username').hide();$('#submit_username').hide();
$('#username').keyup(username_check);
});

function username_check(){    
    var username = $('#username').val();
    if(username == "" || username.length < 4){
        $('#username').css('border', '3px #CCC solid');
        $('#tick_username').hide();
    }else{
        jQuery.ajax({
            type: "POST",
            url: "{$postRequest}",
            data: 'check_username='+ username,
            cache: false,
            success: function(response){
                if(response >= 1){
                    $('#username').css('border', '3px #C33 solid');    
                    $('#tick_username').stop().hide();
                    $('#submit_username').stop().hide();
                    $('#submit_username').attr('disabled', 'disabled');
                }else{
                    $('#username').css('border', '3px #090 solid');
                    $('#cross_username').hide();
                    $('#tick_username').fadeIn();
                    $('#submit_username').removeAttr("disabled");
                    $('#submit_username').fadeIn(1000);
                }
            }
        });
    }
}
</script>
            <form name="usernaeme" method="post" enctype="multipart/form-data">
            <table border='0' width="80%" align="center">
             <tr>
                    <td class="fld-title">{$language['users.lan.uname']}:</td> 
                </tr> 
                <tr>        
                    <td class="fld-content" >
                     <input type="text" name="username" id="username" value="{$login_name}" maxlength="30"> 
                     <img id="tick_username" src="{$images_fld}tick.png" width="16" height="16"/>
                     <img id="cross_username" src="{$images_fld}cross.png" width="16" height="16"/>
                     </td>
                </tr> 
                <tr> <td class="fld-space">&nbsp; </td> </tr>       
            </table>           
            <p> <input type="submit" name="submit" value="{$language['lan.submit']}" id="submit_username"> </p>
            </form>
eol;
            $this->html_output.= $html;
            $this->db->aArrayedResults = null;
            return true;
        } else
            return false;
    }

    /**
     * put your comment there...
     *
     * @param mixed $login_mail
     * @param mixed $provider
     */
    private function check_usermail($login_mail, $provider) {
        if (!defined("USERS_REQUIRE_MAIL") OR USERS_REQUIRE_MAIL == false)
            return false;
        $_SESSION["datacheck"] = kernel::uni_session_name();
        $this->db->aArrayedResults = false;
        $table = str_replace(PREFIX, "", self::tbl);
        $bool = $this->db->Select($table, array(self::tbl_eml => $login_mail));
        // var_dump( $this->db->ArrayResult());
        if (((bool) $this->db->aArrayedResults[0][self::tbl_eml]) OR ($this->check_email_nosql($login_mail) == false)) {
            $this->html_output = null;
            $postRequest = kernel::base_tag("{host}{module_dir}datacheck?socnet");
            $images_fld = kernel::base_tag("{host}{module_dir}images/");
            global $language;
            $html = <<<eol
            <!--//[form for additional information]
            * This is not a template
            //-->
<script type="text/javascript">           
$(document).ready(function(){
 $('#tick_usermail').hide();$('#submit_usermail').hide();
$('#usermail').keyup(usermail_check);
});
function usermail_check(){    
var usermail = $('#usermail').val();
if(usermail == "" || usermail.length < 4){
$('#usermail').css('border', '3px #CCC solid');
$('#tick_usermail').hide();
}else{
jQuery.ajax({
   type: "POST",
   url: "{$postRequest}",
   data: 'check_usermail='+ usermail,
   cache: false,
   success: function(response){
if(response == 1){
    $('#username').css('border', '3px #C33 solid');    
    $('#tick_usermail').stop().hide();
    $('#submit_usermail').stop().hide();
  $('#submit_usermail').attr('disabled', 'disabled');
    }else{
    $('#usermail').css('border', '3px #090 solid');
    $('#cross_usermail').hide();
    $('#tick_usermail').fadeIn();
    $('#submit_usermail').removeAttr("disabled");
    $('#submit_usermail').fadeIn(1000);
         }
}
});
}
}
</script>
            <form name="usermail" method="post" enctype="multipart/form-data">
            <table border='0' width="80%" align="center">
                <tr>
                    <td class="fld-title">{$language['users.lan.email']}:</td> 
                </tr>               
                <tr>        
                    <td class="fld-content" >
                        <input type="text" name="usermail" id="usermail" value="{$login_mail}" maxlength="30">                    
                        <img id="tick_usermail" src="{$images_fld}tick.png" width="16" height="16"/>
                     <img id="cross_usermail" src="{$images_fld}cross.png" width="16" height="16"/>
                     </td>
                </tr> 
                <tr> <td class="fld-space">&nbsp; </td> </tr>       
            </table>  
            <p> <input type="submit" name="submit" value="{$language['lan.submit']}" id="submit_usermail"> </p>
            </form>
eol;
            $this->html_output.= $html;
            $this->db->aArrayedResults = null;
            return true;
        } else
            return false;
    }

    /**
     * Insert/rebiuld new username
     *
     */
    public function mysql_check_username() {
        $this->db->iRecords = 0;
        $username = $_POST['check_username'];
        if (isset($username)) {
            $this->db->Select(self::tbl, array(self::tbl_unm => $username));
            $GLOBALS['THEME'] = $this->db->iRecords;

            return true;
        } else {
            $GLOBALS['THEME'] = "1";
            return false;
        }
    }

    /**
     * Insert/rebiuld new username
     *
     */
    public function mysql_check_usermail() {
        $usermail = $_POST['check_usermail'];
        global $THEME;
        if (isset($usermail)) {
            $this->db->Select(str_replace(PREFIX, "", self::tbl), array(self::tbl_eml => $usermail));
            $email = $usermail;
            if (preg_match("/[\\000-\\037]/", $email)) {
                $THEME = "1";
                return false;
            }
            $pattern = "/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD";
            if (!preg_match($pattern, $email)) {
                $THEME = "1";
                return false;
            }
            // Validate the domain exists with a DNS check
            // if the checks cannot be made (soft fail over to true)
            list($user, $domain) = explode('@', $email);
            if (function_exists('checkdnsrr')) {
                if (!checkdnsrr($domain, "MX")) { // Linux: PHP 4.3.0 and higher & Windows: PHP 5.3.0 and higher
                    $THEME = "1";
                    return false;
                }
            } else if (function_exists("getmxrr")) {
                if (!getmxrr($domain, $mxhosts)) {
                    $GLOBALS['THEME'] = "1";
                    return false;
                }
            } else if ((bool) $this->db->aArrayedResults[0]) {
                $GLOBALS['THEME'] = "1";
            } else {
                $THEME = "";
            }
            return true;
        } else
            return false;
    }

    private function check_email_nosql($email) {
        //check for all the non-printable codes in the standard ASCII set,
        //including null bytes and newlines, and exit immediately if any are found.
        if (preg_match("/[\\000-\\037]/", $email)) {
            return false;
        }
        $pattern = "/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD";
        if (!preg_match($pattern, $email)) {
            return false;
        }
        // Validate the domain exists with a DNS check
        // if the checks cannot be made (soft fail over to true)
        list($user, $domain) = explode('@', $email);
        if (function_exists('checkdnsrr')) {
            if (!checkdnsrr($domain, "MX")) { // Linux: PHP 4.3.0 and higher & Windows: PHP 5.3.0 and higher
                return false;
            }
        } else if (function_exists("getmxrr")) {
            if (!getmxrr($domain, $mxhosts)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check picture and return data
     *
     * @param mixed $picture
     */
    private function insert_picture($picture, $username) {
        if (!empty($picture)) {
            $chext = pathinfo($picture, PATHINFO_EXTENSION);
            $ext = ((bool) $chext) ? $chext : "jpg";
            $fileimage = uniqid() . "." . $ext;
            $newFile = kernel::base_tag("{root}{trunk}{trunk_temp}" . $fileimage);
            if (file_put_contents($newFile, file_get_contents($picture))) {
                $this->picture($newFile, $username);
            }
            if (file_exists(kernel::base_tag("{root}{publicfiles}{module_dir}{$username}/{images}{$fileimage}"))) {
                $picture = $fileimage;
            } else {
                $picture = null;
            }
        }
        return $picture;
    }

    /**
     * This will upload the image from provider
     *
     * @param mixed $url
     */
    private function picture($copied_file, $username) {
        $im_folder = kernel::base_tag("{root}{publicfiles}{module_dir}{$username}/{images}");
        $th_folder = kernel::base_tag("{root}{publicfiles}{module_dir}{$username}/{thumbs}");
        if (!is_dir($th_folder)) {
            kernel::mkdirTree($th_folder);
        }
        if (!is_dir($im_folder)) {
            kernel::mkdirTree($im_folder);
        }
        $foto_upload = new upload_photo;
        $json['size'] = 512 * 1024;
        $json['img'] = '';
        $foto_upload->upload_dir = kernel::base_tag(pathinfo($copied_file, PATHINFO_DIRNAME) . "/");
        $foto_upload->foto_folder = $im_folder;
        $foto_upload->thumb_folder = $th_folder;
        $foto_upload->extensions = array(".jpg", ".jpeg", ".gif", ".png");
        $foto_upload->language = "en";
        // Let's find upload properties and set-up the upload class
        $this->from_etc_upload();
        if (is_array($this->upload_prop)) {
            $foto_upload->x_max_size = $this->upload_prop['upload.image.xmax.size'];
            $foto_upload->y_max_size = $this->upload_prop['upload.image.ymax.size'];
            $foto_upload->x_max_thumb_size = $this->upload_prop['upload.thumb.xmax.size'];
            $foto_upload->y_max_thumb_size = $this->upload_prop['upload.thumb.ymax.size'];
        } else {
            die(" Cannot locate upload.prop!");
        }
        $foto_upload->file_copy = pathinfo($copied_file, PATHINFO_BASENAME);
        $foto_upload->http_error = null;
        $foto_upload->rename_file = true;
        $foto_upload->use_image_magick = false;
        $foto_upload->process_image(false, true, false, 80);
        $filename = $foto_upload->upload_dir . $foto_upload->file_copy;
        $thumb = $foto_upload->thumb_folder . $foto_upload->file_copy;
        if (!file_exists($foto_upload->thumb_folder . $foto_upload->file_copy)) {
            $file_name_src = $filename;
            $quality = 80;
            $size = getimagesize($file_name_src);
            if ($this->larger_dim == "x") {
                $w = number_format($target_size, 0, ',', '');
                $h = number_format(($size[1] / $size[0]) * $target_size, 0, ',', '');
            } else {
                $h = number_format($target_size, 0, ',', '');
                $w = number_format(($size[0] / $size[1]) * $target_size, 0, ',', '');
            }
            $dest = imagecreatetruecolor($w, $h);
            imageantialias($dest, TRUE);
            $src = imagecreatefromjpeg($file_name_src);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
            imagejpeg($dest, $thumb, $quality);
        }
        $foto_upload->del_temp_file($copied_file);
        $this->messages = $foto_upload->show_error_string();
    }

    /**
     * This will generate theme output for additional user
     * information.
     *
     */
    public function html_out($sql = null) {
        global $language;
        $txt = kernel::base_tag($language['users.soc.wreq.txt']);
        theme::content(Array($language['users.soc.wreq.ttl'], "
                    <p>{$txt}<br />
                    {$this->html_output} </p> 
                    <p> {$this->messages} </p> 
                    <p> {$sql} </p>
                    "));
    }

    /**
     * This function will find "upload.prop"
     * and will return it in  array "$this->upload_prop"
     *
     */
    private function from_etc_upload() {
        $etc = array();
        global $CONFIGURATION, $kernel;
        $etc = $kernel->etc("system_requests", "reserved_url");
        global $CONFIGURATION;
        $file = str_replace("[system]", kernel::base_tag("{system}"), $etc['request.upload']);
        // lets do not forget main prop
        $this->upload_prop = combine_master_with_slave($CONFIGURATION, $kernel->loadprop($file, 1));
    }

    /**
     * Geting time zone from place
     *
     * @param mixed $zone
     */
    private function get_timezone($zone, $language = null) {
        $check_zone = explode("/", $zone);
        if (isset($check_zone[0]) && isset($check_zone[1])) {
            return $zone;
        } elseIf (!empty($zone)) { // check from place
            $allzones = timezone_identifiers_list();
            foreach ($allzones as $key => $place) {
                if (strstr($place, $zone, true)) {
                    return ($allzones[$key]);
                }
            }
        } elseIf (isset($language) && function_exists("geoip_time_zone_by_country_and_region")) {
            return geoip_time_zone_by_country_and_region($language);
        } else {
            return null;
        }
    }

    /**
     * Converting Object to array
     *
     * @param mixed $result
     */
    public function object_2_array($result) {
        $array = array();
        foreach ($result as $key => $value) {
            if (is_object($value)) {
                $array[$key] = self::object_2_array($value);
            }
            if (is_array($value)) {
                $array[$key] = self::object_2_array($value);
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    private function image_copy($uri_image, $server_dir) {
        $url = $uri_image;
        $dir = $server_dir;
        $lfile = fopen($dir . basename($url), "w");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)');
        curl_setopt($ch, CURLOPT_FILE, $lfile);
        fclose($lfile);
        curl_close($ch);
    }

}
