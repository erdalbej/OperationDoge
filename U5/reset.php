<?php
include_once 'data/dbHelper.php';
$sqlQueries = fopen("dbScript.sql", "r") or die("Unable to open file!");
$content = fread($sqlQueries,filesize("dbScript.sql"));
$res = query($content);
print_r($res);
?>