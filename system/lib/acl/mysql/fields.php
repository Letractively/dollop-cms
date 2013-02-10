<?php

/**
  ============================================================
  Last committed:     $Revision: 3 $
  Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
  Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
  ID:            $Id: fields.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

/**
 *
 * @package mysql_fields
 */
class mysql_fields extends kernel {

    private $db = "";
    private $prop = array();
    private $etc = array();
    private $cchFld = null;

    function __construct() {
        //
        // Getting Configuration data of class
        $this->prop = parent::loadprop(__FILE__, 1);
        $this->load_etc();
        $this->mysql_check_fields();
    }

    /**
     * Collection of information from etc folder
     */
    private function load_etc() {
        //
        // Load ETC data
        if(empty($this->etc)){
            $cache = parent::cache("cl.fields.etc.socnet", 48);
            if (empty($cache)) {
        $this->etc['socnet'] = parent::etc($this->prop['cl.fields.etc.socnet.folder'], $this->prop['cl.fields.etc.socnet.file']);
        $this->etc['mysql'] = parent::etc($this->prop['cl.fields.etc.socnet.folder'], $this->prop['cl.fields.etc.socnet.file']);
        $this->etc['users'] = parent::etc($this->prop['cl.fields.etc.socnet.folder'], $this->prop['cl.fields.etc.socnet.file']);  
        parent::cache("cl.fields.etc.socnet", serialize($this->etc));
            }else{
       $this->etc = unserialize($cache) ;        
            }
        }
    }

    /**
     * Give me array for additional fields and 
     *  I will return you exist fields for users table.
     * 
     * @param array $aCelNames // additional array fields
     * @return array // new array
     */
    public function merger_social($aCelNames) {
        if (!is_array($aCelNames))
            return FALSE;
        $newArray = array();
        foreach ($aCelNames as $key => $val) {

            $newArray[$this->array_dig($key)] = $val;
        }
        return $newArray;
    }

    /**
     *  Dig in Array for keys
     * @param array $Array - Array to dig for keys
     * @return key
     */
    private function array_dig($Array) {

        $result = array();
        $firstKeys = array_keys($Array);
        for ($i = 0; $i < count($firstKeys); $i++) {
            $key = $firstKeys[$i];
            $result = array_merge($result, array_keys($Array[$key]));
        }
        return $result;
    }

    /**
     * Get Custom user field cels in MySQL
     * This function using cache to store the data
     */
    private function mysql_check_fields() {

        if (is_null($this->cchFld)) {
            //
            // Cache avalible for 4 hours
            $cache = parent::cache("users.sqltbl.field", 48);
            if (empty($cache)) {
                $this->db = new mysql_ai;
                $fieldTable = kernel::prop_constant("users.sqltbl.field");
                $this->db->Select($fieldTable);
                if ((bool) $this->db->iRecords) {
                    $this->cchFld = $this->db->aArrayedResults;
                    parent::cache("users.sqltbl.field", serialize($this->cchFld));
                }
            } else {

                $this->cchFld = unserialize($cache);
            }
        }
    }

    /**
     * 
     */
    public function mysql_calculations_based($field){
        
        
    }
}

