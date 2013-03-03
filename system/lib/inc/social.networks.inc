<?php

/**
  ============================================================
 * Last committed:      $Revision$
 * Last changed by:     $Author$
 * Last changed date:   $Date$
 * ID:                  $Id$
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
 * @filesource Dollop Social networks
 * @package Dollop
 * @subpackage Dollop networks
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
//  Social networks buttons
//
//
//
//
//

/**
 * Google Plus (+1) Button
 *
 * @return type HTML Content
 */
function google_plus() {
    $custemer = propc("google.consumer.key");
    $cusecret = propc("rOuqajPFG96_Wcymy_U6F52P");
    $requestu = request_uri();
    $requesth = HOST;

    if (propc("module.socialnetworks")) {


        return <<<eol

    <!--Google-Plus-->

   <div class="g-plusone line-item" data-size="Medium" ></div>

<script>
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

    <!--Google-Plus-->

eol;
    }
}

/**
 * This function combine share and like in to one tag
 *
 * @return html content
 */
function facebook_share() {
    $custemer = propc("facebook.consumer.key");
    $link = kernel::base_tag("{host}{request_uri}");
    return <<<eol

   <!--Facebook-Share-->

<div class="fb-like line-item" data-href="{$link}" data-send="true" data-layout="button_count" data-width="80" data-show-faces="true"></div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=$custemer";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>



    <!--Facebook-Share-->

eol;
}

/**
 * Send link to Twitter
 *
 */
function twitter_link() {

    return <<<eol

   <!--Twitter-Link-->

   <div class="line-item tw-link">
   <a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
   </div>
    <!--Twitter-Link-->

eol;
}
/**
 * Twitter hash link
 *
 * @global array $SQL_WEBSITE
 * @param string $hash
 * @return html content
 */
function twitter_hash($hash = null) {

    global $SQL_WEBSITE;
    if (is_null($hash) OR is_numeric($hash)) {
        $hash = $SQL_WEBSITE['site_name'];
    }

    return <<<eol

    <!--Twitter-Hash-->

   <div class="line-item tw-hash">
   <a href="https://twitter.com/intent/tweet?button_hashtag={$hash}" class="twitter-hashtag-button">Tweet #{$hash}</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
   </div>

    <!--Twitter-Hash-->

eol;
}

/**
 * Send Message
 *  This will send PM to user in CMS
 *
 * @global array $language
 * @global array $SQL_WEBSITE
 * @return html content
 */
function dollop_message() {
    global $language, $SQL_WEBSITE;
    $mlink = kernel::base_tag("{host}{request_uri}");
    $link = kernel::base_tag("/{users}/messages?m=send&share");
    $user_link = kernel::base_tag("{host}{users}/view?id=".USER_ID);
    $username = (defined("USER_NAME")) ? "<b><a href=\"{$user_link}\" target=\"_self\" >" . constant("USER_NAME") . "</a></b>" : null;
    //
    // Generate output attach message
    $_SESSION['dollop_message'] = str_replace(array("{link}", "{site_mail}", "{username}"),
            Array("<a href=\"$mlink\" target=\"_self\">{$mlink}</a>", $SQL_WEBSITE['site_mail'],$username), $language['web.messages.in']);


    if (defined("USER_ID")) {
        // Return button
        return <<<eol

   <!--Dollop-Message-->

   <div class="dp-pm line-item">
    <a href="{$link}" class="button-dp-ml" title="{$language['web.mess.button.t']}"><span></span> {$language['soc.mess.ttl']} </a>
   </div>

    <!--Dollop-Message-->


eol;
    }
}

/**
 * Send E-mail
 *  This will send e-mail from cms
 */
