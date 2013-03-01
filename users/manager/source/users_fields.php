<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: users_fields.php 115 2013-02-08 16:27:29Z fire $
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





$process = "process_{$sector}";
$this->show_sublink[] = $process;
global $mydb, $language;
$userFieldsTable = str_replace(PREFIX, "", USERS_SQLTBL_FIELD);
$mydb = new mysql_ai;
$usrTbl = constant("USERS_SQLTBL_MAIN");
//
// Delete field

if ((bool) $_POST["{$sector}-erase"]) {
    $fld_name = $_POST["{$sector}-erase"];
    mysql_query("ALTER TABLE `{$usrTbl}` DROP `{$fld_name}`");
    //
    $mydb->Delete($userFieldsTable, array("fld_name" => $fld_name));
}


if ((bool) $_POST[$sector]) {

    $charst = kernel::base_tag("{sqlcharset}");
    $comment = addslashes($_POST['fld_descr']);
    if (empty($_POST['row_type']) || empty($_POST['fld_name'])) {
        $ERRR = $this->mysql_alert_box("Required field For MySQL query is empty", $sector);
    } else {
        if (is_numeric($_POST['id'])) {
            $case = " CHANGE `{$_POST['fld_name']}` ";
            $mydb->Update($userFieldsTable, $_POST, array("ID" => $_POST['id']), array("id", $sector));
        } else {
            $case = " ADD ";
            $mydb->Insert($_POST, $userFieldsTable, array(1 => $sector));
        }

        $mysq_edd = "ALTER TABLE  `{$usrTbl}` {$case} `{$_POST['fld_name']}` {$_POST['row_type']}
            NOT NULL COMMENT '{$comment}'";

        mysql_query($mysq_edd) or ($ERRR = $this->mysql_error_box(mysql_error(), $sector) );
    }
}


$tbl = new html_table(null, 'admin', 0, 0, 4);
// Table Header Information
$tbl->addRow();
$tbl->addCell("ID", null, 'header', array('width' => '5%'));
$tbl->addCell($language['users.flds.titl'], null, 'header', array('width' => '30%'));
$tbl->addCell($language['users.flds.name'], null, 'header', array('width' => '15%'));
$tbl->addCell($language['users.flds.fldty'], null, 'header', array('width' => '15%'));
$tbl->addCell($language['md.pll.opt'], null, 'header', array('width' => '10%'));

$mydb->Select($userFieldsTable);
if ((bool) $mydb->iRecords) {
    foreach ($mydb->aArrayedResults as $row) {
        $tbl->addRow();
        $tbl->addCell($row['ID']);
        $tbl->addCell($row['fld_title']);
        $tbl->addCell($row['fld_name']);
        $tbl->addCell($row['fld_type']);
        $operations = "";
        $operations = '<ul id="icons">';
        $operations .= $this->operation_buttons($row['ID'], $i, $process, $process, ' ui-icon-pencil', " {$language['lw.edit']} ");
        $operations .= $this->operation_buttons($row['fld_name'], $i, $sector, "{$sector}-erase", 'trash', " {$language['lw.erase']}", array(
            //
            // Option, ok, execute it
            "OK" => true,
            //
            // Title of window
            'title' => " {$language['main.cp.question.erase']}:",
            //
            // Body of window
            'body' => "{$language['users.flds.name']}: <b>{$row['fld_name']}</b>, <b>{$row['fld_type']}</b>"));

        $operations .='</ul>';
        $tbl->addCell($operations);
    }
    $operations = "";

    $BODY = "<p>$ERRR</p>" . $tbl->display();
} else {
    $BODY = "<p>&nbsp;</p><br /><p><center>No fields for users!</center></p>";
}



if (is_numeric($_POST[$process])) {
    $mydb->Select($userFieldsTable, array("ID" => $_POST[$process]));
    if ((bool) $mydb->iRecords) {
        $r = $mydb->aArrayedResults[0];
        $disable = 'readonly="readonly"';
    } else {
        $r = array();
        $disable = null;
    }
}
$ac = "style=\"background:rgba(180,255,150,0.5);\" title=\"{$language['lw.active']}\" ";
$SUBBODY[$process] = <<<feof
<br />
<p>&nbsp;</p>
<script>
$(document).ready(function(){
    $("#fld_name").keyup(function (e) { 
    if (this.value.match(/[^a-zA-Z0-9_]/g)) {
    this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
    }
    });
});

