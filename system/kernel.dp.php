<?php

/**
  ============================================================
 * Last committed:      $Revision: 134 $
 * Last changed by:     $Author: fire1 $
 * Last changed date:   $Date: 2013-04-05 12:49:50 +0300 (ïåò, 05 àïð 2013) $
 * ID:                  $Id: kernel.dp.php 134 2013-04-05 09:49:50Z fire1 $
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
 * @author Angel Zaprianov <fire1@abv.bg>
 * @filesource kernel
 * @package dollop
 * @subpackage kernel
 * @version 1.1026
 *
 * This are Global string used in Dollop
 */
global $CONFIGURATION, $SQL_WEBSITE, $USER_SESS, $THEME, $ERRORS;

class kernel {

    const version = '1.1026';

    /**
     * $MAIN contains [MAIN] sector from .prop file
     *
     * @var string
     */
    PRIVATE $MAIN;

    /**
     * $KERNEL variable is parsed as part of package kernel.
     *
     * contains kernel all .prop data  (still not in use).
     * @var mixed
     */
    PRIVATE static $KERNEL;

    /**
     * $BOOT variable is parsed as part of package kernel.
     *
     * Controls kernel boot mode.
     * @var mixed
     */
    PRIVATE $BOOT;

    /**
     * $EXTENSIONS variable is parsed as part of package kernel.
     *
     * This varible contains extensions (add-ons) of kernel.
     * @var mixed
     */
    PUBLIC $EXTENSIONS;

    /**
     * $ERRORS variable is parsed as part of package kernel.
     *
     * This variable contains all errors, errors types, debugger data.
     * @var string
     */
    var $ERRORS;

    /**
     * $SAVEMODE variable is parsed as part of package kernel.
     *
     * Controls SAVEMODE of system and kernel.
     * @var mixed
     */
    PUBLIC $SAVEMODE = false;

    /**
     * $REBIRTH variable is parsed as part of package kernel.
     *
     * Clear all cached data, GLOBAL refresh.
     * @var mixed
     */
    PUBLIC $REBIRTH = false;

    /**
     * $CONFIGURATION variable is parsed as part of package kernel.
     *
     * Contains website configuration data.
     * @var string
     */
    private static $kernel_file_contents;
    private static $kernel_file_contents_hash;

    /**
     * execute varible after connect to mysql
     *
     * @var mixed
     */
    private $exec_after_conn_sql = array();
    //
    //  Private properties for kernel
    private static $KERNEL_SAVEMODE;
    private static $KERNEL_REBIRTH;
    private static $KERNEL_PROP_DATA;
    private static $KERNEL_PROP_MAIN;
    private static $KERNEL_PROP_EXTENSIONS;
    private static $CONFIGURATION;
    var $CONF;
    //
    // Installation Module strings
    private static $installation_hash;
    private static $installation_id;

    /**
     *
     * @var Array Store log data
     */
    private static $klog;

    /**
     *
     * @var object
     */
    public $db;
    private static $db_conf;

    /**
     * External file  varible array
     *
     * @var array
     */
    private $external_file = array();

    /**
     * Var for current page theme
     *
     * @var mixed
     */
    public $current_page_theme;

    /*
     * @var array
     */
    private static $dirMap = array();

    /**
     * Construct class kernel
     * Starts the construction of  kernel.
     */
    public function __construct() {
        self::kernel_count_memory(1);
        // load kernel data
        // and
        // configuration data
        self::CONFIGURATION();
        // Set up the time zone
        global $CONFIGURATION;
        ini_set('date.timezone', $CONFIGURATION['timezone']);
        self::session_fix();
        self::buffering('start');
        self::kernel_status_mode();
        self::dollop_session_start();
        self::exec_etc_boot();
        if (self::SAVEMODE() === false) {
            // setup system data
            self::SetSecurityType();
            self::id_is_numericl();
            self::RegisterGlobals();
            self::ProxyAllow();
        }

        self::load_get_contents();
        self::MobileDevice();
        self::urlCourse_in_kernel();
        //
        // return firs from query;
        define('QF', self::query_GFV());
        // glueCode
        self::glueCode(__FUNCTION__);
    }

    /**
     * Load class
     *
     *  from library [lib] folder
     * @example add_data('main');
     *
     * @param string $class_name  [only name]
     */
    private static function classload($class_name) {
        include self::lib() . $class_name . '.class.php';
    }

    /**
     * ///////////////////////////////////////////////////////////////
      Usage:
      $auth = new Auth;
     * ///////////////////////////////////////////////////////////////
     *
     */

    /**
     * Buffering function
     *
     * @param mixed $case // (start,end)
     * @return mixed
     */
    public function kernel_count_memory($a = null) {
        global $kernel_memory_get_usage, $kernel_memory_get_usage_base;
        if ($a == 1) {
            $kernel_memory_get_usage_base = memory_get_usage();
        } elseif ($a == 2) {
            return (array) $kernel_memory_get_usage;
        } else {
            $kernel_memory_get_usage[$a] = memory_get_usage() - $kernel_memory_get_usage_base;
        }
    }

    /**
     * This will probably be done on application startup.
     * We need to use an absolute path here, but this is not hard to get with
     *  dirname(_FILE_) from your setup script or some such.
     *  Hardcoded for the example.
     * @example  Loader::Register('Core.Controls', '/controls');
     * @author Jon
     * @param type $virtual
     * @param type $physical
     */
    protected static function Register($virtual, $physical) {
        self::$dirMap[$virtual] = $physical;
    }

    /**
     * And then at some other point
     * @author Jon
     * @example kernel::Attach('Core.Controls.Control');
     * @param type $file
     */
    public static function Attach($file) {
        $pos = strrpos($file, '.');
        if ($pos === false) {
            die('Error: expected at least one dot.');
        }

        $path = substr($file, 0, $pos);
        $file = substr($file, $pos + 1);

        if (!isset(self::$dirMap[$path])) {
            die('Unknown virtual directory: ' . $path);
        }

        include (self::$dirMap[$path] . DIRECTORY_SEPARATOR . $file . '.php');
    }

    /**
     * kernel buffering
     *
     * @param mixed $case
     * @return mixed
     */
    public function buffering($case) {

        if (!self::$KERNEL_PROP_MAIN['kernel.buffering'])
            return false;;
        if (!empty(self::$KERNEL_PROP_MAIN['kernel.buffering.type'])) {
            $buffType = self::$KERNEL_PROP_MAIN['kernel.buffering.type'];
            $http_string = '$_SERVER["HTTP_ACCEPT_ENCODING"]';
        } else {
            $buffType = "";
        }
        switch ($case) {
            case 'start':
                ini_set('zlib.output_compression_level', self::$KERNEL_PROP_MAIN['kernel.buffering.level']);
                if (@$buffType) {
                    $eval = <<<php

if (substr_count({$http_string}, 'gzip')) ob_start("{$buffType}");

else ob_start();

php;
                } else {
                    $eval = ' if ( !ob_start( !DEBUGMODE ? \'ob_gzhandler\' : null ) ) { ob_start(); } ';
                }
                return eval($eval);
                break;
            case 'end':
                return eval("ob_end_flush();");
                break;
        }
    }

    /**
     * display memory
     *
     */
    public function memory_use() {

        if (defined('CPANEL') && self::$KERNEL_PROP_MAIN['kernel.memory.diagram'] == 1 OR self::$KERNEL_PROP_MAIN['kernel.memory.diagram'] == 2) {
            if (!isset($_GET[CPANEL])) {
                eval(' self::includeByHtml("' . self::$KERNEL_PROP_MAIN['kernel.memory.diagram.style'] . '","css");$GLOBALS["THEME"]= str_replace("</body>",diagram_chart_data(kernel::kernel_count_memory(2))."</body>",$GLOBALS["THEME"]);');
            }
        }
    }

