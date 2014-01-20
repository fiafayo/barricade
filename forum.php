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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor :: Forum </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
<script type="text/javascript" language = "javascript">

function confirmDelete()
{
	return confirm('Apakah anda yakin ingin menghapus data ?');
	
}
</script>
</head>
<body>
	<div id="container">
		<div id="header"><img src="images/b.png" align="right" style="margin:0px 50px 0px 0px">
			<h1>Academic Advisor Universitas Surabaya</h1>
		</div>
		<table border="0" width="100%" bgcolor="#f5f5f5">
			<tr bgcolor="#A3ADF8">
				<td width="12%" align="center"  height="25"><a href="advisor.php">Advisor</a></td>
				<td width="12%"align="center"  height="25"> <a href="faq.php">FAQ's</a></td>
				<td> </td>
			</tr>
		</table>
		<div id="content">
		<div align="right">Selamat datang <?php print $username; ?>, <a href="logout.php">keluar</a></div><hr >
			<table border="0" width="100%">
			  <tr>
			    <td>
					
					<?php
						/*$host="localhost"; // Host name 
						$username="root"; // Mysql username 
						$password="root"; // Mysql password 
						$db_name="test"; // Database name 
						
						
						// Connect to server and select databse.
						mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
						mysql_select_db("$db_name")or die("cannot select DB");*/
						$tbl_name="forum_group"; // Table name 
						$sql="SELECT * FROM $tbl_name ORDER BY g_datetime";
						// OREDER BY id DESC is order result by descending 
						$result=mysql_query($sql);
						?>
						<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
						<tr>
						<!--<td width="6%" align="center" bgcolor="#E6E6E6"><strong>#</strong></td> -->
						<td width="65%" align="center" bgcolor="#E6E6E6" colspan="2"><strong>Forum</strong></td>
						<td width="6%" align="center" bgcolor="#E6E6E6"><strong>Topics</strong></td>
						<td width="15%" align="center" bgcolor="#E6E6E6"><strong>Date/Time</strong></td>
						<? if($username=="admin")
						{?>
						<td width="10%" align="center" bgcolor="#E6E6E6" colspan="2"><strong>Pilihan</strong></td>
						<? } ?>
						</tr>
						
						<?php
						while($rows=mysql_fetch_array($result)){ // Start looping table row 
						?>
						<tr>
						<td width="6%" bgcolor="#FFFFFF" align="center"><img src="images/folder.gif"><? //echo $rows['id']; ?></td>
						<td bgcolor="#FFFFFF"><a href="main_forum.php?id=<? echo $rows['g_id']; ?>"><? echo $rows['g_group']; ?></a><BR>
						<font size="-2" color="#999999"><? echo $rows['g_detail']; ?></font>
						</td>
						<td align="center" bgcolor="#FFFFFF"><? 
							$g_id=$rows['g_id'];
							$jumtopic=mysql_query("SELECT * FROM forum_question WHERE g_id=$g_id");
							echo mysql_num_rows($jumtopic); ?></td>
						<td align="center" bgcolor="#FFFFFF"><? echo $rows['g_datetime']; ?></td>
						<? if($username=="admin")
						{?>
						<td align="center" bgcolor="#FFFFFF"><a href="update_group.php?id=<?  echo $rows['g_id']; ?>"><img src="images/edit.png" border="0" title="Ubah" alt="Ubah"></a></td>
						<td align="center" bgcolor="#FFFFFF"><a href="delete_group.php?id=<?  echo $rows['g_id']; ?>"  onclick="return confirmDelete();"><img src="images/delete.png" border="0" title="Hapus" alt="Hapus"></a></td>
						<? } ?>
						</tr>
						
						
						<?php
						// Exit looping and close connection 
						}
						//mysql_close();
						if($username=="admin")
						{
						?>
						<tr>
						<td colspan="6" align="right" bgcolor="#E6E6E6"><a href="create_group.php"><strong>Buat Group Baru</strong> </a></td>
						</tr>
						<? } ?>
						</table>
			    </td>
			</tr>
			</table>
		</div>
		
		<div id="sidebar">
		   <ul>
		   <li><h2>MENU</h2></li>
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
		   { 
		   	$today=getdate();
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
					  <li><a href='main_forum.php'>Forum</a></li> ";
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

