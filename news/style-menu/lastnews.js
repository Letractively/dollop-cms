/**
  ============================================================
 * Last committed:      $Revision$
 * Last changed by:     $Author$
 * Last changed date:   $Date$
 * ID:                  $Id$
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
 *
 */
$(function(){
    var $wdthlastnews = $("#menu-last-news").width();
    $scrollstep = $wdthlastnews;
    $("#menu-last-news .last-news-block").width($wdthlastnews);
    console.log("width to news block:"+ $wdthlastnews);
    /*Scroller*/
    var $scrollTo = function(e) {
        $('#menu-last-news').animate({
            scrollLeft:e
        }, 800);
        console.log("Scroll steps by: "+e + 'px');
    }
    $("#nav-menu-last-news-nx").live("click",function(){
        $current = $('#menu-last-news .current');
        if ($current.index() != $('#menu-last-news .last-news-block').length - 1) {
            $scrollTo($scrollstep);
            $current.removeClass('current').next().addClass('current');
            $scrollstep =$scrollstep+ $wdthlastnews
        }

    });
    $("#nav-menu-last-news-pv").live("click",function(){
        $current = $('#menu-last-news .current');
        if (!$current.index() == 0) {
            $current.removeClass('current').prev().addClass('current');
            $scrollstep =$scrollstep- $wdthlastnews
            $scrollTo($scrollstep - $wdthlastnews);

        }
    });




});


