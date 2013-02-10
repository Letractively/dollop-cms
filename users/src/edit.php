<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: edit.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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

if (defined("USER_ID")) {
    global $language;
    $title = $language['users.edi'] . " " . constant("USER_NAME");
    $my = new mysql_ai;
    switch ($_GET['change']) {
        default :

            if (isset($_POST['submit_edits'])) {
                check_post_checkbox();
                $mysql_hash = null;
                $un = propc("users.sqltbl.col.uname");
                if ($_POST[$un]) {
                    // if is changet username
                    $t = time();
                    $mysql_hash = mysql_hash($_POST[$un], $t);
                    $_POST[propc("users.sqltbl.col.hash.generated")] = $t;
                }
                if ($my->Update(constant("USERS_SQLTBL_MAIN"), $_POST, array(constant("USERS_SQLTBL_COL_UID") => constant("USER_ID")), array('submit_edits'))) {
                    // Change mysql Hash on username change
                    if ((bool) $mysql_hash) {
                        mysql_query("UPDATE " . constant("USERS_SQLTBL_MAIN") . " SET " . propc("users.sqltbl.col.hash") . "=$mysql_hash
                        WHERE " . constant("USERS_SQLTBL_COL_UID") . "='" . constant("USER_ID") . "' ") or die(mysql_error());
                    }
                }

                $updated = $language['users.updated'];
            }

            $tb = new html_table("user-edit", "table-edit", 0, 14, 14, array('width' => '85%', "align" => "center"));
            $my->Select(constant("USERS_SQLTBL_MAIN"), array(constant("USERS_SQLTBL_COL_UID") => constant("USER_ID")));

            if ($my->iRecords != 0) {
                $un = kernel::prop_constant("users.sqltbl.col.uname");
                $um = kernel::prop_constant("users.sqltbl.col.umail");
                $uf = kernel::prop_constant("users.sqltbl.col.fname");
                $ut = kernel::prop_constant("users.sqltbl.col.tzone");
                $ul = kernel::prop_constant("users.sqltbl.col.lang");
                $ud = kernel::prop_constant("users.sqltbl.col.dname");

                $v = $my->aArrayedResults[0];
                $images_fld = kernel::base_tag("{host}{module_dir}images/");
                $imageeff = <<<eol
                     <img id="tick_username" src="{$images_fld}tick.png" width="16" height="16"/>
                     <img id="cross_username" src="{$images_fld}cross.png" width="16" height="16"/>   
eol;
                // User name
                $tb->addRow();
                $tb->addCell($language['users.regname'], null, 'header', array('width' => '30%'));
                $tb->addCell(html_form_input("text", $un, $v[$un], 'id="username" style="border:solid 3px;padding:5px; "'), null, 'header', array('width' => '30%'));
                $tb->addCell($imageeff);

                // User Mail
                $tb->addRow();
                $tb->addCell($language['users.regmail'], null, 'header', array('width' => '30%'));
                $tb->addCell($v[$um]);
                $tb->addCell('<a href="?change=mail"><b>' . $language['users.change.mail'] . '</b></a>');

                // User Password
                $tb->addRow();
                $tb->addCell($language['users.lan.upass'], null, 'header', array('width' => '30%'));
                $tb->addCell("<b> ****** </b>");
                $tb->addCell('<a href="?change=pass"><b>' . $language['users.change.password'] . '</b></a>');

                // Time Zone
                $allzones = implode("\n", timezone_identifiers_list());
                $tb->addRow();
                $tb->addCell($language['users.timezone'], null, 'header', array('width' => '30%'));
                $tb->addCell(html_form_input("select", $ut, $allzones, $v[$ut]));
                $tb->addCell(' ');


                $my->Select(constant("USERS_SQLTBL_FIELD"));
                if ($my->iRecords != 0) {
                    foreach ($my->aArrayedResults as $fl) {
                        $tb->addRow();
                        $tb->addCell($fl['fld_title'], null, 'header', array('width' => '30%'));
                        if ($fl['fld_type'] == "checkbox" OR $fl['fld_type'] == "radio") {
                            //
                            // Checkbox and Radoi fields have's a def select value
                            $tb->addCell(html_form_input($fl['fld_type'], $fl['fld_name'], $fl['fld_value'], $v[$fl['fld_name']]));
                        } else {
                            //
                            // Other fields
                            $tb->addCell(html_form_input($fl['fld_type'], $fl['fld_name'], $v[$fl['fld_name']], html_maxlength($fl['row_type'])));
                        }
                        $tb->addCell($fl['fld_descr'], 'description');
                    }
                }
                $postRequest = kernel::base_tag("{host}{module_dir}datacheck?socnet");
                $_SESSION["datacheck"] = kernel::uni_session_name();
                $action = HOST . request_uri();
                $js = <<<jscript
   
$(document).ready(function(){
 $('#tick_username').hide();
$('#username').keyup(username_check);
username_check();
});

function username_check(){    
    var username = $('#username').val();
    if(username == "{$v[$un]}"){
        $("#username").removeAttr("name");
        $('#username').css('border', '3px #1D7CF2 solid');
        $('#tick_username').hide();
        $('#cross_username').hide();
        $('#submit').removeAttr("disabled");
        $('#submit').show(500);
    }else if(username.length < 4){
        $('#username').css('border', '3px #C33 solid');
        $('#tick_username').hide();
        $('#submit').hide(500);
        $('#submit').attr('disabled', 'disabled');
    }else{
        jQuery.ajax({
            type: "POST",
            url: "{$postRequest}",
            data: 'check_username='+ username,
            cache: false,
            success: function(response){
                if(username == "{$v[$un]}"){
                    $('#username').css('border', '3px #1D7CF2 solid');
                    $('#cross_username').hide();
                    $('#tick_username').fadeIn();
                    $('#submit').removeAttr("disabled");
                    $('#submit').show(500);
                }else if(response >= 1){
                    $('#username').css('border', '3px #C33 solid');    
                    $('#tick_username').stop().hide();
                    $('#cross_username').fadeIn(1000);
                    $('#submit').hide(500);
                    $('#submit').attr('disabled', 'disabled');
                }else{
                    $('#username').attr('name', '{$un}')
                    $('#username').css('border', '3px #090 solid');
                    $('#cross_username').hide(1000);
                    $('#tick_username').fadeIn(1000);
                    $('#submit').removeAttr("disabled");
                    $('#submit').show(500);
                }
            }
        });
    }
}
jscript;


                global $kernel;
                $kernel->external_file("jquery", $js);

                $content = <<<eol
   <blockquote> {$updated}</blockquote>
   <form method="post" action="{$action}" name="edit-user" id="edit-user">
eol;
                $content .= $tb->display();
                $content .=<<<eol
   <br />
   <p align="center"><input type="submit" name="submit_edits" value="{$language['lan.submit']}" id="submit"> </p>
          </form>
eol;
            }
            break;

        case "mail":
            //
            // Creating callback in case user viewing the website after session is expire!
            if (!defined("USER_ID")) {
                callback("{request_uri}");
                header("location:" . kernel::base_tag("{users}/main?callback={request_uri}"));
                exit();
            } else {
                callback_clear();
            }
            $um = propc("users.sqltbl.col.umail");
            $my->Select(constant("USERS_SQLTBL_MAIN"), array(constant("USERS_SQLTBL_COL_UID") => constant("USER_ID")));
            $r = $my->aArrayedResults[0];
            // return session
            if (!empty($_GET['q']) || !empty($_GET['h'])) {

                //
                // Create decode data
                $_GET['h'] = base64_decode($_GET['h']);
                $_GET['q'] = base64_decode($_GET['q']);

                if ((bool) md5crypt($_GET['q'], $_GET['h'])) {
                    global $kernel;
                    $new_mail = $kernel->SESSION($_GET['h']);
                    if (!filter_var($new_mail, FILTER_VALIDATE_EMAIL)) {
                        $html = $language['users.chml.sesexp'] . $language['users.sent.cont'];
                    } else {
                        $kernel->SESSION_CILL($_GET['h']);
                        $c = $my->Update(constant("USERS_SQLTBL_MAIN"), array(propc("users.sqltbl.col.umail") => $new_mail), array(propc("users.sqltbl.col.uid") => $r[propc("users.sqltbl.col.uid")]));
                        // check Change of password
                        if ((bool) $c) {
                            $html = $language['users.updated'];
                        } else {
                            $html = " <br /> <br /> <center> " . constant("USR_LAN_ERR_LOG_3") . " <br />Mysq problem!</center>";
                        }
                    }
                } else {
                    $html = $language['users.recov.errorhash'];
                }

                $content = $html;
            } else {
                $unises = sha1(md5(kernel::uni_session_name() . "-" . HEX) . ((int) substr(time() + 1, 0, -2)));


                if (isset($_POST[$unises])) {
                    $uHash_i = $r[propc("users.sqltbl.col.hash")];
                    $q = base64_encode(md5crypt($uHash_i));
                    $h = base64_encode($uHash_i);
                    $mail = new misc_email();
                    $link = kernel::base_tag("{host}{module_dir}edit?change=mail&q={$q}&h={$h}");
                    $hostparts = parse_url(HOST);
                    $mail->from = "no-reply@{$hostparts['host']}";
                    $mail->to = $r[propc("users.sqltbl.col.umail")];
                    $mail->subject = $language['users.f.title'];
                    if (in_array(propc("users.mail.type"), array("mix", "text", "html"))) {
                        $type = propc("users.mail.type");
                    } else {
                        $type = "text";
                    }
                    $mail->type = $type;
                    $tags = array();
                    $tags['TITLE'] = $language['users.chml.ttl'];
                    $tags['MESSAGE'] = $language['users.chml.mss'];
                    $tags['SUBMESSAGE'] = $language['users.chml.smss'];
                    $tags['LINK'] = $link;

                    $body = theme::custom_template("mail-" . $type, $tags);

                    $mail->body = kernel::base_tag($body);
                    $mail->setHeaders();
                    if ($mail->send()) {
                        global $kernel;
                        $kernel->SESSION_SET($uHash_i, $_POST['email'], (3600 * 96));
                        $html = "<br />" . $language['users.chml.sendmail'] . "<br /><br /><br /><p align=\"center\">{$language['users.sent.cont']}</p><br />";
                    } else {
                        $html = "Cannot send mail<br /><br /><br /><p align=\"center\">{$language['users.sent.cont']}</p><br />";
                    }
                    $content = $html;
                } else {


                    $images_fld = kernel::base_tag("{host}{module_dir}images/");

                    $imageeff = <<<eol
                     <img id="tick_email" src="{$images_fld}tick.png" width="16" height="16"/>
                     <img id="cross_email" src="{$images_fld}cross.png" width="16" height="16"/>   
eol;
                    $tb = new html_table("user-edit", "table-edit", 0, 14, 14, array('width' => '80%', "align" => "center"));
                    $tb->addRow();
                    $tb->addCell($language['users.f.cmail'], null, 'header', array('width' => '30%'));
                    $tb->addCell("<b> {$r[$um]}</b>", null, 'header', array('width' => '30%'));
                    $tb->addCell(" ");

                    $tb->addRow();
                    $tb->addCell($language['users.f.nmail'], null, 'header', array('width' => '30%'));
                    $tb->addCell(html_form_input("text", "email", $r[$um], 'id="email"'), null, null, array('width' => '30%'));
                    $tb->addCell($imageeff);
                    $postRequest = kernel::base_tag("{host}{module_dir}datacheck?socnet");
                    $_SESSION["datacheck"] = kernel::uni_session_name();
                    $action = HOST . request_uri();

                    $js = <<<jscript
   
$(document).ready(function(){
 $('#tick_email').hide();
 $('#submit').hide();
$('#email').keyup(mail_check);
mail_check();
});

function mail_check(){    
    var email = $('#email').val();
    if(email == "{$r[$um]}"){
        $('#email').css('border', '3px #1D7CF2 solid');
        $('#tick_email').hide();
        $('#cross_email').hide();
        $('#submit').hide(500);
    }else if(email.length <= 6){
        $('#email').css('border', '3px #C33 solid');
        $('#tick_email').hide();
        $('#submit').hide(500);
        $('#submit').attr('disabled', 'disabled');
    }else{
        jQuery.ajax({
            type: "POST",
            url: "{$postRequest}",
            data: 'check_usermail='+ email,
            cache: false,
            success: function(response){
                if(email == "{$r[$um]}"){
                    $('#email').css('border', '3px #1D7CF2 solid');
                    $('#cross_email').hide();
                    $('#tick_email').fadeIn();
                    $('#submit').removeAttr("disabled");
                    $('#submit').show(500);
                }else if(response == 1){
                    $('#email').css('border', '3px #C33 solid');    
                    $('#tick_email').stop().hide();
                    $('#cross_email').fadeIn(1000);
                    $('#submit').hide(500);
                    $('#submit').attr('disabled', 'disabled');
                    $('#edit-user').attr('disabled', 'disabled');
                }else{
                    $('#email').css('border', '3px #090 solid');
                    $('#cross_email').hide(1000);
                    $('#tick_email').fadeIn(1000);
                    $('#submit').removeAttr("disabled");
                    $('#submit').show(500);
                    $('#edit-user').removeAttr("disabled");
                }
            }
        });
    }
}
jscript;
                    global $kernel;
                    $kernel->external_file("jquery", $js);

                    $content = <<<eol
   <blockquote> {$updated}</blockquote>
   <form method="post" action="{$action}" name="edit-user" id="edit-user">
eol;

                    $content .= $tb->display();
                    $content .=<<<eol
   <br />
   <p align="center"><input type="submit" name="{$unises}" value="{$language['lan.submit']}" id="submit"> </p>
          </form>
eol;
                }
            }
            break;
        //
        // Change password
        case "pass":
            if (!defined("USER_ID")) {
                header("location: main");
                exit();
            }
            $unises = sha1(md5(kernel::uni_session_name() . "-" . HEX) . ((int) substr(time() + 1, 0, -2)));

            if (isset($_POST[$unises])) {

                $my->Select(constant("USERS_SQLTBL_MAIN"), array(constant("USERS_SQLTBL_COL_UID") => constant("USER_ID")));

                $r = $my->aArrayedResults[0];

                if ($r[propc("users.sqltbl.col.upass")] != password($_POST['password'])) {
                    $err = constant("USR_LAN_ERR_LOG_3");
                } else IF ($_POST['password1'] != $_POST['password2']) {
                    $err = $language['users.f.pdnm'];
                } else {
                    $c = $my->Update(constant("USERS_SQLTBL_MAIN"), array(propc("users.sqltbl.col.upass") => password($_POST['password1'])), array(propc("users.sqltbl.col.uid") => $r[propc("users.sqltbl.col.uid")]));
                    // check Change of password
                    if ((bool) $c) {
                        $content_insert = $language['users.updated'];
                    } else {
                        $content_insert = " <br /> <br /> <center> " . constant("USR_LAN_ERR_LOG_3") . " <br />Mysq problem!</center>";
                    }
                }
            } elseIf (isset($_POST)) {
                $err = $language['sys.countout'];
            }

            $tb = new html_table("user-edit", "table-edit", 0, 14, 14, array('width' => '80%', "align" => "center"));
            $tb->addRow();
            $tb->addCell($language['users.lan.upass'], null, 'header', array('width' => '30%'));
            $tb->addCell(html_form_input("password", "password"), null, 'header', array('width' => '30%'));
            $tb->addCell(" ");
            // NEW password 2
            $tb->addRow();
            $tb->addCell($language['users.lan.newpass'], null, 'header', array('width' => '30%'));
            $tb->addCell(html_form_input("password", "password1", null, 'id="pass1"'), null, 'header', array('width' => '30%'));
            $tb->addCell(" ");
            // NEW password 2
            $tb->addRow();
            $tb->addCell($language['users.lan.confirm'], null, 'header', array('width' => '30%'));
            $tb->addCell(html_form_input("password", "password2", null, 'id="pass2"'), null, 'header', array('width' => '30%'));
            $tb->addCell(" ");

            $action = HOST . request_uri();
            $content = <<<eol

   <blockquote> {$updated}</blockquote>
       <p class="countdown" align="center"> </p>
       <p align="center">$err</p>
   <form method="post" action="{$action}" name="edit-user" id="edit-user">
eol;
            if (!(bool) $c) {
                $content .= $tb->display();
                $content .=<<<eol
   <br />
   <p align="center"><input type="submit" name="{$unises}" value="{$language['lan.submit']}" id="submit"> </p>
          </form>
eol;
            } else {
                $content .= $content_insert;
            }


            $js = <<<eol
    var check_pass = function(){
        
        var pass1 = $('#pass1').val();
        var pass2 = $('#pass2').val();
            if(pass1 == pass2 && pass1.length >= 6 ){
                $('#submit').fadeIn(555);    
                $('#pass1, #pass2').css('border', '3px #090 solid');     
            }else{
                $('#submit').fadeOut(555);
                $('#pass1, #pass2').css('border', '3px #C33 solid'); 
            }
}
$(function(){
    $('#submit').hide();
        $('#pass1, #pass2').keyup(check_pass); 
        $('#pass').keyup(function(){
           if( $(this).val() >= 6){
                $(this).css('border', '3px #090 solid'); 
            }
        });
  var count = 60;
  countdown = setInterval(function(){
    $("p.countdown").html('<font size="+2"><b>' + count + "</b></font> <br />{$language['sys.countdown']}");
    if (count == 0) {
      window.location = "edit";
    }
    if(count < 10 ){
     $('p.countdown').css('color', '#C33');  
     }
    count--;
  }, 1000);
});
eol;
            global $kernel;
            $kernel->external_file("jquery", $js);
            break;
    }
} else {
    header("location:" . kernel::base_tag("{host}{module_dir}"));
}












theme::content(array($title, $content));


