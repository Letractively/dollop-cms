<?php

/**
  ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: web_info.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
//
//
global $language;
if ($_POST[$sector]) {
    $_POST = stripslashes_deep($_POST);
    $site_name = addslashes(htmlspecialchars(trim($_POST['site_name'])));
    $_POST = array_map('addslashes', $_POST);
    $slq = mysql_query("UPDATE  `" . PREFIX . "preferences`

SET

`site_name`         ='{$site_name}',
`site_description`  ='{$_POST['site_description']}',
`site_mail`         ='{$_POST['site_mail']}',

`site_disclaimer`   ='{$_POST['site_disclaimer']}',
`copyright`         ='{$_POST['copyright']}',
`ico`               ='{$_POST['ico']}'


WHERE `ID`=1;") or print (mysql_error());
}
$slq = mysql_query("SELECT * FROM `" . PREFIX . "preferences` WHERE `ID`=1;");
if ($r = mysql_fetch_array($slq)) {
    $BODY = <<<html
<form id="info" method="post" action="#{$sector}">

<p>
<table width="70%" border="0" align="center" cellpadding="8" cellspacing="0">

  <tr>
        <td colspan="4" align="center">
&nbsp;
        </td>

    </tr>
  <tr>
    <td width="20%"></td>
    <th align="right" class="ui-state-active" width="20%"> {$language['lw.website']}<br/>{$language['lw.name']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="site_name"  value="{$r['site_name']}" /></td>
    <td width="20%"></td>
  </tr>
    <tr>
    <td></td>
    <td align="right">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']}<br/> {$language['lw.description']} </th>
    <td align="left" class="ui-state-default"><textarea name="site_description"  cols="45" rows="3">{$r['site_description']}</textarea></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td></td>
  </tr>
  <tr>
      <td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']}<br/> {$language['lw.disclaimer']} </th>
    <td align="left" class="ui-state-default"><textarea name="site_disclaimer"  cols="45" rows="3">{$r['site_disclaimer']}</textarea></td>
    <td></td>
  </tr>
    <tr>
    <td></td>
    <td align="right">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td></td>
  </tr>
  <tr>
  <td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']}<br/> {$language['lw.copyright']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="copyright"  value="{$r['copyright']}" /></td>
    <td></td>
  </tr>
    <tr>
    <td></td>
    <td align="right">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td></td>
  </tr>
  <tr>
  <td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']}<br/> {$language['users.lan.email']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="site_mail"  value="{$r['site_mail']}" /></td>
    <td></td>
  </tr>
    <tr>
    <td></td>
    <td align="right">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td></td>
  </tr>
  <tr>
  <td></td>
    <th align="right" class="ui-state-active"> {$language['lw.website']}<br/> {$language['lw.icon']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="ico"   value="{$r['ico']}" /></td>
    <td></td>
  </tr>
  <tr>
        <td colspan="4" align="center">
        <input type="submit" name="{$sector}" id="button" value="{$language['lan.submit']}" />
        </td>

    </tr>
</table>

<p/>
</form>
html;
}
