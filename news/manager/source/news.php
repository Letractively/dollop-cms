<?php

/**
  ============================================================
 * Last committed:     $Revision: 133 $
 * Last changed by:    $Author: fire1 $
 * Last changed date:    $Date: 2013-04-02 20:13:15 +0300 (âò, 02 àïð 2013) $
 * ID:       $Id: news.php 133 2013-04-02 17:13:15Z fire1 $
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
 * manage the news
 */
global $language, $cpanel;
$sector_insert = 'insert_' . $sector;
$sector_edit = 'edit_' . $sector;
$this->show_sublink[] = $sector_insert;
$tbl = new html_table(null, 'admin', 0, 0, 4);

// news categories
function news_category($category = null) {
    $query = db_query("SELECT * FROM `" . PREFIX . "news_category` ") or die($mysql_error = db_error());
    $option = null;
    foreach (db_fetch($query) as $row) {
        if ($category == $row['title']) {
            $slct = 'selected="selected"';
        } else {
            $slct = null;
        }
        $option.= <<<eol
<option value="{$row['title']}" {$slct} >{$row['title']}</option>
eol;
    }
    return $option;
}

if (isset($_POST['erase-news'])) {
    db_query("DELETE FROM `" . PREFIX . "news_content` WHERE   `ID`='{$_POST['erase-news']}' ") or ($mysql_error = db_error());
}




