<?php

/**
  ============================================================
 * Last committed:      $Revision: 135 $
 * Last changed by:     $Author: fire1 $
 * Last changed date:   $Date: 2013-04-19 12:50:06 +0300 (ïåò, 19 àïð 2013) $
 * ID:                  $Id: html.form.field.inc 135 2013-04-19 09:50:06Z fire1 $
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
 * @filesource Main 
 * @package dollop kernel
 * @subpackage functions
 * 
 */
if (!defined('FIRE1_INIT')) {
    @header_remove();
    header("location:error400");
    exit();
}

/**
 *  Generate input for form
 *    
 * @param mixed $type        //type of input
 * @param mixed $name        //name of input
 * @param mixed $def_val     //value of input
 * @param mixed $attr        // other data (exp: id="my_inpt")
 * @param mixed $rows_cols   // for textarea (exp: $rows_cols= array(2,20); )
 */
function html_form_input($type, $name, $def_val = "", $attr = "", $rows_cols = array()) {

    switch ($type) {

        case 'text':
            $def_val = trim($def_val);
            return <<<html
    <input type="text" name="{$name}" value="{$def_val}" {$attr}/>
html;
            break;

        case 'password':
            $def_val = trim($def_val);
            return <<<html
    <input type="password" name="{$name}" value="{$def_val}" {$attr}/>
html;
            break;

        case 'checkbox':

            $options = explode("\n", $def_val);
            $option = "";
            $i = 0;
            $selected = html_checkbox_vals($attr, FALSE);
            $attr = null;
            foreach ($options as $optn) {
                $optn = trim($optn);
                if (is_array($selected)) {
                    $selected = array_map("trim", $selected);
                    if (in_array($optn, $selected)) {
                        $attr = 'checked="checked"';
                    }
                } elseif (trim($selected) == $optn) {
                    $attr = 'checked="checked"';
                } else {
                    $attr = null;
                }
                $option .= <<<html
            
    <input type="checkbox" name="{$name}[]" value="{$optn}" {$attr} id="{$name}_{$i}"/>
    <label for="{$name}_{$i}">$optn</label><br />
html;
                $i++;
                $attr = null;
            }
            return $option;
            break;

        case 'radio':

            $options = explode("\n", $def_val);
            $option = "";
            $i = 0;
            $selected = $attr;
            foreach ($options as $optn) {
                $optn = trim($optn);
                if ($selected == $optn) {
                    $attr = 'checked="checked"';
                } else {
                    $attr = null;
                }
                $option .= <<<html
            
    <input type="radio" name="{$name}" value="{$optn}" {$attr} id="{$name}_{$i}"/>
    <label for="{$name}_{$i}">$optn</label><br />
html;
                $i++;
            }
            return $option;
            break;

        case 'hidden':
            $def_val = trim($def_val);
            return <<<html
    <input type="hidden" name="{$name}" value="{$def_val}" {$attr}/>
html;
            break;

        case 'image':
            return <<<html
    <input type="image" src="{$name}" alt="{$def_val}" {$attr}/>
html;
            break;

        case 'select':

            $options = explode("\n", $def_val);
            $option = "";
            $selected = $attr;
            foreach ($options as $optn) {
                if ($selected == $optn) {
                    $attr = 'selected="selected"';
                } else {
                    $attr = null;
                }
                $optn = trim($optn);
                $option .= '            <option value="' . $optn . '" ' . $attr . '>' . $optn . '</option>';
            }

            return <<<html
    <select name="{$name}" >
{$option}
    </select>
html;
            break;

        case 'textarea':
            if (!is_array($rows_cols)) {
                $rows_cols[0] = '2';
                $rows_cols[1] = '10';
            }
            return <<<html
<textarea rows="{$rows_cols[0]}" cols="{$rows_cols[1]}" name="{$name}" {$attr}>{$def_val}</textarea>
html;
            break;

        DEFAULT:
            return <<<html
    <input type="{$type}" name="{$name}" alt="{$def_val}" {$attr}/>
html;
            break;
    }
}

/**
 * Give mysql Row type ad will extract maxlength for input
 * @example html_maxlength("int(30)");
 * @param type $row_type type of mysql cel
 * @return null
 */
function html_maxlength($row_type) {
    if (is_numeric($limit = filter_var($row_type, FILTER_SANITIZE_NUMBER_INT))) {
        $max = "maxlength=\"$limit\"";
    } else {
        $max = null;
    }
    return $max;
}

/**
 * declaring the value of checkbox
 * @param type $value
 * @param type $ins
 * @param type $CustomDec
 * @return type
 */
function html_checkbox_vals($value, $ins = TRUE, $Spliter = "&#44;") {

    if ((bool) $ins) {
        if (is_array($value)) {
            $out = implode($Spliter, $value);
        } else {
            $out = $value;
        }
        return $out;
    } else {
        $test = explode($Spliter, $value);
        if (is_array($test)) {
            return $test;
        } else {
            return $value;
        }
    }
}

/**
 * Check on method post for sub arrays 
 * and convert the to implodet val 
 * $_POST
 * @return string
 */
function check_post_checkbox() {
    foreach ($_POST as $k => $v) {
        if (is_array($v)) {
            $_POST[$k] = html_checkbox_vals($v);
        }
    }
}

