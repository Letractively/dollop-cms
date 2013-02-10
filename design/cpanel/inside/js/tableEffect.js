/**
  ============================================================
 * Last committed:      $Revision: 4 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 15:04:50 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: tableEffect.js 4 2013-02-03 13:04:50Z fire1.A.Zaprianov@gmail.com $
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
 * @filesource Dollop CPanel  
 * @package Dollop
 * @subpackage CPanel
 * 
 */
var $topbar  =$('<div id="cp-menu-head">'+
    '<ul id="icons" style="background-color:#fff; position:absolute;margin-left:95%;margin-right:0px;">'+
    // '<li class="ui-state-default ui-corner-all" style="position:fixed;" title="minimiza" id="minimize-cp-menu-head"><span class="ui-icon ui-icon-  ui-icon-arrowthick-2-n-s"></span></li> '+
    '</ul>'+
    '<a href="#index" class="button" style="display:none;"><img src="/design/cpanel/img/inside-close-fullscreen.png"  id="close-fullscreen"></a>'+
    '</div>');

function execNearTbl(sectortbl,eff) {
    //////// fix position to chrome
    // parent.window.scrollTo(0,0);
    var  scroller = $(window).scrollLeft();
    var  offset = $("#" + sectortbl).offset();
    $("div#" + sectortbl + " table").find('tr').hide();
    $(window).scroll(function() {
        scroller = $(window).scrollLeft();
        offset = $("#" + sectortbl).offset();
        if (  location.hash == ("#"+sectortbl) && ( scroller + 100) >= offset.left) {
            $("div#" + sectortbl + " table").find('tr').each(function (i) {
                alltime=(i+eff)*2+"00";
                $(this).delay( alltime ).fadeIn(1000);
            });
        }
    });
    /* DISABLE To do a fix 
              $('#'+sectortbl).hover(function(){
              $('#cp-scrollbar-'+sectortbl).tinyscrollbar_update(1);
              return false;
            });
            */
    ///////////////////////////  
    // fix fo firefox 
    // after submit form
    ///////////////////////////
    if ( $.browser.mozilla && scroller  >= offset.left && location.hash == ("#"+sectortbl) ){
        $("div" + location.hash + " table").find('tr').each(function (i) {
            $(this).delay((i+eff)*2+"00").fadeIn(1000);   
        // uncomment to force chrom to go on top of main screen
        // parent.window.scrollTo(0,0);      
        });
    }
}
// fix for chrome
function fixlinkScroll(){
//parent.window.scrollTo(0,0);
}
$(document).ready(function(){
    var isInIFrame = (window.location != window.parent.location) ? true : false;
    if(isInIFrame==false){
        //*******************************enter fullscreen*****************************
        document_width     = $(window).width();
        //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        //  stretch contents
        //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        $('body').find('.full-content').each(   function(c){
            $(this).width(document_width);
        });
        $('body').find('.content').each(        function(c){
            $(this).width(document_width);
        });
        $('body').find('.sub-content').each(    function(c){
            $(this).width(document_width);
        });
        $('body').find('.content').each(        function(c){
            $(this).width(document_width);
        });   
        $('body').find('#cp-scrollbar').each(   function(c){
            $(this).width(document_width);
        }); 
        $('body').find('.cp-title').each(       function(b){ 
            $(this).css({
                right:"0px"
            });
        }); 
        //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        //  create bottom links menu on fullscreen
        //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        $(".content .nav").clone().appendTo($topbar);
        $($topbar).appendTo("body");
        $("#cp-menu-head").find('a').show('slow');
        // @to do need fix
        if(location.hash != 'index'){
            var timeOutClose = 1500;
        }else{
            var timeOutClose = 500;
        }
        //
        // Adding close button
        $('#close-fullscreen').live("click",function(){
            $('.content').delay( 1000 ).hide(1000);
            setTimeout(function(){
                window.location.href="/";
            },timeOutClose)
        });
        $('#minimize-cp-menu-head').live("click",function(){
            $('#cp-scrollbar').height($('.viewport').height());
            $("#cp-menu-head").slideToggle('slow');
        });
    }
});