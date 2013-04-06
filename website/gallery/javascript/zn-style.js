$(function(){
    $(window).bind('hashchange', function () { //detect hash change
        var hash = window.location.hash.slice(1); //hash to string (= "myanchor")
        $(".opened").slideToggle("slow");
        $(".gallery.content").removeClass("opened");
        $("#"+hash).slideToggle("slow",function(){
            $(this).addClass("opened");
            $("html, body").animate({ scrollTop: $("#gallery-scroll-content").height() }, 1000);
            });
    });
});

$(document).ready(function() {
    var open = window.location.hash.slice(1);
    if(open){
        $("#"+open).show();
        $("#"+open).addClass("opened");

    }

});
