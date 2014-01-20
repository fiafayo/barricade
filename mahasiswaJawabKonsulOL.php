<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	include_once ("fckeditor/fckeditor.php") ;
	$kode=$_REQUEST['kode']; //kode dosen
	$frmKode=$_REQUEST['frmKode']; //kode konsulonline
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	if(empty($kode))
	{
		print "<script>
					window.location='mahasiswaKonsulOL.php';					
				   </script>";  
	}
	if(!empty($frmKode))
	{
		$cek=mysql_query("SELECT * FROM konsulonline WHERE KodeDosen=$kode AND Kode=$frmKode AND KodeMahasiswa=$username AND Status=1");
		if(mysql_num_rows($cek)<=0)
		{
			print "<script>
					alert('Tidak dapat mengakses data!');						
				   </script>";
			print "<script>
					window.location='mahasiswaKonsulOL.php';					
				   </script>";  
		}
	}
	function recs($kodepost)
	{
		if ($kodepost==0)
		{
			return;
		}
		else
		{
			$query = "SELECT k.*, m.Nama as namaMhs, d.Nama as namaDsn FROM konsulonline as k, mahasiswa as m, dosen as d WHERE k.Kode='$kodepost' AND k.KodeMahasiswa=m.NRP AND k.KodeDosen=d.Kode";
			$hasil = mysql_query($query);
			$datapostingan = mysql_fetch_assoc($hasil);
			echo "<table align='center' bgcolor='#F0F0F0' cellpadding='4' width='100%' style='border:1px solid #ccc;'>";
			if($datapostingan['Status']==0)
			{ 
				echo "<tr><td>".$datapostingan['namaMhs']."</td><td align='right'>".$datapostingan['Waktu']."</td></tr>";
			}
			else
			{
				echo "<tr><td>".$datapostingan['namaDsn']."</td><td align='right'>".$datapostingan['Waktu']."</td></tr>";
			}
			echo "<tr><td colspan='2' bgcolor='#E2E2E2'>";
			recs($datapostingan['FromKode']);
			echo $datapostingan['Pesan']."</td></tr>";
			
			echo "</table>";
		}
	}
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		if($_POST['submit']=="Batal")
		{
			header("Location: mahasiswaKonsulOL.php");
			exit();
		}
		else
		{
			$sValue = stripslashes( $_POST['FCKeditor1'] ) ;
			if(empty($frmKode))
				$frmKode=0;
			if(empty($sValue))
				$kesalahan="data kosong";
			else
			{
				$query="INSERT INTO konsulonline(Pesan, Status, FromKode, KodeDosen, KodeMahasiswa) VALUES ('$sValue', 0, '$frmKode', '$kode', '$username')";
				$hasil=mysql_query($query);
				if($hasil)
				{
					$emaildsn=mysql_query("SELECT * FROM dosen WHERE Kode='$kode'");
					$brsemail=mysql_fetch_assoc($emaildsn);
					$emailto=$brsemail['Email'];
					
					$qfrom=mysql_query("SELECT * FROM mailfrom WHERE Variabel='mailfrom'");
					$brsfrom=mysql_fetch_assoc($qfrom);
					$emailfrom=$brsfrom['Isi'];
					$msg=strip_tags($sValue);
					
					$namamhs=mysql_query("SELECT * FROM mahasiswa WHERE NRP='$username'");
					$brsnamamhs=mysql_fetch_assoc($namamhs);
				
					$to      = $emailto;
					$subject = 'Pesan konsultasi online dari '.$brsnamamhs['Nama'];
					$message = $msg;
					$headers = 'From: '.$emailfrom. "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					mail($to, $subject, $message, $headers);
					print "<script>
						alert('Pesan berhasil dikirim!');
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

	<title>Academic Advisor | Mahasiswa :: Kirim Konsultasi Online</title>
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
			<form action="mahasiswaJawabKonsulOL.php?kode=<? echo $kode; ?>&frmKode=<? echo $frmKode; ?>" method="post">
				<table border="0" align="center" cellpadding="2" cellspacing="2" width="100%">
				  <tr>
						<td colspan="2">
						<? if(empty($frmKode))
							{
								$nmdsn=mysql_query("SELECT * FROM dosen WHERE Kode='$kode'");
								$brsnm=mysql_fetch_assoc($nmdsn);
								echo"<strong>Kepada:</strong> ".$brsnm['Nama']; 
							}
							else
							{
								recs($frmKode);
							}
						?>
						</td>
				  </tr>
				 <tr><td width="3%" valign="top" align="right"><strong>Pesan:</strong> </td>
				 	<td  height="270" valign="top">
					<?php
					$oFCKeditor = new FCKeditor('FCKeditor1') ;//kluarin fck
					$oFCKeditor->BasePath = 'fckeditor/' ;
					$oFCKeditor->Value = '' ;
					$oFCKeditor->Create() ;
					?>
					</td>
				 </tr>
				  <tr>
				 
				  	<td  align="center" colspan="2"><input type="submit" name="submit" value="Kirim"> 
					&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Batal"></td>
				  </tr> 
				 </table>
				 </form>
			</td>
		</tr>
		  
			</table>
		</div><!-- end content -->
		
  <div id="sidebar">
<?php  include_once('menuMahasiswa.php'); ?>   
 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
