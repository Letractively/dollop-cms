<?php

/**
  ============================================================
 * Last committed:      $Revision$
 * Last changed by:     $Author$
 * Last changed date:   $Date$
 * ID:                  $Id$
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
 * @filesource Menu LiveChat
 * @package Dollop
 * @subpackage LiveChat
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
$ldb = null;
//
// Get all channel data from MySQL with dead connection
$channels = kernel::sql_fetch_array("SELECT * FROM `" . PREFIX . "chat_chanels` ");
//
// Check if is avalible some channel
if (is_array($channels)) {
    foreach ($channels as $row_channel)
        //
        // Get a guest channel is created
        if ($row_channel['available'] == 0) {
            //
            // Create a live Database connection if is available channel
            if (!is_object($ldb)) {
                $ldb = new mysql_ai();
            }

        } elseIf ($row_channel['available']) {

        }



    $ldb->Select("chat_chanels", 0);
}






