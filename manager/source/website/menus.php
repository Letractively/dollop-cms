<?php
/**
 ============================================================
 * Last committed:     $Revision: 117 $
 * Last changed by:    $Author: fire $
 * Last changed date:    $Date: 2013-02-13 13:35:16 +0200 (ñð, 13 ôåâð 2013) $
 * ID:       $Id: menus.php 117 2013-02-13 11:35:16Z fire $
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
//
// basic config
global $language, $theme;
$theme_file = kernel::current_page_theme();
$theme_sectors = theme::menu_discover(file_get_contents($theme_file));
$host = HOST;
$sector_insert_menu = 'insert_' . $sector;
$sector_edit_menu = 'edit_' . $sector;
$sector_addt_menu_php = 'additions_php_' . $sector;
$sector_addt_menu_js = 'additions_js_' . $sector;
$this->show_sublink[] = $sector_insert_menu;
if (@$_POST[$sector_insert_menu]) {
    $_POST = stripslashes_deep($_POST);
    $_POST['title'] = (htmlspecialchars(trim($_POST['title'])));
    $_POST = array_map('addslashes', $_POST);
    $slq = mysql_query("INSERT INTO `" . PREFIX . "menus`

 ( `title` , `body` , `section`  ) VALUES ('{$_POST['title']}', '{$_POST['body']}', '0'  ); ") or ($mysql_error = mysql_error());
}
if (@$_POST[$sector_edit_menu] && $_POST['id']) {
    $_POST = stripslashes_deep($_POST);
    $_POST['title'] = (htmlspecialchars(trim($_POST['title'])));
    $_POST = array_map('addslashes', $_POST);
    $slq = mysql_query("UPDATE  `" . PREFIX . "menus` 

SET 

`title` ='{$_POST['title']}',
`body`  ='{$_POST['body']}'

WHERE `ID`='{$_POST['id']}';") or ($mysql_error = mysql_error());
}
//////////////////////////////////////////// ERASE
if (@$_POST['erase-this-menu']) {
    $slq = mysql_query("DELETE FROM `" . PREFIX . "menus` WHERE `" . PREFIX . "menus`.`ID`='{$_POST['erase-this-menu']}' ") or ($mysql_error = mysql_error());
}
//update addt
if (@$_POST[$sector_addt_menu_js]) {
    $_POST = stripslashes_deep($_POST);
    $_POST = array_map('addslashes', $_POST);
    $slq = mysql_query("UPDATE  `" . PREFIX . "menus` SET `jscripts`='{$_POST['jscripts']}' WHERE `ID`='{$_POST['id']}'; ") or ($mysql_error = mysql_error());
}
if (@$_POST[$sector_addt_menu_php]) {
    $_POST = stripslashes_deep($_POST);
    $_POST = array_map('addslashes', $_POST);
    $slq = mysql_query("UPDATE  `" . PREFIX . "menus` SET `phpscript`='{$_POST['phpscripts']}' WHERE `ID`='{$_POST['id']}'; ") or ($mysql_error = mysql_error());
}
if ($_POST["submit-{$sector}"]) {
    foreach ($theme_sectors as $numsect) {
        if (!empty($_POST["sector-{$numsect}"])) {
            $i = 1;
            $arr = explode("menu[]=", $_POST["sector-{$numsect}"]);
            foreach ($arr as $key => $val) {
                $slq = mysql_query("UPDATE  `" . PREFIX . "menus` SET `section` ='{$numsect}',`position`  ='{$key}' 
        
        WHERE `ID`='{$val}'; ") or ($mysql_error = mysql_error());
                $i++;
            }
        }
        $arr = "";
        if (!empty($_POST['sector-0'])) {
            $arr = explode("menu[]=", $_POST["sector-0"]);
            foreach ($arr as $key => $val) {
                $slq = mysql_query("UPDATE  `" . PREFIX . "menus` SET `section` ='0',`position`  ='{$key}' 
        
        WHERE `ID`='{$val}'; ") or ($mysql_error = mysql_error());
                $i++;
            }
        }
    }
    global $dp;
    @mysql_close($dp);
    @mysql_close();
    exit($language['lw.saved']);
}
$js = '$(document).ready(function() {';
// disable menus that sector do not exist
$disabled_sectors = " OR ";
$allsect = count($theme_sectors);
$i = 1;
foreach ($theme_sectors as $disable_sector) {
    $disabled_sectors.= " `section`!='{$disable_sector}'  ";
    if ($allsect > $i) {
        $disabled_sectors.= ' AND ';
    }
    $i++;
}
$currentPage = str_replace(ROOT, "", $theme_file);
$BODY = <<<eol


{$mysql_error}
<b></b>
<table border="0" width="80%" align="center" cellpadding="8" cellspacing="0">

    <tr style="background-color:#fff">
        <td align="right">
       
        
      
        <b><span style="font-size:82%">{$language['lw.current']} {$language['lw.theme']}:</span></b> </td>
        <td align="left"><small>{$currentPage}</small>
        <button onClick="window.location.reload()" style="float:left;background:none;border:none;"> <span  class="ui-icon ui-icon-refresh"></span></button>
        
        </td>
        </tr>
        
<tr class="ui-state-active">   
     <th width="45%" >Deactive</th> <th >Active 
     <div class="success message-hidden ui-state-highlight" style="color:#888;width:80%; float:right;"></div> </th>
</tr>

  <tr >
    <td valign="top" align="center" >


    <ul class="sortable menu-0 menu-sector-disable" id="connectedSortable" style="text-align:left;">
eol;
$sql_unactive = mysql_query("SELECT `ID`,`title` FROM `" . PREFIX . "menus` WHERE `section`='0' {$disabled_sectors} ORDER BY `position` ASC; ") or ($mysql_error = mysql_error());
while ($r = mysql_fetch_array($sql_unactive)) {
    //////////////////////////////////////////////////////////////////// UN ACTIVE
    $BODY.= <<<eol
    <li class="ui-state-highlight" id="menu_{$r['ID']}" >  
        {$r['title']}
        <ul style="">   
        <li class="ui-state-default ui-corner-all" title="{$language['lw.erase']}" id="erase-{$r['ID']}-button-menu">
        <span  class="ui-icon ui-icon-trash"></span>
        </li> 
        <li class="ui-state-default ui-corner-all" title="{$language['lw.edit']}"  id="edit-{$r['ID']}-button-menu">
        <span class="ui-icon ui-icon-pencil"></span>
        </li>
        <li class="ui-state-default ui-corner-all" title="{$language['lw.additions']}"  id="addt-{$r['ID']}-button-menu">
        <span class="ui-icon ui-icon-script"></span>
        </li>
        </ul>
    
    </li>
    

eol;
    $form.= <<<form
        <div id="dialog-{$r['ID']}-box-menu" calss="dialog" title="{$language['main.cp.question.erase']}:">
            <p>{$language['lw.menu']} <b>{$r['title']}</b></p>
        </div>
        
        <div id="dialog-{$r['ID']}-box-attr-menu" calss="dialog" title="{$language['lw.options']}:">
            <table border="0" width="100%">
            <tr>
                <td width="40%">
                <form id="attr-{$r['ID']}-form-js-menu" method="post" action="#{$sector_addt_menu_js}">
                <input type="hidden" name="addt-this-menu-js" value="{$r['ID']}">
               <center> <input type="submit" name="addt-this-menu-js-button" id="button" value="JavaScript" /></center>
                </form>
                </td>

                <td width="40%">
                <form id="attr-{$r['ID']}-form-php-menu" method="post" action="#{$sector_addt_menu_php}">
                <input type="hidden" name="addt-this-menu-php" value="{$r['ID']}">
                <center> <input type="submit" name="addt-this-menu-php-button" id="button" value="P.H.P." /></center> 
                </form>
                </td>
            <tr>
            </table>
        </div>

<form name="menu-{$r['ID']}-erase" id="menu-{$r['ID']}-erase" action="#{$sector}" method="post">
<input type="hidden" name="erase-this-menu" value="{$r['ID']}"  readonly>
</form>
<form name="menu-{$r['ID']}-edit" id="menu-{$r['ID']}-edit" action="#{$sector_edit_menu}" method="post">
<input type="hidden" name="edit-this-menu" value="{$r['ID']}"  readonly>
</form>

form;
    $js.= <<<js
 

                $('#dialog-{$r['ID']}-box-menu').dialog({
                    autoOpen: false,
                    width: 400,
                    buttons: {
                        "{$language['lw.ok']}": function() { 
                                             $('#menu-{$r['ID']}-erase').submit(); 
                        }, 
                        "{$language['lw.close']}": function() { 
                            $(this).dialog("close"); 
                        } 
                    }
                });  
                
                
                $('#dialog-{$r['ID']}-box-attr-menu').dialog({
                    autoOpen: false,
                    width: 800,
                    buttons: {
                         
                        "{$language['lw.close']}": function() { 
                            $(this).dialog("close"); 
                        } 
                    }
                });
                
                        
$('#erase-{$r['ID']}-button-menu').click(function(){ $('#dialog-{$r['ID']}-box-menu').dialog('open');return false; });
$('#edit-{$r['ID']}-button-menu').click(function()     { $('#menu-{$r['ID']}-edit').submit(); });
$('#addt-{$r['ID']}-button-menu').click(function(){ $('#dialog-{$r['ID']}-box-attr-menu').dialog('open');return false; });  
js;
    
}
$BODY.= <<<eol
    </ul>
    </td>
    
    <td valign="top" align="left">
eol;
$i = 1;
$js_sectors = ".menu-0,";
foreach ($theme_sectors as $theme_sector) {
    $BODY.= <<<eol
    <div class="menu-sector"><div class="menu-sector-title ui-state-active"> {$language['lw.area']} {$theme_sector}</div>
    <ul class="sortable menu-{$theme_sector}" id="connectedSortable" style="padding-bottom:30px;"> 
eol;
    $js_sectors.= ".menu-{$theme_sector}";
    if ($allsect > $i) {
        $js_sectors.= ",";
    }
    $sql_active = mysql_query("SELECT `ID`,`title` FROM `" . PREFIX . "menus` WHERE `section`='{$theme_sector}' ORDER BY `position` ASC; ");
    while ($r = mysql_fetch_array($sql_active)) {
        $BODY.= <<<eol
    <li class="ui-state-default" id="menu_{$r['ID']}">
        {$r['title']}
        <ul>   
        <li class="ui-state-default ui-corner-all" title="{$language['lw.erase']}" id="erase-{$r['ID']}-button-menu">
        <span  class="ui-icon ui-icon-trash"></span>
        </li> 
        <li class="ui-state-default ui-corner-all" title="{$language['lw.edit']}"  id="edit-{$r['ID']}-button-menu">
        <span class="ui-icon ui-icon-pencil"></span>
        </li>
        
        <li class="ui-state-default ui-corner-all" title="{$language['lw.additions']}"  id="addt-{$r['ID']}-button-menu">
        <span class="ui-icon ui-icon-script"></span>
        </li>
        
        </ul>
    
    </li>
    
        <div id="dialog-{$r['ID']}-box-menu" calss="dialog" title="{$language['main.cp.question.erase']}:">
            <p>{$language['lw.menu']} <b>{$r['title']}</b></p>
        </div>
eol;
        $form.= <<<form
<form name="menu-{$r['ID']}-erase" id="menu-{$r['ID']}-erase" action="#{$sector}" method="post">
<input type="hidden" name="erase-this-menu" value="{$r['ID']}"  readonly>
</form>
<form name="menu-{$r['ID']}-edit" id="menu-{$r['ID']}-edit" action="#{$sector_edit_menu}" method="post">
<input type="hidden" name="edit-this-menu" value="{$r['ID']}"  readonly>
</form>


        <div id="dialog-{$r['ID']}-box-attr-menu" calss="dialog" title="{$language['lw.options']}:">
            <table border="0" width="100%">
            <tr>
                <td width="40%">
                <form id="attr-{$r['ID']}-form-js-menu" method="post" action="#{$sector_addt_menu_js}">
                <input type="hidden" name="addt-this-menu-js" value="{$r['ID']}">
               <center> <input type="submit" name="addt-this-menu-js-button" id="button" value="JavaScript" /></center>
                </form>
                </td>

                <td width="40%">
                <form id="attr-{$r['ID']}-form-php-menu" method="post" action="#{$sector_addt_menu_php}">
                <input type="hidden" name="addt-this-menu-php" value="{$r['ID']}">
                <center> <input type="submit" name="addt-this-menu-php-button" id="button" value="P.H.P." /></center> 
                </form>
                </td>
            <tr>
            </table>
        </div>
form;
        $js.= <<<js
                $('#dialog-{$r['ID']}-box-menu').dialog({
                    autoOpen: false,
                    width: 400,
                    buttons: {
                        "{$language['lw.ok']}": function() { 
                                             $('#menu-{$r['ID']}-erase').submit(); 
                        }, 
                        "{$language['lw.close']}": function() { 
                            $(this).dialog("close"); 
                        } 
                    }
                });  
                
                $('#dialog-{$r['ID']}-box-attr-menu').dialog({
                    autoOpen: false,
                    width: 800,
                    buttons: {
                         
                        "{$language['lw.close']}": function() { 
                            $(this).dialog("close"); 
                        } 
                    }
                });     

$('#addt-{$r['ID']}-button-menu').click(function(){ $('#dialog-{$r['ID']}-box-attr-menu').dialog('open');return false; });    
                        
$('#erase-{$r['ID']}-button-menu').click(function(){ $('#dialog-{$r['ID']}-box-menu').dialog('open');return false; });
$('#edit-{$r['ID']}-button-menu').click(function()     { $('#menu-{$r['ID']}-edit').submit(); });
$('#addt-{$r['ID']}-button-menu').click(function()     { $('#addt-{$r['ID']}-addt').submit(); });

js;
        
    }
    $BODY.= <<<eol
    </ul>
    </div>
eol;
    $i++;
}
$BODY.= <<<eol
        </td>
    
  </tr>
  
  <tr>
  <td colspan="2" align="center">
  &nbsp;   
  </td>
  </tr>
</table>
$uri

eol;
$i = 1;
foreach ($theme_sectors as $js_th_sector) {
    $jsss.= <<<js
'sector-{$js_th_sector}':$(".menu-{$js_th_sector}").sortable('serialize')
js;
    if ($allsect > $i) {
        $jsss.= ",";
    }
    $i++;
}
$js.= <<<js


   $("{$js_sectors}").sortable(
    {
        connectWith: '{$js_sectors}',
        update : function () 
        { 
            $.ajax(
            {
                type: "POST",
                url: "#{$sector}",
                data: 
                { 'submit-{$sector}':true,
                'sector-0':$(".menu-0").sortable('serialize'),
                          {$jsss}
                },
                success: function(html)
                { 
                    $('.success, .message-hidden').html(html);
                    $('.success, .message-hidden').fadeIn(400);
                    $('.success, .message-hidden').fadeOut(400);
                }
            });
        } 
    }).disableSelection();


js;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// SUB BODY INSERT MENU

/**
 *   INSERT MENU
 *               SECTOR OF MENU
 *
 */
