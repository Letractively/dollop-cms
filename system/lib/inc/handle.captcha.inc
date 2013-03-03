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

/**
 * Captcha Secure
 *
 * @param type $user_input User input or empry to generate HTML captcha
 * @return boolean HTML content
 */
function captcha($user_input = null) {
    global $language;
    if (is_null($user_input)) {
        //
        // Get requred information
        $design = kernel::base_tag("{design}");
        $unicid = md5(uniqid());
        $html = <<<html

<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0" bgcolor="#ededed" style="padding:2px; border:solid 1px #ddd; vertical-align: bottom;" id="captcha-table">
  <tr>
    <td width="222" rowspan="2" valign="middle"><img src="/captcha?sid={$unicid}" border="0" align="left" alt="" id="cappic"  style="border:solid 1px #ddd;"/></td>
    <td width="0%" align="left">

  <object type="application/x-shockwave-flash" data="{$design}captcha/securimage_play.swf?bgcol=#ededed&amp;icon_file={$design}captcha/audio_icon.png&amp;audio_file=/captcha?securimage_play" height="32" width="32">
  <param name="movie" value="{$design}captcha/securimage_play.swf?bgcol=#ededed&amp;icon_file={$design}/captcha/audio_icon.png&amp;audio_file=/captcha?securimage_play" />
  </object>
</td>
  </tr>
  <tr>
    <td width="0%" align="left">

  <a tabindex="-1" style="border-style: none;" href="#cappic" title="Refresh Image"
onclick="document.getElementById('cappic').src = '/captcha?sid=' + Math.random(); this.blur(); return false">

  <img src="{$design}captcha/refresh.png" alt="Reload Image" height="32" width="32" align="bottom" border="0" /></a>
</td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="middle"><font color="#333">{$language['web.captcha']}</font></td>
  </tr>
</table>


html;
return $html;
    } else {
        $securimage = new captcha_securimage();
        //
        // Check the input
        if ($securimage->check($user_input) == false) {
            return false;
        } else {
            return true;
        }
    }
}
