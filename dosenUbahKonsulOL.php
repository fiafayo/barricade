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
	$cek=mysql_query("SELECT * FROM konsulonline WHERE KodeDosen=$username AND Kode=$kodeEdit AND KodeMahasiswa=$kode AND Status=1");
	if(mysql_num_rows($cek)<=0)
	{
		print "<script>
				alert('Tidak dapat mengganti data!');						
			   </script>";
		print "<script>
				window.location='dosenKonsulOL.php';					
			   </script>";  
	}
	if($_SERVER['REQUEST_METHOD']=="POST")
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
					window.location='detailDosenKonsulOL.php?nrp=$kode';
				   </script>";
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

	<title>Academic Advisor | Dosen :: Ubah Konsultasi Online</title>
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
			<form action="dosenUbahKonsulOL.php?kode=<? echo $kode; ?>&kodeEdit=<? echo $kodeEdit; ?>" method="post">
				<table border="0" align="center" cellpadding="2" cellspacing="2" width="100%">
				  <tr>
				  		<td width="3%"><strong>Kepada:</strong> </td>
						<td>
						<?	echo $kode; 
								?>
						</td>
				  </tr>
				 <tr>
				 	<td valign="top"><strong>Pesan:</strong> </td>
				 	<td  height="280" valign="top">
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
				  <td></td>
				  	<td colspan="2" valign="top"><input type="submit" name="submit" value="Simpan"><br/><br/></td>
				  </tr> 
				 </table>
				 </form>
			</td>
		</tr>
		  
			</table>
		</div><!-- end content -->
		
  <div id="sidebar">
<?php include_once('menuDosen.php'); ?>
 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
