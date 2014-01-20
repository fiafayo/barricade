<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$g_id=$_GET['id'];
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
   }
   $carigroup=mysql_query("SELECT * FROM forum_group WHERE g_id='$g_id'");
   if(@mysql_num_rows($carigroup)<=0)
   {
		header("Location: forum.php");
		exit();
   }
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$group=$_REQUEST['group'];
		$detail=$_REQUEST['detail'];
		if($_POST['Submit']=='Submit')
		{
			
			if(empty($group) || empty($detail))
			{
				$kesalahan="data kosong";
			}
			else
			{
				$qupdate=mysql_query("UPDATE forum_group SET g_group='$group', g_detail='$detail' WHERE g_id='$g_id'");
				if($qupdate)
				{
					print "<script>
						alert('Data berhasil diubah!');						
					   </script>";
					print "<script>
					  window.location='forum.php';	
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

	<title>Academic Advisor :: Forum </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
<?
	if($kesalahan=="data kosong")
	{
		print "<script>
				alert('Semua data harap diisi!');						
			   </script>";
	}
?>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" width="100%">
			  <tr>
			    <td>
					<table width="100%%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
						<tr>
						<form id="form1" name="form1" method="post" action="update_group.php?id=<? echo $g_id; ?>">
						<td>
						<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
						<tr>
						<td colspan="3" bgcolor="#E6E6E6"><strong>Ubah Group</strong> </td>
						</tr>
						<tr>
						<td width="14%" align="right"><strong>Group</strong></td>
						<td>:</td>
						<td width="84%">
						<? $brsgroup=mysql_fetch_assoc($carigroup);?><input name="group" type="text" id="group" size="50" value="<? if(!empty($kesalahan)) echo $group; else echo $brsgroup['g_group']; ?>"/></td>
						</tr>
						<tr>
						<td valign="top" align="right"><strong>Detail</strong></td>
						<td valign="top">:</td>
						<td><textarea name="detail" cols="50" rows="3" id="detail"><? if(!empty($kesalahan)) echo $detail; else echo $brsgroup['g_detail']; ?></textarea></td>
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

