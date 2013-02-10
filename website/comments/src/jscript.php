<?php
/**
 ============================================================
 * Last committed:     $Revision: 86 $
 * Last changed by:    $Author: fire $
 * Last changed date:    $Date: 2012-10-30 14:12:58 +0200 (вторник, 30 Октомври 2012) $
 * ID:       $Id: jscript.php 86 2012-10-30 12:12:58Z fire $
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
 * @filesource
 * Comments function libs
 */
global $THEME;
$process = MODULE_DIR . "process";
$style = MODULE_DIR . "style/main.css";
$jqForm = file_get_contents(MODULE_DIR . "javascript/jq.form.js");
$imgLoad = MODULE_DIR . "img/ajax-loader.gif";
$THEME = <<<EOL
{$jqForm}
    document.write('<div id="comments"><p align="center"><img src="/{$imgLoad}" border="0" align="center" /></p></div>');
 
    $(document).ready(function(){
    $('head').append('<link rel="stylesheet" href="/{$style}" type="text/css" />');  
  
    var pathname,process,items;   
     pathname = window.location.pathname +"?"+ window.location.search.substring(1);    
    $('#comments').load('/{$process}?type=html&url=' + escape( pathname ), function() {
    $('#insert_comments').live('submit', function() {  
    $('#comments #loader').fadeIn(800);  
    $(this).ajaxSubmit(function() {  
        $('#comments').load('/{$process}?type=html&url=' + escape( pathname ),function(){ 
        $('table#comment-table.first').fadeIn(2500);
         $('#comments #loader').fadeOut(800); 
    });
    });
      
    // return false to prevent normal browser submit and page navigation
    return false;
    });   
  });

    }); 
   
EOL;
$GLOBALS['THEME'] = $THEME;
