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
		/*if($usr=="admin")
		{
			print "<script>
					  window.location='admin.php';	
				   </script>";	
		}*/
		/*else if(substr($usr,0,1)=="d")
		{
			print "<script>
					  window.location='homeDosen.php';	
				   </script>";	
		}*/
   }
   $group=$_POST['group'];
	$detail=$_POST['detail'];
	if(empty($group) || empty($detail))
	{
		print "<script>
				alert('Semua data harap diisi!');						
			   </script>";
		print "<script>
				window.location='create_group.php';
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
					
					<?php
					/*$host="localhost"; // Host name 
					$username="root"; // Mysql username 
					$password="root"; // Mysql password 
					$db_name="test"; // Database name 
					
					
					// Connect to server and select database.
					mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
					mysql_select_db("$db_name")or die("cannot select DB");*/
					$tbl_name="forum_group"; // Table name 
					// get data that sent from form 
					
					$datetime=date("d/m/Y H:i:s"); //create date time
					
					$sql="INSERT INTO $tbl_name(g_group, g_detail, g_datetime)VALUES('$group', '$detail', '$datetime')";
					$result=mysql_query($sql);
					
					if($result){
					echo "Berhasil<BR>";
					echo "<a href=forum.php>Lihat group anda</a>";
					}
					else {
					echo "ERROR";
					}
					mysql_close();
					?>


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

