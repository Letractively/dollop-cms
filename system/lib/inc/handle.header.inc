<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: handle.header.inc 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * handle header data
 */
function meta_keywords($text = null) {
    if (is_null($text)) {
        // catch theme on exec of this function
        eval('$cont=$GLOBALS["THEME"];');
        if ((bool) $cont) {
            @preg_match("/<title>([a-z 0-9]*)<\/title>/si", $cont, $match);
            // tag contents
            $tags = strip_tags(@$match[1]);
        }
    } else {
        
    }
}

function meta_description($words) {
    
}

/**
 * Getting browser information
 *
 */
function _get_browser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }
    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }
    return array('userAgent' => $u_agent, 'name' => $bname, 'version' => $version, 'platform' => $platform, 'pattern' => $pattern);
}

/**
 * Save operation for header location use
 *
 * @param mixed $uri // request uri
 * @param bool $exit
 * @param bool $debug - it will create log_header_location in root dir !
 */
function header_location($uri = null, $exit = false, $debug = false) {
    global $BROWSER, $header_location;
    $b = _get_browser();
    if (empty($uri)) {
        $uri = request_uri();
    }
    if ($b['name'] != "Unknown" OR !empty($b['name'])) {
        $try = 3;
    } else {
        $try = 0;
    }

    if (is_array($HTTP_COOKIE_VARS)) {
        $chk = "true";
        if (session_id() != '' AND request_uri() == $uri) {
            if ((int) $_SESSION['header_location'] >= $try)
                return false;
            if ($uri == request_uri()) {
                (int) $_SESSION['header_location']++;
                ;
            }
        }
        if (!session_id()) {
            session_start();
        }
        if (isset($_SESSION['callback'])) {
            $uri = $_SESSION['callback'] . "#callbacked";
        }
        // standart redirect
        header("location: {$uri} ");
        if ((bool) $exit) {
            exit();
        }
        // checking for change of page
    } elseif (request_uri() == "/" OR request_uri() != $uri) {
        header("location: {$uri} ");
        $chk = "false";
        // execut header location only once
    } elseif ($header_location == 1) {
        if ($b['name'] != "Unknown" && $_SESSION['header_location'] <= 2) {
            header("location: {$uri} ");
        }
    }
    // counting in page
    (int) $header_location++;
    if ((bool) $debug)
        file_put_contents(ROOT . "log_header_location", "
                Session \t header_location:{$_SESSION['header_location']}\n
                Page \t header_location:{$header_location}\n \t \t OR \n
                The \t HTTP_COOKIE_VARS:{$chk}\n
                Request:{$uri}

                ");
}

function callback_clear() {
    if (!session_id()) {
        session_start();
    }
    if (isset($_SESSION['callback'])) {
        unset($_SESSION['callback']);
    }
}

/**
 * Sending to page in return the user.
 *
 * @param mixed $target uri request
 */
function callback($target) {
    if (!session_id()) {
        session_start();
    }
    $_SESSION['callback'] = kernel::base_tag($target);
}

function callback_users() {
    if (!session_id()) {
        session_start();
    }
    if (isset($_SESSION['callback'])) {
        if ($_SESSION['callback'] != request_uri()) {
            header_location($_SESSION['callback']);
        }
        //unset($_SESSION['callback']);
    }
}