    /**
     * This function load classes in Dollop
     *
     *  The string 'dollop.class' in kernel.prop file give the path
     *  for loading classes in main source.
     *
     */
    public static function autoload_classes() {

        self::kernel_count_memory(__FUNCTION__);

        eval('
                function __autoload($class_name) {
                global $KERNEL_PROP_MAIN;
                if(defined($KERNEL_PROP_MAIN[\'kernel.database.constant\'])){
                if(constant($KERNEL_PROP_MAIN[\'kernel.database.constant\']) == $class_name) return false;
                }
                $path = str_replace("_", DIRECTORY_SEPARATOR, $class_name);
                require_once( "' . ROOT . SRCDR . self::$KERNEL_PROP_MAIN["dollop.class"] . DIRECTORY_SEPARATOR . '" .$path  . ".php");
            }');
    }

    /**
     * Configure the external database management class
     * @uses lib Library folder
     */
    private static function db_kernel() {
        //
        // Check for call of class
        if (defined(self::$KERNEL_PROP_MAIN['kernel.database.constant'])) {
            $usage = constant(self::$KERNEL_PROP_MAIN['kernel.database.constant']);
            self::$db_conf = self::etc(self::$KERNEL_PROP_MAIN['kernel.database.folder'], $usage);

            if (!defined("KERNEL_DATABASE_CONF_INIT")) {
                include self::lib() . self::$KERNEL_PROP_MAIN['kernel.database.folder'] . self::$db_conf['kernel.database.class'];

                $conf_keys = array("sqlHOST", "DATABASE", "sqlUSER", "sqlPASS");
                $conf_vals = array(constant("sqlHOST"), constant("DATABASE"), constant("sqlUSER"), constant("sqlPASS"));
                eval(str_replace($conf_keys, $conf_vals, self::$db_conf['kernel.database.confg']));

                if (!empty(self::$db_conf['kernel.database.suppl'])) {
                    include self::lib() . self::$KERNEL_PROP_MAIN['kernel.database.folder'] . self::$db_conf['kernel.database.suppl'];
                }
            }
        }
    }

    /**
     * Kernel Function to execute functions
     *  from other (external) class
     *
     * @global kernel $kernel
     * @param string $sMethod
     * @param array $aUsage
     *
     * @return object The object is stored in "$kernel->db"
     */
    public function db($sMethod, $aUsage) {
        // Global string $kernel
        global $kernel;
        //
        // Filter prefix
        $aUsageTable = str_replace(constant("PREFIX"), "", $aUsage[0]);
        $aUsage[0] = constant("PREFIX") . $aUsageTable;
        //
        // Like this way the method/function is called and stored in object
        // This method is used for extension in future development
        $kernel->db = call_user_func_array(self::$db_conf['kernel.database.usage'] . $sMethod, $aUsage);
        // to be continued ...
    }

    /**
     * locate kernel folder
     *
     * @return kernel folder
     */
    private function dir() {
        return dirname(__FILE__);
    }

    /**
     * Return / Install / Add  - etc
     * In case of Install the function will use module prop data, it will return false if exist. Do not saved older information.
     * In case of  Add will use file names and path to construct the string. Do not saved older information.
     *
     *
     * @param mixed $folder      // destn of folder
     * @param mixed $resurse     // name of file (with ".prop")
     * @param mixed $case        // install add or check
     * @param mixed $initArray   // strings to add in etc
     * @param mixed $boot        // if is "1" will exec. on start and convert it to constants (dots"." are repl. with "_" in upper case)
     * @return kernel
     */
    public function etc($folder, $resurse, $case = null, $initArray = null, $boot = 0) {

        $file = (ROOT . SRCDR . self::$KERNEL_PROP_MAIN['dollop.etc'] . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . pathinfo($resurse, PATHINFO_FILENAME) . ".prop");
        if ($case == null) {
            $case = "check";
        }
        switch ($case) {
            case 'check':
                $reserved = self::loadprop($file, 1);
                return $reserved;
                break;
            case 'add':

                $date = date('d') . "-" . date('m') . "-" . date('Y');
                foreach ($initArray as $key => $value) {
                    $request.= <<<EOL

;#added on date:{$date}
{$folder}.{$resurse}.{$key}={$value}

EOL;
                }
                file_put_contents($file, $request, FILE_APPEND);
                break;
            case 'install':
                $dir = ROOT . SRCDR . self::$KERNEL_PROP_MAIN['dollop.etc'] . DIRECTORY_SEPARATOR . $folder;
                if (!is_dir($dir)) {
                    mkdir($dir, 0700, true);
                }
                // enabling OVERWRITTEN
                if ((bool) self::$KERNEL_PROP_MAIN['kernel.overwritten.etc'] === false) {
                    if (file_exists($dir . $resurse . ".prop")) {
                        return false;
                    }
                }

                $date = date('d') . "-" . date('m') . "-" . date('Y');
                $lines = count($initArray);
                $request = <<<EOL
;#######################################
; "etc" Dollop generated file
;   DO NOT EDIT THIS FILE BY HAND
;   -- YOUR CHANGES WILL BE OVERWRITTEN
;      on date:{$date}
;           with lines: {$lines}
;  file: {$file}
;#######################################

EOL;
                if (!is_array($initArray)) {
                    self::ERROR('<font color="#BF0000">[!] ERROR IN <b>"kernel::etc"</b> INSTALL ... !!! </font>', 'php', ' have wrong parameter, must be array ' . "\n Case: " . $case . "\n File:" . $file . "\n Line:" . __LINE__);
                    return false;
                }
                //
                // Loop the array
                if (defined("MODULE_NAME")) {
                    $patterns = array(
                        '/\W+/', // match any non-alpha-numeric character sequence, except underscores
                        '/\d+/', // match any number of decimal digits
                        '/_+/', // match any number of underscores
                        '/\s+/'  // match any number of white spaces
                    );

                    $replaces = array(
                        '', // remove
                        '', // remove
                        '', // remove
                        '_' // leave only 1 space
                    );
                    $string = preg_replace($patterns, $replaces, strtolower(constant("MODULE_NAME"))) . ".";
                } else {
                    $string = $resurse . ".";
                }
                foreach ($initArray as $key => $value) {
                    $request.= <<<EOL
;
md.{$string}{$key}={$value}

EOL;
                }
                file_put_contents($file, $request, LOCK_EX);
                // make boot
                if (defined("MODULE_NAME")) {
                    $boot = array(constant("MODULE_NAME") => realpath($file));
                } else {
                    $boot = array($file);
                }

                if ($boot) {
                    self::make_etc_boot($boot);
                }
                break;
        }
    }

    /**
     * Make files etc data files to be load
     *
     * @param mixed $arrayFiles // array string with files
     */
    private function make_etc_boot($arrayFiles) {
        // glueCode
        self::glueCode(__FUNCTION__);

        $file = self::$KERNEL_PROP_MAIN['dp.etc'];
        $dir = ROOT . SRCDR . self::$KERNEL_PROP_MAIN['dollop.etc'] . DIRECTORY_SEPARATOR . ".boot";
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }
        if (!file_exists($dir . DIRECTORY_SEPARATOR . $file)) {
            file_put_contents($dir . DIRECTORY_SEPARATOR . $file, serialize($arrayFiles), LOCK_EX);
        } else {
            $oldArr = unserialize(file_get_contents($dir . DIRECTORY_SEPARATOR . $file));
            $newArr = array_merge($oldArr, $arrayFiles);
            file_put_contents($dir . DIRECTORY_SEPARATOR . $file, serialize($newArr), LOCK_EX);
        }
    }

    /**
     * Execute etc data and conver it to constants
     *
     */
    private function exec_etc_boot() {

        self::kernel_count_memory(__FUNCTION__);
        $file = self::$KERNEL_PROP_MAIN['dp.etc'];
        $dir = ROOT . SRCDR . self::$KERNEL_PROP_MAIN['dollop.etc'] . DIRECTORY_SEPARATOR . ".boot/";
        //
        // Dollop's etc boot for users and other
        // This is default boot data
        $static_boots = unserialize(file_get_contents($dir . DIRECTORY_SEPARATOR . self::$KERNEL_PROP_MAIN['dp.etc.static']));
        //
        // Check for installed module etc
        if (file_exists($dir . DIRECTORY_SEPARATOR . $file)) {
            $user_boots = unserialize(file_get_contents($dir . DIRECTORY_SEPARATOR . $file));
        }
        //
        // Check for user boot array
        if (!is_array($user_boots)) {
            $boots = $static_boots;
        } else {
            //
            // Mrege boot arrays, user boot must replace static boot now
            $boots = array_merge($static_boots, $user_boots);
        }
        //
        // Loop the boot
        foreach ($boots as $boot) {
            $file = str_replace("{system}", ROOT . SRCDR, $boot);
            if (!realpath($file)) {
                self::ERROR('<font color="#BF0000">[!] ERROR IN <b>"kernel::exec_etc_boot"</b> BOOT </font>', 'php', 'Booted file do not exist ' . "\n File: " . $boot . " Line:" . __LINE__);
            }
            //
            // Parse  array
            $arrParse = self::loadprop($file, 1);
            if (is_array($arrParse)) {
                foreach ($arrParse as $key => $value) {
                    $key = strtoupper(str_replace(".", "_", $key));
                    if (!defined($key)) {
                        define($key, $value);
                    }
                }
            }
        }
    }

    /**
     * Prop loader
     * This function load all ".prop" files in Dollop.
     *
     * The first argument of function is for the prop file destination,
     *  also this argument can filled with file destination of execution,
     *  simply bay using __FILE__ constant in php.
     *
     * If secondary argument of function is filled the function will
     * return array information. In case the secondary argument is not empty
     * the function will create global string array with name upper case from
     * name of the prop file. Note in this operation is
     * require the file name without special characters.
     *
     * @package dollop
     *
     * @param mixed $__FILE__
     * @param mixed $return
     * @return array
     * Like this way function will return array
     * @example $array = kernel::loadprop(__FILE__,true);
     * Copy kernel configuration to external
     * @global array $KERNEL_PROP_DATA, $KERNEL_PROP_MAIN, $KERNEL_PROP_EXTENSIONS
     *
     *  Like this way function will construct global string from prop file name
     *  kernel::loadprop(__FILE__);
     *
     */
    protected function loadprop($__FILE__ = "", $return = false) {
        if (empty($__FILE__)) {
            self::kernel_count_memory(__FUNCTION__);
            //
            // Set-up main strings
            self::$KERNEL = parse_ini_file(self::dir() . DIRECTORY_SEPARATOR . "kernel.prop", true);
            //$this_MAIN = self::$KERNEL['MAIN'];
            //$this_EXTENSIONS = self::$KERNEL['EXTENSIONS'];
            self::$KERNEL_PROP_DATA = self::$KERNEL;
            self::$KERNEL_PROP_MAIN = self::$KERNEL['MAIN'];
            //
            // Set-up for object kernel
            if (is_object($this)) {
                $this->KERNEL = $this_KERNEL;
                $this->MAIN = $this_KERNEL['MAIN'];
                $this->EXTENSIONS = $this_KERNEL['EXTENSIONS'];
            }
            //
            // Copy Kernel configuration to external strings
            global $KERNEL_PROP_DATA, $KERNEL_PROP_MAIN, $KERNEL_PROP_EXTENSIONS;
            $KERNEL_PROP_DATA = self::$KERNEL;
            $KERNEL_PROP_MAIN = self::$KERNEL_PROP_DATA['MAIN'];
            $KERNEL_PROP_EXTENSIONS = self::$KERNEL_PROP_DATA['EXTENSIONS'];
            self::$KERNEL_PROP_EXTENSIONS = self::$KERNEL['EXTENSIONS'];
        } else {

            global $CONFIGURATION;
            $file_parts = pathinfo($__FILE__);
            $srchArr = array($file_parts['extension'], ".dp", ".class");
            $replace = array("prop", "");

            $file = str_replace($srchArr, $replace, $__FILE__);
            if ($return) {
                $prop_data = parse_ini_file($file, true);
                if (is_array($CONFIGURATION) && (bool) self::$KERNEL_PROP_MAIN['MasterWithSlaveProp']) {
                    $prop_data = combine_master_with_slave($CONFIGURATION, $prop_data);
                }
                return $prop_data;
                ;
            } else {
                $filter = array(".", "-", "@", "=", "{", "}", "#", "!", "~");
                $global_string = str_replace($filter, "_", $file_parts['filename']);
                $prop_data = parse_ini_file($file, true);
                if (is_array($CONFIGURATION) && (bool) self::$KERNEL_PROP_MAIN['MasterWithSlaveProp']) {
                    $prop_data = combine_master_with_slave($CONFIGURATION, $prop_data);
                }
                $this->PROP = $prop_data;
                if (preg_match("/^[A-Za-z][a-zA-Z0-9_]/", $global_string) && self::$KERNEL_PROP_MAIN['kernel.loadprop.global_string']) {
                    $global_string = "prop_" . $global_string;
                    $GLOBALS[strtoupper($global_string)] = $prop_data;
                }
            }
        }
    }

    /**
     * Create file or return data / require
     *
     * if all empty return bin folder
     * @copyright fire1
     * @param mixed $file return data or false
     * @param mixed $type type to put or type to execute
     * @return mixed  data array / require /false
     */
    protected function lib($file = "", $type = "", $main = false) {
        self::kernel_count_memory(__FUNCTION__);
        //
        // If is empty return only folder
        if (empty($file)) {
            return self::dir() . DIRECTORY_SEPARATOR . self::$KERNEL_PROP_MAIN['dollop.lib'] . DIRECTORY_SEPARATOR;
        } else {
            //
            // Using other types
            if ((bool) $main) {
                $file = self::dir() . DIRECTORY_SEPARATOR . self::$KERNEL_PROP_MAIN['dollop.lib'] . DIRECTORY_SEPARATOR . $file;
            }
            switch ($type) {
                case 'require':
                    if (file_exists($file)) {
                        require_once ($file);
                    } else
                        return false;;
                    break;

                case "include":
                    @include($file);
                    break;
                case 'unserialize':
                    return unserialize(file_get_contents($file));
                    break;
                DEFAULT:
                    file_put_contents($file, serialize($type), LOCK_EX);
                    break;
            }
        }
    }

    /**
     * usr data in system
     *
     * In case of all parameters empty will return usr folder
     * Create file and return data or require it
     *
     * Valid types are require, unserialize
     *
     * require - will extract ".gzp" file and will add it to source code
     * unserialize - will extract file and will return array
     *
     * @copyright fire1
     *
     * @example usr('usr_data_1', array( 'my', 'data', 'is', 'here!' ) ); /-> usr('usr_data_1','unserialize');
     *
     * @param mixed $file return data or false
     * @param mixed $type type to put or type to execute
     * @return mixed  data array / require / false
     */
    function usr($name, $type = "") {
        self::kernel_count_memory(__FUNCTION__);

        if (empty($name)) {
            return self::dir() . DIRECTORY_SEPARATOR . self::$KERNEL_PROP_MAIN['dollop.usr'] . DIRECTORY_SEPARATOR;
        } else {
            switch ($type) {
                case 'require':
                    $file = self::usr() . ".$name.gzp";
                    if (file_exists($file)) {
                        $temp = tmpfile();
                        file_put_contents($temp, rgzp(self::usr(), $name));
                        require_once ($temp);
                    } else
                        return false;;
                    break;
                case 'unserialize':
                    return unserialize(rgzp(self::usr(), $name));
                    break;
                DEFAULT:
                    self::wgzp(self::usr(), $name, serialize($type));
                    break;
            }
        }
    }

    /**
     * bin data in system
     *
     * In case of all parameters empty will return bin folder
     * Create file and return data or require it
     *
     * Valid types are require, unserialize
     *
     * require - will extract ".gzp" file and will add it to source code
     * unserialize - will extract file and will return array
     *
     * @example bin('usr_data_1', array( 'my', 'data', 'is', 'here!' ) );  /->  bin('usr_data_1','unserialize');
     *
     * @copyright fire1
     * @param mixed $file return data or false
     * @param mixed $type type to put or type to execute
     * @return mixed  data array / require /false
     */
    protected function bin($name = "", $type = "") {

        self::kernel_count_memory(__FUNCTION__);
        if (empty($name)) {
            return self::dir() . DIRECTORY_SEPARATOR . self::$KERNEL_PROP_MAIN['dollop.bin'] . DIRECTORY_SEPARATOR;
        } else {
            switch ($type) {
                case 'require':
                    $file = self::bin() . ".$name.gzp";
                    if (file_exists($file)) {
                        $temp = tmpfile();
                        file_put_contents($temp, rgzp(self::bin(), $name));
                        require_once ($temp);
                    } else
                        return false;;
                    break;
                case 'unserialize':
                    return unserialize(rgzp(self::bin(), $name));
                    break;
                DEFAULT:
                    self::wgzp(self::bin(), $name, serialize($type));
                    break;
            }
        }
    }

    /**
     * cache some data
     *
     * configured by kernel.prop
     * @copyright fire1
     * @param string $name of file
     * @param mixed $put  data for file or hours for validing this file
     * @return content of file or false
     */
    public function cache($name, $put = "") {

        self::kernel_count_memory(__FUNCTION__);
        $dir = ROOT . TRUNK . trunk_temp() . DIRECTORY_SEPARATOR;
        if (empty($put) OR is_numeric($put)) {
            if (empty($put) OR isset(self::$KERNEL_PROP_MAIN['kernel.cacheFTime'])) {
                $hour = self::$KERNEL_PROP_MAIN['kernel.cacheTime'];
            } else {
                $hour = $put;
            }
            if (!file_exists($dir . $name) OR @filemtime($dir . $name) > (time() + $hour * 3600)) {
                return false;
            } else {
                return file_get_contents($dir . $name);
            }
        } else {
            file_put_contents($dir . $name, $put);
        }
    }

    /**
     * Check inmortant directories
     */
    private static function do_work_directories() {

        if (!defined("TRUNK_WAY")) {
            $trunk_d = constant("ROOT") . constant("TRUNK") . trunk_temp();

            if (!is_dir($trunk_d)) {
                if (!(bool) @readlink($trunk_d)) {
                    mkdir($trunk_d, 0700, true);
                }
            }
            define("TRUNK_WAY", $trunk_d);
        } else {
            $trunk_d = constant("TRUNK_WAY");
        }
        if (!defined("SESSI_WAY")) {
            $sessi_d = rtrim(constant("ROOT") . constant("SSSDR"),DIRECTORY_SEPARATOR);

            if (!is_dir($sessi_d)) {
                if (!(bool) @readlink($sessi_d)) {
                    mkdir($sessi_d, 0700, true);
                }
            }
            define("SESSI_WAY", $sessi_d);
        } else {
            $sessi_d = constant("SESSI_WAY");
        }

        return array("trunk" => $trunk_d, "session" => $sessi_d);
    }

    /**
     * Trunk some data for eval or serialize
     *
     * for  sql selected conf data
     * configured by kernel.prop
     * @copyright fire1
     *
     * @example trunkit([my functionality name],[my data],__FILE__)
     *
     * @param mixed $name of section
     * @param string $put  empty / data or time
     * @param string $FILE  __FILE__
     * @return init and eval content data
     */
    protected static function trunkit($name, $put = "", $FILE = "") {

        self::kernel_count_memory(__FUNCTION__);
        // here must be changed with hash function
        $mCrYptKey = crc32_(constant('HEX') . $name);
        if (!self::$KERNEL_SAVEMODE) {
            $type = $name;
            if (empty(self::$KERNEL_PROP_MAIN['kernel.trunkExt'])) {
                $ext = self::$KERNEL_PROP_EXTENSIONS['EXC'];
            } else {
                $ext = self::$KERNEL_PROP_MAIN['kernel.trunkExt'];
            }
            // Disable the hKey for system cache
            //$filenameKey = self::hkey($name, basename($FILE));
            $filenameKey = md5($name . self::$CONFIGURATION['SPR'] . self::$CONFIGURATION['KEY']);
            $name = $filenameKey . '-{FILE_HASHE}-' . self::$KERNEL_PROP_MAIN['kernel.trunkExt'];
            if (defined("TRUNK")) {
                $d = self::do_work_directories();
                $dir = $d['trunk'] . DIRECTORY_SEPARATOR;
            }
            // Check time or Unlink command
            if (empty($put) OR is_numeric($put)) {
                $unlink_this = false;
                if (empty($put)) {
                    $hour = self::$KERNEL_PROP_MAIN['kernel.trunkTime'];
                } else {
                    // If is negative unlink the file!
                    if ($put < 0) {
                        $unlink_this = true;
                    } else {
                        // Else use time limit
                        $hour = $put;
                    }
                }
                $searchStr = str_replace("{FILE_HASHE}", "*", $dir . $name);
                $FolderFiles = glob($dir . $filenameKey . "*" . $ext);
                if (is_array($FolderFiles) AND !empty($FolderFiles[0])) {
                    $FolderFiles = $FolderFiles[0];
                } else {
                    self::ERROR('<b>CAN NOT</b> locate files for trunkit for:' . " <b>$type</b> ", 'file', $filenameKey);
                    $FolderFiles = @$FolderFiles[0];
                }
                $srchrepl = explode('{FILE_HASHE}', $dir . $name);
                $hasher1 = str_replace($srchrepl, "", $FolderFiles);
                $hasher2 = @hash_file(self::$KERNEL_PROP_MAIN['kernel.trunkHash'], $FolderFiles); // return error
                if ($hasher1 == $hasher2) {
                    $file_my = $FolderFiles;
                }

                //debuger data
                if (empty($file_my)) {
                    self::ERROR('trunkit error: Can not load the trunk', 'file', $dir . $name);
                }
                // Clear trunk File!
                if ($unlink_this === true) {
                    self::trunk_flush();
                }
                if ((bool) self::$KERNEL_PROP_MAIN['kernel.emptying.trunk'] == true) {
                    if ((bool) self::$KERNEL_REBIRTH == true && (bool) self::$CONFIGURATION['trunk.disable.cleaning'] === false) {
                        foreach (glob(constant("ROOT") . constant("TRUNK") . "{$foldername}/*" . self::$KERNEL_PROP_MAIN['kernel.trunkExt']) as $un) {
                            @unlink($un);
                        }
                    }
                }
                if (!file_exists($file_my) OR @filemtime($file_my) > (time() + $hour * 3600)) {
                    @unlink($file_my);
                    return false;
                } else {
                    $output = file_get_contents($file_my);
                    $output = self::deCrypt($output, $mCrYptKey);
                    $case = strtolower(substr($type, strrpos($type, '.') + 1));
                    switch ($case) {
                        case "serialize":
                            return unserialize($output);
                            break;
                        case "eval":
                            if (self::$KERNEL_PROP_MAIN['kernel.trunk64']) {
                                $output = base64_decode($output);
                            }
                            eval($output);
                            break;
                        DEFAULT:
                            if (self::$KERNEL_PROP_MAIN['kernel.trunk64']) {
                                $output = base64_decode($output);
                            }
                            eval($output);
                            break;
                    }
                    return true;
                }
            } else {
                $case = strtolower(substr($type, strrpos($type, '.') + 1));
                switch ($case) {
                    case "serialize":
                        $put = serialize($put);
                        break;
                    default:
                        if (self::$KERNEL_PROP_MAIN['kernel.trunk64']) {
                            $put = base64_encode($put);
                        }
                        break;
                }
                //echo $mCrYptKey;
                if (!empty($mCrYptKey) && !empty($put)) {
                    @file_put_contents($dir . $name, self::enCrypt($put, $mCrYptKey));
                    $hash_file = @hash_file(self::$KERNEL_PROP_MAIN['kernel.trunkHash'], $dir . $name);
                    $newname = str_replace('{FILE_HASHE}', $hash_file, $dir . $name);
                    @rename($dir . $name, $newname);
                    return false;
                }
            }
            return false;
        }
    }

    /**
     * Clear the Dollop trunk
     */
    private function trunk_flush() {
        $foldername = ROOT . TRUNK . trunk_temp();

        exec("rm {$foldername}/* > /dev/null 2>/dev/null & ");
    }

    /**
     * Handle keys for trunk data
     *
     * @param mixed $name
     * @param mixed $destination
     * @return string
     */
    private function hkey($name, $destination = "") {

        if (!self::$KERNEL_SAVEMODE) {
            $hex = md5($name . "+" . self::$KERNEL_PROP_MAIN['kernel.trunkKey']);
            if (!empty($destination)) {
                $dir = constant('HKEYS') . md5($destination);
            } else {
                $dir = constant('HKEYS') . $hex;
            }
            if (!is_dir(ROOT . $dir)) {
                mkdir(ROOT . $dir, 0700, true);
            }
            $dir = $dir . DIRECTORY_SEPARATOR;
            if (defined("HEX")) {
                $hex = md5($hex . "-" . @constant("HEX"));
            }
            if (!file_exists($dir . $hex)) {
                $new_key = md5(time() . "-" . $hex . "*" . rand(111, 999));
                file_put_contents(ROOT . $dir . $hex, $new_key);
                return $new_key;
            } else {
                $content = @file_get_contents($dir . $hex);
                if ($content) {
                    return $content;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Cache dir for kernel
     * configured by kernel.prop
     * @return string with cache dir
     */
    private function chdr() {
        $pathtree = explode(DIRECTORY_SEPARATOR, self::dir());
        $dirs = count($pathtree);
        foreach ($pathtree as $dir) {
            $i++;
            if ($dirs > $i AND !empty($dirs)) {

            }
        }
        if (!defined("CACHE")) {
            $cache = "cache";
        } else {
            $cache = constant("CACHE");
        }
        $cacheDir = $path . $cache;
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        return $cacheDir . DIRECTORY_SEPARATOR;
    }

    /**
     * Optimazi cache
     *
     */
    private function opch($stat = null) {

        if (self::SAVEMODE() === true)
            return false;
        if (!(bool) self::$KERNEL_PROP_MAIN['kernel.opty'])
            return false;
        //
        // Folders for optymize
        $folders = self::do_work_directories();
        $trnk = $folders['trunk'];
        $sssn = $folders['session'];
        switch ($stat) {
            default:
                if (is_readable(self::$KERNEL_PROP_MAIN['kernel.opty.cache']) && is_writable(self::$KERNEL_PROP_MAIN['kernel.opty.cache'])) {

                    if (!(bool) @readlink($trnk) || !(bool) @readlink($sssn)) {
                        if (is_dir($trnk) OR is_dir($sssn)) {
                            if (is_dir($trnk)) {
                                @self::rrmdir($trnk . DIRECTORY_SEPARATOR);
                            }
                            if (is_dir($sssn)) {
                                @self::rrmdir($sssn . DIRECTORY_SEPARATOR);
                            }
                            //
                            // Build a trunk link
                            if (!(bool) @readlink($trnk)) {
                                @mkdir(self::$KERNEL_PROP_MAIN['kernel.opty.cache'] . DIRECTORY_SEPARATOR . "trunk-" . crc32_(HOST));
                                symlink(self::$KERNEL_PROP_MAIN['kernel.opty.cache'] . DIRECTORY_SEPARATOR . "trunk-" . crc32_(HOST), $trnk . "");
                            }
                            //
                            // Build a session link
                            if (!(bool) @readlink($sssn)) {
                                @mkdir(self::$KERNEL_PROP_MAIN['kernel.opty.cache'] . DIRECTORY_SEPARATOR . "session-" . crc32_(HOST));
                                symlink(self::$KERNEL_PROP_MAIN['kernel.opty.cache'] . DIRECTORY_SEPARATOR . "session-" . crc32_(HOST), $sssn);
                            }
                            return true;
                        }
                    }
                }
                break;

            case "clear":
                if (is_link($trnk) || is_link($sssn)) {
                    @unlink($trnk);
                    @unlink($sssn);
                }
                break;
        }
    }

    /**
     * Recursively remove a directory
     *
     * @param string $dir
     *
     */
    private function rrmdir($dir) {
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file))
                rrmdir($file);
            else
                unlink($file);
        }
        rmdir($dir);
    }

    /**
     * Read compressed files
     *
     * Read .gzp files and returns data uncompressed
     * @param mixed $d directory
     * @param mixed $f file name
     * @return string
     */
    private function rgzp($d, $f) {
        $file = $d . ".$f.gzp";
        if (file_exists($file) and $fd = @fopen($file, 'r', true)) {
            if ($buf = fread($fd, filesize($file))) {
                return gzuncompress($buf);
            } else {
                return false;
            }
        } else
            return false;
    }

    /**
     * Write compressed files
     *
     * Write .gzp files and compress data
     * @param mixed $d directory
     * @param mixed $f file name
     * @param mixed $i compress level
     */
    private function wgzp($d, $f, $i = 9) {
        if (empty($d) OR empty($f) OR empty($i))
            return false;;
        $tempnam = tmpfile();
        $fd = fopen($tempnam, 'w');
        $param = array('blocks' => 9, 'work' => 0);
        $filterArray = array("\n", "\t", "\r", "\0", "\x0B");
        fwrite($fd, gzcompress(str_replace($filterArray, "", $insurt), $i));
        fclose($fd);
        rename($tempnam, $d . ".$f.gzp");
        chmod($d . ".$f.gzp", 0600);
    }

    /**
     * URL query lock id
     *
     * Lock query "id" string to be numericl
     */
    private function id_is_numericl() {
        if (!empty($_GET['id'])) {
            If (!is_numeric($_GET['id']) && $_GET['id'] <= 0) {
                @mysql_close();
                exit("<h1>Access denied to fire1 dollop:</h1> <h2> ERROR in URL address. <br>

                        Report: " . link_report('807', $fire1_) . " </h2> <hr>Please set specify only the address of website. <!-- Error in \"URL\" address! -->");
            }
        }
    }

    /**
     * Setup security type
     *
     * This function set security level of system.
     * configured by kernel.prop
     */
    private function SetSecurityType() {

        switch (self::$KERNEL_PROP_MAIN['SetSecurityType']) {
            case '0':
                break;
            case 'advanced':
                exit("inside advanced Security!!!!");
                require_once (self::lib() . 'adv.security.class.php');
                break;
            case 'another':
                require_once (self::$KERNEL_PROP_MAIN['AnotherSecurity']);
                break;
            default:
                require_once (self::lib() . "def.security.class.php");
                break;
        }
    }

    /**
     * Proxy Allow
     *
     * Block or Allow proxy users
     * configured by kernel.prop
     */
    private function ProxyAllow() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) || ($_SERVER['HTTP_USER_AGENT'] == '') || (@$_SERVER['HTTP_VIA'] != '')) {
            @mysql_close();
            exit("<h1>Access denied to fire1 dollop: </h1> <h2> ERROR report: " . link_report('809', $fire1_) . " </h2><hr> Website is locked for proxy users.<!-- Website is locked for proxy -->");
        }
    }

    /**
     * Register globals
     *
     * disable or enable it
     * configured by kernel.prop
     */
    private function RegisterGlobals() {

        if (self::$KERNEL_PROP_MAIN['RegisterGlobals']) {
            while (list($global) = each($GLOBALS)) {
                if (!preg_match('/^(_POST|_GET|_COOKIE|_SERVER|_FILES|GLOBALS|HTTP.*|_REQUEST|retrieve_prefs|eplug_admin|eTimingStart)|oblev_.*$/', $global)) {

                }
            }
            if (self::$KERNEL_PROP_MAIN['UnsetRegisterGlobals']) {
                unset($global);
            }
        }
    }

    /**
     *  HTTPS Secure URL
     *
     * Check for valid https:// and will redirects to it
     * configured by kernel.prop
     */
    private function HTTPSecure() {
        $HTTPS = $_SERVER['HTTPS'];
        $REQUEST_URI = $_SERVER['REQUEST_URI'];
        if (!isset($HTTPS)) {
            $str_fileRequest = $REQUEST_URI;
            $parseUrl = parse_url(trim($Address));
            $hosted_site = trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2)));
            $str_ReLocation = 'https://' . $hosted_site . '/';
            header_location($str_ReLocation);
        }
    }

