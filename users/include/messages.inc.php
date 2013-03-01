<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author fire1
 */
function user_lister() {
    if(!defined("USER_ID"))return false;
    if (!session_id()) {
        session_start();
    }global $language;
    $_SESSION["datacheck"] = kernel::uni_session_name();
    $request = kernel::base_tag("/{module_dir}datacheck");



    $js = <<<eol
       $("#user_list").autocomplete({
            minLength: 0,
           source: function (request, response) {
            $.ajax({
                url: "$request",
                dataType: "json",
                data: {
                    "user_list": request.term

                },
                success: function (data) {
                    response(data);
                }
            });
        },
            focus: function( event, ui ) {
                $( "#user_list" ).val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {

                $( "#user-id" ).val( ui.item.id );
                $( "#user-uname" ).html( ui.item.uname );
                $( "#na_receiver" ).val( ui.item.uname );
                $( "#user-fname" ).html( ui.item.fname );
                $( "#user-icon" ).attr( "src", ui.item.icon );

                return false;
            }
        })
        .data( "autocomplete" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                .data( "item.autocomplete", item )
                .append( "<a>" + item.uname + "</a> ")
                .appendTo( ul );
        };

/*
       $("#user_list").autocomplete({
            minLength: 0,
            source: "{$request}"+ $("#user_list").val(),
            focus: function( event, ui ) {
                $( "#user_list" ).val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {

                $( "#user-id" ).val( ui.item.id );
                $( "#user-uname" ).html( ui.item.uname );
                $( "#na_receiver" ).val( ui.item.uname );
                $( "#user-fname" ).html( ui.item.fname );
                $( "#user-icon" ).attr( "src", ui.item.icon );

                return false;
            }
        })
        .data( "autocomplete" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                .data( "item.autocomplete", item )
                .append( "<a>" + item.uname + "</a> ")
                .appendTo( ul );
        };
*/

eol;

    global $kernel;
    $kernel->external_file("jquery", $js);
    $input = html_form_input("text", "user_list", null, 'id="user_list" size="20" style="width:75%;"');
    global $language;
    $pr_image = kernel::base_tag("/{module_dir}/images/stamp-print.png");
    $def_image = kernel::base_tag("/{module_dir}/images/stamp.png");
    return <<<eol
<div class="ui-widget" style="min-height:180px;" id="widget-select-user">
<div id="user-label">{$language['users.messages.select']}</div>
<img src="{$pr_image}" class="ui-state-default" alt="stamp-print"
    style="background:none;
    border:none;
    box-shadow:none;
    float:left;
    z-index:2;
    position:absolute;" />

<img id="user-icon" src="{$def_image}" class="ui-state-default" alt=""  style="z-index:1;float:left;margin:15px;margin-left:35px;"/>
{$input}
<input type="hidden" id="user-id" name="id_receiver" value=""/>
<input type="hidden" id="na_receiver" name="na_receiver" value=""/>
<p id="user-uname"></p>
<p id="user-fname"></p>
<p><label for="priority">{$language['users.messages.mesimp']}</label> <input type="checkbox" name="priority"  value="1"  /></p>

   </div>
eol;
}

function datacheck_user_list() {
    if(!defined("USER_ID"))return false;
    header_remove("content-type");
    header("content-type: application/json;");
    $un = propc("users.sqltbl.col.uname");
    $id = propc("users.sqltbl.col.uid");
    $fn = propc("users.sqltbl.col.fname");
    $pi = propc("users.sqltbl.col.avatar");
    $term = mysql_real_escape_string($_GET['user_list']);
    $sql = "SELECT {$un},{$id},{$pi},{$fn} FROM " . constant("USERS_SQLTBL_MAIN") . " WHERE  {$un} LIKE('{$term}%') ";
    $res = mysql_query($sql);
    $GLOBALS['THEME'] = null;
    $return = array();
    while ($r = mysql_fetch_array($res)) {
        $images = kernel::base_tag("{publicfiles}{module_dir}{$r[$un]}/{thumbs}".$r[$pi]);
        if(file_exists($images)){
            $img ="/". $images;
        }else{
            $img = kernel::base_tag("{host}{design}users/thumbs/dp4-noavatar.png");
        }
        $return[] = array(
            "id" => $r[$id],
            "uname" => $r[$un],
            "fname" => (empty($r[$fn]))?"":$r[$fn],
            "icon" => $img,
            );
    }
    if (!empty($return)) {

        $GLOBALS['THEME'] = json_encode($return);
    }
}
function datacheck_messages(){

 if(!defined("USER_ID"))return false;
    header_remove("content-type");
    if(!(bool)session_id()){
        session_start();
    }
       if(!isset($_SESSION['datacheck_messages'])){
           $_SESSION['datacheck_messages']=false;
       }
 $uid=constant("USER_ID");
    $sql = "SELECT `ID` FROM " .PREFIX. "messages WHERE  `id_receiver`='{$uid}' AND `readed`=1 ";
    $result = mysql_query($sql);
    $num =  mysql_num_rows($result);
    if($num == "0" OR $_SESSION['datacheck_messages'] == $num){
        header('HTTP/1.0 204 No Content', true, 204);
    }elseIf($_SESSION['datacheck_messages'] <= $num){
        header("content-type: text/plain;");
        $GLOBALS['THEME'] =$num;
        $_SESSION['datacheck_messages']=$num;
    }else{
       $GLOBALS['THEME'] =0;
    }

}
