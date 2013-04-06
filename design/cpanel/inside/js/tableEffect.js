/**
  ============================================================
 * Last committed:      $Revision: 133 $
 * Last changed by:     $Author: fire1 $
 * Last changed date:   $Date: 2013-04-02 20:13:15 +0300 (âò, 02 àïð 2013) $
 * ID:                  $Id: tableEffect.js 133 2013-04-02 17:13:15Z fire1 $
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

(function($) {
    if(jQuery()){
        $.fn.execNearTbl = function(sectortbl,eff) {
            // Exec the scroll bar
            var $ScrollBar = $('#cp-scrollbar-'+sectortbl);
            //$ScrollBar.tinyscrollbar();
            var alltimer = 0;
            // Scroll controlls
            var  scroller = $(window).scrollLeft();
            var  offset = $("#" + sectortbl).offset();
            $("#" + sectortbl + "  table").find('tr').hide();

            $(window).scroll(function() {
                scroller = $(window).scrollLeft();
                offset = $("#" + sectortbl).offset();
                // Get location id check it with URL
                if (  location.hash == ("#"+sectortbl) && ( scroller + 100) >= offset.left) {

                    // Hide all table rows
                    $("div#" + sectortbl + " table").find('tr').each(function (i) {
                        alltime=(i+eff)*200;
                        $(this).delay( alltime ).fadeIn(500);
                        alltimer = (alltimer+alltime)+500;
                    });
                        //  Disable due too many problems with windows screens
                        //$ScrollBar.tinyscrollbar_update()
                        //$('#cp-scrollbar-'+sectortbl).delay(alltimer).tinyscrollbar();
                        //$('#cp-scrollbar-'+sectortbl).delay(alltimer).tinyscrollbar_update(alltimer);

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
                var alltimer = 0;
                $("div" + location.hash + " table").find('tr').each(function (i) {
                    alltime=(i+eff)*200;
                    $(this).delay(alltime).fadeIn(500);
                    alltimer = (alltimer+alltime)+500;
                // uncomment to force chrom to go on top of main screen
                // parent.window.scrollTo(0,0);
                });

            }

            //setTimeout($ScrollBar.tinyscrollbar_update(),alltimer+10000);
        };
    }
}(jQuery));
// fix for chrome
function fixlinkScroll(){
//parent.window.scrollTo(0,0);
}
$(document).ready(function(){
    var isInIFrame = (window.location != window.parent.location) ? true : false;
    if(isInIFrame==false){
        //*******************************enter fullscreen*****************************
        document_width     = $(window).width();
        document_height     = $(window).height() - 83;
        //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        //  stretch contents
        //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        $('body').find('.full-content').each(   function(c){
            $(this).width(document_width);
            $(this).height(document_height);
        });
        $('body').find('.content').each(        function(c){
            $(this).width(document_width);
            $(this).height(document_height);
        });
        $('body').find('.sub-content').each(    function(c){
            $(this).width(document_width);
            $(this).height(document_height);
        });
        $('body').find('.content').each(        function(c){
            $(this).width(document_width);
            $(this).height(document_height);
        });
        $('body').find('#cp-scrollbar').each(   function(c){
            $(this).width(document_width);
            $(this).height(document_height);
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