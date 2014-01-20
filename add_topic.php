<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$g_id=$_POST['g_id'];
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
		else
		{
			$cekgrp=mysql_query("SELECT * FROM forum_group WHERE g_id='$g_id'");
		   if(mysql_num_rows($cekgrp)<=0)
		   {
				print "<script>
						  window.location='index.php';	
					   </script>";	
		   }
		}
		/*else if(substr($usr,0,1)=="d")
		{
			print "<script>
					  window.location='homeDosen.php';	
				   </script>";	
		}*/
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
					
					<?php
					/*$host="localhost"; // Host name 
					$username="root"; // Mysql username 
					$password="root"; // Mysql password 
					$db_name="test"; // Database name 
					
					
					// Connect to server and select database.
					mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
					mysql_select_db("$db_name")or die("cannot select DB");*/
					$tbl_name="forum_question"; // Table name 
					// get data that sent from form 
					$topic=$_POST['topic'];
					$detail=$_POST['detail'];
					$name=$_POST['name'];
					$email=$_POST['email'];
					
					$datetime=date("d/m/Y H:i:s"); //create date time
					
					$sql="INSERT INTO $tbl_name(topic, detail, datetime, Kode, g_id)VALUES('$topic', '$detail', '$datetime', '$username', '$g_id')";
					$result=mysql_query($sql);
					
					if($result){
					echo "Berhasil<BR>";
					echo "<a href=main_forum.php?id=$g_id>Lihat topic anda</a>";
					}
					else {
					echo "ERROR";
					}
					//mysql_close();
					?>


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

