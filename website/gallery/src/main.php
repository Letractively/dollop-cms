<?php
/**
 ============================================================
 * Last committed:      $Revision$
 * Last changed by:     $Author$
 * Last changed date:   $Date$
 * ID:                  $Id$
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
 * @filesource Index redirect
 * @package search
 * @subpackage none
 *
 */

if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
global $language;
$title = kernel::base_tag("{site_name} ") . $language['md.title'];
$tag = array();
$my = new mysql_ai;

if (is_numeric($_GET['cat'])) {
    
    $my->Select("gallery_category", array(
        "ID" => $_GET['cat']
    ));
    $cat = $my->aArrayedResults;
    $my->aArrayedResults = null;
    $my->Select("gallery_content", array(
        "category" => $cat[0]['name']
    ));
    $folder_module = kernel::base_tag("/{publicfiles}{module_dir}");
    $images = kernel::base_tag("{images}");
    $thumbs = kernel::base_tag("{thumbs}");
    $cnt = '<div class="gallery-content">';
    $title  = $title . " &rarr; " . str_replace("_", " ", $cat[0]['name']);
    $width  = $cat[0]['value_type'];
    $videoThumb = HOST . MODULE_DIR . "images/video-thumb.png";
    foreach ($my->aArrayedResults as $row) {
        
        switch ($row['value_type']) {
            case "image":
                $cnt.= <<<eol
            <a href="{$folder_module}{$row['category']}/{$images}{$row['name']}" rel="shadowbox[{$row['category']}]" title="{$row['title']}">
                <img src="{$folder_module}{$row['category']}/{$thumbs}{$row['name']}"  />
            </a>
eol;
                
            break;
            case "video":
                $cnt.= <<<eol
            <a href="{$row['value_text']}" rel="shadowbox[{$row['category']}];width=405;height=340;player=swf;">
                <img src="{$videoThumb}" class="video"  style="height:$width"/>
            </a>
eol;
                
            break;
        }
    }
    $content = $cnt . '</div>';
} else {
    $my->Select("gallery_category");
    foreach ($my->aArrayedResults as $row) {
        $row['name'] = "<a href=\"?cat={$row['ID']}\"> " . str_replace("_", " ", $row['name']) . "</a>";
        $cnt.= theme::custom_template("category", $row);
    }
    $content = "<ul id=\"gallery\" class=\"horizontal\"> $cnt </ul>";
}

//$THEME = null;
theme::content(array(
    $title,
    $content
));


