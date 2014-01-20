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
	$cek=mysql_query("SELECT *
					FROM pengadaan WHERE NoPengadaan='$nopengadaan' AND Status<>'Batal' AND Kode='$username'");
	if(mysql_num_rows($cek)<=0)
	{
		header("Location: pengadaanKonsul.php");
		exit();
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Dosen :: Detail Pengadaan Konsultasi </title>
	<link rel="stylesheet" href="style.css" media="screen" />

</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">

		<table border="0" align="center" width="100%">
			  <tr>
			  <td colspan="5">
			  
			 <br/><div style="font-size:20px" align="center">DETAIL PENGADAAN</div><br/>
						<table align="center" border="0" cellpadding="4" width="100%">
							<tr>
								<td align="right" width="50%"><strong>No. Pengadaan</strong></td>
								<td><?  
								$str="SELECT p.NoPengadaan, p.Tanggal, p.Hari, p.JamMulai, p.JamSelesai, p.MaxMhs FROM pengadaan as p WHERE p.NoPengadaan='$nopengadaan' ";
								$hasil=mysql_query($str);
								$baris=mysql_fetch_assoc($hasil);
								echo $baris['NoPengadaan']; ?>
								</td>
							</tr>
							
							<tr>
								<td align="right"><strong>Tanggal</strong></td>
								<td><? echo $baris['Tanggal']; ?>
								</td>
							</tr> 
							
							<tr>
								
								<td align="right"><strong>Hari</strong></td>
								<td><? echo $baris['Hari']; ?></td>
							</tr>
							<tr>
								<td align="right"><strong>Jam mulai</strong></td>
								<td><? echo $baris['JamMulai']; ?></td>
							</tr>
							
							<tr>
								<td align="right"><strong>Jam selesai</strong></td>
								<td><? echo $baris['JamSelesai']; ?>
								</td>
							</tr>
							<tr>
								<td align="right"><strong>Max. Mhs</strong></td>
								<td><? echo $baris['MaxMhs']; ?>
								</td>
							</tr>
							<tr>
								<td align="right"><strong>Jumlah Pendaftar</strong></td>
								<td><? $dftr=mysql_query("SELECT * FROM pendaftaran WHERE NoPengadaan='$nopengadaan' AND Status=0");
										$jumDaftar=mysql_num_rows($dftr);
										echo $jumDaftar; ?>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="right"><br/>
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
											<td align="center"> - </td> <td align="center"> - </td> <td align="center"> - </td> <td align="center"> - </td>
											</tr>
									<?	 } ?> 
								</table>
								</td>
							</tr>
							<tr>
							<td colspan="2">
								<a href="pengadaanKonsul.php">&lt;&lt;Kembali</a>
							</td>
							</tr>
						</table>
						
				
				<br/><br/>
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
			  <li><a href="resumeKategori.php">Laporan Kategori Masalah</a></li>
			 <li><a href="konsulOnline.php">Konsultasi Online</a></li>
			  <li><a href="main_forum.php">Forum</a></li>
		   </ul>
		 </div> 

   		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>