<?php

/**
  ============================================================
  Last committed:     $Revision: 134 $
  Last changed by:    $Author: fire1 $
  Last changed date:    $Date: 2013-04-05 12:49:50 +0300 (ïåò, 05 àïð 2013) $
  ID:            $Id: design.dp.php 134 2013-04-05 09:49:50Z fire1 $
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
    header("location: error404");
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

/**
 * class theme     extends    kernel
 * @package theme
 * @version 1.85
 */
class theme extends kernel {

    /**
     * This variable is parsed as part of package kernel.
     * Contains configuration of website.
     * @var $CONFIGURATION
     */
    var $CONFIGURATION;

    /**
     * This variable is parsed as part of package theme.
     * Contains values of design.prop file
     * @var $PROP
     */
    var $PROP;

    /**
     * This variable is parsed as part of package theme
     * Contains values of theme.prop file from selected theme.
     * @var $THEMEPROP
     */
    var $THEMEPROP;
    //MERGE with external prop
    var $MERGE_THM;

    /**
     * This variable is parsed as part of package theme.
     * Contains values of template.prop file from selected theme.
     * @var $TEMPLATEPROP
     *
     */
    var $TEMPLATEPROP;
    //MERGE with external prop
    var $MERGE_TMPL;

    /**
     * This variable is parsed as part of package theme.
     * Contains generated html website theme.
     * @var $theme
     */
    var $theme;

    /**
     * This variable is parsed as part of package theme.
     * Contains dynamic MySQL select information for website.
     * @var $WEBSITE
     */
    var $WEBSITE;

    /**
     * This variable is parsed as part of package theme
     * and contains dynamic html parts that are with dynamic information for website.
     * @var $strHtmlData
     */
    private $strHtmlData = array();

    /**
     * This variable is parsed as part of package theme.
     * This variable value is links of website.
     * @var $links
     */
    private $links;

    /**
     * This variable is parsed as part of package theme.
     * This variable value is menus of website.
     * @var $menu
     */
    private $menu;

    function __construct() {
        $is++;
        kernel::CONFIGURATION();
        kernel::loadprop(__FILE__);
        self::theme_propping();
        self::template_setup();
        self::construct_theme();
        self::links_construct();
        self::sql_website_tags();
    }

    function __destruct() {
        //empty
    }

    /**
     * Load theme properties of the website and load it.
     * It loadtheme prop / template prop files
     * Create's $SQL_WEBSITE global array
     * loaded in varibles:
     * $this->WEBSITE/$this->TEMPLATEPROP/$this->THEMEPROP
     */
    private function theme_propping() {
        //
        // Insert Global data in function
        global $SQL_WEBSITE, $CONFIGURATION;
        //
        // Creating SQL query
        $queryresult = "SELECT
            `charset`,`theme`,`site_name`,`site_description`,`site_mail`,`site_meta`,`site_disclaimer`,`ico`,`lan`,`start_page`,`txt_area`,`copyright`,`site_keywords`
            FROM `" . PREFIX . "preferences`
            WHERE ID='1' ";
        //
        // Load SQL from cache
        $row = kernel::sql_fetch_array($queryresult);
        //
        // Check selected rows
        if (is_array($row)) {
            //
            // Fill-up date in class string
            $this->WEBSITE = $row[0];
            //
            // Fill-up default theme folder in array key
            $this->WEBSITE['themedir'] = $CONFIGURATION['themes'] . $this->WEBSITE['theme'];
            //
            // Check for forcing default folder file in array key
            if (!empty($this->PROP['MAIN']['theme.force_to_be'])) {
                //
                // Change if is forced
                $this->WEBSITE['theme'] = $this->PROP['MAIN']['theme.force_to_be'];
            }
            //
            // merge theme properties data
            self::merge_theme_prop();
            //
            // merge template properties data
            self::merge_template_prop();
            //
            // Fill-up findet theme file in Array key
            if (!defined("MOBILE_DEVICE")) {
                $this->WEBSITE['themefile'] = self::search_file_design('theme');
            } elseIf (defined("MOBILE_DEVICE")) {
                $this->WEBSITE['themefile'] = self::search_file_design('mobile_theme');
            }
        }
        //
        // Fill-up data in global string
        $SQL_WEBSITE = $this->WEBSITE;
    }

    /**
     * Add file and attach it to theme
     *
     */
    public function init_php() {
        global $theme, $MERGE_TEMPLATE;
        $array = $this->MERGE_THM['PHP_INIT'];
        //
        // Loop array string and files
        $lookup = array_keys($array);
        foreach ($array as $key => $val) {
            $file = kernel::base_tag(ROOT . $val);
            include($file);
            theme::assign($key, $$key);
        }
    }

    /**
     * Add desc
     *
     */
    private function sql_website_tags() {
        global $SQL_WEBSITE;
        $dollop_def = $SQL_WEBSITE;
        $dollop_def["DESC"] = $SQL_WEBSITE['site_description'];
        foreach ($dollop_def as $key => $val) {
            if (strlen($key) > 2):
                if (defined("template_" . strtoupper($key)))
                    self::content(array($val), $key);endif;
        }
    }

