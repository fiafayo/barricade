<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nopengadaan=$_REQUEST['nopengadaan'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	$periksa=mysql_query("SELECT * FROM pembatalan WHERE NoPengadaan='$nopengadaan'");
	$sudahada=mysql_fetch_assoc($periksa);
	if($sudahada)
	{
		print "<script>
			alert('Pembatalan dengan No.Pengadaan($nopengadaan) sudah pernah dilakukan!');	
		 </script>";
		print "<script>
				window.location='pembatalanKonsul.php';	
			</script>";
		exit();
	}
	if($_SERVER['REQUEST_METHOD']=="POST")
	{		
		$alasan=$_REQUEST['alasan'];
		if($_POST['submit']=='Batal')
		{
			header('Location: pembatalanKonsul.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($alasan))
			{
				print "<script>
					alert('Alasan pembatalan harap diisi!');	
				 </script>";
				print "<script>
						window.location='setBatalKonsul.php?nopengadaan=$nopengadaan';	
					</script>";
				exit();
			}
			else
			{
				$hasil=mysql_query("UPDATE pengadaan SET Status='Batal' WHERE NoPengadaan='$nopengadaan'");
				$insert=mysql_query("INSERT INTO pembatalan (Alasan,NoPengadaan) VALUES ('$alasan','$nopengadaan')");
				
				if($hasil && $insert)
				{	
					$qpendaftar=mysql_query("SELECT p.Tanggal, p.JamMulai, p.JamSelesai, o.Nama, m.Email as EmailMhs, o.Email as EmailDsn 
								FROM pendaftaran as d, pengadaan as p, dosen as o, mahasiswa as m
								WHERE p.NoPengadaan='$nopengadaan' AND p.NoPengadaan=d.NoPengadaan AND p.Kode=o.Kode AND d.NRP=m.NRP AND d.Status=0");
					//echo mysql_num_rows($qpendaftar);
					if(mysql_num_rows($qpendaftar)>0)
					{
						$brspendaftar=mysql_fetch_assoc($qpendaftar);
						while($brspendaftar)
						{
							$qfrom=mysql_query("SELECT * FROM mailfrom WHERE Variabel='mailfrom'");
							$brsfrom=mysql_fetch_assoc($qfrom);
							$emailfrom=$brsfrom['Isi'];
							$emailto=$brspendaftar['EmailMhs'];
							
							$to      = $emailto;
							$subject = 'Pembatalan konsultasi oleh: '.$brspendaftar['Nama'];
							$message = 'Jadwal konsultasi pada tanggal: '.$brspendaftar['Tanggal']. "\r\n" .
										'Jam: '.$brspendaftar['JamMulai'].' - '.$brspendaftar['JamSelesai']. "\r\n" .
										'Telah dibatalkan dengan alasan: '.$alasan;
							$headers = 'From: '.$emailfrom. "\r\n" .
								'X-Mailer: PHP/' . phpversion();
							mail($to, $subject, $message, $headers);
							$brspendaftar=mysql_fetch_assoc($qpendaftar);
						}
					}
						
					print "<script>
						alert('Pengadaan dengan nomor: $nopengadaan berhasil dibatalkan!');	
						</script>";
					print "<script>
					window.location='pembatalanKonsul.php';
				   </script>";
				}
			
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Dosen :: Detail Pembatalan Konsultasi </title>
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
 
		<div id="content">
            <table border="0" align="center" width="100%" cellspacing="0">
			  <tr>
			  <td colspan="5">
			  <form action="setBatalKonsul.php?nopengadaan=<? echo $nopengadaan; ?>" method="post">
			 <br/><div style="font-size:20px" align="center">Pembatalan Konsultasi</div><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" align="center" width="100%"> 
				<tr>
					<td align="right" width="50%"><strong>No. Pengadaan</strong></td>
					<td><? 
						 $hasil = mysql_query("SELECT p.*, COUNT(d.NoPengadaan) as JumDaftar FROM pengadaan as p, pendaftaran as d WHERE p.NoPengadaan='$nopengadaan' AND d.NoPengadaan='$nopengadaan' AND d.NoPengadaan=p.NoPengadaan GROUP BY p.NoPengadaan ORDER BY p.NoPengadaan"); 
						 $barisEdit=mysql_fetch_assoc($hasil);
						echo $barisEdit['NoPengadaan']; 
						?></td>
				</tr>
				<tr>
					<td align="right"><strong>Tanggal</strong></td>
					<td><? echo $barisEdit['Tanggal']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Hari</strong></td>
					<td><? 	echo $barisEdit['Hari'];	?>
							
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Jam mulai</strong></td>
					<td>
						<? 	echo $barisEdit['JamMulai'];	?>
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Jam selesai</strong></td>
					<td><? echo $barisEdit['JamSelesai']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Max. Mhs</strong></td>
					<td><? echo $barisEdit['MaxMhs']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Total pendaftar</strong></td>
					<td><? echo $barisEdit['JumDaftar']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Total Pendaftar Batal</strong></td>
					<td><? $btl=mysql_query("SELECT * FROM pendaftaran WHERE NoPengadaan='$nopengadaan' AND Status=1");
						$totbtl=mysql_num_rows($btl);
						echo $totbtl; ?></td>
				</tr>
				<tr>
					<td colspan="2"><br/>
						<table align="center"> <caption style="font-size:12px"><b>Detail Pendaftar</b></caption>
							<th>NRP</th><th>Nama</th><th>Jam Datang</th><th>Status</th>
							
							<? 
								$pndaftar=mysql_query("SELECT d.NRP, m.Nama, d.JamDatang, d.Status FROM pendaftaran as d, mahasiswa as m WHERE d.NoPengadaan='$nopengadaan' AND d.NRP=m.NRP ORDER BY d.JamDatang");
								$brspndaftar=mysql_fetch_assoc($pndaftar);
								if($brspndaftar)
								{
									$warna=1;
									while($brspndaftar)
									{
										
										if($warna%2==0)
										{
										?>
										<tr bgcolor="#E2E2E2">
										<td align="center"><? echo $brspndaftar['NRP']; ?></td> <td align="center"><? echo $brspndaftar['Nama']; ?></td> <td align="center"><? echo $brspndaftar['JamDatang']; ?></td><td align="center"><? if($brspndaftar['Status']==0) echo "Tidak Batal"; else echo"Batal"; ?></td>
										</tr>
										<? }
										else
										{	?>
										<tr bgcolor="#F0F0F0">
										<td align="center"><? echo $brspndaftar['NRP']; ?></td> <td align="center"><? echo $brspndaftar['Nama']; ?></td> <td align="center"><? echo $brspndaftar['JamDatang']; ?></td><td align="center"><? if($brspndaftar['Status']==0) echo "Tidak Batal"; else echo"Batal"; ?></td>
										</tr>
										<? } 
										$warna=$warna+1;
										$brspndaftar=mysql_fetch_assoc($pndaftar);
									 } 
								 }
								 else
								 { ?>
									<tr bgcolor="#F0F0F0">
									<td align="center"> - </td> <td align="center"> - </td> <td align="center"> - </td>
									</tr>
							<?	 } ?> 
						</table>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><br/><strong>Alasan pembatalan</strong></td>
					<td><br/><textarea name="alasan" rows="5" cols="36"></textarea></td>
				</tr>
				<tr>
					<td align="right" ><br/><input type="submit" value="Simpan" name="submit"></td>
					<td align="left"><br/><input type="submit" value="Batal" name="submit"></td>
				</tr>
				
				</table>
				</form> 
				</td>
			  </tr>
			  </table>
		</div><!-- end content -->
		
		<div id="sidebar">
   <ul>
     <li><h2>MENU</h2></li>
    <li><a href="homeDosen.php">Halaman Utama</a></li>
	 <li><a href="dsnKategori.php">Kategori Masalah</a></li>
			  <li><a href="pengadaanKonsul.php">Pengadaan Konsultasi</a></li>
			   <li><a href="pembatalanKonsul.php">Pembatalan Konsultasi</a></li>
			 <li><a href="hasilKonsul.php">Hasil Konsultasi</a></li>
			   <li><a href="ubahHasilKonsul.php">Ubah Hasil Konsultasi</a></li>
			 <li><a href="riwayatKonsul.php">Riwayat Konsultasi</a></li>
			 <li><a href="dsnlaporanHasilKonsul.php">Laporan Hasil Konsultasi</a></li>
			 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
			 <li><a href="dosenKonsulOL.php">Konsultasi Online</a></li>
			  <li><a href="forum.php">Forum</a></li>
   </ul>
 </div> 

   		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

