<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: lister.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 *
 * @filesource
 * News sorceode
 */
class mysql_lister {
    private $count = 1;
    private $display;
    private $current;
    private $result;
    private $request;
    private $target;
    function __construct($query, $display = 10, $reqest = "lister") {
        $this->request = $reqest;
        $this->display = $display;
        if (!empty($query)) {
            $this->result = @mysql_query($query);
            $this->count = mysql_numrows($this->result);
        }
        if (empty($_GET[$this->request])) {
            $this->current = 1;
        } else {
            $this->current = + (int)$_GET[$this->request];
        }
    }
    private function href($url, $title) {
        return <<<eol
 <a href="{$url}" terget="_self">{$title}</a> 
eol;
        
    }
    /**
     * Filter for url request
     *
     * @param mixed $url
     * @param mixed $varname
     */
    private function removeqsvar($url, $varname) {
        return preg_replace('/([?&])' . $varname . '=[^&]+(&|$)/', '$1', $url);
    }
    /**
     * Making a URL for navigation
     *
     * @param $pages number
     * @param $title title of link
     *
     */
    private function page_url($pages, $title) {
        $clear = explode($this->request . "=", request_uri());
        $arrUrl = parse_url(request_uri());
        $split = (isset($arrUrl['query'])) ? "&" : "?";
        $newUrl = $this->removeqsvar(request_uri(), $this->request);
        return str_replace("&&", "&", $this->href($newUrl . $split . $this->request . "=" . $pages . $this->target, $title));
    }
    /**
     *     Display page Navigation
     *
     * @param int $speps steps fw / bw
     * @param boolen $terget
     */
    public function display_list($speps = 5, $terget = null) {
        if (!is_null($terget)) {
            $this->target = $terget;
        }
        $html = null;
        $show = ceil($this->current * $this->display) - $this->display;
        for ($page = $speps;$page > 0;$page--) {
            if ($this->current - $page >= 0 and $this->current - $page != 0) {
                $html_bk.= $this->page_url($this->current - $page, $this->current - $page);
            }
        }
        if ($this->current - 1 >= 1) {
            $html_bk_one = $this->page_url($this->current - 1, " <span>&#8249;</span> ");
        } else {
            $html_bk_one = " <span>&#8249;</span> ";
        }
        if ($this->current > 1) {
            $html_first = $this->page_url(1, " <span>&#8249;&#8249;</span> ");
        } else {
            $html_first = " <span>&#8249;&#8249;</span> ";
        }
        for ($page = 0;$page <= $speps;$page++) {
            if (($page + $this->current) * $this->display <= $this->count && $page + $this->current != $this->current) {
                $html_up.= $this->page_url($page + $this->current, $page + $this->current);
            }
        }
        if ((1 + $this->current) * $this->display <= $this->count) {
            $html_up_one = $this->page_url($this->current + 1, " <span>&#8250;</span> ");
        } else {
            $html_up_one = " <span>&#8250;</span> ";
        }
        if (ceil($this->count / $this->display) > $this->current) {
            $html_last = $this->page_url(ceil($this->count / $this->display), " <span>&#8250;&#8250;</span> ");
        } else {
            $html_last = " <span>&#8250;&#8250;</span> ";
        }
        return $html_first . $html_bk_one . $html_bk . "<b>" . $this->current . "</b>" . $html_up . $html_up_one . $html_last;
    }
    /**
     * Attach this in mysql_query funtion
     *
     * @example mysql_query("SELECT * FROM `table`". $mysql_lister->limit ());
     *
     */
    public function limit() {
        $show = ceil($this->current * $this->display) - $this->display;
        return " LIMIT " . +$show . "," . $this->display . " ;";
    }
}
