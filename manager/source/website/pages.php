<?php

/**
  ============================================================
 * Last committed:     $Revision: 134 $
 * Last changed by:    $Author: fire1 $
 * Last changed date:    $Date: 2013-04-05 12:49:50 +0300 (ïåò, 05 àïð 2013) $
 * ID:       $Id: pages.php 134 2013-04-05 09:49:50Z fire1 $
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
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
global $language, $cpanel;
$heightscripts = "450";
$host = HOST;
$sector_insert_page = 'insert_' . $sector;
$sector_edit_page = 'edit_' . $sector;
$sector_addt_page_js = 'js_' . $sector;
$sector_addt_page_php = 'php_' . $sector;
$sector_preference = 'preference_' . $sector;
$this->show_sublink[] = $sector_insert_page;

function class_view($inuse = false) {
    $option = null;
    $option .= <<<eol

        <option value=""></option>
eol;

    $result = db_query("SELECT class_view FROM  `" . PREFIX . "pages` GROUP BY class_view");
    foreach (db_fetch($result, "assoc") as $row) {

        if ($inuse == $row['class_view']){
            $selected = 'selected="selected"';
        }else{
            $selected = null;
        }
        if (!empty($row['class_view'])) {
            $option .= <<<eol

        <option value="{$row['class_view']}" $selected>{$row['class_view']}</option>
eol;
        }
    }
    return $option;
}

///////////// SCRIPTS
if ((bool)$_POST[$sector_addt_page_js]) {
    $_POST = stripslashes_deep($_POST);
    $_POST = array_map('addslashes', $_POST);
    $slq = db_query("UPDATE  `" . PREFIX . "pages` SET `jscripts` ='{$_POST['jscripts']}'

            WHERE `ID`='{$_POST['id']}'; ") or ($mysql_error = db_error());
}
if ((bool)$_POST[$sector_addt_page_php]) {
    $_POST = stripslashes_deep($_POST);
    $_POST = array_map('addslashes', $_POST);
    $slq = db_query("UPDATE  `" . PREFIX . "pages` SET `phpcripts` ='{$_POST['phpscripts']}'

            WHERE `ID`='{$_POST['id']}'; ") or ($mysql_error = db_error());
}
//////////////////////////////////////////// UPDATE
if ($_POST["update_{$sector}"] && $_POST['id']) {
    $_POST = stripslashes_deep($_POST);
    $_POST['preference'] = serialize($_POST['preference']);
    $_POST['title'] = (htmlspecialchars(trim($_POST['title'])));
    $_POST = array_map('addslashes', $_POST);
    if (!empty($_POST['category_new'])) {
        $class_view = $_POST['category_new'];
    } else {
        $class_view = $_POST['category_old'];
    }
    $slq = db_query("UPDATE  `" . PREFIX . "pages`

            SET

                `title`='{$_POST['title']}' ,
                `body`='{$_POST['body']}',
                `class_view`='$class_view',
                `description`='{$_POST['description']}',
                `keywords`='{$_POST['keywords']}',
                `metatags`='{$_POST['metatags']}',
                `dates`='{$_POST['dates']}',
                `admin`='{$_POST['admin']}',
                `preference`='{$_POST['preference']}',
                `comment`='{$_POST['comment']}',
                `com_user`='{$_POST['com_user']}'

            WHERE `ID`='{$_POST['id']}';

            ") or ($mysql_error = db_error());
}
//////////////////////////////////////////// INSER
if ($_POST["insert_{$sector}"]) {
    $_POST = stripslashes_deep($_POST);
    $_POST['preference'] = serialize($_POST['preference']);
    $_POST['title'] = (htmlspecialchars(trim($_POST['title'])));
    $_POST = array_map('addslashes', $_POST);
    if (!empty($_POST['category_new'])) {
        $class_view = $_POST['category_new'];
    } else {
        $class_view = $_POST['category_old'];
    }
    $slq = db_query("INSERT INTO `" . PREFIX . "pages`

            ( `title` , `body`,`class_view`,`description`,`keywords`,`metatags`,`dates`,`admin`,`preference`,`comment`,`com_user` )

            VALUES

            (

            '{$_POST['title']}',
            '{$_POST['body']}',
            '$class_view',
            '{$_POST['description']}',
            '{$_POST['keywords']}',
            '{$_POST['metatags']}',
            '{$_POST['dates']}',
            '{$_POST['admin']}',
            '{$_POST['preference']}',
            '{$_POST['comment']}',
            '{$_POST['com_user']}'



            ); ") or ($mysql_error = db_error());
}
//////////////////////////////////////////// ERASE
if ($_POST['erase-this-page']) {
    $slq = db_query("DELETE FROM `" . PREFIX . "pages` WHERE `" . PREFIX . "pages`.`ID`='{$_POST['erase-this-page']}' ") or ($mysql_error = db_error());
}

//////////////////////////////////////////// INSERT preference
if (isset($_POST["update_{$sector_preference}"]) && is_numeric($_POST['id'])) {
    $_POST = stripslashes_deep($_POST);
    $_POST['preference'] = serialize($_POST['preference']);
    $_POST['preference'] = addslashes($_POST['preference']);

    db_query("UPDATE `" . PREFIX . "pages` SET `preference`='{$_POST['preference']}' WHERE `ID`='{$_POST['id']}'  ") or ($mysql_error = db_error());
}



$BODY = <<<eol

<p>&nbsp;  <input type="text" id="search-input-{$sector}" value="{$language['lw.search']}" style="width:18%;margin-left:10%;float:left;"
 onblur="if (this.value == '') {this.value = '{$language['lw.search']}';}"
 onfocus="if (this.value == '{$language['lw.search']}') {this.value = '';}"
 /> </p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0" id="table-list-{$sector}">
eol;

$my_userLevel = USER_PRIV;

$result = db_query("SELECT * FROM `" . PREFIX . "pages` WHERE `admin`<='$my_userLevel' ");
$BODY.= <<<eol
<tr class="ui-state-active">
    <th width="auto">{$language['lw.titles']}</th>
    <th width="45%">{$language['lw.addresses']}</th>
    <th width="155px">{$language['lw.options']}</th>
</tr>
eol;
$js = <<<js
$(document).ready(function() {

   $("#search-input-{$sector}").filter_table({table:"#table-list-{$sector}"});

js;
$OK = ucfirst(MAIN_p_ok);
$Close = ucfirst(MAIN_p_close);
$host = HOST;
$i = 0;
foreach (db_fetch($result, "assoc")as $r) {
    $js.= <<<js

$('#slct-{$i}-input').click(function()      { $('#slct-{$i}-input').select();});
$('#slct-{$i}-input2').click(function()      { $('#slct-{$i}-input2').select();});
$('#edit-{$i}-button').click(function()     { $('#edit-{$i}-form').submit(); });
$('#link-{$i}-button').click(function()     { $('#link-{$i}-form').submit(); });
$('#sgnl-{$i}-button').click(function()     { $('#sgnl-{$i}-form').submit(); });

                $('#dialog-{$i}-box-pages').dialog({
                    autoOpen: false,
                    width: 400,
                    buttons: {
                        "{$language['lw.ok']}": function() {
                                             $('#erase-{$i}-form-pages').submit();
                        },
                        "{$language['lw.close']}": function() {
                            $(this).dialog("close");
                        }
                    }
                });

                $('#dialog-{$i}-box-attr-pages').dialog({
                    autoOpen: false,
                    width: 800,
                    buttons: {

                        "{$language['lw.close']}": function() {
                            $(this).dialog("close");
                        }
                    }
                });

$('#erase-{$i}-button-page').click(function(){ $('#dialog-{$i}-box-pages').dialog('open');return false; });
$('#attr-{$i}-button-page').click(function(){ $('#dialog-{$i}-box-attr-pages').dialog('open');return false; });
js;

if(!empty($r['class_view'])){
    $page_link = <<<eol
   <input type="text" id="slct-{$i}-input" value="{$host}page?view={$r['ID']}" style="width:40%;float :left;" readonly/>
   <input type="text" id="slct-{$i}-input2" value="{$host}page?tabs={$r['class_view']}:{$r['ID']}" style="width:40%;float :left;" readonly/>
eol;
}else{
    $page_link = <<<eol
   <input type="text" id="slct-{$i}-input" value="{$host}page?view={$r['ID']}" style="float :left;width:85%;" readonly/>
eol;

}

    $BODY.= <<<eol
<tr class="ui-state-default">
<td align="left"> {$r['title']} </td>
<td>
$page_link
</td>
 <td>
 <!-- ERASE PAGE -->

        <div id="dialog-{$i}-box-attr-pages" calss="dialog" title="{$language['lw.options']}:">
            <table border="0" style="width:100%; position: relative;">
            <tr>
                <td width="50%" style="width:50% !important" align="center">
                <form id="attr-{$i}-form-js" method="post" action="#{$sector_addt_page_js}" name="attr-js_form-{$sector}">
                <input type="hidden" name="addt-this-page-js" value="{$r['ID']}">
               <input type="submit" name="addt-this-page-js-button" id="button" value="JavaScript" style="width:100%;"/>
                </form>
                </td>

                <td width="50%" style="width:50% !important" align="center">
                <form id="attr-{$i}-form-php" method="post" action="#{$sector_addt_page_php}" name="attr-php_form-{$sector}">
                <input type="hidden" name="addt-this-page-php" value="{$r['ID']}">
                <input type="submit" name="addt-this-page-php-button" id="button" value="P.H.P." style="width:100%;"/>
                </form>
                </td>
            <tr>
            </table>
        </div>

        <div id="dialog-{$i}-box-pages" calss="dialog" title="{$language['main.cp.question.erase']}:">
            <p>{$language['lw.page']} <b>{$r['title']}</b></p>
        </div>
<form id="erase-{$i}-form-pages" method="post" action="#{$sector}" target="_self" name="erase_form-{$sector}">
    <input type="hidden" name="erase-this-page" value="{$r['ID']}"  readonly>
</form>
 <!-- ERASE PAGE -->

<ul id="icons">

        <li class="ui-state-default ui-corner-all" id="erase-{$i}-button-page" title="{$language['lw.erase']}">
        <span  class="ui-icon ui-icon-trash"></span>
</li>

<form id="edit-{$i}-form" method="post" action="#{$sector_edit_page}" target="_self" name="edit_form-{$sector}">
    <input type="hidden" name="edit-this-page" value="{$r['ID']}"  readonly>
        <li class="ui-state-default ui-corner-all" id="edit-{$i}-button" title="{$language['lw.edit']}">
        <span  class="ui-icon ui-icon-pencil"></span>
        </li>
</form>


<form id="link-{$i}-form" method="post" action="#links" target="_self" name="link_form-{$sector}">
    <input type="hidden" name="url_new-links" value="/page?view={$r['ID']}"  readonly>
    <input type="hidden" name="title_new-links" value="{$r['title']}"  readonly>
    <input type="hidden" name="position_new-links" value="last"  readonly>
    <input type="hidden" name="target_new-links" value="_self"  readonly>
        <li class="ui-state-default ui-corner-all" id="link-{$i}-button" title="{$language['lw.link']}">
        <span  class="ui-icon ui-icon-link"></span>
        </li>
</form>


    <li class="ui-state-default ui-corner-all" id="attr-{$i}-button-page" title="{$language['lw.more']}">
    <span class="ui-icon ui-icon-script"></span>
    </li>

<form id="sgnl-{$i}-form" method="post" action="#{$sector_preference}" target="_self" name="sqnl_form-{$sector}">
    <input type="hidden" name="preference-this-page" value="{$r['ID']}"  readonly />
    <li class="ui-state-default ui-corner-all" id="sgnl-{$i}-button" title="{$language['lw.additional']}">
    <span class="ui-icon ui-icon-signal-diag"></span>
    </li>
</form>

</ul>



</td>
</tr>
eol;
    $i++;
}
$BODY.= <<<eol
</table>
eol;
////// INSERT PAGE
$operations = '';
$d = array("OK" => false, "title" => "upload", "body" => $this->upload_operation($sector), "w" => "660");
$operations.= $this->operation_buttons($sector_insert_page, $sector_insert_page, $sector_insert_page, "upload", "arrowthickstop-1-n", "upload", $d);


$textarea = theme::textarea('body', null, null, 25, null, '80%', '340px');

$js .= <<<eol
    $(".addtioanl").hide();
       $("#additional-toggle").click(function(){
            $(".optioanl").hide(5);
            $(".adminedin").hide(5);
            $(".addtioanl").toggle();


        });
        $(".optioanl").hide();
       $("#options-toggle").click(function(){
             $(".addtioanl").hide(5);
             $(".adminedin").hide(5);
            $(".optioanl").toggle();

        });

        $(".adminedin").hide();
       $("#adminedin-toggle").click(function(){
             $(".addtioanl").hide(5);
             $(".optioanl").hide(5);
            $(".adminedin").toggle();

        });

        $("#switch-group-classview-insert").click(function(){
        $(".group-classview-insert").toggle();
});

eol;
$page_preference = null;
global $USERS_PRIVILEGE;
$propt_deni = null;
$propt_edit = null;
foreach ($USERS_PRIVILEGE['users.privilege'] as $lv => $nm) {
    if ($page_preference['denied'] == "{$lv}.{$nm}") {
        $deni_sl = 'selected="selected"';
    }
    $propt_deni .=<<<eol
   <option value="{$lv}.{$nm}" $deni_sl>{$nm}</option>
eol;

    $deni_sl = null;
}
foreach ($USERS_PRIVILEGE['users.privilege'] as $lv => $nm) {
    if ($page_preference['edit'] == "{$lv}") {
        $propt_sl = 'selected="selected"';
    }
    if ($USERS_PRIVILEGE['users.cpanel'] <= $lv) {
        $propt_edit .=<<<eol
   <option value="{$lv}" $deni_sl>{$nm}</option>
eol;
    }
    $propt_sl = null;
}
//$operation_buttons = $cpanel->oper
$class_view = class_view();
$dates = date('Y-m-d');
$myusername = USER_NAME;
$SUBBODY[$sector_insert_page] = <<<html

<form id="insert" method="post" action="#{$sector}" target="_self" name="insert_form-{$sector}">
<p/>
<p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<td width="10%">&nbsp;</td>
<td></td>
</tr>
<tr>
<th align="right" class="ui-state-active">{$language['lw.page']} <br/> {$language['lw.title']}</td>
<td class="ui-state-default"><input type="text" name="title"  value="" /></td>
</tr>
<tr>
<td></td>
<td>
<ul id="icons">
   <li class="ui-state-default ui-corner-all" id="additional-toggle" title="{$language['lw.additional']}">
        <span class="ui-icon ui-icon-signal-diag"></span>
    </li>
     <li class="ui-state-default ui-corner-all" id="options-toggle" title="{$language['lw.additional']}">
        <span class="ui-icon ui-icon-newwin"></span>
    </li>
     <li class="ui-state-default ui-corner-all" id="adminedin-toggle" title="{$language['lw.additional']}">
        <span class="ui-icon ui-icon-person"></span>
    </li>
    {$operations}
</ul>
</td>
</tr>
<!-- attachments tags -->
<tr style="display: table-row !important">
<th align="right" class="ui-state-active addtioanl online-page">{$language['lw.page']} <br/> {$language['lw.additional']}</td>
<td class="ui-state-default addtioanl online-page">
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['lw.meta']} {$language['lw.keywords']} </small><br />
    <input type="text" name="keywords"  value="" style="font-size:12px;" />
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small>{$language['lw.meta']} {$language['lw.description']} </small><br />
    <textarea name="metatags" style="font-size:12px;" rows="1"></textarea>
    </label>
</td>
</tr>
<tr >
<td class="addtioanl online-page">&nbsp;</td>
<td class="addtioanl online-page">&nbsp;</td>
</tr>
<!-- attachments tags -->

<!-- attachments category -->
<tr style="display: table-row !important">
<th align="right" class="ui-state-active optioanl online-page">{$language['lw.page']} <br/> {$language['lw.execute']}</td>
<td class="ui-state-default optioanl online-page">
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['lw.page']} {$language['lw.category']} </small><br />
    <select name="category_old" class="group-classview-insert" style="font-size:12px;width:80%;float:left">
        $class_view
    </select>
    <input type="text" name="category_new" maxlength="50" value="" style="font-size:12px;width:70%;display:none;float:left;"  class="group-classview-insert" />
    <span class="ui-icon ui-icon-circle-plus" id="switch-group-classview-insert" style="float:left;"></span>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small>{$language['lw.category']} {$language['lw.description']} </small><br />
    <textarea name="description" style="font-size:12px;" rows="1"></textarea>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small>{$language['lw.page']} {$language['lw.like']} {$language['web.news.articles']}  </small><br />
        <input type="checkbox" value="{$dates}" name="dates" /> <small> <font color="#2694e8"> <b>*</b>page category will act like articles!</font></small>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small>{$language['lw.allow']} {$language['main.cp.comments']}  </small><br />
        <input type="checkbox" value="1" name="com_user" /> <small> <font color="#2694e8"> <b>*</b> Comments on page</font></small>
    </label>
</td>
</tr>
<tr >
<td class="optioanl online-page">&nbsp;</td>
<td class="optioanl online-page">&nbsp;</td>
</tr>
<!-- attachments category-->


<!-- attachments admin -->
<tr style="display: table-row !important">
<th align="right" class="ui-state-active adminedin online-page">{$language['lw.page']} <br/> {$language['lw.priority']}</td>
<td class="ui-state-default adminedin online-page">
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['lw.denied']} {$language['lw.page']} {$language['lw.to']} {$language['main.cp.user']} {$language['lw.level']} </small><br />
   <select name="preference[denied]" >{$propt_deni}</select>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['lw.allow']} {$language['lw.page']} {$language['lw.edit']} {$language['lw.from']} {$language['lw.level']} </small><br />
    <select name="admin" >{$propt_edit}</select>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['main.cp.comments']} / {$language['lw.author']}  </small><br />
    <textarea name="comment" style="font-size:12px;" rows="1"> {$language['lw.author']}: {$myusername}</textarea>
    </label>
</td>
</tr>
<tr >
<td class="adminedin online-page">&nbsp;</td>
<td class="adminedin online-page">&nbsp;</td>
</tr>
<!-- attachments admin -->

<tr>
<th align="right" class="ui-state-active">{$language['lw.page']} <br/> {$language['lw.content']}</td>
<td class="ui-state-default">  <center>{$textarea}</center>  </td>
</tr>
  <tr>
        <td colspan="2" align="center">
        <input type="submit" name="insert_{$sector}" id="button" value="{$language['lan.submit']}" />
        </td>

    </tr>

</table>
</form>
html;
//////////////// Jscript
$edit_info = "";
if ($_POST['addt-this-page-js']) {
    $sql = db_query("SELECT * FROM  `" . PREFIX . "pages` WHERE `ID`='{$_POST['addt-this-page-js']}' LIMIT 1; ");
    $r = db_fetch($sql,"assoc","current");
} else {
    $text = "{$language['lw.the']} {$language['lw.page']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_edit_menu);
}
$area_js = $this->script_editor('js', "{$sector_addt_page_js}-js", 'jscripts', htmlspecialchars($r['jscripts']), 'style="height: 450px;"');
$autocomplete = $this->autocomplete_function_editor('js', "acm-{$sector_addt_page_js}");
$SUBBODY[$sector_addt_page_js] = <<<html
<form id="{$sector_addt_page_js}" name="{$sector_addt_page_js}" method="post" action="#{$sector}" target="_self">
<style>.CodeMirror-scroll {height:{$heightscripts}px;}</style>
<input type="hidden" name="id" value="{$r['ID']}"  readonly>
<p/>
<p>{$edit_info}
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<td width="10%">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<th height="450px" rowspan="2" class="ui-state-active"> JavaScript {$language['lw.page']} <br/> {$language['lw.section']} </th>
<td>{$autocomplete}</td>
  </tr>
  <tr>
 <td height="420px">
    {$area_js}
 </td>
</tr>
<tr>
        <td colspan="2" align="center">
        <input type="submit" name="{$sector_addt_page_js}" id="button" value="{$language['lan.submit']}" />
        </td>

    </tr>
</table>

</form>
html;
//////////////// PHPscript
$r = null;
$area_js = "";
$edit_info = '';
$autocomplete = "";
if ($_POST['addt-this-page-php']) {
    $sql = db_query("SELECT * FROM  `" . PREFIX . "pages` WHERE `ID`='{$_POST['addt-this-page-php']}' LIMIT 1; ");
    $r = db_fetch($sql,"assoc","current");
} else {
    $text = "{$language['lw.the']} {$language['lw.page']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_edit_menu);
}

$area_php = $this->script_editor('php', "{$sector_addt_page_js}-php", 'phpscripts', htmlspecialchars($r['phpcripts']), 'style="height: 450px;"');
$autocomplete = $this->autocomplete_function_editor('php', "acm-{$sector_addt_page_php}");
$SUBBODY[$sector_addt_page_php] = <<<html
<form id="{$sector_addt_page_php}" name="{$sector_addt_page_php}" method="post" action="#{$sector}" target="_self">
<style>.CodeMirror {height:{$heightscripts}px;}</style>
<input type="hidden" name="id" value="{$r['ID']}"  readonly>
<p/>
<p>{$edit_info}
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<td width="10%">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<th height="450px" rowspan="2" class="ui-state-active"> PHP {$language['lw.page']} <br/> {$language['lw.section']} </th>
<td>{$autocomplete}</td>
  </tr>
  <tr>
 <td height="420px">
    {$area_php}
 </td>
</tr>
<tr>

        <td colspan="2" align="center">
        <input type="submit" name="{$sector_addt_page_php}" id="button" value="{$language['lan.submit']}" />
        </td>

    </tr>
</table>

</form>
html;
////////////////////////////////////////////////////// EDIT PAGE
$edit_info = "";
$r = null;
if ($_POST['edit-this-page']) {
    $result = db_query("SELECT * FROM  `" . PREFIX . "pages` WHERE `ID`={$_POST['edit-this-page']} ");
    $r = db_fetch($result, "assoc", "current");
    $page_preference = unserialize($r['preference']);
} else {
    $text = "{$language['lw.the']} {$language['lw.page']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_edit_page);
}

$js .= <<<eol
    $(".addtioanl-edit").hide();
       $("#additional-toggle-edit").click(function(){
            $(".optioanl-edit").hide(5);
            $(".adminedin-edit").hide(5);
            $(".addtioanl-edit").toggle();


        });
        $(".optioanl-edit").hide();
       $("#options-toggle-edit").click(function(){
             $(".addtioanl-edit").hide(5);
             $(".adminedin-edit").hide(5);
            $(".optioanl-edit").toggle();

        });

        $(".adminedin-edit").hide();
       $("#adminedin-toggle-edit").click(function(){
             $(".addtioanl-edit").hide(5);
             $(".optioanl-edit").hide(5);
            $(".adminedin-edit").toggle();

        });

        $("#switch-group-classview-update").click(function(){
        $(".group-classview-update").toggle();
});
eol;


$textarea = theme::textarea('body', $r['body'], null, 25, null, '80%', '325px');
$operations = '';
$d = array("OK" => false, "title" => "upload", "body" => $this->upload_operation($sector), "w" => "660");
$operations.= $this->operation_buttons($sector_edit_page, $sector_edit_page, $sector_edit_page, "upload", "arrowthickstop-1-n", "upload", $d);
$page_preference = null;
global $USERS_PRIVILEGE;
$propt_deni = null;
$propt_edit = null;
foreach ($USERS_PRIVILEGE['users.privilege'] as $lv => $nm) {
    if ($page_preference['denied'] == "{$lv}.{$nm}") {
        $deni_sl = 'selected="selected"';
    } else {
        $deni_sl = null;
    }

    $propt_deni .=<<<eol
   <option value="{$lv}.{$nm}" $deni_sl>{$nm}</option>
eol;

    $deni_sl = null;
}
foreach ($USERS_PRIVILEGE['users.privilege'] as $lv => $nm) {
    if ($r['admin'] == "{$lv}") {
        $propt_sl = 'selected="selected"';
    } else {
        $propt_sl = null;
    }
    if ($USERS_PRIVILEGE['users.cpanel'] <= $lv) {
        $propt_edit .=<<<eol
   <option value="{$lv}" $deni_sl>{$nm}</option>
eol;
    }
    $propt_sl = null;
}
//$operation_buttons = $cpanel->oper
$class_view = class_view($r['class_view']);
if ($r['dates']) {
    $dates = date('Y-m-d');
    $datec = 'checked';
}
$myusername = USER_NAME;
if (!empty($r['com_user'])) {
    $com_user = 'checked';
}
$SUBBODY[$sector_edit_page] = <<<html

<form id="edit_form" method="post" action="#{$sector}" target="_self" name="edit_form-{$sector}">
<input type="hidden" name="id" value="{$r['ID']}"  readonly>
<p align="center">
<b>{$edit_info}</b>
</p>
<p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<td width="10%">&nbsp;</td>
<td></td>
</tr>
<tr>
<th align="right" class="ui-state-active">{$language['lw.page']} <br/> {$language['lw.title']}</td>
<td class="ui-state-default"><input type="text" name="title"  value="{$r['title']}" /></td>
</tr>
<tr>
<td></td>
<td>
<ul id="icons">
   <li class="ui-state-default ui-corner-all" id="additional-toggle-edit" title="{$language['lw.additional']}">
        <span class="ui-icon ui-icon-signal-diag"></span>
    </li>
     <li class="ui-state-default ui-corner-all" id="options-toggle-edit" title="{$language['lw.additional']}">
        <span class="ui-icon ui-icon-newwin"></span>
    </li>
     <li class="ui-state-default ui-corner-all" id="adminedin-toggle-edit" title="{$language['main.cp.user']} {$language['lw.data']}">
        <span class="ui-icon ui-icon-person"></span>
    </li>
    {$operations}
</ul>
</td>
</tr>
<!-- attachments tags -->
<tr style="display: table-row !important">
<th align="right" class="ui-state-active addtioanl-edit online-page">{$language['lw.page']} <br/> {$language['lw.additional']}</td>
<td class="ui-state-default addtioanl-edit online-page">
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['lw.meta']} {$language['lw.keywords']} </small><br />
    <input type="text" name="keywords"  value="{$r['keywords']}" style="font-size:12px;" />
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small>{$language['lw.meta']} {$language['lw.description']} </small><br />
    <textarea name="metatags" style="font-size:12px;" rows="1">{$r['metatags']}</textarea>
    </label>
</td>
</tr>
<tr >
<td class="addtioanl-edit online-page">&nbsp;</td>
<td class="addtioanl-edit online-page">&nbsp;</td>
</tr>
<!-- attachments tags -->

<!-- attachments category -->
<tr style="display: table-row !important">
<th align="right" class="ui-state-active optioanl-edit online-page">{$language['lw.page']} <br/> {$language['lw.execute']}</td>
<td class="ui-state-default optioanl-edit online-page">
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['lw.page']} {$language['lw.category']} </small><br />
    <select name="category_old" class="group-classview-update" style="font-size:12px;width:80%;float:left">
        $class_view
    </select>
    <input type="text" name="category_new" maxlength="50" value="" style="font-size:12px;width:70%;display:none;float:left;"  class="group-classview-update" />
    <span class="ui-icon ui-icon-circle-plus" id="switch-group-classview-update" style="float:left;"></span>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small>{$language['lw.category']} {$language['lw.description']} </small><br />
    <textarea name="description" style="font-size:12px;" rows="1">{$r['description']}</textarea>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small>{$language['lw.page']} {$language['lw.like']} {$language['web.news.articles']}  </small><br />
        <input type="checkbox" value="{$dates}" name="dates" $datec/> <small> <font color="#2694e8"> <b>*</b>page category will act like articles!</font></small>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small>{$language['lw.allow']} {$language['main.cp.comments']}  </small><br />
        <input type="checkbox" value="1" name="com_user" $com_user/> <small> <font color="#2694e8"> <b>*</b> Comments on page</font></small>
    </label>
</td>
</tr>
<tr >
<td class="optioanl-edit online-page">&nbsp;</td>
<td class="optioanl-edit online-page">&nbsp;</td>
</tr>
<!-- attachments category-->


<!-- attachments admin -->
<tr style="display: table-row !important">
<th align="right" class="ui-state-active adminedin-edit online-page">{$language['lw.page']} <br/> {$language['lw.priority']}</td>
<td class="ui-state-default adminedin-edit online-page">
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['lw.denied']} {$language['lw.page']} {$language['lw.to']} {$language['main.cp.user']} {$language['lw.level']} </small><br />
   <select name="preference[denied]" >{$propt_deni}</select>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['lw.allow']} {$language['lw.page']} {$language['lw.edit']} {$language['lw.from']} {$language['lw.level']} </small><br />
    <select name="admin" >{$propt_edit}</select>
    </label>
    <label style="display:inline-block;float:left;width:20%">
    <small> {$language['main.cp.comments']} / {$language['lw.author']}  </small><br />
    <textarea name="comment" style="font-size:12px;" rows="1"> {$language['lw.author']}: {$myusername}</textarea>
    </label>
</td>
</tr>
<tr >
<td class="adminedin-edit online-page">&nbsp;</td>
<td class="adminedin-edit online-page">&nbsp;</td>
</tr>
<!-- attachments admin -->

<tr>
<th align="right" class="ui-state-active">{$language['lw.page']} <br/> {$language['lw.content']}</td>
<td class="ui-state-default">  <center>{$textarea}</center>  </td>
</tr>
  <tr>
        <td colspan="2" align="center">
        <input type="submit" name="update_{$sector}" id="button" value="{$language['lan.submit']}" />
        </td>

    </tr>

</table>
</form>
html;

//update_{$sector}

$r = null;
$edit_info = "";
if ($_POST['preference-this-page']) {
    $result = db_query("SELECT `ID`,`preference` FROM  `" . PREFIX . "pages` WHERE `ID`={$_POST['preference-this-page']} LIMIT 1; ");
    $r = db_fetch($result, "assoc", "current");
} else {
    $text = "{$language['lw.the']} {$language['lw.page']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_preference);
}
if (!empty($r['preference'])) {
    $page_preference = unserialize($r['preference']);
}
global $USERS_PRIVILEGE;
$propt_deni = null;
$propt_edit = null;
foreach ($USERS_PRIVILEGE['users.privilege'] as $lv => $nm) {
    if ($page_preference['denied'] == "{$lv}.{$nm}") {
        $deni_sl = 'selected="selected"';
    }
    $propt_deni .=<<<eol
   <option value="{$lv}.{$nm}" $deni_sl>{$lv}.{$nm}</option>
eol;

    $deni_sl = null;
}
foreach ($USERS_PRIVILEGE['users.privilege'] as $lv => $nm) {
    if ($page_preference['edit'] == "{$lv}.{$nm}") {
        $propt_sl = 'selected="selected"';
    }
    if ($USERS_PRIVILEGE['users.cpanel'] <= $lv) {
        $propt_edit .=<<<eol
   <option value="{$lv}.{$nm}" $deni_sl>{$lv}.{$nm}</option>
eol;
    }
    $propt_sl = null;
}
$SUBBODY[$sector_preference] = <<<html
<form id="preference_form" method="post" action="#{$sector}" target="_self" name="preference_form-{$sector}">
<input type="hidden" name="id" value="{$r['ID']}"  readonly>
<p align="center">
<b>{$edit_info} &nbsp;</b>
</p>
<p>
<table width="80%" border="0" align="center">
  <tr class="ui-state-active">
    <th>{$language['lw.meta']} {$language['lw.keywords']}</th>
    <th>{$language['lw.meta']} {$language['lw.description']}</th>
  </tr>
  <tr>
    <td><input type="text" value="{$page_preference['keywords']}" name="preference[keywords]" /></td>
    <td><textarea name="preference[description]" >{$page_preference['description']}</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="ui-state-active">
    <th>{$language['lw.denied']} {$language['lw.to']}:</th>
    <th>{$language['lw.allow']} {$language['lw.edit']} {$language['lw.from']}:</th>
  </tr>
  <tr>
    <td><select name="preference[denied]" >{$propt_deni}</select></td>
    <td><select name="preference[edit]" >{$propt_edit}</select></td>
  </tr>
   <tr>
        <td colspan="2" align="center">
        <input type="submit" name="update_{$sector_preference}" id="button" value="{$language['lan.submit']}" />
        </td>

    </tr>
</table>
</form>
</p>




html;



////////////////////////////////////////////////////// EDIT PAGE
$js.= <<<js
});
js;
$this->html_include($js, 'jscript');
