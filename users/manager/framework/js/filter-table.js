/**
 *  Dollop table filter
 *  Creating jQuery plugin for filtering user's tables
 */
(function($) {
    $.fn.extend({
        filtertbl: function(options) {
            options = $.extend( {}, $.filterTable.defaults, options );

            this.each(function() {
                new $.filterTable(this,options);
            });
            return this;
        }
    });

    // input is the element, options is the set of defaults + user options
    $.filterTable = function( input, options ) {
        $(input).keyup(function(){
            
            //hide all the rows
            $(options['table']).find("tr").hide();
            //split the current value of searchInput
            var data = this.value.split(" ");
            //create a jquery object of the rows
            var jo = $(options['table']).find("tr");
            //Recursively filter the jquery object to get results.
            $.each(data, function(i, v){
                jo = jo.filter("*:contains('"+v+"')");
            });
            //show the rows that match.
            jo.show();
        //Removes the placeholder text
        }).focus(function(){
            this.value="";
            $(this).css({
                "color":options['color_b']
                });
            $(this).unbind('focus');
        }).css({
            "color":options['color_a']
            });
    };

    // option defaults
    $.filterTable.defaults = {
        table: "#list",
        color_a: "#C0C0C0",
        color_b: "black"
    };

})(jQuery);





