<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: twitter.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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

require (kernel::base_tag("{root}{module_dir}networks/twitter/twitteroauth.php"));
if (!(bool) session_id()) {
    session_start();
}
global $kernel;
if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
    // We've got everything we need
    $twitteroauth = new TwitterOAuth(constant("TWITTER_CONSUMER_KEY"), constant("TWITTER_CONSUMER_SECRET"), $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    // Let's request the access token
    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
    // Save it in a session var
    $_SESSION['access_token'] = $access_token;
    // Let's get the user's info
    $user_info = $twitteroauth->get('account/verify_credentials');
    // Print user's info
    $arr = array();
    $arr['id'] = $user_info->id;
    $arr['username'] = $user_info->screen_name;
    $arr['fullname'] = $user_info->name;
    $arr['email'] = null;
    $arr['provider'] = 'twitter';
    $arr['picture'] = filter_var(str_replace("_normal", "", $user_info->profile_image_url), FILTER_VALIDATE_URL);
    $arr['timezone'] = $user_info->time_zone;
    $arr['language'] = $user_info->lang;
    $arr['other'] = $user_info;
    $arr['error'] = $user_info->error;
    // fill up class
    if ((bool) $usr = new users_socnet($arr))
        ;
} else {
    $twitteroauth = new TwitterOAuth(constant("TWITTER_CONSUMER_KEY"), constant("TWITTER_CONSUMER_SECRET"));
    // Requesting authentication tokens, the parameter is the URL we will be redirected to
    $request_token = $twitteroauth->getRequestToken(kernel::base_tag("{host}{module_dir}twitter"));
    // Saving them into the session
    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    // If everything goes well..
    if ($twitteroauth->http_code == 200) {
        // Let's generate the URL and redirect
        $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
        header('Location: ' . $url);
    } else {
        // It's a bad idea to kill the script, but we've got to know when there's an error.
        die('Something wrong happened.');
    }
}
