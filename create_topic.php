<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$g_id=$_GET['g_id'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	else if($_SESSION['usernamee'] && $_SESSION['passwordd'])
  	{
		$usr=$_SESSION['usernamee'];
		$dsn=mysql_query("SELECT * FROM dosen WHERE Kode='$usr'");
		if(mysql_num_rows($dsn)<=0)
		{
			print "<script>
						  window.location='index.php';	
					   </script>";	
		}
   }
   $cekgrp=mysql_query("SELECT * FROM forum_group WHERE g_id='$g_id'");
   if(mysql_num_rows($cekgrp)<=0)
   {
   		print "<script>
						  window.location='index.php';	
					   </script>";	
   }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor :: Forum </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>

		<div id="content">
			<table border="0" width="100%">
			  <tr>
			    <td>
					<table width="100%%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
						<tr>
						<form id="form1" name="form1" method="post" action="add_topic.php">
						<td>
						<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
						<tr>
						<td colspan="3" bgcolor="#E6E6E6"><strong>Buat Topic Baru</strong> </td>
						</tr>
						<tr>
						<td width="14%" align="right"><strong>Topic</strong></td>
						<td>:</td>
						<td width="84%"><input name="topic" type="text" id="topic" size="50" /></td>
						</tr>
						<tr>
						<td valign="top" align="right"><strong>Detail</strong></td>
						<td valign="top">:</td>
						<td><textarea name="detail" cols="50" rows="3" id="detail"></textarea></td>
						</tr>
						<tr>
						<tr>
						<td><input name="g_id" type="hidden" value="<? echo $g_id; ?>"></td>
						<td>&nbsp;</td>
						<td><input type="submit" name="Submit" value="Submit" /> <input type="reset" name="Submit2" value="Reset" /><br/><br/><br/></td>
						</tr>
						</table>
						</td>
						</form>
						</tr>
					</table>
					<br/>
			    </td
			  ></tr>
			</table>
		</div>
		
		<div id="sidebar">
		   <ul>
		   <li><h2>MENU</h2>
			 </li>
			 <? if($username=="admin")
		   { ?>
		   	<li><a href="admin.php">Halaman Utama</a></li>
			 <li><a href="kategori.php">Kategori Masalah</a></li>
			  <li><a href="fakultas.php">Fakultas</a></li>
			   <li><a href="jurusan.php">Jurusan</a></li>
			 <li><a href="karyawan.php">Karyawan</a></li>
			 <li><a href="mahasiswa.php">Mahasiswa</a></li>
			  <li><a href="forum.php">Forum</a></li>
			   <li><a href="settingmail.php">Setting E-mail</a></li>
		   <?
		   }
		   else
		  { 	$today=getdate();
			$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
		   	$qjbtan=mysql_query("SELECT * FROM detailjabatan WHERE KodeDosen='$username' AND '$tanggalskrg'>=TanggalAwal AND '$tanggalskrg'<=TanggalAkhir");
			$brsjbtn=mysql_fetch_assoc($qjbtan);
				if($brsjbtn['KodeJabatan']==5)
				{
				?>
				<li><a href="homeDosen.php">Halaman Utama</a></li>
				 <li><a href="dsnKategori.php">Kategori Masalah</a></li>
				  <li><a href="pengadaanKonsul.php">Pengadaan Konsultasi</a></li>
				   <li><a href="pembatalanKonsul.php">Pembatalan Konsultasi</a></li>
				 <li><a href="hasilKonsul.php">Hasil Konsultasi</a></li>
				 <li><a href="ubahHasilKonsul.php">Ubah Hasil Konsultasi</a></li>
				 <li><a href="riwayatKonsul.php">Riwayat Konsultasi</a></li>
				  <li><a href="dsnlaporanHasilKonsul.php">Laporan Hasil Konsultasi</a></li>
				 <li><a href="resumeKategori.php">Laporan Kategori Masalah</a></li>
				 <li><a href="dosenKonsulOL.php">Konsultasi Online</a></li>
				  <li><a href="forum.php">Forum</a></li>
			 <?	}
			 	else if($brsjbtn['KodeJabatan']==4)
				{ 
					
					echo" <li><a href='homeKajur.php'>Halaman Utama</a></li>
					 <li><a href='kajurRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='laporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					  <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					  <li><a href='main_forum.php'>Forum</a></li>";
					  
				}
				else if($brsjbtn['KodeJabatan']==3 || $brsjbtn['KodeJabatan']==7)
				{
					echo"
					 <li><a href='homeDkn.php'>Halaman Utama</a></li>
					 <li><a href='dekanRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='dekanLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					  <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					  <li><a href='main_forum.php'>Forum</a></li>
					  ";
				}
				else if($brsjbtn['KodeJabatan']==1 || $brsjbtn['KodeJabatan']==2)
				{
					echo"
				 <li><a href='homeWP.php'>Halaman Utama</a></li>
				 <li><a href='wpRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
				 <li><a href='wpLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
				  <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
				  <li><a href='laporanKinerjaDosen.php'>Laporan Kinerja Dosen</a></li>
				  <li><a href='main_forum.php'>Forum</a></li>
				  ";
				}
			 } ?>
		   </ul>
		 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

