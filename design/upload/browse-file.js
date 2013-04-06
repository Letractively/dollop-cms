

$(document).ready(function() {

var i =0;
var eff = 250;
$("#browse-files-container .browse-file").hide();
$('#browse-files').click(function(){
$("#browse-files-container").show(200);
$("#browse-files-container").delay(200).find('.browse-file').each(function (i) {
                $(this).delay( (i*2)*100 ).show(200,function(){
                    $(this).css('display', 'inline-block');
                });
              $(this, "input[type=text]").val($(this + "input[type=text]").val());
            });
});
$("#browse-files-container").find("input[type=text]").each(function (i) {
$(this).click (function(){
    this.select();
});
});

$('.browse-colse').click(function(){
$("#browse-files-container").find('.browse-file').each(function (i) {
    $(this).delay( (i*2)*100 ).hide(400);
    });
$("#browse-files-container").delay( 500 ).hide(1500);
});

$('.browse-file img').mousedown(function(){
    $(this).css({"cursor":" url(https://mail.google.com/mail/images/2/closedhand.cur) 8 8, move"});

});
$('.browse-file .show-big').click(function(){
    $(".browse-file-image").hide(400);
    $(this).parent().parent().find(".browse-file-image").show(400);
});

});