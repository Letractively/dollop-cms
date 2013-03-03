/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function($){
    if(typeof $.mobile === "undefined"){
        var $screenw = $(window).width();
        $('img').each( function() {
            var $item = $(this);
            if($item.width() > $screenw){
                $item.css({
                    width:$screenw -50,
                    height:"auto"
                });
            }

        });
    }
});


