<?php
/**
 ============================================================
 * Last committed:     $Revision: 3 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:       $Id: networks-users.inc.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 */
       if (!defined('FIRE1_INIT')) { exit("<div style='background-color: #FFAAAA; '> error..1001</div>"); }
    /**
    * 
    * @filesource
    * put your comment there...
    */
  
class User {

    function checkUser($uid, $oauth_provider, $username,$email,$twitter_otoken,$twitter_otoken_secret) 
    {
        $query = mysql_query("SELECT * FROM `".PREFIX."users` 
        
        WHERE auth_uid = '$uid' and 
        auth_provider = '$oauth_provider'
        
        ") or die(mysql_error());
        $result = mysql_fetch_array($query);
        if (!empty($result)) {
            # User is already present
        } else {
            #user not present. Insert a new Record
            $query = mysql_query("INSERT INTO ``".PREFIX."users` ` (auth_provider, auth_uid, username,usermail,auth_token,token_secret) VALUES ('$oauth_provider', $uid, '$username','$email')") or die(mysql_error());
            $query = mysql_query("SELECT * FROM `users` WHERE auth_uid = '$uid' and auth_provider = '$oauth_provider'");
            $result = mysql_fetch_array($query);
            
            $res['oauth_provider']= $result['auth_provider'];
            $res['oauth_uid']= $result['auth_uid'];
            $res['email']= $result['usermail'];
            $res['twitter_oauth_token']= $result['auth_token'];
            $res['twitter_oauth_token_secret']= $result['token_secret'];
            
            return $res;
        }
        return $result;
    }

    

}