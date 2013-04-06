<?php

/**
  ============================================================
 * Last committed:      $Revision: 130 $
 * Last changed by:     $Author: fire1 $
 * Last changed date:   $Date: 2013-03-28 14:53:34 +0200 (÷åòâ, 28 ìàðò 2013) $
 * ID:                  $Id: _lan.php 130 2013-03-28 12:53:34Z fire1 $
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
 * @filesource Users Language
 * @package Dollop Users
 * @subpackage Users
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
global $SQL_WEBSITE, $language;

define("USR_LAN_PG_MAIN_1", "User login ");
/// log

$language['users.logname'] = "Username or email ";
$language['users.pass'] = "Your Password: ";
$language['users.remember'] = "Tick to remember! ";
$language['users.forgot'] = "Forgot password? ";

/// reg
$language['users.regname'] = " User Name: ";
$language['users.regmail'] = " Email: ";
$language['users.regpass'] = " Password: ";

/// main logged in
$language['users.welcome'] = "Welcome ";


//buttons
$language['users.b.log'] = "Sign In";
$language['users.b.reg'] = "Sign Up";


define("USR_LAN_ERR_LOG_1", " No such user! ");
define("USR_LAN_ERR_LOG_2", " No such user! ");
define("USR_LAN_ERR_LOG_3", " Incorrect password! ");

define("USR_LAN_ERR_REG_REGULAR", "Missed  required field:");

//Missed field
define("USR_LAN_ERR_REG_REQUIRE_USERPASS", "Missed  required for password!");
define("USR_LAN_ERR_REG_REQUIRE_USERNAME", "Missed  required for username!");
define("USR_LAN_ERR_REG_REQUIRE_USERMAIL", "Missed  required for e-maail!");

define("USR_LAN_ERR_REG_VALID_USERMAIL", "The e-mail address is invalid!");

//Taken field
define("USR_LAN_ERR_REG_TAKE_USERNAME", "This username is already taken!");
define("USR_LAN_ERR_REG_TAKE_USERMAIL", "This e-mail address already exist!");

define("USR_LAN_ERR_REG_ERROR_INSERT", "Cannot insert User to MySQL database!");
define("USR_LAN_ERR_REG_ERROR_USERNAME", "Cannot check Username from MySQL selection!");
define("USR_LAN_ERR_REG_ERROR_USERMAIL", "Cannot check User e-mail from MySQL selection!");



define("USR_LAN_OK_REG", "You have successfully signed up to our website <b>" . $SQL_WEBSITE['site_name'] . "</b>.");

$language['users.log'] = "Website Login:";
$language['users.reg'] = "Website Registration:";
$language['users.edi'] = "Edit user:";


//Cpanel
$language['p.users.id'] = "#";
$language['p.users.na'] = "username";
$language['p.users.pa'] = "password";
$language['p.users.em'] = "e-mail";
$language['p.users.ro'] = "roles";
$language['p.users.st'] = "status";
$language['p.users.st.v'] = "verified";
$language['p.users.st.u'] = "unverified";
$language['p.users.mbf'] = "member for";
$language['p.users.op'] = "operations";
$language['p.users.hgr'] = "<small>You can save view from table and use it in other sectors</small>";
$language['p.users.mmls'] = "select user list";
$language['p.users.smsv'] = "Comprehensive information on this email will be kept as a bookmark. ";
$language['p.users.mbmk'] = "Bookmarks ";
$language['p.users.omsm'] = "Other list to send ";
$language['p.users.omsh'] = "Seperate  emails with comma  ";


$language['users.days'] = "Days";
$language['users.months'] = "Months";
$language['users.years'] = "Years";
$language['users.hours'] = "Hours";
$language['users.minutes'] = "Minutes";
$language['users.seconds'] = "Seconds";

$language['users.avatar'] = "Your website picture";


//Recover password
$language['users.f.email'] = "Your e-mail:";
$language['users.f.cmail'] = "Your current email:";
$language['users.f.nmail'] = "Your new email:";
$language['users.f.title'] = "Website Data Recovery";
$language['users.f.messa'] = "To get back into your {site_name} account, you'll need to create a new password.";
$language['users.f.pdnm'] = "New passwords don't match";
$language['users.f.reset'] = "Reset your password now";
$language['users.f.resnw'] = "Link not working? Copy and paste the one below into your browser:";

//
// Change mail
$language['users.chml.ttl'] = "You receive this message to change current email in {host}";
$language['users.chml.mss'] = "By clicking the link below you will be redirected to web page that will activate the  current mailbox.";
$language['users.chml.smss'] = "It is recommended to be signed-in like a user, before you continue.";
$language['users.chml.sendmail'] = "The email message for changing mailbox is sent successfully.<p>Please check your requested mailbox.<p>";
$language['users.chml.sesexp'] = "Session for change of mailbox is expired.";

//
// User fields
$language['users.flds.name'] = "MySQL Field name";
$language['users.flds.desc'] = "Field description";
$language['users.flds.titl'] = "Field title";
$language['users.flds.fldty'] = "Field Type";
$language['users.flds.rscol'] = "Field rows/cols";
$language['users.flds.order'] = "Fields Order";
$language['users.flds.rowty'] = "Field type in MySQL";
$language['users.flds.attre'] = "HTML Field attributes";

$language['users.flds.defva'] = "Field Def.Value";
$language['users.flds.fld_require'] = "On Registration:";
$language['users.flds.reqy'] = "Require";
$language['users.flds.rehi'] = "Hide ";
$language['users.flds.reqn'] = "Display ";

// Forgotten password
$language['users.sent.mail'] = "The email for password recovery is sent successfully,</br > check your email and follow the steps written in the email.";
$language['users.sent.err'] = "Please Try Again!";
$language['users.sent.cont'] = "To continue please click <b><a href='/users'>here</a></b>";
$language['users.recov.errorhash'] = "Error in the given link";
$language['users.recov.changfor'] = "Changing password for username:";

$language['users.updated'] = "<p align='center'>New information is set in your profile!<br /><br />
    To continue go into <a href='main'>the user's main page</a> or <a href='/'>the website home page.</a></p>
";
$language['users.change.mail'] = "Change your email";
$language['users.change.password'] = "Change your password";
$language['users.timezone'] = "Your timezone";

// Messages
$language['users.messages.index'] = "Inbox";
$language['users.messages.inbox'] = "Inbox";
$language['users.messages.einbox'] = "No messages in your Inbox";
$language['users.messages.sendme'] = "Send New Message";
$language['users.messages.goback'] = "Main User Page";
$language['users.messages.readla'] = "Read Last Message";
$language['users.messages.select'] = "Select User:";
$language['users.messages.sendto'] = "Send Message To:";
$language['users.messages.uisect'] = "sending to:";
$language['users.messages.alertm'] = "To send a message, please fill in all fields.";
$language['users.messages.messtt'] = "Your Subject:";
$language['users.messages.messms'] = "Your Message:";
$language['users.messages.messuc'] = " Message is sent successfully!";
$language['users.messages.messfr'] = "Message from";
$language['users.messages.mesimp'] = "Tick for Important";
$language['users.messages.mesrep'] = "Reply this message";
$language['users.messages.senttt'] = "Outbox";
$language['users.messages.mailtt'] = "Message Notification";
$language['users.messages.mailtx'] = "This notification is sent because you have important message from user ";
$language['users.messages.mailun'] = "Un-readed ";
$language['users.messages.mailre'] = "Readed ";
$language['users.messages.mailer'] = "Are you sure you want to delete ";


// View user
$language['users.view.notexist'] = "This user do not exist.<br /> Check your link";
$language['users.view.sendmess'] = "Send personal message";
$language['users.view.viewingu'] = "viewing ";

// User review
$language['users.revw.usrfromt'] = " Member for";
$language['users.revw.changeph'] = " Change your photo";
$language['users.revw.authprov'] = " Provider";
$language['users.revw.authnone'] = " Not provided";
$language['users.revw.picdescr'] = " After uploading the picture reload the page to send it in to photo picker!";
$language['users.revw.piclickr'] = " Photo picker";
$language['users.revw.picuplod'] = " Photo upload";


//
// Goodbye