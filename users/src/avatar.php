<?php

/**
  ============================================================
 * Last committed:      $Revision: 127 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-27 09:19:53 +0200 (ñð, 27 ìàðò 2013) $
 * ID:                  $Id: avatar.php 127 2013-03-27 07:19:53Z fire $
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

global $language, $CONFIGURATION;

if (defined("USER_ID")) {

    $sqltable = kernel::prop_constant('users.sqltbl.main');
    $sqlcolAvatar = kernel::prop_constant('users.sqltbl.col.avatar');
    $sqlcolID = kernel::prop_constant('users.sqltbl.col.uid');
    $userid = constant("USER_ID");

    if (isset($_POST['save_avatar'])) {

        $_POST['avatar'] = mysql_real_escape_string(addslashes(stripslashes_deep($_POST['avatar'])));

        db_query("UPDATE `$sqltable` SET `$sqlcolAvatar`='{$_POST['avatar']}' WHERE `{$sqlcolID}`='$userid' ");
    }

    $sql = db_query("SELECT `$sqlcolAvatar` FROM `$sqltable` WHERE `$sqlcolID`='$userid' ") or die(mysql_error());
    if ((bool)$r = db_fetch($sql,"assoc","current")) {

        $selected[$r[$sqlcolAvatar]] = 'checked="checked"';
        $inuse[$r[$sqlcolAvatar]] = '<span id="pic-inuse"></span>';
    }


    $request_here = request_uri();
    $username = constant("USER_NAME");
    $sector_folder = "users/" . $username;
    $destination = kernel::base_tag_folder_filter("{publicfiles}{users}/{$username}/{thumbs}/");
    $destination_read = ROOT . $destination;
    $destination_show = HOST . $destination;


    $arrFiles = dir_read($destination_read);

    if (is_array($arrFiles)) {
        foreach ($arrFiles as $images_in) {
            $value = str_replace($destination_read, "", $images_in);

            $id = crc32($value);

            $img_in .= <<<feol
<div class="users-picture">

  <center><input type="radio" name="avatar" value="{$value}" id="{$id}"  {$selected[$value]} />{$inuse[$value]} </center><br />

  <label  for="{$id}">
  <img src="{$destination_show}{$images_in}" border="0">
 </label>

</div>
feol;
        }
    } else {
        $img_in .='<div class="browse-file" id="users-picture-browse-file">';
        $img_in .=" {$language['lw.empty']} !</div>";
    }

    $request = kernel::base_tag_folder_filter("{host}{$CONFIGURATION['websiteUploads']}");

    $html .=<<<eol

 <div class="users-picture-browse">
 <br />
 <p>
 <div class="users-picture-picker title">{$language['users.revw.piclickr']}</div>
 <form name="chs_image" id="chi" method="post" target="_self">
 <center>
 <table width="80%" border="0" align="center" cellspacing="0" cellpadding="0">
 <tr>
  <td width="%">&nbsp;</td>
 <td align="center">
 <span>{$img_in} </span>
<td width="%">&nbsp;</td>
</td></tr>
 </table>
 </center>
 <div class="users-picture-space"></div>
 <center>
 <input type="submit" name="save_avatar" id="button" value="{$language['lw.save']}">
 </center>
 </form>
</p>

 </div>
 <div class="users-picture-space"></div>
 <script>
 $(function(){
        $("#image").change(function(){
        window.location='$request_here';
        });
  });
  </script>
<input type="hidden" value="" id="image" />
 <p align="center">
 <div class="title">{$language['users.revw.picuplod']}</div>
 <p>
<div class="users-picture-description">{$language['users.revw.picdescr']}</div></p><br />
  <center>
<IFRAME src="{$request}?d={$sector_folder}&exec=image&reload=true" align="center" frameborder="0" height="320px" width="85%" name="upload"
style="box-shadow: 0 0 10px #333;border:solid 4px #fff;" style="margin-left:auto !important;margin-right:auto !important;">
</IFRAME>
 </center>
 </p>
eol;
} else {

    theme::content(dp_show_responses(401, $language['lan.need.users']));
}


theme::content(array($language['users.avatar'], $html));
