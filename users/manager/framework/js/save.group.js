/**
 *  Dollop Save Group
 *  Creating jQuery plugin for filtering user's tables
 */
(function($) {

    $.fn.extend({
        savegroup: function(options) {
            options = $.extend( {}, $.savegroup.defaults, options );

            this.each(function() {
                new $.savegroup(this,options);
            });
            return this;
        }
    });

    // input is the element, options is the set of defaults + user options
    $.savegroup = function( input, options ) {
var IDtext = new Array();
            $(input).find('tr').each(function(i){

                if ($(this).is(":visible")){
                    if(options['elmntype'] == "text"){
                     IDtext[i] = $(this).find(options['elements']).text();
                    }else{
                     IDtext[i] = $(this).find(options['elements']).value();
                    }

                }
            } );

            $(options['outputin']).val(IDtext);
            $(options['formelmn']).submit();
        };
            // option defaults
        $.savegroup.defaults = {

            elements: "td:first",
            elmntype: "text",
            outputin: "saving",
            formelmn: null

        };
    })(jQuery);