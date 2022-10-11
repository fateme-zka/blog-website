<?php

session_start();

header("Content-Type: image/png");
$_SESSION['captcha'] = rand(100000, 99999999);

$img = imagecreate(210, 60);
imagecolorallocate($img, 200, 200, 200);
$color = imagecolorallocate($img, 255, 0, 0);;
$font = '../assets/font.ttf';
imagefttext($img, 30, 7, 20, 50, $color, $font, fa_number($_SESSION['captcha']));
imagepng($img);
imagedestroy($img);

function fa_number($number)
{
    if (!is_numeric($number) || empty($number))
        return '۰';
    $en = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $fa = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
    return str_replace($en, $fa, $number);
}


?>

