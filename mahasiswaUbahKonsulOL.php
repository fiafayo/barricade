<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	include_once ("fckeditor/fckeditor.php") ;
	$kode=$_REQUEST['kode'];
	$kodeEdit=$_REQUEST['kodeEdit'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	$cek=mysql_query("SELECT * FROM konsulonline WHERE KodeDosen=$kode AND Kode=$kodeEdit AND KodeMahasiswa=$username AND Status=0");
	if(mysql_num_rows($cek)<=0)
	{
		print "<script>
				alert('Tidak dapat mengganti data!');						
			   </script>";
		print "<script>
				window.location='mahasiswaKonsulOL.php';					
			   </script>";  
	}
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		if($_POST['submit']=='Batal')
		{
			header('Location: mahasiswaKonsulOL.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			$sValue = stripslashes( $_POST['FCKeditor1'] ) ;
			if(empty($frmKode))
				$frmKode=0;
			if(empty($sValue))
				$kesalahan="data kosong";
			else
			{
				$query="UPDATE konsulonline SET Pesan='$sValue' WHERE Kode=$kodeEdit";
				$hasil=mysql_query($query);
				if($hasil)
				{
					print "<script>
						alert('Pesan berhasil diubah!');						
					   </script>";
				   print "<script>
						window.location='mahasiswaKonsulOL.php?cboDosen=$kode';
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

	<title>Academic Advisor | Mahasiswa :: Ubah Konsultasi Online</title>
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
<?
if($kesalahan=="data kosong")
{
	
	print "<script>
			alert('Data harap diisi!');						
		   </script>";
}

?>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" cellpadding="4" cellspacing="4">
		<tr>
			<td>
			<form action="mahasiswaUbahKonsulOL.php?kode=<? echo $kode; ?>&kodeEdit=<? echo $kodeEdit; ?>" method="post">
				<table border="0" align="center" cellpadding="2" cellspacing="2" width="100%">
				  <tr>
						<td colspan="2">
						<?	echo"<strong>Kepada:</strong> ".$kode; 
								?>
						</td>
				  </tr>
				 <tr>
				 	<td valign="top" width="20"><strong>Pesan:</strong></td>
					<td>
					<?php 
					$ambil=mysql_query("SELECT * FROM konsulonline WHERE Kode=$kodeEdit");
					$brs=mysql_fetch_assoc($ambil);
					$isi=$brs['Pesan'];
					$oFCKeditor = new FCKeditor('FCKeditor1') ;//kluarin fck
					$oFCKeditor->BasePath = 'fckeditor/' ;
					$oFCKeditor->Value = $brs['Pesan'] ;
					$oFCKeditor->Create() ;
					?>
					</td>
				 </tr>
				  <tr>
				  	<td  align="center" colspan="2"><br/><input type="submit" name="submit" value="Simpan">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Batal"></td>
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
    <li><a href="homeMahasiswa.php">Halaman Utama</a></li>
	  <li><a href="daftarKonsul.php">Daftar Konsultasi</a></li>
	  <li><a href="batalDaftarKonsul.php">Batal Daftar Konsultasi</a></li>
	<li><a href="riwayatKonsulMhs.php">Riwayat Konsultasi</a></li>
	 <li><a href="mahasiswaKonsulOL.php">Konsultasi Online</a></li>
   </ul>
 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
