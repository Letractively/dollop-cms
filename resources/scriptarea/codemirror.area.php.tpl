

    <link rel="stylesheet" href="{dir}/lib/codemirror.css">
    <script src="{dir}/lib/codemirror.js"></script>
    <script src="{dir}/mode/xml/xml.js"></script>
    <script src="{dir}/mode/javascript/javascript.js"></script>
    <script src="{dir}/mode/css/css.js"></script>
    <script src="{dir}/mode/clike/clike.js"></script>
    <script src="{dir}/mode/php/php.js"></script>
    
    
<textarea id="{id_area}" name="{name}" {style}>{text}</textarea>
<!--~ JS ~-->
    <script type="text/javascript">
       var editor = CodeMirror.fromTextArea(document.getElementById("{id_area}"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "text/x-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift"

      });   
    
  
    </script>