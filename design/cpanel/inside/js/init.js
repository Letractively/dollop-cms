/**
  ============================================================
 * Last committed:      $Revision: 4 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 15:04:50 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: init.js 4 2013-02-03 13:04:50Z fire1.A.Zaprianov@gmail.com $
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
$(document).ready(function() {
    $.localScroll({
        axis:'xy',
        duration:1500,
        hash:true, 
        queue:false //one axis at a time
    });
    //$.localScroll.hash();
    var isInIFrame = (window.location != window.parent.location) ? true : false;
    if(isInIFrame==false){
        $('.viewport').height($(window).height()-85);
        $('#cp-scrollbar').height($(window).height()-100);
    }else{
        $('.viewport').height($(window).height());
        $('#cp-scrollbar').height($(window).height());
    }
    var c=null;
    var h=0;
    var hc=$('.viewport');
    $('.full-content').each(function(i) {
        h = $(this)
        if(hc.height() < h.height() ){
            hc = h;
        }
    });

    $('#end-viewport').height($(window).height());

});



