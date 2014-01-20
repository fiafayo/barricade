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

	<title>Academic Advisor | Dosen :: Home Dosen </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" width="100%"  height="350px">
                <tr><td >Mahasiswa belum daftar? <a href="konsultasiLangsung.php" border="0"><input type="button" value="Input Konsultasi Mahasiswa" border="0" /></a><hr /></td></tr>
			  <tr>
			    <td valign="top"><font size="2" >
				<p>
				 <p>Selamat datang Dosen, di website Academic Advisor Universitas Surabaya. </p>
			<p>Fasilitas yang dimiliki oleh Dosen pada website ini antara lain:<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melakukan pengadaan konsultasi.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melakukan pembatalan konsultasi.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Mencatat dan mengubah hasil konsultasi mahasiswa.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melihat riwayat konsultasi mahasiswa.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melihat laporan hasil konsultasi.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melihat laporan kategori masalah.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melakukan konsultasi secara online dengan mahasiswa.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Dan dapat melakukan sharing pada forum yang telah disediakan.<br/>
			</p>	
				</p>	</font>
			    </td
			  ></tr>
			</table>
		</div>
		
		<div id="sidebar">
<?php include_once('menuDosen.php'); ?>
		 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

