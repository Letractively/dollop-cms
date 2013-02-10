<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: options.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
if (!defined("CPANEL") || !defined("FIRE1_INIT")) {
    exit("error:1001");
}
global $language;
if ($_POST[$sector]) {
    $_POST = stripslashes_deep($_POST);
    $site_name = addslashes(htmlentities(trim($_POST['site_name'])));
    array_map('addslashes', $_POST);
    $slq = mysql_query("UPDATE  `" . PREFIX . "preferences` 

SET 

`host`              ='{$_POST['host']}',
`charset`           ='{$_POST['charset']}',
`site_keywords`     ='{$_POST['site_keywords']}',
`site_meta`         ='{$_POST['site_meta']}',
`start_page`        ='{$_POST['start_page']}',
`txt_area`          ='{$_POST['txt_area']}',
`lan`               ='{$_POST['lan']}'


WHERE `ID`=1;") or print (mysql_error());
}
$slq = mysql_query("SELECT * FROM `" . PREFIX . "preferences` WHERE `ID`=1;");
if ($r = mysql_fetch_array($slq)) {
    ///////////////   TEXT AREAS
    $dir_textarea = ROOT . kernel::CONFIGURATION('textarea');
    $folder_textarea = dir_read($dir_textarea);
    if ($folder_textarea) {
        $options_ta = "";
        $option = "";
        foreach ($folder_textarea as $option) {
            $slct = '';
            if ($option == $r['txt_area']) {
                $slct = 'selected="selected"';
            }
            $options_ta.= '<option value="' . $option . '" ' . $slct . '>' . $option . '</option>';
        }
    }
    ///////////////   LANGUAGE
    $dir_language = ROOT . kernel::CONFIGURATION('language');
    $folder_language = dir_read($dir_language);
    if ($folder_language) {
        $options_la = "";
        $option = "";
        foreach ($folder_language as $option) {
            $slct = '';
            if ($option == $r['lan']) {
                $slct = 'selected="selected"';
            }
            $options_la.= '<option value="' . $option . '" ' . $slct . '>' . $option . '</option>';
        }
    }
    ///////////////   HOST
    if (empty($r['host'])) {
        $fltr_host = array("http://");
        $host = explode("/", str_replace($fltr_host, "", HOST));
        $r['host'] = $host[0];
        $host_chng = ' id="change" ';
    }
    $BODY = <<<html

<form id="info" method="post" action="#{$sector}">

<p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
  <td width="15%" align="center">&nbsp;</td>
  <td width="20%" align="center">&nbsp;</td>
  <td width="30%" align="center">&nbsp;</td>
  <td width="15%" align="center">&nbsp;</td>
  
  </tr>
  <tr> <td ></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']} <br /> {$language['lw.host']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="host" {$host_chng} value="{$r['host']}" /></td>
 <td></td> </tr>
 
   <tr>
  <td colspan="4" align="center">  &nbsp; </td> 
 </tr>
 
  <tr><td></td>
    <th align="right" class="ui-state-active"> {$language['lw.start']} {$language['lw.date']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="datestart" value="{$r['datestart']}" disabled="disabled"  /></td>
 <td></td> </tr>
 
   <tr>
  <td colspan="4" align="center">  &nbsp; </td> 
 </tr>
 
  <tr><td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']}<br /> {$language['lw.charset']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="charset" value="{$r['charset']}" /></td>
 <td></td> </tr>
 
  <tr>
  <td colspan="4" align="center">  &nbsp; </td> 
 </tr>
 
  <tr><td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']}<br /> {$language['lw.keywords']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="site_keywords" value="{$r['site_keywords']}" /></td>
 <td></td> </tr>
 
   <tr>
  <td colspan="4" align="center">  &nbsp; </td> 
 </tr>
 
  <tr><td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']} <br /> {$language['lw.meta']}  {$language['lw.tags']}</th>
    <td align="left" class="ui-state-default">
    <textarea name="site_meta" cols="45" rows="2">{$r['site_meta']}</textarea></td>
<td></td>  </tr>

  <tr>
  <td colspan="4" align="center">  &nbsp; </td> 
 </tr>

  <tr><td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']} <br /> {$language['lw.startpage']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="start_page" value="{$r['start_page']}" /></td>
 <td></td> </tr>
 
   <tr>
  <td colspan="4" align="center">  &nbsp; </td> 
 </tr>
 
  <tr><td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']} <br />  {$language['lw.text']} {$language['lw.area']} </th>
    <td align="left" class="ui-state-default">
    <select name="txt_area" >
   
    {$options_ta}
    </select>
    
    </td>
<td></td>  </tr>

  <tr>
  <td colspan="4" align="center">  &nbsp; </td> 
 </tr>
 
  <tr><td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']} <br /> {$language['lw.language']} </th>
    <td align="left" class="ui-state-default">
    <select name="lan" >

    {$options_la}
    </select>
    </td>
 <td></td> </tr>
  
    <tr>
        <td colspan="4" align="center">
        <input type="submit" name="{$sector}" id="button" value="Submit" />
        </td> 
        
    </tr>
</table>
</form>
html;
    
}
