<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: forgotpass.php 115 2013-02-08 16:27:29Z fire $
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
/**
 *
 * @filesource
 * Recover password
 */
global $language;
if (empty($_GET['q']) || empty($_GET['h'])) {

    $unises = sha1(md5(COOKIE_SESSION . HEX) . substr(time(), 0, -2));
    if (isset($_POST[$unises])) {
        // check the session is started
        if (!@session_id()) {
            @session_start();
        }
        $mdb = new mysql_ai;
        $mdb->Select(constant("USERS_SQLTBL_MAIN"), array(propc("users.sqltbl.col.umail") => $_POST['email']));
        $r = $mdb->aArrayedResults[0];
        if ($mdb->iRecords == 0) {
            $mssg = $language['users.err.valid.email'];
        }
        //getting hashed data
        $uHash_i = $r[propc("users.sqltbl.col.hash")];
        $uHash_g = $r[propc("users.sqltbl.col.hash.generated")];
        // generate unique name and save it
        $session_name = kernel::uni_session_name();
        $q = base64_encode(md5crypt($uHash_i));
        $h = base64_encode($uHash_i);
        // hide session
        $_SESSION[$session_name] = $uHash_i;
        /**
         * @todo send mail with $uHash_i and check
         *
         */
        $mail = new misc_email();
        $link = kernel::base_tag("{host}{module_dir}forgotpass?q={$q}&h={$h}");
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
        $tags['TITLE'] = $language['users.passreset.mail.title'];
        $tags['MESSAGE'] = $language['users.f.messa'];
        $tags['SUBMESSAGE'] = $language['users.passreset.mail.content'];
        $tags['LINK'] = $link;

        $body = theme::custom_template("mail-" . $type, $tags);

        $mail->body = kernel::base_tag($body);
        $mail->setHeaders();
        if ($mail->send()) {
            $html = "<br />" . $language['users.sent.mail'] . "<br /><br /><br /><p align=\"center\">{$language['users.sent.cont']}</p><br />";
        } else {
            $html = "Cannot send mail<br /><br /><br /><p align=\"center\">{$language['users.sent.cont']}</p><br />";
        }
    } else {
        if ($_POST) {
            $err = $language['users.sent.err'];
        } else {
            $err = null;
        }
        $html = <<<eol

<div><p><br/>
<p>{$mssg} </p><br/>
{$err}
<form action="{request_uri}" method="post" enctype="multipart/form-data" target="_self">
{$language['users.f.email']}<br />
<input type="text" name="email" value="" />
<input type="submit" value="{$language['lan.submit']}" name="{$unises}"/>
</form>
</p>
</div>

eol;
        $html = kernel::base_tag($html);
    }
} else {

    $_GET['h'] = base64_decode($_GET['h']);
    $_GET['q'] = base64_decode($_GET['q']);

    // generate unique name and save it
    if ((bool) md5crypt($_GET['q'], $_GET['h'])) {
        $ses = kernel::uni_session_name();
        $mdb = new mysql_ai;
        echo $_SESSION[$ses];
        $mdb->Select(constant("USERS_SQLTBL_MAIN"), array(
            propc("users.sqltbl.col.hash") => $_SESSION[$ses])
        );

        if ($mdb->iRecords == 1) {
            $r = $mdb->aArrayedResults[0];
            if (!isset($_POST[$ses])) {

                $username = $r[propc("users.sqltbl.col.uname")];
                $html = <<<eol
<script  type="text/javascript">
   var check_pass = function(){
        
        var pass1 = $('#pass1').val();
        var pass2 = $('#pass2').val();
            if(pass1 == pass2 && pass1.length > 4){
                $('#submit').fadeIn(555);    
                $('#pass1, #pass2').css('border', '3px #090 solid');     
            }else{
                $('#submit').fadeOut(555);
                $('#pass1, #pass2').css('border', '3px #C33 solid'); 
            }
}
    $(document).ready(function(){
    $('#submit').hide();
        $('#pass1, #pass2').keyup(check_pass); 
    });
</script>
<form method="post" action="" target="_self">
       <p align="center">
            {$language['users.recov.changfor']} <b>{$username}</b>
                
       </p><br />
       <label for="pass1">{$language['users.lan.newpass']}</label><br />
        <input type="password" name="password1" id="pass1"/><br />
        <label for="pass2">{$language['users.lan.confirm']}</label><br />
        <input type="password" name="password2" id="pass2"/><br />
        <br />
        <br />
        <p align="center">
            <input type="submit" value="{$language['lan.submit']}" id="submit" name="{$ses}"  />
        </p>
</form>       
eol;
            } else {
                if (empty($_POST['password1'])) {
                    $html = " <br /> <br /> <center> " . constant("USR_LAN_ERR_REG_REQUIRE_USERPASS") . "</center>";
                } else {

                    if ($_POST['password1'] == $_POST['password2']) {

                        $c = $mdb->Update(constant("USERS_SQLTBL_MAIN"), array(propc("users.sqltbl.col.upass") => password($_POST['password1'])), array(propc("users.sqltbl.col.uid") => $r[propc("users.sqltbl.col.uid")]));
                        // check Change of password
                        if ((bool) $c) {
                            $html = $language['users.updated'];
                        } else {
                            $html = " <br /> <br /> <center> " . constant("USR_LAN_ERR_LOG_3") . " <br />Mysq problem!</center>";
                        }
                    } else {
                        $html = " <br /> <br /> <center> " . $language['users.f.pdnm'] . "</center>";
                    }
                }
            }
        } else {
            $html = $language['userrs.pass.recov.uerr'] . "<p align=\"center\"><b>" . $_GET['h'] . "</b></p>";
        }
    } else {
        $html = $language['users.recov.errorhash'];
    }
}
theme::content(array($language['users.f.title'], " <blockquote>" . $html . " </blockquote><br /><br />"));
