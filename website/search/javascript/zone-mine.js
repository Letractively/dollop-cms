$(function(){

        $(".open-search").click(function(){
            var getvalue = $(this).attr('rel');
            $('#'+ getvalue).slideToggle("slow");

            $('#allsearch').slideToggle("slow");
            
            });
 
    $(".close-button").click(function(){
        
        $(this).parent().slideToggle("slow");
        $('#allsearch').slideToggle("slow");

    })
    });