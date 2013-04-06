<?php

/**
  ============================================================
 * Last committed:      $Revision: 124 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-03-19 15:00:12 +0200 (âò, 19 ìàðò 2013) $
 * ID:                  $Id: cpanel.class.php 124 2013-03-19 13:00:12Z fire $
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
 * @subpackage class
 *
 */
if (!defined('FIRE1_INIT')) {
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

/**
 * @package cpanel
 */
class cpanel extends kernel {

    private $theme = 'design/cpanel/theme.html';
    private $style = 'design/cpanel/style.css';
    private $design;
    private $js;
    private $url;
    private $i = 100;
    private $fullscreen;
    private $content;
    private $privfl;

    const folder = USER_PRIV_NAME;
    const privlg = USER_PRIV;
    const userid = USER_IDIN;
    const usernm = USER_NAME;
    const manage = MANAGER_FLDR;
//const privfl = "conf.prop";
    const varuse = "manage";

    private $exec = "";
    private $path;
    private $prop = array();
    private $optn = array();
    private $pico = array('25', '25');
    private $divs = array();
    private $modl = array();

    function __construct() {


        self::theme();

        $this->manager_process();
    }

    /**
     * This wunction show errors  from scripts
     *
     * @param mixed $value   // string with errors from kernel
     */
    function errors($value) {
        if (empty($value['array'])) {
            $count = "0";
        } else {
            $count = $value['array'];
        }

        self::linker('error', $value['content'], '#error', $count, 'error');
    }

    /**
     * create link in bar
     *
     * @param mixed $name        // name
     * @param mixed $content     // content (links)
     * @param mixed $url         // uri adress
     */
    function catch_option($name, $content, $url) {

        self::linker($name, $content, $url, $count);
    }

    public function manager_process($exec = "") {
        global $db;
        $this->exec = str_replace(ROOT, "", $exec);
        $path = realpath(ROOT . self::manage . "/");
        if ($path) {

            if ($this->prop = $this->add_priv_prop()) {

                $this->priv_from_files($this->prop);

                If (kernel::prop_constant("module.manager.privilege") && kernel::prop_constant("module.manager.source") && kernel::prop_constant("module.manager.main")) {
                    $this->module_data();

                    $this->priv_from_modules();
                }
            }
        } else {

            kernel::ERROR("Cannot construct path for manager", "php", "In class [manager_process] \n Line:" . __LINE__);
        }
    }

//////////////////////////////////////////////////////////////
/// Private functions
//////////////////////////////////////////////////////////////

    private function theme() {
        if (file_exists($this->theme)) {

            $this->design = file_get_contents($this->theme);
        } else {
            echo 'can not start debugger!';
        }
    }

    private function JavaScript() {



        $outJS = '


function savelink(){
$(window).data("pastlink", window.location);
}
windowheight=screen.height;
winmin= (windowheight +97) / 3;
//alert(windowheight+""+winmin);

$(".iframe-cpanel").height(windowheight - winmin);
';

#         $(".'.$click.'_debugger").css("background-image", "url(design/debugger/img/anand-button-a.png)");
        foreach ($this->js as $click) {

            if (in_array($click, $this->fullscreen)) {
                $js_fs = '$(".overlay").toggle(0);  $(".overlay").height($(document).height());';
            } else {
                $js_fs = "";
            }

            if (!empty($this->url[$click])) {

                $host = $_SERVER['HTTP_HOST'];
                $open_url =
                        <<<popen
 //$(".iframe-cpanel").contents().find("body").css("background-color","#fff");
 //$('.iframe-cpanel').iframeAutoHeight({debug: true});
popen;
            } else {
                $open_url = "";
            }

            $outJS .=<<<HTML

function changeParentUrl_{$click}() {
  parent.window.location.reload();
}



$("#cose-cp-{$click}").click(function(){
         $("#{$click}_cpanel_content").hide("slide", { direction: "left" }, 600);
         $(".overlay").hide();
});

$('#if_{$click} html body').localScroll({
   target:  "#if_{$click}" ,
   axis:'xy',
   duration:3500,
   queue:true //one axis at a time
});


$(".{$click}_cpanel").live('click',function(){

         {$js_fs}
         $('.opened').hide();
            $("#{$click}_cpanel_content").addClass('opened')
            $("#{$click}_cpanel_content").toggle("blind", { direction: "vertical" }, 600);
         {$open_url}
         }
     );
HTML;
        }



        return $outJS;
    }

    private function linker($name, $content = '', $url = '', $num = '', $class = '') {
        $cpanel_get = CPANEL;



        $link_menu = "";
        $real_name = $name;
        $name = str_replace("/", "", $name);
        $HOST = HOST;

        if (empty($class)) {
            $class = "fullpage";


            foreach ($content as $title_lnk => $adress_lnk) {
                $title_name_lnk = str_replace(".", "_", $title_lnk);
                $title_name_lnk = str_replace("_", " ", $title_name_lnk);

                //panel?3079943161=users/#mained
                $link_menu .=<<<DOM
<A HREF="/panel?{$cpanel_get}={$name}/#{$title_lnk}" target="cpanel_{$name}" class="href-{$title_lnk}">{$title_name_lnk}&nbsp;</A>
DOM;
            }
            $title = <<<DIV

 <div class="cp-title"> {$name}
 <a href="{$HOST}panel?{$cpanel_get}={$real_name}/#index" class="cp-fullscreen" onClick="savelink()" target="_top">  </a>
 <div class="cp-menu" id="cp-menu-$name"> {$link_menu} </div>
 </div>
DIV;
            $this->fullscreen[] = $name;
            $title_lnk = "";

            $current = current($content);
            /*
             * Remuving scrolling="no" from iframe
             *
             */
            $html_content =
                    <<<html
<!-- inline, after the iframe {$name}/{$url}/{$current}-->
<iframe src="" hrefsrc="{$HOST}panel?{$cpanel_get}={$real_name}/" class="auto-height iframe-cpanel"
 frameborder="0" name="cpanel_{$name}" id="if_{$name}">
    </iframe>

html;
            $js_upper = "";
        } else {
            $html_content = $content;
        }

        $i = $this->i - 100;
// position of icon in panel
//$margin = $this->pico[$i];

        if (defined("MODULE_DIR")) {
            $module = constant("MODULE_DIR");
        }
        if (str_replace("/", "", $module) == $name) {

//$this->modl;

            $img = <<<IMG
    <img src="{$HOST}{$module}/{$this->modl['main']}{$this->modl['icon']}" alt="{$name}"   align="center" />
IMG;
        } else {

            $img = <<<IMG
    <img src="{$HOST}design/cpanel/icons/icons-{$name}.png" alt="{$name}"   align="center"  />
IMG;
        }

        $link = <<<EOL
    <a href="#{$name}" class="{$name}_cpanel" onClick="document.getElementById('if_{$name}').src=document.getElementById('if_{$name}').getAttribute('hrefsrc')">
    <span style="float:left;width:9px; position:absolute;">{$num}</span>
    {$img}
    </a>
EOL;

        $this->content .=<<<EOL
    <div  id="{$name}_cpanel_content" style="z-index:{$this->i};" class="{$class} " >
    <div  class="close-cp-button" id="cose-cp-{$name}" onClick="setTimeout(1200, 'changeParentUrl_{$name}()');"></div>
    {$title}
    {$html_content}
    </div>
EOL;

        $this->design = str_replace('<!-- LINK -->', $link . '<!-- LINK -->', $this->design);
        $this->js[$name] = $name;

        $this->i++;
    }

    private function module_data() {

        if (defined("MODULE_DIR")) {
            $main_dir['main'] = kernel::prop_constant("module.manager.main");
            $main_dir['privilege'] = kernel::prop_constant("module.manager.privilege");
            $main_dir['source'] = kernel::prop_constant("module.manager.source");
            $main_dir['cover'] = kernel::prop_constant("module.manager.cover");
            $main_dir['icon'] = kernel::prop_constant("module.manager.icon");
            $main_dir['css'] = kernel::prop_constant("module.manager.css");
            $main_dir['template'] = kernel::prop_constant("module.admin.template");

            $this->modl = $main_dir;
        }
    }

    private function read_dir($dir) {

        $dirs = array();
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $dirs[] = $entry;
                }
            }
            closedir($handle);
        }else
            return false;;

        return $dirs;
        ;
    }

    private function catch_opt() {

        global $SQL_WEBSITE;

        $this->optn = $SQL_WEBSITE;
    }

    private function chmod_fix($file) {
        if (file_exists($file)) {
            if (substr(sprintf('%o', @fileperms($file)), -4) != 0600) {
                @chmod($file, 0600);
            }
        }
    }

    private function add_priv_prop() {
        global $KERNEL_PROP_MAIN;

        $this->privfl = $KERNEL_PROP_MAIN['kernel.manager.privilege.defprop'];


        $open = ROOT . self::manage . "/" . $KERNEL_PROP_MAIN['kernel.manager.privilege.folder'] . self::folder . "/" . $this->privfl;

        self::chmod_fix($open);
        if (file_exists($open)) {
            return kernel::loadprop($open, 1);
        }else
            return false;;
    }

    private function add_priv_propmod() {

        global $KERNEL_PROP_MAIN;

        $this->privfl = $KERNEL_PROP_MAIN['kernel.manager.privilege.defprop'];

        $open = ROOT . MODULE_DIR . $this->modl['main'] . $this->modl['privilege'] . self::folder . "/" . $this->privfl;


        self::chmod_fix($open);
        if (file_exists($open)) {

            return kernel::loadprop($open, 1);
        } else {
            return false;
        }
    }

    private function priv_from_modules() {


        if (defined("MODULE_DIR")) {


            $this->catch_option(constant("MODULE_DIR"), $this->add_priv_propmod(), $this->modl['main'] . $this->modl['source']);
        }
    }

    private function priv_from_files($prop) {



        foreach ($prop as $group => $file_add) {

            $this->catch_option($group, $file_add, ($this->exec));
        }
    }

    /**
     * Output of Control Panel
     *
     */
    function output() {
        global $kernel;
        if (defined("CPANEL") && !isset($_GET[@constant('CPANEL')])) {
            kernel::includeByHtml(HOST . $this->style, 'css');
            kernel::includeByHtml(HOST . 'jquery/ui/jquery-ui-custom.min.js', 'js');
            //kernel::includeByHtml(HOST.'design/cpanel/iframe.js','js');
            kernel::includeByHtml(HOST . 'design/cpanel/inside/js/localscroll-min.js', 'js');
            $kernel->external_file('jquery', self::JavaScript());
            //kernel::includeByHtml(self::JavaScript(), 'add');
            $fulscreen = "\n" . '<div class="overlay"></div>';

            global $THEME;
            $GLOBALS['THEME'] = str_replace('<body>', '<body>' . $this->design . $this->content . $fulscreen, $GLOBALS['THEME']);
        }
    }

}

