<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: rss.php 115 2013-02-08 16:27:29Z fire $
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
 * @filesource  Dollop News
 * @package dollop 
 * @subpackage Module
 * 
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

global $SQL_WEBSITE, $CONFIGURATION, $THEME;
$feed = new feed_rss();
$THEME = null;
header_remove("Content-Type");
header("Content-Type: text/xml; charset={$SQL_WEBSITE['charset']}");
$news_content = PREFIX . "news_content";
$news_category = PREFIX . "news_category";
if (is_numeric($_GET['id'])) {
    $WHERE = " WHERE  `$news_category`.`ID`='{$_GET['id']}' ";
} else {
    $WHERE = null;
}
$query = "SELECT 

        `$news_content`.`ID` AS news_id,
        `$news_content`.`title` AS news_title,
        `$news_content`.`body` AS news_text,
        `$news_content`.`image`,
        `$news_content`.`timestamp`,
        `$news_content`.`category`,
        `$news_category`.`title` AS cat_title,
        `$news_category`.`ID` AS cat_id

        FROM `$news_content` RIGHT  JOIN `$news_category` ON `$news_content`.`category`=`$news_category`.`title`

        {$WHERE}

        ORDER BY `$news_content`.`timestamp` DESC

        LIMIT 20; ";
$result = mysql_query($query) or die(mysql_error());
$publicfiles = kernel::base_tag_folder("{publicfiles}");
$design = kernel::base_tag_folder("{design}");
while ($r = mysql_fetch_array($result)) {
    $row[] = $r;
}
$feed->setTitle($SQL_WEBSITE['site_name'] . " " . $row[0]['cat_title']);
$feed->setLink(HOST . MODULE_DIR . "view?c={$row[0]['cat_id']}");
$feed->setDescription($news_text);
$feed->setImage($SQL_WEBSITE['site_name'] . " " . $row[0]['cat_title'], HOST, HOST . $design . "website/logo.png");
$feed->setChannelElement('language', $SQL_WEBSITE['lan']);
$feed->setChannelElement('generator', "Dollop {$CONFIGURATION['codname']}");
$feed->setChannelElement('pubDate', date(NEWS_DATEFORMAT, time() + $row[0]['timestamp']));

$r = null;
$thumbs = kernel::base_tag("{thumbs}");
$images = kernel::base_tag("{images}");
$news_title = null;
foreach ($row as $r) {
    // limiting content if is set
    if ((bool) @constant("NEWS_FIRSTFULL") && $i == 1) {
        $news_title = $row['news_title'];
        $news_text = $r['news_text'];
    } elseIf ((bool) @constant("NEWS_CONTLIMIT")) {
        $read_more = " ";
        $news_text = truncate($r['news_text'], @constant("NEWS_CONTLIMIT"), $suffix = $read_more, $isHTML = true);
    } else {
        $news_text = $r['news_text'];
    }
    $link = HOST . MODULE_DIR . "select?n={$r['news_id']}";
    if (empty($r['image'])) {
        $image = " ";
        $image_content = null;
    } else {
        $image = HOST . $publicfiles . MODULE_DIR . $thumbs . $r['image'];
        $image_content = '<img scr="' . HOST . $publicfiles . MODULE_DIR . $images . $r['image'] . '" alt="" /><br /> ';
    }
    $item = $feed->createNewItem();
    $item->setTitle($r['news_title']);
    $item->addElement('image', "<url>" . $image . "</url>\n" . "<title>" . $r['news_title'] . "</title>\n");
    $item->setLink($link);
    $item->setDate($r['timestamp']);
    $item->setDescription($image_content . $news_text);
    $item->addElement('author', $SQL_WEBSITE['site_name']);
    $feed->addItem($item);
}
$feed->genarateFeed();
exit();
