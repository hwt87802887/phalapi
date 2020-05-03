<?php
$ali_public_key_path='key/alipay_public_key.pem';
$pubKey = file_get_contents($ali_public_key_path);
$res = openssl_get_publickey($pubKey);
echo "<div>public_key:</div>";
var_dump($res);



echo "<div>private_key:</div>";
//读取商户私钥
$priKey = file_get_contents('key/rsa_private_key1.pem');
//转换为openssl密钥，必须是没有经过pkcs8转换的私钥
$res = openssl_get_privatekey($priKey);
var_dump($res);
?>