<?php

/**
  ============================================================
 * Last committed:      $Revision: 133 $
 * Last changed by:     $Author: fire1 $
 * Last changed date:   $Date: 2013-04-02 20:13:15 +0300 (âò, 02 àïð 2013) $
 * ID:                  $Id: page.php 133 2013-04-02 17:13:15Z fire1 $
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
 * @filesource Main Dollop
 * @package dollop kernel
 * @subpackage Module
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("This is not right way to open the pages in Dollop");
}
/**
 * @filesource
 * Dollop pages file
 *
 * @version 1.3
 * @author Angel Zaprianov <fire1@abv.bg>
 *
 */
// Default Index
If (isset($_GET['index'])) {
    global $SQL_WEBSITE;
    parse_str(parse_url($SQL_WEBSITE['start_page'], PHP_URL_QUERY), $_GET);
}
// Show Pages
if (is_numeric($_GET['view'])) {

    $sqlquery = "SELECT * FROM " . PREFIX . "pages WHERE ID = '" . $_GET['view'] . "' LIMIT 1;";
    if (class_exists('kernel')) {
        $row = kernel::sql_fetch_array($sqlquery);
    } else {
        $queryresult = db_query($sqlquery) or die(db_error());
        $row = db_fetch($queryresult, "assoc", "current");
    }
    if (!empty($row)) {
        //Header tag / advanced
        foreach ($row as $row) {
            if ($row['com_user']) {
                if ((bool) $comments = propc("md.comments.destination")) {
                    $comment = <<<eol
                        <br />
                        <script src="$comments/static.process.js" type="text/javascript"></script>
eol;
                }
            }
            $TITLE = $row['title'];
            $BODY = $row['body'] . $comment;
            // eval php script in to selected page
            if (!empty($row['phpcripts'])) {
                eval(" {$row['phpcripts']} ");
            }
            // Attach jQuery script in to page
            if (!empty($row['jscripts'])) {
                global $kernel;
                $kernel->external_file('jquery', $row['jscripts']);
            }
        }

        theme::content(array($TITLE, $BODY));
    } else {
        theme::responses(404);
    }

// Contact Form
} elseif (isset($_GET['contact'])) {
    global $language, $SQL_WEBSITE;
    $err = false;
    if (isset($_POST['tAction'])) {

        /// SEND EMAIL
        if (captcha($_POST['captcha']) == false) {
            $err = $language['web.captcha.err'];
        }


        if (!(bool) $email = filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)) {
            $err = "<b>{$language['lw.from']} (email)</b> <br />" . $language['users.err.valid.email'];
        }
        if (isset($_GET['share'])) {
            if (!(bool) $tmail = filter_var($_POST['to_email'], FILTER_VALIDATE_EMAIL)) {
                $err = "<b>{$language['lw.to']} (email)</b> <br />" . $language['users.err.valid.email'];
            }
        } else {
            // Use website email
            $tmail = $SQL_WEBSITE['site_mail'];
        }

        $show_message = (isset($_GET['share'])) ? $language['web.emailing.sn.b'] : $language['web.emailing.sn.a'];

        if ($err === false) {
            $mail = new misc_email();
            $mail->type = "html";
            $mail->from = $email;
            $mail->to = $tmail;
            $mail->subject = $_POST['subject'];
            $body = nl2br(stripallslashes(addslashes($_POST['body'])));

            $mail->body = <<<eol
            <p>
             {$_SESSION['contact_body']}
            </p>
            <br />
            ------------
            <br />
            <p>
            {$body}
            </p>



eol;
            $mail->setHeaders();
            if ($mail->send()) {
                $_SESSION['contact_info'] = null;
                $html = <<<eol
                <p align="center"><b> $show_message</b> </p>
                    <br />
                    <p align="center">
                    <a href="{$SQL_WEBSITE['start_page']}" class="button">{$language['lw.home']}</a>
                    </p>

eol;
            } else {
                $html = "<p align='center'>Cannot send mail</p><br /><br /><br />";
            }
        } else {
            $back = request_uri();
            $html = <<<eol
            <p align="center"> $err </p><br />

            <p align="center"><a href="$back" class="button">{$language['lan.back']}</a>
                </p>
eol;
        }
        $title = $language['main.cp.mail'];
    } else {
        /// CONTACT FORM
        $captcha = captcha();
        if (defined("USER_ID")) {
            $mtitle = (isset($_GET['share'])) ? ucfirst($language['users.lan.uname']) : ucfirst($language['lw.title']);
            $descr = (isset($_GET['share'])) ? ucfirst($language['lw.description']) : ucfirst($language['cont.from.mss']);
            $my = new mysql_ai;
            $my->Select(USERS_SQLTBL_MAIN, array(USERS_SQLTBL_COL_UID => constant("USER_ID")));
            $email = $my->aArrayedResults[0][USERS_SQLTBL_COL_UMAIL];
            $closemail = "readonly";
            $username = (isset($_GET['share'])) ? USER_NAME : $_SESSION['contact_title'];
        } else {
            $email = "";
            $closemail = "";
            $username = (isset($_GET['share'])) ? ucfirst($language['shr.mail.ttl']) : ucfirst($_SESSION['contact_title']);
            $mtitle = ucfirst($language['cont.from.ttl']);
            $descr = ucfirst($language['cont.from.mss']);
        }
        $information = (isset($_SESSION['contact_info'])) ? ucfirst($_SESSION['contact_info']) : null;
        if (isset($_GET['share'])) {
            $share = <<<eol
        <label for="to_email">{$language['lw.to']} (email):</label>
        <input type="text" name="to_email" value="" id="to_email"  />

eol;
        }
        //$_SESSION['contact_body']


        $action = request_uri();
        $title = $language['main.cp.mail'];
        $html = <<<eol
   <form method="post" action="$action" name="contact_form" id="contact_form">
   <table cellspacing="0" cellpadding="0" align="center" width="80%">
  <tbody>
    <tr>
      <td colspan="3"><label>{$information}</label></td>
    </tr>
    <tr>
      <td height="10" colspan="3"></td>
    </tr>
    <tr>
      <td colspan="3"><label for="uname">{$mtitle}:</label>
        <input type="text" name="subject" value="{$username}" /></td>
    </tr>
    <tr>
      <td height="10" colspan="3"></td>
    </tr>
    <tr>
      <td width="220"><label for="u_email">{$language['lw.from']} (email):</label>
        <input type="text" name="u_email" value="$email" $closemail/></td>
      <td width="20"></td>
      <td width="220">
                {$share}
        </td>
    </tr>
    <tr>
      <td height="10" colspan="3"></td>
    </tr>
    <tr>
      <td colspan="3"><label for="uname">{$descr}:</label>
        <textarea name="body"></textarea></td>
    </tr>
    <tr>
      <td height="10" colspan="3"></td>
    </tr>
    <tr>
      <td width="240" valign="bottom" colspan="2">
        {$captcha}
        <input type="text" value="" name="captcha" />

      </td>
      <td valign="bottom" align="right">
        <input type="submit" name="tAction" value="{$language['lan.submit']}" />

      </td>
    </tr>
  </tbody>
</table></form>
eol;
    }

    theme::content(array($title, $html));
} elseif (isset($_GET['tabs'])) {
    //
    // Convert Request to Dollop type
    $request = _GET("tabs");
    if (is_numeric($_GET['id'])) {

        global $theme, $kernel;
        $theme->jquery_ui($_GET['ui']);
        $tabs = "tabs";
        $sqlquery = "SELECT * FROM " . PREFIX . "pages WHERE ID = '" . $_GET['id'] . "' LIMIT 1;";
        $row = kernel::sql_fetch_array($sqlquery);
        $tab_name = "$tabs-1";
        if (!empty($row)) {
            $class_view = db_escape_string($request);
            $sqlquery = "SELECT ID,title FROM " . PREFIX . "pages WHERE `class_view` = '" . $class_view . "' ";
            $trows = kernel::sql_fetch_array($sqlquery);
            $JScript = $theme->custom_template("tabs-jscript", array("TAB_NAME" => "#$tabs"));
            if (is_array($trows)) {
                $li_tab = null;
                foreach ($trows as $trow) {
                    if ($trow['ID'] == $_GET['id']) {
                        $tab_link = "#" . $tab_name;
                    } else {
                        $tab_link = "/page?clear={$trow['ID']}&body=1";
                    }
                    $li_tab .= $theme->custom_template("tabs-li", array("tab_link" => $tab_link, "tab_title" => $trow['title']));
                }
            }
            foreach ($row as $row) {
                if ($row['com_user']) {
                    if ((bool) $comments = propc("md.comments.destination")) {
                        $comment = <<<eol
                        <br />
                        <script src="$comments/static.process.js" type="text/javascript"></script>
eol;
                    }
                }
                $TITLE = $row['title'];
                $BODY = $theme->custom_template("tabs-body", array("TAB_NAME" => "$tabs", "NAV_CONTENT" => $li_tab, "BODY_CONTENT" => $row['body'] . $comment, "TAB_CURRENT" => $tab_name));
                // eval php script in to selected page
                if (!empty($row['phpcripts'])) {
                    eval(" {$row['phpcripts']} ");
                }
                // Attach jQuery script in to page
                if (!empty($row['jscripts'])) {

                    $JScript .= $row['jscripts'];
                }
            }

            $kernel->external_file('jquery', $JScript);
            theme::content(array($TITLE, $BODY));
        } else {
            theme::responses(404);
        }
    }
    //
    // Only Page Content
} elseIf (is_numeric($_GET['clear'])) {


    $sqlquery = "SELECT * FROM " . PREFIX . "pages WHERE ID = '" . $_GET['clear'] . "' LIMIT 1;";
    if (class_exists('kernel')) {
        $row = kernel::sql_fetch_array($sqlquery);
    } else {
        $queryresult = db_query($sqlquery) or die(db_error());
        $row = db_fetch($queryresult, "assoc", "current");
    }
    if (!empty($row)) {
        //Header tag / advanced




        foreach ($row as $row) {
            if ((bool) $_GET['title']) {
                $TITLE = $row['title'];
            } else {
                $TITLE = NULL;
            }
            if ((bool) $_GET['body']) {
                $BODY = $row['body'];
            } else {
                $BODY = NULL;
            }
            if ((bool) $_GET['template']) {
                $GLOBALS['THEME'] = theme::content(array($TITLE, $BODY), "BODY", true);
            } else {
                $GLOBALS['THEME'] = $TITLE . "\n" . $BODY;
            }
        }
    } else {
        theme::responses(404);
    }
}