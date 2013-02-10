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
  * 
  * @filesource
  * TinyURL class
  */

class tiny{
/**
* Simply provide the URL and you'll received the new, tiny URL in return.
* 
* @param string $url is 'http://tinyurl.com/api-create.php?url='
* @param string $server
* @param int $timeout
*/
public function tiny($url,$server='http://tinyurl.com/api-create.php?url=',$timeout=5)  {  
 
  $ch = curl_init();  
  curl_setopt($ch,CURLOPT_URL,$server.$url);  
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
  $data = curl_exec($ch);  
  curl_close($ch);  
  return $data;  
}


}
