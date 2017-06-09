<?php
include('database.php');
$url = SITEURL.'/cronjobs/sendmessage';
file_get_contents($url);
echo '200';
?>