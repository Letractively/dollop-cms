<?php
    #$Id: dollop 4
    /**

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

    See COPYRIGHT and LICENSE

    */
    if (!defined('FIRE1_INIT')) { exit("<div style='background-color: #FFAAAA; '> error..1001</div>"); }
    /**
    * 
    * @filesource
    * Issues function libs
    */


    /**
    *  Return categories
    *  $category var category
    *
    **/


    class comments{

        private         $db         = object;
        private         $request;
        private         $output;



        /**
        * Contruction Comments for section
        * 
        * @param mixed $db
        * @param mixed $request_uri
        * @param mixed $sec
        * @return comments
        */
        function  __construct($db,$request_uri=null,$sec=false){


            $this->db=$db;

            $this->output    = array();

            $this->request($request_uri);

            $this->admin();

            $this->insert();

            $this->output[] = ($this->usr()) ? $this->form() :
            '<a href="'.kernel::base_tag('{users}/main?callback=' ).$this->request.'">'.
            $this->msg('md.usr.loglnk')."</a> ".$this->msg('md.usr.logtxt');

            $this->show();



        }

        /**
        * Admin mysql process
        * 
        */
        private function admin(){
            if(defined("CPANEL")){

                if($_POST['cpanel'] != constant("CPANEL")) return false;

                switch($_POST['c']){

                    case 'erase':

                        $this->db->Delete("comments_content",array("ID"=>$_POST['id']));

                        break;
                        
                    case 'censor':
                        global $language;
           $this->db->Update("comments_content",array("body"=>"[".$language['md.censorship'].USER_PRIV_NAME." ]"),array("ID"=>$_POST['id']));
                    
                    break;


                }


            }


        }

        /**
        * Retrun admin form
        * 
        */
        private function admin_form($id){
        
        if(!defined('CPANEL')) return null;
        
        $cpanel=constant("CPANEL");
        
        $action = kernel::base_tag("/{module_dir}/process");
        $imgdir = kernel::base_tag("/{module_dir}/img/");
        
            $html = <<<eol
            <!-- // Admin prc // -->
                <form method="post" action="{$action}" id="insert_comments" name="admin-{$id}-erase" style="float:left;margin-right:10px;margin-left:10px;">
                    <input type="hidden" name="c" value="erase" />
                    <input type="hidden" name="id" value="{$id}" />
                    <input type="hidden" name="cpanel" value="{$cpanel}" />
                    <input type="image"  src="{$imgdir}erase.png" value="erase" height="12px" />
                </form>
                <form method="post" action="{$action}" id="insert_comments" name="admin-{$id}-censor" style="float:left;margin-right:10px;margin-left:10px;">
                    <input type="hidden" name="c" value="censor" />
                    <input type="hidden" name="id" value="{$id}" />
                    <input type="hidden" name="cpanel" value="{$cpanel}" />
                    <input type="image"  src="{$imgdir}censor.png" value="censor" height="12px" />
                </form>
            <!-- // Admin prc // -->
eol;
        
        return $html;
        }
        
        /**
        * Showint comments with json filter
        * 
        */
        private function show(){

            global $language;
            $arr = array();

            $res =  mysql_query("


                SELECT `picture`,
                `user_id`,
                `user_na`,
                `body`,
                `dates`,
                `ID`
                 FROM ".USERS_SQLTBL_MAIN." 

                INNER   JOIN `".PREFIX."comments_content` ON

                ".PREFIX."comments_content.`user_id`=`".USERS_SQLTBL_MAIN."`.`userid` 

                WHERE uri_request='{$this->request}'

                ORDER BY `".PREFIX."comments_content`.`ID` DESC;

            ") or die(mysql_error());



            if((bool)$res) { 
                $i=0;
                while( $r = mysql_fetch_array($res)){
                    /*
                    $arr['user_na']     = $row['user_na'];
                    $arr['user_id']     = $row['user_id'];
                    $arr['dates']       = $row['dates'];
                    $arr['body']     = $row['body'];
                    */
                    $image = kernel::base_tag("/{publicfiles}users/{$r['user_na']}/{thumbs}/".$r['picture']);

                    if(!file_exists(ROOT.$image)){
                        $image = kernel::base_tag("/{design}/users/{thumbs}/dp4-noavatar.png");
                    }
                    $first=" ";
                    if($i ==0){$first='class="first"';}

                   
                    $row['first']          = ($i ==0) ? 'class="first"': " " ;
                    $row['achor']          = "<a name=\"{$row['ID']}\" ></a>";
                    $row['user_na']     = $r['user_na'];
                    $row['user_pi']     = $image;
                    $row['user_id']     = $r['user_id'];
                    $row['body']        = str_replace('\n','<br />' ,nl2br( stripslashes($r['body']) )  );
                    $row['dates']       = $this->admin_form($r['ID']).$r['dates'];
                    $row['ID']          = "";
                    $row['ttl_da']      = $language['md.ttl.da'];
                    $this->output[] =  theme::custom_template("comments",$row) ;

                    $i++;  }

            }else{

                $this->output[] = '<p align="center">' . $this->msg('md.empty.comm') .'</p>';

            }



        }

        /**
        * Filter html text
        * 
        * @param mixed $var
        */
        private function html_filter($var){

            $fltr = array("\n","\t");
            $repl = array("","");

            return str_replace($fltr,$repl,$var);


        }

        /**
        * Set-up request in classs
        * 
        * @param mixed $request request uri
        */
        private function request($request=null){

            if(is_null($request)){

                $this->request = request_uri();

            }else{

                $this->request = $request;

            }


        }

        /**
        * put your comment there...
        * 
        */
        private function msg($k){
            global $language;

            return $language[$k];


        }

        /**
        * Check for loged-in user
        * 
        */
        private function usr(){

            if(defined("USER_ID")){ return true;}else{return false;}

        } 

        /**
        * Creating Form for comments
        * 
        * @param mixed $sec
        */
        private function form($sec=false){

            if(!$this->usr()) return false;

            $_SESSION['hashoem'] = uniqid();

            global $language;

            $arr= array();
            $loadimg =kernel::base_tag("/{module_dir}/img/ajax-loader.gif");


            $arr['act'] = kernel::base_tag("/{module_dir}/process?type=html&url=".$this->request);
            $arr['uri'] = $this->request;
            $arr['ttl_cmm']=$language['md.com.title'];
            $arr['des_cmm']=$language['md.com.descr'];
            $arr['submit']=$language['lan.submit'];
            $arr['hash']=$_SESSION['hashoem'];
            $arr['load']=kernel::base_tag("/{module_dir}/img/ajax-loader.gif");

            return theme::custom_template("form",$arr);


        }


        public function insert(){

            if(empty($_POST['comment_hash'])) return false;

            // if($_SESSION['hashoem'] != $_POST['comment_hash']) return false;

 

            $_POST['user_na']=USER_NAME;
            $_POST['user_id']=USER_ID;
            $_POST['user_pi']="-";
            $_POST['body']=(htmlspecialchars( $_POST['body'] ));

            $this->db->Insert($_POST,"comments_content",array("comment_hash","submit"));

            $_SESSION['hashoem']="";


        }

        private function usr_pi(){
            $this->db->aArrayedResults="";
            $this->db->Select("users",Array("userid"=>USER_ID));
            echo mysql_error();
            return $this->db->aArrayedResults[0]['picture'];

        }

        /**
        * Summary
        * @param string $str text string
        * @return string   formated text
        */
        private static function validate_text($str)
        {
            /*
            /	This method is used internally as a FILTER_CALLBACK
            */


            if(mb_strlen($str,kernel::base_tag("{sqlcharset}"))<1)
                return false;

            // Encode all html special characters (<, >, ", & .. etc) and convert
            // the new line characters to <br> tags:

            $str = nl2br(htmlspecialchars($str));

            // Remove the new line characters that are left
            $str = str_replace(array(chr(10),chr(13)),'',$str);

            return $str;
        }

        /**
        * Return data formated for type
        * 
        * @param mixed $type html/json
        */
        public function output($type="html"){

            switch($type){

                case "html":

                    return implode(" ",$this->output);

                    break;


                case "json":

                    return json_encode($this->output);

                    break;


            }

        }

    }














