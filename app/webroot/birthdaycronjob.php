<?php
include('database.php');
$url = SITEURL.'/cronjobs/sendbirthday';
file_get_contents($url);
echo '200';
?>