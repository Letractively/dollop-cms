<?php

/**
  ============================================================
 * Last committed:     $Revision: 127 $
 * Last changed by:    $Author: fire $
 * Last changed date:    $Date: 2013-03-27 09:19:53 +0200 (ñð, 27 ìàðò 2013) $
 * ID:       $Id: view.php 127 2013-03-27 07:19:53Z fire $
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
global $language, $dbli;
// page title
// mysql class
$mysql = new mysql_ai();
// html table
$tbl = new html_table(null, 'chat', 0, 0, 3, array("width" => "50%", "style" => "float:left;"));
if (!defined("USER_PRIV")) {
    define("USER_PRIV", "0");
}
$tbl->addRow();
$tbl->addCell($language['lchat.channels'], null, 'header', array('width' => '30%'));
$tbl->addCell($language['lchat.ch.desc'], null, 'header', array('width' => '35%'));
$tbl->addCell($language['lchat.date'], null, 'header', array('width' => '15%'));
if ($query = db_query("SELECT * FROM `" . PREFIX . "chat_chanels` WHERE `available`<='" . USER_PRIV . "' ;") or die(db_error())) {
    foreach (db_fetch($query, "assoc") as $row) {
        $tbl->addRow();
        $tbl->addCell("<a href='room?id={$row['ID']}' >{$row['title']}</a>");
        $tbl->addCell($row['description']);
        $tbl->addCell($language['lchat.date']);
    }
}
if (!empty($row)) {
    $content = $tbl->display();
} else {
    $content = "<p align='center'><b>{$language['lchat.ch.empty']}</b></p>";
}
$title = $language['lchat.livechat'];
if (defined("USER_ID")) {

    $tbl = new html_table(null, 'users', 0, 0, 3, array("width" => "50%", "style" => "float:right;"));
    $tbl->addRow();
    $tbl->addCell($language['lchat.with'], null, 'header', array('width' => '60%'));
    $tbl->addCell($language['lchat.ch.online'], null, 'header', array('width' => '35%'));
    $mysql->Select("users");
}




theme::content(array(ucfirst($title), $content));
