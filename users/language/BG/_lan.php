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

$language['users.logname'] = "Потребителско име или e-mail ";
$language['users.pass'] = "Вашата парола: ";
$language['users.remember'] = "Отбележете за да се запомни! ";
$language['users.forgot'] = "Забравена парола? ";

/// reg
$language['users.regname'] = " Потребителско име: ";
$language['users.regmail'] = " E-mail: ";
$language['users.regpass'] = " Парола: ";

/// main logged in
$language['users.welcome'] = "Добре дошли ";


//buttons
$language['users.b.log'] = "Вход";
$language['users.b.reg'] = "Регистрация";


define("USR_LAN_ERR_LOG_1", " Няма такъв потребител! ");
define("USR_LAN_ERR_LOG_2", " Няма такъв потребител! ");
define("USR_LAN_ERR_LOG_3", " Некоректна парола! ");

define("USR_LAN_ERR_REG_REGULAR", "Пропуснато задължително поле:");

//Missed field
define("USR_LAN_ERR_REG_REQUIRE_USERPASS", "Пропуснато задължително поле Парола!");
define("USR_LAN_ERR_REG_REQUIRE_USERNAME", "Пропуснато задължително поле Потребителско име!");
define("USR_LAN_ERR_REG_REQUIRE_USERMAIL", "Пропуснато задължително поле e-maail!");

define("USR_LAN_ERR_REG_VALID_USERMAIL", "Не валиден e-mail адрес!");

//Taken field
define("USR_LAN_ERR_REG_TAKE_USERNAME", "Това потребителско име вече е заето!");
define("USR_LAN_ERR_REG_TAKE_USERMAIL", "Този e-mail адрес вече съществува!");

define("USR_LAN_ERR_REG_ERROR_INSERT", "Не може да въведете потребител MySQL база данни!");
define("USR_LAN_ERR_REG_ERROR_USERNAME", "Не може да провери в базата данни за потребителско име!");
define("USR_LAN_ERR_REG_ERROR_USERMAIL", "Не може да провери в базата данни за потребителски e-mail!");



define("USR_LAN_OK_REG", "Вие успешно са се регистрирали в нашия сайт <b>" . $SQL_WEBSITE['site_name'] . "</b>.");

$language['users.log'] = "Уеб сайт вход:";
$language['users.reg'] = "Уеб сайт регистрация:";
$language['users.edi'] = "Редактиране на потребител:";


//Cpanel
$language['p.users.id'] = "#";
$language['p.users.na'] = "Потребителско име";
$language['p.users.pa'] = "Парола";
$language['p.users.em'] = "E-mail";
$language['p.users.ro'] = "права";
$language['p.users.st'] = "статус";
$language['p.users.st.v'] = "проверено";
$language['p.users.st.u'] = "непроверено";
$language['p.users.mbf'] = "потребител от";
$language['p.users.op'] = "операции";
$language['p.users.hgr'] = "<small>Можете да запазите прегледа на потребители и да го използвате в други сектори</small>";
$language['p.users.mmls'] = "изберете списък на потребители";
$language['p.users.smsv'] = "Цялостната информация за този имейл ще бъде запазена, като отметка. ";
$language['p.users.mbmk'] = "Отметки ";

$language['p.users.omsm'] = "Допълнителен Списък  ";
$language['p.users.omsh'] = "Разделете отделните имайли с запетая  ";


$language['users.days'] = "Дни";
$language['users.months'] = "Месеци";
$language['users.years'] = "Години";
$language['users.hours'] = "Часове";
$language['users.minutes'] = "Минути";
$language['users.seconds'] = "Секунди";

$language['users.avatar'] = "Вашите снимки в сайта";


//Recover password
$language['users.f.email'] = "Вашият e-mail:";
$language['users.f.cmail'] = "Вашият текущ e-mail:";
$language['users.f.nmail'] = "Вашият нов e-mail:";
$language['users.f.title'] = "Въстановяване на данни от уеб сайта";
$language['users.f.messa'] = "За да се върнете във Вашият {site_name} профил, трябва да създадете нова парола.";
$language['users.f.pdnm'] = "Паролите не съвпадат";
$language['users.f.reset'] = "Променете паролата си сега";
$language['users.f.resnw'] = "Връзката не работи? Копирайте и поставете във вашия браузър:";

