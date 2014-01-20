<?php
	$database="heloz_aa";
	$host="localhost";
	$user="heloz";
	$pass="heloz";
	mysql_connect($host,$user,$pass) or die("Error");
	mysql_select_db($database) or die("database tidak ada");

$fin=fopen('tk_dosen.csv','r');
$row=fgetcsv($fin, 2048, ';');
$format="INSERT INTO dosen(Kode,Nama,Fakultas,Jurusan,Email,Password,Status) values ('%s','%s',%s,%s,'%s','%s',0)";
while ($row) {
    $n=count($row);
    if ($n==6) { 
        $npk=$row[0];
        $nama=$row[1];
        $jurusan=intval(substr($row[4],1,1));
        $sql=sprintf($format,$npk,$nama,6,$jurusan,'d'.$npk.'@if.ubaya.ac.id','p'.$npk,0);
        try {
            echo 'Insert dosen '.$nama.' ...';
            mysql_query($sql);
            echo "done\n";
        } catch (Exception $e) {
            echo "\n     Error: ".$e->getMessage()."\n";
        }
    }
    $row=fgetcsv($fin, 2048, ';');     
}
fclose($fin);
print "\nSELESAI\n";
?>
