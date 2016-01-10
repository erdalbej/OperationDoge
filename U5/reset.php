<?php
include_once 'data/dbHelper.php';
$sqlQueries = fopen("dbScript", "r") or die("Unable to open file!");
$content = fread($sqlQueries,filesize("dbScript"));
print_r($content);
$res = nonQuery($content, array());
print_r($res);
?>