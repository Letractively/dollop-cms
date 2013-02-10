<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: facebook.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * @filesource  Dollop Users
 * @package dollop 
 * @subpackage Module
 * 
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
/**
 *
 * @filesource
 * Facebook login page
 */
require (kernel::base_tag("{root}{module_dir}networks/facebook/facebook.php"));
$facebook = new Facebook(array('appId' => constant("FACEBOOK_CONSUMER_KEY"), 'secret' => constant("FACEBOOK_CONSUMER_SECRET"),));
$user = $facebook->getUser();
if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
    }
    if (!empty($user_profile)) {
        # User info ok? Let's print it (Here we will be adding the login and registering routines)
        $lang = explode("_", $user_profile["locale"]);
        $email = $user_profile['email'];
        $arr = array();
        $arr['id'] = $user_profile['id'];
        $arr['username'] = $user_profile['name'];
        $arr['fullname'] = $user_profile['name'];
        $arr['email'] = filter_var($user_profile['email'], FILTER_SANITIZE_EMAIL);
        $arr['provider'] = 'facebook';
        $arr['picture'] = "http://graph.facebook.com/{$user_profile['id']}/picture?type=large";
        $arr['timezone'] = date('e', $user_profile['timezone']);
        $arr['language'] = $lang[0];
        // fill up class
        if ($usr = new users_socnet($arr))
            ;
    } else {
        # For testing purposes, if there was an error, let's kill the script
        die("There was an error.");
    }
} else {
    # There's no active session, let's generate one
    $login_url = $facebook->getLoginUrl(array('scope' => 'email'));
    header("Location: " . $login_url);
}
