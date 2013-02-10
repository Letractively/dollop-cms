<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: messages.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * Users messages
 */
global $language;
if (!defined("USER_ID")) {
    header("location: main");
} else {
    $d = new mysql_ai;
    $content = <<<eol
<div class="mail-border"></div>
eol;
    switch (_GET("m")) {
        case "d":
            if (is_numeric($_GET['id'])) {
                $d->Update("messages", array("erase" => "1"), array("ID" => $_GET['id']));
                header("location:messages");
            }
            break;

        default :

            $title = $language['users.messages.index'];
            $tbl = new html_table("messages", "table-messages", 0, 6, 6, array('width' => '95%', "align" => "center"));
            $sql = "SELECT * FROM `" . PREFIX . "messages` WHERE `id_receiver`='" . constant("USER_ID") . "'
                AND `erase`!='1' ";
            $pg = new mysql_lister($sql, 15);
            $result = mysql_query($sql . " ORDER BY `ID` DESC" . $pg->limit()) or die(mysql_error());
            $imgFolder = kernel::base_tag("/{module_dir}images/");
            if ((bool) mysql_num_rows($result)) {
                while ($r = mysql_fetch_array($result)) {
                    $stat = ((bool) $r['readed']) ? "mail_unread" : "mail_readed";
                    $stat_lan = ((bool) $r['readed']) ? $language['users.messages.mailun'] : $language['users.messages.mailre'];
                    $tbl->addRow();
                    if ((bool) $r['priority']) {
                        $imp = '<img src="' . kernel::base_tag("/{module_dir}images/important.png") . '" id="message-icon" style="position:absolute"/>';
                    } else {
                        $imp = null;
                    }
                    $tbl->addCell($imp . "<img src=\"{$imgFolder}$stat.png\" title=\"{$stat_lan}\">", null, null);
                    $tbl->addCell("<a href=\"?m=read:{$r['ID']}\">" . $r['title'] . "</a>", null, "title");
                    $tbl->addCell("<a href=\"view?u=id:{$r['id_sender']}\">" . $r['na_sender'] . "</a>", null, null);
                    $tbl->addCell($r['timechange'], null, null);
                    $options = <<<eol
   <a href="?m=d:{$r['ID']}" onClick="return confirm('{$language['users.messages.mailer']} {$r['title']}?')"><img src="{$imgFolder}mail_erase.png"> </a>
   <a href="?m=send:{$r['id_sender']}"><img src="{$imgFolder}reply_mail.png" title="{$language['users.messages.mesrep']}"> </a>
eol;
                    $tbl->addCell($options, null, null);
                }
                $content .= '<p aling="center">' . $tbl->display() . '</p><br /><center>' . $pg->display_list() . '</center><br />';
            } else {
                $content .= '<p align="center"><br />' . constant("USER_NAME") . " " . $language['users.messages.inbox']
                        . '<br /><br /><b>' . $language['users.messages.einbox'] . "</b></p><br />";
            }
            break;
        // READ
        case "read":


            global $language;
            if (is_numeric($_GET['id'])) {
                if ($d->Select("messages", array("ID" => $_GET['id'], "id_receiver" => constant("USER_ID")))) {
                    $r = $d->aArrayedResults[0];

                    if ((bool) $r['readed']) {
                        // readerd true
                        $d->Update("messages", array("readed" => 0), array("ID" => $_GET['id'], "id_receiver" => constant("USER_ID")));
                    }
                    $imgFolder = kernel::base_tag("/{module_dir}images/");
                    $options = <<<eol
   <a href="?m=d:{$r['ID']}" onClick="return confirm('{$language['users.messages.mailer']} {$r['title']}?')"><img src="{$imgFolder}mail_erase.png"> </a>
   <a href="?m=send:{$r['na_sender']}"><img src="{$imgFolder}reply_mail.png" title="{$language['users.messages.mesrep']}"> </a>
eol;
                    $content .=<<<eol
   <h2> {$r['title']}</h2>     
<p>
{$r['body']}
<form metthod="get">
<p align="right">
$options
    </p>
</form>
</p>
eol;
                    $title = $language['users.messages.messfr'] . " " . $r['na_sender'];
                }
            }

            break;

        //
        // SEND
        //  NEW
        //      Message
        case "send":

            $title = $language['users.messages.senttt'];

            $uni = kernel::uni_session_name();
            if ($_POST[$uni]) {
                // send mail for important priority
                if ($_POST['priority'] >= 1) {
                    if ($d->Select(constant("USERS_SQLTBL_MAIN"), array(propc("users.sqltbl.col.uid") => $_POST['id_receiver']))) {
                        $r = $d->aArrayedResults[0];
                        $m = new misc_email;
                        $link = kernel::base_tag("{host}{module_dir}messages");
                        $hostparts = parse_url(HOST);
                        $m->from = "no-reply@{$hostparts['host']}";
                        $m->to = $r[propc("users.sqltbl.col.umail")];
                        $m->subject = $language['users.messages.mailtt'];
                        $uname = constant("USER_NAME");
                        $m->type = "text";
                        $m->body = <<<eol
                    
                    {$language['users.messages.mailtx']} {$uname}
                    
                    {$link}
eol;
                        $m->setHeaders();
                        $m->send();
                    }
                }
                unset($_POST['user_list']);
                unset($_POST[$uni]);
                $_POST['id_sender'] = constant("USER_ID");
                $_POST['na_sender'] = constant("USER_NAME");
                $d->Insert($_POST, "messages");
                $content = <<<eol
   <br />
   <br />
   <p align="center">
   {$language['users.messages.messuc']}
   </p>
   <br />
   <p align="center">
   {$language['users.sent.cont']}
   </p>
   <br />
   <br />
eol;
            } else {
                global $theme;
                $theme->jquery_ui();

                $js = <<<eol
$('#submit').bind("click",function(event){
   var send_to = $('#user-id').val();
   var titletxt= $("#send_title").val();
   var na_receiver= $("#na_receiver").val();

 
$("#send_title,#user_list").bind("keypress", function(e) {
  if (e.keyCode == 13) {               
    e.preventDefault();
    return false;
  }
  
});

});   
eol;
                global $kernel, $theme;
                $kernel->external_file("jquery", $js);


                $content .=<<<eol
   <form method="post" action="" id="send-message">
       
   <br />
eol;
                if (is_numeric($_GET['id'])) {
                    $d->Select(constant("USERS_SQLTBL_MAIN"), array(constant("USERS_SQLTBL_COL_UID") => $_GET['id']));
                    $r = (is_array($d->aArrayedResults[0])) ? $d->aArrayedResults[0] : "";
                    $content .=html_form_input("hidden", "id_receiver", $r[propc("users.sqltbl.col.uid")]);
                    $content .=html_form_input("hidden", "na_receiver", $r[propc("users.sqltbl.col.uname")]);
                } else {
                    $content .=user_lister() . "   <div style=\"clear:both;\"></div><hr />";
                }
                $titletxt = html_form_input("text", "title", null, 'id="send_title" size="40"');
                $text = $theme->textarea('body', null, "send_body", 25, null, '90%', '350px');
                $content .=<<<eol

   <p>
   <blockquote>
   {$language['users.messages.messtt']}<br />
   {$titletxt}
          </blockquote>
   </p>
   
    <p align="center">
    
    {$language['users.messages.messms']}
        <br />
   {$text}
   </p>      
   <br />
   <p align="center">
   <input type="submit" id="submit" value="{$language['lan.submit']}" name ="{$uni}"/>
   </p>
   <br /><br />
eol;
                $content .="</form>";
            }
            break;
    }
}

$content .=<<<eol
<div class="mail-border"></div>
    <a href="main">&laquo; {$language['users.messages.goback']}</a> | 
    <a href="?">{$language['users.messages.index']}</a> | 
    <a href="?m=send">{$language['users.messages.sendme']} &raquo;</a>
eol;



theme::content(array($title, $content));

