<?php

/**
  ============================================================
 * Last committed:     $Revision: 133 $
 * Last changed by:    $Author: fire1 $
 * Last changed date:    $Date: 2013-04-02 20:13:15 +0300 (âò, 02 àïð 2013) $
 * ID:       $Id: view.php 133 2013-04-02 17:13:15Z fire1 $
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
 * News sorceode
 */
$content = null;
$proposed = null;
// Setup News template tags
// - Constants: "NEWS_SLTAG_TITLE","NEWS_SLTAG_CONTENT" & etc.
//      are defined in build.prop" file as "news.sltag.title","news.sltag.content" & etc.
// Creating News template
global $language, $MERGE_TEMPLATE, $theme;
$MERGE_TEMPLATE["NEWS"]['sector'][] = NEWS_SLTAG_TITLE;
$MERGE_TEMPLATE["NEWS"]['sector'][] = NEWS_SLTAG_CONTENT;
$MERGE_TEMPLATE["NEWS"]['sector'][] = NEWS_SLTAG_IMAGE;
$MERGE_TEMPLATE["NEWS"]['sector'][] = NEWS_SLTAG_DESCR;
$theme->template_setup("NEWS");
// check is requested category
if (is_numeric($_GET['cat'])) {
    $news_content = PREFIX . "news_content";
    $news_category = PREFIX . "news_category";
    $query = "SELECT

        `$news_content`.`ID` AS ID,
        `$news_content`.`title` AS title,
        `$news_content`.`body` AS body,
        `$news_content`.`image`,
        `$news_content`.`timestamp`,
        `$news_content`.`category`,
        `$news_category`.`title` AS cat_title,
        `$news_category`.`ID` AS cat_id

        FROM `$news_content` RIGHT  JOIN `$news_category` ON `$news_content`.`category`=`$news_category`.`title`
        WHERE  `$news_category`.`ID`='{$_GET['cat']}'
        ORDER BY `$news_content`.`timestamp` DESC

        LIMIT " . NEWS_LIMIT . "; ";
    // result of MySQL query
    $result = mysql_query($query) or die(mysql_error());
    // re convert array
    while ($r = mysql_fetch_array($result)) {
        $rs[] = $r;
    }
    //
    // Show button for rss link
    $proposed .='<a href="rss?id=' . $_GET['cat'] . '" class="rss"><span> </span>' . $language['ns.rssc'] . $rs['category'] . '</a>';
} else {
    //
    // Attaching mysql_ai class
    $mysql = new mysql_ai();
    //
    // Selecting mysql NEWS rows
    $mysql->Select("news_content", null, "`timestamp` DESC", NEWS_LIMIT);
    $proposed .='<a href="rss" class="rss"><span></span>' . $language['ns.rssa'] . '</a>';
}
//
// combine arrays
$comb_rows = (is_array($rs)) ? $rs : $mysql->aArrayedResults;
//
// Check is array sql result is empty
if (is_array($comb_rows)) {
    //
    // getting folders from base tag
    $thumbs = kernel::base_tag_folder("{thumbs}");
    $images = kernel::base_tag_folder("{images}");
    $publicfiles = kernel::base_tag_folder("{publicfiles}");
    //
    // Null/Set news content string
    $text = null;
    $i = 0;
    $news_text = null;
    foreach ($comb_rows as $row) {
        $i++;
        //
        // Null row tags string
        $row_tag = array();
        if (empty($row['image'])) {
            $image = " ";
        } else {
            $image = "

                <A href=\"/" . $publicfiles . MODULE_DIR . $images . $row['image'] . "\" >
                <img src=\"/" . $publicfiles . MODULE_DIR . $thumbs . $row['image'] . "\" alt=\"{$row['title']}\" title=\"{$row['title']}\"/>
                </A>
                ";
        }
        $description = null;
        $description.= date(NEWS_DATEFORMAT, $row['timestamp']) . " ";
        $description.= (@constant("NEWS_SHOWCHANGE")) ? "{$language['ns.lstc']} <b>{$row['lastchange']}</b> " : null;
        $description.= (@constant("NEWS_SHCATEGORY")) ? "{$language['ns.catg']} <b>{$row['category']}</b> " : null;
        $description.= (@constant("NEWS_SHKEYWORDS")) ? "{$language['ns.tags']} <b>{$row['keywords']}</b> " : null;
        $news_title = "<A href=\"select?n={$row['ID']}\" >{$row['title']}</A>";
        if ((bool) @constant("NEWS_FIRSTFULL") && $i == 1) {
            $news_title = $row['title'];
            $news_text = $row['body'];
        } elseIf ((bool) @constant("NEWS_CONTLIMIT")) {
            $read_more = "<A href=\"select?n={$row['ID']}\" class=\"button\" >{$language['ns.more']}</A>";
            $news_text = truncate_content(strip_tags($row['body']), @constant("NEWS_CONTLIMIT"), $read_more, true);
        } else {
            $news_text = $row['body'];
        }
        //
        // Creating tags
        $row_tag[NEWS_SLTAG_TITLE] = $news_title;
        $row_tag[NEWS_SLTAG_CONTENT] = $news_text;
        $row_tag[NEWS_SLTAG_IMAGE] = $image;
        $row_tag[NEWS_SLTAG_DESCR] = $description;
        // Attach tags to template NEWS
        $text.= theme::content($row_tag, "NEWS", true);
    }
    //
    // last check of text in news
    if ($i <= 0 OR empty($news_text)) {
        $text = $language['ns.empt'];
    }
    //
    // Social Networks
    $socnetwr = social_networks(1, 1, 1, 0, 0, 0, $proposed);
    $proposed = <<<eol
            <p>
                <div class="clearfix"></div>
                <div class="hr bottom" ></div>
                {$socnetwr}
            </p>

eol;

    //
    // Attach/Showing NEWS template in theme
    theme::content(array(ucfirst($language['ns.view']), $text . $proposed));
} else {
    // Showing empty message in theme
    theme::content(array(ucfirst($language['ns.view']), $language['ns.empt']));
}
