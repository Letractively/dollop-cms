<?php

/**
  ============================================================
 * Last committed:      $Revision: 133 $
 * Last changed by:     $Author: fire1 $
 * Last changed date:   $Date: 2013-04-02 20:13:15 +0300 (âò, 02 àïð 2013) $
 * ID:                  $Id: handle.main.inc 133 2013-04-02 17:13:15Z fire1 $
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
 * @filesource
 * Functions that need to be loaded on every Dollop request.
 */

/**
 * Handle denied users.
 *
 * @param $ip
 *   IP address to check. Prints a message and exits if access is denied.
 */
function dollop_block_denied($ip) {
    // Deny access to blocked IP addresses - t() is not yet available.
    if (dollop_is_denied($ip)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        print 'Sorry, ' . check_plain(ip_address()) . ' has been banned.';
        exit();
    }
}

/**
 * Since $_SERVER['REQUEST_URI'] is only available on Apache, we
 * generate an equivalent using other environment variables.
 */
function request_uri() {
    if (isset($_SERVER['REQUEST_URI'])) {
        $uri = $_SERVER['REQUEST_URI'];
    } else {
        if (isset($_SERVER['argv'])) {
            $uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['argv'][0];
        } elseif (isset($_SERVER['QUERY_STRING'])) {
            $uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
        } else {
            $uri = $_SERVER['SCRIPT_NAME'];
        }
    }
    // Prevent multiple slashes to avoid cross site requests via the Form API.
    $uri = '/' . ltrim($uri, '/');
    return $uri;
}

/**
 * Conver request_uri to array.
 * @return array array("tree","request");
 */
function request_uri_tree() {
    $srch = array("&", "=", ":");
    $uri = explode("?", request_uri());
    $tree = explode("/", @$uri[0]);
    $uri[1] = str_replace($srch, "{|-exPlodE-|}", @$uri[1]);
    $request = explode("{|-exPlodE-|}", @$uri[1]);
    return array("tree" => $tree, "request" => $request);
}

/**
 * switch files from URI request
 *
 * @param mixed $switch      // switcher
 * @param mixed $case        // case
 * @param mixed $file        // file to include
 */
function switch_uri($switch, $case, $file = "", $tree_type = 1) {
    $request = request_uri_tree();
    $tree_switch = $request['tree'][($tree_type + 1)];
    $tree_case = $request['tree'][($tree_type + 2)];
    if ($tree_switch == $switch) {
        if ($tree_case == $case) {
            if (!empty($file)) {
                include_once ($file);
            }
            return true;
        }
        return false;
    }
}

/**
 * fix dir path
 *
 * @param mixed $path
 * @return string fixed path
 */
function fixPath($path) {
    return dirname($path . '/.');
}

/**
 * Strip  ALL Slashes
 *
 * @param string $string
 * @return striped data
 *
 */
function stripallslashes($string) {
    while (strchr($string, '\\')) {
        $string = stripcslashes($string);
    }
    return $string;
}

/**
 * Using stripslashes() on an array
 *
 * @param string / array $value
 * @return value
 */
function stripslashes_deep($value) {
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripcslashes($value);
    return $value;
}

function stripallslashes_deep($value) {
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripallslashes($value);
    return $value;
}

/**
 * Get id from url by and key value
 *
 * @author  Angel Zaprianov <fire1@abv.bg>
 * @param string $GET scing key.
 * @return string Will return value of $_GET string and $_GET['id'] if is numeric.
 *
 */
function _GET($GET) {
    if (!defined("dp_URL_EXTENSION")) {
        $ext = array(":", "%3A");
    } else {
        $ext = explode("/", constant("dp_URL_EXTENSION"));
    }
    $sect_articles = str_replace($ext, "{ExplodE}", $_GET[$GET]);
    $sect_articles = explode("{ExplodE}", $sect_articles);
    if (!empty($sect_articles[1]) && is_numeric($sect_articles[1])) {
        $_GET['id'] = $sect_articles[1];
    }
    $_GET[$GET] = $sect_articles[0];
    return $sect_articles[0];
}

/**
 * redirect from header
 *
 * @param mixed $to // destination
 */
