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

$language['users.logname']  = "Užívateľské meno alebo email";
$language['users.pass']     = "Vaše heslo: ";
$language['users.remember'] = "Zaškrtnite pamätať! ";
$language['users.forgot']   = "Zabudli ste heslo? ";

/// reg
$language['users.regname']  = " užívateľské meno: ";
$language['users.regmail']     = " Email: ";
$language['users.regpass']  = " Password: ";

/// main logged in
$language['users.welcome']  = "Vitajte ";


//buttons
$language['users.b.log']    = "Prihlásiť";
$language['users.b.reg']    = "Registrácia";


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

$language['users.log'] = "Webová stránka Login: ";
$language['users.reg'] = "Webové stránky Registrácia:";
$language['users.edi'] = "Upraviť užívateľa:";


//Cpanel
$language['p.users.id'] = "#";
$language['p.users.na'] = "meno";
$language['p.users.pa'] = "heslo";
$language['p.users.em'] = "e-mail";
$language['p.users.ro'] = "role";
$language['p.users.st'] = "postavenie";
$language['p.users.st.v'] = "overená";
$language['p.users.st.u'] = "unverified";
$language['p.users.mbf'] = "člen,";
$language['p.users.op'] = "operácie";
$language['p.users.hgr'] = "<small>You can save view from table and use it in other sectors</small>";
$language['p.users.mmls'] = "select user list";
$language['p.users.smsv'] = "Comprehensive information on this email will be kept as a bookmark. ";
$language['p.users.mbmk'] = "Bookmarks ";
$language['p.users.omsm'] = "Other list to send ";
$language['p.users.omsh'] = "Seperate  emails with comma  ";


$language['users.days']     = "dni";
$language['users.months']   = "mesiacov";
$language['users.years']    = "roky";
$language['users.hours']    = "hodiny";
$language['users.minutes']  = "zápis";
$language['users.seconds']  = "sekundy";

$language['users.avatar']   = "Vaše webové stránky obrázok";


//Recover password
$language['users.f.email']    = "Váš e-mail: ";
$language['users.f.cmail']    = "Vaša aktuálna e-mail:";
$language['users.f.nmail']    = "Tvoj nový email:";
$language['users.f.title']    = "Webové stránky pre obnovu dát";
$language['users.f.messa']    = "Ak sa chcete dostať späť do svojho {SITE_NAME} účtu, budete musieť vytvoriť nové heslo.";
$language['users.f.pdnm']     = "Nová heslá sa nezhodujú";
$language['users.f.reset']    = "Obnoviť teraz svoje heslo";
$language['users.f.resnw']    = "LINK nefunguje, skopírujte a vložte jeden z nich pod do svojho prehliadača:?";

//
// Change mail
$language['users.chml.ttl']   ="Táto správa sa zmeniť súčasný e-mail v {hostiteľovi}";
$language['users.chml.mss']   ="Kliknutím na odkaz nižšie budete presmerovaní na webovú stránku, ktorá sa bude aktivovať aktuálne schránku.";
$language['users.chml.smss']   ="Odporúča sa, aby sa podpísal v ako užívateľ, než budete pokračovať.";
$language['users.chml.sendmail']   ="E-mailová správa pre zmenu poštovej schránky úspešne odoslaná <skontrolujte prosím požadovanú schránku <p> ..";
$language['users.chml.sesexp'] ="Session pre zmenu poštovej schránky vypršala.";

//
// User fields
$language['users.flds.name']    ="MySQLNázov poľa";
$language['users.flds.desc']    ="Popis polí";
$language['users.flds.titl']    ="pole Názov";
$language['users.flds.fldty']    ="Field Type";
$language['users.flds.rscol']   ="Pole riadkov / stĺpcov";
$language['users.flds.order']   ="pole Objednávka";
$language['users.flds.rowty']   ="Poľa zadajte MySQL";
$language['users.flds.attre']   ="HTML poľné atribúty";

$language['users.flds.defva']   ="Pole Určitý Value";
$language['users.flds.fld_require']="O registráciu:";
$language['users.flds.reqy']    ="požadovať";
$language['users.flds.rehi']    ="schovať ";
$language['users.flds.reqn']    ="Zobraziť ";

// Forgotten password
$language['users.sent.mail']="E-mail pre obnovenie hesla je úspešne odoslaná, </ br> skontrolujte svoj ​​e-mail a postupujte podľa pokynov napísané v e-mailu.";
$language['users.sent.err'] = "Prosím, skúste to znova!";
$language['users.sent.cont']="Ak chcete pokračovať, kliknite prosím <b><a href='/users'>here</a></b>";
$language['users.recov.errorhash'] = "Chyba v danom odkaze ";
$language['users.recov.changfor'] = "Zmena hesla pre používateľské meno: ";

$language['users.updated'] = "<p Align='center'> Nové informácie sa nachádza vo vašom profile! <br /> <br />
   Ak chcete pokračovať ísť do <a href='main'> užívateľa hlavnú stránku </ a> alebo <a href='/'> stránky domovskú stránku. </ A> </ p>
";
$language['users.change.mail']="Zmeňte svoj ​​e-mail ";
$language['users.change.password']="Zmeniť heslo ";
$language['users.timezone'] = "Vaše časové pásmo";

// Messages
$language['users.messages.index']="Inbox";
$language['users.messages.inbox']="Inbox";
$language['users.messages.einbox']="Žiadne správy v priečinku Doručená pošta";
$language['users.messages.sendme']="Odoslať novú správu";
$language['users.messages.goback']="Hlavné Užívateľ Page";
$language['users.messages.readla']="Prečítajte si posledné správy";
$language['users.messages.select']="vybrať užívateľa:";
$language['users.messages.sendto']="Poslať správu:";
$language['users.messages.uisect']="zasielanie:";
$language['users.messages.alertm']="Ak chcete poslať správu, prosím, vyplňte všetky polia.";
$language['users.messages.messtt']="Vaša Predmet:";
$language['users.messages.messms']="Vaša správa:";
$language['users.messages.messuc']=" Správa bola úspešne odoslaná!";
$language['users.messages.messfr']="správa z";
$language['users.messages.mesimp']="Zaškrtnite pre Dôležitét";
$language['users.messages.mesrep']="Odpovedať na tento odkaz";
$language['users.messages.senttt']="Na odoslanie";
$language['users.messages.mailtt']="správa Oznámenie";
$language['users.messages.mailtx']="Toto oznámenie je odoslaná, pretože budete mať dôležitú správu od užívateľa ";
$language['users.messages.mailun']="neprečítaný ";
$language['users.messages.mailre']="čítať ";
$language['users.messages.mailer']="Ste si istí, že chcete zmazať ";


// View user
$language['users.view.notexist']="Tento užívateľ neexistuje <br /> či máš odkaz.";
$language['users.view.sendmess']="Poslať osobnú správu";
$language['users.view.viewingu']="prehliadanie ";

// User review
$language['users.revw.usrfromt'] = " členom už";
$language['users.revw.changeph'] = " Zmeniť fotografiu";
$language['users.revw.authprov'] = " Poskytovateľ";
$language['users.revw.authnone'] = " nie je k dispozícii";
$language['users.revw.picdescr'] = " Po nahraní fotografie znovu načítať stránku pošlite ho na fotografiu výberu!";
$language['users.revw.piclickr'] = " Foto výber";
$language['users.revw.picuplod'] = " Nahrajte";

//
// Goodbye