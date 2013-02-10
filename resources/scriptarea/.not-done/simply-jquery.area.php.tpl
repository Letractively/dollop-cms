  
  
  
<textarea id="{id_area}" name="{name}">{text}</textarea>
    
     <script type="text/javascript">   
    $(document).ready(function() {
        function split( val ) {
            return val.split( "/[\W0-9;]+/"  );
        };
        function extractLast( term ) {
            return split( term ).pop();
        };
  
      $("{id_area}")

            .bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                        $( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function( request, response ) {
                $.getJSON( "{host}/panel" , { 
                        '{panel_hash}':'{panel_target}',
                        type:'{type}',
                        term: extractLast( request.term )

                    }, response );
                },
                search: function() {
                
                var Content = this.value;
                
                    var term = extractLast( this.value );
                    if ( term.length < 3 ) {
                        return false;
                    }
                },
                focus: function() {

                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );

                  terms.pop();

                    terms.push( ui.item.value );

                    terms.push( "" );
                    this.value = terms.join( " " );
                    return false;
                }
            });


  });
      