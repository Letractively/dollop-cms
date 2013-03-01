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


    $("#text_only").keyup(function (e) { 
    if (this.value.match(/[^a-zA-Z0-9_]/g)) {
    this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
    }
    });



});