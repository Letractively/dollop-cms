<?php
/**
 ============================================================
 * Last committed:     $Revision: 86 $
 * Last changed by:    $Author: fire $
 * Last changed date:    $Date: 2012-10-30 14:12:58 +0200 (вторник, 30 Октомври 2012) $
 * ID:       $Id: process.php 86 2012-10-30 12:12:58Z fire $
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
/**
 *
 * @filesource
 * chat process
 */
global $language;
function badlink($link, $prefix) {
    if ($prefix == "mailto:") {
        if (strpos($link, "@") === FALSE || strpos($link, ".", (strpos($link, "@") + 2)) === FALSE || substr_count($link, "@") > 1 || strpos($link, "@") == 0) {
            return 1;
        }
    }
    if (strpos($link, ".") == 0 || strpos($link, ".") == strlen($link) || (strpos($link, "/") < strpos($link, ".") && strpos($link, "/") !== FALSE)) {
        return 1;
    }
};
function setlinks($r, $prefix) {
    if (substr($r, 0, strlen($prefix)) == $prefix) {
        $r = "\n" . $r;
    }
    $r = str_replace("<br>" . $prefix, "<br>\n" . $prefix, $r);
    $r = str_replace(" " . $prefix, " \n" . $prefix, $r);
    while (strpos($r, "\n" . $prefix) !== FALSE) {
        list($r1, $r2) = explode("\n" . $prefix, $r, 2);
        if (strpos($r2, " ") === FALSE && strpos($r2, "<br>") === FALSE) {
            if ($prefix != "mailto:") {
                $target = ' target="_blank"';
            } else {
                $target = "";
            }
            if (strpos($r2, ".") > 1 && strpos($r2, ".") < strlen($r2) && badlink($r2, $prefix) != 1) {
                $r = $r1 . '<a href="' . $prefix . $r2 . '"' . $target . '><font size="2" color="blue">' . $prefix . $r2 . '</font></a>';
            } else {
                $r = $r1 . $prefix . $r2;
            }
        } else {
            if (strpos($r2, " ") === FALSE || (strpos($r2, " ") > strpos($r2, "<br>") && strpos($r2, "<br>") !== FALSE)) {
                list($r2, $r3) = explode("<br>", $r2, 2);
                if (badlink($r2, $prefix) != 1) {
                    $r = $r1 . '<a href="' . $prefix . $r2 . '"' . $target . '><font size="3" color="blue">' . $prefix . $r2 . '</font></a><br>' . $r3;
                } else {
                    $r = $r1 . $prefix . $r2 . '<br>' . $r3;
                }
            } else {
                list($r2, $r3) = explode(" ", $r2, 2);
                if (strpos($r2, ".") > 1 && strpos($r2, ".") < strlen($r2) && badlink($r2, $prefix) != 1) {
                    $r = $r1 . '<a href="' . $prefix . $r2 . '"' . $target . '><font size="3" color="blue">' . $prefix . $r2 . '</font></a> ' . $r3;
                } else {
                    $r = $r1 . $prefix . $r2 . ' ' . $r3;
                }
            }
        }
    }
    return $r;
};
if ($_GET['id'] && is_numeric($_GET['id'])) {
    $THEME = null;
    $mysql = new mysql_ai();
    if ($mysql->Select("chat_live", array("chat_chanel" => $_GET['id']), "`timestamp` ASC")) {
        if (!empty($mysql->aArrayedResults)) {
            $arrFilter = array("\n");
            $arrElement = array("<br />");
            foreach ($language['lchat.icon'] as $key => $val) {
                array_push($arrFilter, " {$key}");
                array_push($arrElement, '<img src="' . HOST . MODULE_DIR . 'emotions/' . $val . '.png" border="0" class="emotions" alt="' . $key . '" />');
            }
            foreach ($mysql->aArrayedResults as $row) {
                $text = str_replace($arrFilter, $arrElement, htmlspecialchars(stripslashes(($row['chat_message']))));
                $text = setlinks($text, "http://");
                $text = setlinks($text, "https://");
                $text = setlinks($text, "mailto:");
                $THEME.= <<<eol
                <div class="chat-message">
                <div class="chat-username">{$row['username']}:</div>
                <div class="chat-message-content">{$text}
                </div>
                </div>        
                
eol;
                
            }
        }
    } else {
        $mysql_error = mysql_error();
        $THEME = $language['lchat.ch.nomessage'] . $mysql_error;
    }
    $GLOBALS['THEME'] = $THEME;
} elseIf ($_GET['m'] && is_numeric($_GET['m'])) {
    $mysql = new mysql_ai();
    //$_POST['chat_message']=" ". addslashes( $_POST['chat_message']) ;
    if (empty($_COOKIE['username'])) {
        $username = $language['lchat.ch.guest'];
    } else {
        $username = $_COOKIE['username'];
    }
    $_POST['chat_chanel'] = $_GET['m'];
    $_POST['username'] = $username;
    if (!empty($_POST['chat_message']) && $mysql->Insert($_POST, "chat_live", array(""))) {
        $GLOBALS['THEME'] = false;
    } else {
        $GLOBALS['THEME'] = false;
    }
}
