/**
 ============================================================
Last committed:     $Revision: 3 $
Last changed by:    $Author: fire1.A.Zaprianov@gmail.com $
Last changed date:    $Date: 2013-02-03 13:57:44 +0200 (íåä, 03 ôåâð 2013) $
ID:            $Id: superfish.process.js 3 2013-02-03 11:57:44Z fire1.A.Zaprianov@gmail.com $
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
 * Start with Configuration vars
 */
var notify_sound,messages_page,StatusAlert,MessageData;
function loadNotifyPlayer() {

    var audioPlayer = new Audio();
        
    audioPlayer.src=notify_sound;
    document.getElementById("notify-player").appendChild(audioPlayer);
    document.getElementById("notify-player").play();
}

function deliverMessageAlert(MessageData){
    if(!messages_page){
        messages_page ="/users/messages";
    }
    var a =window.confirm("You have "+MessageData+" massages! \n \n \t Click \"OK\" to go into your account's, INBOX!");
    if (a==true){             
        window.location = messages_page;        
    }
}
if(typeof $.mobile === "undefined"){        
    $(function(){
        $('ul.links').superfish();
    });
    $(document).ready(function(){


        $.ajax({
            type: "GET",
            url: '/users/datacheck',
            data:{
                'check_message':'1'
            },
            dataType:'text', 
            timeout: 300,
            success: function(data) {  
                MessageData = data;
                if(data >= 1){
                    if(!notify_sound){
                        var notify_sound = "/design/theme/misc/notify.mp3";
                    }
                    $('body').append('<audio id="notify-player" width="300" height="32" autoplay="autoplay" src="'+notify_sound+'" name="media" preload="auto" type="audio/mp3" style="display:none;"><source src="'+notify_sound+'" type="audio/mpeg" /> </audio>');
                    loadNotifyPlayer();
                   
                    setTimeout('deliverMessageAlert(MessageData)',1000);
                    
                }
            }
            
        });
    });
}else{    
  
    /*
 * sf-Touchscreen v1.0b - Provides touchscreen compatibility for the jQuery Superfish plugin.
 *
 * Developer's note:
 * Built as a part of the Superfish project for Drupal (http://drupal.org/project/superfish) 
 * Found any bug? have any cool ideas? contact me right away! http://drupal.org/user/619294/contact
 *
 * jQuery version: 1.3.x or higher.
 *
 * Dual licensed under the MIT and GPL licenses:
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 */          
    (function($){
        $.fn.sftouchscreen = function() {
            // Return original object to support chaining.
            return this.each( function() {
                // Select hyperlinks from parent menu items.
                $(this).find('li > ul').closest('li').children('a').each( function() {
                    var $item = $(this);
                    // No .toggle() here as it's not possible to reset it.
                    $item.click( function(event){
                        // Already clicked? proceed to the URI.
                        if ($item.hasClass('sf-clicked')) {
                            var $uri = $item.attr('href');
                            window.location = $uri;
                        }
                        else {
                            event.preventDefault();
                            $item.addClass('sf-clicked');
                        }
                    }).closest('li').mouseleave( function(){
                        // So, we reset everything.
                        $item.removeClass('sf-clicked');
                    });
                });
            });
        };       
    })(jQuery);
    $(function(){
        //
        // Super-Fishong with touchscreen
        //
        $('ul.links').superfish();
        $('ul.links').sftouchscreen();

        //
        // This will fix bigger images
        var $screenw = $(window).width();
        $('img').each( function() {
            var $item = $(this);
            if($item.width() > $screenw){
                $item.css({
                    width:$screenw -50,
                    height:"auto"
                });
            }

        });
      
    // -
    // This will fix links 


    });
    // fixin ajaj load 
    $(document).bind("mobileinit", function () {
        $.mobile.ajaxEnabled = false;
    });
    
    
}