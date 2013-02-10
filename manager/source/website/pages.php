<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: pages.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
global $language;
$heightscripts = "450";
$host = HOST;
$sector_insert_page = 'insert_' . $sector;
$sector_edit_page = 'edit_' . $sector;
$sector_addt_page_js = 'js_' . $sector;
$sector_addt_page_php = 'php_' . $sector;
$this->show_sublink[] = $sector_insert_page;
///////////// SCRIPTS
if (@$_POST[$sector_addt_page_js]) {
    $_POST = stripslashes_deep($_POST);
    $_POST = $_POST = array_map('addslashes', $_POST);
    $slq = mysql_query("UPDATE  `" . PREFIX . "pages` SET `jscripts` ='{$_POST['jscripts']}'

            WHERE `ID`='{$_POST['id']}'; ") or ($mysql_error = mysql_error());
}
if (@$_POST[$sector_addt_page_php]) {
    $_POST = stripslashes_deep($_POST);
    $_POST = $_POST = array_map('addslashes', $_POST);
    $slq = mysql_query("UPDATE  `" . PREFIX . "pages` SET `phpcripts` ='{$_POST['phpscripts']}'

            WHERE `ID`='{$_POST['id']}'; ") or ($mysql_error = mysql_error());
}
//////////////////////////////////////////// UPDATE
if ($_POST["update_{$sector}"] && $_POST['id']) {
    $_POST = stripslashes_deep($_POST);
    $_POST['title'] = (htmlspecialchars(trim($_POST['title'])));
    $_POST = $_POST = array_map('addslashes', $_POST);
    $slq = mysql_query("UPDATE  `" . PREFIX . "pages` 

            SET 

            `title` ='{$_POST['title']}',
            `body`  ='{$_POST['body']}'

            WHERE `ID`='{$_POST['id']}';") or ($mysql_error = mysql_error());
}
//////////////////////////////////////////// INSER
if ($_POST["insert_{$sector}"]) {
    $_POST = stripslashes_deep($_POST);
    $_POST['title'] = (htmlspecialchars(trim($_POST['title'])));
    $_POST = array_map('addslashes', $_POST);
    $slq = mysql_query("INSERT INTO `" . PREFIX . "pages`

            ( `title` , `body` ) VALUES ('{$_POST['title']}', '{$_POST['body']}' ); ") or ($mysql_error = mysql_error());
}
//////////////////////////////////////////// ERASE
if ($_POST['erase-this-page']) {
    $slq = mysql_query("DELETE FROM `" . PREFIX . "pages` WHERE `" . PREFIX . "pages`.`ID`='{$_POST['erase-this-page']}' ") or ($mysql_error = mysql_error());
}
$BODY = <<<eol

<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
eol;
$sql = mysql_query("SELECT * FROM `" . PREFIX . "pages`");
$BODY.= <<<eol
<tr class="ui-state-active">
<th width="auto">{$language['lw.titles']}</th>
<th width="45%">{$language['lw.addresses']}</th>
<th width="155px">{$language['lw.options']}</th>
</tr>
eol;
$js = <<<js
$(document).ready(function() {
js;
$OK = ucfirst(MAIN_p_ok);
$Close = ucfirst(MAIN_p_close);
$host = HOST;
$i = 0;
while ($r = mysql_fetch_array($sql)) {
    $js.= <<<js

$('#slct-{$i}-input').click(function()      { $('#slct-{$i}-input').select();});
$('#edit-{$i}-button').click(function()     { $('#edit-{$i}-form').submit(); });
$('#link-{$i}-button').click(function()     { $('#link-{$i}-form').submit(); });



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
    $BODY.= <<<eol
<tr class="ui-state-default">
<td align="left"> {$r['title']} </td> 
<td> 
<input type="text" id="slct-{$i}-input" value="{$host}page?view={$r['ID']}" readonly> 
</td>
 <td>
 <!-- ERASE PAGE -->
 
        <div id="dialog-{$i}-box-attr-pages" calss="dialog" title="{$language['lw.options']}:">
            <table border="0" width="100%">
            <tr>
                <td width="40%">
                <form id="attr-{$i}-form-js" method="post" action="#{$sector_addt_page_js}">
                <input type="hidden" name="addt-this-page-js" value="{$r['ID']}">
               <center> <input type="submit" name="addt-this-page-js-button" id="button" value="JavaScript" /></center>
                </form>
                </td>

                <td width="40%">
                <form id="attr-{$i}-form-php" method="post" action="#{$sector_addt_page_php}">
                <input type="hidden" name="addt-this-page-php" value="{$r['ID']}">
                <center> <input type="submit" name="addt-this-page-php-button" id="button" value="P.H.P." /></center> 
                </form>
                </td>
            <tr>
            </table>
        </div>
        
        <div id="dialog-{$i}-box-pages" calss="dialog" title="{$language['main.cp.question.erase']}:">
            <p>{$language['lw.page']} <b>{$r['title']}</b></p>
        </div>    
<form id="erase-{$i}-form-pages" method="post" action="#{$sector}" target="_self">
    <input type="hidden" name="erase-this-page" value="{$r['ID']}"  readonly>
</form>     
 <!-- ERASE PAGE -->        

<ul id="icons">

        <li class="ui-state-default ui-corner-all" id="erase-{$i}-button-page" title="{$language['lw.erase']}">
        <span  class="ui-icon ui-icon-trash"></span> 
</li>

<form id="edit-{$i}-form" method="post" action="#{$sector_edit_page}" target="_self">
    <input type="hidden" name="edit-this-page" value="{$r['ID']}"  readonly>
        <li class="ui-state-default ui-corner-all" id="edit-{$i}-button" title="{$language['lw.edit']}">
        <span  class="ui-icon ui-icon-pencil"></span> 
        </li>
</form> 

  
<form id="link-{$i}-form" method="post" action="#links" target="_self">
    <input type="hidden" name="url_new-links" value="/page?view={$r['ID']}"  readonly>
    <input type="hidden" name="title_new-links" value="{$r['title']}"  readonly>
    <input type="hidden" name="position_new-links" value="last"  readonly>
    <input type="hidden" name="target_new-links" value="_self"  readonly>
        <li class="ui-state-default ui-corner-all" id="link-{$i}-button" title="{$language['lw.link']}">
        <span  class="ui-icon ui-icon-link"></span> 
        </li>
</form> 
  

    <li class="ui-state-default ui-corner-all" id="attr-{$i}-button-page" title="{$language['lw.options']}">
    <span class="ui-icon ui-icon-script"></span>
    </li>
    <li class="ui-state-default ui-corner-all" id="mdat-{$i}-button-page" title="{$language['lw.options']}">
    <span class="ui-icon ui-icon-signal-diag"></span>
    </li>
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
$operations = '<ul id="icons">';
$d = array("OK" => false, "title" => "upload", "body" => $this->upload_operation($sector), "w" => "550");
$operations.= $this->operation_buttons($sector_insert_page, $sector_insert_page, $sector_insert_page, "upload", "arrowthickstop-1-n", "upload", $d);
$operations.= '</ul>';
$textarea = "";
$textarea = theme::textarea('body', null, null, 25, null, '80%', '340px');
$SUBBODY[$sector_insert_page] = <<<html

<form id="insert" method="post" action="#{$sector}" target="_self">
<p/>
<p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<td width="10%">&nbsp;</td>
<td>{$operations}</td>
</tr>
<tr>
<th align="right" class="ui-state-active">{$language['lw.page']} <br/> {$language['lw.title']}</td>
<td class="ui-state-default"><input type="text" name="title"  value="" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
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
    $sql = mysql_query("SELECT * FROM  `" . PREFIX . "pages` WHERE `ID`='{$_POST['addt-this-page-js']}' LIMIT 1; ");
    $r = mysql_fetch_array($sql);
} else {
    $text = "{$language['lw.the']} {$language['lw.page']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_edit_menu);
}
$area_js = $this->script_editor('js', "{$sector_addt_page_js}-js", 'jscripts', $r['jscripts'], 'style="height: 450px;"');
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
$area_js = "";
$edit_info = '';
$autocomplete = "";
if ($_POST['addt-this-page-php']) {
    $sql = mysql_query("SELECT * FROM  `" . PREFIX . "pages` WHERE `ID`='{$_POST['addt-this-page-php']}' LIMIT 1; ");
    $r = mysql_fetch_array($sql);
} else {
    $text = "{$language['lw.the']} {$language['lw.page']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_edit_menu);
}
$area_php = $this->script_editor('php', "{$sector_addt_page_js}-php", 'phpscripts', $r['phpcripts'], 'style="height: 450px;"');
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
if ($_POST['edit-this-page']) {
    $sql = mysql_query("SELECT * FROM  `" . PREFIX . "pages` WHERE `ID`={$_POST['edit-this-page']} LIMIT 1; ");
    $r = mysql_fetch_array($sql);
} else {
    $text = "{$language['lw.the']} {$language['lw.page']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_edit_page);
}
$textarea = "";
$textarea = theme::textarea('body', $r['body'], null, 25, null, '80%', '325px');
$operations = '<ul id="icons">';
$d = array("OK" => false, "title" => "upload", "body" => $this->upload_operation($sector), "w" => "550");
$operations.= $this->operation_buttons("edit_page", $sector_edit_page, $sector_edit_page, "upload", "arrowthickstop-1-n", "upload", $d);
$operations.= '</ul>';
$SUBBODY[$sector_edit_page] = <<<html

<form id="edit_form" method="post" action="#{$sector}" target="_self">
<input type="hidden" name="id" value="{$r['ID']}"  readonly>
<p align="center">
<b>{$edit_info}</b>
</p>
<p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<td width="10%">&nbsp;</td>
<td> {$operations}</td>
</tr>
<tr>
<th align="right" class="ui-state-active"> {$language['lw.page']} <br/> {$language['lw.title']}</td>
<td class="ui-state-default"><input type="text" name="title"  value="{$r['title']}" /></td>
</tr>
<tr>
<td height="10px"></td>
<td></td>
</tr>
<tr>
<th align="right" class="ui-state-active"> {$language['lw.page']} <br/> {$language['lw.content']}</td>
<td class="ui-state-default"> <center>{$textarea}</center> </td>
</tr>
  <tr>
        <td colspan="2" align="center">
        <input type="submit" name="update_{$sector}" id="button" value="{$language['lan.submit']}" />
        </td> 
        
    </tr>

</table>
</form>
html;
////////////////////////////////////////////////////// EDIT PAGE
$js.= <<<js
});
js;
$this->html_include($js, 'jscript');
