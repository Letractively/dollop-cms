<!--//[users template entry]
    * this template is content for entry of users
    * & JS effects
    //-->

<script type="text/javascript">

 $(document).ready(function() {

active ="#"+ window.location.hash.substring( 1 );
  $("#mainLogin").accordion({
   animated: 'bounceslide',
        header: "h2",
        heightStyle: "content",
        navigation: true,
        collapsible: true
        });

 $(".users-head-entry").click(function(){

 $(this).addClass("accordion-content-active");
 });

 });

 //var hash = window.location.hash.substring( 1 );
</script>

<div id="mainLogin">
<h2 class="users-head-entry" id="social"><a href="#social">{USRSOCIAL}</a></h2>
<div class="user-form-opened"> {SOCIAL}</div>

<h2 class="users-head-entry" id="login"><a href="#login">{USERSLOG}</a> </h2>
<div >
<form  method="post" action="{TARGET}#login" class="user-form">
    <table border="0" cellpadding="2" cellspacing="5" width="80%" align="center" >

    <tr>
         <td>   {SIGNINERR}  </td>
    </tr>
    <tr>
        <td>{USERSLOGNAME}</td>
    </tr>
    <tr>
        <td><input type="text" name="username" value="" /> </td>
    </tr>

    <tr>
        <td>{USERSREGPASS}</td>
    </tr>

    <tr>
        <td><input type="password" name="password" value="" /></td>
    </tr>

    <tr>
    <td> &nbsp;</td>
    </tr>

    <tr>
      <td>
       <label style="cursor:pointer;">
      <input type="checkbox" name="remember" value="true" align="left"> {USERS_REMEBER}
      </label>
      </td>
    </tr>

    <tr>
      <td><a href="forgotpass"> {USERS_FORGOT} </a> </td>
    </tr>

    <tr>
    <td> &nbsp; </td>
    </tr>

    <tr>
       <td>
          <input type="submit" name="signin" value="{USERS_B_R0G}">

       </td>
        </tr>
   </table>
</form></div>


<h2 class="users-head-entry" id="signin"><a href="#signin">{USERREG}</a></h2>
<div>
<form  method="post" action="{TARGET}#signin" class="user-form">
    <table border="0" cellpadding="2" cellspacing="5" width="80%" align="center"  >

    <tr>
         <td>   {SIGNUPERR}  </td>
    </tr>
    <tr>
        <td>{USERSREGNAME}</td>
    </tr>

    <tr>
        <td><input type="text" name="username" value="" /> </td>
    </tr>
    <tr>
        <td>{USERSREGMAIL}</td>
    </tr>
     <tr>
        <td><input type="text" name="usermail" value="" /> </td>
    </tr>

    <tr>
        <td>{USERSREGPASS}</td>
    </tr>

    <tr>
        <td><input type="password" name="userpass" value="" /></td>
    </tr>

    <tr>
    <td> <p> <div class="users-fld-main" >{REGFILDS}</div> </p></td>
    </tr>
    <tr>
    <td> &nbsp; </td>
    </tr>

    <tr>
       <td>
          <input type="submit" name="signup" value="{USERS_B_REG}">

       </td>
        </tr>
   </table>
</form>
</div>

</div>

<div style="clear:both;"></div>



