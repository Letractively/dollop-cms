<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: google.php 115 2013-02-08 16:27:29Z fire $
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
 * put your comment there...
 */
include_once kernel::base_tag("{root}{module_dir}networks/google/apiClient.php");
include_once kernel::base_tag("{root}{module_dir}networks/google/contrib/apiOauth2Service.php");
$client = new apiClient();
$client->setApplicationName("Google/Dollol Authentication");
// Visit https://code.google.com/apis/console?api=plus to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$client->setClientId(kernel::prop_constant("google.consumer.key"));
$client->setClientSecret(kernel::prop_constant("google.consumer.secret"));
$client->setRedirectUri(kernel::base_tag("{host}{module_dir}google"));
$client->setDeveloperKey(kernel::prop_constant("google.developer.key"));
$oauth2 = new apiOauth2Service($client);
if (isset($_GET['code'])) {
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
    $redirect = kernel::base_tag("{host}{module_dir}google");
    header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}
if (isset($_SESSION['token'])) {
    $client->setAccessToken($_SESSION['token']);
}
if ($client->getAccessToken()) {
    $user = $oauth2->userinfo->get();
    // These fields are currently filtered through the PHP sanitize filters.
    // See http://www.php.net/manual/en/filter.filters.sanitize.php
    $arr = array();
    $arr['id'] = $user['id'];
    $arr['username'] = $user['name'];
    $arr['fullname'] = $user['name'];
    $arr['email'] = $user['email'];
    $arr['provider'] = 'google';
    $arr['picture'] = filter_var($user['picture'], FILTER_VALIDATE_URL);
    $arr['timezone'] = null;
    $arr['language'] = $user['locale'];
    $arr['other'] = $user;
    // fill up class
    if ($usr = new users_socnet($arr))
        ;
    // The access token may have been updated lazily.
    $_SESSION['token'] = $client->getAccessToken();
} else {
    header("location:" . $client->createAuthUrl());
}
