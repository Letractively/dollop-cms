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
 * News Menu
 */
$destination = propc("md.news.destination");
if (isset($destination)) {
kernel::includeByHtml("/{$destination}style-menu/lastnews.css", "css");
kernel::includeByHtml("/{$destination}style-menu/lastnews.js", "js");
    $BODY = null;
    $str = null;
    $prfx = constant("PREFIX");
    $sql = "SELECT `ID`,`title`,`body` FROM `{$prfx}news_content` LIMIT 6 ";
    $results = kernel::sql_fetch_array($sql);
    if (is_array($results)) {
        $cur = "current";
        foreach ($results as $row) {
            $text =  implode(' ',array_slice(str_word_count(strip_tags($row['body']),1),0,30)) ;
            $str .=<<<EOL
        <div class="last-news-block $cur">
            <div class="sub-title"><a href="{$destination}/select?n={$row['ID']}">{$row['title']}</a></div>
            <div class="text sub">$text</div>
        </div>


EOL;
            $cur=null;
        }
    }

$last_news = <<<EOL
    <div id="menu-last-news">
        <div class="menu-news-entry">
            $str
           <div class="clearfix"></div>
        </div>

</div>
        <div id="navigation-last-news">
        <div id="nav-menu-last-news-pv"></div>
        <div id="nav-menu-last-news-nx"></div>
        </div>
EOL;


    $BODY = $last_news;
}



