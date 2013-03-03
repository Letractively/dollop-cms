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
 * @filesource Dollop Manager
 * @package Dollop
 * @subpackage Manager
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
//
// Get conf
global $language, $KERNEL_PROP_MAIN, $CONFIGURATION;
$jscript = "$(function(){";
//
// Manager Sub sectors
$sector_boot = 'boot_' . $sector;
$this->show_sublink[] = $sector_boot;
//
// Save new Dollop Props
if (isset($_POST["submit_{$sector}"])) {
    unset($_POST["submit_{$sector}"]);
    $result = array_merge($CONFIGURATION, $_POST);
    $ext = $KERNEL_PROP_MAIN['kernel.trunkExt'];
    $enc = kernel::enCrypt(serialize($result), crc32(constant('HEX')));
    file_put_contents(@constant('TRUNK') . md5(@constant('HEX')) . $ext, $enc);
    header_location(request_uri() . "#{$sector}");
}
//
// Get boot file
$bootfile = realpath($dir = ROOT . SRCDR . $KERNEL_PROP_MAIN['dollop.etc'] . DIRECTORY_SEPARATOR . ".boot/" . $KERNEL_PROP_MAIN['dp.etc']);


//
// SYSTEM manager
$tbl = new html_table(null, 'admin', 0, 0, 4);
//
// Table Header
$tbl->addRow();
$tbl->addCell('№', null, 'header', array('width' => '5%'));
$tbl->addCell($language['lw.field'] . " " . $language['lw.name'], null, 'header', array('width' => '40%'));
$tbl->addCell($language['lw.data'], null, 'header', array('width' => '50%'));
$tbl->addCell($language['lw.options'], null, 'header', array('width' => '50px'));
$skip = array("KEY", "HEX", "SPR", "version", "codname", "release");
$BODY = "<center><strong>Dollop {$language['lw.settings']} </strong></center>
<form method='post' action='#{$sector}' name='edit-main-prop' >";
$i = 0;
foreach ($CONFIGURATION as $key => $val) {
    //
    // Fill-up information
    if (!in_array($key, $skip)) {
        if (is_array($val)) {
            foreach ($val as $sk => $sv) {
                $tbl->addRow();
                $tbl->addCell($i++);
                $tbl->addCell("<b>$key &rarr;</b> $sk");
                $tbl->addCell(html_form_input('text', "{$key}[{$sk}]", $sv, "id=\"fld-$i\""));
                $tbl->addCell("none");
            }
        } else {
            $tbl->addRow();
            $tbl->addCell($i++);
            $tbl->addCell("<b>$key</b>");
            $tbl->addCell(html_form_input('text', $key, addslashes($val), "id=\"fld-$i\""));
            $tbl->addCell("none");
        }
    }
}
$BODY .=$tbl->display() . <<<eol
        <p align="center"><input type="submit" name="submit_{$sector}" value="{$language['lan.submit']}" id="button"> </p>
   </form>
eol;
//
// Boot manager
$tbl = new html_table(null, 'admin', 0, 0, 4);
//
// Table Header
$tbl->addRow();
$tbl->addCell('№', null, 'header', array('width' => '5%'));
$tbl->addCell($language['lw.field'] . " " . $language['lw.name'], null, 'header', array('width' => '40%'));
$tbl->addCell($language['lw.contact'], null, 'header', array('width' => '50%'));
$tbl->addCell($language['lw.options'], null, 'header', array('width' => '50px'));
//
// Save new Boot data
if (isset($_POST["submit_{$sector_boot}"])) {
    unset($_POST["submit_{$sector_boot}"]);
    if(!empty($_POST['new_key'])){
        $new_key = preg_replace('/^[\w]$/', "", $_POST['new_key']);
        $_POST[$new_key]= $_POST['new_val'];
    }
    unset($_POST['new_key']);
    unset($_POST['new_val']);
    $result = serialize($_POST);
    file_put_contents($bootfile, $result);
    header_location(request_uri() . "#{$sector_boot}");
}

//
// Edit Boot dep
if (isset($bootfile)) {
    $i = 0;
    $boot_array = unserialize(file_get_contents($bootfile));
    foreach ($boot_array as $key => $val) {
        //
        // Fill-up information
        $tbl->addRow();
        $tbl->addCell($i++);
        $tbl->addCell("<b>$key</b>");
        $tbl->addCell(html_form_input('text', $key, addslashes($val), "id=\"bfld-$i\""));
        $tbl->addCell("<center><ul id='icons'><li class='ui-state-default ui-corner-all' id='boot-disable-$i'><span class='ui-icon ui-icon-closethick'></span></li></ul></center>");
        $jscript .=<<<eol
$("#boot-disable-$i").click(function(){ $("#bfld-$i").attr("disabled","disabled"); });

eol;
    }
    $tbl->addRow();
    $tbl->addCell();
    $tbl->addCell("<center>".$language['lw.additional'] ." ". $language['lw.name'].":</center>");
    $tbl->addCell("<center>".$language['lw.additional'] ." ". $language['lw.data'].":</center>");
    $tbl->addCell();
    $tbl->addRow();
    $tbl->addCell();
    $tbl->addCell(html_form_input('text', "new_key", null));
    $tbl->addCell(html_form_input('text', "new_val", null));
    $tbl->addCell("-");
    if ($i >= 1) {
        $SUBBODY[$sector_boot] = "<form method='post' name='$sector_boot' action='#{$sector_boot}'>" . $tbl->display() . <<<eol
        <p align="center"><input type="submit" name="submit_{$sector_boot}" value="{$language['lan.submit']}" id="button"> </p>
   </form>
eol;
    }
}

$this->html_include($jscript . "});", 'jscript');


