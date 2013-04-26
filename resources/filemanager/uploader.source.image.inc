<?php

/**
  ============================================================
 * Last committed:      $Revision: 136 $
 * Last changed by:     $Author: fire1 $
 * Last changed date:   $Date: 2013-04-23 12:12:36 +0300 (âò, 23 àïð 2013) $
 * ID:                  $Id: uploader.source.image.inc 136 2013-04-23 09:12:36Z fire1 $
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
 * Uploadify Source Code file
 */
global $CONFIGURATION, $kernel, $jquery, $requestTarget, $dir, $upload_prop, $fileTypes, $uploadFolder, $language, $USERS_PRIVILEGE;



$show_text = ((bool) $_GET['text']) ?  $_GET['text'] : "For Reload click here";

$js = <<<eol
$(document).ready(function() {
    var options = {
        beforeSubmit:  showRequest,
        success:       showResponse,
           url:       '{$requestTarget}',
        dataType:  'json'
    };
            $('#form-upload').bind('submit', function(e) {
            e.preventDefault(); // <-- important
            $(this).ajaxSubmit(options);
            });
  /*
    $('#form-upload').submit(function() {
        $('#message').html('');
        $(this).ajaxSubmit(options);
        return false;
    });
    */

  $('#fileToUpload').change(function(){

  var fakeipute = $(this).val();
  $('#fake-input').val( fakeipute );
  });

  $("#link_uploaded").live("click",function(){
    parent.location.reload();
  });

});

function showRequest(formData, jqForm, options) {
    var fileToUploadValue = $('#fileToUpload').fieldValue();
    if (!fileToUploadValue[0]) {
        $('#message').html('Please select a file.');
        return false;
    }
    $("#loadingMessage").fadeIn(1500);
    return true;
}

var realoadparent = '{$_GET['reload']}';
function showResponse(data, statusText)  {
    $("#loadingMessage").hide();
    if (statusText == 'success') {
        var msg = data.error.replace("##", "<br />");
        if (data.img != '') {
            $('#result').html('<img src="{$uploadFolder}{$upload_prop['upload.thumb.folder']}' + data.img + '" id="img-thumb"/>');
            //
            // Show Message on upload
            $('#message').html('<br /><a href="#" id="link_uploaded">{$show_text}</a>');
                var inputimg = parent.document.getElementById('image');
             $(".upload_image",window.parent.document).val(data.img);
             $(".upload_image",window.parent.document).css({color:"green"});
             $(".upload_image",window.parent.document).addClass("trigger-attention");

             if(realoadparent){
               window.parent.document.location.reload();
            }

            $('#formcont').html('');
        } else {
            $('#message').html(msg);
        }
    } else {
        $('#message').html('Unknown error!');
    }
}

eol;



if (constant("USER_PRIV") >= $USERS_PRIVILEGE['users.cpanel']) {
    $host = constant("HOST");
    $source_imgFolder = kernel::base_tag_folder_filter(ROOT . "/" . $uploadFolder . "/" . $upload_prop['upload.thumb.folder']);
    $img_in = <<<eol

    <div id="browse-files-container" border="0" width="99%" >
<div class="browse-colse" id="browse-file"> X </div>
eol;
    $arrFiles = dir_read($source_imgFolder);

    if (is_array($arrFiles)) {$is=0;
        foreach ($arrFiles as $images_in) {
            $imgs = "{$uploadFolder}{$upload_prop['upload.image.folder']}{$images_in}";
            $thms = "{$uploadFolder}{$upload_prop['upload.thumb.folder']}{$images_in}";

            $imgs =(file_exists($imgs)) ? "<img src=\"$imgs\" border=\"0\"  class=\"thumb\" />" : "<div class=\"none\"></div>" ;
            $thms =(file_exists($thms)) ? "<img src=\"$thms\" border=\"0\"  class=\"image\" />" : "<div class=\"none\"></div>" ;
            $img_in.= <<<feol
 <div class="browse-file">

     <div class="browse-file-thumb">
            <div class="show-big"></div>
            <div class="forma"> {$thms}</div>
            <div class="browse-file-info">
                <input type="text" name="thimg-$is"  value="{$host}{$uploadFolder}{$upload_prop['upload.thumb.folder']}{$images_in}"  readonly/><br />
                <label for="thimg-$is">  $images_in</label>
            </div>
      </div>

      <div class="browse-file-image">
       {$imgs}
           <span id="close-big">x</span>
        <div class="browse-file-info">
            <input type="text" name="rlimg-$is" value="{$host}{$uploadFolder}{$upload_prop['upload.image.folder']}{$images_in}"  readonly/><br />
            <label for="rlimg-$is"> $images_in</label>
        </div>
    </div>
</div>

feol;
        $images_in=null;
  $is++;
        }
    } else {
        $img_in.= '<div class="browse-file" id="browse-file">';
        $img_in.= " {$language['lw.empty']} !</div>";
    }
    $img_in.= '</div>   ';
    $browse_link = (defined("CPANEL"))?' &nbsp;| &nbsp; <a href="#browse" id="browse-files">browse files</a>':"";
    $refres_link = (defined("CPANEL"))? ' &nbsp;| &nbsp; <a href="#refresh" onClick="window.location=window.location.href">refresh</a>':"";
    $browse_js = kernel::base_tag_folder_filter("{host}{design}/upload/browse-file.js");
    $browse_css = kernel::base_tag_folder_filter("{host}{design}/upload/browse-file.css");
    $js_attach = <<<aeol

<script type="text/javascript" src="{$browse_js}"></script>
<link rel="stylesheet" type="text/css" href="{$browse_css}">
aeol;
}
$maxFileSize = formatBytes($upload_prop['upload.max.size']);
$GLOBALS['THEME'] = <<<eol
<!DOCTYPE html>
<html>
<head>
    <title>Dollop Upload</title>

    <script type="text/javascript" src="{$jquery}"></script>

    <script type="text/javascript" src="{$dir}/js/jquery.form.js"></script>
<script type="text/javascript">
{$js}
</script>
<link rel="stylesheet" type="text/css" href="{$dir}/css/style.css">
{$js_attach}
</head>
<BODY>
{$img_in}
<form id="form-upload" name="form-upload" method="post" action="">
    <input type="hidden" name="MAX_FILE_SIZE" value="{$upload_prop['upload.max.size']}" />

   <div class="size-info"> {$language['lw.image']} {$language['lw.max']} {$language['lw.size']} {$maxFileSize} {$browse_link}  {$refres_link}</div>
    <div class="fake-file boxshadow border">{$language['lan.choose.file']} <input type="text" name="fake" id="fake-input" readonly></div>
        <input type="file" name="fileToUpload" id="fileToUpload"  />
        <input type="Submit" value="{$language['lan.submit']}" id="buttonForm" class="button boxshadow border" />
    </div>
</form>
<div class="content">
<img id="loadingMessage" src="{$dir}/img/loadingAnimation.gif" style="display:none;" />

<div id="result"></div>
<p id="message" ></p>

</div>
</BODY>
</html>


eol;
//$kernel->external_file('js',$js);