function redirect($to = "") {
    If (!headers_sent()) {
        header("location: " . $to);
    }
}

/**
 * read dir and return Array
 *
 * @param mixed $dir // destination of dir
 */
function dir_read($dir) {
    $arr = "";
    if (!is_dir($dir))
        return false;
    if ($handle = opendir($dir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && $entry != ".htaccess") {
                $arr[] = "$entry";
            }
        }
        closedir($handle);
    }
    if (is_array($arr)) {
        return $arr;
        ;
    } else
        return false;;
}

/**
 * adds http in link if doesn't have
 *
 * @param mixed $url //link url
 */
function link_http_fix($url) {
    if (strpos($url, "http") === 0) {
        $link_url = $url;
    } else {
        $link_url = constant("HOST") . $url;
    }
    return $link_url;
}

/**
 * Function that search in array values for match
 *
 * @param mixed $need    // need like this value
 * @param mixed $array   // array with values
 * @param boolean        // how to return (Array or Values)
 * @return Array/Values
 */
function search_array($need, $array, $ra = null) {
    if (is_array($array)) {
        foreach ($array as $position => $key) {
            $part_key = substr($key, 0, strlen($need));
            if ($part_key == $need) {
                if (!@$ra) {
                    $return.= $key;
                } else {
                    $return[] = $key;
                }
            }
        }
    } else {
        return false;
        ;
    }
    return $return;
}

/**
 * Format bytes to kilobytes, megabytes, gigabytes
 *
 * @param mixed $bytes
 * @param mixed $precision
 */
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    // Uncomment one of the following alternatives
    $bytes/= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Mysql set charset
 *
 * @param mixed $db // connection
 */
function mysql_charset($db) {
    $varCharset = kernel::base_tag_folder_filter("{sqlcharset}");
    mysql_set_charset($varCharset, $db);
}

/**
 * It will fix multiadd slashes
 *
 * @param string $input // input string values
 *
 *
 */
function addslashes_once($input) {
    //These characters are single quote ('), double quote ("), backslash (\) and NUL (the NULL byte).
    $pattern = array("\\'", "\\\"", "\\\\", "\\0");
    $replace = array("", "", "", "");
    if (preg_match("/[\\\\'\"\\0]/", str_replace($pattern, $replace, $input))) {
        return addslashes($input);
    } else {
        return $input;
    }
}

/**
 * Adding slashes and mixed
 *
 * @param mixed $value
 *
 */
function addslashes_once_deep($value) {
    $value = is_array($value) ? array_map('addslashes_once_deep', $value) : addslashes_once($value);
    return $value;
}

function remove_magic_quotes($array) {
    foreach ($array as $k => $v) {
        if (is_array($v)) {
            $array[$k] = remove_magic_quotes($v);
        } else {
            $array[$k] = stripslashes($v);
        }
    }
    return $array;
}

function smartstripslashes($str) {
    $cd1 = substr_count($str, "\"");
    $cd2 = substr_count($str, "\\\"");
    $cs1 = substr_count($str, "'");
    $cs2 = substr_count($str, "\\'");
    $tmp = strtr($str, array("\\\"" => "", "\\'" => ""));
    $cb1 = substr_count($tmp, "\\");
    $cb2 = substr_count($tmp, "\\\\");
    if ($cd1 == $cd2 && $cs1 == $cs2 && $cb1 == 2 * $cb2) {
        return strtr($str, array("\\\"" => "\"", "\\'" => "'", "\\\\" => "\\"));
    }
    return $str;
}

/**
 * function that prevents double-quoting
 *
 * @param mixed $receive
 */
function escape_quotes($receive) {
    if (!is_array($receive))
        $thearray = array($receive);
    else
        $thearray = $receive;
    foreach (array_keys($thearray) as $string) {
        $thearray[$string] = stripallslashes($thearray[$string]);
        $thearray[$string] = addslashes($thearray[$string]);
    }
    if (!is_array($receive))
        return $thearray[0];
    else
        return $thearray;
}

/**
 * slashes  handles multidimensional arrays
 *
 * @param mixed $arr_r
 */
