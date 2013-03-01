<?php

/**
  ============================================================
 * Last committed:      $Revision: 121 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-01 15:54:10 +0200 (ïåò, 01 ìàðò 2013) $
 * ID:                  $Id: main.php 121 2013-03-01 13:54:10Z fire $
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
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}


if (!defined('FIRE1_INIT')) {
    header("location:error400");
    exit();
}
global $language;
$target = HOST . request_uri();
if (!$_COOKIE[USERS_COOKIE_UNAME] || !$_COOKIE[USERS_COOKIE_UID] || !$_COOKIE[USERS_COOKIE_USESS]) {
    $title = USR_LAN_PG_MAIN_1;
    global $KERNEL_PROP_MAIN;
    // Fill  config to short varibles
    $basic_key = $KERNEL_PROP_MAIN['kernel.users.session.remember'];
    $uname_key = $KERNEL_PROP_MAIN['kernel.users.session.username'];
    $upass_key = $KERNEL_PROP_MAIN['kernel.users.session.password'];
    if (!(bool) session_id()) {
        session_start();
    }
    if ($_POST['remember']) {
        $_SESSION[$basic_key] = true;
        define(strtoupper($basic_key), true);
        $_SESSION['checked'] = "checked=\"checked\" ";
    }
    if ($_SESSION[$basic_key] === true) {
        $_SESSION['checked'] = "checked=\"checked\" ";
    }
    /////////////////////////////////////////////////
    $users_db = new users_process();
    /////////////////////////////////////////////////
    if ($_POST['signin']) {
        $sign_in_err = '<div class="signin-err">' . $users_db->signIn($_POST['username'], $_POST['password']) . '</div>';
    }
    // crate fields and check it
    $reg_filds = $users_db->signUp_fields(); // commented .. is exec in user_db class
    if ($_POST['signup']) {
        //
        // Convert checkbox to implode value to record in mysql
        check_post_checkbox();
        //
        // Show results from registration
        $sign_up_err = '<div class="signin-err">' . $users_db->signUp() . '</div>';
    }
    global $theme;
    $theme->jquery_ui();
    //
    // Entry template tags
    $tags = array();
    $tags['USERSLOG'] = $language['users.log'];
    $tags['USERREG'] = $language['users.reg'];
    $tags['TARGET'] = $target;
    $tags['SIGNUPERR'] = (isset($sign_up_err)) ? $sign_up_err : "&nbsp; ";
    $tags['SIGNINERR'] = (isset($sign_in_err)) ? $sign_in_err : "&nbsp; ";
    $tags['USERSLOGNAME'] = $language['users.logname'];
    $tags['USERSREGPASS'] = $language['users.regpass'];
    $tags['USERS_REMEBER'] = $language['users.remember'];
    $tags['USERS_FORGOT'] = $language['users.forgot'];
    $tags['USERS_B_R0G'] = $language['users.b.log'];
    $tags['USERSREGNAME'] = $language['users.regname'];
    $tags['USERSREGMAIL'] = $language['users.regmail'];
    $tags['USERSREGPASS'] = $language['users.regpass'];
    $tags['REGFILDS'] = $reg_filds;
    $tags['USERS_B_REG'] = $language['users.b.reg'];
    //
    // Social template
    $social_tags = array();
    $social_tags['SLOGINTXT'] = $language['users.soc.loginw'];
    $social_tags['DESTINATION'] = kernel::base_tag("{host}{module_dir}");
    $social_tags['SOCTXTMES'] = $language['users.soc.mess'];
    //
    // Fill-up social template
    $social_template = theme::custom_template("social", $social_tags);
    //
    // Check Use of social option
    $tags['SOCIAL'] = ((bool) kernel::prop_constant("module.socialnetworks")) ? $social_template : " ";
    $tags['USRSOCIAL'] = ((bool) kernel::prop_constant("module.socialnetworks")) ? $language['users.soc.sign'] : " ";
    //
    // Fill up all data in entry template
    $content = theme::custom_template("entry", $tags);
} else {
    // Turns on users
    /////////////////////////////////////////////////
    $users_process = new users_process();
    /////////////////////////////////////////////////
    if ($usr = $users_process->my_user()) {
        $users_process->signUp_fields();
        $title = $language['users.welcome'] . " " . $usr[USERS_SQLTBL_COL_UNAME] . " ";
        //users.sqltbl.col.lastchange
        //images?image=create&text=fire1.a.zaprianov%7C-%2B-%7Cgmail.com&font=arial&size=12
        //images?image=create&text=fire1.a.zaprianov|-+-|gmail.com&font=arial&size=12
        $mail = urlencode(str_replace("@", "|-+-|", $usr[USERS_SQLTBL_COL_UMAIL]));
        $alt_mail = urlencode(str_replace("@", " at ", $usr[USERS_SQLTBL_COL_UMAIL]));
        $img_email = HOST . ("images?image=create&text={$mail}&font=arial&size=12");
        if (empty($usr[USERS_SQLTBL_COL_AVATAR])) {
            $avatar = kernel::base_tag_folder_filter("{host}{design}users/thumbs/dp4-noavatar.png");
        } else {
            $avatar = kernel::base_tag_folder_filter("{host}{publicfiles}{users}/{$usr[USERS_SQLTBL_COL_UNAME]}/{thumbs}/") . $usr[USERS_SQLTBL_COL_AVATAR];
        }
        $filds = "";
        $filds = $users_process->fields_user;
        foreach ($filds as $oth) {
            $other.= <<<oth
  <tr>
    <th align="right">{$oth['fld_title']}</th>
    <td align="left">{$usr[$oth['fld_name']]}</td>
  </tr>
oth;
        }
        //
        // this will return to used section
        callback_users();
        $url = HOST . request_uri();
        $js = <<<js
    <meta property="og:image" content="{$avatar}" />
    <meta property="og:description" content="{$usr[USERS_SQLTBL_COL_UNAME]}" />
    <meta property="og:url" content="{$url}">
    <script type="text/javascript">
$(document).ready(function(){
            $(".usr-avt").hover(function(){
            $(".usr-avt-option").slideToggle(500);
  });
});
</script>
js;
        kernel::includeByHtml($js, 'add');
        $request_avatar = kernel::base_tag_folder_filter("{host}{users}/avatar");
        global $MERGE_TEMPLATE, $theme;
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "REQ_AVATAR";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "AVATAR";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "UNAME";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "IMGMAIL";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "ALTMAIL";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "CHANGES";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "LASTCHANGE";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "OTHER";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "MESSAGES";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "PREDIT";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "PHOTO";
        $MERGE_TEMPLATE["REVIEW"]['sector'][] = "SIGNOUT";
        $theme->template_setup("REVIEW");
        $tags['REQ_AVATAR'] = $request_avatar;
        $tags['AVATAR'] = $avatar;
        $tags['UNAME'] = $usr[USERS_SQLTBL_COL_UNAME];
        $tags['IMGMAIL'] = $img_email;
        $tags['ALTMAIL'] = $alt_mail;
        $tags['CHANGES'] = $language['users.lan.changes'];
        $tags['LASTCHANGE'] = $usr[USERS_SQLTBL_COL_LASTCHANGE];
        $tags['OTHER'] = (isset($other)) ? $other : "&nbsp; ";
        $tags['MESSAGES'] = $language['users.lan.messages'];
        $tags['PREDIT'] = $language['users.lan.editprof'];
        $tags['PHOTO'] = $language['users.lan.photo'];
        $tags['SIGNOUT'] = $language['users.lan.signout'];
        $content = theme::content($tags, "REVIEW", true);
    } else {
        define("WEBSITE_RESPONSES", 006);
    }
}
theme::content(array($title, $content));
