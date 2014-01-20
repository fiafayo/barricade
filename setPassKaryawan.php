<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$kode=$_REQUEST['kode'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	else
	{
		$today=getdate();
		$thnskrg=$today['year'];
		/*$hasil = mysql_query("SELECT d.*,f.Nama as NamaFakultas, j.Nama as NamaJurusan, t.Jabatan 
							FROM dosen as d, fakultas as f, jurusan as j, jabatan as t, detailjabatan as dt 
							WHERE d.Kode='$kode' AND d.Status=0 AND $thnskrg>=dt.TahunAwal AND $thnskrg<=dt.TahunAkhir AND d.KodeJabatan=t.Kode AND d.Fakultas=f.Kode AND d.Jurusan=j.Kode AND f.Kode=j.KodeFakultas"); 
		 */
		 $hasil = mysql_query("SELECT d.*,f.Nama as NamaFakultas, j.Nama as NamaJurusan 
							FROM dosen as d, fakultas as f, jurusan as j
							WHERE d.Kode='$kode' AND d.Status=0 AND d.Fakultas=f.Kode AND d.Jurusan=j.Kode AND f.Kode=j.KodeFakultas"); 
		 $barisEdit=mysql_fetch_assoc($hasil);
		 if(!$barisEdit)
		{
			print "<script>
						alert('Kode karyawan tidak terdaftar dalam masa jabatan sekarang!');	
	  				 </script>";
			  print "<script>
				window.location='karyawan.php';	
			</script>";
				exit();
		}
	}
	if($_SERVER['REQUEST_METHOD']=="POST")
	{		
		$pwd1=$_REQUEST['pwd1'];
		$pwd2=$_REQUEST['pwd2'];
		if($_POST['submit']=='Batal')
		{
			header('Location: karyawan.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($pwd1))
			{
				print "<script>
						alert('Password harap diisi!');	
	  				 </script>";
				print "<script>
							window.location='setPassKaryawan.php?kode=$kode';	
						</script>";
				exit();
			}
			if(empty($pwd2))
			{
				print "<script>
						alert('Konfirmasi Password harap diisi!');	
	  				 </script>";
				print "<script>
							window.location='setPassKaryawan.php?kode=$kode';	
						</script>";
				exit();
			}
			if($pwd1 != $pwd2)
			{
				print "<script>
						alert('Password dan konfirmasinya harus sama!');	
	  				 </script>";
				print "<script>
							window.location='setPassKaryawan.php?kode=$kode';	
						</script>";
				exit();
			}
			else
			{
				$str="UPDATE dosen SET Password='$pwd1' WHERE Kode='$kode'";
				$query=mysql_query($str);
				print "<script>
					alert('Data telah diubah!');	
				 </script>";
				print "<script>
						window.location='karyawan.php';	
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
			  <form action="setPassKaryawan.php?kode=<? echo $kode; ?>" method="post">
			 <br/><div style="font-size:20px" align="center">SET PASSWORD </div><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" style="margin:0px 50px 50px 300px" > 
				<tr>
					<td align="right"><strong>Kode</strong></td>
					<td><? 
						echo $barisEdit['Kode']; 
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
				<!-- <tr>
					<td align="right">Jabatan</td>
					<td>
						<? //	echo $barisEdit['Jabatan'];	?>
					</td>
				</tr>
				<tr>
					<td align="right">Tahun awal</td>
					<td>
						<? 	//echo $barisEdit['TahunAwal'];	?>
					</td>
				</tr>
				<tr>
					<td align="right">Tahun akhir</td>
					<td>
						<? //	echo $barisEdit['TahunAkhir'];	?>
					</td>
				</tr> -->
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
					<td><? echo "<input type='password' name='pwd1' value=''>"; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Konfirmasi Password</strong></td>
					<td><? echo "<input type='password' name='pwd2' value=''>"; ?></td>
				</tr>
				<tr>
					<td colspan="2" align="left" ><br/><input type="submit" value="Simpan" name="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" value="Batal" name="submit"></td>
				</tr>
				
				</table>
                         				<? echo "<input type='hidden' name='pwd' value='".$barisEdit['Password']."'>"; ?>
				 

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

