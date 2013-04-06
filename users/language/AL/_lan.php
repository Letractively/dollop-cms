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

$language['users.logname']  = "Username ose e-mail";
$language['users.pass']     = "Fjalлkalimi juaj: ";
$language['users.remember'] = "Shлnoni pлr tл kujtuar! ";
$language['users.forgot']   = "Keni harruar fjalлkalimin?";

/// reg
$language['users.regname']  = " Emri i pлrdoruesit: ";
$language['users.regmail']     = " Email: ";
$language['users.regpass']  = " fjalлkalimi: ";

/// main logged in
$language['users.welcome']  = "i mirлpritur ";


//buttons
$language['users.b.log']    = "Sign In";
$language['users.b.reg']    = "Regjistrohu";


define("USR_LAN_ERR_LOG_1"," Asnjл pлrdorues tл tillл! ");
define("USR_LAN_ERR_LOG_2"," Asnjл pлrdorues tл tillл! ");
define("USR_LAN_ERR_LOG_3"," Fjalлkalimi i gabuar! ");

define("USR_LAN_ERR_REG_REGULAR","Missed fushл tл kлrkuara:");

//Missed field
define("USR_LAN_ERR_REG_REQUIRE_USERPASS","Humbur kлrkohet pлr fjalлkalimin!");
define("USR_LAN_ERR_REG_REQUIRE_USERNAME","Humbur kлrkohet pлr emrin!");
define("USR_LAN_ERR_REG_REQUIRE_USERMAIL","Humbur kлrkuar pлr e-maail!");

define("USR_LAN_ERR_REG_VALID_USERMAIL","E-mail adresa лshtл e pavlefshme!");

//Taken field
define("USR_LAN_ERR_REG_TAKE_USERNAME","Ky identifikim лshtл marrл tashmл!");
define("USR_LAN_ERR_REG_TAKE_USERMAIL","Kjo adresл e-mail tashmл ekziston!");

define("USR_LAN_ERR_REG_ERROR_INSERT","Nuk mund tл futur pлr pлrdoruesin MySQL database!");
define("USR_LAN_ERR_REG_ERROR_USERNAME","Nuk mund tл kontrolloni Username nga zgjedhja MySQL!");
define("USR_LAN_ERR_REG_ERROR_USERMAIL","Nuk mund tл kontrolloni pлrdoruesin e-mail nga zgjedhja MySQL!");



define("USR_LAN_OK_REG","Ju keni nлnshkruar me sukses deri nл faqen tonл tл internetit <b>".$SQL_WEBSITE['site_name']."</b>.");

$language['users.log'] = "Identifikohu website:";
$language['users.reg'] = "Regjistrimi website:";
$language['users.edi'] = "Edit pлrdorues:";


//Cpanel
$language['p.users.id'] = "#";
$language['p.users.na'] = "emrin";
$language['p.users.pa'] = "fjalлkalimi";
$language['p.users.em'] = "e-mail";
$language['p.users.ro'] = "rolet";
$language['p.users.st'] = "status";
$language['p.users.st.v'] = "verifikuar";
$language['p.users.st.u'] = "paverifikuar";
$language['p.users.mbf'] = "anлtare pлr";
$language['p.users.op'] = "operacionet";
$language['p.users.hgr'] = "<small>You can save view from table and use it in other sectors</small>";
$language['p.users.mmls'] = "select user list";
$language['p.users.smsv'] = "Comprehensive information on this email will be kept as a bookmark. ";
$language['p.users.mbmk'] = "Bookmarks ";
$language['p.users.omsm'] = "Other list to send ";
$language['p.users.omsh'] = "Seperate  emails with comma  ";

$language['users.days']     = "ditл";
$language['users.months']   = "muaj";
$language['users.years']    = "vjet";
$language['users.hours']    = "orл";
$language['users.minutes']  = "minuta";
$language['users.seconds']  = "sekonda";

$language['users.avatar']   = "Avatar";


//Recover password
$language['users.f.email']    = "Juaj e-mail:";
$language['users.f.cmail']    = "Emaili juaj e tanishme:";
$language['users.f.nmail']    = "Emaili juaj e re:";
$language['users.f.title']    = "Data Rimлkлmbjes website";
$language['users.f.messa']    = "Pлr tл marrл pлrsлri nл llogarinл tuaj {site_name} , ju do tл duhet pлr tл krijuar njл fjalлkalim tл ri.";
$language['users.f.pdnm']     = "Fjalлkalimet e reja nuk pлrputhen";
$language['users.f.reset']    = "Rivendosni fjalлkalimin tuaj tani";
$language['users.f.resnw']    = "Link nuk punon? Kopjoni dhe ngjisni njл poshtл nл shfletuesin tuaj:";

