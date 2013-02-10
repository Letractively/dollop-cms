<?php
/**
  ============================================================
  Last committed:     $Revision: 3 $
  Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
  Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
  ID:            $Id: email.php 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * @package mail
 */
class misc_email {

    var $to;
    var $cc;
    var $bcc;
    var $subject;
    var $from;
    var $headers;
    var $type;
    var $body;
    var $charset;
    var $boundary = "dollop";

    /**
     * Fill null data
     */
    function misc_email($to=null,$subj=null,$body=false,$type="mix") {
        $this->to       = $to;
        $this->cc       = NULL;
        $this->bcc      = NULL;
        $this->subject  = $subj;
        $this->from     = NULL;
        $this->headers  = NULL;
        $this->body     = $body;
        $this->type     = $type;
        if((bool)$to && (bool)$subj && (bool)$body && (bool)$type){
            $this->setHeaders();
            $this->send();
        }
        
    }

    /**
     *  Convert $_POST params to vaues in e-mail
     * @param array $params
     */
    function getParams($params, $aExclude) {
        $i = 0;
        if (!is_array($params))
            return FALSE;;
        //
        // Excluding keys
        foreach ($params as $k => $v) {
            if (in_array($k, $aExclude)) {
                unset($params[$k]);
            }
        }
        //
        // Order the data
        foreach ($params as $key => $value) {
            switch ($key) {
                case 'to':
                    $this->to = $value;
                    break;
                case 'cc':
                    $this->cc = $value;
                    break;
                case 'bcc':
                    $this->bcc = $value;
                    break;
                case 'subject':
                    $this->subject = $value;
                    break;
                case 'from':
                    $this->from = $value;
                    break;
                case 'submit':
                    NULL;
                    break;
                default:
                    $this->body[$i]["key"] = str_replace("_", " ", ucWords(strToLower($key)));
                    $this->body[$i++]["value"] = $value;
            }
        }
    }
    
    /**
     * Setup header for mail
     * @global type $CONFIGURATION
     */
    function setHeaders() {
        global $CONFIGURATION;
        $this->charset = $CONFIGURATION['charset'];
        $this->headerType();

        if (!empty($this->cc))
            $this->headers.= "Cc: {$this->cc}\r\n";
        if (!empty($this->bcc))
            $this->headers.= "Bcc: {$this->bcc}\r\n";
            $this->headerType();
    }

    /**
     * Resolv header mail type
     */
    
    function headerType() {
        
        If($this->from === TRUE){
            $this->from="no-reply@".constant("HOST");
        }elseif(empty($this->from)){
            $this->from="no-reply@".constant("HOST");
        }
        $this->headers = "From: {$this->from}\r\n";
        $this->headers.= "MIME-Version: 1.0\r\n";
        switch ($this->type) {
            default :

                $this->headers.= "Content-Type: multipart/alternative; boundary={$this->boundary}\r\n";

                //html version
                $this->headers .= "\n--{$this->boundary}\n";
                $this->headers .= "Content-type: text/html; charset={$this->charset}\r\n";
                $this->headers .= $this->body;

                //text version
                $this->headers .= "\n--{$this->boundary}"; // beginning \n added to separate previous content
                $this->headers .= "Content-type: text/plain; charset={$this->charset}\r\n";
                $this->headers .= $this->body;

                break;
            case "html":
                $this->headers .= "Content-type: text/html; charset={$this->charset}\r\n";
                break;
            case "text":
                $this->headers .= "Content-type: text/plain; charset={$this->charset}\r\n";
                break;
        }
    }

    /**
     * If is used getParams This function will convert 
     * Keys to data
     */
    function parseBody() {
        $count = count($this->body);
        for ($i = 0; $i < $count; $i++) {
            if ($this->html)
                $content.= "<b>";
            $content .= $this->body[$i]["key"] . ': ';
            if ($this->html)
                $content.= "</b>";
            if ($this->html)
                $content .= nl2br($this->body[$i]["value"]) . "\n";
            else
                $content .= $this->body[$i]["value"];
            if ($this->html)
                $content.= "<hr noshade size=1>\n";
            else
                $content.= "\n" . str_repeat("-", 80) . "\n";
        }

        $this->body = $content;
    }

    function send() {
        if (mail($this->to, $this->subject, $this->body, $this->headers))
            return TRUE;
        else
            return FALSE;
    }

    function set($key, $value) {
        if ($value)
            $this->$key = $value;
        else
            unset($this->$key);
    }

}