<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: avatar.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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

        mysql_query("UPDATE `$sqltable` SET `$sqlcolAvatar`='{$_POST['avatar']}' WHERE `{$sqlcolID}`='$userid' ");
    }

    $sql = mysql_query("SELECT `$sqlcolAvatar` FROM `$sqltable` WHERE `$sqlcolID`='$userid' ") or die(mysql_error());
    if ($r = @mysql_fetch_array($sql)) {

        $selected[$r[$sqlcolAvatar]] = 'checked="checked"';
    }



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

  <center><input type="radio" name="avatar" value="{$value}" id="{$id}"  {$selected[$value]}></center><br />
  
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
 <form name="chs_image" id="chi" method="post" target="_self">
 {$img_in} 
 <div class="users-picture-space"></div>
 <center>
 <input type="submit" name="save_avatar" id="button" value="{$language['lw.save']}">
 </center>
 </form>

 
 </div> 
 <div class="users-picture-space"></div>
 
 <p align="center">
<IFRAME src="{$request}?d={$sector_folder}&exec=image" align="center" frameborder="0" height="320px" width="85%" name="upload" 
style="box-shadow: 0 0 10px #333;border:solid 4px #fff;">
</IFRAME>
 </p>
eol;
} else {

    theme::content(dp_show_responses(401, $language['lan.need.users']));
}


theme::content(array($language['users.avatar'], $html));
