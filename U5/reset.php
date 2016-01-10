<?php
include_once 'data/dbHelper.php';
$sqlQueries = fopen("dbScript.sql", "r") or die("Unable to open file!");
$content = fread($sqlQueries,filesize("dbScript.sql"));
$res = nonQuery($content, array());
print_r($res);
?>