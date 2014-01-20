<?php
/**
 * 
 *   baak:
    class:        sfPropelDatabase
    param:
      classname:  PropelPDO
      dsn:        mysql:dbname=baak;host=neon.ubaya.ac.id
      username:   teknik
      password:   prnfuFyBaHvV3dT5
      encoding:   utf8
      persistent: true
      pooling:    true
 * 
 */

$databaseFT="baak";
$hostFT="neon.ubaya.ac.id";
$userFT="teknik";
$passFT="prnfuFyBaHvV3dT5";
$connFT=mysql_connect($hostFT,$userFT,$passFT,true) or die("Error");
mysql_select_db($databaseFT,$connFT) or die("database tidak ada");


$database="advisor_2012";
$host="localhost";
$user="advisor";
$pass="advisor_345";
$connAA=mysql_connect($host,$user,$pass,true) or die("Error");
mysql_select_db($database,$connAA) or die("database tidak ada");
define('DEFAULT_SEMESTER_AKTIF',20122);

$sqlInsert = "INSERT INTO mahasiswa (NRP,Nama,Status,Password,Email,Jurusan,Fakultas) VALUES ('%s','%s','%s','%s','%s','%s','%s')";
$sqlSelect = "SELECT NRP, Nama, Pin, KodeStatus FROM Mahasiswa WHERE NRP LIKE '612%' ORDER BY NRP";

$rsFT = mysql_query($sqlSelect, $connFT);
$mhsFT = mysql_fetch_assoc($rsFT);
$flog=fopen('/tmp/insert_maharu.sql',"w");
while ($mhsFT) {
    $nrp=$mhsFT['NRP'];
    $nama=$mhsFT['Nama'];
    $pin=$mhsFT['Pin'];
    $status=$mhsFT['KodeStatus'];
    $kodeJur = substr($nrp, 3,1);
    if ($status=='A') {
        $sqlText=sprintf($sqlInsert,$nrp,$nama,0,$pin,'s'.$nrp.'@students.ubaya.ac.id',$kodeJur,6);
        try {
            echo "INS $nrp \n";
            //$rsAA = mysql_query($sqlText);
            fwrite($flog,$sqlText.";\n");
        } catch (Exception $e) {
            echo "GAGAL $nrp karena ".$e->getMessage()."\n";
        }
    }
    $mhsFT = mysql_fetch_assoc($rsFT);
}
fclose($flog);
?>