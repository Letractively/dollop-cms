<?php

/**
  ============================================================
 * Last committed:      $Revision: 3 $
 * Last changed by:     $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:   $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
 * ID:                  $Id: responses.inc 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * @filesource Main 
 * @package dollop kernel
 * @subpackage functions
 * 
 */
    if (!defined('FIRE1_INIT')) { exit("<div style='background-color: #FFAAAA; '> error..1001</div>"); }

/** LICENSE NOTE
*  ////////////////////////////////////////////////////////////////
 * //	All DOLLOP code is released under the apache LICENSE 2.0
 * //		See COPYRIGHT and LICENSE
 * ////////////////////////////////////////////////////////////////
 */

/**
 * @filesource
 * Functions that need to be loaded on every Dollop request.
 */	
  
 
/**
* Parse the response headers.
* 	
* @param mixed $key
* @example dp_responses(404); return 'Not Found'
* @return mixed
*/
function dp_responses($key,$moreinfo=null){	
  $responses = array(
    100 => 'Continue',
    101 => 'Switching Protocols',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    307 => 'Temporary Redirect',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Time-out',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Large',
    415 => 'Unsupported Media Type',
    416 => 'Requested range not satisfiable',
    417 => 'Expectation Failed',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Time-out',
    505 => 'HTTP Version not supported',
    001 => 'MySQL connection fail.',
    002 => 'System fail, to select mysql database.',
    004 => 'Error in php request.',
    005 => 'Error in php page.',
    006 => 'Error in dollop php script.',
  );
 
    if(empty($moreinfo)){
    $moreinfo="That’s all we know.";
    }
    
    
return  array($responses[$key],"<h1>". $key . " " .$responses[$key] . " " . 
 "</h1><p><b> The requested URL \"". request_uri() ."\"  creates \"".$responses[$key]."\" on this server.</b> <br /><br />
  <pre style=\"white-space: normal\">$moreinfo</pre> "); 
}


function dp_show_responses($constant,$moreinfo=null){
    if(!empty($constant)){

  return  call_user_func("dp_responses",  $constant,$moreinfo ) ;
    
    }
}