//
// Change mail
$language['users.chml.ttl']   ="Ju merrni kлtл mesazh pлr tл ndryshuar email aktuale nл {host}";
$language['users.chml.mss']   ="Duke klikuar lidhjen mл poshtл ju do tл ridrejtuar nл faqen e internetit qл do tл aktivizojл kuti postare e tanishme.";
$language['users.chml.smss']   ="Лshtл e rekomanduar pлr tл nлnshkruar-nл si njл pлrdorues, para se tл vazhdojл.";
$language['users.chml.sendmail']   ="Mesazhi mail pлr ndryshimin e kuti postare лshtл dлrguar me sukses.<p>Ju lutem kontrolloni kutinл tuaj postare kлrkuar.<p>";
$language['users.chml.sesexp'] ="Seanca pлr ndryshimin e kuti postare ka skaduar.";

//
// User fields
$language['users.flds.name']    ="MySQL Emri fusha";
$language['users.flds.desc']    ="pлrshkrimi fushл";
$language['users.flds.titl']    ="Titulli fushл";
$language['users.flds.fldty']    ="Lloji fushл";
$language['users.flds.rscol']   ="Rreshtave nл terren / cols";
$language['users.flds.order']   ="fushat Rendit";
$language['users.flds.rowty']   ="Lloji fushл nл MySQL";
$language['users.flds.attre']   ="HTML atributet fushл";

$language['users.flds.defva']   ="Fusha e parazgjedhur vlera";
$language['users.flds.fld_require']="pлr Regjistrimin:";
$language['users.flds.reqy']    ="kлrkon";
$language['users.flds.rehi']    ="fsheh ";
$language['users.flds.reqn']    ="shfaq ";

// Forgotten password
$language['users.sent.mail']="Mail pлr shлrim fjalлkalimin лshtл dлrguar me sukses,</br > kontrolloni email tuaj dhe ndiqni hapat e shkruara nл e-mail.";
$language['users.sent.err'] = "Ju lutem provoni pлrsлri!";
$language['users.sent.cont']="Pлr tл vazhduar klikoni <b><a href='/users'>kлtu</a></b>";
$language['users.recov.errorhash'] = "Gabim nл lidhjen e dhлnл";
$language['users.recov.changfor'] = "Fjalлkalimi ndryshuar pлr emrin:";

$language['users.updated'] = "<p align='center'>New information is set in your profile!<br /><br />
    To continue go into <a href='main'>the user's main page</a> or <a href='/'>the website home page.</a></p>
";
$language['users.change.mail']="Ndryshimi email-it tuaj";
$language['users.change.password']="Ndrysho fjalлkalimin tuaj";
$language['users.timezone'] = "timezone tuaj";

// Messages
$language['users.messages.index']="Inbox";
$language['users.messages.inbox']="Inbox";
$language['users.messages.einbox']="Nuk ka mesazhe nл kutinл tuaj";
$language['users.messages.sendme']="Dлrgo mesazh tл ri";
$language['users.messages.goback']="Faqja Kryesore User";
$language['users.messages.readla']="Lexoni Mesazhi i fundit";
$language['users.messages.select']="Zgjidh pлrdoruesin:";
$language['users.messages.sendto']="Dлrgo mesazh Tл:";
$language['users.messages.uisect']="dлrguar pлr:";
$language['users.messages.alertm']="Pлr tл dлrguar mesazh, ju lutemi plotлsoni tл gjitha fushat.";
$language['users.messages.messtt']="Subjekti juaj:";
$language['users.messages.messms']="Mesazhi juaj:";
$language['users.messages.messuc']=" Mesazhi лshtл dлrguar me sukses!";
$language['users.messages.messfr']="mesazh nga";
$language['users.messages.mesimp']="Tick ??pлr Rлndлsishme";
$language['users.messages.mesrep']="Pлrgjigju kлtл mesazh";
$language['users.messages.senttt']="Dalje";
$language['users.messages.mailtt']="Njoftimi mesazh";
$language['users.messages.mailtx']="Ky njoftim лshtл dлrguar pлr shkak se ju keni mesazh tл rлndлsishлm nga pлrdoruesi ";
$language['users.messages.mailun']="i palexuar ";
$language['users.messages.mailre']="lexoj ";
$language['users.messages.mailer']="Jeni te sigurte qe doni te fshini ";


// View user
$language['users.view.notexist']="Ky pлrdorues nuk ekzistojnл.<br /> Kontrolloni lidhjen tuaj";
$language['users.view.sendmess']="Dлrgo mesazh personal";
$language['users.view.viewingu']="shikimin ";

// User review
$language['users.revw.usrfromt'] = " Anëtar që prej";
$language['users.revw.changeph'] = " Ndrysho foton tuaj";
$language['users.revw.authprov'] = " Ofrues";
$language['users.revw.authnone'] = " nuk sigurohet";
$language['users.revw.picdescr'] = " Pasi ngarkimi foto ringarkoni faqen për të dërguar atë në zgjedhjen foto!";
$language['users.revw.piclickr'] = " zgjedhja foto";
$language['users.revw.picuplod'] = " Foto upload";

//
// Goodbye