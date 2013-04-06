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

$language['users.logname']  = "Felhasználónév vagy e-mail";
$language['users.pass']     = "Az Ön jelszava: ";
$language['users.remember'] = "Jelölje emlékezni! ";
$language['users.forgot']   = "Elfelejtette a jelszavát? ";

/// reg
$language['users.regname']  = " Felhasználónév: ";
$language['users.regmail']     = " Email: ";
$language['users.regpass']  = " Password: ";

/// main logged in
$language['users.welcome']  = "Fogadtatás ";


//buttons
$language['users.b.log']    = "Bejelentkezés";
$language['users.b.reg']    = "Regisztráció";


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

$language['users.log'] = "Website Bejelentkezés:";
$language['users.reg'] = "Website Regisztráció:";
$language['users.edi'] = "Szerkesztés felhasználó:";


//Cpanel
$language['p.users.id'] = "#";
$language['p.users.na'] = "username";
$language['p.users.pa'] = "password";
$language['p.users.em'] = "e-mail";
$language['p.users.ro'] = "szerepek";
$language['p.users.st'] = "állapot";
$language['p.users.st.v'] = "ellenőrzött";
$language['p.users.st.u'] = "ellenőrizetlen";
$language['p.users.mbf'] = "tag a";
$language['p.users.op'] = "művelet";
$language['p.users.hgr'] = "<small>Ön tudja menteni kilátás az asztalra, és használja az egyes ágazatokban</small>";
$language['p.users.mmls'] = "válasszuk a felhasználó lista";
$language['p.users.smsv'] = "Átfogó információ az e-mailt fog tartani egy \"könyvjelzőt\". ";
$language['p.users.mbmk'] = "Könyvjelzők ";
$language['p.users.omsm'] = "Egyéb listát küldjön ";
$language['p.users.omsh'] = "Külön e-maileket vesszővel  ";


$language['users.days']     = "Napok";
$language['users.months']   = "hónapok";
$language['users.years']    = "év";
$language['users.hours']    = "Óra";
$language['users.minutes']  = "jegyzőkönyv";
$language['users.seconds']  = "másodperc";

$language['users.avatar']   = "Webhelye kép";


//Recover password
$language['users.f.email']    = "Ön e-mail:";
$language['users.f.cmail']    = "Az Ön jelenlegi e-mail:";
$language['users.f.nmail']    = "Az új e-mail:";
$language['users.f.title']    = "Website adat gyógyulás";
$language['users.f.messa']    = "Ahhoz, hogy vissza a {SITE_NAME} fiókot, akkor létre kell hozni egy új jelszót.";
$language['users.f.pdnm']     = "Az új jelszavak nem egyeznek";
$language['users.f.reset']    = "Jelszó visszaállítása most";
$language['users.f.resnw']    = "Link nem működik? Másolja be a lenti a böngésző:";

//
// Change mail
$language['users.chml.ttl']   ="Ha megkapja ezt az üzenetet, hogy változtatja meg az aktuális e-mail-ben: {host}";
$language['users.chml.mss']   ="Ha rákattint az alábbi linkre, átirányítja az internetes oldal, amely aktiválja az aktuális postafiók.";
$language['users.chml.smss']   ="Javasoljuk, hogy aláírt-in, mint a felhasználó, mielőtt folytatja.";
$language['users.chml.sendmail']   ="Az e-mail üzenet megváltoztatása postafiók sikeresen elküldve. <p> Kérjük, ellenőrizze a kért postafiók.<p>";
$language['users.chml.sesexp'] ="Session váltási postafiók lejárt.";

//
// User fields
$language['users.flds.name']    ="MySQL mező neve";
$language['users.flds.desc']    ="mező leírása";
$language['users.flds.titl']    ="mező neve";
$language['users.flds.fldty']    ="mező típus";
$language['users.flds.rscol']   ="mező sorok / oszlopok";
$language['users.flds.order']   ="mező rendelés";
$language['users.flds.rowty']   ="mező típus in MySQL";
$language['users.flds.attre']   ="HTML mező attribútumok";

$language['users.flds.defva']   ="mező határozott érték";
$language['users.flds.fld_require']="on regisztráció:";
$language['users.flds.reqy']    ="kíván";
$language['users.flds.rehi']    ="elrejt ";
$language['users.flds.reqn']    ="kijelző ";

// Forgotten password
$language['users.sent.mail']="Az e-mail a jelszó visszaszerzés elküldése sikeres volt, </ br> e-mailjeit, és kövesse a lépéseket írt e-mailben.";
$language['users.sent.err'] = "Kérjük, próbálja újra!";
$language['users.sent.cont']="A folytatáshoz kattintson<b><a href='/users'>here</a></b>";
$language['users.recov.errorhash'] = "Hiba történt az adott kapcsolat";
$language['users.recov.changfor'] = "Megváltoztatása jelszó username:";

$language['users.updated'] = "<p align='center'>Új információ van beállítva a profiljában!<br /><br />
   Ha továbbra is megy <a href='main'>A felhasználó főoldal</a> or <a href='/'>A honlap honlap.</a></p>
";
$language['users.change.mail']="Módosítása az e-mail";
$language['users.change.password']="A jelszó módosítása";
$language['users.timezone'] = "Az időzóna";

// Messages
$language['users.messages.index']="Inbox";
$language['users.messages.inbox']="Inbox";
$language['users.messages.einbox']="No üzeneteket a Inbox";
$language['users.messages.sendme']="Új üzenet küldése";
$language['users.messages.goback']="Fő felhasználói oldal";
$language['users.messages.readla']="Olvasás Utolsó üzenet";
$language['users.messages.select']="Felhasználó kiválasztása:";
$language['users.messages.sendto']="Üzenet küldése:";
$language['users.messages.uisect']="küldés:";
$language['users.messages.alertm']="Üzenet küldéséhez, kérjük, töltse ki az összes mezőt.";
$language['users.messages.messtt']="Ön Tárgy:";
$language['users.messages.messms']="Az Ön üzenete:";
$language['users.messages.messuc']=" Az üzenet elküldése sikeres!";
$language['users.messages.messfr']="Üzenet";
$language['users.messages.mesimp']="Jelölje meg a fontos";
$language['users.messages.mesrep']="Válasz az üzenetre";
$language['users.messages.senttt']="Kimenõ";
$language['users.messages.mailtt']="Értesítés Értesítés";
$language['users.messages.mailtx']="Ez az értesítés érkezik, mert fontos üzenetet a felhasználó ";
$language['users.messages.mailun']="Un-olvas flashből";
$language['users.messages.mailre']="olvas flashből ";
$language['users.messages.mailer']="Biztos benne, hogy törölni szeretné ";


// View user
$language['users.view.notexist']="Ez a felhasználó nem létezik.<br /> Ellenőrizze a link";
$language['users.view.sendmess']="Személyes üzenet küldése";
$language['users.view.viewingu']="megtekintők ";

// User review
$language['users.revw.usrfromt'] = " Tagja";
$language['users.revw.changeph'] = " Változtasd meg a fénykép";
$language['users.revw.authprov'] = " Ellátó";
$language['users.revw.authnone'] = " Nem biztosított";
$language['users.revw.picdescr'] = " Feltöltése után a kép újra az oldalt, hogy küldje el a fényképet picker!";
$language['users.revw.piclickr'] = " Fotó választás";
$language['users.revw.picuplod'] = " Fotó feltöltése";

//
// Goodbye