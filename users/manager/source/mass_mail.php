<?php

/**
  ============================================================
 * Last committed:      $Revision: 129 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-28 13:31:47 +0200 (÷åòâ, 28 ìàðò 2013) $
 * ID:                  $Id: mass_mail.php 129 2013-03-28 11:31:47Z fire $
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


global $language, $cpanel;
$error = null;

if (isset($_POST['send-mail-submit'])) {
    $mail_list = array();
    $bad_mail = array();
    $unsend = array();
    $sendet = array();
    $other_mails = $_POST['other'];
    $main_mails = $_POST['mail-list'];
    $user_table = propc("users.sqltbl.main");
    $mail_coll = propc("users.sqltbl.col.umail");
    $uid_coll = propc("users.sqltbl.col.uid");

    $getList = explode(",", $main_mails);
    if (in_array("all", $getList)) {

        $result = db_query("SELECT $mail_coll FROM $user_table  ");
        if ((bool) $result) {
            foreach (db_fetch($result, "assoc") as $user) {
                $mail_list[] = $user[$mail_coll];
            }
        }
    } else {
        foreach ($getList as $mail_list) {
            $trimlist =trim($mail_list);
            if (is_numeric(trim($trimlist))) {
                $sqlgen .= " `$uid_coll`='$trimlist'   OR";
            }
        }
        $mail_list = array();
        $generate_query = substr($sqlgen, 0, -2);
        $result = db_query("SELECT $mail_coll FROM $user_table WHERE  $generate_query ");
        if ((bool) $result) {
            foreach (db_fetch($result, "assoc") as $user) {
                $mail_list[] = $user[$mail_coll];
            }
        }
    }

    if (!empty($_POST['other'])) {
        $getOtherList = explode(",", $other_mails);
        $mail_list = array_merge($mail_list, $getOtherList);
    }

    foreach ($mail_list as $mailcheck) {
        if (filter_var(trim($mailcheck), FILTER_VALIDATE_EMAIL)) {
            $mail_list[] = trim($mailcheck);
        } else {
            $bad_mail[] = $mailcheck;
        }
    }


    $mail = new misc_email();
    $mail->type = $_POST['mail-type'];
    $mail->from = $_POST['from'];
    $mail->subject = $_POST['title'];
    $body = (stripallslashes(addslashes($_POST['mailbody'])));
    $find = array("http://",' src="',' href="');
    $replace = array("",' src="http://'.parse_url(HOST,PHP_URL_HOST)."/",' href="http://'.parse_url(HOST,PHP_URL_HOST)."/");
    $body = str_replace($find, $replace, $body);

    $mail->body = $body;
    //
    // Send and check for succeed
    $trysend = array_unique($mail_list);
    foreach ($trysend as $sending) {
        $mail->to = $sending;
        $mail->setHeaders();
        if ((bool) $mail->send()) {
            $sendet[] = $sending;
        } else {
            $unsend[] = $sending;
        }
    }

    $sendet = implode(", ", $sendet);
    $bad_mail = $cpanel->mysql_alert_box(implode(", ", $bad_mail), $sector);
    $unsend = $cpanel->mysql_error_box(implode(", ", $unsend), $sector);

    $BODY = <<<eol
<br />
   <table width="80%" border="0" align="center">
       <tr>
       <td>

       <p><b>Unsend Emails:</b> $unsend </p>
           <hr />
       <p><b>Invalid Emails:</b> $bad_mail </p>
           <hr />
       <p><b>Email sent:</b> $sendet </p>

        </td>
        </tr>
        </table>

        <form action="#{$sector}" method="post">
          <p align="center"> <center> <input type="submit"  id="button" value="{$language['lw.ok']}" /> </center></p>
        </form>



eol;
} else {



//
// SAVE Bookmarks
    if (isset($_POST['Message']) && isset($_POST['mail_id'])) {
        $now = date("j  n  Y");

        $result = db_query("REPLACE INTO `" . PREFIX . "mail`
      SET

        `UserTo`='{$_POST['UserTo']}',
        `UserFrom`='{$_POST['UserFrom']}',
        `Subject`='{$_POST['Subject']}',
        `Message`='{$_POST['Message']}',
        `SentDate`='$now',
        `mail_id`='{$_POST['mail_id']}'
");
        if (!(bool) $result) {
            $error = $cpanel->mysql_error_box(db_error(), $sector);
        }
    }
//
// ERASE Bookmarks
    if (isset($_POST['erase-bookmark'])) {
        if (!(bool) db_query("DELETE FROM `" . PREFIX . "mail` WHERE `mail_id`='{$_POST['erase-bookmark']}' ")) {
            $error = db_error();
        }
    }


    if (is_array($_SESSION['saved-group'])) {

        if (count($_SESSION['saved-group']) == count($_SESSION['saved-group'], COUNT_RECURSIVE)) {
            // echo 'array is not multidimensional';
        } else {
            $list = $_SESSION['saved-group'];
            //$list = array_reduce($_SESSION['saved-group'], function ($result, $current) {$result[]=current($current); return $result;}, array());
        }
        $option_mails = null;
        foreach ($_SESSION['saved-group'] as $sn => $ids) {
            if (!empty($ids)) {
                if (is_array($ids)) {
                    $cds = implode(",", $ids);
                    $sds = implode(" id:", $ids);
                }
                $option_mails .= <<<eol
   <option value="$cds" title="$sds">{$language['lw.on']} {$sn} {$language['lw.view']} {$language['lw.saved']} {$language['main.cp.users']}</option>
eol;
            }
        }
    }


//
// Select All Bookmarks
    $query_result = db_query("SELECT `mail_id`,`Subject`,`SentDate` FROM `" . PREFIX . "mail`  ORDER BY SentDate DESC ");
    $bookmarks = null;
    $op = null;
    foreach (db_fetch($query_result) as $bm) {
        if (empty($bm['Subject'])) {
            $bm['Subject'] = " - none -  ";
        }
        //
        // Sub options for Bookmarks
        $op = $cpanel->operation_buttons($bm['mail_id'], $sector, $sector, "useit-bookmark", "mail-open", " {$language['lw.activate']} {$language['p.users.mbmk']}");
        $op .= $cpanel->operation_buttons($bm['mail_id'], $sector, "$sector", "erase-bookmark", 'trash', " {$language['lw.erase']} {$language['p.users.mbmk']}", array(
            "OK" => true,
            'title' => " {$language['main.cp.question.alt']} {$language['lw.erase']}:",
            'body' => "{$language['main.cp.question.erase']}: <br/><b> {$bm['Subject']}</b> <br/>id: {$bm['mail_id']}</div>"
                )
        );

        $bookmarks .=<<<eol
<tr>
   <td width="60%">{$bm['Subject']}</td> <td><small>{$bm['SentDate']} </small></td> <td width="65px"> <ul id="icons"> $op </ul> </td>
</tr>
eol;
    }
    if (empty($bookmarks)) {
        $bookmarks = "<p align='center'>{$language['lw.empty']} {$language['lw.field']} <p>";
    }
    $op = null;

    $operations = '<ul id="icons">';
    $d = array("OK" => false, "title" => "upload", "body" => $this->upload_operation($sector), "w" => "550");
    $operations.= $cpanel->operation_buttons($sector, $sector, $sector, "upload", "arrowthickstop-1-n", "upload", $d);
    $operations .= $cpanel->operation_buttons($sector, "bookmark", "$sector\" disabled=\"disabled", "save-mail", 'disk', " {$language['lw.save']} {$language['lw.this']} {$language['lw.e-mail']}", false);
    $operations .= <<<html
<form name="bookmark-mass_mail-save-mail-form" id="bookmark-mass_mail-save-mail-form" method="post" action="#{$sector}">
    <input type="hidden" value="" name="UserTo" id="UserTo" />
    <input type="hidden" value="" name="mail_id" id="mail_id" />
    <input type="hidden" value="" name="UserFrom"  id="UserFrom" />
    <input type="hidden" value="" name="Subject"  id="Subject" />
    <input type="hidden" value="" name="Message" id="Message" />
    <input type="hidden" value="" name="SentDate" id="SentDate" />
</form>
html;
    $operations .= $cpanel->operation_buttons($sector, $sector, $sector, "saved-mail", 'bookmark', " {$language['lw.saved']} {$language['p.users.mbmk']}", array(
        "w" => 600,
        "OK" => true,
        'title' => " {$language['lw.saved']} {$language['main.cp.mail']}:",
        'body' => <<<eol
<div style="max-height:400px;overflow-y: auto;">
   <table border="0" width='100%'>{$bookmarks}</table>
</div>

eol
            )
    );
    $operations.= '</ul>';

    $host = kernel::base_tag("{host}");
    global $theme, $SQL_WEBSITE;

    if (isset($_POST['act_theme'])) {
        $_SESSION['mail_theme'] = strtolower($_POST['use_theme']);
    }


    if (!(bool) $_SESSION['mail_theme']) {
        $mail_theme = "mailing";
    } else {
        $mail_theme = $_SESSION['mail_theme'];
    }


    $generate_titlemail = ucfirst("{$language['lw.e-mail']} {$language['lw.from']} " . ucfirst($SQL_WEBSITE['site_name']));
    $tpl = theme::custom_template($mail_theme, array(
                "title" => "<a href=\"{$host}\" target=\"_blank\">{$generate_titlemail}</a>",
                "host" => kernel::base_tag("{host}"),
                "design" => kernel::base_tag("{design}"),
                "date" => $language['lan.months.' . strtolower(date("M"))] . " " . date(" j Y ")
            ));


    $templates_destination = ROOT . $theme->PROP['theme.spare_parts'] . $theme->PROP['MAIN']['template.folder_destination'];
//
// Find all custom templates
    foreach (glob($templates_destination . "template_mailing_*.tpl") as $mail_template) {
        $mail_template_title = ucfirst(str_replace(array($templates_destination . "template_mailing_", ".tpl"), "", $mail_template));
        $use_mail_theme .= <<<eol
      <label style="padding:10px;">
      <input type="radio" value="mailing_$mail_template_title" name="use_theme">
         <small>$mail_template_title</small> </label><br />


eol;
    }


    $mail_template_title = <<<eol
<form name="mail_theme" method="post" id="mail_theme">
   <div id="mail_themes" style="margin:5%;">

      <label style="padding:10px;">
      <input type="radio" value="mailing" name="use_theme">
      <small title="{$language['lw.default']}">{$language['lw.empty']}</small>   </label><br />

           $use_mail_theme
<p align="center">
<input type="submit" value="{$language['lw.activate']}" name="act_theme" style="width:80%;font-size:12px;cursor:pointer" />
    </p>
</div>
</form>
eol;

    if (isset($_POST['useit-bookmark'])) {
        $qure = db_query("SELECT * FROM `" . PREFIX . "mail` WHERE `mail_id`='{$_POST['useit-bookmark']}' ");
        $row = db_fetch($qure, "assoc", "current");
        if (!empty($row)) {
            $tpl = $row['Message'];
            $titlemail = $row['Subject'];
            $saved_options = "<option value=\"{$row['UserTo']}\"> {$language['lw.saved']} {$language['main.cp.users']}</option>";
        }
    }


    $textarea = theme::textarea('mailbody', $tpl, null, 25, null, '80%', '340px');
    $BODY = <<<eol
<script>
   $(function(){
   $("#mail-text").hide();
    $("#mail-type").change(function () {
   if( $("select#mail-type option:selected").val() == "text"){
        $("#mail-html").hide();
        $("#mail-html textarea").attr("disabled","disabled");
        $("#mail-text textarea").removeAttr("disabled");
        $("#mail-text").show();
    }else{
        $("#mail-text").hide();
        $("#mail-text textarea").attr("disabled","disabled");
        $("#mail-html textarea").removeAttr("disabled");
        $("#mail-html").show();
    }
});
   });
</script>
<form name="sendmail" action="#{$sector}" method="post">
<p align="center"> {$error}</p>
    <input type="hidden" value="{$ID}" name="theme_mail_id" id="theme_mail_id" />
<table width="80%" border="0" align="center">
   <tr>
   <th width="15%" class="ui-state-active">{$language['main.cp.mail']} {$language['cont.from.ttl']} *: </th>
   <td width="35%"><input type="text" value="$titlemail" name="title" id="title-mail" /></td>
   <th width="15%" class="ui-state-active"> {$language['lw.from']} {$language['lw.e-mail']}: </th>
   <td width="35%"><input type="text" value="{$SQL_WEBSITE['site_mail']}" name="from" id="from" /></td></td>
   </tr>
   <tr>
        <th class="ui-state-active"> {$language['p.users.mmls']}: </th>
            <td>
                <select name="mail-list" id="mail-list" >
                    {$saved_options}
                    <option value="all,">{$language['lw.all']} {$language['main.cp.users']}</option>
                    {$option_mails}
                </select>
         </td>
            <th  class="ui-state-active">{$language['p.users.omsm']}: </th>
            <td> <input type="text" value="" name="other" id="other-mails" title="{$language['p.users.omsh']}" /></td>
   </tr>
   <tr>
     <td class="ui-state-default" style="padding:15px;">
      {$operations}
                 </td>
     <th colspan="3" class="ui-state-active" style="padding:15px;"> {$language['main.cp.mail']} {$language['cont.from.mss']} :</th>
     </tr>
   <tr>
        <th valign="top" >
        <div class="ui-state-active"   style="padding:15px;">{$language['main.cp.mail']} {$language['lw.type']}: </div>
        <select name="mail-type" id="mail-type">
            <option value="html" selected="selected">HTML</option>
            <option value="text">TEXT</option>
            <option value="mix">MIX</option>
        </select>

        <div id="mail-type-html">
                <div class="ui-state-active"   style="padding:15px;">{$language['main.cp.mail']} HTML {$language['main.cp.theme']}: </div>
                $mail_template_title
         </div>
        </th>
        <td colspan="4">

         <center>
         <div id="mail-html">
         {$textarea}
             </div>
        <div id="mail-text">
            <textarea name="mailbody" disabled="disabled" style="width: 80%; height: 340px;">{$row['Message']}</textarea>
        </div>
             </center>

        </td>
</tr><tr>
            <td colspan="5"  align="center"><input type="submit" name="send-mail-submit" id="button" value="{$language['lan.submit']}" /></td>
</tr>
</table>
</form>
eol;
}