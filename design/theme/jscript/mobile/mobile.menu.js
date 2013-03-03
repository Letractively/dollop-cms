$(function(){
    var l_showOrHide    = false;
    var r_showOrHide    = false;
    var z_index         = 1;/* make by default #navmenu z-index:1 and  #allmenu z-index:0 to work proper */
    $navigation         = $("#navmenu");
    $page               = $("#pagebody");
    $allmeus            = $("#allmenu");

$("#allmenu h1,#allmenu h2,#allmenu h3,#allmenu .title").next().css('display', 'none');
$("#allmenu h1,#allmenu h2,#allmenu h3,#allmenu .title").click(function(){
      $(this).next().slideToggle("slow");
});


function l_handleClick(event)
{
  event.preventDefault();
  if(l_showOrHide == false){
      if(z_index != 1){
        $navigation.css({'z-index':1});
      }
     $page.animate({left:"80%"},1000);
      l_showOrHide     = true;
  }else{
       $page.animate({left:"0"});
       l_showOrHide     = false;
  }
}
function r_handleClick(event)
{
  event.preventDefault();
  if(r_showOrHide == false){
     if(z_index == 1){
        $navigation.css({'z-index':0});
        $allmeus.css({'z-index':1});
        z_index = 0;
      }
      $page.animate({left:"-80%"},1000);
      r_showOrHide     = true;
  }else{
      $page.animate({left:"0"});
        $allmeus.css({'z-index':0});
        $navigation.css({'z-index':1});
        z_index = 1;
       r_showOrHide     = false;
  }
}

$('#nav-btn').on('click', l_handleClick);
$('#menu-btn').on('click', r_handleClick);




});