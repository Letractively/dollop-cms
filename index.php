<?php

/**
  ============================================================
 * Last committed:     $Revision: 5 $
 * Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
 * Last changed date:    $Date: 2013-02-07 12:05:11 +0200 (÷åòâ, 07 ôåâð 2013) $
 * ID:       $Id: index.php 5 2013-02-07 10:05:11Z fire1.A.Zaprianov@gmail.com $
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
 * @filesource Index file, the contant with all resurces
 * @package dollop
 * @subpackage dollop construction
 */
//
// Debug mode:
//ini_set('error_reporting', E_ALL);
//ini_set("display_errors","1");
//
// My INIT for scripts
define('FIRE1_INIT', true);
// Root directory of Dollop installation.
define('ROOT', getcwd() . "/");
// Defines configuration of system after installation.
@include (ROOT . "config.php");
define("ADD.TRUNK.INIT", TRUE);
//
// Boot scripts and system script packs
// This are required for execution of the other website script.
$file = "bootstrapping.main.inc";
if (file_exists(ROOT . SRCDR . "lib/inc/" . $file)) {
    // Resolving the dynamic system directory.
    $binsysdir = SRCDR . "bin/inc/";
    $libsysdir = SRCDR . "lib/inc/";
    $arrsysdir = SRCDR . "lib/arr/";
} else {
    // Resolving the static system directory.
    $binsysdir = "system/bin/inc/";
    $libsysdir = "system/lib/inc/";
    $arrsysdir = "system/lib/arr/";
}
//
// Required functions for website.
require_once (ROOT . $libsysdir . $file); 
build_inc($libsysdir); // system functions
build_inc($binsysdir); // execute functions
build_inc($arrsysdir); // execute array data functions
//
//  Clear used varibles from index file
unset($file);
unset($libsysdir);
unset($binsysdir);
// Adds kernel to script
require_once (file_exists(ROOT . SRCDR . "kernel.dp.php") ? ROOT . SRCDR . "kernel.dp.php" : ROOT . "system/kernel.dp.php");
//  Adding glueCode to index at start
kernel::glueCode("index.start");
// Connection with MySQL
if (file_exists("db.php"))
    require_once (ROOT . "db.php");;
//  Adding glueCode to index at MySQL connection
kernel::glueCode("index.db");
// Adds html design to script
require_once (file_exists(ROOT . SRCDR . "design.dp.php") ? ROOT . SRCDR . "design.dp.php" : ROOT . "system/design.dp.php");
//
// Attach kernel and theme global varibles
global $kernel, $theme;
// A user data from etc
$kernel->users_const_data();
// Check for properly user data
$kernel->ctrl_cookie();
// Execute CPanel class
$kernel->cpanel_execute();
// Execute scripts that need's MySQL data
$kernel->exec_after_mysqlConnect();
// Attachment languages
$kernel->language();
// Redirect if request is empty
kernel::first_page($theme->WEBSITE);
// Includes script resurses used by the main module script
kernel_add_scripts(kernel::urlCourse_includes());
// Attachment classes called by the main module script
kernel::autoload_classes();
// Attachment of main module script from the kernel course
kernel_include_script(kernel::urlCourse());
// Closing the kernel course
kernel::urlCourse_close();
// Getting the website responses
theme::content(dp_show_responses((defined("WEBSITE_RESPONSES")) ? constant("WEBSITE_RESPONSES") : 400));
// Output the manager html theme
if (!defined("WEBSITE_RESPONSES"))
    $kernel->manager_out();;
    $theme->init_php();
// Attachment of the resulting estimates of the used memory
$kernel->memory_use();
// Adding glueCode to index at theme output
kernel::glueCode("index.theme_echo");
kernel::set_header();
// Output the theme html
theme::theme_echo();
//  Adding glueCode to index at end
kernel::glueCode("index.end");
?>