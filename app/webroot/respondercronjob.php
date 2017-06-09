<?php
include('database.php');
$url = SITEURL.'/cronjobs/sendresponder';
file_get_contents($url);
echo '200';
?>