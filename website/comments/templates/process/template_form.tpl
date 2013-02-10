<form method="post" action="{ACT}" id="insert_comments" name="insert_comments">
 <input type="hidden" name="comment_hash" value="{HASH}" />
 <input type="hidden" name="uri_request" value="{URI}" />
 <table width="95%" align="center" border="0">
    <tr>
    <td> <br />
    <label for="error_log"><h3>{TTL_CMM}</h3><small>{DES_CMM}</small>
        <center>
    <textarea name="body" id="comment-textarea" rows="3"></textarea>
    </center>
    </label>
     
    </td>
    </tr>
    <tr>
    <td align="right">
    <img src="{LOAD}" border="0" id="loader"/>
    <input type="submit" name="submit" value="{SUBMIT}">

       </td>
    </tr>
    </table>  
</form><br /><br />