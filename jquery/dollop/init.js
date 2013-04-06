/* Dollop JavaScript attachments v1.0 */
var notify_sound,messages_page,StatusAlert,MessageData,eFire=0,dbug;
dbug=true;
function dbg(t){if(dbug){console.log(t)}}
/*$_GET...*/
function $_get(name) { return decodeURI ( (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]);}
/* deliver Message Sound Alert in Dollop */
function loadNotifyPlayer() {var audioPlayer = new Audio(); audioPlayer.src=notify_sound; document.getElementById("notify-player").appendChild(audioPlayer);document.getElementById("notify-player").play();}
/* deliver Message Alert in Dollop */
function deliverMessageAlert(MessageData){
    if(!messages_page){ messages_page ="/users/messages"; }
    dbg("messages_page is: "+messages_page);
    var a =window.confirm("You have "+MessageData+" massages! \n \n \t Click \"OK\" to go into your account's, INBOX!");
    if (a==true){window.location = messages_page;}}
/* Get last messages */
function lastmessage(){
    if (eFire >= 1) {return false;}
    $.ajax({ type: "GET",url: '/users/src/datacheck.php',
        data:{'check_message':'1'},dataType:'text',timeout: 204,
        success: function(data) { MessageData = data;
            dbg("count messages is: "+data);
                if(data >= 1){ 
                    if(!notify_sound){var notify_sound = "/design/theme/misc/notify.mp3";}
                    $('body').append('<audio id="notify-player" width="300" height="32" autoplay="autoplay" src="'+notify_sound+'" name="media" preload="auto" type="audio/mp3" style="display:none;"><source src="'+notify_sound+'" type="audio/mpeg" /> </audio>');
                    loadNotifyPlayer();
                    setTimeout('deliverMessageAlert(MessageData)',500);               }}}); return true;}
/* Run Te processes */
$(document).ready(function(){

 lastmessage();

 eFire++;
 dbg("script looped: "+eFire);

});


