<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: links.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
//create select array options from sql
function mysql_position_option($sql) {
    $d = 0;
    $p = mysql_numrows($sql);
    $d = 1;
    for ($c = 1;$c <= $p;$c++) {
        $select_options[$d] = <<<slct
        <option value="{$d}"  >{$d}</option>
slct;
        $d++;
    }
    return $select_options;
}
// Convert position arr to options for links
function conver_position_arr($position, $arr) {
    $select_gp = $arr;
    $select_gp[$position] = str_replace(' >', ' selected="selected">', $select_gp[$position]);
    foreach ($select_gp as $option) {
        $options.= $option;
    }
    return $options;
}
// NEW LINK
if ((bool)$_POST["title_new-{$sector}"] && (bool)$_POST["url_new-{$sector}"]) {
    $_POST["title_new-{$sector}"] = (htmlspecialchars(trim($_POST["title_new-{$sector}"])));
    $_POST = array_map('addslashes', $_POST);
    if ($_POST['position_new-' . $sector] == "last") {
        $position_sql = "  `position`   +1  ";
    } else {
        $position_sql = " '{$_POST['position_new-' . $sector]}' ";
    }
    $slq = mysql_query("INSERT INTO `" . PREFIX . "links`(  `title` , `url`, `position`, `target` ) 

            VALUES ( '{$_POST['title_new-' . $sector]}', '{$_POST['url_new-' . $sector]}', {$position_sql}, '{$_POST['target_new-' . $sector]}' ); ") or ($mysql_error = mysql_error());
}
//  DELETE LINK
if ($_POST['erase-this-link']) {
    $slq = mysql_query("DELETE FROM `" . PREFIX . "links` WHERE `" . PREFIX . "links`.`ID`='{$_POST['erase-this-link']}' ") or ($mysql_error = mysql_error());
}
//  INSERT SUB-LINK
if ($_POST["gr-{$sector}"]) {
    $_POST = stripslashes_deep($_POST);
    $_POST['title'] = (htmlspecialchars(trim($_POST['title'])));
    $_POST = array_map('addslashes', $_POST);
    $GR = $_POST["gr-{$sector}"];
    $slq = mysql_query("INSERT INTO `" . PREFIX . "links`

            ( `GR`, `title` , `url`, `position`, `target` ) 
            VALUES ('{$GR}', '{$_POST['title']}', '{$_POST['url']}', '{$_POST['position']}', '{$_POST['target']}' ); ") or ($mysql_error = mysql_error());
}
if ((bool)$_POST["submit-{$sector}"] && (bool)$_POST['ID']) {
    //  UPDATE LINKS
    foreach ($_POST['ID'] as $id) {
        $slq = mysql_query("UPDATE  `" . PREFIX . "links` 

                SET 

                `title`     ='{$_POST['title'][$id]}',
                `url`       ='{$_POST['url'][$id]}',
                `position`  ='{$_POST['position'][$id]}',
                `target`    ='{$_POST['target'][$id]}'

                WHERE `ID`='{$id}'; ") or ($mysql_error = mysql_error());
        $a++;
    }
}
$BODY = <<<eol
<form id="links-update" method="post" action="#{$sector}">
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
eol;
$sql = mysql_query("SELECT * FROM `" . PREFIX . "links` WHERE `GR`=0 ORDER BY `position` ASC ");
$BODY.= <<<eol
<tr>
<th width="16px" align="center"> 
<ul id="icons">
<li id="new-{$sector}-button" class="ui-state-default ui-corner-all" title="{$language['lw.new']} {$language['lw.link']}">
<span class="ui-icon ui-icon-circle-plus"></span></li>
</ul></th>
<th width="auto">   {$language['lw.titles']}      </th>
<th width="40%">    {$language['lw.addresses']}    </th>
<th width="60px">   {$language['lw.positions']}   </th>
 <th width="40px">  {$language['lw.targets']}     </th>
 <th width="50px">  </th>
</tr>
eol;
$i = 0;
$js = '$(document).ready(function() {';
$select_options = mysql_position_option($sql);
$form_out = "";
$form_out_sub = "";
while ($r = mysql_fetch_array($sql)) {
    //START SUBLINKS
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// SUBLINKS
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $sqlsub = mysql_query("SELECT * FROM `" . PREFIX . "links` WHERE `GR`='{$r['ID']}' ORDER BY `position` ASC ");
    $subinBODY = "";
    $sub_option_slct = mysql_position_option($sqlsub);
    while ($sl = mysql_fetch_array($sqlsub)) {
        $i = $sl['ID'];
        $subln_options = conver_position_arr($sl['position'], $sub_option_slct);
        $js.= <<<js

            
                $('#erasedl-{$i}-box-links').dialog({
                    autoOpen: false,
                    width: 400,
                    buttons: {
                        "{$language['lw.ok']}": function() { 
                            $('#erase-{$i}-form-links').submit(); 
                        }, 
                        "{$language['lw.close']}": function() { 
                            $(this).dialog("close"); 
                        } 
                    }
                });
         
$('#erase-{$i}-button-links').click(function(){ $('#erasedl-{$i}-box-links').dialog('open');return false;});

js;
        $form_out_sub.= <<<htmlform
        
<form id="erase-{$i}-form-links" method="post" action="#{$sector}">
    <input type="hidden" name="erase-this-link" value="{$sl['ID']}"  readonly>
</form>  

htmlform;
        $divmark = '<span class="markcolor"></span>';
        $subinBODY.= <<<eol
<tr class="ui-state-default">
<td align="right"> {$divmark} <span  class="ui-icon ui-icon-plus"></span></td>
<td><input type="text" name="title[{$i}]" value="{$sl['title']}"> </td> 
<td> <input type="text" name="url[{$i}]" value="{$sl['url']}"> </td>
<td>
<select name="position[{$i}]">
{$subln_options}
</select>
  </td>
 <td> <input type="text" name="target[{$i}]" value="{$sl['target']}"> </td>
 <td> 

<!-- edit form -->
 <input type="hidden" name="ID[{$i}]" value="{$sl['ID']}">
 
<!-- erase option -->
  <ul id="icons" >
        <li class="ui-state-default ui-corner-all" id="erase-{$i}-button-links" title="{$language['lw.erase']}">
        <span  class="ui-icon ui-icon-trash"></span> 
        </li>
  
       <div class="dialog" id="erasedl-{$i}-box-links" title="{$language['main.cp.question.erase']}">
            <p>{$language['lw.link']}: <b>{$sl['title']}</b></p>
     </div>   
     

     
</ul>

</td>
</tr>
eol;
        
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// SUBLINKS
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // END SUBLINKS
    $options = "";
    $i = "";
    $i = $r['ID'];
    // Erase link js+html form
    $js.= <<<js

                $('#sublink-{$i}-box-links').dialog({
                    autoOpen: false,
                    width: 900,
                    buttons: {
                        "{$language['lw.ok']}": function() { 
                            $('#addsub-{$i}-form-links').submit(); 
                        }, 
                        "{$language['lw.close']}": function() { 
                            $(this).dialog("close"); 
                        } 
                    }
                });
                
                $('#erasedl-{$i}-box-links').dialog({
                    autoOpen: false,
                    width: 400,
                    buttons: {
                        "{$language['lw.ok']}": function() { 
                            $('#erase-{$i}-form-links').submit(); 
                        }, 
                        "{$language['lw.close']}": function() { 
                            $(this).dialog("close"); 
                        } 
                    }
                });
         
$('#erase-{$i}-button-links').click(function(){ $('#erasedl-{$i}-box-links').dialog('open');return false;});
$('#addsub-{$i}-button-links').click(function(){ $('#sublink-{$i}-box-links').dialog('open');return false;});

js;
    if (empty($subln_options)) {
        $subln_options = '<option value="1" selected="selected">1</option>';
    }
    $form_out.= <<<htmlform
<div class="dialog" id="sublink-{$i}-box-links" title="{$language['lw.create']} {$language['lw.sub']}{$language['lw.link']}">
            <p> 
            <b>"{$r['title']}" {$language['lw.sub']}{$language['lw.link']}:</b> <br />
<form id="addsub-{$i}-form-links" method="post" action="#{$sector}">            
            <table align="center" widht="90%" border="0">
             <tr>
             <th>{$language['lw.title']}</th>
             <th>{$language['lw.address']}</th>
             <th>{$language['lw.position']}</th>
             <th>{$language['lw.target']}</th>
             </tr>
            <tr>
                <td width="25%"> <input type="text" name="title" value=""> </td>
                <td width="35%"><input type="text" name="url" value=""> </td>
                <td width="15%"><select name="position">
                    {$subln_options}
                    </select> </td>
                <td width="15%"><input type="text" name="target" value=""> </td>
            </tr>
            </table>
            <input type="hidden" name="gr-{$sector}" value="{$r['ID']}" readonly> 

</form>
            </p>
        </div>     
      
        
     <div class="dialog" id="erasedl-{$i}-box-links" title="{$language['main.cp.question.erase']}">
            <p>{$language['lw.link']}: <b>{$r['title']}</b></p>
     </div>       
           
           <form id="erase-{$i}-form-links" method="post" action="#{$sector}">
    <input type="hidden" name="erase-this-link" value="{$r['ID']}"  readonly>
    </form>  

htmlform;
    $options = conver_position_arr($r['position'], $select_options);
    $last_position = $r['position'];
    $BODY.= <<<eol
<tr class="ui-state-active">
<td align="center"> $divmark </td>
<td><input type="text" name="title[{$i}]" value="{$r['title']}"> </td> 
<td> <input type="text" name="url[{$i}]" value="{$r['url']}"> </td>
<td>
<select name="position[{$i}]">
{$options}
</select>
  </td>
 <td > <input type="text" name="target[{$i}]" value="{$r['target']}"> </td>
 <td width="65px">
        
<!-- erase option -->
  <ul id="icons" >
        <li class="ui-state-default ui-corner-all" id="erase-{$i}-button-links" title="{$language['lw.erase']}">
        <span  class="ui-icon ui-icon-trash"></span> 
        </li>
        
        <li class="ui-state-default ui-corner-all" id="addsub-{$i}-button-links" title="{$language['lw.create']} {$language['lw.sub']}{$language['lw.link']} ">
        <span class="ui-icon ui-icon-plusthick"></span>
        </li>
        
  <!-- edit form -->
 <input type="hidden" name="ID[{$i}]" value="{$r['ID']}">
</ul>

</td>
</tr>
                {$subinBODY}

eol;
    $subinBODY = "";
    $divmark = "";
}
$new_position = (int)$last_position + 1;
// new link and end
$BODY.= <<<eol
<tr>
<td colspan="6" align="center"><input type="submit" id="button" value="{$language['lw.save']}" name="submit-{$sector}"> </td>
</tr>
    </table>
</form>

<!-- // NEW LINK // -->
<div class="dialog" id="new-{$sector}-dialog" title="{$language['lw.new']} {$language['lw.link']}">
<form name="new-link" id="new-{$sector}-dialog-form" method="post" target="_self" >
<table align="center" widht="99%" border="0">

             <tr>
             <th>{$language['lw.title']}</th>
             <th>{$language['lw.address']}</th>
             <th>{$language['lw.position']}</th>
             <th>{$language['lw.target']}</th>
             </tr>
             
<tr>
<td><input type="text" name="title_new-{$sector}" value=""> </td> 
<td> <input type="text" name="url_new-{$sector}" value=""> </td>
<td>
<select name="position_new-{$sector}">
{$options}
<option value="{$new_position}"  selected="selected">{$new_position}</option>
</select>
  </td>
 <td > <input type="text" name="target_new-{$sector}" value="_self"> </td>
 <td width="65px">
   <!--   <input type="submit" id="button" value="{$language['lw.new']}" name="submit-{$sector}-new">  -->
</td>
</tr>
</table>
</form>
</div>


 {$form_out_sub}
  {$form_out}
  
  
eol;
$js.= <<<eol
                $('#new-{$sector}-dialog').dialog({
                    autoOpen: false,
                    width: 800,
                    buttons: {
                       "{$language['lw.ok']}": function() { 
                            $('#new-{$sector}-dialog-form').submit(); 
                        },
                     
                        "{$language['lw.close']}": function() { 
                            $(this).dialog("close"); 
                        } 
                    }
                });
                $('#new-{$sector}-button').click(function(){ $('#new-{$sector}-dialog').dialog('open');return false;});

});
eol;
$this->html_include("{$js}", 'jscript');
