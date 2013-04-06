<?php

/**
  ============================================================
 * Last committed:      $Revision: 126 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-22 17:58:47 +0200 (ïåò, 22 ìàðò 2013) $
 * ID:                  $Id: manage_users.php 126 2013-03-22 15:58:47Z fire $
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


global $language;


$priority = "{$sector}_priority";


if ($_POST[$priority] && $_POST['userid'] && $_POST['userlevel']) {

    $_POST = stripslashes_deep($_POST);
    array_map('addslashes', $_POST);

    if (constant("USER_PRIV") > $_POST['userlevel']) {
        $slq = db_query("UPDATE  `" . USERS_SQLTBL_MAIN . "` SET `userlevel`='{$_POST['userlevel']}'

    WHERE `userid`='{$_POST['userid']}'; ") or ( $mysql_error = db_error());
    } else {

        $alert_error = $this->mysql_alert_box("Cannot set-up this user level with bigger from your own level!", $sector);
    }
}

if ($_POST['erase-user']) {

    $slq = db_query("DELETE FROM `" . USERS_SQLTBL_MAIN . "` WHERE `" . USERS_SQLTBL_MAIN . "`.`userid`='{$_POST['erase-user']}' ") or ($mysql_error = db_error());
}
if($_POST['input-save-group']){
    if(!is_array($_SESSION['saved-group'])){
        $_SESSION['saved-group'] = array();
    }
    array_push($_SESSION['saved-group'],explode(",",$_POST['input-save-group']));
}
if($_POST['input-erase-save-group']){
    $_SESSION['saved-group'] = null;
}

$BODY = <<<eol

   <script type="text/javascript">
   $(function(){
        $("#search-users").filtertbl({table:"#user-list"});
        $("#save-group").click(function(){
            $("#user-list").savegroup({
            elements: "td:first",
            elmntype: "text",
            outputin: "#input-save-group",
            formelmn: "#form-save-group"

        });
        });
        $("#erase-save-group").click(function(){
                  var checkconfirm =confirm('{$language['main.cp.question.erase']} {$language['lw.saved']} {$language['lw.view']}');
                  if (checkconfirm==true){
                        $("#form-erase-group").submit();

              }
        });

        $("#help-save-group").click(function(){
   $(".help-save-group").toggle();
});
   });
   </script>
<p>&nbsp; {$mysql_error} {$alert_error}
<div>
<input type="text" id="search-users" value="{$language['lw.search']}" style="width:15%;margin-left:10%;float:left;"
 onblur="if (this.value == '') {this.value = '{$language['lw.search']}';}"
 onfocus="if (this.value == '{$language['lw.search']}') {this.value = '';}"
 />
 <ul id="icons" style="float:left">
    <li id="save-group"><span class="ui-icon ui-icon-copy" title="{$language['lw.save']} {$language['lw.view']}" >&nbsp;</span></li>
    <li id="erase-save-group"> <span class="ui-icon ui-icon-trash " title="{$language['lw.erase']} {$language['lw.saved']} {$language['lw.view']}">&nbsp;</span> </li>
    <li id="help-save-group"> <span class="ui-icon ui-icon-help " title="help">&nbsp;</span> </li>
 </ul>
 <span class="help-save-group" style="display:none">{$language['p.users.hgr']}</span>
 </div>
</p>

<form name="save-group" id="form-save-group" method="post">
<input type="hidden" value="" name="input-save-group" id="input-save-group" />
</form>

<form name="form-erase-group" id="form-erase-group" method="post">
    <input type="hidden" value="true" name="input-erase-save-group"  />
</form>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="1" id="user-list">
eol;



$sql = db_query("SELECT * FROM `" . USERS_SQLTBL_MAIN . "`   ");
$BODY .= <<<eol
<tr>
    <th width="16px" align="center">{$language['p.users.id']}</th>
    <th width="auto">   {$language['p.users.na']}   </th>
    <th width="20%">    {$language['p.users.ro']}   </th>

    <th width="10%">    {$language['p.users.st']}   </th>
    <th width="15%">    {$language['p.users.mbf']}  </th>
    <th width="20%">    {$language['p.users.op']}   </th>
</tr>
eol;
$userlevel = "";
foreach ( db_fetch($sql) as $r) {

    $userlevel = user_status_privilege($r['userlevel']);


    if ($r['hash_generated'] != 0) {
        $time_ago = datediff(date('Y-m-d ', $r['hash_generated']), date('Y-m-d', time()));
    } else {
        $time_ago = "";
    }


    If ((bool) $r['valid']) {
        $status = $language['p.users.st.v'];
    } else {
        $status = $language['p.users.st.u'];
    }

// Buttons operations
    $options = "";
// $options .= $this->operation_buttons($r['userid'],$sector,$sector,"editus",'pencil'," {$language['lw.edit']} {$language['lw.user']} ");
    $options .= $this->operation_buttons($r[USERS_SQLTBL_COL_UID], $sector, $priority, $priority, 'battery-2', " {$language['lw.user']} {$language['lw.priority']}");
// ban user
    $options .= $this->operation_buttons($r[USERS_SQLTBL_COL_UID], $sector, $sector, "banned", 'unlocked', " {$language['lw.deny']} {$language['lw.website']} {$language['lw.for']} {$language['lw.this']} {$language['lw.user']}");
//erase user
    $options .= $this->operation_buttons($r[USERS_SQLTBL_COL_UID], $sector, $sector, "erase-user", 'trash', " {$language['lw.erase']} {$language['lw.user']}", array(
        "OK" => true,
        'title' => " {$language['main.cp.question.erase']}:",
        'body' => "{$language['lw.user']}: <b>{$r['username']}</b>"
            )
    );


    $BODY .=<<<eol
<tr>
    <td> {$r[USERS_SQLTBL_COL_UID]} </td>
    <td> {$r[USERS_SQLTBL_COL_UNAME]} </td>
    <td align="center"> <small>{$userlevel}</small> </td>

    <td> {$status} </td>
    <td align="center"> <small> {$time_ago} {$language['users.days']}  </small> </td>
    <td> <ul id="icons">{$options}</ul> </td>
</tr>
eol;
    $userlevel = "";
}


$BODY .=<<<eol

 </table>
eol;

$data = "";
$error = "";
$data = $this->mysql_select_sector($priority, USERS_SQLTBL_MAIN, " {$language['lw.user']} {$language['lw.not']} {$language['lw.selected']}.", "`userid`='{$_POST[$priority]}' LIMIT 1;", false);


if (is_array($data)) {
    $r = $data;
    $userlevel = user_status_privilege($r['userlevel']);
    $select = form_select_user_privilege('userlevel', $r['userlevel']);
} else {
    $error = $data;
}



$SUBBODY[$priority] = <<<EOL

<p>{$error}</p><br /><br><br>
<p>
<form id="{$priority}" method="post" action="#{$sector}">

<input type="hidden" name="userid" value="{$r['userid']}"  readonly>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<th width="16px" align="center">{$language['p.users.id']}</th>
<th width="auto">   {$language['p.users.na']}   </th>
<th width="20%">    {$language['p.users.ro']}   </th>


<th width="20%">  {$language['lw.new']}  {$language['p.users.ro']}   </th>

<th width="20%">  {$language['users.lan.changes']}    </th>

</tr>
<tr>
<td >{$r['userid']}</td>
<td >{$r['username']}</td>
<td >{$userlevel}</td>

<td >{$select}</td>
<td >{$r['lastchange']}</td>
</tr>
<tr>

<td colspan="5" align="center"><input type="submit" name="{$priority}" id="button" value="{$language['lan.submit']}" /></td>

</tr>

</table>
</form>
</p>
EOL;