    /**
     * Starting session in kernel
     *
     */
    public function dollop_session_start() {

        if (!(bool) self::$KERNEL_PROP_MAIN['kernel.session.start'])
            return false;;
        if (!isset($_SESSION)) {
            session_start();
            if (!isset($_SESSION[self::$KERNEL_PROP_MAIN['kernel.session.strCheckUA']]) || !isset($_SESSION[self::$KERNEL_PROP_MAIN['kernel.session.strCheckIP']]))
                $_SESSION[self::$KERNEL_PROP_MAIN['kernel.session.strCheckUA']] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION[self::$KERNEL_PROP_MAIN['kernel.session.strCheckIP']] = $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Check Up sessions for injected ID
     * @package kernel
     */
    private function protect_session() {

        if (!(bool) self::$KERNEL_PROP_MAIN['kernel.session.check'])
            return false;;
        if ($_SESSION[self::$KERNEL_PROP_MAIN['kernel.session.strCheckUA']] != $_SERVER['HTTP_USER_AGENT'] || $_SESSION[self::$KERNEL_PROP_MAIN['kernel.session.strCheckIP']] != $_SERVER['REMOTE_ADDR']) {
            kernel::ERROR('Session protection warning!', 'php', "Protection for session give back warning!");
            @define("WEBSITE_RESPONSES", 205);
            theme::content(dp_show_responses(constant("WEBSITE_RESPONSES"), "Session protection give back unexpected session values!"));
            return false;
        }
    }

    /**
     * Detect mobile device
     *
     * If user use mobile device for browsing the website, the system will use mobile design.
     * configured by kernel.prop
     */
    protected function MobileDevice() {
        // glueCode
        // self::glueCode(__FUNCTION__);

        if (file_exists(self::lib() . self::$KERNEL_PROP_MAIN['MobileScript']) && self::$KERNEL_PROP_MAIN['MobileDevice']) {
            require_once (self::lib() . self::$KERNEL_PROP_MAIN['MobileScript']);
            $detect = new Mobile_Detect();
            if ($detect->isMobile()) {
                define('MOBILE_DEVICE', true);
                return true;
            }
        }
    }

    /**
     * Create users constant in kernel
     *
     */
    public function users_const_data() {

        $etc_folder = self::$KERNEL_PROP_MAIN['dollop.etc'];
        $constants_folder = self::$KERNEL_PROP_MAIN['kernel.users.depend.folder'];
        $constants_file = self::$KERNEL_PROP_MAIN['kernel.users.depend.constants'];
        $prop = ROOT . SRCDR . $etc_folder . DIRECTORY_SEPARATOR . $constants_folder . DIRECTORY_SEPARATOR . $constants_file;
        if (!file_exists($prop)) {
            self::ERROR('ERROR ETC data for users constants do not exists', 'php', "Function: kernel::users_const_data\n File:{$prop}\n Line:" . __LINE__);
            return false;
            ;
        }
        $const = self::loadprop($prop, 1);
        eval('$prefix = constant("PREFIX");');
        foreach ($const as $key => $value) {
            $const_key = strtoupper(str_replace(".", "_", $key));
            if (!defined($const_key)) {
                define($const_key, str_replace("{PREFIX}", $prefix, $value));
            }
        }
    }

    /**
     * Fix sessions on seme servers
     *
     */
    public function session_fix() {
        global $CONFIGURATION;
        // check session is active
        if (empty($_COOKIE[$CONFIGURATION['COOKIE_SESSION']]) && isset($_COOKIE[ini_get('session.name')])) {
            setcookie(ini_get('session.name'), "", 0, DIRECTORY_SEPARATOR);
            // refresh
            header_location(null, true);
            return false;
        } elseIf (!is_array($HTTP_COOKIE_VARS) && empty($_COOKIE[$CONFIGURATION['COOKIE_SESSION']]))
            self::SESSION_SET('', '');
        if (!defined("COOKIE_SESSION")) {
            define("COOKIE_SESSION", $_COOKIE[$CONFIGURATION['COOKIE_SESSION']]);
        }
    }

    /**
     * Hash some values to create session from username
     *
     * @param mixed $username
     * @return string
     */
    protected function username_session($username) {
        return md5(md5(HEX) . ' \/ ' . $arr_sess['name']);
    }

    /**
     * This function create users sessions and remember the user data for the maximum
     * cookie time  of 30 days, by default set-up on all  public servers.
     *
     * The prop string in kernel prop ’session.gc_maxlifetime’ can give more time to cookies.
     * @package kernel/users
     * @todo more time for storing users data
     */
    private function users_session() {

        // Fill  config to short varibles
        $basic_key = self::$KERNEL_PROP_MAIN['kernel.users.session.remember'];
        $uname_key = self::$KERNEL_PROP_MAIN['kernel.users.session.username'];
        $upass_key = self::$KERNEL_PROP_MAIN['kernel.users.session.password'];
        $cookieKey = self::$KERNEL_PROP_MAIN['kernel.users.session.cookie'];
        $remain_tm = self::$KERNEL_PROP_MAIN['kernel.users.session.onremains'];
        $parsUrlHost = parse_url(constant("HOST"), PHP_URL_HOST);
        // Cookie for check and start redeclare dollop session
        $cookie_user_id = constant("USERS_COOKIE_UID");
        // Get the current Session Timeout Value
        $currentTimeoutInSecs = ini_get('session.gc_maxlifetime');
        // Set max time for cookie
        $sessionCookieExpireTime = self::$KERNEL_PROP_MAIN['CookieTime'];
        // Check if session is started
        if (isset($_COOKIE[ini_get('session.name')]) && empty($_SESSION[$uname_key])) {
            if (!(bool) session_id()) {
                session_start();
            }
            if (isset($_SESSION[$basic_key])) {
                $sessionCookieExpireTime = self::$KERNEL_PROP_MAIN['MaxCookieTime'];
                self::$KERNEL_PROP_MAIN['CookieTime'] = self::$KERNEL_PROP_MAIN['MaxCookieTime'];
            }
            if (isset($_SESSION[$basic_key]) && !isset($_COOKIE[$cookieKey])) {
                // Set dollop cookie to resolve time
                setcookie($cookieKey, time() + $sessionCookieExpireTime, time() + $sessionCookieExpireTime, DIRECTORY_SEPARATOR, $parsUrlHost, check_secure_http('https'), check_secure_http('http'));
                @session_set_cookie_params($sessionCookieExpireTime);
                setcookie(ini_get('session.name'), $_COOKIE[ini_get('session.name')], time() + $sessionCookieExpireTime, DIRECTORY_SEPARATOR, $parsUrlHost, check_secure_http('https'), check_secure_http('http'));
                //rebiuld dollop cookie
                setcookie(self::CONFIGURATION('COOKIE_SESSION'), $_COOKIE[self::CONFIGURATION('COOKIE_SESSION')], time() + $sessionCookieExpireTime, "/", $parsUrlHost, check_secure_http('https'), check_secure_http('http'));
                // Re declare session varibles
                $_SESSION[$basic_key] = true;
                $_SESSION[$uname_key] = $_SESSION[$uname_key];
                $user_data = self::SESSION(self::username_session($_SESSION[$uname_key]));
                $_SESSION['checked'] = "checked=\"checked\" ";
            } elseIf (isset($_COOKIE[$cookieKey])) {
                // Check for rebuild
                $remains = time() - $_COOKIE[$cookieKey];
                if ($remains < $remain_tm) {
                    setcookie($cookieKey, time() + $sessionCookieExpireTime, time() + $sessionCookieExpireTime, DIRECTORY_SEPARATOR, $parsUrlHost, check_secure_http('https'), check_secure_http('http'));
                    setcookie(ini_get('session.name'), $_COOKIE[ini_get('session.name')], time() + $sessionCookieExpireTime, DIRECTORY_SEPARATOR, $parsUrlHost, check_secure_http('https'), check_secure_http('http'));
                }
            }
            if (!$_COOKIE[$cookie_user_id] && !empty($_SESSION[$uname_key])) {
                return array("user" => $_SESSION[$uname_key], "pass" => $user_data['pass']);
            } else
                return false;;
        }
    }

    /**
     * This function is main setup of Dollop user cookies and protection of cookies.
     * Also in this function execute function for users sessions.
     * It is higly recommended to let this function to create user cookies data.
     *
     * In the case "$case" string have value to "set" will require data value
     * for username or user email. Sub string "$reload" gives opportunity
     * for reloading page in same request.
     *
     * In case of empty execution of values in this function will be executed process
     *  for attestation of all user data in cookies.
     *
     *
     * @package kernel/users
     * @param mixed $case        // DEFAULT to check it or add
     * @param mixed $user_name   // user name or email
     */
    public function ctrl_cookie($case = null, $user_name = null, $reload = false) {
        // glueCode
        self::glueCode(__FUNCTION__);
        kernel::protect_session();

        $basic_key = self::$KERNEL_PROP_MAIN['kernel.users.session.remember'];
        $privilege_file = self::$KERNEL_PROP_MAIN['kernel.users.depend.privilege'];
        $constants_folder = self::$KERNEL_PROP_MAIN['kernel.users.depend.folder'];
        $etc_folder = self::$KERNEL_PROP_MAIN['dollop.etc'];
        // Get hex
        $hex = constant("HEX");
        /// sQL
        $sql_user_table = constant("USERS_SQLTBL_MAIN");
        $sql_user_table_row_uid = constant("USERS_SQLTBL_COL_UID");
        $sql_user_table_row_uname = constant("USERS_SQLTBL_COL_UNAME");
        $sql_user_table_row_upass = constant("USERS_SQLTBL_COL_UPASS");
        $sql_user_table_row_umail = constant("USERS_SQLTBL_COL_UMAIL");
        $sql_user_table_row_upriv = constant("USERS_SQLTBL_COL_UPRIV");
        /// COOKIE
        $cookie_user_name = constant("USERS_COOKIE_UNAME");
        $cookie_user_id = constant("USERS_COOKIE_UID");
        $cookie_user_ses = constant("USERS_COOKIE_USESS");
        $conf = self::CONFIGURATION();
        if (!is_array($conf)) {
            self::ERROR("[!] Cannot extraction website configuration! ", "php", "error is in 'kernel::cpanel_cookie' function! \n Line:" . __LINE__);
        }
        $mktime = sprintf("%u", crc32('@' . time()));
        ////////////////////////////////////////////////////////////////
        // After session is checked and it is set-up to remember    ////
        // re-declare the dollop file session                       ////
        if (!$user_name && ($user_sesd = self::users_session())) { ////
            $case = 'set';
            $reload = true; ////
            $user_name = addslashes($user_sesd['user']); ////
            $sql_password = addslashes($user_sesd['pass']); ////
            $sql_addrelogin = ////
                    " AND `{$sql_user_table_row_upass}`='{$sql_password}' "; ////
        } ////
        ////////////////////////////////////////////////////////////////
        // START SWITHING THE CLASS METHOD
        switch ($case) {
            // set data to cookie

            case "set":
                //
                // Check For session
                if (!(bool) session_id()) {
                    session_start();
                }
                //
                // View to remember user...
                if (defined(strtoupper($basic_key)) OR ($_SESSION[$basic_key] === true)) {
                    $CkTime = self::$KERNEL_PROP_MAIN['MaxCookieTime'];
                } else {
                    $CkTime = self::$KERNEL_PROP_MAIN['CookieTime'];
                }
                //
                // SQL Query
                $sql = "SELECT
                    `$sql_user_table_row_uid`,
                    `$sql_user_table_row_uname`,
                    `$sql_user_table_row_upass`,
                    `$sql_user_table_row_umail`,
                    `$sql_user_table_row_upriv`
                    FROM `$sql_user_table`
                    WHERE  `$sql_user_table_row_uname`='$user_name' {$sql_addrelogin} OR
                    `$sql_user_table_row_umail`='$user_name' {$sql_addrelogin} LIMIT 1; ";
                global $db;
                $sql_request = mysql_query($sql, $db) or die(mysql_error());
                if ($row = mysql_fetch_array($sql_request)) {
                    $data = $row[$sql_user_table_row_uname] . $hex . $mktime . $conf['KEY'] . $row[$sql_user_table_row_uid] . $_COOKIE[$conf['COOKIE_SESSION']];
                    $unic_value = md5($data) . $conf['SPR'] . $mktime;
                    // set cookie
                    setcookie($cookie_user_name, $row[$sql_user_table_row_uname], time() + $CkTime, DIRECTORY_SEPARATOR, parse_url(constant("HOST"), PHP_URL_HOST), check_secure_http('https'), check_secure_http('http'));
                    setcookie($cookie_user_id, $row[$sql_user_table_row_uid], time() + $CkTime, DIRECTORY_SEPARATOR, parse_url(constant("HOST"), PHP_URL_HOST), check_secure_http('https'), check_secure_http('http'));
                    setcookie($cookie_user_ses, $unic_value, time() + $CkTime, "/", parse_url(constant("HOST"), PHP_URL_HOST), check_secure_http('https'), check_secure_http('http'));
                    $arr_sess['name'] = $row[$sql_user_table_row_uname];
                    $arr_sess['pass'] = $row[$sql_user_table_row_upass];
                    $arr_sess['idin'] = $row[$sql_user_table_row_uid];
                    $arr_sess['priv'] = $row[$sql_user_table_row_upriv];
                    $arr_sess['sess'] = $unic_value;
                    // set file session
                    self::SESSION_SET(self::username_session($arr_sess['name']), $arr_sess);
                }

                self::$KERNEL_REBIRTH = true;
                if ($reload) {
                    header_location();
                }
                break;
            DEFAULT:
                global $CONFIGURATION;
                //
                // To cill cookie must use max cookie time
                $CkTime = self::$KERNEL_PROP_MAIN['MaxCookieTime'];
                If (!$_COOKIE[$cookie_user_name] AND !$_COOKIE[$cookie_user_id] AND !$_COOKIE[$cookie_user_ses])
                    return false;;
                // stoping use of website cache
                // get time from cookie
                $catch_time = explode($conf['SPR'], str_replace("--", "-", $_COOKIE[$cookie_user_ses]));
                $mktime = $catch_time[1];
                // get data from trunk
                $arr_sess = self::SESSION(self::username_session($_COOKIE[$cookie_user_name]));
                $data = $arr_sess['name'] . HEX . $mktime . $conf['KEY'] . $arr_sess['idin'] . $_COOKIE[$conf['COOKIE_SESSION']];
                $unic_value = md5($data) . $conf['SPR'] . $mktime;
                $unic_value = str_replace("--", "-", $unic_value);
                $cookie_uname = $_COOKIE[$cookie_user_name];
                $cookie_uidin = $_COOKIE[$cookie_user_id];
                $cookie_usess = $_COOKIE[$cookie_user_ses];
                $dollopCookie = $CONFIGURATION['COOKIE_SESSION'];
                if ($cookie_uname != $arr_sess['name'] || $cookie_uidin != $arr_sess['idin'] || $cookie_usess != $arr_sess['sess'] || $unic_value != $cookie_usess) {
                    // delete cookie
                    unset_all_cookies(null);
                    // delete session
                    self::SESSION_CILL(self::username_session($row[$sql_user_table_row_uname]));
                    header_location();
                    return false;
                    ;
                } else {
                    $privilege = self::loadprop(ROOT . SRCDR . $etc_folder . DIRECTORY_SEPARATOR . $constants_folder . DIRECTORY_SEPARATOR . $privilege_file, 1);
                    global $USERS_PRIVILEGE;
                    $USERS_PRIVILEGE = $privilege;
                    define("USER_PRIV", $arr_sess['priv']);
                    define("USER_PRIV_NAME", $privilege['users.privilege'][$arr_sess['priv']]);
                    define("USER_NAME", $arr_sess['name']);
                    define("USER_IDIN", $arr_sess['idin']);
                    define("USER_ID", $arr_sess['idin']);
                    if ($privilege['users.cpanel'] <= $arr_sess['priv']) {
                        // manager
                        define("CPANEL", md5($privilege['users.privilege'] . "!" . constant("USER_NAME") . $_COOKIE[$conf['COOKIE_SESSION']] . $conf['KEY']));
                        define("CPANEL_MANAGER", $privilege['users.privilege'][$arr_sess['priv']]);
                        define("MANAGER_FLDR", self::$KERNEL_PROP_MAIN['dp.manager.folder']);
                    }
                    return true;
                    ;
                }
                break;
            // END SWITHING THE CLASS METHOD
        }
        if (!defined("USER_PRIV")) {
            define("USER_PRIV", "0");
        }
    }

    /**
     * shows cpanel
     *
     * [This function use obj $cpane]
     */
    public function manager_out() {
        global $cpanel;
        // glueCode
        self::glueCode(__FUNCTION__);
        // refresh after rebirth
        if (self::$KERNEL_REBIRTH) {
            // closed to fix
            //header_location(null,true);
        }
        if (defined("MOBILE_DEVICE"))
            return false;
        if (defined("CPANEL")) {
            $cpanel->output();
        }
    }

    /**
     * function that loads cpanel classes.
     * Classes setup can be controlled from the kernel prop.
     * It is required cpanel classes to be compatible with kernel
     * and other basic functionality of Dollop.
     *
     * Activation and deactivation of cpanel can be controlled from prop data.
     *  It is strongly recommended this option to be used only
     * of strongly arisen process problem of website.
     *
     * @package kernel.cpanel
     */
    private function cpanel() {

        switch (strtolower(self::$KERNEL_PROP_MAIN['dp.cpanel'])) {
            case '0':
                return false;
                break;
            case '1':
                if (defined("MOBILE_DEVICE"))
                    return false;
                self::classload(self::$KERNEL_PROP_MAIN['dp.cpanel.default']);
                break;
            case '2':
                if (defined("MOBILE_DEVICE"))
                    return false;
                self::classload(self::$KERNEL_PROP_MAIN['dp.cpanel.default']);
                break;
            DEFAULT:
                if (defined("MOBILE_DEVICE"))
                    return false;
                if (defined("CPANEL")) { ////////////////////////////////////////////////// NOT READY
                    self::classload(self::$KERNEL_PROP_MAIN['dp.cpanel']);
                }
                break;
        }
    }

    /**
     * Execute Cpanel
     * This function runs given class from function cpanel and
     * fill data errors for showing in bar.
     *
     * @package kernel.cpanel
     * @param mixed $error - content of errors
     */
    public function cpanel_execute() {
        if (defined("MOBILE_DEVICE"))
            return false;
        // glueCode
        self::glueCode(__FUNCTION__);
        global $cpanel;
        self::kernel_count_memory(__FUNCTION__);
        switch (self::$KERNEL_PROP_MAIN['dp.cpanel']) {
            case "1":
                self::cpanel();
                if (class_exists(self::$KERNEL_PROP_MAIN['dp.cpanel.default'])) {
                    $cpanel = new self::$KERNEL_PROP_MAIN['dp.cpanel.default'];
                    $cpanel->errors(self::ERROR('', 'output'));
                }
                break;
            case "2":
                if (defined("CPANEL")) {
                    self::kernel_status_mode('KERNEL_REBIRTH', 1);
                    self::cpanel();
                    if (class_exists(self::$KERNEL_PROP_MAIN['dp.cpanel.default'])) {
                        $cpanel = new self::$KERNEL_PROP_MAIN['dp.cpanel.default'];
                        $cpanel->errors(self::ERROR('', 'output'));
                    }
                } else {

                }
                break;
            DEFAULT:
                if (defined("CPANEL")) {
                    self::cpanel();
                    if (class_exists(self::$KERNEL_PROP_MAIN['dp.cpanel'])) {
                        $cpanel = new self::$KERNEL_PROP_MAIN['dp.cpanel'];
                        $cpanel->errors(self::ERROR('', 'output'));
                    }
                } else {

                }
                break;
        }
    }

    /**
     * Quick tag replace
     *
     * @param mixed $tags
     * @param mixed $content
     * @param mixed $template
     */
    private function template_exec($tags, $content, $template) {
        return str_replace($tags, $content, file_get_contents($template));
    }

    /**
     * Show messages from lan.prop sector repo.*
     *
     * @param mixed $repoName1   // title
     * @param mixed $repoName2   //body
     */
    public function show_report($repoName1, $repoName2) {
        global $language;
        if (empty($language)) {
            kernel::language();
            ;
        }
        $tags[0] = "{TITLE_REPORT}";
        $values[0] = $language['repo.' . $repoName1];
        $tags[1] = "{BODY_REPORT}";
        $values[1] = $language['repo.' . $repoName2];
        echo kernel::template_exec($tags, $values, kernel::base_tag_folder_filter(self::$KERNEL_PROP_MAIN['kernel.repo.html']));
        exit();
    }

    /**
     * This function control status modes, save mode and rebirth.
     * On status save mode for kernel, kernel uses basic
     * functionality own class variables.
     * On status rebirth mode for kernel, kernel rebiuld all
     * caches and erases all old trunk data.
     *
     *
     * @package kernel
     * @todo strong rebirth
     *
     * @param mixed $key
     * @param bool $status
     * @return mixed
     */
    protected function kernel_status_mode($key = null, $status = 0) {
        global $kernel_status_mode;
        $unic = $_COOKIE[ini_get('session.name')] . $_COOKIE[self::CONFIGURATION('COOKIE_SESSION')];
        $session = self::SESSION('kernel_status_mode_' . $unic);
        if (!$key || !!$status) {
            if ($session) {
                self::$KERNEL_REBIRTH = $session['KERNEL_REBIRTH'];
                self::$KERNEL_SAVEMODE = $session['KERNEL_SAVEMODE'];
            }
        }
        If (!empty($key) && !!$status && !empty($session[$key])) {
            return $session[$key];
        }
        If (!empty($key) && $status && ($session[$key] != $status)) {
            $session['KERNEL_REBIRTH'] = self::$KERNEL_REBIRTH;
            $session['KERNEL_SAVEMODE'] = self::$KERNEL_SAVEMODE;
            $session[$key] = $status;
            self::$KERNEL_REBIRTH = $session['KERNEL_REBIRTH'];
            self::$KERNEL_SAVEMODE = $session['KERNEL_SAVEMODE'];
            self::SESSION_SET('kernel_status_mode_' . $unic, $session);
        }
        $GLOBALS['kernel_status_mode'] = 'kernel::kernel_status_mode';
    }

    /**
     * This function holds basic website data:
     *  encryption keys, folders information for system and owner of website.
     *
     * @package kernel
     *
     * @copyright fire1
     * @param mixed $KEY of configuration
     * @return all configuration
     */
    public function CONFIGURATION($KEY = "") {
        kernel::loadprop();
        global $KERNEL_CONFIGURATION, $CONFIGURATION;
        self::kernel_count_memory(__FUNCTION__);
        if (defined('HEX')) {
            if (!is_array($CONFIGURATION)) {
                $ext = self::$KERNEL_PROP_DATA['MAIN']['kernel.trunkExt'];
                $src = file_get_contents(@constant('TRUNK') . md5(@constant('HEX')) . $ext);
                $denc = self::deCrypt($src, crc32(constant('HEX')));
                self::$CONFIGURATION = unserialize($denc);
                $GLOBALS['CONFIGURATION'] = self::$CONFIGURATION;
                foreach (self::$CONFIGURATION as $key => $value) {
                    $constant_name = str_replace(".", "_", $key);
                    if (is_scalar($value)) {
                        define(strtoupper($constant_name), $value);
                    }
                }
            } else {
                self::$CONFIGURATION = $CONFIGURATION;
            }
            if (empty($KEY)) {
                return self::$CONFIGURATION;
            } else {
                if (empty(self::$CONFIGURATION[$KEY])) {
                    return false;
                    ;
                } else {
                    return self::$CONFIGURATION[$KEY];
                    ;
                }
            }
        } else {
            if (!is_array(self::$CONFIGURATION)) {
                self::$CONFIGURATION = parse_ini_file(self::$KERNEL_PROP_DATA['MAIN']['dp.boot']);
                $this->BOOT = true;
                if (self::SESSION('installation_dollop') == false) {
                    self::SESSION_SET('installation_dollop', true);
                }
                self::dollop_installation();
            }
            if (empty(self::$CONFIGURATION[$KEY])) {
                return false;
                ;
            } else {
                return self::$CONFIGURATION[$KEY];
                ;
            }
        }
    }

    /**
     * SAVEMODE light
     *  This function controls light regimen of the system save mode.
     *  For switching to the light regimen "save mode" give value to function "true"
     *
     * @package kernel
     *
     * @copyright fire1
     * @param mixed $IS false / true
     * @return mode
     */
    public static function SAVEMODE($IS = "") {

        if (self::$KERNEL_SAVEMODE == false AND $IS == true) {
            self::$KERNEL_SAVEMODE = true;
        }
        return self::$KERNEL_SAVEMODE;
    }

    /**
     * System ERRORs
     *
     * This function creates monitoring of the main functionality in Dollop
     *
     * @uses kernel / theme
     *
     * @copyright fire1
     *
     * @example kernel::ERROR('O no... error','php','in line 123 my.php file');
     *
     * @param string $error description
     * @param string $case conf,file,sql,php,dbug
     * @param string $moreInfo is for more information that can help
     */
    public function ERROR($error, $case = "", $moreInfo = '') {
        // debug_backtrace(); to check all function
        global $ERRORS;
        $arr_ERRORS = "";
        if (!empty($moreInfo)) {
            $more = '[<span title="[More Info:' . $moreInfo . ']" > <u>more</u> </span>]<br /> ';
        }
        switch ($case) {
            case "php":
                $arr_ERRORS['php'][] = $error . $more;
                break;
            case "sql":
                $arr_ERRORS['sql'][] = $error . $more;
                if (self::$KERNEL_PROP_MAIN['sqlStopError']) {
                    @mysql_close($db);
                    exit($error . $more);
                }
                break;
            case "file":
                $arr_ERRORS['file'][] = $error . $more;
                break;
            case "conf":
                $this->ERRORS['conf'][] = $error . $more;
                break;
            case 'dbug':
                $arr_ERRORS['dbug'][] = $error . $more;
                break;
            DEFAULT:
                $arr_ERRORS['other'][] = $error . $more;
                break;
            case 'output':
                $err = $GLOBALS['ERRORS'];
                if (!is_array($err)) {
                    $errorend = '';
                } else {
                    $errorsend = $ERRORS;
                    /* fore to open system errors uncomment "print_r" */
                    //print_r($errorsend);
                    $b = 0;
                    $errorend = "<div style=''>";
                    foreach ($errorsend as $myError) {
                        foreach ($myError as $key => $err) {
                            if (!empty($err)) {
                                $err = str_replace("[!]", "<img src='" . HOST . "design/cpanel/icons/attention.png' alt='!' border='0' >", $err);
                                $errorend.= "<div style='padding:5px;margin:2px;'><b>{$key}</b>: {$err[0]} </div>";
                            }
                        }
                        $b++;
                    }
                    $errorend.= "</div>";
                }
                return array('content' => $errorend, 'array' => $b);
                break;
        }
        if (is_array($arr_ERRORS)) {
            $ERRORS[] = $arr_ERRORS;
        }
    }

    /**
     * Set Session
     * This function create system protected session
     *
     * @package kernel.sessions
     *
     * @copyright fire1
     * @param string $name of session
     * @param string $value of session
     * @param string $time of session (time() + $time)
     *
     */
    public function SESSION_SET($name, $value, $time = false) {
        global $CONFIGURATION;
        //
        // Fix installation Issues
        // When installing the dollop form using kernel class like real class
        // in other cases kernel class are only pack with function
        if (empty($CONFIGURATION)) {
            $GLOBALS['CONFIGURATION'] = self::$CONFIGURATION;
        }
        $mktime = round(time() * rand(1, 9), -3);
        $key = sha1($CONFIGURATION['HEX'] . '"' . $CONFIGURATION['COOKIE_SESSION'] . "'})" . $mktime) . $CONFIGURATION['SPR'] . $mktime;
        if (!$_COOKIE[$CONFIGURATION['COOKIE_SESSION']]) {
            if (empty(self::$KERNEL_PROP_MAIN['CookieTime'])) {
                self::$KERNEL_PROP_MAIN['CookieTime'] = 7200;
            }
            setcookie($CONFIGURATION['COOKIE_SESSION'], $key, time() + self::$KERNEL_PROP_MAIN['CookieTime'], DIRECTORY_SEPARATOR, parse_url(constant("HOST"), PHP_URL_HOST), check_secure_http('https'), check_secure_http('http'));
            self::exec_after_mysqlConnect('add', 'header_location();');
        } else {
            $key_parts = explode($CONFIGURATION['SPR'], $_COOKIE[$CONFIGURATION['COOKIE_SESSION']]);
            $key = sha1($CONFIGURATION['HEX'] . '"' . $CONFIGURATION['COOKIE_SESSION'] . "'})" . end($key_parts)) . $CONFIGURATION['SPR'] . end($key_parts);
        }
        $file = ROOT . $CONFIGURATION['session_dir'] . DIRECTORY_SEPARATOR . strtoupper($name) . $CONFIGURATION['SPR'] . sha1($key . $name);
        if (!is_dir($CONFIGURATION['session_dir'])) {
            if (!(bool) is_link($CONFIGURATION['session_dir']) && self::SAVEMODE() !== true) {

                mkdir(ROOT . $CONFIGURATION['session_dir'], 0700, true);
            }
        }
        $svalue = serialize($value);
        // here must reate change of hash mode in mcrypt
        $evalue = self::enCrypt($svalue, crc32($CONFIGURATION['HEX'] . $name));
        if (file_put_contents($file, $evalue, LOCK_EX) !== FALSE) {
            //
            // Set-up the explire time
            if ((bool) $time) {
                //
                // This is the touch time, we'll set it with $time string.
                $time = time() + (int) $time;
                if (!@touch($file, $time)) {
                    return true;
                }
            }
            return true;
        } else if (self::SAVEMODE() !== true) {
            //
            // If dollop read/write in
            self::opch("clear");
        }
    }

    /**
     *  Session value
     * This function returns the already existing session value.
     *
     * @package kernel.sessions
     *
     * @copyright fire1
     * @param string $name  of session
     * @return string value or false
     */
    public function SESSION($name) {
        global $KERNEL_CONFIGURATION;
        self::kernel_count_memory(__FUNCTION__);
        $key_parts = explode(self::$CONFIGURATION['SPR'], $_COOKIE[self::$CONFIGURATION['COOKIE_SESSION']]);
        $key = sha1(self::$CONFIGURATION['HEX'] . '"' . self::$CONFIGURATION['COOKIE_SESSION'] . "'})" . end($key_parts)) . self::$CONFIGURATION['SPR'] . end($key_parts);
        $file = self::$CONFIGURATION['session_dir'] . DIRECTORY_SEPARATOR . strtoupper($name) . self::$CONFIGURATION['SPR'] . sha1($key . $name);
        if (file_exists($file)) {
            $value = file_get_contents($file);
            // here must reate change of hash mode in mcrypt
            $value = self::deCrypt($value, crc32(self::$CONFIGURATION['HEX'] . $name));
            return @unserialize($value);
        } else {
            return false;
        }
    }

    /**
     * Unset session
     * Erase already existing session.
     *
     * @package kernel.sessions
     *
     * @copyright fire1
     * @param string $name  of session
     */
    public function SESSION_CILL($name) {
        global $KERNEL_CONFIGURATION;
        $key_parts = explode(self::$CONFIGURATION['SPR'], $_COOKIE[self::$CONFIGURATION['COOKIE_SESSION']]);
        $key = sha1(self::$CONFIGURATION['HEX'] . '"' . self::$CONFIGURATION['COOKIE_SESSION'] . "'})" . $key_parts[2]) . self::$CONFIGURATION['SPR'] . $key_parts[2];
        $file = self::$CONFIGURATION['session_dir'] . DIRECTORY_SEPARATOR . strtoupper($name) . self::$CONFIGURATION['SPR'] . sha1($key . $name);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Create new configuration file
     *
     * @copyright fire1
     * @param string $file          [destionation of file]
     * @param boolean $privilege    [privilege (0,1,2>>none,normal,high)]
     * @param boolean $sections     [sectors in readed file]
     * @param boolean $unlink       [delete file after add it]
     * @param __METHOD__ $method    [method for use (kernel::etc adds to etc)]
     * @param array $reset          [It will reinsert array from this varible]
     */
    protected function kernel_new_configuration($file, $privilege = 0, $sections = 0, $unlink = 0, $method = null, $reset = false) {

        if (empty($file) && $privilege >= self::$KERNEL_PROP_MAIN['kernel.privilege.normal']) {
            self::ERROR("New configuration called, but varible destination for file is empty!", 'conf', "file: <b>$file</b>");
            if ($privilege == self::$KERNEL_PROP_MAIN['kernel.privilege.high']) {
                exit(print_r($GLOBALS['ERRORS']));
            }
            return false;
        }
        if (is_array($reset)) {
            $var = $reset;
        } else {
            $var = parse_ini_file($file, $sections);
        }
        if ($unlink == 1) {
            @unlink($file);
        }
        $path_external = dirname($file);
        $path_external = str_replace(ROOT, "", $path_external);
        if (!empty($method) && $method == "kernel::etc") {
            $dirtree = SRCDR . "etc" . DIRECTORY_SEPARATOR . $path_external;
            $etc = true;
        } else {
            $etc = false;
            $dirtree = self::CONFIGURATION('trunk') . DIRECTORY_SEPARATOR . $path_external;
        }
        self::mkdirTree($dirtree);
        $puFile = $dirtree . DIRECTORY_SEPARATOR . self::kernel_file_configuration($file);
        if ($etc == false) {
            $put_var = self::enCrypt(serialize($var), crc32_(self::kernel_file_configuration($file) . HEX));
        } else {
            $put_var = serialize($var);
        }
        file_put_contents($puFile, $put_var);
        if (file_exists($puFile)) {
            return $var;
        } else
            return false;;
    }

    /**
     *  make directories from path
     *
     * @param mixed $dirs path
     */
    protected function mkdirTree($dirs, $rights = 0755) {
        if (!is_dir($dirs)) {
            $dirs = str_replace(ROOT, "", $dirs);
            @mkdir(ROOT . $dirs, $rights, true);
        }
    }

    /**
     * Load configuration from url tree
     *
     * @copyright fire1
     * @param string $file empty or put full path and file
     * @param boolean $privilege
     * @param boolean $sections
     * @return init or configuration;
     */
    protected function kernel_load_configuration($file = "", $privilege = 0, $sections = 0, $method = null) {
        $path_external = str_replace(ROOT, "", dirname($file));
        if (!empty($method) && $method == "kernel::etc") {
            $dirtree = SRCDR . "etc" . DIRECTORY_SEPARATOR . $path_external;
            $etc = true;
        } else {
            $etc = false;
            $dirtree = self::CONFIGURATION('trunk') . DIRECTORY_SEPARATOR . $path_external;
        }
        $geFile = $dirtree . DIRECTORY_SEPARATOR . self::kernel_file_configuration($file);
        if (!file_exists(ROOT . $geFile))
            return false;;
        $var = file_get_contents($geFile);
        if (empty($var)) {
            self::ERROR("Configuration called, but returnet string is empty :-/ ", 'conf', "file: <b>$file</b>");
        } else {
            if ($etc == false) {
                $var = (self::deCrypt($var, crc32_(self::kernel_file_configuration($file) . HEX)));
            }
            $var = unserialize($var);
        }
        if (is_array($var)) {
            return $var;
        } else {
            self::ERROR("Something is wrong, the configuration for module is not array :-/ ", 'conf', "file: <b>$file</b>");
            return false;
        }
    }

    /**
     * Create file name for configuration
     *
     * @param mixed $file file source
     */
    private function kernel_file_configuration($file) {

        return md5($file . HEX) . self::$KERNEL_PROP_MAIN['kernel.trunkExt'];
    }

    /**
     * Encrypt
     *
     * encrypt $input value with password
     * @copyright fire1
     * @param string $input text
     * @param string $key password
     * @return string encrypted $input
     */
    protected function enCrypt($input, $key) {

        $td = mcrypt_module_open(self::$KERNEL_PROP_MAIN['kernel.cryptCypher'], '', self::$KERNEL_PROP_MAIN['kernel.cryptMode'], '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $crypttext = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        return $iv . $crypttext;
    }

    /**
     * Dencrypt
     *
     * Dencrypt $input value with password
     * @copyright fire1
     * @param string $input text
     * @param string $key password
     * @return string dencrypted $input
     */
    protected function deCrypt($input, $key) {

        $plaintext = '';
        $td = mcrypt_module_open(self::$KERNEL_PROP_MAIN['kernel.cryptCypher'], '', self::$KERNEL_PROP_MAIN['kernel.cryptMode'], '');
        $ivsize = mcrypt_enc_get_iv_size($td);
        $iv = substr($input, 0, $ivsize);
        $crypttext = substr($input, $ivsize);
        if ($iv) {
            mcrypt_generic_init($td, $key, $iv);
            $plaintext = mdecrypt_generic($td, $crypttext);
        }
        return $plaintext;
    }

    /**
     * Dollop $_GET
     * Or simple secund section of website
     *
     * The function is alternative for "$_GET" use.
     * This function given opportunity to use sub value in uri requests.
     *
     * This is only available when the sub value is numeric.
     * In this order sub value can be used tor transmission of IDs.
     *
     * @package kernel
     * @copyright fire1
     * @example URL query: ?news=main:1  $str= _('news');  query "news" is "main" and $_GET["main"] is "1"
     *
     * @param string $GET n@me of URL string
     * @returns   $_GET['id'] ;  $_GET['n@me']  ;
     *  @global $_GET
     */
    public function _GET($GET) {
        self::kernel_count_memory(__FUNCTION__);
        if (empty(self::$CONFIGURATION["URL_EXTENSION"])) {
            $ext = array(":", "%3A");
        } else {
            $ext = self::$CONFIGURATION["URL_EXTENSION"];
        }
        $_GET[$GET] = str_replace("{ExplodE}", "", $_GET[$GET]);
        $sect_articles = str_replace($ext, "{ExplodE}", $_GET[$GET]);
        $sect_articles = explode("{ExplodE}", $sect_articles);
        if (!empty($sect_articles[1]) && is_numeric($sect_articles[1])) {
            $this->URL_TREE[0] = $GET;
            $this->URL_TREE[1] = $sect_articles[0];
            $_GET['id'] = $sect_articles[1];
            $_GET[$GET] = $sect_articles[0];
            return true;
        } else {
            return false;
        }
    }

    /**
     * URL query GFV Navigator
     * Get First Value
     * @example ?home=wellcome&view=first -> [part 1]=[part 2]&[part 3] etc
     * This function will return first value in request uri.
     * For all Dollop systems first value from request is highly sensitive.
     *
     *
     *
     * @version 2
     * @param int $part it will return part from request
     * @package kernel
     * @param string $_GET
     * @return string first QUERY_STRING
     */
    function query_GFV($part = 0) {
        $parts = array("&", "="); // geting  sign used in get //
        $query_string = str_replace($parts, "{|}", $_SERVER['QUERY_STRING']); // replacing for  separation //
        $qstring = explode("{|}", $query_string);
        return $qstring[$part]; // return first var //
    }

    /**
     * Send header date of website
     *
     * Header information for php system
     * @copyright fire1
     * @returns [send header data for website]
     */
    public function set_header() {
        global $SQL_WEBSITE, $THEME;
        if (!headers_sent() && !empty($SQL_WEBSITE)) {
            // header ("Content-Type: text/html; charset=".$SQL_WEBSITE['charset']);
            if (!defined("RESERVED_URI")) {
                header("Content-Type: text/html; charset={$SQL_WEBSITE['charset']}; ");
            }
            $PageContent = ob_get_contents();
            if (isset($PageContent) && (bool) self::$KERNEL_PROP_MAIN['kernel.content.cache']) {
                // Generate unique Hash-ID by using MD5
                $HashID = md5($PageContent);
                // Specify the time when the page has
                // been changed. For example this date
                // can come from the database or any
                // file. Here we define a fixed date value:
                $LastChangeTime = 1144055759;
                // Define the proxy or cache expire time
                $ExpireTime = 3600; // seconds (= one hour)
                // Get request headers:
                $headers = apache_request_headers();
                // you could also use getallheaders() or $_SERVER
                // or HTTP_SERVER_VARS
                // Add the content type of your page
                //header('Content-Type: text/html');
                // Content language
                //header('Content-language: en');
                // Set cache/proxy informations:
                header('Cache-Control: max-age=' . $ExpireTime); // must-revalidate
                header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $ExpireTime) . ' GMT');
                // Set last modified (this helps search engines
                // and other web tools to determine if a page has
                // been updated)
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $LastChangeTime) . ' GMT');
                // Send a "ETag" which represents the content
                // (this helps browsers to determine if the page
                // has been changed or if it can be loaded from
                // the cache - this will speed up the page loading)
                header('ETag: ' . $HashID);
                // The browser "asks" us if the requested page has
                // been changed and sends the last modified date he
                // has in it's internal cache. So now we can check
                // if the submitted time equals our internal time value.
                // If yes then the page did not get updated
                $PageWasUpdated = !(isset($headers['If-Modified-Since']) and strtotime($headers['If-Modified-Since']) == $LastChangeTime);
                // The second possibility is that the browser sends us
                // the last Hash-ID he has. If he does we can determine
                // if he has the latest version by comparing both IDs.
                $DoIDsMatch = (isset($headers['If-None-Match']) and ($HashID == $headers['If-None-Match']));
                // Does one of the two ways apply?
                if (!$PageWasUpdated or $DoIDsMatch) {
                    // Okay, the browser already has the
                    // latest version of our page in his
                    // cache. So just tell him that
                    // the page was not modified and DON'T
                    // send the content -> this saves bandwith and
                    // speeds up the loading for the visitor
                    header('HTTP/1.1 304 Not Modified');
                    // That's all, now close the connection:
                    header('Connection: close');
                    // The magical part:
                    // No content here ;-)
                    // Just the headers
                } else {
                    // Okay, the browser does not have the
                    // latest version or does not have any
                    // version cached. So we have to send him
                    // the full page.
                    header('HTTP/1.1 200 OK');
                    // Tell the browser which size the content
                    header('Content-Length: ' . strlen($PageContent));
                }
            }
        }
    }

