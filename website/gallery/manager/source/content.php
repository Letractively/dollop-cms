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

if (isset($_POST['save'])) {

    //
    
    // Loop all results

    foreach ($_POST['id_all'] as $id => $vb) {
        $title = null;
        $body = null;
        $bool = ((bool)$_POST['id_content'][$id]) ? "1" : "0";
        $title = addslashes($_POST['title'][$id]);
        $body = addslashes($_POST['body'][$id]);
        mysql_query("UPDATE " . PREFIX . "gallery_content SET `value_bool`='$bool',`title`='$title', `body`='$body' WHERE `ID`='$id' ") or die(mysql_error());
    }
}
$sector_edit = 'edit_' . $sector;
$my = new mysql_ai;

// Insert new row

if (isset($_POST["{$sector_insert}_submit"])) {
    $_POST['value_date'] = date('Y-m-d H:i:s', time());
    $my->Insert($_POST, "gallery_content", array(
        "{$sector_insert}_submit"
    ));
}

// Update row

if (isset($_POST["{$sector_edit}_submit"])) {
    unset($_POST["{$sector_edit}_submit"]);
    $my->Update("gallery_content", $_POST, array(
        "ID" => $_POST['id']
    ));
}

// Delete Row

if (isset($_POST['erase-content'])) {
    $my->Delete("gallery_content", array(
        "category" => $_POST['erase-content']
    ));
}

// Crating UI table
$htmltblall = new html_table(null, 'admin', 0, 0, 4);
$folder_module = kernel::base_tag("{publicfiles}{module_dir}");
$thums = kernel::base_tag("{thumbs}");
$my->Select("gallery_category");

if (is_array($my->aArrayedResults)) {

    // Header of UI table
    $htmltblall->addRow();
    $i = 1;
    $htmltblall->addCell('№', null, 'header', array(
        'width' => '5%'
    ));
    $htmltblall->addCell($language['md.cat.name'], null, 'header', array(
        'width' => '10%'
    ));
    $htmltblall->addCell($language['md.con.type'], null, 'header', array(
        'width' => '60%'
    ));
    $htmltblall->addCell($language['md.cat.optn'], null, 'header', array(
        'width' => '5%'
    ));
    foreach ($my->aArrayedResults as $row) {

        // Looping rows
        $htmltblall->addRow();
        $htmltblall->addCell($i);
        $htmltblall->addCell($row['name']);
        $folder_content = $folder_module . $row['name'] . "/" . $thums;
        $result = mysql_query("SELECT * FROM " . PREFIX . "gallery_content WHERE  `category`='{$row['name']}' ") or die(mysql_error());
        $data = '<div class="opener-preview">
            <form name="resurce-' . $i . '" method="post" action="#' . $sector . '"> ';
        while ($res = mysql_fetch_array($result)):
            $checked = ((bool)$res['value_bool']) ? "checked" : "";
            $data.= <<<eoll
            <ul id="icons" class="tab-image">
                <li class="ui-state-default">
                    <label class="resurce-content {$res['value_type']}">
                        <img src="{$folder_content}{$res['name']}" class="{$res['value_type']}" />
                        <input type="hidden" name="id_all[{$res['ID']}]" value="{$res['ID']}">
                        <input type="checkbox" name="id_content[{$res['ID']}]" value="1" $checked>
                    </label>
                </li>
                <li class="ui-state-default" >
                <span class="ui-icon ui-icon- ui-icon-pencil inform"></span>
                <div class="small-form">
                    {$language['md.insert.in']}
                    <input type="text" value="{$res['title']}" name="title[{$res['ID']}]" class="small-input">
                    <textarea name="body[{$res['ID']}]" class="small-input" rows="4">{$res['body']}</textarea>
                </div>
                </li>

            </ul>
 
eoll;
            
        endwhile;
        $data.= "<input type=\"submit\" value=\"{$language['lw.save']}\" name=\"save\" style=\"width:100%\"></form>";
        $htmltblall->addCell($data);
        $operations = '<ul id="icons">';
        $operations.= $this->operation_buttons($row['ID'], $i, $sector_edit, $sector_edit, ' ui-icon-arrowthickstop-1-n', " {$language['lw.upload']} ");
        $operations.= $this->operation_buttons($row['name'], $sector, $sector, "erase-content", 'trash', " {$language['md.erase.cnt']}", array(
            "OK" => true,
            'title' => " {$language['md.erase.cnt']}:",
            'body' => "{$language['md.erase.all']}: <b>{$row['name']}</b>"
        ));
        $htmltblall->addCell("{$operations}</ul>");
        $i++;
    }
    
    if ($i > 1) {
        $BODY = "<center><small>Click \"REFRESH\", if you make a change somewhere else!</small><br /><a href=\"" . request_uri() . "#$sector\" onClick=\"document.location.reload(true)\">refresh</a></center>" . $htmltblall->display();
    } else {
        $BODY = $language['md.empt.cat'];
    }
} else {
    $BODY = $language['md.empt.cat'];
}

