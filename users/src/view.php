<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: view.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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

global $language;
if (!defined("USER_ID")) {
    header("location: main");
} else {
    $d = new mysql_ai;
    switch (_GET("u")) {


        default :
            if (is_numeric($_GET['id'])) {
                $d->Select(constant("USERS_SQLTBL_MAIN"), array(constant("USERS_SQLTBL_COL_UID") => $_GET['id']));
                if ($d->iRecords >= 1) {
                    $users_process = new users_process();
                    $users_process->signUp_fields();
                    $r = $d->aArrayedResults[0];
                    $tag = array();
                    $tag['avatar'] = kernel::base_tag("{host}{publicfiles}{module_dir}".$r[USERS_SQLTBL_COL_UNAME].DIRECTORY_SEPARATOR."{thumbs}".$r[USERS_SQLTBL_COL_AVATAR]);
                    $tag['uname'] = $r[propc("users.sqltbl.col.uname")];
                    $mail = urlencode(str_replace("@", "|-+-|", $r[USERS_SQLTBL_COL_UMAIL]));
                    $alt_mail = urlencode(str_replace("@", " at ", $r[USERS_SQLTBL_COL_UMAIL]));
                    $img_email = HOST . ("images?image=create&text={$mail}&font=arial&size=12");
                    $tag['imgmail'] = $img_email;
                    $tag['altmail'] = $alt_mail;
                    $filds = $users_process->fields_user;
                    $other = null;
                    foreach ($filds as $oth) {
                        $other.= <<<oth
  <tr>
    <th align="right">{$oth['fld_title']}</th>
    <td align="left">{$r[$oth['fld_name']]}</td>
  </tr>
oth;
                    }
                    $tag['other'] = $other;
                    $tag['additional'] = <<<eol
   <a href="messages?m=send:{$_GET['id']}" target="_self">{$language['users.view.sendmess']}</a>
eol;
                    $title = $language['users.view.viewingu'] . $tag['uname'];
                    $content = theme::custom_template("review", $tag);
                } else {
                    $content = $language['users.view.notexist'];
                }
            }

            break;
    }
}





theme::content(array($title, $content));

