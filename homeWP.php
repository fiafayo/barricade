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
		if($usr=="admin")
		{
			print "<script>
					  window.location='admin.php';	
				   </script>";	
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
<? 
	$today=getdate();
	$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
	$ambiljbtn=mysql_query("SELECT * FROM detailjabatan WHERE KodeDosen='$username' AND (TanggalAwal<='$tanggalskrg' AND '$tanggalskrg'<=TanggalAkhir)");
	$brsjbtn=mysql_fetch_assoc($ambiljbtn);
	?>
	<title>Academic Advisor | <? if($brsjbtn['KodeJabatan']==1) echo"Wakil Rektor"; else echo"PLKPAM"; ?> :: Home <? if($brsjbtn['KodeJabatan']==1) echo"Wakil Rektor"; else echo"PLKPAM"; ?> </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" width="100%" height="350px">
			  <tr>
			    <td valign="top"><font size="2" >
				<p>
				 <p>Selamat datang Wakil Rektor/karyawan PLKPAM, di website Academic Advisor Universitas Surabaya. </p>
			<p>Fasilitas yang dimiliki oleh Wakil Rektor/karyawan PLKPAM pada website ini antara lain:<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melihat riwayat konsultasi mahasiswa.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melihat laporan hasil konsultasi para dosen Academic Advisor.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melihat laporan kategori masalah.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melihat laporan kinerja dosen.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Dan dapat melakukan sharing pada forum yang telah disediakan.<br/>
			</p>	
				</p>	</font>
			    </td
			  ></tr>
			</table>
		</div>
		
		<div id="sidebar">
		   <ul>
		     <li><h2>MENU</h2></li>
			 <li><a href="homeWP.php">Halaman Utama</a></li>
			 <li><a href="wpRiwayatKonsul.php">Riwayat Konsultasi</a></li>
			 <li><a href="wpLaporanHasilKonsul.php">Laporan Hasil Konsultasi</a></li>
			 <li><a href="resumeKategori.php">Laporan Kategori Masalah</a></li>
			 <li><a href="laporanKinerjaDosen.php">Laporan Kinerja Dosen</a></li>
			 <li><a href="main_forum.php">Forum</a></li>
			 
			
		   </ul>
		 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

