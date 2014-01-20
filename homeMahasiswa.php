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

	<title>Academic Advisor | Mahasiswa :: Home Mahasiswa </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" width="100%" height="350px">
			  <tr>
			    <td valign="top"><font size="2" >
				<p>Selamat datang Mahasiswa/i, di website Academic Advisor Universitas Surabaya. </p>
			<p>Fasilitas yang dimiliki oleh mahasiswa/i pada website ini antara lain:<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Mendaftar konsultasi.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Membatalkan pendaftaran konsultasi.<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melihat riwayat konsultasinya. <br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Melakukan konsultasi online dengan dosen Academic Advisor.<br/>

			</p>	</font>
			    </td
			  ></tr>
			</table>
		</div>
		
		<div id="sidebar">
<?php  include_once('menuMahasiswa.php'); ?>   

		 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

