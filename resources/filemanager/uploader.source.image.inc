<?php

/**
  ============================================================
 * Last committed:      $Revision: 121 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-01 15:54:10 +0200 (ïåò, 01 ìàðò 2013) $
 * ID:                  $Id: uploader.source.image.inc 121 2013-03-01 13:54:10Z fire $
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
 * Uploadify Source Code file
 */
global $CONFIGURATION, $kernel, $jquery, $requestTarget, $dir, $upload_prop, $fileTypes, $uploadFolder, $language, $USERS_PRIVILEGE;
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


function showResponse(data, statusText)  {
    $("#loadingMessage").hide();
    if (statusText == 'success') {
        var msg = data.error.replace("##", "<br />");
        if (data.img != '') {
            $('#result').html('<img src="{$uploadFolder}{$upload_prop['upload.thumb.folder']}' + data.img + '" id="img-thumb"/>');
            //msg +
            $('#message').html('<br /><a href="#" id="link_uploaded">For Reload click here</a>');
                var inputimg = parent.document.getElementById('image');
             $(".upload_image",window.parent.document).val(data.img);
             $(".upload_image",window.parent.document).css({color:"green"});
             $(".upload_image",window.parent.document).addClass("trigger-attention");

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
    $img_in = '<div id="browse-files-container">   <div class="browse-file colse" id="browse-file"> X </div>';
    $arrFiles = dir_read($source_imgFolder);
    if (is_array($arrFiles)) {
        foreach ($arrFiles as $images_in) {
            $img_in.= <<<feol

 <div class="browse-file" id="browse-file">

 <div class="browse-file-thumb">
 <img src="{$uploadFolder}{$upload_prop['upload.thumb.folder']}{$images_in}" border="0"  alt="thumb" id="img-thumb" />
 </div>
 <div class="browse-file-name">
 <input type="text" name="" value="{$host}{$uploadFolder}{$upload_prop['upload.thumb.folder']}{$images_in}"  readonly>

 </div>
 <div class="browse-file-realp">
  <input type="text" name="" value="{$host}{$uploadFolder}{$upload_prop['upload.image.folder']}{$images_in}"  readonly>
 </div>
 </div>

feol;
        }
    } else {
        $img_in.= '<div class="browse-file" id="browse-file">';
        $img_in.= " {$language['lw.empty']} !</div>";
    }
    $img_in.= '</div>   ';
    $browse_link = ' &nbsp;| &nbsp; <a href="#browse" id="browse-files">browse files</a>';
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

   <div class="size-info"> {$language['lw.image']} {$language['lw.max']} {$language['lw.size']} {$maxFileSize} {$browse_link}</div>
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
