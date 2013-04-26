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
 * Dollop Users Privilegeis
 *
 * @global type $USERS_PRIVILEGE    System Privilege array
 * @global type $language           System Text array
 * @param type $type                Ouput Type (options,privilege,group,current,cpanel)
 * @param type $current             If type is options will check for current use
 * @param type $group               Options will output a group
 * @return string|boolean
 */
function users_privilege($type = "options", $current = null, $start = false, $end = false) {
    global $USERS_PRIVILEGE, $language;

    switch ($type) {
        case "options":
            if (!(bool) $options) {
                $options = null;

                $stamp_admin = "\n          </optgroup>
            <optgroup label=\"" . ucfirst($language['lw.admins']) . "\">";
                $group_users = "\n          <optgroup label=\"" . ucfirst($language['main.cp.users']) . "\">";
                $groupending = "\n          </optgroup>";
                if (is_numeric($start) || is_numeric($end)) {
                    $arrays = $USERS_PRIVILEGE['users.privilege'];
                    foreach ($arrays as $lv => $nm) {
                        if (is_numeric($start)) {
                            if ($start <= $lv) {
                                $array[$lv] = $nm;
                            }
                        }
                        if (is_numeric($end)) {
                            if ($end >= $lv && $start <= $lv) {
                                $array[$lv] = $nm;
                            }
                        }
                    }
                } else {
                    $array = $USERS_PRIVILEGE['users.privilege'];
                }

                if (!is_array($array)) {
                    return false;
                }
                foreach ($array as $key => $name) {
                    if (!is_null($current)) {
                        if ((int) $key == (int) $current OR $name == $current) {
                            $slct = 'selected="selected"';
                        } else {
                            $slct = null;
                        }
                    }
                    $group = ($USERS_PRIVILEGE['users.cpanel'] - 1 >= $key) ? $group_users : $stamp_admin;

                    $disabling = (constant("USER_PRIV") <= $key) ? 'disabled="disabled"' : null;

                    if (is_numeric($group)) {

                    }

                    $options .=<<<eol
                    $group
                        <option value="$key" $slct $disabling>$name</option>
eol;



                    if ($USERS_PRIVILEGE['users.cpanel'] - 1 >= $key) {
                        $group_users = null;
                    } else {

                        $stamp_admin = null;
                    }
                }
                $options .=$groupending;
            }
            return $options;
            break;
        case "privilege":
            return $USERS_PRIVILEGE['users.privilege'];
            break;
        case "cpanel":
            return $USERS_PRIVILEGE['users.cpanel'];
            break;
        case "group":
            return $USERS_PRIVILEGE['users.group'];
            break;
        case "current":
            if (!defined("USER_PRIV_NAME"))
                return false;
            return array(constant("USER_PRIV"), constant("USER_PRIV_NAME"));
            break;
        default :
            return $USERS_PRIVILEGE;
            break;
    }
}

