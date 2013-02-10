<?PHP
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: theme.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
global $CONFIGURATION, $language, $SQL_WEBSITE;
if (!empty($_POST[$sector])) {
    $slq = mysql_query("UPDATE  `" . PREFIX . "preferences` SET `theme`='{$_POST[$sector]}'

            WHERE `ID`='1'; ") or ($mysql_error = mysql_error());
}
$BODY = "";
$dir = ROOT . $CONFIGURATION['themes'];
if (is_dir($dir)) {
    $BODY.= <<<eol
 <p>   {$mysql_error}
<table width="75%" border="0" cellpadding="2" cellspacing="0" align="center">
eol;
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file > "..") {
                $title = trim(str_replace($dir, "", $file));
				
                if (file_exists($dir . $file . "/prev.png")) {
					$preview = HOST . $CONFIGURATION['themes'] . $file . "/prev.png";
                } else {
                    $preview = HOST . "design/cpanel/img/theme.png";
                }
                if ($SQL_WEBSITE['theme'] == ($title)) {
                    $opt = '<li class="ui-state-active ui-corner-all" title="' . $language['lw.active'] . " " . $language['lw.theme'] . '">
                        <span class="ui-icon ui-icon-check" ></span></li>';
                } else {
                    $opt = $this->operation_buttons($title, $sector, $sector, $sector, 'circle-check', " {$language['lw.activate']} {$language['lw.the']} {$language['lw.theme']}");
                }
                $prop = $this->load_theme_prop($file);
                $BODY.= <<<eol
      <tr>
    <td>&nbsp;</td> <td colspan="2">&nbsp; </td>
  </tr>
  <tr height="20px">
    <td  rowspan="2" width="10%" > 
    <img src="{$preview}" border="0" width="140" height="160" alt="preview" align="absmiddle" 
    style="border:solid 5px white;box-shadow:0px 0px 10px #ccc; margin:15px;"> 
    
    </td>
    <td width="auto" class="ui-state-active" >  <b>{$title}</b>  </td>
    <td width="16px" ><ul id="icons"> {$opt}</ul></td>
  </tr>
  <tr height=""140px">
    <td colspan="2" >
    <table width="90%" border="0" align="right">
  <tr>
    <td width="20%" class="ui-state-default"> {$language['lw.theme']} {$language['lw.name']} </td>
    <td width="60%"> {$prop['theme.name']} </td>
  </tr>
  <tr>
    <td class="ui-state-default"> {$language['lw.author']}</td>
    <td> {$prop['theme.author']}</td>
  </tr>
    <tr>
    <td class="ui-state-default"> {$language['lw.version']}</td>
    <td>{$prop['theme.version']}</td>
  </tr> 

</table>
    
    </td>
  </tr>
eol;
                
            }
        }
    }
    $BODY.= <<<eol
 </p>   
</table>
eol;
    
} else {
    $BODY = " <p align=\"center\"> The script cannot locate theme directorie! </p>";
}
