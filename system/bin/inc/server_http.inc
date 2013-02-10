<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: server_http.inc 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * Configuration HTTP need to be loaded on every Dollop request.
 */
/**
 * This function will define real website HOST
 * @author  Angel Zaprianov <fire1@abv.bg>
 * @return  init|string Will return HOST
 */
function server_http_host() {
    $httphost = $_SERVER['HTTP_HOST'];
    $iphost = gethostbyname($_SERVER['HTTP_HOST']);
    if (empty($iphost)) {
        return false;
    } else {
        if (!defined("_SERVER_HOST")) define('_SERVER_HOST', $httphost);;
        if (!defined("HOST")) define('HOST', "http://" . $httphost . "/");;
        return $httphost;
    }
}
server_http_host(); //execute function

/**
 * This function will define real IP
 *
 * @author  Angel Zaprianov <fire1@abv.bg>
 * @return  init|string Will return Real IP or false
 */
function get_user_ip() {
    if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ipaddress3)) {
            $ipaddress2 = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10..*/', '/^224..*/', '/^240..*/');
            $ipaddress = preg_replace($ipaddress2, $ipaddress, $ipaddress3[1]);
        }
    } else {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }
    if ($ipaddress == "") {
        return false;
    }
    if (!defined("_SERVER_usrIP")) define('_SERVER_usrIP', $ipaddress);;
    return $ipaddress;
}
get_user_ip(); //execute function
