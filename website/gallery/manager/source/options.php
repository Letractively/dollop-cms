<?php
    /**
    ============================================================
    * Last committed:     $Revision: 86 $
    * Last changed by:    $Author: fire $
    * Last changed date:    $Date: 2012-10-30 14:12:58 +0200 (Tue, 30 Oct 2012) $
    * ID:       $Id: options.php 86 2012-10-30 12:12:58Z fire $
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
    * ----------------------------------------------------------
    *       See COPYRIGHT and LICENSE
    * ----------------------------------------------------------
    */
    if (!defined('FIRE1_INIT')) {
        exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
    }
    /**
    *
    * @filesource
    * Options for news
    */
    global $language;
    $BODY = null;
    
    
    $sector_insert = 'insert_' . $sector;
    $sector_edit = 'edit_' . $sector;
    $this->show_sublink[$language['md.cat.insrt']] = $sector_insert;
    $my = new mysql_ai;
    //
    // Insert new row
    //print_r($_POST);
    if(isset($_POST["{$sector_insert}_submit"])){
        $_POST['name'] = str_replace(" ","_",preg_replace("[A-Za-z0-9_]","", $_POST['name']));
        $my->Insert($_POST,"gallery_category",array("{$sector_insert}_submit"));
    }
    //
    // Update row
    if(isset($_POST["{$sector_edit}_submit"])){
        unset($_POST["{$sector_edit}_submit"]);
        $_POST['name'] = str_replace(" ","_",preg_replace("[A-Za-z0-9_]","", $_POST['name']));
        $my->Update("gallery_category",$_POST,array("ID"=>$_POST['id']));
    }
    //
    // Delete Row
    if(isset($_POST['erase-category'])){
        $my->Delete("gallery_category", array("ID" => $_POST['erase-category']));
    }
    //
    // Crating UI table
    $htmltblall = new html_table(null, 'admin', 0, 0, 4);
    $my->Select("gallery_category");
    if(is_array($my->aArrayedResults)){
        //
        // Header of UI table
        $htmltblall->addRow();     $i=1;
        $htmltblall->addCell('№', null, 'header', array('width' => '5%'));
        $htmltblall->addCell($language['md.cat.name'], null, 'header', array('width' => '20%'));
        $htmltblall->addCell($language['md.cat.type'], null, 'header', array('width' => '20%'));
        $htmltblall->addCell($language['md.cat.actv'], null, 'header', array('width' => '20%'));
        $htmltblall->addCell($language['md.cat.optn'], null, 'header', array('width' => '20%'));
        foreach ($my->aArrayedResults as $row ){
            //
            // Looping rows
            $htmltblall->addRow();
            $htmltblall->addCell($i);
            $htmltblall->addCell($row['name']);
            $htmltblall->addCell($row['value_type']);
            $htmltblall->addCell( ((bool)$row['value_bool'])? $language['md.cat.yes'] :   $language['md.cat.no'] );
            $operations ='<ul id="icons">';
            $operations .= $this->operation_buttons($row['ID'],$i,$sector_edit,$sector_edit,' ui-icon-pencil'," {$language['lw.edit']} ");
            $operations .= $this->operation_buttons($row['ID'], $sector, $sector, "erase-category", 'trash', " {$language['md.cat.erase']}", array("OK" => true, 'title' => " {$language['md.cat.erase']}:", 'body' => "{$language['md.cat.name']}: <b>{$row['name']}</b>"));
            $htmltblall->addCell("{$operations}</ul>");
            $i++;
        }
        if($i > 1){
            $BODY = $htmltblall->display();
        }else{
            $BODY = $language['md.empt.cat'];    
        }
        
    }else{
        $BODY = $language['md.empt.cat'];
    }
    if (isset($_POST[$sector_edit])) {
        $my->aArrayedResults = "";
        $my->Select("gallery_category", array("ID" => $_POST[$sector_edit]));
        $title = $my->aArrayedResults[0]['name'];
        $type = $my->aArrayedResults[0]['value_type'];
        $text = $my->aArrayedResults[0]['value_text'];
    
$textarea = theme::textarea('value_text', $text, null, 25, null, '80%', '340px');
$SUBBODY[$sector_edit] = <<<eol
<br />
<form method="post" action="#{$sector}" target="_self">
  <input type="hidden" name="id" value="{$_POST[$sector_edit]}"  readonly>
  <table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
  <th>Title</th> <td><input type="text" value="{$title}" name="name"></td>
  <td width="30%">
    <font style="font-family: DinBoldWebfont, 'Arial Black', Gadget, sans-serif !important;text-transform: uppercase;font-size: 16px;font-weight: bold;">
    {$language['md.cat.type']}
    </font>
  <select name="value_type" style="width:50%">
    <option>$type</option>
    <option>60px</option>
    <option>80px</option>
    <option>100px</option>
    <option>120px</option>
    <option>140px</option>
    <option>150px</option>
    <option>160px</option>
    <option>170px</option>
    <option>200px</option>
    <option>220px</option>
    <option>240px</option>
    <option>260px</option>
    <option>280px</option>
    <option>300px</option>
  </select>
  </td>
  </tr> 
<tr>
  <td colspan="3" align="center">
  <b>{$language['md.iss.qestion']}</b> <br /> 
 {$textarea}
  </td>
</tr>
  <tr>
        <td colspan="3" align="center">
        <input type="submit" name="{$sector_edit}_submit" id="button" value="{$language['lan.submit']}" />
        </td> 
  </tr>
 
  
  </table> 
  </form>
eol;
    }
$textarea = theme::textarea('value_text', null, null, 25, null, '80%', '240px');
$SUBBODY[$sector_insert] = <<<eol
<br />
<form method="post" action="#{$sector}" target="_self">
  <table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
  <th>{$language['md.cat.name']}</th> <td><input type="text" value="" name="name"></td>
  <td width="30%">
    <font style="font-family: DinBoldWebfont, 'Arial Black', Gadget, sans-serif !important;text-transform: uppercase;font-size: 16px;font-weight: bold;">
    {$language['md.cat.type']}
    </font>
  <select name="value_type" style="width:50%">
    <option></option>
    <option>60px</option>
    <option>80px</option>
    <option>100px</option>
    <option>120px</option>
    <option>140px</option>
    <option>150px</option>
    <option>160px</option>
    <option>170px</option>
    <option>200px</option>
    <option>220px</option>
    <option>240px</option>
    <option>260px</option>
    <option>280px</option>
    <option>300px</option>
    
  </select>
  </td>
  </tr> 
<tr>
  <td colspan="3" align="center">
  <font style="font-family: DinBoldWebfont, 'Arial Black', Gadget, sans-serif !important;
text-transform: uppercase;
font-size: 16px;font-weight: bold;">{$language['md.cat.descr']}</font> <br /> 
 {$textarea}
  </td>
</tr>
  <tr>
        <td colspan="3" align="center">
        <input type="submit" name="{$sector_insert}_submit" id="button" value="{$language['lan.submit']}" />
        </td> 
  </tr>
 
  
  </table> 
  </form>
eol;

