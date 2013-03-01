<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: handle.hash.inc 115 2013-02-08 16:27:29Z fire $
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
 * Handle the hashes of Dollop
 */

/**
 * Create passwords for website
 *
 * @param mixed $value
 * @param mixed $hex
 * @return string hash
 */
function password($value, $hex = null) {
    if (is_null($hex)) {
        $hex = constant("HEX");
    }
    return md5("#$hex-$value!");
}

/**
 * put your comment there...
 *
 * @param mixed $value
 * @param mixed $hex
 * @return string hash
 */
function hash_key($value, $hex = null) {
    if (is_null($hex)) {
        $hex = constant("HEX");
    }
    return "$hex-{$value}-";
}

/**
 * MySQL hash generator (this is used in user table)
 *
 * @param mixed $username - username
 * @return part of mysql query
 */
function mysql_hash($username, $timestamp = "UNIX_TIMESTAMP(NOW())") {
    $hex = md5(HEX);
    $hash_key = "$hex-{$username}-";
    return "PASSWORD( '{$hash_key}' + {$timestamp} )";
}

/**
 * Unix TimeStamp
 *
 *
 * @return mysql query
 */
function mysql_utime() {
    return "UNIX_TIMESTAMP( NOW() )  ";
}

/**
 * Password hashing should be done only with crypt and NEVER
 * with SHA* and MD5 or hash(). The fundamental reason is
 * that crypt is designed to be SLOW which is a VERY good
 * thing for password hashing.
 *
 * @param mixed $password - crypt/ed password
 * @param mixed $user_input - check the password
 * @returns bool / hash
 */
function md5crypt($password, $user_input = null) {
    // create a salt that ensures crypt creates an md5 hash
    $base64_alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . 'abcdefghijklmnopqrstuvwxyz0123456789+/';
    $salt = '$1$';
    for ($i = 0; $i < 9; $i++) {
        $salt.= $base64_alphabet[rand(0, 63)];
    }
    if (is_null($user_input)) {
        // return the crypt md5 password
        return crypt($password, $salt . '$');
    } else {
        if (crypt($user_input, $password) == $password) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Fixed function for negative result
 * integers on 32bit platforms
 *
 * @param mixed $value
 * @return hash
 */
function crc32_($value) {
    // will return only positive integers though
    return sprintf("%u", crc32($value . HEX));
}
