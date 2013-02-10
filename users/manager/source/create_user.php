<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: create_user.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * @filesource  Dollop Users
 * @package dollop 
 * @subpackage Module
 * 
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}



if ($_POST[$sector]) {


    $hex = constant('HEX');
    $password = password($_POST['userpass'], $hex);
    $hash_key = hash_key($_POST['username'], $hex);


    $sql = "INSERT INTO `" . USERS_SQLTBL_MAIN . "` 
            (`username`, `password`, `userid`, `userlevel`, `usermail`, `valid`, `hash`, `hash_generated`)
            VALUES
            ('{$_POST['username']}','{$password}','0','0','{$_POST['usermail']}','0',

            PASSWORD( '{$hash_key}' + UNIX_TIMESTAMP( NOW() ) ), UNIX_TIMESTAMP( NOW() )
            
            );";


    $query = mysql_query($sql) or ($mysql_error = $this->mysql_alert_box(mysql_error(), $sector) );

    if ($query) {
        
    }
}
global $language;



$BODY = <<<eol
    
    
    <form id="register_user" method="post" action="#{$sector}">
<p> {$mysql_error }</p>
<p>
<table width="70%" border="0" align="center" cellpadding="8" cellspacing="0">

  <tr>
        <td colspan="4" align="center">
&nbsp;
        </td> 
        
    </tr>
  <tr>
    <td width="20%"></td>
    <th align="right" class="ui-state-active" width="20%"> {$language['p.users.na']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="username"  value="" /></td>
    <td width="20%"></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td></td>
  </tr>
   <tr>
    <td width="20%"></td>
    <th align="right" class="ui-state-active" width="20%"> {$language['p.users.pa']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="userpass"  value="" /></td>
    <td width="20%"></td>
  </tr>
  <tr>
    <td></td>
    <td align="right">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td></td>
  </tr>
    <tr>
    <td width="20%"></td>
    <th align="right" class="ui-state-active" width="20%"> {$language['p.users.em']} </th>
    <td align="left" class="ui-state-default"><input type="text" name="usermail"  value="" /></td>
    <td width="20%"></td>
  </tr> 
    <tr>
        <td colspan="4" align="center">
        <input type="submit" name="{$sector}" id="button" value="{$language['lan.submit']}" />
        </td> 
        
    </tr>
  </table>
  </form>
eol;
