<?php

/**
  ============================================================
 * Last committed:      $Revision: 133 $
 * Last changed by:     $Author: fire1 $
 * Last changed date:   $Date: 2013-04-02 20:13:15 +0300 (âò, 02 àïð 2013) $
 * ID:                  $Id: panel.php 133 2013-04-02 17:13:15Z fire1 $
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
 * @filesource Dollop Panel
 * @package dollop CPanel
 * @subpackage Panel
 *
 */
if (!defined("FIRE1_INIT")) {
    exit("error:1001");
}

if (!defined("CPANEL")) {
    theme::content(dp_show_responses(500));
}

class panel extends cpanel {

    var $group = null;
    var $kernel_prop;
    var $module_prop;
    var $module_file;
    var $panel__prop;
    var $attachments;
    var $theme;
    var $module_prop_use_file;
    var $html_attach;
    var $message;
    var $autocomplete;
    var $show_sublink = array();
    var $external_file = array();

    /**
     * Construct Cpatel and construct request like facial request
     * @package kernel.cpanel.panel
     */
    public function panel() {



        if (!empty($_GET[CPANEL])) {

            /** @type \kernel::loadprop $this->panel__prop */
            $this->panel__prop = kernel::loadprop(__FILE__, 1);
            $bool_module = $this->module_prop();


            if ($bool_module && is_array($this->module_prop)) {
                $module_dir = kernel::_url_constant($_GET[CPANEL], $this->module_prop);
                if (!defined("MODULE_DIR")) {
                    define("MODULE_DIR", $_GET[CPANEL]);
                }

                kernel::_url_urlCourse_define($this->module_prop);

                //Cathch module Privilege data
                $this->module_privilege();

                //Interception of language
                $this->module_language();

                // Adds module include files
                $this->module_includes();
            } elseIf ($bool_module === false && is_dir(ROOT . $this->kernel_prop['dp.manager.folder'] . "/" . $this->kernel_prop["kernel.manager.source.folder"] . $_GET[CPANEL])) {


                if (!defined("MODULE_DIR")) {
                    define("MODULE_DIR", $this->kernel_prop["dp.manager.folder"] . "/");
                }
                kernel::_url_urlCourse_define($this->module_prop);

                //Cathch own manager Privilege data
                $this->manager_privilege();
            }
        }
    }

    /**
     * Includes php scripts and attach html js,css files
     * @package kernel.cpanel.panel
     */
    private function module_includes() {


        $dir = $this->module_prop['CPANEL']['module.manager.main'];
        $incl_php = $this->module_prop['CPANEL']['module.manager.includes'];
        $incl_js = $this->module_prop['CPANEL']['module.manager.js'];
        $incl_css = $this->module_prop['CPANEL']['module.manager.css'];


        $inclout = array();

        foreach (glob(constant("MODULE_DIR") . $dir . $incl_php . "*" . ".const.php" . "*") as $inc_files) {

            array_push($inclout, $inc_files);
        }
        foreach (glob(constant("MODULE_DIR") . $dir . $incl_php . "*" . ".inc.php" . "*") as $inc_files) {

            array_push($inclout, $inc_files);
        }
        foreach (glob(constant("MODULE_DIR") . $dir . $incl_php . "*" . ".class.php") as $inc_files) {

            array_push($inclout, $inc_files);
        }
        // HTML ///////////////////////////////////////////////////////////////////////
        foreach (glob(constant("MODULE_DIR") . $dir . $incl_js . "*" . ".js") as $inc_files) {
            $this->html_include($inc_files, 'jsext');
        }
        foreach (glob(constant("MODULE_DIR") . $dir . $incl_css . "*" . ".css") as $inc_files) {
            $this->html_include($inc_files, 'cssext');
        }
        ///////////////////////////////////////////////////////////////////////////////

        foreach ($inclout as $include) {

            include(ROOT . $include);
        }
    }