function adsl_array(&$arr_r, $mres = true) {
    if (get_magic_quotes_gpc()) {
        $_GET = remove_magic_quotes($_GET);
        $_POST = remove_magic_quotes($_POST);
        $_COOKIE = remove_magic_quotes($_COOKIE);
    }
    if ((bool) $mres) {
        foreach ($arr_r as & $val)
            is_array($val) ? adsl_array($val, $mres) : $val = smartstripslashes(mysql_real_escape_string($val));
    } else {
        foreach ($arr_r as & $val)
            is_array($val) ? adsl_array($val) : $val = smartstripslashes($val);
    }
    unset($val);
}

/**
 *  Get type of website http or https and it wil return avalible
 *
 * @return string domain with secure type or without
 *
 */
function site_urltype() {
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];
    return $protocol . $domainName;
}

/*
 *
 * @param string $http_type // Insert your type like: http/https
 * @return boolean
 */

function check_secure_http($http_type) {
    switch ($http_type) {
        /*
          default:

          return ($_SERVER['HTTPS'] != 'off' || $_SERVER['SERVER_PORT'] == 443) ? true : false;


          break;

         */
        case "http":
            return ($_SERVER['HTTPS'] != 'off' || $_SERVER['SERVER_PORT'] == 443) ? true : false;
            break;
        case "https":
            return ($_SERVER['HTTPS'] != 'off' || $_SERVER['SERVER_PORT'] == 443) ? false : true;
            break;
    }
}

/**
 * Delete all cookies with maximum time used by karnel
 *
 * @param string $redirect // redirect to uri
 *
 */
function unset_all_cookies($redirect = null) {
    global $KERNEL_PROP_MAIN;
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach ($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time() - $KERNEL_PROP_MAIN['MaxCookieTime']);
            setcookie($name, '', time() - $KERNEL_PROP_MAIN['MaxCookieTime'], '/');
        }
    }
    //
    // Backup option, But this is Dollop's type.
    if (is_array($_COOKIE)) {
        foreach ($_COOKIE as $name => $val) {
            setcookie($name, NULL, time() - $KERNEL_PROP_MAIN['MaxCookieTime']);
            setcookie($name, NULL, time() - $KERNEL_PROP_MAIN['MaxCookieTime'], '/', parse_url(constant("HOST"), PHP_URL_HOST), check_secure_http('https'), check_secure_http('http'));
        }
    }
    header_location($redirect);
}
/**
 * Truncate content
 * @param	string	$text	Truncate input content
 * @param	init	$length	Numbers
 * @param	string	$suffix	Ending with ...
 * @param	Boolean	$isHTML	Is it HTML or not
 * @return	String			With Content that is truncated
 */
  function truncate_content($text, $length, $suffix = '&hellip;', $isHTML = true) {
    $i = 0;
    $simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
    $tags = array();
    if($isHTML){
        preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach($m as $o){
            if($o[0][1] - $i >= $length)
                break;
            $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
            // test if the tag is unpaired, then we mustn't save them
            if($t[0] != '/' && (!isset($simpleTags[$t])))
                $tags[] = $t;
            elseif(end($tags) == substr($t, 1))
                array_pop($tags);
            $i += $o[1][1] - $o[0][1];
        }
    }

    // output without closing tags
    $output = substr($text, 0, $length = min(strlen($text),  $length + $i));
    // closing tags
    $output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');

    // Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
    $pos = (int)end(end(preg_split('/<.*>| /', $output, -1, PREG_SPLIT_OFFSET_CAPTURE)));
    // Append closing tags to output
    $output.=$output2;

    // Get everything until last space
    $one = substr($output, 0, $pos);
    // Get the rest
    $two = substr($output, $pos, (strlen($output) - $pos));
    // Extract all tags from the last bit
    preg_match_all('/<(.*?)>/s', $two, $tags);
    // Add suffix if needed
    if (strlen($text) > $length) { $one .= $suffix; }
    // Re-attach tags
    $output = $one . implode($tags[0]);

    //added to remove  unnecessary closure
    $output = str_replace('</!-->','',$output);

    if(!(bool)$isHTML){
        $output = strip_tags($output);
    }
    return $output;
}