    /**
     * Construct Theme file and folders from URL
     *
     * @returns Array folders,requestMain,requestSub
     */
    private function url_theme_tree($case) {
        $request_uri = request_uri();
        $arrtreepart = explode("?", $request_uri);
        if (!empty($arrtreepart[0])) {
            if (empty($this->PROP['MAIN']['theme.request_parts'])) {
                $parts = array("&", "=");
            } else {
                $parts = $this->PROP['MAIN']['theme.request_parts'];
            }
            $query_string = str_replace($parts, "{|}", $arrtreepart[1]);
            $request_parts = explode("{|}", $query_string);
            $request_main = $request_parts[0];
            if (empty($request_main)) {
                switch ($case) {
                    case 'theme':
                        $request_main = $this->PROP['MAIN']['theme.def_filename'];
                        ;
                        break;
                    case 'template':
                        $request_main = $this->PROP['MAIN']['template.def_filename '];
                        ;
                        break;
                }
            }
            $request_sub = $request_parts[1];
        }
        if (!empty($arrtreepart[0])) {
            $folders = $arrtreepart[0];
        }
        return array('folders' => $folders, 'requestMain' => $request_main, 'requestSub' => $request_sub);
    }

    /**
     * templates theme files
     * @version 1.3
     * @param mixed $case // theme,templates
     * @return string file
     */
    private function search_file_design($case, $sector = 0) {
        global $CONFIGURATION;
        $request = self::url_theme_tree($case);
        switch ($case) {
            DEFAULT:
                kernel::ERROR('some problem in search_file_design()... ' . $case . '', 'php', 'give some unsuspected value');
                break;
            case 'mobile_theme':
                $mb = kernel::base_tag("{mobile}");
                $separator = $this->MERGE_THM['MAIN']['theme.file_separator'];
                $extention = $this->MERGE_THM['MAIN']['theme.file_extension'];
                $dir = $CONFIGURATION['themes'] . $this->WEBSITE['theme'] . "/";
                $def_main_file = $mb . $this->MERGE_THM['MAIN']['theme.def_filename'];
                $spare_parts = $this->PROP['theme.spare_parts'] . $mb . $this->MERGE_THM['MAIN']['theme.def_filename'] . $extention;
                break;
            case 'theme':
                $separator = $this->MERGE_THM['MAIN']['theme.file_separator'];
                $extention = $this->MERGE_THM['MAIN']['theme.file_extension'];
                $dir = $CONFIGURATION['themes'] . $this->WEBSITE['theme'] . "/";
                $def_main_file = $this->MERGE_THM['MAIN']['theme.def_filename'];
                $spare_parts = $this->PROP['theme.spare_parts'] . $this->MERGE_THM['MAIN']['theme.def_filename'] . $extention;
                break;
            case 'template':
                if (empty($sector)) {
                    kernel::ERROR('problem in search_file_design(), no <b>sector</b> value.' . $case . '', 'php', 'give some unsuspected value for string $sector');
                }
                // NEED HERE TO CREATE FOR MODULES
                $separator = $this->MERGE_THM['MAIN']['template.file_separator'];
                $extention = $this->MERGE_THM['MAIN']['template.file_extension'];
                $dir = $CONFIGURATION['themes'] . $this->WEBSITE['theme'] . "/" . $this->MERGE_THM['MAIN']['template.folder_destination'];
                $def_main_file = $this->MERGE_THM['MAIN']['template.def_filename'] . $separator . $sector;
                $spare_parts = $this->PROP['theme.spare_parts'] . $this->PROP['MAIN']['template.folder_destination'] . $this->PROP['MAIN']['template.def_filename'] . $this->PROP['MAIN']['template.file_separator'] . $sector . $this->PROP['MAIN']['template.file_extension'];
                break;
            //
            // this data is out from use, for now

            case 'mobile_template':
                if (empty($sector)) {
                    kernel::ERROR('problem in search_file_design(), no <b>sector</b> value.' . $case . '', 'php', 'give some unsuspected value for string $sector');
                }
                $mb = kernel::base_tag("{mobile}");
                $separator = $this->MERGE_THM['MAIN']['template.file_separator'];
                $extention = $this->MERGE_THM['MAIN']['template.file_extension'];
                $dir = $CONFIGURATION['themes'] . $this->WEBSITE['theme'] . "/" . $this->MERGE_THM['MAIN']['template.folder_destination'] . $mb;
                $def_main_file = $mb . $this->MERGE_THM['MAIN']['template.def_filename'] . $separator . $sector;
                $spare_parts = $this->PROP['theme.spare_parts'] . $this->PROP['MAIN']['template.folder_destination'] . $mb . $this->PROP['MAIN']['template.def_filename'] . $this->PROP['MAIN']['template.file_separator'] . $sector . $this->PROP['MAIN']['template.file_extension'];
                break;
        }
        //
        // Module dir template exampl: "md/templates/view"
        $mdDir = MODULE_DIR . urlCourse_templates . MODULE_TPL_DIR;
        //
        //
        //  md/templates/view/theme_id_1.html
        //  md/templates/view/template_body_id_1.tpl
        $sys_full_path = realpath($mdDir . "/" . $def_main_file . $separator . $request['requestMain'] . $separator . $request['requestSub'] . $extention);
        //
        //  md/templates/view/theme_id.html
        //  md/templates/view/template_body_id.tpl
        $sys_def_path = realpath($mdDir . "/" . $def_main_file . $separator . $request['requestMain'] . $extention);
        //
        //  md/templates/view/theme.html
        //  md/templates/view/template_body.tpl
        $sys_def = realpath($mdDir . "/" . $def_main_file . $extention);
        //
        //
            //  --
        //  --
        $full_path = realpath($dir . $request['folders'] . "/" . $def_main_file . $separator . $request['requestMain'] . $separator . $request['requestSub'] . $extention);
        //
        //  --
        //  --
        $def_path = realpath($dir . $request['folders'] . "/" . $def_main_file . $separator . $request['requestMain'] . $extention);
        //
        //  --
        //  --
        $name_path = realpath($dir . $request['folders'] . "/" . $def_main_file . $extention);
        //
        //  --
        //  --
        $null_path = realpath($dir . $request['requestMain'] . $extention);
        //
        //  --
        //  --
        $def_file = realpath($dir . $def_main_file . $extention);
        // check for themes in website theme folder
        If (($full_path)) {
            $file = $full_path; // in theme template    (theme)
        } else If (($sys_full_path)) {
            $file = $sys_full_path; // for templates in modules with sub data
        } else If (($name_path)) {
            $file = $name_path; // in theme template    (theme)
        } else If (($sys_def_path)) {
            $file = $sys_def_path; // for templates in modules without sub data
        } else If (($def_path)) {
            $file = $def_path;
        } else If (($sys_def)) {
            $file = $sys_def; // for templates in modules only folder data
        } else If (($null_path)) {
            $file = $null_path;
        } else If (($def_file)) {
            $file = $def_file;
        } else {
            $file = $spare_parts;
            if (!file_exists($file)) {
                // show in debugger problem
                kernel::ERROR('CANNOT LOAD "' . $case . '" for sector "' . $sector . '" in system. ', 'file', $file);
                $case = "";
                $file = "";
                return false;
            } else {
                kernel::ERROR('Attention for ' . $case . " " . $sector . ' system use spare parts for design. ', 'file', $file);
            }
        }
        return $file;
    }

