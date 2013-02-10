<?php
/**
 ============================================================
 * Last committed:     $Revision: 86 $
 * Last changed by:    $Author: fire $
 * Last changed date:    $Date: 2012-10-30 14:12:58 +0200 (вторник, 30 Октомври 2012) $
 * ID:       $Id: room.php 86 2012-10-30 12:12:58Z fire $
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
 * Live Chat Room
 *
 */
global $language;
$title = "{$language['lchat.chat']} {$language['lchat.ch.room']}";
if ($_GET['id'] && is_numeric($_GET['id'])) {
    $mysql = new mysql_ai();
    if ($mysql->Select("chat_chanels", array("ID" => $_GET['id']))) {
        $row = $mysql->aArrayedResults[0];
        $title.= "{$title}";
        $content = null;
        $content.= <<<eol
  <p align="center">
<a href="view">{$language['lchat.channels']}</a> {$language['lchat.ch.spr']} {$row['title']}
</p>
<form id="chat_content_form" action="process?m={$_GET['id']}" method="post"> 
    <div class="lc-messages">
    <div id="live_message"> </div>
    </div>
   
    <small>{$language['lchat.ch.message']}</small>: <br />
    <div class="chat_form">
    <textarea name="chat_message" rows="1" id="chat_message"></textarea> 
    <input type="submit" value="&#8629;"  id="chat_button" /> 
    </div>
</form>  
  
eol;
        
    } else {
        $content = " asd";
    }
} else {
    $content = $language['lchat.ch.errurl'];
}
theme::content(array(ucfirst($title), $content));
