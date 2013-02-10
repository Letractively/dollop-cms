/* 
Created by: Kenrick Beckett

Name: Chat Engine
*/




function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}
      function GetNewChatMessage() {
                $("#live_message").load("process?id="+ getURLParameter("id"), function() {
             
                
                 
                  
                 
                } );
                $(".lc-messages").animate({ scrollTop: $('#live_message').height()}, 1000);
                window.setTimeout(function() {
                    GetNewChatMessage();
 
                   
                   
                }, 500);
                
                
            }
            
$(document).ready(function() {

GetNewChatMessage();

    $('#chat_message').keypress(function(e) {
    
    if (e.keyCode != 13  ) return;
    
    if (e.keyCode == 13 && !e.shiftKey){
      event.preventDefault();
      
    var msg = $("#chat_message").val();
    
    if (msg)
    {
        $('#chat_button').focus().click();
        $("#chat_message").val("");
         $('#chat_message').focus();
         
         GetNewChatMessage();
    }
    
    
    return false;
    }
    });
    

    $('#chat_content_form').ajaxForm(function() { 
    
               
            
            }); 
            
$('#chat_message').autogrow();

});