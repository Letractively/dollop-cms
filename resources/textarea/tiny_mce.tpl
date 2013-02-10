

<!-- TinyMCE -->
<script type="text/javascript">
//<![CDATA[
tinyMCE.init({
    mode : "exact",
    elements: "{name}",
    theme : "advanced", 
        skin : "o2k7",
skin_variant : "silver",

theme_advanced_buttons1 : "pre,|,visualchars,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright",
theme_advanced_buttons2 : "justifyfull,code,emotions,bullist,|,numlist,undo,redo,link,unlink,iespell",
theme_advanced_buttons3 : "styleselect,formatselect,undo,redo,fullscreen",
content_css : "design/css/style_1.css",
content_css : "{theme_dir}/style.css",

plugins : "pre,emotions,iespell,fullscreen,iespell,visualchars,inlinepopups",


});

function ajaxSave() {
    var ed = tinyMCE.get('content');

    // Do you ajax call here, window.setTimeout fakes ajax call
    ed.setProgressState(1); // Show progress
    window.setTimeout(function() {
        ed.setProgressState(0); // Hide progress
        alert(ed.getContent());
    }, 3000);
}


//]]>
</script>
<!-- /TinyMCE -->

<textarea id="{name}" name="{name}" rows="{rows}" cols="{cols}" style="{width}{height}">{text}</textarea>