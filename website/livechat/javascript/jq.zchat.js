/*
Created by: Kenrick Beckett

Name: Chat Engine
*/

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
        );
}
function FirstViewScroll(){
    var gatBottom =true;
window.setTimeout(function() {
    $('.lc-messages').on('scroll',function () {
         gatBottom =false;
    });
    if (gatBottom) $('.lc-messages').animate({scrollTop:$('#live_message').height()},10000,function(){

        gatBottom =false;
    });
    }, 4000);
}



function GetNewChatMessage() {


		$.ajax({
			url: "process?id="+ getURLParameter("id"),
			cache: true,
			success: function(html){
                            var atBottom = ( $('.lc-messages').scrollTop() == $('#live_message').height() - $('.lc-messages').height() );
				$("#live_message").html(html); //Insert chat log into the #chatbox div
                                if (atBottom) $('.lc-messages').scrollTop($('#live_message').height());
		  	}
		});
/*
    var atBottom = ( $('.lc-messages').scrollTop() == $('#live_message').height() - $('.lc-messages').height() );
    $("#live_message").load("process?id="+ getURLParameter("id"), function(response) {
        var elem = $(this);
        /*console.log( $('.lc-messages').scrollTop() +"=="+ $('#live_message').height() +"-"+ $('.lc-messages').height() )
        /*console.log(atBottom);
        if (atBottom) $('.lc-messages').scrollTop($('#live_message').height());
    } );

    */
    window.setTimeout(function() {
        GetNewChatMessage();
    }, 1000);
}



function ins2pos(str, id) {
   var TextArea = document.getElementById(id);
   var val = TextArea.value;
   var before = val.substring(0, TextArea.selectionStart);
   var after = val.substring(TextArea.selectionEnd, val.length);
   TextArea.value = before +" "+ str +" "+ after;
}

$(document).ready(function() {
FirstViewScroll();

$('#chat_emotions a').click(function(event) {
    event.preventDefault();
    var smiley = $(this).attr('title');
    ins2pos(smiley, 'chat_message');
});

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

    $(".open_sub_emotions,.close_sub_emotions").click(function(){
        $('.sub_emotions').prependTo('#sub_emotions');
        $(".sub_emotions").slideToggle("slow");
    });


});