    /**
     * Merge internal and external theme.prop files
     */
    private function merge_theme_prop() {
        global $CONFIGURATION;
        $external_prop_file = $CONFIGURATION['themes'] . $this->WEBSITE['theme'] . "/" . $this->PROP['theme.prop_filename'];
        if (file_exists($external_prop_file)) {
            $external_prop = kernel::loadprop($external_prop_file, 1);
        } else {
            kernel::ERROR('CANNOT LOAD theme prop file ' . $PropTheme, 'file', $this->PROP['PropTheme']);
        }
        if (is_array($external_prop['MAIN'])) {
            $this->MERGE_THM['MAIN'] = array_merge($this->PROP['MAIN'], $external_prop['MAIN']);
            ;
        } else {
            $this->MERGE_THM['MAIN'] = $this->PROP['MAIN'];
        }
        if (is_array($external_prop['HEADER'])) {
            $this->MERGE_THM['HEADER'] = array_merge($this->PROP['HEADER'], $external_prop['HEADER']);
            ;
        } else {
            $this->MERGE_THM['HEADER'] = $this->PROP['HEADER'];
        }
        if (is_array($external_prop['DEF_HTML_HEADER'])) {
            $this->MERGE_THM['DEF_HTML_HEADER'] = array_merge($this->PROP['DEF_HTML_HEADER'], $external_prop['DEF_HTML_HEADER']);
            ;
        } else {
            $this->MERGE_THM['DEF_HTML_HEADER'] = $this->PROP['DEF_HTML_HEADER'];
        }
        if (is_array($external_prop['PHP_INIT'])) {
            $this->MERGE_THM['PHP_INIT'] = array_merge($this->PROP['PHP_INIT'], $external_prop['PHP_INIT']);
            ;
        } else {
            $this->MERGE_THM['PHP_INIT'] = $this->PROP['PHP_INIT'];
        }
    }

