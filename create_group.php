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
		if(mysql_num_rows($dsn)>0)
		{
			if($usr<>"admin")
			{
				print "<script>
						  window.location='forum.php';	
					   </script>";	
			}
		}
		else
		{
			print "<script>
						  window.location='index.php';	
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
						<form id="form1" name="form1" method="post" action="add_group.php">
						<td>
						<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
						<tr>
						<td colspan="3" bgcolor="#E6E6E6"><strong>Buat Group Baru</strong> </td>
						</tr>
						<tr>
						<td width="14%" align="right"><strong>Group</strong></td>
						<td>:</td>
						<td width="84%"><input name="group" type="text" id="group" size="50" /></td>
						</tr>
						<tr>
						<td valign="top" align="right"><strong>Detail</strong></td>
						<td valign="top">:</td>
						<td><textarea name="detail" cols="50" rows="3" id="detail"></textarea></td>
						</tr>
						<tr>
						<tr>
						<td>&nbsp;</td>
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