    /**
     * Execute installation for system
     *
     */
    private function dollop_installation() {
        if (strlen(request_uri()) > 1) {
            header_location("/");
        }
        self::CONFIGURATION();
        global $KERNEL_CONFIGURATION;
        if (!file_exists(self::$CONFIGURATION['dp.config'])) {
            self::SAVEMODE(TRUE);
            $c = self::$CONFIGURATION['trunk'] . DIRECTORY_SEPARATOR . md5(self::$CONFIGURATION['HEX']) . self::$KERNEL_PROP_DATA['MAIN']['kernel.trunkExt'];
            if (!file_exists($c) OR @filesize($c) > 1) {
                $src = self::enCrypt(serialize(self::$CONFIGURATION), crc32(self::$CONFIGURATION['HEX']));
                file_put_contents($c, $src, LOCK_EX);
            }
            require_once (self::$CONFIGURATION['install'] . self::$KERNEL_PROP_DATA['install']['indexFile']);
            // system exit here
        }
    }

    /**
     * Function for reading of SQL files
     *   This function will be executed only if can find website "PREFIX"
     *    in order to replace it when ."sql" file is loaded.
     *
     *   Also can replace other tags in form "{$tag}".
     *   For this use "$arrKey" must have name of tag and "$arrContent",
     *   content that be replaced.
     *
     * @version 3.1.24
     * @example read_sql('myfilewithsql.sql',[mytable],[mytablesigood])
     * @author fire1@abv.bg
     * @package kernel
     *
     * @param mixed $file //file name
     * @param mixed $arrKey // key tags
     * @param mixed $arrContent // content
     */
    public function read_sql($file, $arrKey = null, $arrContent = null) {
        global $dpdb, $CONFIGURATION, $returnback_sqlresult;
        $handle = @fopen($file, "r");
        if (!defined("PREFIX")) {
            return "Error! Cannot find SQL Prefix!";
        }
        if ($handle) {
            $returnback_sqlresult = "";
            if (!is_object($dpdb)) {
                return "Connection closed! The file \"handle.database\" is missing from the library !";
            }
            While (!feof($handle)) {
                $query.= fgets($handle, 4096);
                if (substr(rtrim($query), -1) == ';') {
                    // Placing prefix and other tags
                    $query = str_replace('".PREFIX."', constant('PREFIX'), $query);
                    $query = str_replace('{$' . $arrKey . '}', $arrContent, $query);
                    if (!empty($CONFIGURATION['base_tag']['sqlcharset']) && !empty(self::$KERNEL_PROP_DATA['install']['sql.charset_tag'])) {
                        $query = str_replace(self::$KERNEL_PROP_DATA['install']['sql.charset_tag'], $CONFIGURATION['base_tag']['sqlcharset'], $query);
                    }
                    $query = str_replace('{$' . $arrKey . '}', $arrContent, $query);
                    $catch_table_sql = explode(constant('PREFIX'), $query);
                    $catch_table = explode(" ", $catch_table_sql[1]);
                    $returnback_sqlresult.= (db_query($query)) ? "{$catch_table[0]} is executed OK... \n " : " {$catch_table[0]} is NOT executed! Error:\n " . db_error() . " \n\n\n ";
                    unset($query);
                }
            }
            fclose($handle);
            return $returnback_sqlresult . "";
        } else {

            return "File cannot be load!";
        }
    }

