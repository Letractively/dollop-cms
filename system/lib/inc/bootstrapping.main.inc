<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: bootstrapping.main.inc 115 2013-02-08 16:27:29Z fire $
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
/**
 * The current system version.
 */
define('VERSION', '4.0');
/**
 * Core API compatibility.
 */
define('DOLLOP_CORE_COMPATIBILITY', '3.x');
/**
 * Minimum supported version of PHP.
 */
define('DOLLOP_MINIMUM_PHP', '5.2.4');
/**
 * Minimum recommended value of PHP memory_limit.
 */
define('DOLLOP_MINIMUM_PHP_MEMORY_LIMIT', '32M');
/**
 * The type of language used to define the content language.
 */
define('LANGUAGE_TYPE_CONTENT', 'language_content');
/**
 * The string for LANGUAGE in ".prop" files .
 */
define('VARPROP.LANGUAGE', 'language');
/**
 * The type of language used for URLs.
 */
define('LANGUAGE_TYPE_URL', 'language_url');
/**
 * Language written left to right. Possible value of $language->direction.
 */
define('LANGUAGE_LTR', 0);
/**
 * Language written right to left. Possible value of $language->direction.
 */
define('LANGUAGE_RTL', 1);
/**
 * For convenience, define a short form of the request time global.
 */
define('REQUEST_TIME', $_SERVER['REQUEST_TIME']);

/**
 * Start the timer with the specified name. If you start and stop the same
 * timer multiple times, the measured intervals will be accumulated.
 *
 * @param name
 *   The name of the timer.
 */
function timer_start($name) {
    global $timers;
    $timers[$name]['start'] = microtime(TRUE);
    $timers[$name]['count'] = isset($timers[$name]['count']) ? ++$timers[$name]['count'] : 1;
}

/**
 * Read the current timer value without stopping the timer.
 *
 * @param name
 *   The name of the timer.
 * @return
 *   The current timer value in ms.
 */
function timer_read($name) {
    global $timers;
    if (isset($timers[$name]['start'])) {
        $stop = microtime(TRUE);
        $diff = round(($stop - $timers[$name]['start']) * 1000, 2);
        if (isset($timers[$name]['time'])) {
            $diff+= $timers[$name]['time'];
        }
        return $diff;
    }
    return $timers[$name]['time'];
}

/**
 * Stop the timer with the specified name.
 *
 * @param name
 *   The name of the timer.
 * @return
 *   A timer array. The array contains the number of times the timer has been
 *   started and stopped (count) and the accumulated timer value in ms (time).
 */
function timer_stop($name) {
    global $timers;
    if (isset($timers[$name]['start'])) {
        $stop = microtime(TRUE);
        $diff = round(($stop - $timers[$name]['start']) * 1000, 2);
        if (isset($timers[$name]['time'])) {
            $timers[$name]['time']+= $diff;
        } else {
            $timers[$name]['time'] = $diff;
        }
        unset($timers[$name]['start']);
    }
    return $timers[$name];
}

/**
 * Loading ".inc" files from build.prop
 *
 * @example build_inc(system/lib/inc/); // in this inc folder is build.prop
 *
 * @param mixed $buildinc path to folder
 * @param mixed $subfolder if files are subfolder
 * @return array what its loaded
 */
function build_inc($buildinc, $subfolder = 0) {
    if ($subfolder) {
        $sub = $subfolder . "/";
    }
    $var = parse_ini_file(ROOT . $buildinc . "build.prop");
    $toload = count($var);
    $i = 0;
    foreach ($var as $key => $value) {
        $file = $buildinc . "/" . @$sub . $key . ".inc";
        if ($value == 2) {
            $i++;
            require_once ($file);
        } elseIf ($value == 1) {
            $i++;
            @include_once ($file);
        }
    }
    return array('all' => $toload, 'load' => $i);
}

/**
 * Check and valid secure host
 *
 * @param mixed $host
 */
function dp_valid_http_host($host) {
    return preg_match('/^\[?(?:[a-zA-Z0-9-:\]_]+\.?)+$/', $host);
}

/**
 * Create name of trunk temp folder
 *
 */
