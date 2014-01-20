<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$kode=$_REQUEST['kode'];
	$kategori=$_REQUEST['kategori'];
	
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	
	$query="SELECT h.*,m.Nama as NamaMhs, d.JamDatang, d.NRP, p.Tanggal, j.Nama as NamaJurusan, j.Nama as NamaJurusan, o.Nama as NamaDsn 
			FROM hasilkonsultasi as h, pendaftaran as d, pengadaan as p, dosen as o, mahasiswa as m, fakultas as f, jurusan as j 
			WHERE h.Kode=$kode AND h.NoPendaftaran=d.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND m.NRP=d.NRP AND m.Fakultas=f.Kode AND m.Jurusan=j.Kode AND p.Kode=o.Kode AND o.Fakultas=f.Kode AND o.Jurusan=j.Kode AND f.Kode=j.KodeFakultas";
	
	$hasil=mysql_query($query);		
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title> Academic Advisor | Kajur :: Detail Riwayat Konsultasi</title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <br/><div style="font-size:20px" align="center">DETAIL RIWAYAT KONSULTASI</div><br/>
		<table border="0" align="center" width="100%" >
			  <tr>
			    <td>
				
				<? $baris=mysql_fetch_assoc($hasil);
				
					if($baris)
					{
						echo "<table border='0' align='center' cellpadding='4' cellspacing='1' bgcolor='#CCCCCC'>
						<tr>
							<td align='right' bgcolor='#FFFFFF'><b>NRP & Nama Mahasiswa: </b></td>
							<td bgcolor='#FFFFFF'>".$baris['NRP']." & ".$baris['NamaMhs']."</td>
						</tr>
						<tr>
							<td align='right' bgcolor='#FFFFFF'><b>Jurusan/Prodi/Subsistem: </b></td>
							<td bgcolor='#FFFFFF'>".$baris['NamaJurusan']."</td>
						</tr>
						<tr>
							<td align='right' bgcolor='#FFFFFF'><b>Tanggal: </b></td>
							<td bgcolor='#FFFFFF'>".$baris['Tanggal']."</td>
						</tr>
						<tr>
							<td align='right' bgcolor='#FFFFFF'><b>Waktu konsultasi: </b></td>
							<td bgcolor='#FFFFFF'>".$baris['JamDatang']."</td>
						</tr>
						<tr>
							<td align='right' bgcolor='#FFFFFF'><b>Dosen academic advisor: </b></td>
							<td bgcolor='#FFFFFF'>".$baris['NamaDsn']."</td>
						</tr>
						<tr>
							<td align='right' bgcolor='#FFFFFF'><b>Permasalahan: </b></td>
							<td bgcolor='#FFFFFF'>".$baris['Permasalahan']."</td>
						</tr>
						<tr>
							<td align='right' bgcolor='#FFFFFF'><b>Saran: </b></td>
							<td bgcolor='#FFFFFF'>".$baris['Saran']."</td>
						</tr>
						<tr>
							<td align='right' bgcolor='#FFFFFF'><b>Hasil yang diperoleh: </b></td>
							<td bgcolor='#FFFFFF'>".$baris['HasilKonsultasi']."</td>
						</tr>
						
						";
						echo"</table>";
					}
					else
						{print "<font color='red' size='5'><i>Tidak ada data riwayat konsultasi.</i></font>";
						$isi=false;
						/*print "<script>
								window.location='pembatalanKonsul.php';
							   </script>";*/
							}
					 ?>
				
			    </td>
			  </tr>
			  <tr>
			  	<td><br/>
					<a href="kajurDetailRiwayatKonsul.php?nrp=<? echo $baris['NRP']; ?>&kategori=<? echo $kategori; ?>">&lt;&lt;Kembali</a>
			  	</td>
			  </tr>
			</table>
		</div><!-- end content -->
		
  <div id="sidebar">
   <ul>
		 <li><h2>MENU</h2></li>
		<li><a href="homeKajur.php">Halaman Utama</a></li>
	 <li><a href="kajurRiwayatKonsul.php">Riwayat Konsultasi</a></li>
	 <li><a href="laporanHasilKonsul.php">Laporan Hasil Konsultasi</a></li>
	 <li><a href="resumeKategori.php">Laporan Kategori Masalah</a></li>
	  <li><a href="main_forum.php">Forum</a></li>
   </ul>
 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