$textarea = "";
$textarea = theme::textarea('body', null, null, 10, null, '80%');
$operations = '<ul id="icons">';
$d = array("OK" => false, "title" => "upload", "body" => $this->upload_operation($sector), "w" => "550");
$operations.= $this->operation_buttons($sector_insert_menu, $sector_insert_menu, $sector_edit_page, "upload", "arrowthickstop-1-n", "upload", $d);
$operations.= '</ul>';
$SUBBODY[$sector_insert_menu] = <<<html

<form id="{$sector_insert_menu}" name="{$sector_insert_menu}" method="post" action="#{$sector}">
<p/>
<p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<td width="10%">&nbsp;</td>
<td>{$operations}</td>
</tr>
<tr>
<th align="right" class="ui-state-active">{$language['lw.menu']} <br/> {$language['lw.title']}</td>
<td class="ui-state-default"><input type="text" name="title"  value="" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<th align="right" class="ui-state-active">{$language['lw.menu']} <br/> {$language['lw.content']}</td>
<td class="ui-state-default">  <center> {$textarea} </center></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
  <tr>
        <td colspan="2" align="center">
        <input type="submit" name="{$sector_insert_menu}" id="button" value="{$language['lan.submit']}" />
        </td> 
        
    </tr>

</table>
</form>
html;
////////////////////////////////////////////////////////////////////////////////////////////////Additional
//////////////// Jscript
$edit_info = "";
if ($_POST['addt-this-menu-js']) {
    $sql = mysql_query("SELECT ID,jscripts FROM  `" . PREFIX . "menus` WHERE `ID`='{$_POST['addt-this-menu-js']}' LIMIT 1; ");
    $r = mysql_fetch_array($sql);
}
if (!is_array($r)) {
    $text = "{$language['lw.the']} {$language['lw.menu']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_addt_menu_js);
}
$area_js = $this->script_editor('js', "{$sector_addt_menu_js}-js", 'jscripts', htmlspecialchars($r['jscripts']), 'style="height: 450px;"');
$autocomplete = $this->autocomplete_function_editor('js', "acm-{$sector_addt_menu_js}");
$SUBBODY[$sector_addt_menu_js] = <<<html
<form id="{$sector_addt_menu_js}" name="{$sector_addt_menu_js}" method="post" action="#{$sector}" target="_self">
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
<th  rowspan="2" class="ui-state-active"> JavaScript {$language['lw.menu']} <br/> {$language['lw.section']} </th>
<td>{$autocomplete}</td>
  </tr>
  <tr>
 <td height="420px">
    {$area_js}
 </td>
