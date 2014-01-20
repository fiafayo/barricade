<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$var=$_GET['var'];
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
  	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$email=$_REQUEST['email'];
		$benar=eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
		if($_POST['submit']=='Batal')
		{
			header('Location: settingmail.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($email) || !$benar)
			{
				$kesalahan="email salah";
			}
			else
			{
				$updatemail=mysql_query("UPDATE mailfrom SET Isi='$email' WHERE Variabel='$var'");
				if($updatemail)
				{
					print "<script>
						alert('Email berhasil diubah!');						
						   </script>";
					print "<script>
							window.location='settingmail.php';
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

	<title>Academic Advisor | Admin :: Setting Mail </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
<?
if($kesalahan=="email salah")
{
print "<script>
	alert('Format email salah!');						
	   </script>";
}
?>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" width="100%">
			  <tr>
			    <td>
					<? if(!$var)
					{ ?>
					Email yang digunakan saat ini: <? $ambilmail=mysql_query("SELECT * FROM mailfrom WHERE Variabel='mailfrom'");
					$brsambilmail=mysql_fetch_assoc($ambilmail);
					echo "<b>".$brsambilmail['Isi']."</b>";?><br/>
					<a href="settingmail.php?var=<? echo $brsambilmail['Variabel']; ?>">Ubah Email</a>
					<? } 
					else if($var=="mailfrom")
					{ ?>
						<font size="+1"><b>UBAH EMAIL</b></font>
						<form action="settingmail.php?var=<? echo $var; ?>"  method="post">
						<table border="0" align="">
							<tr>
								<td>
								<strong>Email:</strong>
								</td>
								<td>
								<input type="text" name="email" size="30" value="<?
								if(!empty($kesalahan))
								{	echo $email; } 
								else
								{
								$ambilmail=mysql_query("SELECT * FROM mailfrom WHERE Variabel='$var'");
								$brsambilmail=mysql_fetch_assoc($ambilmail);
								echo $brsambilmail['Isi']; 
								}
								?>"/>
								</td>
							</tr>
							<tr>
								
								<td colspan="2" align="center"><br/><input type="submit" name="submit" value="Simpan"/>
									<input type="submit" name="submit" value="Batal"/>
								</td>
							</tr>
						</table>
						</form>
				<?	} 
					else
					{
						print "<script>
						  window.location='settingmail.php';	
					   </script>";	
					}?>
					
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