if (isset($_POST[$sector_edit])) {
    $my->aArrayedResults = "";
    $my->Select("gallery_category", array(
        "ID" => $_POST[$sector_edit]
    ));
    global $CONFIGURATION;
    $timestamp = time();
    $token = md5(HEX . $CONFIGURATION['SPR'] . $timestamp . $CONFIGURATION['SPR'] . $CONFIGURATION['KEY'] . $CONFIGURATION['SPR'] . $timestamp);
    $uploadswf = kernel::base_tag("/{module_dir}uploadify.swf");
    $uploaderf = kernel::base_tag("{module_dir}upload");
    $textarea = theme::textarea('value_text', $text, null, 25, null, '80%', '300px');
    $nametitle = str_replace(" ", "_", preg_replace("[A-Za-z0-9_]", "", $my->aArrayedResults[0]['name']));
    $type = $my->aArrayedResults[0]['value_type'];
    $text = $my->aArrayedResults[0]['value_text'];
    $textarea = theme::textarea('value_text', $text, null, 25, null, '80%', '340px');
    $size = rtrim($type, 'px');
    $SUBBODY[$sector_edit] = <<<eol
<br />
<form method="post" action="#{$sector}" target="_self">
  <table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
  <td colspan="3" align="center">
  <font style="font-family: DinBoldWebfont, 'Arial Black', Gadget, sans-serif !important;text-transform: uppercase;font-size: 16px;font-weight: bold;">
  <div id="switchers" class="switchers"><a href="#$sector_edit">{$language['md.cat.upVid']} / {$language['md.cat.upImg']}</a></div>
<br />
  </font>
  <br /> 
  <div id="video" style="display:none;">
    <textarea name="value_text" style="height:300px;">  
    </textarea>
 </div>
 <div id="image">
         <form>
                <div id="queue"><br />
                <div id="showdata">Select images for gallery</div>
                <br />
                <input id="file_upload" name="file_upload" type="file" multiple="true">
        </form>
 <script type="text/javascript">
                $(function() {
                        $('#file_upload').uploadify({
                                'formData'     : {
                                        'timestamp' : '$timestamp',
                                        'token'     : '$token',
                                        'size'      : '$size',
                                        'cat'       : '$nametitle'
                                            
                                },
                                'swf'      : '{$uploadswf}',
                                'uploader' : '{$uploaderf}',
                                'buttonText': '{$language['md.cat.sltxt']}',
                                'displayData': 'percentage',
                                'onComplete': function(a, b, c, data, e){
                                    $("#showdata").text("log: " +data );
                                }
                        });
                });
        </script>
        
        <div id="showdata"></div>
        </div>
 </div>
  </td>
</tr>
  <tr>
        <td colspan="3" align="center">
        <input type="hidden" value="$nametitle" name="category" >
        <input type="hidden" value="video" name="value_type" >
        <input type="submit" name="{$sector_insert}_submit" id="button" value="{$language['lan.submit']}" class="video-button" />
        </td> 
  </tr>
 
  
  </table> 
  </form>
eol;
    
}

