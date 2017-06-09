<?php
include('BarcodeQR.php');
$qr = new BarcodeQR();
$qr->sms("9463423767", "hello world");
$name = time();
$qr->draw(150, "qr/".$name);
?>
<img src="http://www.ultrasmsscript.com/dev/app/webroot/qrcode/qr/<?php echo $name; ?>.png" />
<?php
/* $test = '1234567890';
$message = 'hello-world'; */
//BarcodeQR::png($test, 'qr/'.$test.'-large.png', 'L', 8, 2);



?>