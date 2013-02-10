<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: process.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * @subpackage class
 * 
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
/**
 *
 * @filesource
 * manager_process class
 */
if (!defined('FIRE1_INIT')) {
    exit("--------------");
}

class manager_process extends cpanel {

    const folder = USER_PRIV_NAME;
    const privlg = USER_PRIV;
    const userid = USER_IDIN;
    const usernm = USER_NAME;
    const manage = MANAGER_FLDR;
    const privfl = "conf.prop";
    const varuse = "manage";

    private $exec = "";
    private $path;
    private $prop = array();
    private $optn = array();

    /**
     * exec manager
     *
     * @param mixed $exec // __FILE__
     * @return manager_process
     */
    public function manager_process($exec) {
        global $db;
        $this->exec = str_replace(ROOT, "", $exec);
        $path = realpath(ROOT . self::manage . "/");
        if ($path) {
            if ($this->prop = $this->add_priv_prop()) {
                $this->priv_to_files($this->prop);
            }
        } else {
            kernel::ERROR("Cannot construct path for manager", "php", "In class [manager_process] \n Line:" . __LINE__);
        }
    }

    private function read_dir($dir) {
        $dirs = array();
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $dirs[] = $entry;
                }
            }
            closedir($handle);
        } else
            return false;;
        return $dirs;
        ;
    }

    private function catch_opt() {
        global $SQL_WEBSITE;
        $this->optn = $SQL_WEBSITE;
    }

    private function chmod_fix($file) {
        if (substr(sprintf('%o', @fileperms($file)), -4) != 0600) {
            @chmod($file, 0600);
        }
    }

    private function add_priv_prop() {
        $open = ROOT . self::manage . "/" . self::folder . "/" . self::privfl;
        self::chmod_fix($open);
        if (file_exists($open)) {
            return kernel::loadprop($open, 1);
        } else
            return false;;
    }

    private function priv_to_files($prop) {
        global $cpanel;
        foreach ($prop as $group => $file_add) {
            $cpanel->catch_option($group, $file_add, ($this->exec));
        }
    }

}
