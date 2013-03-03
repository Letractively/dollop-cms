<?php




if(isset($_GET['securimage_play'])){

$img = new captcha_securimage();
$img->outputAudioFile();


}else{
$img = new captcha_securimage();

//Change some settings
$img->image_width = 220;
$img->image_height = 64;
$img->perturbation = 0.85;
$img->image_bg_color = new securimageColor("#f6f6f6");
$img->use_transparent_text = true;
$img->text_transparency_percentage = 30; // 100 = completely transparent
$img->num_lines = 6;
$img->line_color = new securimageColor("#eaeaea");
$img->image_signature = "";
$img->signature_color = new securimageColor(rand(0, 64), rand(64, 128), rand(128, 255));
$img->use_wordlist = true;

$img->show(kernel::base_tag('{design}backgrounds/bg3.jpg'));

}