function trunk_temp() {
    global $KERNEL_PROP_MAIN;
    if (!defined("TRUNK_TEMP")) {
        define("TRUNK_TEMP", md5($KERNEL_PROP_MAIN['kernel.trunkDir'] . constant("HEX")));
    }
    return constant("TRUNK_TEMP");
}

function request_path() {
    static $path;
    if (isset($path)) {
        return $path;
    }
    if (isset($_GET['q']) && is_string($_GET['q'])) {
        // This is a request with a ?q=foo/bar query string. $_GET['q'] is
        // overwritten in drupal_path_initialize(), but request_path() is called
        // very early in the bootstrap process, so the original value is saved in
        // $path and returned in later calls.
        $path = $_GET['q'];
    } elseif (isset($_SERVER['REQUEST_URI'])) {
        // This request is either a clean URL, or 'index.php', or nonsense.
        // Extract the path from REQUEST_URI.
        $request_path = strtok($_SERVER['REQUEST_URI'], '?');
        $base_path_len = strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/'));
        // Unescape and strip $base_path prefix, leaving q without a leading slash.
        $path = substr(urldecode($request_path), $base_path_len + 1);
        // If the path equals the script filename, either because 'index.php' was
        // explicitly provided in the URL, or because the server added it to
        // $_SERVER['REQUEST_URI'] even when it wasn't provided in the URL (some
        // versions of Microsoft IIS do this), the front page should be served.
        if ($path == basename($_SERVER['PHP_SELF'])) {
            $path = '';
        }
    } else {
        // This is the front page.
        $path = '';
    }
    // Under certain conditions Apache's RewriteRule directive prepends the value
    // assigned to $_GET['q'] with a slash. Moreover we can always have a trailing
    // slash in place, hence we need to normalize $_GET['q'].
    $path = trim($path, '/');
    return $path;
}

function dollop_environment_initialize() {
    if (!isset($_SERVER['HTTP_REFERER'])) {
        $_SERVER['HTTP_REFERER'] = '';
    }
    if (!isset($_SERVER['SERVER_PROTOCOL']) || ($_SERVER['SERVER_PROTOCOL'] != 'HTTP/1.0' && $_SERVER['SERVER_PROTOCOL'] != 'HTTP/1.1')) {
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';
    }
    if (isset($_SERVER['HTTP_HOST'])) {
        // As HTTP_HOST is user input, ensure it only contains characters allowed
        // in hostnames. See RFC 952 (and RFC 2181).
        // $_SERVER['HTTP_HOST'] is lowercased here per specifications.
        $_SERVER['HTTP_HOST'] = strtolower($_SERVER['HTTP_HOST']);
        if (!dp_valid_http_host($_SERVER['HTTP_HOST'])) {
            // HTTP_HOST is invalid, e.g. if containing slashes it may be an attack.
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
            exit;
        }
    } else {
        // Some pre-HTTP/1.1 clients will not send a Host header. Ensure the key is
        // defined for E_ALL compliance.
        $_SERVER['HTTP_HOST'] = '';
    }
    // When clean URLs are enabled, emulate ?q=foo/bar using REQUEST_URI. It is
    // not possible to append the query string using mod_rewrite without the B
    // flag (this was added in Apache 2.2.8), because mod_rewrite unescapes the
    // path before passing it on to PHP. This is a problem when the path contains
    // e.g. "&" or "%" that have special meanings in URLs and must be encoded.
    // $_GET['q'] = request_path();
    // $_SERVER['REQUEST_URI'] = request_path();
    // Enforce E_ALL, but allow users to set levels not part of E_ALL.
    error_reporting(E_ALL ^ E_NOTICE | error_reporting());
    // Override PHP settings required for Dollop to work properly.
    // The .htaccess file contains settings that cannot be changed at runtime.
    // Don't escape quotes when reading files from the database, disk, etc.
    ini_set('magic_quotes_runtime', '0');
    // Use session cookies, not transparent sessions that puts the session id in
    // the query string.
    ini_set('session.use_cookies', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_trans_sid', '0');
    // Don't send HTTP headers using PHP's session handler.
    ini_set('session.cache_limiter', 'none');
    // Use httponly session cookies.
    ini_set('session.cookie_httponly', '1');
    // Set sane locale settings, to ensure consistent string, dates, times and
    // numbers handling.
    setlocale(LC_ALL, 'C');
}

dollop_environment_initialize();
