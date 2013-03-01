<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: google.code.inc 115 2013-02-08 16:27:29Z fire $
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
 * @subpackage functions
 * 
 */
function diagram_chart_data($values) {

// Port of JavaScript from http://code.google.com/apis/chart/
// http://james.cridland.net/code
// First, find the maximum value from the values given

    $maxValue = max($values);

// A list of encoding characters to help later, as per Google's example
    $i = 0;
    $all = count($values);
    foreach ($values as $key => $val) {
        $i++;

        $arr .=<<<js
          ["{$key}",{$val}]
js;

        if ($i < $all) {
            $arr .=',';
        }
    }
// Return the chart data - and let the Y axis to show the maximum value




    $js = <<<js


    <script type="text/javascript">
    jQuery(function(){
var diagram_dm_w=$(window).width();
var diagram_dm_h=$(window).height();

    $(".dollop-diagram-memory-content").hide();
    $(".dollop-diagram-memory").click(function () {
    $(".dollop-diagram-memory-content").toggle("slow");
    });  

    $("#dollop-diagram-memory").width( $(window).width() );
        $("#dollop-diagram-memory").height($(".dollop-diagram-memory-content").height());
   
      });
      
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn("string", "function");
        data.addColumn("number", "memory");

        data.addRows([
$arr
        ]);

        var options = {
          title: "Dollop 4 memory diagram"
        };

        var chart = new google.visualization.LineChart(document.getElementById("dollop-diagram-memory"));
        chart.draw(data, {width: 1024, height:400});
      } 
      
    
    </script>


  
js;





    eval('kernel::includeByHtml("https://www.google.com/jsapi","js");');
    eval('kernel::includeByHtml(\'' . $js . '\',"add");');
    return '<div class="dollop-diagram-memory">MDD</div>
<div class="dollop-diagram-memory-content">
<div id="dollop-diagram-memory" style="width:1024px;height: 400px;"></div>
</div>';
}