</tr>
<tr>
        <td colspan="2" align="center">
        <input type="submit" name="{$sector_addt_menu_js}" id="button" value="{$language['lan.submit']}" />
        </td> 
        
    </tr>
</table>

</form>
html;
//////////////// PHPscript
$area_js = "";
$edit_info = '';
$autocomplete = "";
if ($_POST['addt-this-menu-php']) {
    $sql = mysql_query("SELECT `ID`,`phpscript` FROM  `" . PREFIX . "menus` WHERE `ID`='{$_POST['addt-this-menu-php']}' LIMIT 1; ");
    $r = mysql_fetch_array($sql);
}
if (!is_array($r)) {
    $text = "{$language['lw.the']} {$language['lw.menu']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_addt_menu_php);
}
$area_php = $this->script_editor('php', "{$sector_addt_menu_php}-php", 'phpscripts', htmlspecialchars($r['phpscript']), 'style="height: 450px;"');
$autocomplete = $this->autocomplete_function_editor('php', "acm-{$sector_addt_menu_php}");
$SUBBODY[$sector_addt_menu_php] = <<<html
<form id="{$sector_addt_menu_php}" name="{$sector_addt_menu_php}" method="post" action="#{$sector}" target="_self">
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
<th  rowspan="2" class="ui-state-active"> PHP {$language['lw.menu']} <br/> {$language['lw.section']} </th>
<td>{$autocomplete}</td>
  </tr>
  <tr>
 <td height="420px">
    {$area_php}
 </td>
