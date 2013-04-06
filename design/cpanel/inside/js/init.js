/**
  ============================================================
 * Last committed:      $Revision: 129 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-28 13:31:47 +0200 (÷åòâ, 28 ìàðò 2013) $
 * ID:                  $Id: init.js 129 2013-03-28 11:31:47Z fire $
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

/**
 * Function Filter table
 *
 */
(function($) {
    $.fn.extend({
        filter_table: function(options) {
            options = $.extend( {}, $.filterTable.defaults, options );

            this.each(function() {
                new $.filterTable(this,options);
            });
            return this;
        }
    });

    // input is the element, options is the set of defaults + user options
    $.filterTable = function( input, options ) {
        $(input).keyup(function(){

            //hide all the rows
            $(options['table']).find("tr").hide();
            //split the current value of searchInput
            var data = this.value.split(" ");
            //create a jquery object of the rows
            var jo = $(options['table']).find("tr");
            //Recursively filter the jquery object to get results.
            $.each(data, function(i, v){
                jo = jo.filter("*:contains('"+v+"')");
            });
            //show the rows that match.
            jo.show();
        //Removes the placeholder text
        }).focus(function(){
            this.value="";
            $(this).css({
                "color":options['color_b']
                });
            $(this).unbind('focus');
        }).css({
            "color":options['color_a']
            });
    };

    // option defaults
    $.filterTable.defaults = {
        table: "#list",
        color_a: "#C0C0C0",
        color_b: "black"
    };

})(jQuery);

$(function() {
    $.localScroll({
        axis:'xy',
        duration:1500,
        hash:true,
        queue:true //one axis at a time
    });
    //$.localScroll.hash();
    var isInIFrame = (window.location != window.parent.location) ? true : false;
    if(isInIFrame==false){

        $('.viewport').height($(window).height()-80);
        $('#cp-scrollbar').height($(window).height()-83);

    }else{
        $('.viewport').height($(window).height());
        $('#cp-scrollbar').height($(window).height());

    }
    /*
    var c=null;
    var h=0;
    var hc=$('.viewport');
    $('.full-content').each(function(i) {
        h = $(this)
        if(hc.height() < h.height() ){
            hc = h;
        }
    });
    */

    //$('#end-viewport').height($(window).height());

});