/// Process mysql
if (isset($_POST['cat_new']) && !empty($_POST['cat_new'])) {
    $_POST = stripslashes_deep($_POST);
    $_POST['cat_new'] = (htmlspecialchars(trim(addslashes($_POST['cat_new']))));
    $_POST['cat_des'] = addslashes($_POST['cat_des']);
    db_query("INSERT INTO `" . PREFIX . "news_category`  (`title`,`description`)

            VALUES  ('{$_POST['cat_new']}','{$_POST['cat_des']}');  ") or ($mysql_error = db_error());
}
// INSERT
if (isset($_POST[$sector_insert])) {
    @$_SESSION['picture'] = null;
    $_POST['image'] = basename($_POST['image']);
    $_POST = stripslashes_deep($_POST);
    $_POST['title'] = (htmlspecialchars(trim($_POST['title'])));
    $_POST = array_map('addslashes', $_POST);
    if (!empty($_POST['cat_new'])) {
        $category = $_POST['cat_new'];
    } else {
        $category = $_POST['cat'];
    }
    db_query("INSERT INTO `" . PREFIX . "news_content`(`title`,`body`,`image`,`description`,`keywords`,`timestamp`,`category`)
            VALUES(

            '{$_POST['title']}',
            '{$_POST['body']}',
            '{$_POST['image']}',
            '{$_POST['description']}',
            '{$_POST['tag']}',
            UNIX_TIMESTAMP(),
            '{$category}'

            );") or ($mysql_error = db_error());
}
// UPDATE
if (isset($_POST["{$sector_edit}-this"]) && !empty($_POST['id'])) {
    @$_SESSION['picture'] = null;
    $_POST['image'] = basename($_POST['image']);
    $_POST = stripslashes_deep($_POST);
    $_POST['title'] = (htmlspecialchars(trim($_POST['title'])));
    $_POST = array_map('addslashes', $_POST);
    if (!empty($_POST['cat_new'])) {
        $category = $_POST['cat_new'];
    } else {
        $category = $_POST['cat'];
    }
    db_query("UPDATE  `" . PREFIX . "news_content`

            SET
            `title` =    '{$_POST['title']}',
            `body`  =    '{$_POST['body']}',
            `image` =    '{$_POST['image']}',
            `description`='{$_POST['description']}',
            `keywords`='{$_POST['tag']}',

            `category`='{$category}'


            WHERE `ID`='{$_POST['id']}' ;

            ") or die(db_error());
}
$query = db_query("SELECT `ID`,`title`,`category`,`timestamp` FROM `" . PREFIX . "news_content` ORDER BY `ID` DESC ") or ($mysql_error = db_error());
$tbl->addRow();
$tbl->addCell('id', null, 'header', array('width' => '5%'));
$tbl->addCell($language['lw.title'], null, 'header', array('width' => '40%'));
$tbl->addCell($language['lw.category'], null, 'header', array('width' => '15%'));
$tbl->addCell($language['lw.date'], null, 'header', array('width' => '15%'));
$tbl->addCell($language['lw.options'], null, 'header', array('width' => '50px'));
$i = 0;
foreach (db_fetch($query, "assoc") as $r) {
    $i++;
    $tbl->addRow();
    $tbl->addCell($r['ID']);
    $tbl->addCell($r['title']);
    $tbl->addCell($r['category']);
    $tbl->addCell("<center>" . date("d-m-Y", $r['timestamp']) . "</center>");
    $operations = '<ul id="icons">';
    $operations.= $cpanel->operation_buttons($r['ID'], $i, $sector_edit, $sector_edit, ' ui-icon-pencil', " {$language['lw.edit']} ");
    $operations.= $cpanel->operation_buttons($r['ID'], $i, "$sector", "erase-news", 'trash', " {$language['lw.erase']} ", array(
        "OK" => true,
        'title' => " {$language['main.cp.question.alt']} {$language['lw.erase']}:",
        'body' => "{$language['main.cp.question.erase']}: <br/><b> {$r['title']}</b>"
            )
    );
    ;
    $operations.= '</ul>';
    $tbl->addCell($operations);
    $operations = null;
}
if ($i > 0) {
    $error = null;
    if (!empty($mysql_error)) {
        $error = '<p align="center">' . $cpanel->mysql_error_box($mysql_error, $sector) . "</p>";
    }

    $BODY = $error . $tbl->display();
} else {
    $BODY = "<p align='center'><b>{$language['ns.empt']}</b></p>";
}
///// INSERT NEWS
$textarea = theme::textarea('body', null, null, 25, null, '80%', '340px');
$cat_opt = news_category();
$operations = '<ul id="icons">';
$data_upl = array("OK" => false, "title" => $language['lw.upload'], "body" => $this->upload_operation(MODULE_DIR), "w" => "660");
$operations.= $this->operation_buttons("option", // sub name
        $sector_insert, // name
        null, //
        $language['lw.upload'], // operation option name
        "arrowthickstop-1-n", // option icon
        $language['lw.upload'], // title
        $data_upl
// content data array
);
$operations.= '</ul>';
$image = $r['image'];

$SUBBODY[$sector_insert] = <<<eol


<form id="edit_form" method="post" action="#{$sector}" target="_self">
<input type="hidden" name="id" value="{$r['ID']}"  readonly>


<p align="center">

</p>
<p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<th width="10%" align="right">{$language['lw.image']}:</th>
<td>
{$operations}  <input type="text" name="image" value="{$image}" style="width:20%;" id="image" class="upload_image" /></td>
</tr>
<tr>
<td height="5px"></td>
<td></td>
</tr>
<tr>
<th align="right" class="ui-state-active"> {$language['main.cp.news']} <br/> {$language['lw.data']}</td>
<td class="ui-state-default" height="25px">
<script type="text/javascript">
$(document).ready(function(){
    $('#button-{$sector_insert}-ctegory-switch').click(function() {
        $('.{$sector_insert}-ctegory-switch').toggle('slow');
    });
});
</script>

<div style="display:block;">
    <div style="width:35%; display:inline-block;" >
        <label for="{$sector}-title"><small> {$language['main.cp.news']} {$language['lw.title']}:</small><br />
            <input type="text" name="title"  value="" id="{$sector}-title" />
        </label>
    </div>

     <div style="width:35%; display:inline-block;" >
        <label for="{$sector}-tag"><small> {$language['main.cp.news']} {$language['lw.tags']}:</small><br />
            <input type="text" name="tag"  value="" id="{$sector}-tag" />
        </label>
    </div>

    <div style="width:2%; display:inline-block; " id="button-{$sector_insert}-ctegory-switch" title="{$language['lw.new']}  {$language['lw.category']} / {$language['lw.categories']}">
        <span class="ui-state-default ui-corner-all" id="button-{$sector_insert}-ctegory-switch" style="float:right">
            <span class="ui-icon ui-icon-carat-2-n-s" ></span>
        </span>
    </div>

    <div style="width:25%; display:inline-block; max-height: 42px;" >

        <div class="{$sector_insert}-ctegory-switch" style="display:none;">
            <label for="{$sector}-category"><small>{$language['lw.new']} {$language['main.cp.news']} {$language['lw.category']}:</small><br />
                <input type="text" name="cat_new"  value="" id="{$sector}-category" />
            </label>
        </div>

        <div class="{$sector_insert}-ctegory-switch">
            <label for="{$sector}-category"><small> {$language['main.cp.news']} {$language['lw.categories']}:</small><br />
            <select name="cat" id="{$sector}-category">
                {$cat_opt}
            </select>
            </label>
        </div>

    </div>
</div>

</td>
</tr>
<tr>
<td height="5px">&nbsp; </td>
<td>&nbsp; </td>
</tr>
<tr>
<th align="right" class="ui-state-active"> {$language['main.cp.news']} <br/> {$language['lw.content']}</td>
<td class="ui-state-default"> <center>{$textarea}</center> </td>
</tr>
  <tr>
        <td colspan="2" align="center">
        <input type="submit" name="{$sector_insert}" id="button" value="{$language['lan.submit']}" />
        </td>

    </tr>

</table>
</form>

eol;
///// EDIT NEWS
$r = null;
$text = "{$language['lw.the']} {$language['main.cp.news']} {$language['lw.not']} {$language['lw.selected']}.";
if ($_POST[$sector_edit]) {

    $sql = db_query("SELECT * FROM  `" . PREFIX . "news_content` WHERE

            `ID`='{$_POST[$sector_edit]}'  ") or ($mysql_error = $cpanel->mysql_error_box(db_error(), $sector));
    $r = db_fetch($sql, "assoc", "current");
    if (is_null($r)) {
        $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_edit);
    }
} else {

    $edit_info = $this->mysql_alert_box(ucfirst($text), $sector_edit);
}

$textarea = theme::textarea('body', $r['body'], null, 25, null, '80%', '340px');
$cat_opt = news_category($r['category']);
$operations = '<ul id="icons">';
$data_upl = array("OK" => false, "title" => $language['lw.upload'], "body" => $this->upload_operation(MODULE_DIR), "w" => "660");
$operations.= $this->operation_buttons("option", // sub name
        $sector_edit, // name
        null, //
        $language['lw.upload'], // operation option name
        "arrowthickstop-1-n", // option icon
        "{$language['lw.upload']} {$language['lw.image']}", // title
        $data_upl
// content data array
);
$operations.= '</ul>';

$image = $r['image'];

$SUBBODY[$sector_edit] = <<<eol


<form id="edit_form" method="post" action="#{$sector}" target="_self">
<input type="hidden" name="id" value="{$r['ID']}"  readonly>
$cat_opt
<p align="center">
{$mysql_error}{$edit_info}
</p>
<p>
<table width="80%" border="0" align="center" cellpadding="8" cellspacing="0">
<tr>
<th width="10%" align="right">{$language['lw.image']}:</th>
<td>
    {$operations}  <input type="text" name="image" value="{$image}" style="width:20%;" id="image" class="upload_image" />
</td>
</tr>
<tr>
<td height="5px"></td>
<td></td>
</tr>
<tr>
<th align="right" class="ui-state-active"> {$language['main.cp.news']} <br/> {$language['lw.data']}</td>
<td class="ui-state-default" height="25px">
<script type="text/javascript">
$(document).ready(function(){
    $('#button-{$sector_edit}-ctegory-switch').click(function() {
        $('.{$sector_edit}-ctegory-switch').toggle('slow');
    });
});
</script>

<div style="display:block;">
    <div style="width:35%; display:inline-block;" >
        <label for="{$sector}-title"><small> {$language['main.cp.news']} {$language['lw.title']}:</small><br />
            <input type="text" name="title"  value="{$r['title']}" id="{$sector}-title" />
        </label>
    </div>

     <div style="width:35%; display:inline-block;" >
        <label for="{$sector}-tag"><small> {$language['main.cp.news']} {$language['lw.tags']}:</small><br />
            <input type="text" name="tag"  value="{$r['keywords']}" id="{$sector}-tag" />
        </label>
    </div>

    <div style="width:2%; display:inline-block; " id="button-{$sector_edit}-ctegory-switch" title="{$language['lw.new']}  {$language['lw.category']} / {$language['lw.categories']}">
        <span class="ui-state-default ui-corner-all" id="button-{$sector_edit}-ctegory-switch" style="float:right">
            <span class="ui-icon ui-icon-carat-2-n-s" ></span>
        </span>
    </div>

    <div style="width:25%; display:inline-block; max-height: 42px;" >
        <div class="{$sector_edit}-ctegory-switch" style="display:none;">
            <label for="{$sector}-category"><small>{$language['lw.new']} {$language['main.cp.news']} {$language['lw.category']}:</small><br />
                <input type="text" name="cat_new"  value="" id="{$sector}-category" />
            </label>
        </div>
        <div class="{$sector_edit}-ctegory-switch">
            <label for="{$sector}-category"><small> {$language['main.cp.news']} {$language['lw.categories']}:</small><br />
            <select name="cat" id="{$sector}-category">
                {$cat_opt}
            </select>
            </label>
        </div>

    </div>
</div>

</td>
</tr>
<tr>
<td height="5px">&nbsp; </td>
<td>&nbsp; </td>
</tr>
<tr>
<th align="right" class="ui-state-active"> {$language['main.cp.news']} <br/> {$language['lw.content']}</td>
<td class="ui-state-default"> <center>{$textarea}</center> </td>
</tr>
  <tr>
        <td colspan="2" align="center">
        <input type="submit" name="{$sector_edit}-this" id="button" value="{$language['lan.submit']}" />
        </td>

    </tr>

</table>
</form>

eol;
