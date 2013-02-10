jQuery(document).ready(function($){
//jQuery.noConflict();
$('.yoxview').yoxview();
});

$(document).ready(function() {
$(".news-dsc").hide();
$(".news").mouseenter(function(){
$(this).addClass("hovered-news");

$(".hovered-news .news-dsc").stop().slideDown(500);

return false;
}).mouseleave(function(){

    $(".hovered-news .news-dsc").stop().slideUp(500);
    
    $(this).removeClass("hovered-news");
    return false;
}).stop();



});