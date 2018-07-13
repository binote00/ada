<?php 
 $im=@imagecreatetruecolor(200,200)or die("Impossible d'initialiser la bibliothèque GD");
 $background_color=imagecolorallocate($im,0,0,0);
 $text_color=imagecolorallocate($im,233,14,91);
 //imagestring($im,1,5,5,"Test",$text_color);
 imageline($im,10,150,15,200,$text_color);
 header("Content-type: image/png");
 imagepng($im);
 imagedestroy($im);	 
?>