function dollop_share() {
    global $language, $SQL_WEBSITE;
    //
    // Information
    $_SESSION['contact_info'] = $language['web.emailing.in'];
    $mlink = kernel::base_tag("{host}{request_uri}");
    $user_link = kernel::base_tag("{host}{users}/view?id=".USER_ID);
    $username = (defined("USER_NAME")) ? "<b><a href=\"{$user_link}\" target=\"_blank\" >" . constant("USER_NAME") . "</a></b>" : null;
    //
    // Generate output attach message
    $_SESSION['contact_body'] = str_replace(array("{link}", "{site_mail}", "{username}"),
            Array("<a href=\"$mlink\" target=\"_blank\">{$mlink}</a>", $SQL_WEBSITE['site_mail'], $username), $language['web.messages.in']);

    // Return button
    return <<<eol

   <!--Dollop-Mail-->

   <div class="dp-pm line-item">
    <a href="/page?contact&share" title="{$language['web.mail.button.t']}" class="button-dp-pm" ><span class="mail-icon"></span> {$language['soc.mail.ttl']} </a>
   </div>

    <!--Dollop-Mail-->


eol;
}

/**
 * Generate Social networks line button intems
 *
 * @param bool $fblike Generate Facebook like button
 * @param bool $glplus Generate Google plus button
 * @param bool $twlink Generate Twitter share button
 * @param bool $dpmail Generate Dollop share by mail button
 * @param bool $fbshare Generate Facebook share button
 * @param bool $twhash Generate Twitter hash button
 * @param bool $dpmess Generate Dollop Message like button
 * @return string HTML content with social networks buttons
 */
function social_networks($fbshare = true, $glplus = true, $twlink = true, $dpmail = true, $twhash = false, $dpmess = false, $other = null) {
    //
    // Check whate to use

    $fb_shar = ((bool) $fbshare) ? "  " . facebook_share() : null;
    $gl_plus = ((bool) $glplus) ? "  " . google_plus() : null;
    $tw_link = ((bool) $twlink) ? "  " . twitter_link() : null;
    $tw_hash = ((bool) $twhash) ? "  " . twitter_hash($twhash) : null;
    $dp_mess = ((bool) $dpmess) ? "  " . dollop_message() : null;
    $dp_mail = ((bool) $dpmail) ? "  " . dollop_share() : null;

    $css = <<<eol
/** Social networks style **/
#dollop-social-networks.line-items{
    width: 100%;
    clear: both;
    position: relative;
    height: 22px;

}

#dollop-social-networks .line-item{
    display: inline;
    float: left !important;
    margin-right: 15px;
}
#dollop-social-networks .button-dp-pm,#dollop-social-networks .button-dp-ml{

    border-right-width: 1px;
    border-left-width: 1px;
    border-bottom-width: 1px;
    border-top-width: 1px;
    border-right-color: silver;
    border-left-color: silver;
    border-bottom-color: silver;
    border-top-color: silver;
    border-right-style: solid;
    border-left-style: solid;
    border-bottom-style: solid;
    border-top-style: solid;
color:#333;
font-weight: 600;
float: left;
padding: 0px 15px 0px 5px;
display:inline-block;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
line-height: 17px;
font-size: 12px;
height: 17px;

text-align: right;
background: rgb(238,238,238); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(238,238,238,1) 0%, rgba(204,204,204,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(238,238,238,1)), color-stop(100%,rgba(204,204,204,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc',GradientType=0 ); /* IE6-9 */
text-shadow: 1px 1px 0px #eee;

}
#dollop-social-networks .button-dp-pm:hover,#dollop-social-networks .button-dp-ml:hover{
background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(204,204,204,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(204,204,204,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(204,204,204,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(204,204,204,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(204,204,204,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(204,204,204,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#cccccc',GradientType=0 ); /* IE6-9 */

}

#dollop-social-networks .button-dp-pm span,#dollop-social-networks .button-dp-ml span{
    height: 16px;
    width: 16px;
float: left;
background-position: 0px 0px;
background-repeat: no-repeat;
content: '.';
display:inline-block;
background-image: url(/jquery/ui/theme/images/ui-icons_454545_256x240.png);
}
#dollop-social-networks .button-dp-pm span{
background-position: -128px -96px;
}
#dollop-social-networks .button-dp-ml span{
background-position: -80px -96px;

}


/** Social networks style **/

eol;

    global $kernel;
    $kernel->external_file("css", $css);

    return <<<eol
<div id="dollop-social-networks" class="social-networks line-items">
$other  $fb_shar  $tw_link $tw_hash $dp_mess $dp_mail $gl_plus

</div><div style="clear:both"></div>


eol;
}