<?php
$databaseFT="ftubaya_20131";
$hostFT="localhost";
$userFT="heloz";
$passFT="heloz";
$connFT=mysql_connect($hostFT,$userFT,$passFT,true) or die("Error");
mysql_select_db($databaseFT,$connFT) or die("database tidak ada");


$database="advisor_2012";
$host="localhost";
$user="heloz";
$pass="heloz";
$connAA=mysql_connect($host,$user,$pass,true) or die("Error");
mysql_select_db($database,$connAA) or die("database tidak ada");
define('DEFAULT_SEMESTER_AKTIF',20131);
?>
