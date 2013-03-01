<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: image.php 115 2013-02-08 16:27:29Z fire $
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
ob_start();
ob_clean();


@mysql_close();
@mysql_close($db);


if (!defined('FIRE1_INIT')) {
    mysql_close($db);
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

switch ($_GET['image']) {
    case 'create':
        if (!empty($_GET['s'])) {
            $strSize = explode('x', $_GET['s']);
        }
        if (empty($strSize[1])) {
            $w = 280;
            $h = 30;
        } else {
            $w = $strSize[0];
            $h = $strSize[1];
        }


//image generation code
        header("Content-type: image/png"); //Picture Format
        header("Expires: Mon, 01 Jul 2003 00:00:00 GMT"); // Past date
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Consitnuously modified
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Pragma: no-cache"); // NO CACHE
// end HEADERS


        /* image generation code */
//create Image of size 350px x 75px
        $bg = imagecreatetruecolor($w, $h);

//This will make it transparent
        imagesavealpha($bg, true);

        $trans_colour = imagecolorallocatealpha($bg, 0, 0, 0, 127);
        imagefill($bg, 0, 0, $trans_colour);

//Text to be written

        $helloworld = isset($_GET['text']) ? strip_tags(urldecode(str_replace('|-+-|', '@', $_GET['text']))) : "No text to image.";

        _GET('color');
        switch ($_GET['color']) {
            case 'white':
// White text
                $txtcolor = imagecolorallocate($bg, 255, 255, 255);
                break;
            case 'gray':
// Grey Text
                $txtcolor = imagecolorallocate($bg, 128, 128, 128);
                break;
            case 'black':
// Black Text
                $txtcolor = imagecolorallocate($bg, 0, 0, 0);
                break;
            case 'custom':
// create custom collor example : 122026026 bordo
                $strColorR = substr($_GET['id'], 0, 3);
                $strColorG = substr($_GET['id'], -3, 3);
                $strColorB = substr($_GET['id'], -6, 3);
#echo "$strColorR/$strColorG/$strColorB";
                $txtcolor = imagecolorallocate($bg, $strColorR, $strColorB, $strColorG);
                break;
            default:
                $color['red'] = imagecolorallocate($bg, 255, 0, 0);
                $color['blue'] = imagecolorallocate($bg, 100, 149, 237);
                $color['orange'] = imagecolorallocate($bg, 255, 69, 0);
                $color['black'] = imagecolorallocate($bg, 112, 138, 144);
                $color['white'] = imagecolorallocate($bg, 128, 128, 128);
                $txtcolor = $color[THEME_MAIN_COLOR];
                break;
        }


        if (!empty($_GET['font']) && file_exists('design/fonts/' . $_GET['font'] . '.ttf')) {
            $font = 'design/fonts/' . $_GET['font'] . '.ttf';
        } else {
            $font = 'design/fonts/arial.ttf'; //path to def font you want to use
        }
        if (!empty($_GET['size']) && is_numeric($_GET['size'])) {
            $fontsize = $_GET['size']; //size of font
        } else {
            $fontsize = 12; //size of font
        }


//Writes text to the image using fonts using FreeType 2
        imagettftext($bg, $fontsize, 0, 20, 20, $txtcolor, $font, $helloworld);

//Create image
        imagepng($bg);

//destroy image
        ImageDestroy($bg);


        break;

    default:
        header('Content-type: image/jpeg');
        img_resampling($_GET['i'], $_GET['d'], $_GET['s']); //resampling image giv in var "i" the "d" var is folder in p.files and "s" is size of image
        break;
}

echo ob_get_contents();
kernel::buffering('end');
while (@ob_end_flush());
exit(1);
?>