</script>
<form method="post" action="#{$sector}" target="_self">
<input type="hidden" name="id" value="{$r['ID']}">
  <table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">

    <tr>
      <th>{$language['users.flds.name']}</th>
      <th>{$language['users.flds.titl']}</th>
      <th>{$language['users.flds.fldty']}</th>
    </tr>
    <tr>
      <td><input type="text" maxlength="60"  value="{$r['fld_name']}" name="fld_name" id="fld_name" {$disable}  /></td>
      <td><input type="text" maxlength="120" value="{$r['fld_title']}" name="fld_title" /></td>
      <td><select name="fld_type">
        <option value="{$r['fld_type']}" selected="selected" $ac> {$r['fld_type']}</option>
        <option value="text">text</option>
        <option value="checkbox">checkbox</option>
        <option value="radio" >radio</option>
        <option value="hidden">hidden</option>
        <option value="image">image</option>
        <option value="select">select</option>
        <option value="textarea">textarea</option>
        <option value="select">select</option>
      </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th>{$language['users.flds.rowty']}</th>
      <th>{$language['users.flds.attre']}</th>
      <th>{$language['users.flds.order']} </th>
    </tr>
    <tr>
      <td>   
      <select name="row_type">
        <option value="{$r['row_type']}" selected="selected" $ac> {$r['row_type']}</option>
        <option value="BOOLEAN">BOOLEAN</option>
        <option value="INT(3)">INT(3)</option>
        <option value="INT(9)">INT(9)</option>
        <option value="INT(12)">INT(12)</option>
        <option value="VARCHAR(30)">VARCHAR(30)</option>
        <option value="VARCHAR(60)">VARCHAR(60)</option>
        <option value="VARCHAR(80)">VARCHAR(80)</option>
        <option value="VARCHAR(120)">VARCHAR(120)</option>
        <option value="VARCHAR(180)">VARCHAR(180)</option>
        <option value="VARCHAR(220)">VARCHAR(220)</option>
        <option value="TINYTEXT">TINYTEXT</option>
        <option value="TEXT">TEXT</option>
        <option value="MEDIUMTEXT">MEDIUMTEXT</option>
        <option value="DATE">DATE</option>
        </select>
        
        </td>
      <td><textarea name="fld_attr"  rows="1">{$r['fld_attr']}</textarea></td>
      <td> 
       <select name="fld_order">
        <option value="{$r['fld_order']}" selected="selected" $ac> {$r['fld_order']}</option>
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
        <option>6</option>
        <option>7</option>
        <option>8</option>
        <option>9</option>
        <option>10</option>
        <option>11</option>
        <option>12</option>
        <option>13</option>
        <option>14</option>
        <option>15</option>
        <option>16</option>
        <option>17</option>
        <option>18</option>
        <option>19</option>
        <option>20</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th>{$language['users.flds.fld_require']}</th>
      <th>{$language['users.flds.rscol']}</th>
      <th>{$language['users.flds.defva']}</th>
    </tr>
    <tr>
      <td><select name="fld_require">
        <option value="{$r['fld_require']}" selected="selected" $ac> {$r['fld_require']}</option>
        <option value="required"> {$language['users.flds.reqy']}</option>
        <option value="hidden"> {$language['users.flds.rehi']}</option>
        <option value="display"> {$language['users.flds.reqn']}</option>
      </select><br />
      <small>if you use "{$language['users.flds.reqy']}" option, will require user to fill value</small>
      </td>
      <td><input type="text" maxlength="120" value="{$r['fld_rowscols']}" name="fld_rowscols" /><br />
      <small>use separator "|" to separate like <b>rows|cols</b> (textarea only)</small>
      </td>
      <td rowspan="4" valign="top"> 
       <textarea name="fld_value"  rows="8">{$r['fld_value']}</textarea>
     <br /><small>For Radio and CheckBox new line is option</small>
        </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     
    </tr>
    <tr>
      <th>{$language['users.flds.desc']}</th>
      <td>&nbsp;</td>
      
    </tr>
    <tr>
      <td colspan="2">
      <textarea name="fld_descr" id="fld_descr" rows="2">{$r['fld_descr']}</textarea></td>
     
    </tr>
    <tr>
      <td colspan="3" align="center"><input type="submit" name="{$sector}" id="button" value="{$language['lan.submit']}" /></td>
    </tr>
  </table>

</form>
feof;







