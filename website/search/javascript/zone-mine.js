$(function(){

        $(".open-search").click(function(){

            var getvalue = $(this).attr('rel');
            $('#'+ getvalue).addClass("animate");
            $('#'+ getvalue).slideToggle("slow",function(){
                $(this).removeClass("animate");
            });

            $('#allsearch').slideToggle("slow");

            });

    $(".close-button").click(function(){

        $(this).parent().slideToggle("slow");
        $('#allsearch').slideToggle("slow");

    })
    });