<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: lan_.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * ----------------------------------------------------------
 *       See COPYRIGHT and LICENSE
 * ----------------------------------------------------------
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '>lan error..1001</div>");
}
$language['numerical.error'] = "Error \"URL\" link! Please check the link";
$language['web.news'] = "Article";
$language['last.news'] = "News of the day";
$language['month.news'] = "News of the month";
$language['empty.news'] = "No change";
$language['back'] = "back";
$language['submit'] = "submit";
$language['update'] = "update";
$language['reset'] = "reset";
$language['back_js'] = "<br /><A href=\"javascript: history.go(-1)\" id=\"button\">{$language['back']}</A>";
$language['refresh_js'] = '<p align="center">Automatically page reload!</p>
                        <small><br>click on the <a href="?">link</a> or wait 3sec.</small> <META HTTP-EQUIV="Refresh" CONTENT="3; URL=?"> ';
$language['users.err.require'] = "Missed (*) required field!";
$language['users.err.name'] = "The username is already in use.";
$language['users.err.pass.match'] = "Your fields passwords does not match.";
$language['users.err.valid.email'] = "Not a valid e-mail.";
$language['users.err.nosuch.user'] = "There is no such user.";
$language['users.err.taken.mail'] = "E-mail already exists.";
$language['users.pm'] = "Personal message";
$language['users.ignup.ok'] = "Successful registration";
$language['___'] = "___";
$language['___'] = "___";
$language['___'] = "___";
$language['___'] = "___";
$language['___'] = "___";
$language['___'] = "___";
define('LAST_NEWS', 'News of the day');
define('MONTH_NEWS', 'News of the month');
define('NO_NEWS', 'No change');
define("ARHIV_NEWS", "Archives:");
define("BACK", "&larr; back");
define("SUBMIT", "submit");
define("UPDATE", "update");
define("RESET", "reset");
define("fire_BACK", "<br><br><A href=\"javascript: history.go(-1)\" id=\"botton\">" . BACK . "</A>");
define("fire_REFRESH", '<center><p>Automatically page reload!</p><small><br>click on the <a href="?">link</a> or wait 3sec. <META HTTP-EQUIV="Refresh" CONTENT="3; URL=?"></small></center>');
define("USERS_ERR_1", "Missed (*) required field!");
define("USERS_ERR_2", "The username is already in use.");
define("USERS_ERR_3", "Your password does not match.");
define("USERS_ERR_4", "Not a valid e-mail");
define("USERS_ERR_5", "There is no such user.");
define("USERS_ERR_MAIL", "E-mail already exists.");
define("USERS_LAN_PM", "Personal message");
define("USERS_LAN_1", "Successful registration Please wait");
define("ERROR_LAN_LOG", "You must be a registered user to run the functionality you want!"); #need login
define("USERS_LAN_2", "login");
define("USERS_LAN_3", "Registration");
define("USERS_LAN_4", "username:");
define("USERS_LAN_5", "password:");
define("USERS_LAN_6", "Confirm Password:");
define("USERS_LAN_7", "Internet mail:");
define("USERS_LAN_8", "Registration");
define("USERS_LAN_9", "Successfully registered as a user in this site.");
define("USERS_LAN_10", "photo:");
define("USERS_LAN_11", "Status:");
define("USERS_LAN_12", "Signature:");
define("USERS_LAN_13", "exit");
define("USERS_LAN_14", "changes");
define("USERS_LAN_15", "Welcome: ");
define("USERS_LAN_16", "profile");
define("USERS_LAN_PM_1", "Messages");
define("USERS_LAN_Mail_1", 'Type your complete e-mail but with which you have registered in website and we will send your password.');
// email for. info
define("USERS_LAN_Mail_ERROR_1", '<h2>Not successfully sent e-mail!</h2>Your e-mail does not exist in our database. ');
define("USERS_LAN_Mail_ERROR_2", '<h2>Not successfully sent e-mail!</h2>Your e-mail does not exist.');
// error
define("USERS_LAN_Mail_SUC", '<h2> Check your e-mail! </ h2> successfully send an e-mail! ');
// send  pass
define("USERS_LAN_Mail_4", 'Forgotten password (no reply) ');
// forget pass subject
define("USERS_LAN_Mail_5", 'This e-mail was sent because you forgot your password. Please click "link" to reset your password.');
//mail information
define('FORGOT_PASSWORD', "Forgot your password");
define("EMTY_SECTION_WEB", "Empty section of website.");
define("ERROR_401", "The requested URL is Forbidden. ");
define("ERROR_404", "The requested URL/file was not found on this server.");
define("ERROR_500", "Internal Server Error.");
define("ERROR_REFRESH_END", "Error in Cookies. <br> <small><small> pleas reload website</small></small>");
define("NEED_LOGIN", "<h2>You must be a <b>user</b> of the site to <b>access</b> this page!</h2>
<center>
 Please use <a href='?users=login'><b>Login</b></a> or <a href='?users=registration'><b>Registration</b></a> page. </center> ");
define("SECURITY_CHECK_ERROR", "<p>Sorry the security code is invalid! Please try it again!</p>");
define("SECURITY_CODE_LAN", "type security code:");
define("IMG_No_VALID_FORMAT", "	Format is not valid (try : .jpg .gif or .png)");
define("IMG_Error_1", 'Unsuccessfully upload the image...');
define("IMG_Error_2", 'It is not possible to upload <br /><small>please report this problem to admin.</small>');
define("IMG_UPL_scs", "Successfully uploaded the image.");
//bunnons
define("LAN_but1", "Home");
define("LAN_but2", "Profile");
define("LAN_but3", "Friends");
define("LAN_but4", "Messages");
define("LAN_but5", "Preferences");
define("LAN_but6", "Sign off");
define("LAN_but7", "Sign on");
define("LAN_but8", "News");
define("LAN_but9", "Pages");
define("LAN_but10", "Change status");
define("LAN_but11", "Images");
define("LAN_but12", "Links");
define("LAN_but13", "More");
define("LAN_but14", "Invitations");
#months
define("MONThS_01", "January");
define("MONThS_02", "February");
define("MONThS_03", "March");
define("MONThS_04", "April");
define("MONThS_05", "May");
define("MONThS_06", "June");
define("MONThS_07", "July");
define("MONThS_08", "August");
define("MONThS_09", "September");
define("MONThS_10", "October");
define("MONThS_11", "November");
define("MONThS_12", "December");
#months
define('MAIN_A_news_LAN', 'News ');
define('MAIN_A_pages_LAN', 'Pages ');
define('MAIN_A_menus_LAN', 'Menus ');
define('MAIN_A_mailing_LAN', 'Mailing ');
define('MAIN_A_logs_LAN', 'Logs ');
define('MAIN_A_links_LAN', 'Links ');
define('MAIN_A_users_LAN', 'Users ');
define('MAIN_A_update_LAN', 'Update ');
define('MAIN_A_preference_LAN', 'Preference ');
define('MAIN_A_theme_LAN', 'Theme ');
define('MAIN_A_flash_LAN', 'Flash ');
define('MAIN_A_comments_LAN', 'Comments ');
define('MAIN__SI', 'Successful insert  ');
define('MAIN__SU', 'Successful update  ');
define('MAIN__SID', 'Successful insert  your data  ');
define('MAIN__SUD', 'Successful update  your data  ');
define('MAIN__SDEL', 'Are you sure for deleting of ');
define('MAIN__DYR', 'Do you really want to:');
define('MAIN__EMP', 'Please fill the required text fields.');
define('MAIN___LAN_0', 'content ');
define('MAIN___LAN_1', 'title ');
define('MAIN___LAN_1s', 'titles ');
define('MAIN___LAN_2', 'new ');
define('MAIN___LAN_3', 'URL ');
define('MAIN___LAN_3s', 'URLs ');
define('MAIN___LAN_4', 'position ');
define('MAIN___LAN_4s', 'position ');
define('MAIN___LAN_5', 'option ');
define('MAIN___LAN_5s', 'options ');
define('MAIN___LAN_6', 'category ');
define('MAIN___LAN_6s', 'categories ');
define('MAIN___LAN_7', 'create ');
define('MAIN___LAN_8s', 'templates ');
define('MAIN___LAN_8', 'template ');
define('MAIN___LAN_9', 'theme ');
define('MAIN___LAN_9s', 'themes ');
define('MAIN___LAN_10', 'body ');
define('MAIN___LAN_11', 'section ');
define('MAIN___LAN_11s', 'sections ');
define('MAIN___LAN_12', 'default ');
define('MAIN___LAN_13', 'menu ');
define('MAIN___LAN_13s', 'menus ');
define('MAIN___LAN_14', 'erase ');
define('MAIN___LAN_15', 'delete ');
define('MAIN___LAN_15ed', 'deleted ');
define('MAIN___LAN_16', ' image ');
define('MAIN___LAN_16s', ' images ');
define('MAIN___LAN_17ate', 'activate ');
define('MAIN___LAN_17', 'active ');
define('MAIN___LAN_18', 'refresh ');
define('MAIN___LAN_18s', 'refreshes ');
define('MAIN___LAN_19', 'user ');
define('MAIN___LAN_19s', 'users ');
define('MAIN___LAN_20', 'text ');
define('MAIN___LAN_20s', 'texts ');
define('MAIN___LAN_21', 'area ');
define('MAIN___LAN_21s', 'areas ');
define('MAIN___LAN_22s', 'sounds ');
define('MAIN___LAN_22', 'sound ');
define('MAIN___LAN_23', 'log ');
define('MAIN___LAN_23s', 'logs ');
define('MAIN___LAN_24', 'page ');
define('MAIN___LAN_24s', 'pages ');
define('MAIN___LAN_25', 'recordet ');
define('MAIN___LAN_26', 'date ');
define('MAIN___LAN_26s', 'dates ');
define('MAIN___LAN_27', 'disclaimer ');
define('MAIN___LAN_28', 'website ');
define('MAIN___LAN_29', 'name ');
define('MAIN___LAN_31', 'meta ');
define('MAIN___LAN_30', 'description ');
define('MAIN___LAN_32', 'additional ');
define('MAIN___LAN_33s', 'addons ');
define('MAIN___LAN_33', 'addon ');
define('MAIN___LAN_34', 'plugin ');
define('MAIN___LAN_34s', 'plugins ');
define('MAIN___LAN_35', 'module ');
define('MAIN___LAN_35s', 'modules ');
define('MAIN___LAN_36', 'general ');
define('MAIN___LAN_37', ' installed ');
define('MAIN___LAN_37s', 'install ');
define('MAIN___LAN_37ing', 'installing ');
define('MAIN___LAN_38', 'other ');
define('MAIN___LAN_39', ' info ');
define('MAIN___LAN_39s', ' information ');
define('MAIN___LAN_40', 'simple ');
define('MAIN___LAN_41', 'host ');
define('MAIN___LAN_42', 'carset ');
define('MAIN___LAN_43', 'contact ');
define('MAIN___LAN_44', 'e-mail ');
define('MAIN___LAN_45', 'language ');
define('MAIN___LAN_46', 'copyright ');
define('MAIN___LAN_47', 'save ');
define('MAIN___LAN_48', 'action ');
define('MAIN___LAN_49', 'start ');
define('MAIN___LAN_50', 'home ');
define('MAIN___LAN_51', 'class ');
define('MAIN___LAN_52', 'admin ');
define('MAIN___LAN_52s', 'admins ');
define('MAIN___LAN_53', 'manager ');
define('MAIN___LAN_54', 'manage ');
define('MAIN___LAN_55', 'priority ');
define('MAIN___LAN_56', 'problem ');
define('MAIN___LAN_57', 'execute ');
define('MAIN___LAN_58', 'reading  ');
define('MAIN___LAN_59', 'deny  ');
define('MAIN___LAN_59s', 'denied  ');
define('MAIN___LAN_60', 'update ');
define('MAIN___LAN_61', 'successfully ');
define('MAIN___LAN_62', 'progress ');
define('MAIN___LAN_63', 'error ');
define('MAIN___LAN_63s', 'errors ');
define('MAIN___LAN_64', 'edit ');
define('MAIN___LAN_64s', 'edits  ');
define('MAIN___LAN_65s', 'empty  ');
define('MAIN___LAN_66', 'field  ');
define('MAIN___LAN_66s', 'fields ');
define('MAIN___LAN_67', 'time ');
define('MAIN___LAN_67s', 'times ');
define('MAIN___LAN_68', 'tag ');
define('MAIN___LAN_68s', 'tags ');
define('MAIN___LAN_69s', 'process  ');
define('MAIN___LAN_70', 'full  ');
define('MAIN___LAN_71', 'require  ');
define('MAIN___LAN_72', 'registration  ');
define('MAIN___LAN_73', 'login  ');
define('MAIN___LAN_74', 'username  ');
define('MAIN___LAN_75', 'password  ');
define('MAIN___LAN_76', 'link  ');
define('MAIN___LAN_76s', 'links ');
define('MAIN___LAN_77', 'editor ');
define('MAIN___LAN_77s', 'editors ');
define('MAIN___LAN_78', 'change ');
define('MAIN___LAN_78s', 'changes ');
define('MAIN___LAN_79', 'prevent ');
define('MAIN___LAN_79s', 'prevents ');
define('MAIN___LAN_80', 'setting ');
define('MAIN___LAN_80s', 'settings ');
define('MAIN___LAN_81', 'view ');
define('MAIN___LAN_81s', 'views ');
define('MAIN___LAN_81ing', 'viewing ');
define('MAIN___LAN_82', 'show ');
define('MAIN___LAN_82s', 'shows ');
define('MAIN___LAN_82ing', 'showing ');
define('MAIN___LAN_83', 'topic ');
define('MAIN___LAN_83s', 'topics ');
define('MAIN___LAN_84', 'post ');
define('MAIN___LAN_84s', 'posts ');
define('MAIN___LAN_85', 'last ');
define('MAIN___LAN_85s', 'lasts ');
define('MAIN___LAN_86s', 'reply ');
define('MAIN___LAN_86s', 'replys ');
define('MAIN___LAN_87', 'reset ');
define('MAIN___LAN_87s', 'resets ');
define('MAIN___LAN_88', 'first ');
define('MAIN___LAN_88', 'analyzing ');
define('MAIN_p_for', 'for ');
define('MAIN_p_from', 'from ');
define('MAIN_p_to', 'to ');
define('MAIN_p_the', 'the ');
define('MAIN_p_while', 'while ');
define('MAIN_p_this', 'this ');
define('MAIN_p_your', 'your ');
define('MAIN_p_you', 'you ');
define('MAIN_p_do', 'do ');
define('MAIN_p_like', 'like ');
define('MAIN_p_is', ' is ');
define('MAIN_p_tick', 'tick ');
define('MAIN_p_some', 'some ');
define('MAIN_p_be', 'be ');
define('MAIN_p_more', 'more ');
define('MAIN_p_end', 'end ');
define('MAIN_p_start', 'start ');
define('MAIN_p_may', 'may ');
define('MAIN_p_not', 'not ');
define('MAIN_p_allow', 'allow  ');
define('MAIN_p_now', 'now  ');
define('MAIN_p_one', 'one  ');
define('MAIN_p_moment', 'moment  ');
define('MAIN_p_ok', 'ok  ');
define('MAIN_p_close', 'close  ');
define('MAIN_p_press', 'press  ');
define('MAIN_p_click', 'click  ');
define('MAIN_p_already', 'already  ');
define('MAIN_p_ready', 'ready  ');
define('MAIN_p_exist', 'exist  ');
define('MAIN_p_here', 'here  ');
define('MAIN_p_type', 'type  ');
define('MAIN_p_on', 'on  ');
define('MAIN_p_really', 'really  ');
define('MAIN_p_want', 'want  ');
define('MAIN_p_auto', 'auto  ');
define('MAIN_p_radio', 'radio  ');
define('MAIN_p_check', 'check  ');
define('MAIN_p_select', 'select  ');
define('MAIN_p_checkbox', 'checkbox  ');
define('MAIN_p_in', 'in  ');
define('MAIN_p_with', 'with  ');
define('MAIN_p_cause', 'cause ');
define('MAIN_p_all', 'all ');
?>