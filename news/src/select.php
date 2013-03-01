<?php

/**
  ============================================================
 * Last committed:      $Revision: 121 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-01 15:54:10 +0200 (ïåò, 01 ìàðò 2013) $
 * ID:                  $Id: select.php 121 2013-03-01 13:54:10Z fire $
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

global $language;
// Attaching mysql_ai class
$mysql = new mysql_ai();
$title = $language['ns.slct'];
// verification of uri request
if (is_numeric($_GET['n'])) {
    // select the row from mysql
    $mysql->Select("news_content", array("ID" => $_GET['n']));
    // check the result from mysql
    if ((bool) $row = $mysql->aArrayedResults) {
        // move pointer of array
        $row = $row[0];
        //creating template
        global $MERGE_TEMPLATE, $theme;
        $MERGE_TEMPLATE["NEWS"]['sector'][] = NEWS_SLTAG_CONTENT;
        $MERGE_TEMPLATE["NEWS"]['sector'][] = NEWS_SLTAG_IMAGE;
        $MERGE_TEMPLATE["NEWS"]['sector'][] = NEWS_SLTAG_DESCR;
        $MERGE_TEMPLATE["NEWS"]['sector'][] = "SHARE_SA";
        $theme->template_setup("NEWS");
        // getting folders from base tag
        $thumbs = kernel::base_tag_folder("{thumbs}");
        $images = kernel::base_tag_folder("{images}");
        $publicfiles = kernel::base_tag_folder("{publicfiles}");
        // make image for news
        if (empty($row['image'])) {
            $image = " ";
        } else {
            $image = "

                <a href=\"/" . $publicfiles . MODULE_DIR . $images . $row['image'] . "\" >
                <img src=\"/" . $publicfiles . MODULE_DIR . $thumbs . $row['image'] . "\" border=\"0\" title=\"{$row['title']}\"/>
                </a>
                ";
        }
        //
        // Generate Category RSS button
        $mysql->aArrayedResults = null;
        $proposed = null;
        $mysql->Select("news_category", array("title" => $row['category']));
        if((bool)$cat = $mysql->aArrayedResults[0]){
           $proposed .='<a href="rss?id=' . $cat['ID'] . '" class="rss"><span> </span>' . $language['ns.rssc'] . $cat['title'] . '</a>';
           $category = $cat['title'];
        }else{
           $proposed .='<a href="rss" class="rss"><span> </span>' . $language['ns.rssc'] . '</a>';
        }

        // creating description
        $description = null;
        $description.= date(NEWS_DATEFORMAT, $row['timestamp']) . " ";
        $description.= (@constant("NEWS_SHOWCHANGE")) ? "{$language['ns.lstc']} <b>{$row['lastchange']}</b> " : null;
        $description.= (@constant("NEWS_SHCATEGORY")) ? "{$language['ns.catg']} <b>{$row['category']}</b> " : null;
        $description.= (@constant("NEWS_SHKEYWORDS")) ? "{$language['ns.tags']} <b>{$row['keywords']}</b> " : null;
        // fill up data
        $row_tag = array();
        $title = $row['title'];
        $row_tag[NEWS_SLTAG_CONTENT] = $row['body'];
        $row_tag[NEWS_SLTAG_IMAGE] = $image;
        $row_tag[NEWS_SLTAG_DESCR] = $description . " ".$language['lw.category'].": <b><a href=\"/".MODULE_DIR."view?cat={$cat['ID']}\" >" . $row['category']."</a></b>";
        $row_tag['SHARE_SA']  = social_networks(1, 1, 1, 1, $row['category'], 1, $proposed);
        $row_tag['SHARE_SA']  = social_networks(1, 1, 1, 1, $row['category'], 1, $proposed);
        // attach content to template
        $text = theme::content($row_tag, "NEWS", true);
    } else {
        $text = $language['ns.sles'];
    }
} else {
    $text = $language['ns.slur'];
}
// show the result in theme
theme::content(array(ucfirst($title), $text));
