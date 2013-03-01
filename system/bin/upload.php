<?php

/**
  ============================================================
 * Last committed:      $Revision: 115 $
 * Last changed by:     $Author: fire $
 * Last changed date:   $Date: 2013-02-08 18:27:29 +0200 (ïåò, 08 ôåâð 2013) $
 * ID:                  $Id: upload.php 115 2013-02-08 16:27:29Z fire $
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
    exit("<div style='background-color: #FFAAAA; '> error..1001</div>");
}

/**
 *
 * @filesource
 * Source file for website uploads
 */
class upload extends kernel {

    private $kernel_prop = array();
    private $upload_prop = array();
    private $source_prop = array();
    private $conf = array();
    private $unisession = false;
    private $src_folder = null;
    private $upload_folder = null;
    private $execute_as = null;

    public function uploader() {
        // Attaching Configurations and fill own class vars
        if ((bool) $this->configuration()) {
            if ((bool) $this->unisession) {
                $GLOBALS['THEME'] = "";
                global $dir, $fileTypes, $uploadFolder, $requestTarget, $build, $jquery, $style, $build_prop, $upload_prop;
                $request = parse_url(request_uri());
                $fileTypes = array();
                $build = array();
                $fileTypes = $this->source_prop['filetypes'];
                $uploadFolder = $this->conf['publicfiles'] . $this->get_folder();
                $requestTarget = kernel::base_tag_folder_filter('{host}' . $request['path'] . "?{$this->unisession}=process&{$request['query']}");
                $build = $this->source_prop;
                $dir = kernel::base_tag_folder_filter("{host}{$this->src_folder}/{$this->upload_prop['upload.active']}");
                $jquery = kernel::base_tag_folder_filter("{host}{$this->upload_prop['upload.jquery']}");
                $style = kernel::base_tag_folder_filter("{host}{$this->upload_prop['upload.style']}");
                $build_prop = $this->source_prop;
                $upload_prop = $this->upload_prop;
                switch ($_GET[$this->unisession]) {
                    default:
                        if (defined("USER_PRIV")) {
                            if ($this->upload_prop['upload.allow.lvl'] <= constant("USER_PRIV")) {
                                return $this->attach_sourceCode($this->upload_prop['upload.source.key']);
                            }
                        } else {
                            global $language;
                            $responce = (dp_show_responses(401, $language['lan.need.users']));
                            echo " <b>{$responce['0']}</b> <br /> {$responce['1']}";
                            return false;
                        }
                        break;
                    case 'process':
                        if ($this->upload_prop['upload.allow.lvl'] <= constant("USER_PRIV")) {
                            return $this->attach_sourceCode($this->upload_prop['upload.process.key']);
                        } else {
                            global $language;
                            theme::content(dp_show_responses(401, $language['lan.need.users']));
                        }
                        break;
                }
            } else {
                theme::content(dp_show_responses(405, 'You do not have rights to upload!'));
            }
        }
    }

    /**
     * Attaching Configurations and
     * fill upload class vars with established configuration
     *
     */
    public function __construct() {
        global $CONFIGURATION, $KERNEL_PROP_MAIN;
        if (!is_array($CONFIGURATION) OR !is_array($KERNEL_PROP_MAIN))
            return false;;
        $this->src_folder = $CONFIGURATION['filemanager'];
        $this->upload_folder = $CONFIGURATION['publicfiles'];
        $this->kernel_prop = $KERNEL_PROP_MAIN;
        $this->upload_prop = kernel::loadprop(__FILE__, true);
        $boot_th = kernel::base_tag_folder("{thumbs}");
        $boot_im = kernel::base_tag_folder("{images}");
        if (!empty($boot_th)) {
            $this->upload_prop['upload.thumb.folder'] = $boot_th;
        }
        if (!empty($boot_im)) {
            $this->upload_prop['upload.image.folder'] = $boot_im;
        }
        $this->source_prop = kernel::loadprop(ROOT . $this->src_folder . $this->kernel_prop['kernel.urlCourse.configProp'], true);
        $this->conf = $CONFIGURATION;
        $this->unisession = kernel::uni_session_name();
        if (!isset($this->unisession))
            return false;;
        return true;
    }

    private function get_folder() {
        $target = $_GET[$this->upload_prop['upload.request.get_folder']] . "/";
        if (empty($target))
            return null;;
        kernel::mkdirTree($target);
        $thf = $this->conf['publicfiles'] . $target . $this->upload_prop['upload.thumb.folder'];
        $imf = $this->conf['publicfiles'] . $target . $this->upload_prop['upload.image.folder'];
        if (!is_dir($thf)) {
            kernel::mkdirTree($thf);
        }
        if (!is_dir($imf)) {
            kernel::mkdirTree($imf);
        }
        return $target;
    }

    private function attach_sourceCode($key) {
        $exec = $_GET[$this->upload_prop['upload.request.sub_exec']];
        return (ROOT . $this->src_folder . $this->upload_prop['upload.active'] . "." . $key . "." . $exec . $this->upload_prop['upload.source.ext']);
    }

}

$upload = new upload;
kernel_include_script($upload->uploader());