//
// Change mail
$language['users.chml.ttl'] = "Вие получавате това съобщение за смяна на текущия e-mail {host}";
$language['users.chml.mss'] = "Като кликнете върху линка по-долу ще бъдат пренасочени към уеб страница, която ще активира текущата E-mail.";
$language['users.chml.smss'] = "Задължително е да сте потребител на сайта за да продължите.";
$language['users.chml.sendmail'] = "TСъобщение за промяна на E-mail е изпратено успешно.<p>Моля проверете на заявеният от Вас E-mail адрес.<p>";
$language['users.chml.sesexp'] = "Сесия за промяна на E-mail адрес е изтекла.";

//
// User fields
$language['users.flds.name'] = "MySQL име на поле";
$language['users.flds.desc'] = "Описание на поле";
$language['users.flds.titl'] = "Име на поле";
$language['users.flds.fldty'] = "Тип на поле";
$language['users.flds.rscol'] = "Поле редове/колони";
$language['users.flds.order'] = "Подредба на полета";
$language['users.flds.rowty'] = "Тип на поле в MySQL";
$language['users.flds.attre'] = "HTML Атрибути на полетата";

$language['users.flds.defva'] = "Настояща стойност";
$language['users.flds.fld_require'] = "За регистрация:";
$language['users.flds.reqy'] = "Изискване";
$language['users.flds.rehi'] = "Скрий ";
$language['users.flds.reqn'] = "Покажи ";

// Forgotten password
$language['users.sent.mail'] = "Писмо за възстановяване на паролата е изпратено успешно,</br > Проверете пощата си и следвайте стъпките описани в писмото.";
$language['users.sent.err'] = "Моля опитайте отново!";
$language['users.sent.cont'] = "За да продължите моля натиснете <b><a href='/users'>тук</a></b>";
$language['users.recov.errorhash'] = "Грешка в подадената връзка";
$language['users.recov.changfor'] = "Промяна на паролата за потребителско име:";

$language['users.updated'] = "<p align='center'>New information is set in your profile!<br /><br />
    To continue go into <a href='main'>the user's main page</a> or <a href='/'>the website home page.</a></p>
";
$language['users.change.mail'] = "Променете Вашият E-mail адрес";
$language['users.change.password'] = "Променете Вашата парола";
$language['users.timezone'] = "Часова зона";

// Messages
$language['users.messages.index'] = "Входяща поща";
$language['users.messages.inbox'] = "Входяща поща";
$language['users.messages.einbox'] = "Нямате съобщения във входящата поща";
$language['users.messages.sendme'] = "Изпратете ново съобщение";
$language['users.messages.goback'] = "Основна потребителска страница";
$language['users.messages.readla'] = "Прочети последно съобщение";
$language['users.messages.select'] = "Изберете потребител:";
$language['users.messages.sendto'] = "Изпрати съобщение до:";
$language['users.messages.uisect'] = "Изпрашане до:";
$language['users.messages.alertm'] = "За да изпратите съобщение, моля попълнете всички полета.";
$language['users.messages.messtt'] = "Тема:";
$language['users.messages.messms'] = "Вашето съобщение:";
$language['users.messages.messuc'] = "Съобщението беше изшратено успешно!";
$language['users.messages.messfr'] = "Съобщение от";
$language['users.messages.mesimp'] = "Отбележи като важно";
$language['users.messages.mesrep'] = "Отговор на това съобщение";
$language['users.messages.senttt'] = "Изходяща поща";
$language['users.messages.mailtt'] = "уведомително съобщение";
$language['users.messages.mailtx'] = "Това уведомление се изпраща, защото имате важно послание от потребителя ";
$language['users.messages.mailun'] = "Непрочетени ";
$language['users.messages.mailre'] = "Прочетени ";
$language['users.messages.mailer'] = "Сигурни ли сте , че изкате да изтриете";


// View user
$language['users.view.notexist'] = "Този потребител несъществува.<br /> Проверете Вашата връзка";
$language['users.view.sendmess'] = "Изпратете лично съобщение";
$language['users.view.viewingu'] = "Преглед ";

// User review
$language['users.revw.usrfromt'] = " Член от";
$language['users.revw.changeph'] = " Смяна на снимка";
$language['users.revw.authprov'] = " Доставчик";
$language['users.revw.authnone'] = " Няма";
$language['users.revw.picdescr'] = " След качване на изображение, презареди страницата! ";
$language['users.revw.piclickr'] = " Изпор на снимки";
$language['users.revw.picuplod'] = " Качване на снимки";

//
// Goodbye
