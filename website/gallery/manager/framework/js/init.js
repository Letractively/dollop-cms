$(document).ready(function(){
    $("#switchers").click(function(){
        $("#image").slideToggle("slow");
        $("#video").slideToggle("slow");
        $(".video-button").toggle("slow");
        $(elem).prop("disabled",!$(elem).prop("disabled"))
    }); 
    $(".tab-image").each(function(){
            $(".inform",this).click(function(){
        $(this).next().toggle("slow");
    });
            
    });


});