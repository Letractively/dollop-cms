<?php
  if (!defined('FIRE1_INIT')) { exit("<div style='background-color: #FFAAAA; '> error..1001</div>"); }   
/**
* LANGUAGE File for users in dollop
* 
**/


   if (!defined('FIRE1_INIT')) {@header_remove(); header("location:error400");exit(); }
global $SQL_WEBSITE,$language;

define("USR_LAN_PG_MAIN_1","User login ");
/// log

$language['users.logname']  = "&#1055;&#1086;&#1090;&#1088;&#1077;&#1073;&#1080;&#1090;&#1077;&#1083;&#1089;&#1082;&#1086; &#1080;&#1084;&#1077; &#1080;&#1083;&#1080; &#1045;&#1083;&#1077;&#1082;&#1090;&#1088;&#1086;&#1085;&#1085;&#1072; &#1087;&#1086;&#1097;&#1072; : ";
$language['users.pass']     = "&#1042;&#1072;&#1096;&#1072;&#1090;&#1072; &#1087;&#1072;&#1088;&#1086;&#1083;&#1072;: ";
$language['users.remember'] = "&#1048;&#1089;&#1082;&#1072;&#1084; &#1076;&#1072; &#1086;&#1089;&#1090;&#1072;&#1085;&#1072; &#1074; &#1087;&#1088;&#1086;&#1092;&#1080;&#1083;&#1072; &#1089;&#1080;! ";
$language['users.forgot']   = "&#1053;&#1103;&#1084;&#1072;&#1090;&#1077; &#1076;&#1086;&#1089;&#1090;&#1098;&#1087; &#1076;&#1086; &#1087;&#1088;&#1086;&#1092;&#1080;&#1083;&#1072; &#1089;&#1080;?  ";

/// reg
$language['users.regname']  = " &#1048;&#1079;&#1073;&#1077;&#1088;&#1077;&#1090;&#1077; &#1087;&#1086;&#1090;&#1088;&#1077;&#1073;&#1080;&#1090;&#1077;&#1083;&#1089;&#1082;&#1086;&#1090;&#1086; &#1089;&#1080; &#1080;&#1084;&#1077;: ";
$language['users.regmail']  = " &#1045;&#1083;&#1077;&#1082;&#1090;&#1088;&#1086;&#1085;&#1072; &#1087;&#1086;&#1097;&#1072;: ";
$language['users.regpass']  = " &#1048;&#1079;&#1073;&#1077;&#1088;&#1077;&#1090;&#1077; &#1042;&#1072;&#1096;&#1072;&#1090;&#1072; &#1087;&#1072;&#1088;&#1086;&#1083;&#1072;: ";

/// main logged in
$language['users.welcome']  = "&#1044;&#1086;&#1073;&#1088;&#1077; &#1044;&#1086;&#1096;&#1083;&#1080;  ";


//buttons
$language['users.b.log']    = "Sign In";
$language['users.b.reg']    = "Sign Up";
   
   
define("USR_LAN_ERR_LOG_1"," No such user! ");
define("USR_LAN_ERR_LOG_2"," No such user! ");
define("USR_LAN_ERR_LOG_3"," Incorrect password! ");

define("USR_LAN_ERR_REG_REGULAR","Missed  required field:");

//Missed field
define("USR_LAN_ERR_REG_REQUIRE_USERPASS","Missed  required for password!");
define("USR_LAN_ERR_REG_REQUIRE_USERNAME","Missed  required for username!");
define("USR_LAN_ERR_REG_REQUIRE_USERMAIL","Missed  required for e-maail!");

define("USR_LAN_ERR_REG_VALID_USERMAIL","The e-mail address is invalid!");

//Taken field
define("USR_LAN_ERR_REG_TAKE_USERNAME","This username is already taken!");
define("USR_LAN_ERR_REG_TAKE_USERMAIL","This e-mail address already exist!");

define("USR_LAN_ERR_REG_ERROR_INSERT","Cannot insert User to MySQL database!");
define("USR_LAN_ERR_REG_ERROR_USERNAME","Cannot check Username from MySQL selection!");
define("USR_LAN_ERR_REG_ERROR_USERMAIL","Cannot check User e-mail from MySQL selection!");



