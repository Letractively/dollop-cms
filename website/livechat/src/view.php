<?php
/**
 ============================================================
 * Last committed:     $Revision: 86 $
 * Last changed by:    $Author: fire $
 * Last changed date:    $Date: 2012-10-30 14:12:58 +0200 (вторник, 30 Октомври 2012) $
 * ID:       $Id: view.php 86 2012-10-30 12:12:58Z fire $
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
 * put your comment there...
 */
global $language;
// page title
// mysql class
$mysql = new mysql_ai();
// html table
$tbl = new html_table(null, 'chat', 0, 0, 3);
if (!defined("USER_PRIV")) {
    define("USER_PRIV", "0");
}
$tbl->addRow();
$tbl->addCell($language['lchat.channels'], null, 'header', array('width' => '30%'));
$tbl->addCell($language['lchat.ch.desc'], null, 'header', array('width' => '35%'));
$tbl->addCell($language['lchat.date'], null, 'header', array('width' => '15%'));
if ($query = mysql_query("SELECT * FROM `" . PREFIX . "chat_chanels` WHERE `available`<='" . USER_PRIV . "' ;") or die(mysql_error())) {
    while ($row = mysql_fetch_array($query)) {
        $tbl->addRow();
        $tbl->addCell("<a href='room?id={$row['ID']}' >{$row['title']}</a>");
        $tbl->addCell($row['description']);
        $tbl->addCell($language['lchat.date']);
    }
    $content = $tbl->display();
} else {
    $content = "<p align='center'><b>{$language['lchat.ch.empty']}</b></p>";
}
$title = $language['lchat.livechat'];
theme::content(array(ucfirst($title), $content));
