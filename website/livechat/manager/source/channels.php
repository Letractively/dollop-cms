<?php
/**
 ============================================================
 * Last committed:     $Revision: 86 $
 * Last changed by:    $Author: fire $
 * Last changed date:    $Date: 2012-10-30 14:12:58 +0200 (вторник, 30 Октомври 2012) $
 * ID:       $Id: channels.php 86 2012-10-30 12:12:58Z fire $
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
/**
 *
 * @filesource
 * manage the channels
 */
global $language, $KERNEL_PROP_MAIN;
$sector_insert = 'insert_' . $sector;
$sector_edit = 'edit_' . $sector;
$this->show_sublink[] = $sector_insert;
// attach classes
$mysql = new mysql_ai();
if (isset($_POST[$sector_insert])) {
    if (!(bool)$mysql->Insert($_POST, 'chat_chanels', array($sector_insert))) {
        $mysql_error = mysql_error();
    }
}
if (isset($_POST["{$sector_edit}_button"])) {
    if (!(bool)$mysql->Update('chat_chanels', $_POST, array("ID" => $_POST['ID']), array("{$sector_edit}_button"))) {
    }
    $mysql_error = mysql_error();
}
global $arrUserData;
$arrUserData = kernel::etc($KERNEL_PROP_MAIN['kernel.users.depend.folder'], pathinfo($KERNEL_PROP_MAIN['kernel.users.depend.privilege'], PATHINFO_FILENAME));
function select_opt($level = null) {
    global $arrUserData;
    $option = null;
    if (!is_array($arrUserData['users.privilege'])) return false;
    foreach ($arrUserData['users.privilege'] as $key => $privilege) {
        if ($key == $level) {
            $slct = 'selected="selected"';
        } else {
            $slct = null;
        }
        $option.= <<<HTML
     <option value="{$key}" {$slct}>{$privilege}</option>   
HTML;
        
    }
    return $option;
}
$tbl = new html_table(null, 'admin', 0, 0, 4);
// Tabel
$tbl->addRow();
$tbl->addCell($language['lchat.channels'], null, 'header', array('width' => '30%'));
$tbl->addCell($language['lchat.ch.name'], null, 'header', array('width' => '35%'));
$tbl->addCell($language['lchat.ch.aval'], null, 'header', array('width' => '15%'));
$tbl->addCell("&nbsp;", null, 'header', array('width' => '5%'));
if ((bool)$mysql->Select("chat_chanels")) {
    foreach ($mysql->aArrayedResults as $row) {
        $intSlct = true;
        $tbl->addRow();
        $tbl->addCell($row['title']);
        $tbl->addCell($row['description']);
        $tbl->addCell("<center> {$arrUserData['users.privilege'][$row['available']]} </center>");
        $option = $this->operation_buttons($row['ID'], $sector, $sector_edit, "right", 'ui-icon ui-icon-pencil', " {$language['lw.edit']} {$language['lchat.channel']}");
        $tbl->addCell("<ul id=\"icons\">{$option}</ul>");
    }
    $mysql_error = mysql_error();
    $BODY = $tbl->display();
} else {
    $mysql_error = mysql_error();
    $BODY = "<p align='center'><b>{$language['lchat.ch.empty']}</b></p>";
}
$opt = select_opt();
$SUBBODY[$sector_insert] = <<<eol
<p align="center">

</p>
    <form method="post" action="#{$sector}">
        <table width="70%" border="0" align="center" cellpadding="8" cellspacing="0">
            <tr>
                <th> {$language['lchat.ch.name']} </th>
                <th> {$language['lchat.ch.desc']} </th>
                <th> {$language['lchat.ch.aval']} </th>
            </tr>
            <tr>
            
                <td> <input type="text" name="title" value="" />   </td>
                <td> <textarea name="description" style="width:80%" rows="2"> </textarea>  </td>
                <td> <select name="available" > {$opt} </select> </td>
                
            </tr>
            
            <tr>
            
                <td colspan="3" align="center"> <input type="submit" name="{$sector_insert}" id="button" value="{$language['lan.submit']}" />  </td>
                
            </tr>
            
    </table>
    </form>
    
eol;
if (isset($_POST[$sector_edit])) {
    $row = "";
    if ((bool)$mysql->Select("chat_chanels", array("ID" => $_POST[$sector_edit]))) {
        $row = $mysql->aArrayedResults[0];
        $opt = select_opt($row['available']);
        $SUBBODY[$sector_edit] = <<<eol
<p align="center">
&nbsp; 
</p>
    <form method="post" action="#{$sector}">
    <input type="hidden" name="ID" value="{$row['ID']}"  readonly>
        <table width="70%" border="0" align="center" cellpadding="8" cellspacing="0">
            <tr>
                <th> {$language['lchat.ch.name']} </th>
                <th> {$language['lchat.ch.desc']} </th>
                <th> {$language['lchat.ch.aval']} </th>
            </tr>
            <tr>
            
                <td> <input type="text" name="title" value="{$row['title']}" />   </td>
                <td> <textarea name="description" style="width:80%" rows="2"> {$row['description']} </textarea>  </td>
                <td> <select name="available" > {$opt} </select> </td>
                
            </tr>
            
            <tr>
            
                <td colspan="3" align="center"> <input type="submit" name="{$sector_edit}_button" id="button" value="{$language['lan.submit']}" />  </td>
                
            </tr>
            
    </table>
    </form>
    
eol;
        
    } else {
    }
}
