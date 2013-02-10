$(document).ready(function() { 
var i =0;
var eff = 100;
$('#browse-files').click(function(){
$("#browse-files-container").show(200);

$("#browse-files-container").find('#browse-file').each(function (i) {
                    
                $(this).delay( (i*2)*100 ).show(400);
              
              $(this + "input[type=text]").val($(this + "input[type=text]").val());
                
            });
});

$("#browse-files-container") .find("input[type=text]").each(function (i) {

$(this).click (function(){



    this.select();
});

});



$('.browse-file.colse').click(function(){

$("#browse-files-container").find('#browse-file').each(function (i) {

    $(this).delay( (i*2)*100 ).hide(400);
    });
    
$("#browse-files-container").delay( 500 ).hide(1500);
});


});