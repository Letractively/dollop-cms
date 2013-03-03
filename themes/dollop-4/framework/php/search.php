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
 * @filesource Gallery menu
 * @package search
 * @subpackage none
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
global $language, $SQL_WEBSITE;
$destination = propc("md.search.destination");
if (isset($destination)) {
    $voice_code = kernel::loadprop(__FILE__,true);
    $code = $voice_code['voice'][$SQL_WEBSITE['lan']];

    $content = null;
    $destination = propc("md.search.index");
    $query = propc("md.search.text");
    $table = propc("md.search.table");
    $reference = propc("md.search.reference");
    $charset = propc("md.search.charset");
    $token = propc("md.search.token");
    $stand = propc("md.search.stand");
    $name = propc("md.search.name");
    // crypt the table
    if (defined("MD_MAIN_DB")) {
        $name_table = urlencode(md5crypt(PREFIX . constant("MD_MAIN_DB") . HEX));
    } else {
        $name_table = urlencode(md5crypt(PREFIX . "pages" . HEX));
    }
    $host = HOST;

    if (empty($_POST[$query])) {
        $value = "";
    } else {
        $value = $_POST[$query];
    }

    $search = <<<eol
<div class="menu search">
  <form id="searchform" method="post" action="{$destination}" >
      <input type="hidden" value="{$name_table}" name="{$table}" />
    <fieldset><legend class="menu-title title">{$language['lw.search.title']}</legend>
    <div class="search menu content">
    <input type="hidden" value="{$name_table}" name="{$table}" />
    <input id="search"  type="search" name="{$query}" value="{$value}" placeholder="{$language['lw.search']}" type="text" lang="{$code}"  x-webkit-speech  speech  onwebkitspeechchange="this.form.submit();"   />
    </div>
</fieldset>
  </form>
  </div>

eol;
} else {
    $search = "";
}

/* more info for voice search
 * Languages

+ Afrikaans af
+ Basque eu
+ Bulgarian bg
+ Catalan ca
+ Arabic (Egypt) ar-EG
+? Arabic (Jordan) ar-JO
+ Arabic (Kuwait) ar-KW
+? Arabic (Lebanon) ar-LB
+ Arabic (Qatar) ar-QA
+ Arabic (UAE) ar-AE
.+ Arabic (Morocco) ar-MA
.+ Arabic (Iraq) ar-IQ
.+ Arabic (Algeria) ar-DZ
.+ Arabic (Bahrain) ar-BH
.+ Arabic (Lybia) ar-LY
.+ Arabic (Oman) ar-OM
.+ Arabic (Saudi Arabia) ar-SA
.+ Arabic (Tunisia) ar-TN
.+ Arabic (Yemen) ar-YE
+ Czech cs
+ Dutch nl-NL
+ English (Australia) en-AU
+? English (Canada) en-CA
+ English (India) en-IN
+ English (New Zealand) en-NZ
+ English (South Africa) en-ZA
+ English(UK) en-GB
+ English(US) en-US
+ Finnish fi
+ French fr-FR
+ Galician gl
+ German de-DE
+ Hebrew he
+ Hungarian hu
+ Icelandic is
+ Italian it-IT
+ Indonesian id
+ Japanese ja
+ Korean ko
+ Latin la
+ Mandarin Chinese zh-CN
+ Traditional Taiwan zh-TW
+? Simplified China zh-CN ?
+ Simplified Hong Kong zh-HK
+ Yue Chinese (Traditional Hong Kong) zh-yue
+ Malaysian ms-MY
+ Norwegian no-NO
+ Polish pl
+? Pig Latin xx-piglatin
+ Portuguese pt-PT
.+ Portuguese (brasil) pt-BR
+ Romanian ro-RO
+ Russian ru
+ Serbian sr-SP
+ Slovak sk
+ Spanish (Argentina) es-AR
+ Spanish(Bolivia) es-BO
+? Spanish( Chile) es-CL
+? Spanish (Colombia) es-CO
+? Spanish(Costa Rica) es-CR
+ Spanish(Dominican Republic) es-DO
+ Spanish(Ecuador) es-EC
+ Spanish(El Salvador) es-SV
+ Spanish(Guatemala) es-GT
+ Spanish(Honduras) es-HN
+ Spanish(Mexico) es-MX
+ Spanish(Nicaragua) es-NI
+ Spanish(Panama) es-PA
+ Spanish(Paraguay) es-PY
+ Spanish(Peru) es-PE
+ Spanish(Puerto Rico) es-PR
+ Spanish(Spain) es-ES
+ Spanish(US) es-US
+ Spanish(Uruguay) es-UY
+ Spanish(Venezuela) es-VE
+ Swedish sv-SE
+ Turkish tr
+ Zulu zu
 */
