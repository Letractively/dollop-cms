<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: manage_users.php 115 2013-02-08 16:27:29Z fire $
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
        $slq = mysql_query("UPDATE  `" . USERS_SQLTBL_MAIN . "` SET `userlevel`='{$_POST['userlevel']}'

    WHERE `userid`='{$_POST['userid']}'; ") or ( $mysql_error = mysql_error());
    } else {

        $alert_error = $this->mysql_alert_box("Cannot set-up this user level with bigger from your own level!", $sector);
    }
}

if ($_POST['erase-user']) {

    $slq = mysql_query("DELETE FROM `" . USERS_SQLTBL_MAIN . "` WHERE `" . USERS_SQLTBL_MAIN . "`.`userid`='{$_POST['erase-user']}' ") or ($mysql_error = mysql_error());
}





$BODY = <<<eol

<p>&nbsp; {$mysql_error} {$alert_error}</p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="1">
eol;



$sql = mysql_query("SELECT * FROM `" . USERS_SQLTBL_MAIN . "`   ");
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
while ($r = mysql_fetch_array($sql)) {

    $userlevel = user_status_privilege($r['userlevel']);


    if ($r['hash_generated'] != 0) {
        $time_ago = datediff(date('Y-m-d ', $r['hash_generated']), date('Y-m-d', time()), true);
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
    <td align="center"> {$r[USERS_SQLTBL_COL_UID]} </td>
    <td> {$r[USERS_SQLTBL_COL_UNAME]} </td>
    <td> <small>{$userlevel}</small> </td>

    <td> {$status} </td>
    <td> <small> {$time_ago['Days']} {$language['users.days']}  </small> </td>
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


