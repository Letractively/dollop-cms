<?php

/**
  ============================================================
 * Last committed:     $Revision: 127 $
 * Last changed by:    $Author: fire $
 * Last changed date:    $Date: 2013-03-27 09:19:53 +0200 (ñð, 27 ìàðò 2013) $
 * ID:       $Id: view.php 127 2013-03-27 07:19:53Z fire $
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
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}
/**
 *
 * @filesource
 * News sorceode
 */

global $language;

theme::content(array(ucfirst($language['md.error.title']), $language['md.error.body']));
