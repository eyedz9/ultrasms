<?php
include('database.php');
$url = SITEURL.'/cronjobs/loyaltycron';
file_get_contents($url);
echo '200';
?>