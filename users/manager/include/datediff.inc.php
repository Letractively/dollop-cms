<?php
#$Id: dollop 4
/**
 
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
   
   See COPYRIGHT and LICENSE
   
*/
    if (!defined('FIRE1_INIT')) { exit("<div style='background-color: #FFAAAA; '> error..1001</div>"); }  



/**
* put your comment there...
* 
* @param mixed $interval
* @param int $datefrom
* @param int $dateto
* @param mixed $using_timestamps
* @return float
*/
function datediff($start,$end,$out_in_array=false){
        $intervalo = date_diff(date_create($start),date_create($end));
        $out = $intervalo->format("Years:%Y,Months:%M,Days:%d,Hours:%H,Minutes:%i,Seconds:%s");
        if(!$out_in_array)
            return $out;
        $a_out = array();
        array_walk(explode(',',$out),
        function($val,$key) use(&$a_out){
            $v=explode(':',$val);
            $a_out[$v[0]] = $v[1];
        });
        return $a_out;
}
