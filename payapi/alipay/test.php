<?php
$ali_public_key_path='key/alipay_public_key.pem';
$pubKey = file_get_contents($ali_public_key_path);
$res = openssl_get_publickey($pubKey);
echo "<div>public_key:</div>";
var_dump($res);



echo "<div>private_key:</div>";
//��ȡ�̻�˽Կ
$priKey = file_get_contents('key/rsa_private_key1.pem');
//ת��Ϊopenssl��Կ��������û�о���pkcs8ת����˽Կ
$res = openssl_get_privatekey($priKey);
var_dump($res);
?>