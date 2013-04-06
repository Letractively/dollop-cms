/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(function(){
    $("#bookmark-mass_mail-save-mail-button").click(function(){
        $("#UserTo").val(  $("#mail-list").val() + "," + $("#other-mails").val());
        $("#UserFrom").val( $("#from").val());
        $("#Subject").val( $("#title-mail").val());
        $("#Subject").val( $("#title-mail").val());
        $("#mail_id").val( $("#theme_mail_id").val());
        $("#Message").val( $("textarea[name='mailbody']").val());



    });


});