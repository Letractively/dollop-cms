<?php

/**
  ============================================================
 * Last committed:      $Revision: 126 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-22 17:58:47 +0200 (ïåò, 22 ìàðò 2013) $
 * ID:                  $Id: generate.php 126 2013-03-22 15:58:47Z fire $
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
 * @subpackage Module
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
error_reporting(0);
$GLOBALS['THEME'] = "";

/**
 *  Class that generate js,jquery,json and css file
 *  @version $version
 */
class generated_file {

    //var $version=1.01;
    private $type = null;
    private $header = array("js" => " application/javascript", "jquery" => " application/javascript", "css" => "text/css", "json" => " application/json");

    public function generated_file() {
        $this->grip_request();
        $this->send_header();
        $this->operation_type();
    }

    /**
     * Catch file type from request
     *
     */
    private function grip_request() {
        global $KERNEL_PROP_MAIN;
        $aval_types = $KERNEL_PROP_MAIN['kernel.externelFile.types'];
        $key = array_keys($_GET);
        if (in_array($key[0], $aval_types)) {
            $this->type = $key[0];
        }
    }

    /**
     *  Operate with file types
     *
     */
    private function operation_type() {
        if (is_null($this->type))
            return false;;
        global $kernel;
        $content = $kernel->SESSION($_GET[$this->type]);
        $GLOBALS['THEME'] = $content;
        //   $kernel->SESSION_CILL($_GET[$this->type]);
    }

    /**
     * Sending in header type and charset
     *
     */
    private function send_header() {
        global $SQL_WEBSITE, $KERNEL_PROP_MAIN;
        $chachetime = $KERNEL_PROP_MAIN['kernel.cacheTime'] * 60 * 60;
        @header_remove("content-type");
        @ob_end_clean();
        header("Cache-Control: must-revalidate, max-age={$chachetime}, s-maxage={$chachetime}, private");
        //header('HTTP/1.1 304 Not Modified');

        if (is_null($this->type))
            return false;;
        $content_type = $this->header[$this->type];
        header("Content-Type: {$content_type}; charset={$SQL_WEBSITE['charset']} ");
    }

}

$generated_file = new generated_file();
