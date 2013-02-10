$(document).ready(function() {

$('ul li#link').mouseover(function() {
$(this).find('ul:first').slideDown(400);
});

$('ul li#link').mouseleave(function() {
$('ul li#link ul').slideUp(400);
});

$('ul li#link ul').mouseleave(function() {
$('ul li#link ul').slideUp(400);
});
   
});
