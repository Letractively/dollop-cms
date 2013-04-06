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
 * @filesource Menu LiveChat
 * @package Dollop
 * @subpackage LiveChat
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}


function livechat_emotions_show() {
    global $language;
    $text = null;
    $tplImg = '<a href="#" title="{code}"><img src="' . HOST . MODULE_DIR . 'emotions/{name}.gif" border="0"/></a>';

    //
    // Output
    if (is_array($language['lchat.icon'])) {
        foreach (array_unique($language['lchat.icon']) as $code => $name) {
            $text .= str_replace(array("{code}", "{name}"), array($code, $name), $tplImg);
        }
    }
    if (is_dir(ROOT . MODULE_DIR . "emotions/onion")) {
        $text .=<<<eol
                <div class="open_sub_emotions">&raquo</div>
                <div class="sub_emotions"><div class="close_sub_emotions">X</div>
eol;
        foreach (glob(ROOT . MODULE_DIR . "emotions/onion/*.gif") as $onion) {
            if (!is_null($text)) {
                $text .= str_replace(array("{code}", "{name}"), array("(" . pathinfo($onion, PATHINFO_FILENAME) . ")", "onion/" . pathinfo($onion, PATHINFO_FILENAME)), $tplImg);
            }
            $language['lchat.icon']["(" . pathinfo($onion, PATHINFO_FILENAME) . ")"] = "onion/" . pathinfo($onion, PATHINFO_FILENAME);
        }
        $text .=" </div>";
    }
    global $language;
    return $text;
}
