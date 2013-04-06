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
function datediff($start,$end){
 $first_date = strtotime($start);
    $second_date = strtotime($end);
    $offset = $second_date-$first_date;
    return floor($offset/60/60/24);


}
