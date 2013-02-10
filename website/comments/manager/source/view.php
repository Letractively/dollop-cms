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
 * Functions
 *
 */
function option_selects($key, $row) {
    $opt = "";
    global $language;
    $$lopper = kernel::prop_constant("md.issue.{$key}");
    for ($I = 0;$I <= $$lopper;$I++) {
        $ttl = kernel::prop_constant("md.issue.{$key}.{$I}");
        if ($row[$key] == $I) {
            $selected = 'selected="selected"';
        } else {
            $selected = "";
        }
        if (!empty($language[$ttl])) {
            $opt.= <<<eol
    <option value="{$I}" {$selected}>{$language[$ttl]}</value>
eol;
            
        }
    }
    return $opt;
}
/**
 *
 * @filesource
 * manage the news
 */
global $language, $db;
if (isset($_POST['status']) && isset($_POST['id'])) {
    mysql_query("UPDATE `" . PREFIX . "issues_content` SET `status`='{$_POST['status']}' WHERE `ID`='{$_POST['id']}'");
}
if (isset($_POST['priority']) && isset($_POST['id'])) {
    mysql_query("UPDATE `" . PREFIX . "issues_content` SET `priority`='{$_POST['priority']}' WHERE `ID`='{$_POST['id']}'");
}
if (isset($_POST['resolution']) && isset($_POST['id'])) {
    mysql_query("UPDATE `" . PREFIX . "issues_content` SET `resolution`='{$_POST['resolution']}' WHERE `ID`='{$_POST['id']}'");
}
if (isset($_POST['category']) && isset($_POST['id'])) {
    mysql_query("UPDATE `" . PREFIX . "issues_content` SET `category`='{$_POST['category']}' WHERE `ID`='{$_POST['id']}'");
}
$query = mysql_query("SELECT * FROM `" . PREFIX . "issues_category` ");
$cat = "";
if (isset($query)) {
    while ($r = mysql_fetch_array($query)) {
        $arrayCategory[$r['ID']] = $r['title'];
    }
}
function category_issue($ids, $arrayCategory) {
    foreach ($arrayCategory as $id => $title) {
        if ($id == $ids) {
            $slc = 'selected="selected"';
        } else {
            $slc = "";
        }
        $cat.= <<<eol
    <option value="{$id}" {$slc}>{$title}</option>
eol;
        
    }
    return $cat;
}
$query = "SELECT * FROM `" . PREFIX . "issues_content` " . $sql_cat;
$page = new mysql_lister($query, 10);
$result = mysql_query($query . " ORDER BY `ID` DESC" . $page->limit()) or die(mysql_error());
$content.= <<<eol
{$language['md.view.information']}
<table align="center" width="80%" border="0" class="issue-view">
<tr>
    <th width="5%">ID</th>
    <th>{$language['md.view.details']}</th>
    <th width="15%">{$language['md.view.category']}</th>
    <th width="15%">{$language['md.view.priorit']}</th>
    <th width="15%">{$language['md.view.resolut']}</th>
    <th width="15%">{$language['md.view.status']}</th>
</tr>
eol;
while ($row = mysql_fetch_array($result)) {
    $i++;
    $status = option_selects("status", $row);
    $resolution = option_selects("resolution", $row);
    $priority = option_selects("priority", $row);
    $category = category_issue($row['category'], $arrayCategory);
    $content.= <<<eol
 <tr>
    <td align="center" >{$row['ID']}</td>
    <td>  
     {$row['relevant']}
    
    </td>
    
    <td align="center">
    
    <form id="category" name="category" method="post">
     <input type="hidden" value="{$row['ID']}" name="id" />
    <select name="category">{$category}</select>
    <input type="submit" value="change" />
    </form>
    
    </td>
    
    <td align="center">
    
    <form id="priority" name="priority" method="post">
     <input type="hidden" value="{$row['ID']}" name="id" />
    <select name="priority">{$priority}</select>
    <input type="submit" value="change" />
    </form>
    
    </td>
    
    <td align="center">
    
    <form id="resolution" name="resolution" method="post">
     <input type="hidden" value="{$row['ID']}" name="id" />
    <select name="resolution">{$resolution}</select>
    <input type="submit" value="change" />
    </form>
    
    </td>
    <td align="center">
    <form id="status" name="status" method="post">
     <input type="hidden" value="{$row['ID']}" name="id" />
    <select name="status">{$status}</select>
    <input type="submit" value="change" />
    </form>
    </td>

 </tr>
eol;
    
}
$nav = $page->display_list(5, "#view");
$content.= "</table><br /><p align='center'>{$nav}</p><p /><br />";
$BODY = $content;
