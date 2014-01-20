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
		//cek apakah yang login admin/bkn.
		$usr=$_SESSION['usernamee'];
		if(substr($usr,0,1)=="d")
		{
			print "<script>
					  window.location='homeDosen.php';	
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

	<title>Academic Advisor | Admin :: Home Admin </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>

		<div id="content">
			<table border="0" width="100%" height="350px">
			  <tr>
			    <td valign="top"><font size="2" >
			<p>Selamat datang Administrator, di website Academic Advisor Universitas Surabaya. </p>
			<p>Fasilitas yang dimiliki oleh administrator pada website ini antara lain:<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Mengatur kategori masalah.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Mengatur data fakultas dan jurusan.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Mengatur data karyawan. <br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Mengatur data Mahasiswa.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Mengatur forum.<br/>
			</p>	</font>
			    </td
			  ></tr>
			</table>
		</div>
		
		<div id="sidebar">
		   <ul>
		   <li><h2>MENU</h2>
		   
			 </li>
			 <li><a href="admin.php">Halaman Utama</a></li>
			 <li><a href="kategori.php">Kategori Masalah</a></li>
			  <li><a href="fakultas.php">Fakultas</a></li>
			   <li><a href="jurusan.php">Jurusan</a></li>
			 <li><a href="karyawan.php">Karyawan</a></li>
			 <li><a href="mahasiswa.php">Mahasiswa</a></li>
			  <li><a href="forum.php">Forum</a></li>
			  <li><a href="settingmail.php">Setting E-mail</a></li>
		   </ul>
		 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

