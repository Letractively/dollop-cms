<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: kernel.handle.inc 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * @filesource Main Dollop
 * @package dollop kernel
 * @subpackage functions
 * 
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
/**
 *
 * @filesource
 * Kernel attach functions
 */

/**
 * Attach files from gived array
 *     Function is used by kernel
 * @param array $arrFileSrc
 */
function kernel_add_scripts($arrFileSrc) {
    if (!is_array($arrFileSrc))
        return false;;
    foreach ($arrFileSrc as $inc) {
        include ($inc);
    }
}

/**
 * Attach simple file source
 *
 * @param mixed $fileModule
 */
function kernel_include_script($fileModule) {
    if (file_exists($fileModule)) {
        eval("  require('$fileModule');");
    } else {
        if (!defined("WEBSITE_RESPONSES")) {
            define("WEBSITE_RESPONSES", 006);
        }
    }
}

/**
 * Combine Boot (master) with slaves (other prop files)
 *
 * @author krajcar.peter@gmail.com shadyyx (Peter Krajcár)
 *
 * @param mixed $master
 * @param mixed $slave
 * @return array
 */
function combine_master_with_slave($master, $slave) {
    foreach ($slave as $key => $val) { // we will loop through slave array
        if (is_array($val)) { // if the value is an array use recursion
            $slave[$key] = combine_master_with_slave($master, $val);
        }
        if (isset($master[$key])) { // if master array contains the same key as a slave array, replace it's value by the master's one
            $slave[$key] = $master[$key];
        }
    }
    return $slave; // finaly return the processed slave array
}

/**
 * put your comment there...
 *
 * @param mixed $sting
 */
function kernel_hash_handle($sting) {
    
}

/**
 * Prop Constants derived from the kernel
 *
 * @param mixed $val // Section in .prop file
 * @return prop key
 */
function propc($val) {
    return kernel::prop_constant($val);
}
