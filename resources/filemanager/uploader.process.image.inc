<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: uploader.process.image.inc 115 2013-02-08 16:27:29Z fire $
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
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
/**
 *
 * @filesource
 * put your comment there...
 */
ini_set('display_errors', '0');
$foto_upload = new upload_photo;
global $uploadFolder, $fileTypes, $upload_prop;
$json['size'] = $_POST['MAX_FILE_SIZE'];
$json['img'] = '';
$foto_upload->upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/" . $uploadFolder;
$foto_upload->foto_folder = $_SERVER['DOCUMENT_ROOT'] . "/" . $uploadFolder . "/" . $upload_prop['upload.image.folder'];
$foto_upload->thumb_folder = $_SERVER['DOCUMENT_ROOT'] . "/" . $uploadFolder . "/" . $upload_prop['upload.thumb.folder'];
$foto_upload->extensions = $fileTypes;
$foto_upload->language = "en";
$foto_upload->x_max_size = $upload_prop['upload.image.xmax.size'];
$foto_upload->y_max_size = $upload_prop['upload.image.ymax.size'];
$foto_upload->x_max_thumb_size = $upload_prop['upload.thumb.xmax.size'];
$foto_upload->y_max_thumb_size = $upload_prop['upload.thumb.ymax.size'];
$foto_upload->the_temp_file = $_FILES['fileToUpload']['tmp_name'];
$foto_upload->the_file = $_FILES['fileToUpload']['name'];
$foto_upload->http_error = $_FILES['fileToUpload']['error'];
$foto_upload->rename_file = true;
if ($foto_upload->upload()) {
    $foto_upload->process_image(false, true, true, 80);
    $json['img'] = $foto_upload->file_copy;
}
if (!session_id()) {
    session_start();
}
$_SESSION[$upload_prop['upload.session.UserPicture']] = $foto_upload->file_copy;
$json['error'] = strip_tags($foto_upload->show_error_string());
echo json_encode($json);
