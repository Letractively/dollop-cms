<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: photo.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * Photo editor
 */
$dir = pathinfo(__FILE__);
include ($dir['dirname'] . "/file_easy.php");
//error_reporting(E_ALL);
ini_set("memory_limit", "64M");
set_time_limit(60);
class upload_photo extends upload_file_easy {
    var $x_size;
    var $y_size;
    var $x_max_size = 300;
    var $y_max_size = 200;
    var $x_max_thumb_size = 110;
    var $y_max_thumb_size = 88;
    var $thumb_folder;
    var $foto_folder;
    var $larger_dim;
    var $larger_curr_value;
    var $larger_dim_value;
    var $larger_dim_thumb_value;
    var $use_image_magick = true; // switch between true and false
    // I suggest to use ImageMagick on Linux/UNIX systems, it works on windows too, but it's hard to configurate
    // check your existing configuration by your web hosting provider
    function process_image($landscape_only = false, $create_thumb = false, $delete_tmp_file = false, $compression = 85) {
        $filename = $this->upload_dir . $this->file_copy;
        $this->check_dir($this->thumb_folder); // run these checks to create not existing directories
        $this->check_dir($this->foto_folder); // the upload dir is created during the file upload (if not already exists)
        $thumb = $this->thumb_folder . $this->file_copy;
        $foto = $this->foto_folder . $this->file_copy;
        if ($landscape_only) {
            $this->get_img_size($filename);
            if ($this->y_size > $this->x_size) {
                $this->img_rotate($filename, $compression);
            }
        }
        $this->check_dimensions($filename); // check which size is longer then the max value
        if ($this->larger_curr_value > $this->larger_dim_value) {
            $this->thumbs($filename, $foto, $this->larger_dim_value, $compression);
        } else {
            copy($filename, $foto);
        }
        if ($create_thumb) {
            if ($this->larger_curr_value > $this->larger_dim_thumb_value) {
                $this->thumbs($filename, $thumb, $this->larger_dim_thumb_value, $compression); // finally resize the image
                
            } else {
                copy($filename, $thumb);
            }
        }
        if ($delete_tmp_file) $this->del_temp_file($filename); // note if you delete the tmp file the check if a file exists will not work
        
    }
    function get_img_size($file) {
        $img_size = getimagesize($file);
        $this->x_size = $img_size[0];
        $this->y_size = $img_size[1];
    }
    function check_dimensions($filename) {
        $this->get_img_size($filename);
        $x_check = $this->x_size - $this->x_max_size;
        $y_check = $this->y_size - $this->y_max_size;
        if ($x_check < $y_check) {
            $this->larger_dim = "y";
            $this->larger_curr_value = $this->y_size;
            $this->larger_dim_value = $this->y_max_size;
            $this->larger_dim_thumb_value = $this->y_max_thumb_size;
        } else {
            $this->larger_dim = "x";
            $this->larger_curr_value = $this->x_size;
            $this->larger_dim_value = $this->x_max_size;
            $this->larger_dim_thumb_value = $this->x_max_thumb_size;
        }
    }
    function img_rotate($wr_file, $comp) {
        $new_x = $this->y_size;
        $new_y = $this->x_size;
        if ($this->use_image_magick) {
            exec(sprintf("mogrify -rotate 90 -quality %d %s", $comp, $wr_file));
        } else {
            $src_img = imagecreatefromjpeg($wr_file);
            $rot_img = imagerotate($src_img, 90, 0);
            $new_img = imagecreatetruecolor($new_x, $new_y);
            if (function_exists("imageantialias")) {
                imageantialias($new_img, TRUE);
            }
            imagecopyresampled($new_img, $rot_img, 0, 0, 0, 0, $new_x, $new_y, $new_x, $new_y);
            imagejpeg($new_img, $this->upload_dir . $this->file_copy, $comp);
        }
    }
    function thumbs($file_name_src, $file_name_dest, $target_size, $quality = 80) {
        //print_r(func_get_args());
        $size = getimagesize($file_name_src);
        if ($this->larger_dim == "x") {
            $w = number_format($target_size, 0, ',', '');
            $h = number_format(($size[1] / $size[0]) * $target_size, 0, ',', '');
        } else {
            $h = number_format($target_size, 0, ',', '');
            $w = number_format(($size[0] / $size[1]) * $target_size, 0, ',', '');
        }
        if ($this->use_image_magick) {
            exec(sprintf("convert %s -resize %dx%d -quality %d %s", $file_name_src, $w, $h, $quality, $file_name_dest));
        } else {
            $dest = imagecreatetruecolor($w, $h);
            if (function_exists("imageantialias")) {
                imageantialias($dest, TRUE);
            }
            $src = imagecreatefromjpeg($file_name_src);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
            imagejpeg($dest, $file_name_dest, $quality);
        }
    }
}
