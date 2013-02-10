<?php
/**
 ============================================================
 * Last committed:     $Revision: 86 $
 * Last changed by:    $Author: fire $
 * Last changed date:    $Date: 2012-10-30 14:12:58 +0200 (вторник, 30 Октомври 2012) $
 * ID:       $Id: categories.php 86 2012-10-30 12:12:58Z fire $
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
 * Options for issues
 */
$process = "process_{$sector}";
$this->show_sublink[] = $process;
$mydb = new mysql_ai;
if (isset($_POST['erase-category'])) {
    $mydb->Delete("issues_category", array("ID" => $_POST['erase-category']));
}
if (isset($_POST["{$process}_submit"])) {
    if (empty($_POST['id'])) {
        $mydb->Insert($_POST, "issues_category", array("{$process}_submit"));
    } else {
        $mydb->Update("issues_category", $_POST, array("ID" => $_POST['id']), array("{$process}_submit"));
    }
}
$mydb->Select("issues_category");
$row = "";
if (is_array($mydb->aArrayedResults)) {
    foreach ($mydb->aArrayedResults as $r) {
        $options = "";
        // options
        $options.= $this->operation_buttons($r['ID'], $process, $process, "edit", 'pencil', " {$language['md.edit.category']}");
        //erase user
        $options.= $this->operation_buttons($r['ID'], $sector, $sector, "erase-category", 'trash', " {$language['md.cat.erase']}", array("OK" => true, 'title' => " {$language['md.cat.erase']}:", 'body' => "{$language['md.cat.name']}: <b>{$r['title']}</b>"));
        $row.= <<<eol
   
<tr>
    <td width="5%">{$r['ID']} </td>
  <td >{$r['title']} </td>
  <td width="15%">{$r['color']} </td>
  
  <td width="20%"> <ul id="icons">{$options}</ul> </td>
<tr>
eol;
        
    }
}
$BODY = <<<eol
  
  <table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
  
  <tr>
  <th >ID </th>
  <th >{$language['md.cat.name']} </th>
  <th >color </th>
  
  <th width="10%">option </th>
  <tr>
  
  {$row}
  
  
  </table>
eol;
$text = "";
if (isset($_POST[$process])) {
    $mydb->aArrayedResults = "";
    $mydb->Select("issues_category", array("ID" => $_POST[$process]));
    $title = $mydb->aArrayedResults[0]['title'];
    $color = $mydb->aArrayedResults[0]['color'];
    $text = $mydb->aArrayedResults[0]['questions'];
} else {
    $title = "";
    $color = "#BCED91";
    $text = "";
}
$textarea = theme::textarea('questions', $text, null, 25, null, '80%', '340px');
$SUBBODY[$process] = <<<eol
   <form method="post" action="#{$sector}" target="_self">
  <input type="hidden" name="id" value="{$_POST[$process]}"  readonly>
  <table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
  


  <tr>
  <th>Title</th> <td><input type="text" value="{$title}" name="title"></td>
  <td width="30%">{$language['md.iss.color']}<input type="text" value="$color" name="color" style="width:50%"></td>
  </tr>
  
    
<tr>
  <td colspan="3" align="center">
  <b>{$language['md.iss.qestion']}</b> <br />
  
 {$textarea}
  </td>
 }
 
</tr>
  <tr>
        <td colspan="3" align="center">
        <input type="submit" name="{$process}_submit" id="button" value="{$language['lan.submit']}" />
        </td> 
  </tr>
 
  
  </table> 
  </form>
eol;