    /**
     * Merge internal and external theme.prop files
     */
    private function merge_template_prop() {
        global $CONFIGURATION;
        $file = $CONFIGURATION['themes'] . $this->WEBSITE['theme'] . "/" . $this->MERGE_THM['MAIN']['template.folder_destination'] . "/" . $this->MERGE_THM['MAIN']['template.prop_filename'];
        if ($external_prop_file = realpath($file)) {
            $external_prop = kernel::loadprop($external_prop_file, 1);
            $this->TEMPLATEPROP = $external_prop;
            if (is_array($external_prop['SECTORS'])) {
                $this->MERGE_TMPL['SECTORS'] = array_merge($this->PROP['SECTORS'], $external_prop['SECTORS']);
                ;
            } else {
                $this->MERGE_TMPL['SECTORS'] = $this->PROP['SECTORS'];
            }
            foreach ($this->MERGE_TMPL['SECTORS']['sector'] as $sector) {
                $sector = strtoupper($sector);
                if (is_array($external_prop[$sector])) {
                    $this->MERGE_TMPL[$sector] = array_merge($this->PROP[$sector], $external_prop[$sector]);
                    ;
                } else {
                    $this->MERGE_TMPL[$sector] = $this->PROP[$sector];
                }
            }
            global $MERGE_TEMPLATE;
            $MERGE_TEMPLATE = $this->MERGE_TMPL;
        } else {
            kernel::ERROR('CANNOT LOAD template prop file ' . $PropTemplate, 'file', $external_prop_file);
        }
    }

    /// NOT READY

    /**
     * Template setup create
     *
     * @param mixed $sector
     */
    public function template_setup($sector = null) {
        if (is_null($sector)) {
            if (defined("MOBILE_DEVICE")) {
                $extDir = "mobile_";
                //$extDir = null;
            } else {
                $extDir = null;
            }
            foreach ($this->MERGE_TMPL['SECTORS']['sector'] as $sector) {
                $templates = kernel::get_contents(self::search_file_design($extDir . 'template', strtolower($sector)));
                define('template_' . strtoupper($sector), $templates);
            }
        } else {
            $templates = kernel::get_contents(self::search_file_design($extDir . 'template', strtolower($sector)));
            define('template_' . strtoupper($sector), $templates);
        }
    }

    /**
     * Fill template with content
     * Now can be done with array tags and array int tags (only array values)
     *
     * @version 1.6
     *
     * @param string $sector        Sector in HTML template
     * @param mixed $content        HTML content to be inserted in template
     * @$analog boolean $analog     Forcing content to answer of they keys. Also can beused constant "analog_tmpl_{sector}" to activate it.
     * @return mixed template
     */
    public function construct_templates($sector, $content, $analog = false) {
        global $MERGE_TEMPLATE, $THEME;
        //
        // Upper case for sector
        $sector = strtoupper($sector);
        //
        // Upper case for content
        $content_type = strtoupper($sector);
        //
        // Creating template constant
        $template = constant('template_' . $sector);
        $tag = "";
        $i = 0;
        $con = array();
        foreach ($MERGE_TEMPLATE[$sector]['sector'] as $sector) {
            //
            // Seck for empty content sector
            if (empty($content[$sector])) {
                //
                // fixing  $tag string to array
                if (!is_array($tag)) {
                    $tag = array();
                }
                //
                // Replace for empty tags
                $tag[$i] = "{" . strtoupper($sector) . "}";
            } else {
                //
                // Replace for gived tag
                $tag = "{" . strtoupper($sector) . "}";
                $template = str_replace($tag, $content[$sector], $template);
            }
            $i++;
        }
        //
        // Set-up analog tags
        if ((bool) $analog OR defined("analog_tmpl_" . strtoupper($sector))) {
            $con = $content;
            $content = array();
            $tag = array();
            $i = 0;
            foreach ($con as $key => $val) {
                $tag[$i] = "{" . strtoupper($key) . "}";
                $content[$i] = $val;
                $i++;
            }
        }
        if (is_array($tag)) {
            //
            // if tag si in array relace agen
            $output = str_replace($tag, $content, $template);
        } else {
            //
            // OUTPU the data
            $output = $template;
        }
        //
        // if is BODY template
        if ($content_type == 'BODY') {
            //
            // Attach template title to theme
            $GLOBALS['THEME'] = str_replace('<title></title>', '<title>' . current($content) . '</title>', $GLOBALS['THEME']);
        }
        //
        // Return aouput html
        return $output;
    }

    /**
     *
     *
     */
    private function content_request_manage() {
        global $PROP_DESIGN_DP;
        $uri_string = $PROP_DESIGN_DP['design.urirequest.vendor'];
        $vendor = $_GET[$uri_string];
    }

    // not ready

    /**
     * Contruct template and insert content on exist tags.
     * It have 4 types of constructing the HTML output.
     * Those can be controled with other functions in this class.
     *
     * @param Array         $content            Array Content for template
     * @param mixed         $content_type       The type of content
     * @param boolean       $return             It will return the generated HTML content.  Default is false
     * @$analog boolean     $analog             Forcing content to answer of they keys.     Default is false
     */
    public function content($content, $content_type = null, $return = false, $analog = false) {
        if (is_null($content_type)) {
            $content_type = 'BODY';
        }
        $template = self::construct_templates($content_type, $content, $analog);
        if (!is_array($content) && ($content_type == "BODY")) {
            self::responses(404);
        }
        if ((bool) $return) {
            return $template;
        } else {
            return self::assign($content_type, $template);
        }
    }

