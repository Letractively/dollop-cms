<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: fix_gpc_magic.inc 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * ----------------------------------------------------------
 *       See COPYRIGHT and LICENSE
 * ----------------------------------------------------------
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
/**
 * @filesource
 * Functions that need to be loaded on every Dollop request.
 */
function _fix_gpc_magic(&$item) {
    if (is_array($item)) {
	array_walk($item, '_fix_gpc_magic');
    } else {
	$item = stripslashes($item);
    }
}
/**
 * Helper function to strip slashes from $_FILES skipping over the tmp_name keys
 * since PHP generates single backslashes for file paths on Windows systems.
 *
 * tmp_name does not have backslashes added see
 * http://php.net/manual/en/features.file-upload.php#42280
 */
function _fix_gpc_magic_files(&$item, $key) {
    if ($key != 'tmp_name') {
	if (is_array($item)) {
	    array_walk($item, '_fix_gpc_magic_files');
	} else {
	    $item = stripslashes($item);
	}
    }
}
/**
 * Fix double-escaping problems caused by "magic quotes" in some PHP installations.
 */
function fix_gpc_magic() {
    if (ini_get('magic_quotes_gpc')) {
	array_walk($_GET, '_fix_gpc_magic');
	array_walk($_POST, '_fix_gpc_magic');
	array_walk($_COOKIE, '_fix_gpc_magic');
	array_walk($_REQUEST, '_fix_gpc_magic');
	array_walk($_FILES, '_fix_gpc_magic_files');
	$fixed = TRUE;
    }
}
/**
 * execute the file fix_gpc_magic
 */
fix_gpc_magic();
?>