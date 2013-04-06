<?php

/**
  ============================================================
 * Last committed:      $Revision: 129 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-28 13:31:47 +0200 (÷åòâ, 28 ìàðò 2013) $
 * ID:                  $Id: options.php 129 2013-03-28 11:31:47Z fire $
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

global $language, $cpanel;

//
// Edit news category
if (isset($_POST["save-categories-{$sector}"])) {
    $id = null;
    $sql = null;
    $error = null;
    $data = $_POST;
    if (is_array($data["cat-current"])) {
        foreach ($data["cat-current"] as $id => $current_name) {
            $new = trim($data['cat-new'][$id]);

            if (!empty($data['erase-category'][$id])) {
               
                db_query("DELETE FROM `".PREFIX."news_category` WHERE   `ID`='$id' ") or ($error = db_error());
            } else {
                $sqla = "UPDATE `" . PREFIX . "news_content` SET `category`='{$new} ' WHERE `category`='$current_name'; ";
                if (!(bool) db_query($sqla)) {
                    $error .= db_error() . "<br />";
                }
                $sqlb = "UPDATE `" . PREFIX . "news_category` SET `title`='{$new}', `description`='{$data['cat-description'][$id]}' WHERE `ID`='$id' ";
                if (!(bool) db_query($sqlb)) {
                    $error .= db_error() . "<br />";
                }
            }
        }



        if ((bool) $error) {
            $mysql_error = $cpanel->mysql_error_box("<br />$error", $sector);
        }
    }
}

//
// Create news category
if (!empty($_POST["create-category-{$sector}"])) {

    db_query("INSERT INTO `" . PREFIX . "news_category`  (`title`,`description`)
            VALUES  ('{$_POST["create-category-{$sector}"]}','{$_POST["create-description-{$sector}"]}');  ") or ($mysql_error = db_error());
}




$result = db_query("SELECT ID,`title`,`description` FROM `" . PREFIX . "news_category`  ") OR ($mysql_error = $cpanel->mysql_error_box(db_error(), $sector));
$BODY = <<<eol
<p align="center">{$mysql_error}</p>
<form action="" name="news-option" method="post">
<table width="80%" align="center">
    <tr >
    <th class="ui-state-active"> {$language['lw.current']} {$language['lw.name']} </th>
    <th class="ui-state-active"> {$language['lw.new']} {$language['lw.name']} </th>
    <th class="ui-state-active"> {$language['lw.description']} </th>
    </tr>

eol;

foreach (db_fetch($result, "assoc") as $row) {
    $BODY .=<<<eol
<tr>
    <td><input type="text" value="{$row['title']}" name="cat-current[{$row['ID']}]" readonly style="float:left;width:80%">
        <label>
        <input type="checkbox" name="erase-category[{$row['ID']}]" value="[{$row['ID']}]" />
            <b><small>{$language['lw.erase']}</small></b>
        </label>
        </td>
    <td><input type="text" value="{$row['title']}" name="cat-new[{$row['ID']}]"></td>
    <td><textarea name="cat-description[{$row['ID']}]" rows="1">{$row['description']}</textarea></td>
    </tr>

eol;
}
$BODY .=<<<eol
<tr>
    <th align="right" class="ui-state-active"> {$language['lw.new']} {$language['lw.category']}: </th>
    <td><input type="text" value="" name="create-category-{$sector}"></td>
    <td><textarea name="create-description-{$sector}" rows="1"></textarea></td>
    </tr>
    <tr>
        <td colspan="3" align="center">
        <input type="submit" name="save-categories-{$sector}" id="button" value="{$language['lw.save']}" />
        </td>
    </tr>

eol;


$BODY .=<<<eol
    </table>
</form>

eol;





