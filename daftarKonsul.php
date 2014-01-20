<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$kodos=$_REQUEST['cboDosen'];
		$today=getdate();
		$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
		$h=date("H")-1;
		$jamskrg=$h.":".date("i:s");
		$query="(SELECT p.NoPengadaan, p.Tanggal as Tanggal, p.Hari, p.JamMulai, p.JamSelesai, p.MaxMhs, COUNT(d.NoPengadaan) as JumlahDaftar, p.Status 
		FROM pengadaan as p, pendaftaran as d 
		WHERE p.Kode='$kodos' AND (p.Tanggal>'$tanggalskrg' OR (p.Tanggal='$tanggalskrg' AND p.JamSelesai>'$jamskrg')) AND p.NoPengadaan=d.NoPengadaan AND d.Status=0 GROUP BY p.NoPengadaan )
		UNION
		(
		SELECT p.NoPengadaan, p.Tanggal as Tanggal, p.Hari, p.JamMulai, p.JamSelesai, p.MaxMhs, '0' as JumlahDaftar, p.Status 
		FROM pengadaan as p
		WHERE p.Kode='$kodos' AND (p.Tanggal>'$tanggalskrg' OR (p.Tanggal='$tanggalskrg' AND p.JamSelesai>'$jamskrg')) AND p.NoPengadaan NOT IN (SELECT NoPengadaan FROM pendaftaran) 
		) ORDER BY Tanggal";
		$hasil=mysql_query($query);	
	}
	

	
	/*$itemPerPage = 12;
	$page		 = $_GET['page'];

	if (empty($page))
		{$page = 1;}
		
	$totalLaptop	= mysql_num_rows($hasil);
	$totalPage		= ceil($totalLaptop / $itemPerPage);
	
	$start		= ($page - 1) * $itemPerPage;
	$end		= $itemPerPage;
	
	$query = $query." LIMIT $start,$end";
	$hasil = mysql_query($query);
	
	if (($page - 1) < 1)
		$batasBawah = 1;
	else
		$batasBawah = $page - 1;
		
	if (($page + 1) > $totalPage)
		$batasAtas = $totalPage;
	else
		$batasAtas = $page + 1;*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Mahasiswa :: Daftar Konsultasi </title>
	<link rel="stylesheet" href="style.css" media="screen" />	

<script type="text/javascript" language = "javascript">
var XMLHttpRequestObject = false;

//initialisasi object XMLHttpRequest beda antara IE dengan FireFox, dan lain-lain

//jika bukan IE
if (window.XMLHttpRequest) {
XMLHttpRequestObject = new XMLHttpRequest();
}

//jika IE
else if (window.ActiveXObject) {
XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
}

 

//fungsi untuk mengambil data di datasource dimasukkan ke div dengan id divID
function getData(dataSource, divID)

{
//jalankan jika object httprequest telah terbuat
if(XMLHttpRequestObject) {

//ambil object div
var obj = document.getElementById(divID);

//buka data di datasource
XMLHttpRequestObject.open("GET", dataSource);

//jika ada perubahan state
XMLHttpRequestObject.onreadystatechange = function()

{

//jika sudah complete dan sukses
if (XMLHttpRequestObject.readyState == 4 &&
XMLHttpRequestObject.status == 200) {

//ambil data masukkan dalam div
obj.innerHTML = XMLHttpRequestObject.responseText;

}

}

XMLHttpRequestObject.send(null);

}

}

</script>
</head>
<body>

	<div id="container">
<?php  include_once('header.php'); ?>

		<div id="content">		<table border="0" align="center" width="100%" cellpadding="4" cellspacing="4">
		<tr>
			<td>
			<form action="daftarKonsul.php" method="post">
			 <br/><div style="font-size:20px" align="center">PENDAFTARAN KONSULTASI</div><br/>
				<table border="0" align="center" cellpadding="2" cellspacing="2" width="100%">
				  <tr>
				  	<td align="right" width="350px">
						<strong>Dosen</strong>
					</td>
					<td>
						<?
							$queryMhs=mysql_query("SELECT * FROM mahasiswa WHERE NRP='$username'");
							$brsMhs=mysql_fetch_assoc($queryMhs);
							$fakultas=$brsMhs['Fakultas'];
							$jurusan=$brsMhs['Jurusan'];
							$today=getdate();
							$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
							$queryDsn=mysql_query("SELECT d.* FROM dosen as d, detailjabatan as dt WHERE d.Fakultas='$fakultas' AND d.Jurusan='$jurusan' AND d.Status=0 AND dt.KodeDosen=d.Kode AND dt.KodeJabatan=5 AND ('$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir)");
							$brsDsn=mysql_fetch_assoc($queryDsn);
						?>
						<select name="cboDosen" id="cboDosen">
							<option value="Pilih dosen">[Pilih dosen]</option>
							<? while($brsDsn) { ?>
							<option value="<? echo $brsDsn['Kode']; ?>" <? if($kodos==$brsDsn['Kode']) echo"selected";?> ><? echo $brsDsn['Nama']; ?>	</option>
							<? 	$brsDsn=mysql_fetch_assoc($queryDsn);
							}	?>
						</select>
					</td>
				  </tr>
				  <tr>
				  	<td colspan="2" align="center"><input type="submit" name="submit" value="Lihat"></td>
				  </tr>
				  <tr>
				  	<td colspan="2" align="center"><br/><font  style="size:12px; color:red;"><i><b>Klik pada nomor pengadaan untuk mendaftar konsultasi</b></i></font></td>
				  </tr>
				  <tr>
				  	<td colspan="2">
						<? 
							$baris=@mysql_fetch_assoc($hasil);
							if(!$baris)
							{
								echo "<table align='center' width='100%'>
									<th >No.Pengadaan</th> <th>Tanggal</th> <th>Hari</th> <th>Jam Mulai</th> <th>Jam Selesai</th> <th>Max. Mhs</th> <th>Jumlah Pendaftar</th> <th>Status</th>
									<tr bgcolor='#F0F0F0'>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
									</tr>
								</table>";
							}
							else
							{
							echo "<table align='center' width='100%'>
									<th >No.Pengadaan</th> <th>Tanggal</th> <th>Hari</th> <th>Jam Mulai</th> <th>Jam Selesai</th> <th>Max. Mhs</th> <th>Jumlah Pendaftar</th> <th>Status</th>";
								
								$warna=1;
								while($baris)
								{
									if($warna%2==0)
									{
										if($baris['Status']=='Batal')
										{
											$nopengadaanygbatal=$baris['NoPengadaan'];
											$qalasan=mysql_query("SELECT * FROM pembatalan WHERE NoPengadaan='$nopengadaanygbatal'");
											$brsalasan=mysql_fetch_assoc($qalasan);
											$alasan=$brsalasan['Alasan'];
											echo"<tr bgcolor='#E2E2E2'><td>".$baris['NoPengadaan']."</a></td><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['JumlahDaftar']."</td><td>".$baris['Status']." (Alasan: $alasan)"."</td></tr>";
										}
										else
										{
											echo"<tr bgcolor='#E2E2E2'><td><a href='detailDaftarKonsul.php?nopengadaan=".$baris['NoPengadaan']."'>".$baris['NoPengadaan']."</a></td><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['JumlahDaftar']."</td><td>".$baris['Status']."</td></tr>";
										}
									}
									else
									{
										if($baris['Status']=='Batal')
										{
											$nopengadaanygbatal=$baris['NoPengadaan'];
											$qalasan=mysql_query("SELECT * FROM pembatalan WHERE NoPengadaan='$nopengadaanygbatal'");
											$brsalasan=mysql_fetch_assoc($qalasan);
											$alasan=$brsalasan['Alasan'];
											echo"<tr bgcolor='#F0F0F0'><td>".$baris['NoPengadaan']."</a></td><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['JumlahDaftar']."</td><td>".$baris['Status']." (Alasan: $alasan)"."</td></tr>";
										}
										else
										{
											echo"<tr bgcolor='#F0F0F0'><td><a href='detailDaftarKonsul.php?nopengadaan=".$baris['NoPengadaan']."'>".$baris['NoPengadaan']."</a></td><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['JumlahDaftar']."</td><td>".$baris['Status']."</td></tr>";
										}
									}
									$warna=$warna+1;
									$baris=mysql_fetch_assoc($hasil);
								}
								
								echo"</table>";
							}
						?>

					</td>
				  </tr>
				 </table>
				 </form>
			</td>
		</tr>
		  <tr>
			<td align="right">
				<?php //PAGING
					/*if($isi==true)
					{
						if ($page != 1)
						{
							$prev = $page - 1;
							print "<a href='pengadaanKonsul.php'>&lt;&lt;First</a>";
							print "&nbsp;&nbsp;<a href='pengadaanKonsul.php?page=$prev'>&lt;Prev<a>&nbsp;&nbsp;";
						}
						
						if ($batasBawah > 1)
							print '. . . ';
						
						for ($i = $batasBawah; $i <= $batasAtas; $i++)
						{
							if ($i == $page)
								print "<b>[$i]</b> ";
							else
							{
								print "<a href='pengadaanKonsul.php?page=$i'>$i</a> ";
							}
						}
						
						if ($batasAtas < $totalPage)
							print ' . . .';
						
						if ($page != $totalPage)
						{
							$next = $page + 1;
							print "&nbsp;&nbsp;<a href='pengadaanKonsul.php?page=$next'>Next&gt;</a>&nbsp;&nbsp;";
							print "<a href='pengadaanKonsul.php?page=$totalPage'>Last&gt;&gt;</a>";
						}
					}*/
						//END OF PAGING ?>
			</td>
		  </tr>
			</table>
		</div><!-- end content -->
		
  <div id="sidebar">
<?php  include_once('menuMahasiswa.php'); ?>   

 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
