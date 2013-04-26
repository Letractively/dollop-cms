    <link rel="stylesheet" href="{dir}/lib/codemirror.css">
    <script src="{dir}/lib/codemirror.js"></script>
    <script src="{dir}/lib/util/simple-hint.js"></script>
    <link rel="stylesheet" href="{dir}/lib/util/simple-hint.css">
    <script src="{dir}/lib/util/javascript-hint.js"></script>
    <script src="{dir}/mode/javascript/javascript.js"></script>


<textarea id="{id_area}" name="{name}" {style} >{text}</textarea>
<!--~ JS ~-->
    <script type="text/javascript">
      var editor = CodeMirror.fromTextArea(document.getElementById("{id_area}"), {
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        mode: "javascript",
        indentUnit: 3,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        extraKeys: {"Ctrl-Space": function(cm) {CodeMirror.simpleHint(cm, CodeMirror.javascriptHint);}}
      });

    </script>