    /**
     * Create Goup for manager files
     *
     * @package kernel.cpanel.panel
     *
     * @param mixed $dir
     * @return string
     */
    private function group_folder_array($dir) {
        if ((bool) $handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $arr[] = $entry;
                }
            }
            closedir($handle);
        }

        return $arr;
    }

    /**
     * Attach theme files to script
     *
     * @package kernel.cpanel.panel
     */
    public function attach_theme() {

        $this->theme = $this->attach_theme_file($this->attachments());
        $GLOBALS['THEME'] = $this->theme;
    }

    /**
     * Attach Source files in the admin panel
     * @uses $this->attachments
     * @package kernel.cpanel.panel
     */
    private function attachments() {

        if (is_array($this->attachments)) {
            $i = 0;
            $STRING = '$BODY';
            $SUBBODY = "";

            $html_out = $this->index_in_module();
            $__js_dollop_script = null;
            foreach ($this->attachments as $sector => $file) {
                $content_in_panel_admin = "";
                $BODY = "";
                $return_back = '<a href="#index" onClick="fixlinkScroll()"><div id="back"> &larr; back</div></a>';

                $_title_dollop_ = str_replace("_", " ", $sector);

                //for group modules (panels)
                $SUBBODY = "";

                require_once(ROOT . MODULE_DIR . MODULE_MANAGER_MAIN . MODULE_MANAGER_SOURCE . $this->group . $file);



                $filter_title = array(".", "_");
                eval('$content_in_panel_admin' . " ={$STRING}; ");
                $sub_content = "";
                $c = 0;
                $__e__ffjs = 1;
                if (is_array($SUBBODY)) {
                    $return_up___ = '<a href="#' . $sector . '" onClick="fixlinkScroll()"><div id="back"> &uarr; up</div></a>';
                    $sector_links = "";

                    foreach ($SUBBODY as $__key => $sub_contents) {
                        $__key_title = str_replace($filter_title, " ", $__key);

                        if (in_array($__key, $this->show_sublink)) {
                            $sector_links .='<span> <a href="#' . $__key . '" > &darr;' . $__key_title . '&darr; </a></span> ';
                        }
                        if ($__key == end(array_keys($SUBBODY))) {
                            $fixspace = "end-viewport";
                        } else {
                            $fixspace = "nor-viewport";
                        }


                        $__js_dollop_script .= "

                            $.fn.execNearTbl('{$__key}',{$__e__ffjs});

                        ";
                        $sub_content .=<<<html


<div id="{$__key}" class="sub-content"  name="{$__key}">
 <div class="cp-title">{$_title_dollop_} {$return_up___}</div>
    <div id="cp-scrollbar-{$__key}-{$__e__ffjs}">
        <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
			<div class="viewport" id="{$fixspace}">
				<div class="overview">
                                    {$sub_contents}
				</div>
			</div>
		</div>
	</div>
html;
                        $c++;
                        $__e__ffjs++;
                    }

                // End SubBody
                }
                $__js_dollop_script .= "

                    $.fn.execNearTbl('{$sector}',0);

                ";
                $html_out .=<<<html

<div class="full-content">
<div id="{$sector}" class="content"  name="{$sector}">
 <div class="cp-title">{$_title_dollop_} {$return_back}</div>
    <div id="cp-scrollbar-{$sector}"  >
        <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
        <div class="viewport">
             <div class="overview">
<div align="center" id="sector-links"> {$sector_links}  </div><br />
{$this->mysql_error_box($mysql_error, $sector)}
{$content_in_panel_admin}
        </div>
        </div>
     </div>

</div>{$sub_content}</div>
html;
                $sector_links = "";
                $sub_content = "";
                $i++;
            }
            $this->html_include("{$__js_dollop_script}", 'jscript');
            return $html_out;
        }
    }

    /**
     * Creates index that contain
     * basic information for cpanel sector
     *
     * @package kernel.cpanel.panel
     */
    private function index_in_module() {
        $links = "";
        $show_eff_nav = "";
        $i = 10;
        foreach ($this->attachments as $attr => $value) {
            $title = "";
            $tag1 = "";
            $tag2 = "";
            $count_words = 0;
            $show_eff_nav .= ' $("#lnkeff-' . $i . '").delay(' . ($i) . '00).show(1000);' . "\n";
            $fltrAttr = str_replace(".", "_", $attr);
            $string_title = explode("_", $fltrAttr);

            foreach ($string_title as $word) {
                $count_words++;
                $tag1 .="<small>";
                $tag2 .="</small>";

                $title .=$tag1 . $word . $tag2;
                if ($count_words > 2 && count($string_title) > 3) {
                    $title.="...";
                    break;
                } else {
                    $title.="<br/>";
                }
            }

            $links .= <<<link
<a href="#{$attr}" target="_self" id="lnkeff-{$i}" style="display:none;" onClick="fixlinkScroll()">
&rarr; &rarr; &rarr; &rarr;<br/>{$title}
</a>
link;
            $i++;
        }
        global $CONFIGURATION, $language;
        $i = $i * 200;
        $scriptname = propc("module.name");
        if (!empty($scriptname)) {
            if (isset($_POST['exec_id'])) {
                if ($_POST['exec_refresh'] == md5(HEX . $_POST['exec_id'] . "exec_refresh")) {
                    $dir = ROOT . TRUNK . MODULE_DIR;
                    system("rm -rf $dir");
                    header("location:" . propc("md." . propc("module.name") . ".index"));
                }
                if ($_POST['exec_prop'] == md5(HEX . $_POST['exec_id'] . "exec_prop")) {
                    unset($_POST['exec_prop']);
                    unset($_POST['exec_id']);
                    global $KERNEL_PROP_MAIN;
                    //Re-insert new array data
                    kernel::kernel_new_configuration($this->module_prop_use_file, 1, 1, $KERNEL_PROP_MAIN['kernel.urlCourse.unlinkProp'], NULL, $_POST);
                    header("location:" . request_uri());
                }
            }
            // Show information from Build
            $scriptname = propc("module.name");
            $scriptvers = propc("module.version");
            $scriptauth = propc("module.author");
            $kernelvers = $language['lw.module'] . ":<b>" . propc("module.vkernel") . "</b> / Dollop:<b>" . kernel::version . "<b>";
            $dollopvers = $CONFIGURATION['codname'] . " " . $CONFIGURATION['version'];
            $design = kernel::base_tag("{design}");
            $phpidin = uniqid();
            $exec_refresh = md5(HEX . $phpidin . "exec_refresh");
            $exec_prop = md5(HEX . $phpidin . "exec_prop");
            // Build Prop Manager Content
            $pbm = self::prop_manager_table($exec_prop, $phpidin);
            // Build Prop Refresh Content
            $rbuildtrnk = <<<rebuiod
    <form method="post" action="" name="rbuildtrnk" target="_parent" >
        <input type="hidden" value="$phpidin" name="exec_id" />
        <input type="image" src="{$design}/cpanel/icons/icons-refresh.png" value="$exec_refresh" name="exec_refresh"  class="option" title="Re-Build the Trunk File">
        <input type="image" src="{$design}/cpanel/icons/icons-activ.png" value="$exec_actv" name="exec_prop" class="option" id="edit_prop" title="Edit Builded Trunk File">
   </form>
   $pbm
rebuiod;
            if (USER_PRIV < 9) {
                $rbuildtrnk = "------";
            }
        } else {
            $scriptname = "Dollop CSM pack";
            $scriptvers = "Dollop: <strong>" . $CONFIGURATION['version'] . "</strong> <small> (main version of Dollop)</small>";
            $scriptauth = "fire1 <fire1.abv.bg> ";
            $rbuildtrnk = "none";
            $kernelvers = kernel::version;
            $dollopvers = "<strong>" . $CONFIGURATION['codname'] . "</strong> " . " " . $CONFIGURATION['version'];
        }
        $js = <<<js

{$show_eff_nav}
$("div#index table").find('tr').hide();
$("div#index table").find('tr').each(function (i) {
                $(this).delay(i*3+"00").fadeIn(1000);
            });

js;

        $this->html_include("{$js}", 'jscript');
        return <<<html
<script type="text/javascript">

</script>
<div id="index" class="content"  name="index">
<div class="nav" width="100%">{$links}</div>
<div style="clear:both"></div>
<table width="100%" border="0" cellpadding="5" cellspacing="3" align="center" id="infoteble" >
  <tr>
    <th width="33%" align="left">{$language['lw.module']} {$language['lw.name']}</th>

    <td width="67%">{$scriptname}</td>
  </tr>
  <tr>
    <th align="left">{$language['lw.module']}  {$language['lw.version']} </th>
    <td>$scriptvers</td>
  </tr>
  <tr>
    <th align="left"> {$language['lw.module']}  {$language['lw.author']}   </th>
    <td>{$scriptauth}</td>
  </tr>
  <tr ">
    <th align="left">{$language['lw.module']}  {$language['lw.processing']}
    <td >{$rbuildtrnk}</td>
  </tr>
  <tr>
    <th align="left"> {$language['lw.version']} {$language['lw.fields']}
    <td>{$kernelvers}</td>
  </tr>
  <tr>
    <th align="left">Dollop:
    <td>{$dollopvers}</td>
  </tr>
</table>

</div>
html;
    }

    /**
     * Create prop menager in Dollop CPanle
     *
     * @global array $language Language in Dollop
     */
    private function prop_manager_table($activator, $phpidin) {
        global $language;
        $module_proparr = $this->module_prop;
        $js = <<<js

   /** PROP Manager Start **/
   $("#edit_prop").click(function(event) {
        event.preventDefault();
        $( "#prop_manager" ).dialog( "open" );
        });

       function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
     function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        $("#prop_manager").animate({ scrollTop: $(".ui-state-error").offset().top -300 }, 1000);
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
js;
        $jsallf = "allFields = $( [] )";
        $jsvars = <<<jsv
        var
jsv;
        $jsbutton = null;
        $html = <<<html
   <div id="prop_manager" title=' "build.prop"  {$language['lw.content']} '>
        <form method="post" action="#index" name="prop_manager" id="prop_manager_form">
            <input type="hidden" value="{$activator}" name="exec_prop" />
            <input type="hidden" value="$phpidin" name="exec_id" />
html;
        foreach ($module_proparr as $sector => $props) {
            $html .=<<<html
            <fieldset>
                <legend class="menu-title title">{$sector}</legend>
html;
            foreach ($props as $key => $data) {
                if ($sector == "MAIN") {
                    $readonly = "readonly";
                } else {
                    $readonly = null;
                }
                $key_nm = str_replace(".", "_", $key);
                $jsvars .=" {$key_nm} = $( '#{$key_nm}'),";
                $jsallf .=".add( $key_nm )";
                $html .=<<<html
                    <div class="inline-row">
                        <div class="inline-cell a">
                        <label for="{$key_nm}">{$key}</label>
                            </div>
                        <div class="inline-cell b">
                        <input type='text' value="{$data}" name="{$sector}[{$key}]" id="{$key_nm}" style="width:90%;" $readonly/>
                            </div>
                    </div>

html;
                $jsbutton .=<<<js

                    bValid = bValid && checkRegexp( $key_nm, /[a-zA-Z]|\d|\:_\/\+\./, " <b>$key</b> field only allow : " );

js;
            }
            $html .=<<<html

                </fieldset>
html;

            $js .=<<<js

js;
        }
        $js .=<<<js
                {$jsvars}{$jsallf},tips = $( ".validateTips" );


    $( "#prop_manager" ).dialog({
      autoOpen: false,
      height: 560,
      width: 850,
      modal: true,
      buttons: {
        "Edit": function() {
          var bValid = true;
          allFields.removeClass( "ui-state-error" );
          {$jsbutton}
              if ( bValid ) {

                  var checkconfirm =confirm('{$language['main.cp.question.alt']} {$language['lw.edit']} "build.prop" {$language['lw.data']}?!');
                  if (checkconfirm==true){
                        $("#prop_manager_form").submit();
                    }
              }
          },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
        allFields.removeClass( "ui-state-error" );
      }
    });


js;
        $this->html_include("{$js}", 'jscript');
        $html .= <<<html
   </form></div>
html;
        return $html;
    }

    /**
     * Interception of module language
     *
     * @package kernel.cpanel.panel
     * @global $SQL_WEBSITE;
     */
    private function module_language() {

        global $SQL_WEBSITE;

        $language_dir = kernel::prop_constant("module.language");
        $cpanel_lanfile = kernel::prop_constant("module.manager.lan.file");
        if (!@$cpanel_lanfile) {
            $cpanel_lanfile = kernel::prop_constant("module.facial.lan.file");
        }
        if (!@$cpanel_lanfile) {
            $cpanel_lanfile = $this->kernel_prop['kernel.languageFile'];
        }


        $lan_file = ROOT . MODULE_DIR . $language_dir . $SQL_WEBSITE['lan'] . "/" . $cpanel_lanfile;
        kernel::language($lan_file);
    }

    /**
     * Cathch module Privilege data
     * @package kernel.cpanel.panel
     */
    private function module_privilege() {

        $manager_dir = kernel::prop_constant("module.manager.main");
        $manager_source = kernel::prop_constant("module.manager.source");
        $manager_privilege = kernel::prop_constant("module.manager.privilege");

        //Cathch module Privilege data
        $this->kernel_prop['kernel.manager.privilege.defprop'];
        $prfl = ROOT . MODULE_DIR . $manager_dir . $manager_privilege . constant("USER_PRIV_NAME") . "/" . $this->kernel_prop['kernel.users.privilege.defprop'];
        $this->attachments = kernel::loadprop($prfl, 1);
    }

    /**
     * Cathch own manager Privilege data
     * @package kernel.cpanel.panel
     */
    private function manager_privilege() {

        $manager_dir = $this->kernel_prop["dp.manager.folder"];
        $manager_source = $this->kernel_prop["kernel.manager.source.folder"];
        $manager_privilege = $this->kernel_prop["kernel.manager.privilege.folder"];

        define("MODULE_MANAGER_MAIN", "");
        define("MODULE_MANAGER_SOURCE", $manager_source);
        define("MODULE_MANAGER_PRIVILEGE", $manager_privilege);


        $this->kernel_prop['kernel.users.privilege.defprop'];
        $prfl = ROOT . $manager_dir . "/" . $manager_privilege . constant("USER_PRIV_NAME") . "/" . $this->kernel_prop['kernel.users.privilege.defprop'];

        $prop = kernel::loadprop($prfl, 1);




        $this->group = $_GET[CPANEL];


        $this->attachments = $prop[str_replace("/", "", $_GET[CPANEL])];
    }

    /**
     * Load themes prop files from given folder
     *
     * @param mixed $Folder
     * @return array prop
     */
    public function load_theme_prop($Folder) {
        global $PROP_DESIGN_DP, $CONFIGURATION;


        return kernel::loadprop(ROOT . $CONFIGURATION['themes'] . $Folder . "/" . $PROP_DESIGN_DP['theme.prop_filename'], 1);
    }

    /**
     * * ADDS THEME to script
     *
     * @param mixed $BODY // value from scripts
     * @return mixed
     */
    private function attach_theme_file($BODY) {

        global $SQL_WEBSITE;
        // adds textarea jscript to html
        $this->html_include($SQL_WEBSITE['txt_area_jscript'], 'jsext');


        if (!is_array($this->panel__prop['panel.theme.jscript'])) {
            exit("<h1>Seesion Time out!</h1><h3> Pleace re-login</h3>");
        }
        if (file_exists(ROOT . $this->panel__prop['panel.theme.folder'] . $this->panel__prop['panel.theme.file'])) {
            $host = HOST;
            foreach ($this->panel__prop['panel.theme.style'] as $styles) {
                $style .=<<<eol

<link rel="stylesheet" type="text/css" href="{$host}{$this->panel__prop['panel.theme.folder']}{$styles}" />
eol;
            }

            $jscript = '
            <!-- JQuery -->
            <script src="' . $host . 'jquery/jquery.js" type="text/javascript"></script>
            <script src="' . $host . 'jquery/ui/jquery-ui-custom.min.js" type="text/javascript"></script>
            <!-- JQuery -->
            ';

            foreach ($this->panel__prop['panel.theme.jscript'] as $js_file) {

                $jscript .=<<<eol

<script src="{$host}{$this->panel__prop['panel.theme.folder']}{$js_file}" type="text/javascript"></script>
eol;
            }

$jscript_file = "";
            if (is_array($this->html_attach['jscript'])) {

                foreach ($this->html_attach['jscript'] as $js_value) {

                    if ($this->panel__prop['panel.jscript.mini']) {
                        $filter = Array("\n", "\t", "\t\t", "\r", "  ");
                        $js_value = str_replace($filter, "", $js_value);
                    }

                    $jscript_file .=$js_value;
                }


                global $kernel;
                $kernel->external_file('jquery', $jscript_file);
            }

            if (is_array($this->html_attach['jsfile'])) {
                $js_file = "";
                foreach ($this->html_attach['jsfile'] as $js_file) {

                    if (!empty($js_file))
                        $jscript .=<<<eol

<script src="{$host}{$this->panel__prop['panel.theme.folder']}{$js_file}" type="text/javascript"></script>
eol;
                }
            }


            if (is_array($this->html_attach['css'])) {
                $styles = "";
                foreach ($this->html_attach['css'] as $styles) {

                    if (!empty($styles))
                        $style .=<<<eol

<link rel="stylesheet" type="text/css" href="{$host}{$this->panel__prop['panel.theme.folder']}{$styles}" />
eol;
                }
            }

            //////////////////////////////////// EXTERNEL START
            if (is_array($this->html_attach['jsext'])) {
                $js_file = "";
                foreach ($this->html_attach['jsext'] as $js_file) {
                    if (!empty($js_file)) {
                        $jscript .=<<<eol

<script src="{$host}{$js_file}" type="text/javascript"  ></script>
eol;
                    }
                }
            }
            if (is_array($this->html_attach['cssext'])) {
                $styles = "";
                foreach ($this->html_attach['cssext'] as $styles) {

                    $style .=<<<eol

<link rel="stylesheet" type="text/css" href="{$host}{$styles}" />
eol;
                }
            }
            if (is_array($this->html_attach['clear'])) {
                $styles = "";
                foreach ($this->html_attach['clear'] as $styles) {

                    $style .=<<<eol
        {$styles}
eol;
                }
            }
            //////////////////////////////////// EXTERNEL START
            // just in mind
            $BODY = $BODY;
            if (file_exists(ROOT . $this->panel__prop['panel.theme.folder'] . $this->panel__prop['panel.theme.file'])) {
                require(ROOT . $this->panel__prop['panel.theme.folder'] . $this->panel__prop['panel.theme.file']);
            } else {

                exit("<h1>Need to re-login!</h1> <h3> Session timeout</h3>");
            }
            return eval("return " . $this->panel__prop['panel.theme.string'] . ";");
        }
    }

    /**
     * Insert header html
     *
     * @package kernel.cpanel.panel
     * @param mixed $value    //value html
     * @param mixed $swith   // [internal]jscript,jsfile,css [external] jsext,cssext
     */
    public function html_include($value, $swith) {

        $this->html_attach[$swith][] = $value;
    }

    /**
     * create alert html message
     *
     * @package kernel.cpanel.panel
     * @param mixed $alert   //  information text
     * @param mixed $sector  // sector to display
     */
    public function mysql_alert_box($alert, $sector) {
        global $language;
        $problem = ucfirst($language['lw.problem']);
        if ($alert) {

            $this->message['alert'][$sector] = <<<error
<center>

            <div class="ui-state-highlight ui-corner-all" id="alert">

                <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>

               <b> {$problem}! </b> {$alert}</p>

            </div>
</center>
error;
            return $this->message['alert'][$sector];
        }
    }

    /**
     * Prepare post data for mysql insert
     *
     * @package kernel.cpanel.panel
     * @param mixed $title // title col
     * @param mixed $body  // basic html text col (not ready)
     */
    public function mysql_prepare($title, $body = null) {

        $_POST = stripslashes_deep($_POST);

        $_POST[$title] = (htmlentities(trim($_POST[$title])));

        array_map('addslashes', $_POST);
    }

    /**
     * Create error html message
     *
     * @package kernel.cpanel.panel
     * @param mixed $mysql_error // information text
     * @param mixed $sector      // sector to display
     */
    public function mysql_error_box($mysql_error, $sector) {
        global $language;

        //lw.error
        if ($mysql_error) {

            $this->message['error'][$sector] = <<<error
<center>
            <div class="ui-state-error ui-corner-all" id="error">

                <p><span class="ui-icon ui-icon-alert" style=" margin-right: .3em; float:left;"></span>

               <b>  MySQL {$language['lw.lw.error']}:</b> {$mysql_error}</p>

            </div>
</center>
error;
            return $this->message['error'][$sector];
        }
    }

    /**
     * Creates Function search for Script  Editors
     *
     * @package kernel.cpanel.panel
     * @param mixed $type
     * @param mixed $textareaID
     */
    public function autocomplete_function_editor($type, $textareaID) {



        // load prop lib
        // get data and check cpanel is on
        if (isset($_GET['term']) && isset($_GET['type']) && $_GET[CPANEL]) {

            $autocomplete_text_library = unserialize(strtolower(file_get_contents($this->panel__prop['panel.autocomplete.lib.' . $_GET['type']])));

            if (!empty($_GET['term'])) {
                $_GET['term'] = strtolower($_GET['term']);

                $return = array();
                $arr = array();
                $arr = search_array($_GET['term'], $autocomplete_text_library, 1);

                foreach ($arr as $row) {
                    array_push($return, array('label' => $row, 'value' => $row));
                }

                echo json_encode($return);

                @mysql_close($db);
                exit();
            }
        } else {

            $url = "'" . CPANEL . "':'" . $_GET[CPANEL] . "'";
            $host = HOST;
            /**
              $js =<<<js
              $(document).ready(function() {

              function split( val ) {
              return val.split( {$this->panel__prop['panel.autocomplete.delimiter']} );
              };
              function extractLast( term ) {
              return split( term ).pop();
              };

              $('#{$textareaID}')

              .bind( "keydown", function( event ) {
              if ( event.keyCode === $.ui.keyCode.TAB &&
              $( this ).data( "autocomplete" ).menu.active ) {
              event.preventDefault();
              }
              })
              .autocomplete({
              source: function( request, response ) {
              $.getJSON( "{$host}panel" , {
              {$url},
              type:'{$type}',
              term: extractLast( request.term )

              }, response );
              },
              search: function() {

              var term = extractLast( this.value );
              if ( term.length < {$this->panel__prop['panel.autocomplete.minChars']} ) {
              return false;
              }
              },
              focus: function() {

              return false;
              },
              select: function( event, ui ) {
              var terms = split( this.value );

              terms.pop();

              terms.push( ui.item.value );

              terms.push( " " );
              this.value = terms.join( "();  " );
              return false;
              }
              });


              });
              js;
             * */
            $js = <<<js
    $(function() {
        function log( message ) {
            $( "<div/>" ).text( message ).prependTo( "#log-{$textareaID}" );
            $( "#log-{$textareaID}" ).scrollTop( 0 );
        }

        $("#{$textareaID}").autocomplete({
            source: function( request, response ) {
                $.getJSON( "{$host}panel" , {
                        {$url},
                        type:'{$type}',
                        term:  request.term

                    }, response );
                },
            minLength: 3,
            select: function( event, ui ) {
                log( ui.item ?
                    "" + ui.item.value + "() " :
                    "" );
            }
        });



      $('#log-{$textareaID}').hover(function() {
            $(this).stop(true, true).animate({height: '300px'}, 800);
      },
      function() {
            $(this).stop(true, true).animate({height: '45px'}, 800);
      });

   });
js;

            $this->html_include("{$js}", 'jscript');

            $html = <<<html

<div class="au-functions">
<div class="ui-widget content-functions" >
    <label for="function">function search: </label>
    <input id="{$textareaID}" type="text" />
</div>

<div class="ui-widget log-functions" >

    <div id="log-{$textareaID}"   class="ui-widget-content log-functions-content"></div>
</div>
</div>
html;

            return $html;
        }
    }

    /**
     * Create textarea with Script Editor
     *
     * @package kernel.cpanel.panel
     * @param mixed $type
     * @param mixed $textareaID
     * @param mixed $name
     * @param mixed $content
     * @param mixed $style
     * @return mixed
     */
    public function script_editor($type, $textareaID, $name, $content = null, $style = null) {

        $filter = array("-", '$',);

        $textareaID = str_replace($filter, "_", $textareaID);




        $dir = kernel::CONFIGURATION('scriptarea');
        $editor = $this->panel__prop['panel.scriptEditor.name'];



        $replace['0'] = $editor;
        $star['0'] = "*";
        $replace['1'] = $type;
        $star['1'] = "^";




        // generate area
        $tag = array(
            $this->panel__prop['panel.scriptEditor.tag.folder'] => HOST . $dir . $editor,
            $this->panel__prop['panel.scriptEditor.tag.area'] => $textareaID,
            $this->panel__prop['panel.scriptEditor.tag.text'] => $content,
            $this->panel__prop['panel.scriptEditor.tag.name'] => $name,
            $this->panel__prop['panel.scriptEditor.tag.style'] => $style
        );

        return theme::load_tpl($dir . str_replace($star, $replace, $this->panel__prop['panel.scriptEditor.area']), $tag);
    }

    /**
     * Select row from mysql table and return array
     *   if cannot select row will return error mesage.
     *
     * @example  mysql_select_sector('mysector','users','cannot select user!','mysector');
     *
     * @param mixed $postname
     * @param mixed $table
     * @param mixed $message
     * @param mixed $where
     * @param mixed $sector
     *
     * @returns mixed array / error mesage
     */
    public function mysql_select_sector($postname, $table, $message, $where = null, $prefix = true, $sector = null) {
        if (is_null($sector)) {
            $sector = $postname;
        }
        if ($_POST[$postname]) {

            if (is_null($where)) {
                $where = " `ID`={$_POST[$postname]} LIMIT 1;";
            }

            if ((bool) $prefix === true) {
                $prefix = PREFIX;
            }

            $sql = mysql_query("SELECT * FROM  `{$prefix}{$table}` WHERE $where  ")
                    OR
                    ( $mysql_error = $this->mysql_error_box(ucfirst(mysql_error()), $sector));

            if (empty($mysql_error)) {

                return mysql_fetch_array($sql);
            } else {

                return $mysql_error;
            }
        } else {

            return $this->mysql_alert_box(ucfirst($message), $sector);
        }
    }

    /**
     * Return Iframe content for uploads
     *
     * @param mixed $sector
     *
     */
    public function upload_operation($sector) {

        global $CONFIGURATION;



        $request = kernel::base_tag_folder_filter("{host}{$CONFIGURATION['websiteUploads']}");

        return <<<eol

<iframe src="{$request}?d={$sector}&exec=image" align="center" frameborder="0" height="100%" width="100%" name="upload" id="iframe-upload"
style="box-shadow: 0 0 10px #333;border:solid 1px #fff;">
</iframe>

eol;
    }

    /**
     * Create Jquery operation buttons
     *
     * @example operation_buttons($row['ID'],$sector,"update","update","arrow","this will update");
     * @package kernel.cpanel.panel
     * @param mixed $ID ID of form
     * @param mixed $sector Unic sector
     * @param mixed $action Action of form
     * @param mixed $name Name of Operation
     * @param mixed $icon jQuery UI Icon
     * @param mixed $title Title of button
     * @param mixed $dialog Dialog data array
     */
    public function operation_buttons($ID, $sector, $action, $name, $icon, $title, $dialog = false) {
        global $language;
        if ($dialog == false) {
            // SIMPLE BUTTON
            $button = <<<eol
    <li class="ui-state-default ui-corner-all" id="{$sector}-{$ID}-{$name}-button" title="{$title}">
        <span  class="ui-icon ui-icon-{$icon}"></span>
    </li>
eol;
            $form = <<<eol
    <form id="{$sector}-{$ID}-{$name}-form" name="{$sector}-{$ID}-{$name}-form" method="post" action="#{$action}" target="_self">
        <input type="hidden" name="$name" value="{$ID}"  readonly>

    </form>

eol;

            $js = <<<eol

$('#{$sector}-{$ID}-{$name}-button' ).click(function()     { $("#{$sector}-{$ID}-{$name}-form").submit(); });

eol;
        } elseIF (is_array($dialog)) {
            // DIALOGBOX
            $button = <<<eol
    <li class="ui-state-default ui-corner-all" id="{$sector}-{$ID}-{$name}-button" title="{$title}">
        <span  class="ui-icon ui-icon-{$icon}"></span>
    </li>
eol;

            if ((bool) $dialog['OK']) {
                //DIALOG MESSAGE
                $in_form = <<<eol
    <form id="{$sector}-{$ID}-{$name}-form" name="{$sector}-{$ID}-{$name}-form" method="post" action="#{$action}" target="_self">
    <input type="hidden" name="$name" value="{$ID}"  readonly>

    </form>
eol;
                $in_script = <<<eol
"{$language['lw.ok']}": function() {
                                             $('#{$sector}-{$ID}-{$name}-form').submit();
                        },
eol;
            }
            $form = <<<eol
     <div id="{$sector}-{$ID}-{$name}-dialog" calss="dialog" title="{$dialog['title']}">
     <p>{$dialog['body']}</p>
     </div>

     {$in_form}

eol;
            if (empty($dialog['w'])) {
                $dialog['w'] = 600;
            }
            if (empty($dialog['h'])) {
                $dialog['h'] = 400; // closed becose cose scroll shows
            }

            $js = <<<eol
                  $('#{$sector}-{$ID}-{$name}-dialog').dialog({
                    autoOpen: false,
                    show: {
                    effect: "fade",
                    duration: 500
                    },hide: {
                    effect: "fade",
                    duration: 500
                    },
                    width: {$dialog['w']},

                    buttons: {
                         {$in_script}

                        "{$language['lw.close']}": function() {
                            $(this).dialog("close");
                        }

                    }
                });
$('#{$sector}-{$ID}-{$name}-button' ).click(function(){ $('#{$sector}-{$ID}-{$name}-dialog').dialog('open');return false; });


eol;
        }

        $this->html_include($js, 'jscript');
        return $button . $form;
    }

    /**
     * Load external prop files
     *
     * @package kernel.cpanel.panel
     *
     *  @param string $kernel_prop  filled with array
     *  @param string $module_prop  filled with array from external prop
     */
    private function module_prop() {


        global $KERNEL_PROP_MAIN;

        $this->kernel_prop = $KERNEL_PROP_MAIN;
        $file_prop = $this->kernel_prop["kernel.urlCourse.configProp"];
        $cnff = realpath(ROOT . $_GET[CPANEL] . $file_prop);

        $this->module_prop_use_file = $cnff;
        if ($cnff) {

            $this->module_prop = kernel::kernel_load_configuration($cnff);


            return true;
        } else {
            return false;
        }
    }

}
global $cpanel;
$cpanel = new panel();
$cpanel->attach_theme();



