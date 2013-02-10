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
 * @filesource Main Search
 * @package search
 * @subpackage none
 * 
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
/**
 * 
 * @filesource
 * manage the news
 */
global $language;

/*
  $sector_insert = 'insert_' . $sector;
  $sector_edit = 'edit_' . $sector;
  $this->show_sublink[] = $sector_insert;
 */
$BODY = null;
$htmltblall = new html_table(null, 'admin', 0, 0, 4);
$htmltblwht = new html_table(null, 'admin', 0, 0, 4);
$fch = explode(",", MD_CELLS);
PRINT_R($fch);
//
// Get all tables that have a "text" type in it. 
$q1 = mysql_query("SHOW FULL TABLES  FROM " . DATABASE . " LIKE '" . PREFIX . "%' ") or die(mysql_error());
while ($t = mysql_fetch_array($q1)):$q2 = mysql_query("SHOW FULL COLUMNS IN {$t[0]} FROM  " . DATABASE . "") or die(mysql_error());
    while ($c = mysql_fetch_array($q2)):if(in_array($c[0], $fch)):$f[$c[1]][] = $c[0];$tbl[] = $t[0];endif;
    endwhile;
    mysql_free_result($q2);
endwhile;
mysql_free_result($q1);

$i = 1;
$c = 1;
$my = new mysql_ai;

if(isset($_POST['submit_whitelist']) && is_array($_POST['whitelist'])){
    mysql_query("TRUNCATE TABLE  `".PREFIX."search_whitelist`");
    foreach($_POST['whitelist'] as $insert_row){
        mysql_query("INSERT INTO `".PREFIX."search_whitelist` (`tables`) VALUES('$insert_row'); ");  
    }
    
}


$my->Select("search_whitelist");
//
// Creating table for results
$htmltblall->addRow();
$htmltblall->addCell('№', null, 'header', array('width' => '5%'));
$htmltblall->addCell($language['md.table'], null, 'header', array('width' => '40%'));
$htmltblall->addCell($language['md.activate'], null, 'header', array('width' => '10%'));
$form = null;
$checked = null;

foreach ($my->aArrayedResults as $act ):
    $active[] =$act["tables"];
endforeach;
print_r($f);

foreach (array_unique($tbl) as $list):
    $htmltblall->addRow();
    $htmltblall->addCell($i);
    $htmltblall->addCell("<label for=\"table-$list\" >" . $list . "</label>");
    if((bool)array_search($list, $active) ) {
        $checked = "checked";
    } else {
        $checked = null;
    }
    $form = <<<eol
   <center><input type="checkbox" name="whitelist[]" value="{$list}" id="table-$list" style=" font-size: 110%; -ms-transform: scale(2); -moz-transform: scale(2); -webkit-transform: scale(2); -o-transform: scale(2); " $checked/> </center>
eol;
    $htmltblall->addCell("<ul id=\"icons\">{$form}</ul>");
    $i++;
endforeach;
//
// Show messages or table
if ($i > 1) {
    $BODY .= <<<eol
   <form action="#$sector" method="post" name="white-lister">
eol;
    $BODY .= $htmltblall->display() .<<<eol
   <center><input type="submit" id="button" value="{$language['lan.submit']}" name="submit_whitelist" /></center>
            </form>
eol;
} else {
    $BODY .="<p>" . $language['md.not-tables'] . "</p>";
}