    /**
     * Shows html theme, Ends buffering and remuve menu tags
     *
     */
    public function theme_echo() {

        global $THEME, $PROP_DESIGN_DP, $kernel;
        // add menus
        self::menu_construct();
        // remove menu tags
        self::menu_remove_tags();
        // Generate files
        $kernel->generete_external_file();
        $kernel->set_current_page_theme();
        kernel::attempt_klog();
        kernel::GenerateAnstallationModule();
        echo $THEME;
        kernel::buffering('end');
    }

    /**
     * Attach the content with tag
     *
     * @param mixed $tag
     * @param mixed $vale
     */
    public function assign($tag, $vale) {
        global $THEME;
        $THEME = str_replace('{' . strtoupper($tag) . '}', $vale, $THEME, $count);
        return $count;
    }

    /**
     * Replace tags from template
     * @example replace_tag(array("tag"=>array("title"),"value"=>array("my title")),$template);
     *
     * @param mixed $array
     * @param mixed $template
     */
    public function replace_tag($array, $template) {
        foreach ($array as $key => $val) {
            $template.= str_replace('{' . strtoupper($key) . '}', $val, $template);
        }
        return $template;
    }

    /**
     * Constructe html theme
     *
     */
    private function construct_theme() {
        $theme_file = $this->WEBSITE['themefile'];
        $html_prop_tag = $this->MERGE_THM['MAIN']['theme.html_start_tag'];
        $html_count_start_tag = strlen($html_prop_tag);
        // Do NOT USE kernel::get_contents() HERE!!!
        $theme_html_tag = file_get_contents($theme_file, NULL, NULL, 0, $html_count_start_tag);
        $this->theme = kernel::get_contents($theme_file);
        // This will set the theme of current page for cpanel use
        kernel::set_current_page_theme($theme_file);
        if (strtolower($html_prop_tag) != strtolower($theme_html_tag)) {
            $external_header = pathinfo($theme_file);
            if (file_exists($external_header['dirname'] . $this->PROP['theme.spare_parts_header'])) {
                $header_html = kernel::get_contents($external_header['dirname'] . $this->PROP['theme.spare_parts_header']);
            } elseIf (file_exists($external_header['dirname'] . $this->MERGE_THM['MAIN']['theme.spare_parts_header']) && !empty($this->MERGE_THM['MAIN']['theme.spare_parts_header'])) {
                $header_html = kernel::get_contents($external_header['dirname'] . $this->MERGE_THM['MAIN']['theme.spare_parts_header']);
            } else {
                $header_html = kernel::get_contents($this->PROP['theme.spare_parts'] . $this->PROP['theme.spare_parts_header']);
            }
            $this->theme = $header_html . $this->theme;
        }
        // src=" attributes
        if ($this->MERGE_THM['MAIN']['theme.src_in_tags']) {
            $this->theme = str_replace('src="', 'src="' . HOST . $this->WEBSITE['themedir'] . "/", $this->theme);
        }
        global $KERNEL_PROP_DATA;
        $includes['STYLECSS'] = $KERNEL_PROP_DATA['MAIN']['include.TagStyleCSS'];
        $includes['JSCRIPT'] = $KERNEL_PROP_DATA['MAIN']['include.TagJavaScript'];
        $sql_header_string = self::mysql_to_header_string();
        // header tags
        foreach ($this->MERGE_THM['HEADER'] as $header_key => $header_tag) {
            $header_content = self::header_html_templates($header_key, $sql_header_string[$header_key]);
            if ($includes[$header_key]) {
                $inc = $includes[$header_key];
            }
            $this->theme = str_replace($header_tag, $header_content . $inc, $this->theme);
        }
        $GLOBALS['THEME'] = $this->theme;
    }

