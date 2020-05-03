<?php
error_reporting(E_ERROR);
require_once 'phpqrcode.php';
$data = urldecode($_POST["data"]);
$uid = intval($_POST["uid"]);
$logo = "";
$QR="";
if($data!="" && $uid>0){
    $QR='QR/qrcode_'.$uid.'.png';
    QRcode::png($data,$QR,2,6,1);
}
echo $QR;


exit();

//带logo二维码
if($logo !== false){

        $QR = imagecreatefromstring(file_get_contents($QR));
        $logo = imagecreatefromstring(file_get_contents($logo));
        $QR_width = imagesx($QR);
        $QR_height = imagesy($QR);
        $logo_width = imagesx($logo);
        $logo_height = imagesy($logo);
        $logo_qr_width = $QR_width / 5;
        $scale = $logo_width / $logo_qr_width;
        $logo_qr_height = $logo_height / $scale;
        $from_width = ($QR_width - $logo_qr_width) / 2;
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
    }
header("Content-Type:image/jpg");
imagepng($QR);