    /**
     * MySQL kernel cache
     * This function query the MySQL database and cache the selected data,
     * in case cpanel is in use, cache data are rebuilded.
     * The time for uses of caches can be controled from kernel.prop
     *
     * @package kernel
     * @copyright fire1
     * @example sql_fetch_array('SELECT * FROM `pages` WHERE `id`=1;');
     * @version 1.4
     * @param mixed $query QUERY STRING
     * @return  Parser|false
     */
    public function sql_fetch_array($query) {
        global $trunkcount_check_same;

        if (!self::$KERNEL_SAVEMODE) {
            $arrCreateSQLname = explode(constant('PREFIX'), $query);
            $table = explode(" ", $arrCreateSQLname[1]);
            $table = $table[0];
            $filterSRCH = array(" ", "*", "`", ",", ".");
            $filterRPLS = array("_", "-", "", "+", "");
            $table = str_replace($filterSRCH, $filterRPLS, $table);
            $trunkIt = "sql_fetch_array(" . $table . "):" . crc32_($query . HEX . HOST . request_uri()) . ".serialize";
            //clear file in trunk last try
            if (!(bool) self::$KERNEL_REBIRTH) {
                $trunk = self::trunkit($trunkIt);
            } else {
                self::trunkit($trunkIt, -1);
            }
            if ((bool) self::$CONFIGURATION['trunk.disable.sql_fetch'] == true) {
                $trunk = false;
            }
            if ($trunk == false) {
                $result = mysql_query($query) or die($error = mysql_error());
                //
                // show update data
                self::ERROR("MySQL table ({$table}) trunk updated! ", 'dbug', 'SQL Query: ' . $query);
                if ($error) {
                    (kernel::ERROR(" <b>mysql_query</b> error for query [<span title='[Query:$query]\n[Error:" . $error . "]'> <u>more</u> </span>]<br /> ", "sql"));
                }
                $i = 0;
                if ((bool) $result) {
                    while ($array = mysql_fetch_array($result)) {
                        $row[$i] = $array;
                        $i++;
                    }
                }


                if (empty($row)) {
                    kernel::ERROR("Empty result in sql query [<span title='[$sql]'> <u>more</u> </span>] <br /> ");
                    return false;
                } else {
                    // Use unlink Command
                    //self::trunkit($trunkIt, -1);
                    self::trunkit($trunkIt, $row);
                    return $row;
                }
            } else {
                return $trunk;
            }
        }
    }

