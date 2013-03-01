<script type="text/javascript">
<!-- advanced textareas simple -->
    tinyMCE.init({
        // General options
        mode : "exact",
        elements : "{elment_area}",
        theme : "{theme}",
        skin : "o2k7",

        auto_cleanup_word : false,
        relative_urls : false,
        remove_script_host : true,
         verify_html : true,
        document_base_url : "/",
        plugins : "preelementfix,safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        preelementfix_css_aliases: {
            'C++': 'cpp',
            'C#': 'csharp',
            'Delphi': 'delphi',
            'HTML': 'html',
            'Java': 'java',
            'Java Script': 'javascript',
            'PHP': 'php',
            'Python': 'python',
            'Ruby': 'ruby',
            'Sql': 'sql',
            'VB': 'vb',
            'XHTML': 'xhtml',
            'XML': 'xml'
         },
        // Example content CSS (should be your site CSS)
        content_css : "{theme_dir}/style.css",

        
        // Drop lists for link/image/media/template dialogs
        
            //   template_external_list_url : "lists/template_list.js",
            //    external_link_list_url : "lists/link_list.js",
            //    external_image_list_url : "lists/image_list.js",
            //    media_external_list_url : "lists/media_list.js",




        // Replace values for the template plugin
        template_replace_values : {
            username :     "{username}",
            staffid :     "{usersess}"
        }
    });
</script>
<!-- /TinyMCE -->


<textarea id="{elment_area}" name="{name}" {class} rows="{rows}" cols="{cols}" style="{width}{height}">{text}</textarea>