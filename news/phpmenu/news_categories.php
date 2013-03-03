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
$destination = propc("md.news.index");
if (isset($destination)) {
global $news_categories;
    $BODY = null;
    $news_categories = null;
    $prfx = constant("PREFIX");
    $sql = "SELECT `ID`,`title` FROM `{$prfx}news_category`  ";
    $results = kernel::sql_fetch_array($sql);
    if (is_array($results)) {
        foreach ($results as $row) {
            $news_categories .=<<<EOL
   <a href="{$destination}?cat={$row['ID']}" class="button">{$row['title']}</a>
EOL;
        }
    }
}
$BODY = $news_categories;