    /**
     * MySQL operation insert or update table
     * It is highly recommended to... do not use this function for public inserts or updates!
     *   This function will convert all post array data into mysql query.
     *
     * @package kernel
     *
     * @param mixed $table
     * @param mixed $operation case : insert,update
     * @param mixed $where - for update
     */
    public function sql_post_input_query($table, $operation, $where = null) {

        // remuve "submit", "update", "login", "register", "signin" and "signup"
        // see kernel prop to add mmore
        if (is_array(self::$KERNEL_PROP_MAIN)) {
            foreach (self::$KERNEL_PROP_MAIN['kernel.sql_post_input_query.unset'] as $unset) {
                unset($_POST[$unset]);
            }
        }
        switch ($operation) {
            case "insert":
                if ($where == null) {
                    if (is_array($_POST)) {
                        $post = $_POST;
                    } else {
                        return false;
                    }
                }
                // fixing slashes... just in case, because I'm a maniac :)
                stripslashes_deep($post);
                array_walk_recursive($post, 'mysql_real_escape_string');
                array_walk_recursive($post, 'addslashes');
                $table_name = constant("PREFIX") . $table;
                $columns = "";
                $values = "";
                foreach ($post as $column => $value) {
                    $columns.= "`{$table_name}.{$column}`,";
                    $values.= "'{$value}',";
                }
                $columns = "(" . substr($columns, 0, -1) . ")";
                $values = "(" . substr($values, 0, -1) . ")";
                $q = "INSERT INTO {$table_name} {$columns} VALUES {$values};";
                break;
            case "update":
                if (is_array($_POST) && !empty($where)) {
                    $post = $_POST;
                } else {
                    return false;
                }
                // fixing slashes... just in case, because I'm a maniac :)
                $post = stripslashes_deep($post);
                array_walk_recursive($post, 'mysql_real_escape_string');
                array_walk_recursive($post, 'addslashes');
                $table_name = constant("PREFIX") . $table;
                $columns = "";
                $values = "";
                foreach ($post as $column => $value) {
                    $columns_values.= "`{$table_name}.{$column}`='{$value}',";
                }
                $columns_values = "(" . substr($columns_values, 0, -1) . ")";
                $q = "UPDATE {$table_name} SET {$columns_values} WHERE {$where};";
                break;
            DEFAULT:
                self::ERROR(" Error in MySQL <b>{$operation}</b> for table {$table_name} ", "sql", "Use the function like this: \n kernel::sql_query('news','insert'); \n or \n kernel::sql_query('news','update','IDcolumn=1'); ");
                return theme::content(dp_show_responses(003, "Unsuccessful implementation of information, filed for improper execution of function in the PHP script."));
                break;
        }
        global $db;
        $cq = mysql_query($q, $db) or theme::content(dp_show_responses(003, mysql_error()));
        if ($cq)
            return true;
    }

    /**
     * Protect $_POST request for MySQL injection
     *
     */
    public function sql_post_protection_simple($arr = null) {
        if (!defined("MRES")) {
            define("MRES", true);
            $_POST = stripslashes_deep($_POST);
            adsl_array($_POST, true);
        }
    }

    /**
     *  Program information
     *
     * What this does is get the program's information,
     * and splits it into either the CPU Or MEM usage (COM).
     *
     * @copyright dweston@hotmail.co.uk
     * @example GetProgCpuUsage('php','cpu');
     * @param mixed $program name
     * @return string usage of CPU or MEM
     */
    protected function GetProgCOMUsage($program, $case) {
        if (!$program)
            return -1;
        $case = strtolower($case);
        switch ($case) {
            case 'cpu':
                $c_pid = exec("ps aux | grep " . $program . " | grep -v grep | grep -v su | awk {'print $3'}");
                break;
            case 'mem':
                $c_pid = exec("ps aux | grep " . $program . " | grep -v grep | grep -v su | awk {'print $4'}");
                break;
        }
        return $c_pid;
    }

    /**
     * This function can include "css" or "JavaScript" files in theme html source.
     *  Also in the case string $type have value of `add` will add  tags for
     *  JavaScript without "scr=" in it
     *
     *
     * @param string $file 'destination to file'
     * @param string $type  type of file (CSS/JS) (or "add" for script)
     */
    public function includeByHtml($file, $type, $external = false) {

        // glueCode
        self::glueCode(__FUNCTION__);
        if (empty(self::$KERNEL_PROP_MAIN))
            self::loadprop();;
        $parts = parse_url($file);
        if (empty($parts['scheme'])) {
            $HOST = HOST;
        }
        $type = strtolower($type);
        switch ($type) {
            case 'css':
                //if(!file_exists(ROOT.$file) && $external === false ) return false;;
                $GLOBALS['THEME'] = str_replace(self::$KERNEL_PROP_MAIN['include.TagStyleCSS'], '<link rel="stylesheet" type="text/css" href="' . $HOST . $file . '"/>' . "\n" . self::$KERNEL_PROP_MAIN['include.TagStyleCSS'] . "\n", $GLOBALS['THEME']);
                break;
            case 'js':
                //if(!file_exists(ROOT.$file) && $external === false ) return false;;
                $GLOBALS['THEME'] = str_replace(self::$KERNEL_PROP_MAIN['include.TagJavaScript'], '<script src="' . $HOST . $file . '" type="text/javascript"></script>' . "\n" . self::$KERNEL_PROP_MAIN['include.TagJavaScript'] . "\n", $GLOBALS['THEME']);
                break;
            case 'add':
                $GLOBALS['THEME'] = str_replace(self::$KERNEL_PROP_MAIN['include.TagJavaScript'], $file . "\n" . self::$KERNEL_PROP_MAIN['include.TagJavaScript'] . "\n", $GLOBALS['THEME']);
                break;
        }
    }

    /**
     * This function will execute php after connection to mysql is established
     * In case `$case` will attach scripts with string `$add` that are
     *  waiting for connection to mysql.
     *
     * @package kernel
     *
     * @param mixed $case // add or DEFAULT execute
     * @param mixed $add  value of eval
     */
    public function exec_after_mysqlConnect($case = '', $add = '') {
        self::kernel_count_memory(__FUNCTION__);
        switch ($case) {
            DEFAULT:
                if (is_array($this->exec_after_conn_sql)) {
                    foreach ($this->exec_after_conn_sql as $eval) {
                        eval($eval);
                        kernel::ERROR("[!] SQL INSTALL! <ul>{$GLOBALS['returnback_sqlresult']} ", 'dbug', '');
                    }
                }
                self::sql_post_protection_simple();
                break;
            case "add":
                $this->exec_after_conn_sql[] = $add;
                break;
        }
    }

    /**
     * Dual script
     *  Execute a command in the background without waiting for the result
     *
     * @package kernel
     *
     * @todo must test it well
     * @copyright Martin Lakes
     * @param mixed $argv_parameter
     */
    protected function DualScript($argv_parameter) {
        /* NOTE
         * First of all: put the full path to the php binary,
         *  because this command will run under the apache user,
         *  and you will probably not have command alias like php set in that user.
         * Seccond: Note 2 things at the end of the command string:
         * the '2>&1' and the '&'. The '2>&1'
         * is for redirecting errors to the standard IO.
         *  And the most important thing is the '&' at the end of the command string,
         *  which tells the terminal not to wait for a response.
         */
        passthru("/usr/bin/php /path/to/script.php " . $argv_parameter . " >> /path/to/log_file.log 2>&1 &");
    }