define("USR_LAN_OK_REG","You have successfully signed up to our website <b>".$SQL_WEBSITE['site_name']."</b>.");

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

$language['users.days']     = "Days";
$language['users.months']   = "Months";
$language['users.years']    = "Years";
$language['users.hours']    = "Hours";
$language['users.minutes']  = "Minutes";
$language['users.seconds']  = "Seconds";

$language['users.avatar']   = "Your website picture";


//Recover password
$language['users.f.email']    = "Your e-mail:";
$language['users.f.cmail']    = "Your current email:";
$language['users.f.nmail']    = "Your new email:";
$language['users.f.title']    = "Website Data Recovery";
$language['users.f.messa']    = "To get back into your {site_name} account, you'll need to create a new password.";
$language['users.f.pdnm']     = "New passwords don't match";
$language['users.f.reset']    = "Reset your password now";
$language['users.f.resnw']    = "Link not working? Copy and paste the one below into your browser:";

//
// Change mail 
$language['users.chml.ttl']   ="You receive this message to change current email in {host}";
$language['users.chml.mss']   ="By clicking the link below you will be redirected to web page that will activate the  current mailbox.";
$language['users.chml.smss']   ="It is recommended to be signed-in like a user, before you continue.";
$language['users.chml.sendmail']   ="The email message for changing mailbox is sent successfully. <p>
Please check your requested mailbox.<p>";
$language['users.chml.sesexp'] ="Session for change of mailbox is expired.";

//
// User fields
$language['users.flds.name']    ="MySQL Field name";
$language['users.flds.desc']    ="Field description";
$language['users.flds.titl']    ="Field title";
$language['users.flds.fldty']    ="Field Type";
$language['users.flds.rscol']   ="Field rows/cols";
$language['users.flds.order']   ="Fields Order";
$language['users.flds.rowty']   ="Field type in MySQL";
$language['users.flds.attre']   ="HTML Field attributes";

$language['users.flds.defva']   ="Field Def.Value";
$language['users.flds.fld_require']="On Registration:";
$language['users.flds.reqy']    ="Require";
$language['users.flds.rehi']    ="Hide ";
$language['users.flds.reqn']    ="Display ";

// Forgotten password
$language['users.sent.mail']="The email for password recovery is sent successfully,</br > check your email and follow the steps written in the email.";
$language['users.sent.err'] = "Please Try Again!";
$language['users.sent.cont']="To continue please click <b><a href=\"/users\">here</a></b>";
$language['users.recov.errorhash'] = "Error in the given link";
$language['users.recov.changfor'] = "Changing password for username:";

$language['users.updated'] = "<p align=\"center\">New information is set in your profile!<br /><br />
    To continue go into <a href=\"main\">the user's main page</a> or <a href=\"/\">the website home page.</a></p>
";
$language['users.change.mail']="Change your email";
$language['users.change.password']="Change your password";
$language['users.timezone'] = "Your timezone"; 

// Messages
$language['users.messages.index']="Inbox";
$language['users.messages.inbox']="Inbox";
$language['users.messages.einbox']="No messages in your Inbox";
$language['users.messages.sendme']="Send New Message";
$language['users.messages.goback']="Main User Page";
$language['users.messages.readla']="Read Last Message";
$language['users.messages.select']="Select User:";
$language['users.messages.sendto']="Send Message To:";
$language['users.messages.uisect']="sending to:";
$language['users.messages.alertm']="To send a message, please fill in all fields.";
$language['users.messages.messtt']="Your Subject:";
$language['users.messages.messms']="Your Message:";
$language['users.messages.messuc']=" Message is sent successfully!";
$language['users.messages.messfr']="Message from";
$language['users.messages.mesimp']="Tick for Important";
$language['users.messages.mesrep']="Reply this message";
$language['users.messages.senttt']="Outbox";
$language['users.messages.mailtt']="Message Notification";
$language['users.messages.mailtx']="This notification is sent because you have important message from user ";
$language['users.messages.mailun']="Un-readed ";
$language['users.messages.mailre']="Readed ";
$language['users.messages.mailer']="Are you sure you want to delete ";


// View user
$language['users.view.notexist']="This user do not exist. <br /> Check your link";
$language['users.view.sendmess']="Send personal message";
$language['users.view.viewingu']="viewing ";

//
// Goodbye