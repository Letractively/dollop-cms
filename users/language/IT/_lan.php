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

$language['users.logname']  = "Nome utente o e-mail ";
$language['users.pass']     = "la password: ";
$language['users.remember'] = "Spuntare per ricordare! ";
$language['users.forgot']   = "Hai dimenticato la password? ";

/// reg
$language['users.regname']  = " nome utentee: ";
$language['users.regmail']     = " Email: ";
$language['users.regpass']  = " Password: ";

/// main logged in
$language['users.welcome']  = "benvenuto ";


//buttons
$language['users.b.log']    = "Accedi";
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

$language['users.log'] = "Sito Accedi:";
$language['users.reg'] = "Registrazione al Sito:";
$language['users.edi'] = "Modifica utente:";


//Cpanel
$language['p.users.id'] = "#";
$language['p.users.na'] = "nome utente";
$language['p.users.pa'] = "password";
$language['p.users.em'] = "e-mail";
$language['p.users.ro'] = "ruoli";
$language['p.users.st'] = "stato";
$language['p.users.st.v'] = "verificato";
$language['p.users.st.u'] = "non verificato";
$language['p.users.mbf'] = "membro per";
$language['p.users.op'] = "le operazioni di";
$language['p.users.hgr'] = "<small>You can save view from table and use it in other sectors</small>";
$language['p.users.mmls'] = "select user list";
$language['p.users.smsv'] = "Comprehensive information on this email will be kept as a bookmark. ";
$language['p.users.mbmk'] = "Bookmarks ";
$language['p.users.omsm'] = "Other list to send ";
$language['p.users.omsh'] = "Seperate  emails with comma  ";


$language['users.days']     = "giorni";
$language['users.months']   = "Mesi";
$language['users.years']    = "anni";
$language['users.hours']    = "orario";
$language['users.minutes']  = "minuti";
$language['users.seconds']  = "secondi";

$language['users.avatar']   = "Il tuo sito web foto";


//Recover password
$language['users.f.email']    = "Il tuo indirizzo email:";
$language['users.f.cmail']    = "Il tuo indirizzo email corrente:";
$language['users.f.nmail']    = "La tua e-mail nuovo:";
$language['users.f.title']    = "sito dati recupero";
$language['users.f.messa']    = "Per tornare al tuo {} site_name account, sarà necessario creare una nuova password.";
$language['users.f.pdnm']     = "Nuove password non coincidono";
$language['users.f.reset']    = "Per reimpostare la password";
$language['users.f.resnw']    = "Link non funziona? Copia e incolla il sottostante nel tuo browser:";

//
// Change mail
$language['users.chml.ttl']   ="Viene visualizzato questo messaggio di posta elettronica a cambiare corrente {host}";
$language['users.chml.mss']   ="Cliccando sul link sottostante verrete reindirizzati alla pagina Web che attiverà la cassetta postale corrente ";
$language['users.chml.smss']   ="Si raccomanda di essere firmato-in come un utente, prima di continuare. ";
$language['users.chml.sendmail']   ="Il messaggio e-mail per la modifica delle cassette postali è stato inviato correttamente <controlla la tua casella di posta richiesto <p> ";
$language['users.chml.sesexp'] ="Sessione per il cambio di cassetta postale è scaduto ";

//
// User fields
$language['users.flds.name']    ="MySQL nome campo";
$language['users.flds.desc']    ="descrizione del campo";
$language['users.flds.titl']    ="campo titolo";
$language['users.flds.fldty']    ="Tipo di campo";
$language['users.flds.rscol']   ="Righe di campo / colleghis";
$language['users.flds.order']   ="campi d'ordine";
$language['users.flds.rowty']   ="Tipo di campo in MySQL";
$language['users.flds.attre']   ="HTML campo degli attributi";

$language['users.flds.defva']   ="Campo valore definito";
$language['users.flds.fld_require']="su registrazione:";
$language['users.flds.reqy']    ="richiedere";
$language['users.flds.rehi']    ="nascondere ";
$language['users.flds.reqn']    ="display ";

// Forgotten password
$language['users.sent.mail']="L'e-mail per il recupero della password è stato inviato correttamente, </ br> controllare la posta elettronica e seguire le istruzioni scritte in questo messaggio. ";
$language['users.sent.err'] = "Riprovare!";
$language['users.sent.cont']="Per continuare fare clic<b><a href='/users'>here</a></b>";
$language['users.recov.errorhash'] = "Errore nel link indicato ";
$language['users.recov.changfor'] = "Cambiare la password di utente: ";

$language['users.updated'] = "<p align='center'> Nuove informazioni si trova nel tuo profilo! <br /> <br />
     Per continuare a andare in href='main'> <a pagina principale dell'utente </ a> o href='/'> <a home page del sito. </ A> </ p>
";
$language['users.change.mail']="Cambia la tua e-mail ";
$language['users.change.password']="Modificare la password ";
$language['users.timezone'] = "Il fuso orario ";

// Messages
$language['users.messages.index']="Posta in arrivo";
$language['users.messages.inbox']="Posta in arrivo";
$language['users.messages.einbox']="Non ci sono messaggi nella cartella Posta in arrivo";
$language['users.messages.sendme']="Invia nuovo messaggio ";
$language['users.messages.goback']="Utente Pagina principale ";
$language['users.messages.readla']="Leggi messaggio Ultimo ";
$language['users.messages.select']="Selezionare Utente: ";
$language['users.messages.sendto']="Invia messaggio a: ";
$language['users.messages.uisect']="l'invio a: ";
$language['users.messages.alertm']="Per inviare un messaggio, si prega di compilare tutti i campi. ";
$language['users.messages.messtt']="Il tuo Oggetto: ";
$language['users.messages.messms']="Il tuo messaggio:";
$language['users.messages.messuc']=" Il messaggio viene inviato con successo! ";
$language['users.messages.messfr']="Messaggio";
$language['users.messages.mesimp']="Tick per importante";
$language['users.messages.mesrep']="Rispondi questo messaggio ";
$language['users.messages.senttt']="In uscita";
$language['users.messages.mailtt']="Messaggio di notifica ";
$language['users.messages.mailtx']="Questa notifica viene inviata perché avete messaggio importante da utente ";
$language['users.messages.mailun']="non letto ";
$language['users.messages.mailre']="letto ";
$language['users.messages.mailer']="Sei sicuro di voler eliminare";


// View user
$language['users.view.notexist']="Questo utente non esiste <br /> Controlla il tuo link ";
$language['users.view.sendmess']="Invia un messaggio personale ";
$language['users.view.viewingu']="visualizzato ";

// User review
$language['users.revw.usrfromt'] = " Membro per";
$language['users.revw.changeph'] = " Cambia la tua foto";
$language['users.revw.authprov'] = " Provider";
$language['users.revw.authnone'] = " Non fornito";
$language['users.revw.picdescr'] = " Dopo aver caricato l'immagine ricaricare la pagina per inviarlo a selettore foto!";
$language['users.revw.piclickr'] = " Foto scelta";
$language['users.revw.picuplod'] = " Foto di caricamento";

//
// Goodbye