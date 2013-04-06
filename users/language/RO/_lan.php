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

$language['users.logname']  = "Nume de utilizator sau e-mail ";
$language['users.pass']     = "Parola: ";
$language['users.remember'] = "Bifați să-și amintească! ";
$language['users.forgot']   = "Ai uitat parola? ";

/// reg
$language['users.regname']  = " Nume utilizator:";
$language['users.regmail']     = " Email: ";
$language['users.regpass']  = " Password: ";

/// main logged in
$language['users.welcome']  = "bine ai venit ";


//buttons
$language['users.b.log']    = "Conectați-vă";
$language['users.b.reg']    = "Înregistrează-te";


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

$language['users.log'] = "Site-ul Autentificare:";
$language['users.reg'] = "Înregistrare Site-ul: ";
$language['users.edi'] = "Editare de utilizare: ";


//Cpanel
$language['p.users.id'] = "#";
$language['p.users.na'] = "nume de utilizator";
$language['p.users.pa'] = "parolă";
$language['p.users.em'] = "e-mail";
$language['p.users.ro'] = "roluri";
$language['p.users.st'] = "starea";
$language['p.users.st.v'] = "verificate";
$language['p.users.st.u'] = "neverificate";
$language['p.users.mbf'] = "membru pentru ";
$language['p.users.op'] = "operațiuni";
$language['p.users.hgr'] = "<small>You can save view from table and use it in other sectors</small>";
$language['p.users.mmls'] = "select user list";
$language['p.users.smsv'] = "Comprehensive information on this email will be kept as a bookmark. ";
$language['p.users.mbmk'] = "Bookmarks ";
$language['p.users.omsm'] = "Other list to send ";
$language['p.users.omsh'] = "Seperate  emails with comma  ";

$language['users.days']     = "zi";
$language['users.months']   = "Lună";
$language['users.years']    = "anii";
$language['users.hours']    = "ore";
$language['users.minutes']  = "minute";
$language['users.seconds']  = "secunde";

$language['users.avatar']   = "Site-ul dvs. imagine";


//Recover password
$language['users.f.email']    = "Email-ul Dvs: ";
$language['users.f.cmail']    = "Email-ul Dvs curentă: ";
$language['users.f.nmail']    = "Your new email:";
$language['users.f.title']    = "Site-ul de recuperare de date ";
$language['users.f.messa']    = "Pentru a obține înapoi în dvs. {SITE_NAME} cont, va trebui să creați o parolă nouă.";
$language['users.f.pdnm']     = "Parole noi nu se potrivesc";
$language['users.f.reset']    = "Schimbati-va parola acum";
$language['users.f.resnw']    = "Nu LINK lucru Copiați și lipiți cea de mai jos in browser-ul dvs.:?";

//
// Change mail
$language['users.chml.ttl']   ="Veți primi acest mesaj de e-mail pentru a schimba actuala în {} gazdă";
$language['users.chml.mss']   ="Făcând clic pe link-ul de mai jos veți fi redirecționat către pagina de web, care va activa cutia poștală curentă.";
$language['users.chml.smss']   ="Se recomandă să fie semnat-in ca un utilizator, înainte de a continua.";
$language['users.chml.sendmail']   ="Mesajul e-mail pentru a schimba cutia poștală este trimis cu succes <vă rugăm să verificați căsuța poștală a solicitat <p>.";
$language['users.chml.sesexp'] ="Sesiunea de schimbare a cutiei poștale a expirat.";

//
// User fields
$language['users.flds.name']    ="MySQL nume câmp";
$language['users.flds.desc']    ="câmp descriere";
$language['users.flds.titl']    ="domeniu titlu";
$language['users.flds.fldty']    ="câmpul Tip";
$language['users.flds.rscol']   ="Rânduri de câmp / cols";
$language['users.flds.order']   ="Domenii de comandă ";
$language['users.flds.rowty']   ="Tip de câmp în MySQL";
$language['users.flds.attre']   ="HTML Atributele de câmp ";

$language['users.flds.defva']   ="Domeniul Def.Value ";
$language['users.flds.fld_require']="Pe de înregistrare: ";
$language['users.flds.reqy']    ="necesita";
$language['users.flds.rehi']    ="ascunde ";
$language['users.flds.reqn']    ="afișa ";

// Forgotten password
$language['users.sent.mail']="E-mail pentru recuperarea parolei este trimis cu succes, </ br> verifica adresa dvs. de email și urmați pașii scrise în e-mail ";
$language['users.sent.err'] = "Vă rugăm să încercați din nou !";
$language['users.sent.cont']="Pentru a continua rugăm să faceți clic <b><a href='/users'>here</a></b>";
$language['users.recov.errorhash'] = "Eroare în link-ul dat";
$language['users.recov.changfor'] = "Schimbarea parolei pentru numele de utilizator:";

$language['users.updated'] = "<p align='center'> Noua informație este setat în profilul dvs.! <br /> <br />
     Pentru a continua merge în href='main'> <a utilizatorului pagina principala </ a> sau <a href='/'> pagina site-ului </ a>. </ P>
";
$language['users.change.mail']="Schimbați adresa dvs. de email ";
$language['users.change.password']="Schimbați-vă parola ";
$language['users.timezone'] = "Fusul orar ";

// Messages
$language['users.messages.index']="Primite";
$language['users.messages.inbox']="Primite";
$language['users.messages.einbox']="Nu există mesaje în Inbox ";
$language['users.messages.sendme']="Trimite un nou mesaj ";
$language['users.messages.goback']="Pagina utilizatorului principal ";
$language['users.messages.readla']="Citește Ultimul mesaj";
$language['users.messages.select']="Selectați User:";
$language['users.messages.sendto']="Trimite mesaj lui:";
$language['users.messages.uisect']="trimiterea la:";
$language['users.messages.alertm']="Pentru a trimite un mesaj, vă rugăm să completați toate câmpurile. ";
$language['users.messages.messtt']="dvs. Subiect:";
$language['users.messages.messms']="Mesajul tău:";
$language['users.messages.messuc']=" Mesajul este trimis cu succes!";
$language['users.messages.messfr']="mesaj de la";
$language['users.messages.mesimp']="Bifați pentru importante ";
$language['users.messages.mesrep']="Răspundeți la acest mesaj";
$language['users.messages.senttt']="outbox";
$language['users.messages.mailtt']="Notificarea mesajul ";
$language['users.messages.mailtx']="Această notificare este trimis pentru că aveți mesaj important de la utilizator ";
$language['users.messages.mailun']="necitit ";
$language['users.messages.mailre']="se citi ";
$language['users.messages.mailer']="Sunteți sigur că doriți să ștergeți ";


// View user
$language['users.view.notexist']="Acest utilizator nu există <br /> Verificați link-ul tău.";
$language['users.view.sendmess']="Trimite mesaj personal";
$language['users.view.viewingu']="vizionează";

$language['users.revw.usrfromt'] = " Membru pentru";
$language['users.revw.changeph'] = " Schimbați fotografia ta";
$language['users.revw.authprov'] = " Furnizor";
$language['users.revw.authnone'] = " Nu cu condiția";
$language['users.revw.picdescr'] = " După încărcarea imaginii să reîncărcați pagina pentru a trimite in pentru a selectorul de fotografie!";
$language['users.revw.piclickr'] = " Foto alegere";
$language['users.revw.picuplod'] = " Fotografie de încărcare";

//
// Goodbye