<?php
use CieloCheckout\Address;
include("tupi.inicializar.php");
$url = 'c:/temp/';
$image = '';
$width = 360;
$height = 310;
$picture = WideImage::load($url.'image(5).png');
if($picture->getWidth() > $picture->getHeight()){
 $resize = $picture->resize(null,$height, 'fill');
$top = 0;
$left = floor(($resize->getWidth()-$width)/2);
 $crop = $resize->crop($left,$top,$width,$height);
}else{
    $resize = $picture->resize($width,null, 'fill');
    $left = 0;
    $top = floor(($resize->getHeight()-$height)/2);
 $crop = $resize->crop($left,$top,$width,$height);

}
$crop->saveToFile($url.'imagefinal.png');