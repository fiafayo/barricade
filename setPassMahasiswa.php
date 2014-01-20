<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nrp=$_REQUEST['nrp'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	if($_SERVER['REQUEST_METHOD']=="POST")
	{		
		$pwd=$_REQUEST['pwd'];
		if($_POST['submit']=='Batal')
		{
			header('Location: mahasiswa.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($pwd))
			{
				print "<script>
						alert('Password harap diisi!');	
	  				 </script>";
				print "<script>
							window.location='setPassMahasiswa.php?nrp=$nrp';	
						</script>";
				exit();
			}
			else
			{
				$str="UPDATE mahasiswa SET Password='$pwd' WHERE NRP='$nrp'";
				$query=mysql_query($str);
				print "<script>
					alert('Data telah diubah!');	
				 </script>";
				print "<script>
						window.location='mahasiswa.php';	
					</script>";
				exit();
			
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

	<title>Academic Advisor | Admin :: Set Password </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
<script type="text/javascript" language = "javascript">

 

var XMLHttpRequestObject = false;

//initialisasi object XMLHttpRequest beda antara IE dengan FireFox, dan lain-lain

//jika bukan IE
if (window.XMLHttpRequest) {
XMLHttpRequestObject = new XMLHttpRequest();
}

//jika IE
else if (window.ActiveXObject) {
XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
}

 

//fungsi untuk mengambil data di datasource dimasukkan ke div dengan id divID
function getData(dataSource, divID)

{
//jalankan jika object httprequest telah terbuat
if(XMLHttpRequestObject) {

//ambil object div
var obj = document.getElementById(divID);

//buka data di datasource
XMLHttpRequestObject.open("GET", dataSource);

//jika ada perubahan state
XMLHttpRequestObject.onreadystatechange = function()

{

//jika sudah complete dan sukses
if (XMLHttpRequestObject.readyState == 4 &&
XMLHttpRequestObject.status == 200) {

//ambil data masukkan dalam div
obj.innerHTML = XMLHttpRequestObject.responseText;

}

}

XMLHttpRequestObject.send(null);

}

}

</script>
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" cellspacing="0">
			  <tr>
			  <td colspan="5">
			  <form action="setPassMahasiswa.php?nrp=<? echo $nrp; ?>" method="post">
			 <br/><div style="font-size:20px" align="center">SET PASSWORD </div><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" style="margin:0px 50px 50px 300px" > 
				<tr>
					<td align="right"><strong>NRP</strong></td>
					<td><? 
						 $hasil = mysql_query("SELECT m.*,f.Nama as NamaFakultas, j.Nama as NamaJurusan FROM mahasiswa as m, fakultas as f, jurusan as j WHERE NRP='$nrp' AND m.Fakultas=f.Kode AND m.Jurusan=j.Kode AND f.Kode=j.KodeFakultas"); 
						 $barisEdit=mysql_fetch_assoc($hasil);
						echo $barisEdit['NRP']; 
						?></td>
				</tr>
				<tr>
					<td align="right"><strong>Nama</strong></td>
					<td><? echo $barisEdit['Nama']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Fakultas</strong></td>
					<td><? 	echo $barisEdit['NamaFakultas'];	?>
							
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Jurusan</strong></td>
					<td>
						<? 	echo $barisEdit['NamaJurusan'];	?>
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Alamat</strong></td>
					<td><? echo $barisEdit['Alamat']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>No.Telp</strong></td>
					<td><? echo $barisEdit['NoTelp']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>E-mail</strong></td>
					<td><? echo $barisEdit['Email']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Password</strong></td>
					<td><? echo "<input type='text' name='pwd' value='".$barisEdit['Password']."'>"; ?></td>
				</tr>
				<tr>
					<td colspan="2" align="left" ><br/><input type="submit" value="Simpan" name="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" value="Batal" name="submit"></td>
				</tr>
				
				</table>
				</form> 
				</td>
			  </tr>
			  </table>
		</div><!-- end content -->
		
		<div id="sidebar">
   <ul>
     <li><h2>MENU</h2></li>
     <li><a href="admin.php">Halaman Utama</a></li>
	 <li><a href="kategori.php">Kategori Masalah</a></li>
  	<li><a href="fakultas.php">Fakultas</a></li
    ><li><a href="jurusan.php">Jurusan</a></li>
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

