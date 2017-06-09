<?php
include('database.php');
$url = SITEURL.'/emailalerts/sendemail';
file_get_contents($url);
echo '200';
?>