<?php
include_once 'data/dbHelper.php';
$sqlQueries = fopen("sql/sqlScript", "r") or die("Unable to open file!");
$content = fread($sqlQueries,filesize("sql/sqlScript"));
print_r($content);
$res = nonQuery($content, array());
print_r($res);
?>