</tr>
<tr>

        <td colspan="2" align="center">
        <input type="submit" name="{$sector_addt_menu_php}" id="button" value="{$language['lan.submit']}" />
        </td> 
        
    </tr>
</table>

</form>
html;
////////////////////////////////////////////////EDIT MENU
$tex = "";
$edit_info = "";
if ($_POST['edit-this-menu']) {
    $sql = mysql_query("SELECT * FROM  `" . PREFIX . "menus` WHERE `ID`='{$_POST['edit-this-menu']}' LIMIT 1; ");
    $r = mysql_fetch_array($sql);
} else {
    $text = "{$language['lw.the']} {$language['lw.menu']} {$language['lw.not']} {$language['lw.selected']}.";
    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_edit_menu);
}
$operations = '<ul id="icons">';
$d = array("OK" => false, "title" => "upload", "body" => $this->upload_operation($sector), "w" => "550");
$operations.= $this->operation_buttons($sector_edit_menu, $sector_edit_menu, $sector_edit_menu, "upload", "arrowthickstop-1-n", "upload", $d);
$operations.= '</ul>';
$textarea = "";
$textarea = theme::textarea('body', $r['body'], null, 10, null, '80%');
$SUBBODY[$sector_edit_menu] = <<<html

<form id="{$sector_edit_menu}" name="{$sector_edit_menu}" method="post" action="#{$sector}">
<p/>
{$edit_info} 
<p>
<input type="hidden" name="id" value="{$r['ID']}"  readonly>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<td width="10%">&nbsp;</td>
<td>{$operations}</td>
</tr>
<tr>
<th align="right" class="ui-state-active">{$language['lw.menu']} <br/> {$language['lw.title']}</td>
<td class="ui-state-default"><input type="text" name="title"  value="{$r['title']}" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<th align="right" class="ui-state-active">{$language['lw.menu']} <br/> {$language['lw.content']}</td>
<td class="ui-state-default">  <center> {$textarea} </center></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>


  <tr>
        <td colspan="2" align="center">
        <input type="submit" name="{$sector_edit_menu}" id="button" value="{$language['lan.submit']}" />
        </td> 
        
    </tr>

</table>
</form>
html;
$BODY = $BODY . $form;
// insert Jscript to page
$js.= '});';
$this->html_include("{$js}", 'jscript');
