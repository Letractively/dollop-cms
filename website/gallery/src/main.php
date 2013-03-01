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
global $language, $kernel;
$title = kernel::base_tag("{site_name} ") . $language['md.title'];
$tag = array();
$my = new mysql_ai;

// Main page of gallery
$folder_public = kernel::base_tag("/{publicfiles}{module_dir}");
$images = kernel::base_tag("{images}");
$thumbs = kernel::base_tag("{thumbs}");
$jsa = null;
$category = array();
$my->Select("gallery_category");
$category = $my->aArrayedResults;
foreach ($category as $cat) {
    $cat_name = str_replace("_", " ", $cat['name']);

    // Output template list
    $category_list.= theme::custom_template("nav-cat", array(
        "category" => "cat_" . $cat['name'],
        "title" => $cat_name
    ));
    $my->aArrayedResults = null;
    $my->Select("gallery_content", array(
        "category" => $cat['name']
    ));
    $jsa = null;

    // Check Category is empty
    
    if (is_array($my->aArrayedResults)) {
        foreach ($my->aArrayedResults as $row) {
            $jsa.= <<<eol
    <li>
        <img data-frame="{$folder_public}{$row['category']}/{$thumbs}{$row['name']}" src="{$folder_public}{$row['category']}/{$images}{$row['name']}" title="{$row['title']}"  data-description="{$row['body']}"/>
    </li>
eol;
            
        }
        $jscript = theme::custom_template("js-conf", array(
            "GALLERY" => "#{$cat['name']}"
        ));
        $kernel->external_file("js", $jscript);
        $cnt.= <<<eol
    <div class="gallery content" id="cat_{$cat['name']}">
        <ul id="{$cat['name']}">
            {$jsa}
        </ul>
        <div class="description">{$cat['value_text']}</div>
    </div>
eol;
        
    } else {
        $cnt.= <<<eol
    <div class="gallery content" id="cat_{$cat['name']}">
        {$language['md.empty']}
        <div class="description">{$cat['value_text']}</div>
    </div>
eol;
        
    }
}

// Creating output and mrege the content with navigation
$content = theme::custom_template("cat", array(
    "list" => $category_list,
    "content" => $cnt
));

//$THEME = null;
theme::content(array(
    $title,
    $content
));

