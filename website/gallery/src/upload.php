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
 * @filesource Index redirect
 * @package search
 * @subpackage none
 *
 */

if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
global $CONFIGURATION, $THEME, $db;
$verifyToken = md5(HEX . $CONFIGURATION['SPR'] . $_POST['timestamp'] . $CONFIGURATION['SPR'] . $CONFIGURATION['KEY'] . $CONFIGURATION['SPR'] . $_POST['timestamp']);
$cat = str_replace(" ", "_", preg_replace("[A-Za-z0-9_]", "", $_POST['cat']));

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . kernel::base_tag("{publicfiles}" . MODULE_DIR . $cat) . DIRECTORY_SEPARATOR;
    $imgFolder = $targetPath . kernel::base_tag("{images}");
    $thmFolder = $targetPath . kernel::base_tag("{thumbs}");

    if (!is_dir($imgFolder)):
        mkdir($imgFolder, 0755, true);
    endif;

    if (!is_dir($thmFolder)):
        mkdir($thmFolder, 0755, true);
    endif;
    $tempFile = $_FILES['Filedata']['tmp_name'];

    // Validate the file type
    $fileTypes = array(
        '.jpg',
        '.jpeg',
        '.gif',
        '.png'
    ); // File extensions

    ini_set('display_errors', '0');
    $foto_upload = new upload_photo();
    $foto_upload->upload_dir = $targetPath . "/";
    $foto_upload->foto_folder = $imgFolder;
    $foto_upload->thumb_folder = $thmFolder;
    $foto_upload->extensions = $fileTypes;
    $foto_upload->language = "en";
    $foto_upload->x_max_size = 1280;
    $foto_upload->y_max_size = 1024;
    $foto_upload->x_max_thumb_size = $_POST['size'];
    $foto_upload->y_max_thumb_size = $_POST['size'];
    $foto_upload->the_temp_file = $_FILES['Filedata']['tmp_name'];
    $foto_upload->the_file = $_FILES['Filedata']['name'];
    $foto_upload->http_error = $_FILES['Filedata']['error'];
    $foto_upload->rename_file = true;

    if ($foto_upload->upload()) {
        $foto_upload->process_image(false, true, true, 90);
        $json['img'] = $foto_upload->file_copy;
        $THEME = strip_tags($foto_upload->show_error_string());
        $now = date('Y-m-d H:i:s', time());
        $name = mysql_real_escape_string($json['img'], $db);
        $catg = mysql_real_escape_string($cat, $db);
        $type = mysql_real_escape_string("image", $db);
        mysql_query("INSERT INTO `" . PREFIX . "gallery_content`

                                (`name`,`category`,`value_type`,`value_date`,`value_bool`)

                                    VALUES('{$name}','$catg','$type','$now','1'); ", $db);
    } else {
        $THEME = 'Cannot Upload the file.';
    }
} else {
    $THEME = 'Error in session.';
}
