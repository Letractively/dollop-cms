/**
*
    Spare Part Dollop template Tabs JScript
        This theme is not provided from web site designer.
        - It may cause improper viewing web pages.
        - Revision:
         Last committed:        $Revision: 119 $
         Last changed by:       $Author: fire $
         Last changed date:     $Date: 2013-02-22 16:58:55 +0200 (ïåò, 22 ôåâð 2013) $
         ID:                    $Id: template_body.tpl 119 2013-02-22 14:58:55Z fire $
*       See COPYRIGHT and LICENSE
*/


    $( "{TAB_NAME}" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.error(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
            "If this wouldn't be a demo." );
        });
      }
    });
