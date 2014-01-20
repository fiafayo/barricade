<?php
	$database="heloz_aa";
	$host="localhost";
	$user="heloz";
	$pass="heloz";
	mysql_connect($host,$user,$pass) or die("Error");
	mysql_select_db($database) or die("database tidak ada");

$fin=fopen('tk_mhs.csv','r');
$row=fgetcsv($fin, 2048, ';');
$format="INSERT INTO mahasiswa(NRP,Nama,Fakultas,Jurusan,Email,Alamat,NoTelp,Password,Status) values ('%s','%s',%s,%s,'%s','%s','%s','%s',0)";
while ($row) {
    $n=count($row);
    if ($n>20) {
        //"nrp";"sksmax";"ips";"status";"jurusan";"nama";"alamat";"tgllahir";"tmplahir";"totbss";"ipk";"skskum";"telepon";"password";"angkatan";"namasma";"namaortu";"kelamin";"asisten";"konsultasi";"aa"

        $nrp=$row[0];
        $status=$row[3];
        $nama=$row[5];
        $jurusan=intval(substr($row[4],1,1));
        $alamat=$row[6];
        $notelp=$row[12];
        $pin=$row[13];
        if (  ($status=='A') || ($status=='') ) {
            $sql=sprintf($format,$nrp,$nama,6,$jurusan,'s'.$nrp.'@if.ubaya.ac.id',$alamat,$notelp,$pin,0);
            try {
                echo 'Insert mhs '.$nama.' ...';
                mysql_query($sql);
                echo "done\n";
            } catch (Exception $e) {
                echo "\n     Error: ".$e->getMessage()."\n";
            }
        } else {
            echo 'SKIP mhs '.$nrp." karena status=$status \n";
        }
    }
    $row=fgetcsv($fin, 2048, ';');     
}
fclose($fin);
print "\nSELESAI\n";
?>
