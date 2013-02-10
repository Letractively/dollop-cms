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

  
  function user_status_privilege($lv) {
    global $USERS_PRIVILEGE;
    
    if( $lv == $USERS_PRIVILEGE['users.group']['3'] ){
    $r= <<<eol
    <div  class="ui-state-default ui-corner-all " title="{$lv}">
    <span class="ui-icon ui-icon-battery-3" style="float:left;font-size:10px"></span>
     {$USERS_PRIVILEGE['users.privilege'][$lv]}</div>
eol;

    }elseIf( $lv < $USERS_PRIVILEGE['users.group']['3'] && $lv > $USERS_PRIVILEGE['users.group']['2'] ){
    
    $r= <<<eol
    <div    class="ui-state-default ui-corner-all " title="{$lv}">
    <span class="ui-icon ui-icon-battery-2" style="float:left;font-size:10px"></span>
    {$USERS_PRIVILEGE['users.privilege'][$lv]} </div>
eol;
      
      }elseIf( $lv < $USERS_PRIVILEGE['users.group']['2'] && $lv >= $USERS_PRIVILEGE['users.group']['1'] ){
    
    $r= <<<eol
    <div  id="dialog_link"   class="ui-state-default ui-corner-all " title="{$lv}">
    <span class="ui-icon ui-icon-battery-1" style="float:left;font-size:10px"></span>
    {$USERS_PRIVILEGE['users.privilege'][$lv]} </div>
eol;
      
    }else{
    
     $r= <<<eol
    <div   class="ui-state-default ui-corner-all" title="{$lv}">
    <span class="ui-icon ui-icon-battery-0" style="float:left;"></span>
    {$USERS_PRIVILEGE['users.privilege'][$lv]} </div>
eol;

    }
    
    
    return $r;
}
