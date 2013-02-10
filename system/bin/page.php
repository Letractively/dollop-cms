<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: page.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
    exit("This is not right way to open the pages in Dollop");
}
/**
 * @filesource
 * Dollop pages file
 *
 * @version 1.1
 * @author Angel Zaprianov <fire1@abv.bg>
 *
 */
if (isset($_GET['view']) && !is_numeric($_GET['view'])) {
    theme::responses(400);
}
$_GET['view'] = mysql_real_escape_string($_GET['view']);
$sqlquery = "SELECT * FROM " . PREFIX . "pages WHERE ID = '" . $_GET['view'] . "' LIMIT 1;";
if (class_exists('kernel')) {
    $row = kernel::sql_fetch_array($sqlquery);
} else {
    $queryresult = mysql_query($sqlquery) or die(mysql_error());
    $row = mysql_fetch_array($queryresult);
}
if (is_array($row)) {
    //Header tag / advanced
    foreach ($row as $row) {
        $TITLE = $row['title'];
        $BODY = $row['body'];
        // eval php script in to selected page
        if (!empty($row['phpcripts'])) {
            eval(" {$row['phpcripts']} ");
        }
        // Attach jQuery script in to page
        if (!empty($row['jscripts'])) {
            global $kernel;
            $kernel->external_file('jquery', $row['jscripts']);
        }
    }
    theme::content(array($TITLE, $BODY));
} else {
    theme::responses(404);
}
?>