    /**
     * Install data from module
     *
     * @package kernel
     *
     * @param mixed $folder // folder of module
     * @param mixed $install
     */
    private function urlCourse_install($folder, $install) {

        $root = ROOT . $folder . $install;
        $file_prop = $root . DIRECTORY_SEPARATOR . self::$KERNEL_PROP_MAIN['kernel.urlCourse.installProp'];
        if (file_exists($file_prop)) {
            $inprop = self::loadprop($file_prop, 1);
        } else {
            self::ERROR('[!] ERROR IN MODULE INSTALL ... !!! ', 'php', 'Searched file is: ' . $file_prop . "\n Line:" . __LINE__);
            return false;
        }

        if (is_array($inprop[self::$KERNEL_PROP_MAIN['kernel.urlCourse.installPropSector']])) {
            //
            // PHP/ETC/SQL Installation
            foreach ($inprop[self::$KERNEL_PROP_MAIN['kernel.urlCourse.installPropSector']] as $case => $value) {
                $file = $root . DIRECTORY_SEPARATOR . $value;
                //
                //Switch between sectors
                switch ($case) {
                    case self::$KERNEL_PROP_MAIN['kernel.urlCourse.installEtc']: // etc
                        $file = $root . DIRECTORY_SEPARATOR . $value;
                        $fileinfo = pathinfo($file);
                        $etcdata = self::loadprop($file, 1);
                        self::etc($folder, $fileinfo['filename'], 'install', $etcdata, 1);
                        break;
                    case self::$KERNEL_PROP_MAIN['kernel.urlCourse.installSql']: // sql

                        self::exec_after_mysqlConnect("add", 'self::$klog[]= kernel::read_sql("' . $file . '"); ');
                        break;
                    case self::$KERNEL_PROP_MAIN['kernel.urlCourse.installPhp']: // php
                        if (is_array($value)) {
                            foreach ($value as $type => $file) {

                            }
                        }
                        break;
                    case self::$KERNEL_PROP_MAIN['kernel.urlCourse.installMnu']: // menu

                        break;
                    DEFAULT:
                        self::ERROR('[!] ERROR IN MODULE INSTALL ... !!! ', 'conf', 'Searched case keyword is: ' . $case . "\n Line:" . __LINE__);
                        return false;
                        break;
                }
            }
        }
        // un-quote to CLOSE for DEBBUGING fire1
        // $inprop[self::$KERNEL_PROP_MAIN['kernel.urlCourse.installPropMenu']] = null;
        if (is_array($inprop[self::$KERNEL_PROP_MAIN['kernel.urlCourse.installPropMenu']])) {
            //
            // Menu Installation
            foreach ($inprop[self::$KERNEL_PROP_MAIN['kernel.urlCourse.installPropMenu']] as $menunam => $infile) {
                //
                // Get Configuration profile
                $sqltable = self::$KERNEL_PROP_MAIN['kernel.urlCourse.installMnu.table'];
                $cellttle = self::$KERNEL_PROP_MAIN['kernel.urlCourse.installMnu.tcll'];
                $cellbody = self::$KERNEL_PROP_MAIN['kernel.urlCourse.installMnu.bcll'];
                $cellstte = self::$KERNEL_PROP_MAIN['kernel.urlCourse.installMnu.scll'];
                $cellsctp = self::$KERNEL_PROP_MAIN['kernel.urlCourse.installMnu.pcll'];
                //
                // Do the menu insert
                $descramble = md5($menunam . HEX);
                if (file_exists(ROOT . MODULE_DIR . $infile[$cellsctp])) {
                    $attach = realpath(ROOT . MODULE_DIR . $infile[$cellsctp]);
                    $storename_log[] = "Menu `" . $menunam . "` is installed! ";
                    $script = <<<eol
            include(\'$attach\');
            theme::assign(\'$menunam\', $ $menunam);

eol;
                }
                if (isset($attach)) {
                    $sql = <<<eol
   REPLACE INTO `".PREFIX."$sqltable` (`$cellttle`, `$cellbody`,`$cellsctp`, `$cellstte`)
       VALUES ('{$infile[$cellttle]}', '{$infile[$cellbody]}','{$script}  ', '$descramble');
eol;
                    // Exec query after mysql is connected
                    self::exec_after_mysqlConnect("add", ' mysql_query("' . $sql . '") or die(mysql_error()); ');
                    $sql = null;
                }
            }
        }
        if (is_array($storename_log)) {
            self::$klog[] = array_unique($storename_log);
        }
    }

    /**
     * Constructs constants for all configuration data from prop files.
     * String $prop must contain array data by the model of execution from
     *   function `parse ini`  with sections.
     *
     * @package kernel
     *
     * @param mixed $prop array
     */
    protected function _url_urlCourse_define($prop) {
        if (is_array($prop)) {
            foreach ($prop['MAIN'] as $key => $value) {

                $constant_name = str_replace(".", "_", $key);
                if (is_scalar($value)) {
                    if (!defined(strtoupper($constant_name)))
                        define(strtoupper($constant_name), $value);
                }
            }
            $constant_name = "";
            foreach ($prop['FACIAL'] as $key => $value) {
                $constant_name = str_replace(".", "_", $key);
                if (!defined(strtoupper($constant_name)))
                    define(strtoupper($constant_name), $value);
            }
            //////////////////////////////////////////////////////////////////
            if (!empty($prop['INSTALL']['kernel.install'])) {
                define('MODULE_INSTALL', $prop['INSTALL']['kernel.install']);
            }
            //////////////////////////////////////////////////////////////////
            $constant_name = "";
            foreach ($prop['CPANEL'] as $key => $value) {
                $constant_name = str_replace(".", "_", $key);
                if (!defined(strtoupper($constant_name)))
                    define(strtoupper($constant_name), $value);
            }
        }
        return false;
    }

    /**
     * Execute reserved request uri script. Can create or edit data for reserved script.
     *
     * If string values are empty will execute reserved request script.
     * If string `$case` have value check will return recordet data in etc for reserved requests.
     * If string `$case` have value for add will record new line in etc reserved requests. This will
     *  replace all previous information for reserved request uri.
     *
     * @package kernel
     *
     * @param mixed $case    // valid values: check,add,[DEFAULT]
     * @param mixed $title   // in case add
     * @param mixed $url     // in case add
     * @param mixed $file    // in case add
     * @return string or false
     */
    protected function url_reserved($case = "", $title = "", $url = "", $file = "") {

        $file = SRCDR . self::$KERNEL_PROP_MAIN['kernel.reserved_request'];
        switch ($case) {
            case 'check':
                global $reserved_request_uri;
                $request = $title;
                if (empty($reserved_request_uri)) {
                    $reserved_request_uri = self::loadprop($file, 1);
                }
                return $reserved_request_uri['request.' . $request];
                break;
            case 'add':
                $request = ";\n;\n;\t" . ucfirst($title) . "\n;\nrequest.{$url}\t\t={$file}\n;\n;";
                file_put_contents($file, $request, FILE_APPEND);
                break;
            DEFAULT:
                $request = request_uri();
                $request = str_replace(array(DIRECTORY_SEPARATOR, "?", "="), "{|-url_reserved-|}", $request);
                $request_folders = explode("{|-url_reserved-|}", $request);
                if (empty($request_folders[0])) {
                    $first_request = $request_folders[1];
                } else {
                    $first_request = $request_folders[0];
                }
                $reserved = self::loadprop($file, 1);
                foreach ($reserved as $key => $value) {
                    $prop_request = str_replace('request.', "", $key);
                    $file_source = realpath(str_replace("[system]", SRCDR, $value));
                    if ($prop_request == $first_request) {
                        return $file_source;
                    }
                    $file_source = "";
                }
                return false;
                break;
        }
    }

    /**
     * Creates constants from uri request.
     *
     * @package kernel
     *
     * @param mixed $request
     * @param mixed $fileprop
     * @return $folder_tree
     */
    protected function _url_constant($request, $fileprop) {
        $folder_tree = false;
        $parts = explode(DIRECTORY_SEPARATOR, $request);
        foreach ($parts as $key => $value) {
            if (!empty($value))
                $folder_tree.= $value . DIRECTORY_SEPARATOR;
            if (realpath(ROOT . $folder_tree . $fileprop))
                return @$folder_tree;
        }
    }

    /**
     * This function execute Installation and definitions from request in website
     * and prepare data for module.
     *
     * @group urlCourse
     * @package kernel
     *
     */
    private function urlCourse_in_kernel() {
        global $CONFIGURATION;
        //
        // Fix dobble click for user login
        if (!isset($_COOKIE[$CONFIGURATION['COOKIE_SESSION']])) {
            self::SESSION_SET(null, null);
            ;
        }
        //
        // glueCode
        self::glueCode(__FUNCTION__);
        $file_prop = self::$KERNEL_PROP_MAIN["kernel.urlCourse.configProp"];
        $request = explode("?", request_uri());
        define("URI_PATH", $request[0]);
        //
        // Get path to file prop
        $module_dir = self::_url_constant($request[0], $file_prop);
        $cnff = ROOT . $module_dir . $file_prop;
        if (!defined("MODULE_PROP") && !empty($cnff)) {
            define("MODULE_PROP", $cnff);
        }
        //
        // Try to load prop file from Dollop's trunk
        $trunK_prop = self::kernel_load_configuration($cnff);
        //
        // Check for reserved url request
        if ((bool) $reserved = self::url_reserved()) {
            define("RESERVED_URI", $reserved);
            define("APP.INIT", true);
            return true;
        } elseIf ((strlen($request[0]) < 2) && !empty($request[1])) {
            //
            // Using ROOT scripts?
            $root = self::query_GFV();
            $file_root = ROOT . "root" . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . "source.php";
            if (realpath($file_root)) {
                $module_dir_root = dirname($file_root);
                foreach (glob($module_dir_root . "includes" . DIRECTORY_SEPARATOR . "*.inc") as $inc) {
                    include_once ($inc);
                }
                define("MODULE_ROOT", $file_root);
            } else {
                self::ERROR('ERROR ... Root file do not exists', 'conf', 'First Query giving: ' . QF . '  Searched file is: ' . $file_root . "\n Line:" . __LINE__);
                return false;
            }
            //
            // If find the Dollop prop is array runs the module Conf. defines
        } elseif (is_array($trunK_prop)) {
            $prop = $trunK_prop;
            self::_url_urlCourse_define($prop);
        } elseIf (file_exists($cnff)) {
            //
            // Create hashes for installation Improvements
            self::CreateInstallationModule($cnff);
            //
            // Asc for Approve from CPanel User to install Improvements
            if (self::ApproveInstallationModule($cnff) OR $cnff == self::base_tag("{root}{users}/build.prop")) {
                //
                // Creates the Dollop prop file
                $prop = self::kernel_new_configuration($cnff, 1, 1, self::$KERNEL_PROP_DATA['MAIN']['kernel.urlCourse.unlinkProp']);
                //
                // Define constants from props
                self::_url_urlCourse_define($prop);
                //
                // Check build's installation string is defined
                if (defined('MODULE_INSTALL')) {
                    //
                    // Be sure is defined
                    if (!defined("MODULE_DIR")) {
                        define("MODULE_DIR", $module_dir);
                    }
                    if (!defined("MODULE_PROP") && !empty($cnff)) {
                        define("MODULE_PROP", $cnff);
                    }
                    //
                    // Run the installation
                    self::urlCourse_install($module_dir, constant('MODULE_INSTALL'));
                }
            }
        } else {
            self::ERROR('Problem ... Cannot load source file from "Query First" ', 'conf', 'First Query giving: ' . QF . '  Searched file is: ' . $cnff . "\n This can also be caused by the fact that it is not set start page.");
        }

        if (defined("RESERVED_URI")) {

        } elseif (is_array($prop)) {
            $module_request_folder = str_replace($module_dir, "", request_uri());
            if (!defined('urlCourse_templates')) {
                define('urlCourse_templates', $prop['MAIN']['module.templates']);
                ;
            }
            if (!defined('MODULE_FACIAL')) {
                define("MODULE_FACIAL", dirname(dirname($cnff) . DIRECTORY_SEPARATOR . $prop['FACIAL']['module.facial.source']) . DIRECTORY_SEPARATOR);
                ;
            }
            if (!defined("MODULE_DIR")) {
                define("MODULE_DIR", $module_dir);
            }
            if (!defined("MODULE_TPL_DIR")) {
                define("MODULE_TPL_DIR", str_replace(constant("MODULE_DIR"), "", @constant("URI_PATH")));
            }
            if (!defined("MODULE_REQUESTS")) {
                define("MODULE_REQUESTS", $module_request_folder);
            }
            define("APP.INIT", true);
        } else {
            self::ERROR('Problem ... module prop file data is not array ', 'conf', 'To fix check file name: ' . $cnff . " or empty module directory path in system trunk. ");
            return false;
        }
    }

    /**
     * This function attach scripts before starts basic module process
     *
     * @group urlCourse
     */
    public function urlCourse_includes() {
        // glueCode
        self::glueCode(__FUNCTION__);
        if (defined("MODULE_FACIAL_INCLUDES")) {
            $inclout = array();
            foreach (glob(constant("MODULE_DIR") . constant("MODULE_FACIAL_INCLUDES") . "*" . ".const.php" . "*") as $inc_files) {
                array_push($inclout, $inc_files);
            }
            foreach (glob(constant("MODULE_DIR") . constant("MODULE_FACIAL_INCLUDES") . "*" . ".inc.php" . "*") as $inc_files) {
                array_push($inclout, $inc_files);
            }
            foreach (glob(constant("MODULE_DIR") . constant("MODULE_FACIAL_INCLUDES") . "*" . ".class.php") as $inc_files) {
                array_push($inclout, $inc_files);
            }
            // HTML ///////////////////////////////////////////////////////////////////////
            foreach (glob(constant("MODULE_DIR") . constant("MODULE_FACIAL_JAVASCRIPT") . "*" . ".js") as $inc_files) {
                self::includeByHtml($inc_files, 'js');
            }
            foreach (glob(constant("MODULE_DIR") . constant("MODULE_FACIAL_CSS") . "*" . ".css") as $inc_files) {
                self::includeByHtml($inc_files, 'css');
            }
            self::db_kernel();
            return $inclout;
        } else
            return false;;
    }

    /**
     * This function control basic process from request in website.
     *
     * @group urlCourse
     */
    public function urlCourse() {
        global $SQL_WEBSITE;
        /**
         * *  LANGUAGE section for module
         */
        if (defined("MODULE_LANGUAGE") || defined("MODULE_FACIAL_LAN_FILE")) {
            $lan_file = constant("MODULE_DIR") . constant("MODULE_LANGUAGE") . $SQL_WEBSITE['lan'] . DIRECTORY_SEPARATOR . constant("MODULE_FACIAL_LAN_FILE");
            if (file_exists($lan_file)) {
                self::language($lan_file);
            }
        }
        // glueCode
        self::glueCode(__FUNCTION__);
        self::kernel_count_memory(__FUNCTION__);
        if (defined('MODULE_ROOT')) {
            require_once (constant('RESERVED_URI'));
            ;
            return false;
        }
        if (defined('RESERVED_URI')) {
            return (constant('RESERVED_URI'));
            ;
            return false;
        }
        /**
         * debuggin the module and check configuration.
         * creating the require once file.
         */
        if (defined("APP.INIT")) {
            if (defined("MODULE_FACIAL_KERNEL")) {
                foreach (glob(constant("MODULE_DIR") . constant("MODULE_FACIAL_KERNEL") . "*" . ".inc" . "*") as $inc_files) {
                    @include_once ($inc_files);
                }
            }
            $requested = explode("/", request_uri());
            $onlyModuleDir = explode(DIRECTORY_SEPARATOR, constant("MODULE_DIR"));
            $request_file_at_end = explode("?", $requested[count($onlyModuleDir)]);
            if (is_array($request_file_at_end)) {
                $request_file_end = current($request_file_at_end);
            } else {
                $request_file_end = $request_file_at_end;
            }
            /* change include path */
            if (defined("MODULE_FACIAL_INCLUDE_PATH") && (constant("MODULE_FACIAL_INCLUDE_PATH") === true)) {
                set_include_path(constant("MODULE_DIR"));
            }
            if (realpath(constant("ROOT") . constant("MODULE_DIR") . @constant('MODULE_FACIAL_SOURCE'))) {
                $source = constant("ROOT") . constant("MODULE_DIR") . constant('MODULE_FACIAL_SOURCE');
                if (!defined("MODULE_DIR_FILE")) {
                    define("MODULE_DIR_FILE", dirname($source));
                }
                if (!defined("MODULE_INCLUDE_FILE")) {
                    define("MODULE_INCLUDE_FILE", $source);
                }
            } elseIf (defined("MODULE_FACIAL_URL_" . strtoupper($request_file_end)) AND realpath(constant("ROOT") . constant("MODULE_DIR") . constant("MODULE_FACIAL_URL_" . strtoupper($request_file_end)))) {
                $source = constant("ROOT") . constant("MODULE_DIR") . constant("MODULE_FACIAL_URL_" . strtoupper($request_file_end));
                if (!defined("MODULE_DIR_FILE")) {
                    define("MODULE_DIR_FILE", dirname($source));
                }
                if (!defined("MODULE_INCLUDE_FILE")) {
                    define("MODULE_INCLUDE_FILE", $source);
                }
            } else {
                self::ERROR('<b>ERROR</b> File source: " ' . $source . ' ", DO NOT EXISTS !', 'file', 'To fix check ".prop" and erase module directory path in system trunk. ');
                return false;
            }
        } else {
            self::ERROR('<b>ERROR</b> File source: " ' . $source . ' ", DO NOT EXISTS !', 'file', 'To fix check ".prop" and erase module directory path in system trunk. ');
            define("WEBSITE_RESPONSES", 404);
            return false;
        }
        return constant("MODULE_INCLUDE_FILE");
    }

    /**
     * Closing request
     * @group urlCourse
     */
    public function urlCourse_close() {
        if (defined("MODULE_FACIAL_INCLUDE_PATH")) {
            ini_restore('include_path');
        }
        //
        // Creating link in server RAM
        self::opch();
    }

    /**
     * Create Installation hash
     *
     * @group PreventRandomInstallation
     * @param string $module_build Path to file
     */
    private function CreateInstallationModule($module_build) {

        self::$installation_id = uniqid("installation");
        self::$installation_hash = sha1(self::$CONFIGURATION['KEY'] . self::$CONFIGURATION['SPR'] . self::$installation_id . self::$CONFIGURATION['SPR'] . $module_build);
    }

    /**
     * Approve Installation hash
     *
     * @group PreventRandomInstallation
     * @param type $module_build
     * @return boolean
     */
    private function ApproveInstallationModule($module_build) {
        //
        // Regenerate Hash
        if ($_GET['module_hash'] == sha1(self::$CONFIGURATION['KEY'] . self::$CONFIGURATION['SPR'] . $_GET['module_hash_id'] . self::$CONFIGURATION['SPR'] . $module_build)) {
            return true;
        }

        return false;
    }

    /**
     * Approve Installation hash
     *
     * @group PreventRandomInstallation
     * @global $language $language
     * @return boolean
     */
    protected function GenerateAnstallationModule() {
        global $language;

        if (!defined("CPANEL"))
            return false;
        if (!empty($_GET['module_hash']) && !empty($_GET['module_hash_id']))
            return false;
        if (empty(self::$installation_hash) OR empty(self::$installation_id))
            return false;

        $hash = self::$installation_hash;
        $id = self::$installation_id;
        $module_name = MODULE_PROP;
        $installation = kernel::base_tag("{host}{request_uri}?module_hash={$hash}&module_hash_id={$id}");
        $js = <<<EOL

                  var checkconfirm =confirm('{$language['main.cp.question.alt']} {$language['lw.install']}: \\n {$module_name} ?!');
                    if (checkconfirm==true){
                        window.location = "{$installation}" ;
                    }

EOL;

        kernel::includeByHtml('<script type="text/javascript">' . $js . '</script>', "add");
    }

    /**
     * Function will redirect to start page record set in
     * MySQL if is empty request uri
     *
     * @param mixed $configuration // of website
     */
    public function first_page($configuration) {
        $request = str_replace(array("/", "?", "#"), "", request_uri());
        if (empty($request)) {
            header_location($configuration['start_page']);
        }
    }

    /**
     *  This function adds language file to script
     *  There are available tow type of file that can be attached
     *
     * with ".php" and ".prop"
     * @author fire1
     * @global $language
     * @param mixed $lan_file
     *
     */
    public function language($lan_file = NULL) {
        global $SQL_WEBSITE;
        // glueCode
        self::glueCode(__FUNCTION__);
        if (is_null($lan_file)) {
            $lan_folder = self::CONFIGURATION("language");
            $lan_file = $lan_folder . $SQL_WEBSITE['lan'] . DIRECTORY_SEPARATOR . self::$KERNEL_PROP_MAIN['kernel.languageFileMain'];
        }
        $ext = explode(".", $lan_file);
        if (file_exists($lan_file)) {
            if (end($ext) == 'php') {
                require_once ($lan_file);
            } elseIf (end($ext) == 'prop') {
                $GLOBALS['language'] = self::loadprop($lan_file, 1);
            }
        } else {
            return false;
            ;
        }
    }

    /**
     * Simply convert prop string to constant and return value of it
     *
     * @param mixed $prop_var // prop varible prop_constant("module.manager.cover");
     * @return mixed
     */
    public function prop_constant($prop_var) {
        $const = strtoupper(str_replace(".", "_", $prop_var));
        if (defined($const)) {
            return constant($const);
        } else {
            return false;
            ;
        }
    }

    /**
     * Analyzes the file that is placed this function and
     * decide on the implementation of the system.
     *
     * @param mixed $file // file execution
     */
    public function analyzer($file) {

    }

    /**
     * This function will generate external file
     * with types JS,CSS and JSON
     * NOTE in case of using JSON generated file:
     *   The generated file name is hashed with md5 hash with HEX constant
     *   example for the hashed json file name: md5(constant("HEX") . $json_content );
     *   "$json_content" - is content of json
     *
     * @example kernel->external_file('css','.test{color:white;}');
     *
     * @package kernel
     *
     * @param mixed $type
     * @param mixed $content
     */
    public function external_file($type, $content) {

        $aval_types = self::$KERNEL_PROP_MAIN['kernel.externelFile.types'];
        if (in_array($type, $aval_types)) {
            $this->external_file[$type].= $content;
        } else {
            return false;
        }
    }

    /**
     * This function will generate external file
     * with types JS,CSS and JSON
     * NOTE in case of using JSON generated file:
     *   The generated file name is hashed with md5 hash with HEX constant
     *   example for the hashed json file name: md5(constant("HEX") . $json_content );
     *   "$json_content" - is content of json
     *
     */
    protected function generete_external_file() {
        global $CONFIGURATION;
        if (isset($_POST)) {
            $post_reload = time();
        } else {
            $post_reload = null;
        }
        $request = request_uri() . $post_reload;
        $session = $CONFIGURATION['COOKIE_SESSION'];
        $name = md5($request . $_COOKIE[$session]);
        foreach (self::$KERNEL_PROP_MAIN['kernel.externelFile.types'] as $type) {
            switch (strtolower($type)) {
                case 'jquery':
                    $content = <<<eol
/* jQuery file is generated by dollop */
$(function(){
{$this->external_file[$type]}
});

eol;

                    break;
                DEFAULT:
                    $content = $this->external_file[$type];
                    break;
            }
            if (!empty($this->external_file[$type]) && $type != 'json') {
                self::SESSION_SET($name, $content);
                self::includeByHtml("generated?{$type}={$name}", str_replace('jquery', "js", $type), true);
            } elseIF (!empty($this->external_file[$type]) && $type == 'json') {
                self::SESSION_SET(md5(constant("HEX") . $this->external_file[$type]), $this->external_file[$type]);
            }
        }
    }

    /**
     * This function exec external scripts
     * loadet from Dollop boot file
     *
     * @param mixed $function
     */
    public function glueCode($sector) {
        global $CONFIGURATION;
        if (!is_array($CONFIGURATION['glueCode']) OR !(bool) self::$KERNEL_PROP_MAIN['kernel.glueCode'])
            return false;;
        $glue_name = array_search($sector, $CONFIGURATION['glueCode']);
        if (!empty($glue_name)) {
            $include_file = $CONFIGURATION['glueCode.inc'][$glue_name];
            foreach ($CONFIGURATION["glueCode.var.{$glue_name}"] as $var => $value) {
                eval('$' . $var . ' = \'' . $value . '\';');
            }
            $real_path = self::base_tag_folder_filter($include_file);
            if (is_null($CONFIGURATION['glueCode.request'][$glue_name])) {
                include ($real_path);
            } elseIf (request_uri() == $CONFIGURATION['glueCode.request'][$glue_name]) {
                include ($real_path);
            }
        }
    }

    /**
     * additional base tags
     *
     * @param mixed $case
     */
    private function base_tag_additional($case) {
        global $CONFIGURATION;
        switch ($case) {
            case 1:
                $Array = array();
                if (!is_array($CONFIGURATION['base_tag']))
                    return null;
                foreach ($CONFIGURATION['base_tag'] as $key => $value) {
                    $Array["{{$key}}"] = $value;
                }
                return $Array;
                break;
            case 2:
                $a = array();
                $b = array();
                if (!is_array($CONFIGURATION['base_tag']))
                    return null;
                foreach ($CONFIGURATION['base_tag'] as $key => $value) {
                    array_push($a, "{{$key}}");
                    array_push($b, $value);
                }
                return array("tags" => $a, "values" => $b);
                break;
        }
    }

    /**
     * Tags used for dollop folders
     *
     * @example base_tag_folder("{system}'");
     *
     * NOTE if you use base_tag_folder(); function will return array with keys: tags,values
     * tags - folders tags
     * tags - folders values
     *
     * @param mixed $tag
     * @return mixed / array
     */
    public function base_tag_folder($tag = null) {
        global $CONFIGURATION;
        if (is_null($tag)) {
            $a = array('{system}', '{hkey}', '{session}', '{design}', '{themes}', '{plugin}', '{module}', '{addons}', '{jquery}', '{language}', '{textarea}', '{scriptarea}', '{host}', '{users}', '{uploads}', '{publicfiles}', '{mobile_jquery}', '{mobile_style}');
            $b = array($CONFIGURATION['source'], $CONFIGURATION['hkey'], $CONFIGURATION['session_dir'], $CONFIGURATION['design'], $CONFIGURATION['themes'], $CONFIGURATION['plugin'], $CONFIGURATION['module'], $CONFIGURATION['addons'], $CONFIGURATION['jquery'], $CONFIGURATION['language'], $CONFIGURATION['textarea'], $CONFIGURATION['scriptarea'], HOST, $CONFIGURATION['websiteUsers'], $CONFIGURATION['websiteUploads'], $CONFIGURATION['publicfiles'], $CONFIGURATION['mobile_jquery'], $CONFIGURATION['mobile_style']);
            $array = array("tags" => $a, "values" => $b);
            if (is_array($CONFIGURATION['base_tag'])) {
                $array = array_merge_recursive($array, self::base_tag_additional(2));
            }
            return $array;
        } else {
            $array = array('{system}' => $CONFIGURATION['source'], '{hkey}' => $CONFIGURATION['hkey'], '{session}' => $CONFIGURATION['session_dir'], '{design}' => $CONFIGURATION['design'], '{themes}' => $CONFIGURATION['themes'], '{plugin}' => $CONFIGURATION['plugin'], '{module}' => $CONFIGURATION['module'], '{addons}' => $CONFIGURATION['addons'], '{jquery}' => $CONFIGURATION['jquery'], '{language}' => $CONFIGURATION['language'], '{textarea}' => $CONFIGURATION['textarea'], '{scriptarea}' => $CONFIGURATION['scriptarea'], '{host}' => HOST, '{users}' => $CONFIGURATION['websiteUsers'], '{uploads}' => $CONFIGURATION['websiteUploads'], '{publicfiles}' => $CONFIGURATION['publicfiles'], '{mobile_jquery}' => $CONFIGURATION['mobile_jquery'], '{mobile_style}' => $CONFIGURATION['mobile_style']);
            if (is_array($CONFIGURATION['base_tag'])) {
                $array = array_merge_recursive(self::base_tag_additional(1), $array);
            }
            return $array[$tag];
        }
    }

    /**
     * Filter folder tags
     *
     * @example $system_file =base_tag_folder_filter("{system}/lib/file.inc");
     *
     * @package kernel
     * @param mixed $value
     * @return mixed
     */
    public function base_tag_folder_filter($value) {
        $filter = self::base_tag_folder();
        $arrayFilterSrch = array("http:/", "//");
        $arrayFilterRpls = array("http://", "/");
        return str_replace($arrayFilterSrch, $arrayFilterRpls, str_replace($filter['tags'], $filter['values'], $value));
    }

    /**
     * Same as base_tag_folder_filter and
     *  can replace "mudule_dir","request_uri","{uri_path}"
     *
     *
     * @version 1.01
     *
     * @example $system_file =base_tag("{system}/lib/file.inc");
     *
     * @package kernel
     * @param mixed $value
     * @return mixed replaced tags
     */
    public static function base_tag($value) {
        global $base_tag_array_filter_global_value_to_dollop_save_search, $base_tag_array_filter_global_value_to_dollop_save_replace;
        if (!is_array($base_tag_array_filter_global_value_to_dollop_save_search) || !is_array($base_tag_array_filter_global_value_to_dollop_save_replace)) {
            //
            // Getting base tag folders
            $filter = self::base_tag_folder();
            $arrayFilterSrch = array();
            $arrayFilterRpls = array();
            //
            // Attaching module dir
            if (defined("MODULE_DIR")) {
                $arrayFilterSrch[] = "{module_dir}";
                $arrayFilterRpls[] = constant("MODULE_DIR");
                $arrayFilterSrch[] = "{module}";
                $arrayFilterRpls[] = constant("MODULE_DIR");
            }
            // attaches
            $arrayFilterSrch[] = "{request_uri}";
            $arrayFilterRpls[] = request_uri();
            // attaches
            $arrayFilterSrch[] = "{root}";
            $arrayFilterRpls[] = constant("ROOT");
            // attaches
            $arrayFilterSrch[] = "{uri_path}";
            $arrayFilterRpls[] = @constant("URI_PATH");
            // attaches
            $arrayFilterSrch[] = "{trunk_temp}";
            $arrayFilterRpls[] = @constant("TRUNK_TEMP") . DIRECTORY_SEPARATOR;
            // atach mysql data
            global $SQL_WEBSITE, $CONFIGURATION;
            // attaches
            $arrayFilterSrch[] = "{trunk}";
            $arrayFilterRpls[] = $CONFIGURATION['trunk'] . DIRECTORY_SEPARATOR;
            if (is_array($SQL_WEBSITE)) {
                foreach ($SQL_WEBSITE as $key => $val) {
                    $arrayFilterSrch[] = "{{$key}}";
                    $arrayFilterRpls[] = "{$val}";
                }
            }
            // end fix
            $arrayFilterSrch[] = "http:/";
            $arrayFilterSrch[] = "//";
            $arrayFilterRpls[] = "http://";
            $arrayFilterRpls[] = "/";
            $Srch = array_merge($filter['tags'], $arrayFilterSrch);
            $Rpl = array_merge($filter['values'], $arrayFilterRpls);
        } else {
            //
            // If is allready set-up array data, use it.
            $Srch = $base_tag_array_filter_global_value_to_dollop_save_search;
            $Rpl = $base_tag_array_filter_global_value_to_dollop_save_replace;
        }
        return str_replace($Srch, $Rpl, $value);
    }

    /**
     * JQuery kernel library
     *
     * @todo not ready at all ...
     */
    public function jquery_lib() {
        // more info at http://code.google.com/apis/libraries/devguide.html#jquery
        // You may specify partial version numbers, such as "1" or "1.3",
        //  with the same result. Doing so will automatically load the
        //  latest version matching that partial revision pattern
        //  (e.g. 1.3 would load 1.3.2 today and 1 would load 1.6.4).
        /*


         */
        $jQuery = <<<JAVASCRIPT
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("jquery", "1");
</script>
JAVASCRIPT;
        $jQueryUI = <<<JAVASCRIPT
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("jqueryui", "1");
</script>

JAVASCRIPT;
    }

    /**
     * Give back unique session from system session
     * @return mixed session / false
     */
    public static function uni_session_name() {
        global $CONFIGURATION;
        $session = session_id();
        if (!isset($session))
            return false;;
        return password($session);
    }

    /**
     * Saving current page theme
     * Note this function is sensitive with "kernel.requests.vermin"
     *
     * @param mixed $theme_file // this will set  the theme file
     *
     */
    protected function set_current_page_theme($theme_file = null) {
        // get and set configuration
        global $CURRENT_PAGE_THEME;
        $arr_p = explode(",", self::$KERNEL_PROP_MAIN['kernel.requests.vermin']);
        $uritree = request_uri_tree();
        // close the function if request is vermin
        if (in_array($uritree['tree'][1], $arr_p))
            return false;
        if (!empty($theme_file)) {
            // set the theme
            $CURRENT_PAGE_THEME = $theme_file;
        } elseif (is_null($theme_file)) {
            // Do NOT set theme from cpanel only user view will set it
            if (defined("CPANEL")) {
                if (constant("QF") == constant("CPANEL")) {
                    return null;
                }
            }
            if ((bool) self::$KERNEL_PROP_MAIN['kernel.currentpage.theme'] && (bool) session_id()) {
                if (!empty($CURRENT_PAGE_THEME)) {
                    $_SESSION[self::$KERNEL_PROP_MAIN['kernel.currentpage.theme.key']] = $CURRENT_PAGE_THEME;
                    //file_put_contents("check", $_SESSION[self::$KERNEL_PROP_MAIN['kernel.currentpage.theme.key']] . "\n" . $uritree['tree'][1] . "\n\n", FILE_APPEND);
                }
            }
        }
    }

    /**
     * Get use of current theme
     *
     */
    public static function current_page_theme() {

        $key = self::$KERNEL_PROP_MAIN['kernel.currentpage.theme.key'];
        $theme_file = $_SESSION[$key];
        if (isset($theme_file) && (bool) session_id()) {
            return $theme_file;
        } else {
            // return def file
            global $SQL_WEBSITE;
            return $SQL_WEBSITE['themefile'];
        }
    }

    /**
     * Cache And read file
     * This function is similar to file_get_contents(), but
     * NO additional options.
     *
     * @global array    $CONFIGURATION      Global Configuration of Dollop
     * @param string    $filename           Name of the file to read.
     * @return resource                     The function returns the read data or FALSE on failure.
     */
    public static function get_contents($filename) {

        if (!defined("HEX")) {
            return false;
        }
        global $CONFIGURATION;
        if (is_null($maxlen)) {
            $maxlen = filesize($filename);
        }
        $contents = null;
        if ((bool) $CONFIGURATION['trunk.disable.get_contents'] === true) {
            return file_get_contents($filename);
        }
        // Check for rebirth
        if ((bool) self::$KERNEL_REBIRTH == true) {
            // Rebiuld on rebirth
            $contents = @file_get_contents($filename) or (self::klog("ERROR: Cannot locate $filename to open and cache it!"));
            self::$kernel_file_contents[crc32_($filename . HEX)] = $contents;
            return $contents;
        } elseif (empty(self::$kernel_file_contents[crc32_($filename . HEX)])) {
            // Normal mode
            if (!empty($filename)) {
                $contents = file_get_contents($filename);
            }
            self::$kernel_file_contents[crc32_($filename . HEX)] = $contents;
            return $contents;
        } else {
            return self::$kernel_file_contents[crc32_($filename . HEX)];
        }
    }

    /*
     * Self kernel put files in  cached
     */

    private static function cache_get_contents() {
        if (!defined("HEX")) {
            return false;
            ;
        }
        // Check for rebirth
        if ((bool) self::$KERNEL_REBIRTH == false) {
            if (self::$kernel_file_contents_hash != crc32_(self::$kernel_file_contents)) {
                // Using Unlink Command for old file
                self::trunkit("put_file(" . HEX . ").serialize", -1);
                $check = self::trunkit("put_file(" . HEX . ").serialize", self::$kernel_file_contents);
            }
        } else {
            // Using Unlink Command for old file
            self::trunkit("put_file(" . HEX . ").serialize", -1);
        }
    }

    /*
     * Self kernel load files from cached
     */

    private static function load_get_contents() {
        if (!defined("HEX")) {
            return false;
        }
        self::$kernel_file_contents = self::trunkit("put_file(" . HEX . ").serialize");
        if (empty(self::$kernel_file_contents)) {
            self::$kernel_file_contents_hash = false;
        } else {
            self::$kernel_file_contents_hash = crc32_(self::$kernel_file_contents);
        }
    }

    public static function klog($text) {
        self::$klog[] = $text;
    }

    protected static function attempt_klog() {
        if (defined("CPANEL")) {

            $jshtml = '';
            if (!is_array(self::$klog))
                return false;
            foreach (self::$klog as $text) {
                if (is_array($text)) {
                    $text = implode("\\n  * ", $text);
                }
                $jshtml .= "*" . str_replace(array("'", "\n"), array('"', "\\n"), addcslashes(strip_tags($text), "\0..\37!@\177..\377")) . "\\n";
            }
            if (isset($jshtml)) {
                self::includeByHtml('<script type="text/javascript">alert(\'' . $jshtml . '\')</script>', "add");
            }
        }
    }

    public function __destruct() {
        global $dbli;
        //
        // Closing mysql Connection
        if ($dbli) {
            @mysqli_close($dbli);
        }
        self::cache_get_contents();
    }

}

/**
 * Execute class kernel
 * Basic process is executed from here.
 * @var kernel
 */
global $kernel;
$kernel = new kernel;
/**
         *   End of Dollop kernel file
         *   Script continue with design script
         *   file is writen by fire1 (Angel Zaprianov)
         */