    /**
     * CONVERT  mysql data t0 strings
     *
     */
    private function mysql_to_header_string() {
        $string['LANGUAGE'] = $this->WEBSITE['lan'];
        $string['FAVICON'] = $this->WEBSITE['ico'];
        $string['CHARSET'] = $this->WEBSITE['charset'];
        $string['KEYWORDS'] = $this->WEBSITE['site_keywords'];
        $string['METATAGS'] = $this->WEBSITE['site_meta'];
        if (defined("MOBILE_DEVICE")) {
            $mb = kernel::base_tag("{mobile}");
        } else {
            $mb = null;
        }
        //
        //
            //
            // Looping System header varible and Custom header data and place
        //  it by default in html theme.
        foreach ($this->MERGE_THM['DEF_HTML_HEADER'] as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $return) {
                    //
                    // Validing varible
                    $return = ((bool) filter_var($return, FILTER_VALIDATE_URL)) ? $return : kernel::base_tag($return);
                    //
                    // Insert it in te valid template
                    $htmltag = theme::header_mobile_jquery($return);
                    if ((bool) $htmltag) {
                        $string[$key].= $htmltag;
                    } else {
                        if ($key == 'STYLECSS') {
                            $string[$key].= self::header_html_templates($key, "/" . dirname($return) . "/" . $mb . pathinfo($return, PATHINFO_BASENAME), 1) . "\n";
                        } else {
                            $string[$key].= self::header_html_templates($key, "/" . $return, 1) . "\n";
                        }
                    }
                }
            } else {
                $string[$key] = $value;
            }
        }
        return $string;
    }

    /**
     * html header template
     *
     * @param mixed $tagstring string for section
     * @param mixed $value   content
     * @return mixed template
     */
    private function header_html_templates($tagstring, $value, $after_mysql_to_header_string = 0) {
        $dir = $this->PROP['theme.spare_parts_folder'];
        $ext = $this->PROP['theme.spare_parts_extension'];
        $tag = $this->PROP['theme.spare_parts_tag'];
        if ($this->MERGE_THM['MAIN']['theme.html_header'] && !is_array($this->MERGE_THM['DEF_HTML_HEADER'][$tagstring]) OR $after_mysql_to_header_string == 1) {
            $template = kernel::get_contents($dir . strtolower($tagstring) . $ext);
            $template = str_replace($tag, $value, $template);
        } else {
            $template = $value;
        }
        return $template;
    }

    /**
     * This function will replace file if is required
     *   for mobile version of website.
     *
     * @param string $file_path The file path that will be checked.
     * @return new file if is active mobile website version
     */
    private function header_mobile_jquery($file_path) {
        //
        // Check for active mobile device.
        if (!defined("MOBILE_DEVICE"))
            return false;
        //if((bool)constant("MOBILE_DEVICE") === false ) return false;
        //
            // THis will catch only jquery file and replace it with mobile file
        $htmltag = str_replace(kernel::base_tag("{jquery}/{jquery_file}"), "", $file_path, $count);
        //
        // Attach css style when count replace
        if ((bool) $count) {
            $return = "";
            $return.= self::header_html_templates('JSCRIPT', kernel::base_tag("/{jquery}/{jquery_file}"), 1) . "\n";
            $return.= self::header_html_templates('JSCRIPT', kernel::base_tag("/{mobile_jquery}"), 1) . "\n";
            $return.= self::header_html_templates('STYLECSS', kernel::base_tag("/{mobile_style}"), 1) . "\n";
            return $return;
        } else {
            return false;
        }
    }

    /**
     * Trunk theme
     *
     */
    private function output() {
        global $THEME;
        $trunkFile = 'theme' . $this->WEBSITE['themefile'] . '.eval';
        $TRTHEME = kernel::trunkit($trunkFile);
        if (!(bool) $TRTHEME AND $this->PROP['theme_trunkit'] == true) {
            //self::construct_theme();
            $scr = '$THEME =<<<SCRIPTS' . $THEME . 'SCRIPTS;';
            kernel::ERROR(' NO trunkit: <b>output</b> ', 'dbug', $trunkFile);
            kernel::trunkit($trunkFile, $scr, __FILE__);
        } else {
            kernel::ERROR(' USED trunkit: <b>output</b> ', 'dbug', $trunkFile);
            $THEME = $TRTHEME;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////  CONSTRUCT THEME PARTS

    /**
     * construct website menu /s
     * return mysql and plugin menu in array
     */
    public function menu_discover($theme_html = null) {
        if ($theme_html == null) {
            global $SQL_WEBSITE;
            $theme = $SQL_WEBSITE['themefile'];
            $theme_html = kernel::get_contents($theme);
        }
        $bluff0 = explode("{MENU[", $theme_html);
        $i = 0;
        foreach ($bluff0 as $bluffer) {
            $bluff1 = explode("]}", $bluffer);
            if ($i >= 1) {
                $bluff[] = $bluff1[0];
            }
            $i++;
        }
        return $bluff;
    }

    /**
     * Construct and adds website menus from mysql
     *
     * @version 0.3
     * @todo Menu proccess for finding Module menu
     */
    private function menu_construct() {

        $MENU = '';
        $sqlquery = "SELECT * FROM " . PREFIX . "menus ORDER BY `position` ";
        $rows = kernel::sql_fetch_array($sqlquery);
        if (is_array($rows)) {
            foreach ($rows as $dp_menu) {
                $TITLE = $dp_menu['title'];
                $BODY = $dp_menu['body'];
                // Execute PHP Script in menu
                if ((bool) $dp_menu['phpscript']) {
                    $fixscriptquotes = stripallslashes($dp_menu['phpscript']);
                    //echo "<pre>";
                    //print_r(preg_replace('/\s+/', '', $fixscriptquotes));
                    eval(preg_replace('/\s+/', '', $fixscriptquotes));
                }
                // Add JavaScript
                if ((bool) $dp_menu['jscripts']) {
                    kernel::external_file('jquery', $dp_menu['jscripts']);
                }
                $dp_memu_content[$dp_menu['section']].= (self::construct_templates('menu', array($TITLE, $BODY)));
            }

            foreach ($dp_memu_content as $section => $content) {
                self::assign("menu[{$section}]", $content);
            }
        } else
            return false;
    }

    /**
     * Remove Menu Tags from theme
     *
     */
    private function menu_remove_tags() {
        $all_menus_sectors = self::menu_discover($GLOBALS['THEME']);
        // remuve all menu tags tags
        if (!is_array($all_menus_sectors))
            return false;;
        foreach ($all_menus_sectors as $clear_section) {
            $tag_menu[] = "{MENU[{$clear_section}]}";
        }
        $GLOBALS['THEME'] = str_replace($tag_menu, "", $GLOBALS['THEME']);
    }

    /**
     * Construkt links
     *
     */
    private function links_construct() {
        $queryresult = "SELECT *  FROM " . PREFIX . "links WHERE `GR`='0' ORDER BY `position`;";
        $This_ArrResult = kernel::sql_fetch_array($queryresult);
        $filter = explode("?", request_uri());
        foreach ($This_ArrResult as $row) {
            $act_link = "";
            // catch curent link from url
            if ("?" . $filter[0] == $row['url']) {
                $act_link = 'class="act_link"';
            }
            $sub_template = " ";
            //SUBLINK   start
            $subsql = "SELECT * FROM " . PREFIX . "links WHERE `GR`='{$row['ID']}' ORDER BY `position`;";
            $this_sub = kernel::sql_fetch_array($subsql);
            if (is_array($this_sub)) {
                foreach ($this_sub as $subrow) {
                    $sub_template_temp = self::construct_templates('sublink',
                                    // Sub-link data array
                                    array("title" => $subrow['title'], "url" => link_http_fix($subrow['url']), "target" => (!empty($subrow['target'])) ? $subrow['target'] : "_self"));
                    $sub_template .= $sub_template_temp;
                }
            }
            //SUBLINK   end
            // LINK
            // convert sql data to templated link
            $template.= self::construct_templates('link',
                            // Links data array
                            array("title" => $row['title'], "url" => link_http_fix($row['url']), "target" => (!empty($row['target'])) ? $row['target'] : "_self", "sublink" => $sub_template));
        }

        $template = preg_replace('#<ul (.*?)> </ul>#', "", $template);

        // assign links to theme
        self::assign('link', $template);
        if (empty($template)) {
            kernel::ERROR('CAN NOT construct links, returns empty data ', 'php', 'may be construct_templates(\'links\',...) returns empty data');
        }
        //kernel::includeByHtml(kernel::base_tag("{design}/theme/css/sublinks.css") , 'css');
    }

    /**
     * create response of website
     *
     * @param mixed $code
     */
    public function responses($code, $moreinfo = null) {
        @mysql_close($dp);
        @mysql_close();
        self::content(dp_show_responses($code, $moreinfo));
        self::theme_echo();
        exit();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////  CONSTRUCT HTML PARTS

    /**
     *  load file and replace tags
     *
     * @param string $file   // file destination
     * @param array $tags   // Array('tag'=>'value');
     */
    public function load_tpl($file, $tag) {
        $content = kernel::get_contents($file);
        foreach ($tag as $t => $v) {
            $content = str_replace("{{$t}}", $v, $content);
        }
        return $content;
    }

    /**
     * Create html textarea
     *
     * @param mixed $name    // textarea name and id
     * @param mixed $text    // text in textarea
     * @param mixed $class   // class of text area
     * @param mixed $rows    // rows for textarea
     * @param mixed $cols    // cols for textarea
     * @param mixed $width   // width for textarea
     * @param mixed $height  // height for textarea
     * @param mixed $theme   // theme for textarea
     * @return mixed
     */
    public static function textarea($name, $text = null, $class = null, $rows = null, $cols = null, $width = null, $height = null, $theme = null) {
        global $KERNEL_PROP_MAIN, $SQL_WEBSITE, $PROP_DESIGN_DP, $__global_count_of_textareas__;
        @$__global_count_of_textareas__++;
        $confFile = $KERNEL_PROP_MAIN['kernel.urlCourse.configProp'];
        $dir = kernel::CONFIGURATION('textarea');
        $conf = kernel::loadprop($dir . $confFile, 1);
        $key = $SQL_WEBSITE['txt_area'];
        $tag_jscript = $PROP_DESIGN_DP['design.textarea.javascript'];
        $tag_template = $PROP_DESIGN_DP['design.textarea.template'];
        $tag_ctemplate = $PROP_DESIGN_DP['design.textarea.ctemplate'];
        if (defined('CPANEL')) {
            $tagtemplate = $tag_ctemplate;
        } else {
            $tagtemplate = $tag_template;
        }
        //Jscript File
        $scriptFile = $dir . $conf[$key][$tag_jscript];
        //Template File
        $templateFile = $dir . $conf[$key][$tagtemplate];
        //custom tags
        if (empty($rows)) {
            $customtags['rows'] = $conf[$key]['rows'];
        } else {
            $customtags['rows'] = $rows;
        }
        if (empty($cols)) {
            $customtags['cols'] = $conf[$key]['cols'];
        } else {
            $customtags['cols'] = $cols;
        }
        if (empty($width)) {
            $customtags['width'] = $conf[$key]['width'];
        } else {
            $customtags['width'] = $width;
        }
        if (empty($height)) {
            $customtags['height'] = $conf[$key]['height'];
        } else {
            $customtags['height'] = $height;
        }
        if (empty($class)) {
            $customtags['class'] = $conf[$key]['class'];
        } else {
            $customtags['class'] = $class;
        }
        if (empty($theme)) {
            $customtags['theme'] = $conf[$key]['theme'];
        } else {
            $customtags['theme'] = $theme;
        }
        if (!empty($customtags['width'])) {
            $customtags['width'] = "width:{$customtags['width']};";
        }
        if (!empty($customtags['height'])) {
            $customtags['height'] = "height:{$customtags['height']};";
        }
        if (!empty($customtags['class'])) {
            $customtags['class'] = "class=\"{$customtags['class']}\"";
        }
        // configuration other tags
        $customtags['elment_area'] = "elment_textarea" . $__global_count_of_textareas__;
        $customtags['username'] = $_COOKIE['username'];
        $customtags['usersess'] = $_COOKIE['usersess'];
        $customtags['theme_dir'] = HOST . $SQL_WEBSITE['themedir'];
        // personal tags
        $personaltags['name'] = $name;
        $personaltags['text'] = $text;
        $alltags = array_merge((array) $customtags, (array) $personaltags);
        $SQL_WEBSITE['txt_area_jscript'] = $scriptFile;
        kernel::includeByHtml($scriptFile, 'JS');
        return self::load_tpl($templateFile, $alltags);
    }

    /**
     * Creating custom template and retrun html content with data.
     *
     * @example $body = theme::custom_template("mytemplate",array("title"=>"my title","content"=>"my page content"));
     *
     * @version 0
     *
     * @param string        $name               The key name of template and sector
     * @param array         $arrSectors         Arrayed content for template
     * @param boolean       $return             It will return the generated HTML content.  Default is false
     * @return mixed        template
     */
    public function custom_template($name, $arrSectors, $analog = true) {
        //
        // Global settings
        global $MERGE_TEMPLATE, $theme;
        //
        // Check user data
        if (!is_array($arrSectors))
            return false;
        //
        // Empty old sectors
        $MERGE_TEMPLATE[strtoupper($name)]['sector'] = null;
        //
        // Loop sectors
        foreach ($arrSectors as $secKey => $value) {
            //
            // Merge sectors with global Array
            $MERGE_TEMPLATE[strtoupper($name)]['sector'][] = strtoupper($secKey);
            //
            // Fill up content and tags
            $rowTag[$secKey].= $value;
        }
        //
        // Set-up the template
        $theme->template_setup($name);
        //
        // Return the Generated template
        return theme::content($rowTag, strtoupper($name), true, $analog);
    }

    /**
     * Creates Custom tags in template.
     *  This function is mainly intended for already exist templates like the BODY template (template_body).
     *
     *  @param string $name
     *  @param array $arrSectors
     *
     */
    public function custom_tags_intemplate($name, $arrSectors) {
        //
        // Global settings
        global $MERGE_TEMPLATE, $theme;
        //
        // Check user data
        if (!is_array($arrSectors))
            return false;
        //
        // Empty old sectors
        $MERGE_TEMPLATE[strtoupper($name)]['sector'] = null;
        //
        // Loop Sectors
        foreach ($arrSectors as $secKey => $value) {
            $MERGE_TEMPLATE[strtoupper($name)]['sector'][] = strtoupper($secKey);
        }
        //
        // Re-Setup the template
        $theme->template_setup($name);
    }

    /**
     * This function will mix php with html data
     * @param string $template HTML content
     */
    public function touch_template(&$template) {

    }

    /**
     * Attach JQuery UI file
     *
     */
    public function jquery_ui($style = false) {
        $jquery = self::header_html_templates('JSCRIPT', kernel::base_tag('/{jquery}{jquery_file}'), 1);
        $jquery_ui = self::header_html_templates('JSCRIPT', kernel::base_tag('/{ui_jquery}'), 1);
        if ((bool) $style) {
            $jquery_ui .="\n" . self::header_html_templates('STYLECSS', kernel::base_tag('/{ui_jquery_style}'), 1);
        }
        $GLOBALS['THEME'] = str_replace($jquery, $jquery . "\n" . $jquery_ui, $GLOBALS['THEME'], $cont);
        return (bool) $cont;
    }

}

global $theme;
$theme = new theme();

