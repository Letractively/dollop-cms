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
* Shows all system privileges in select
*     
* @param mixed $slct_name
* @param mixed $selected
*/
function form_select_user_privilege($slct_name,$selected=0){
    global $USERS_PRIVILEGE;
    $html = <<<EOL
        <select name="{$slct_name}" >

        
EOL;
    foreach ($USERS_PRIVILEGE['users.privilege'] as $key=>$name){

        if($key == $selected){$select_this = 'selected="selected" ';}else{$select_this="";}
        if($key >= constant("USER_PRIV")){$disable_this = 'disabled="disabled" style="color=#a0a0a0;" disabled';}else{$disable_this="";}
        
        $html .=<<<EOL
        <option value="{$key}"  $select_this $disable_this>{$name}</option>
EOL;
    }

    $html .='        </select>';  

    return  $html; 

}