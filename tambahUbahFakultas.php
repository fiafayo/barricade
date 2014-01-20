<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$kodeFak = $_REQUEST['kode'];
	
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
		$nama=$_REQUEST['nama'];
		
		if($_POST['submit']=='Batal')
		{
			header('Location: fakultas.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($nama))
			{
				print "<script>
						alert('Nama fakultas harap diisi!');	
	  				 </script>";
				print "<script>
						window.location='tambahUbahFakultas.php?kode=$kodeFak';	
	   				</script>";
				exit();
			}
			else
			{
				$str="SELECT * FROM fakultas";
				$hasil=mysql_query($str);
				$baris=mysql_fetch_assoc($hasil);
				$ada=false;
				
				while($baris)
				{
					if(strcmp(strtolower($baris['Nama']),strtolower($nama))==0)
					{
						$ada=true;
						break;
					}
					$baris=mysql_fetch_assoc($hasil);
				}
				if($ada==true)
				{
					print "<script>
						alert('Nama fakultas sudah ada!');	
	  				 </script>";
					print "<script>
							window.location='tambahUbahFakultas.php?kode=$kodeFak';	
						</script>";
					exit();
				}
				else
				{
					if(empty($kodeFak))
					{
						$hasil = mysql_query("SELECT * FROM fakultas ORDER BY Kode DESC"); 
						$baris=mysql_fetch_assoc($hasil);
						$nextkode=$baris['Kode']+1;
						$str="INSERT INTO fakultas(Kode,Nama) values ('$nextkode','$nama')";
						$query=mysql_query($str);
						print "<script>
							alert('Data telah disimpan!');	
						 </script>";
						print "<script>
								window.location='fakultas.php';	
							</script>";
						exit();
					}					
					else
					{
						$str="UPDATE fakultas SET Nama='$nama' WHERE Kode='$kodeFak'";
						$query=mysql_query($str);
						print "<script>
							alert('Data telah diubah!');	
						 </script>";
						print "<script>
								window.location='fakultas.php';	
							</script>";
					exit();
					}
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

	<title>Academic Advisor | Admin :: Tambah Fakultas </title>
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
			  <form action="tambahUbahFakultas.php?kode=<? echo $kodeFak; ?>" enctype="multipart/form-data" method="post">
			 <br/><div style="font-size:20px" align="center"><? if(empty($kodeFak)) { echo "TAMBAH FAKULTAS";}
			 		else { echo"UBAH FAKULTAS";} ?>
			 </div><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" style="margin:0px 50px 50px 280px" > 
				<tr>
					<td align="right"><b>Kode Fakultas</b></td>
					<td><? 	if(empty($kodeFak))
							{
								$hasil = mysql_query("SELECT * FROM fakultas ORDER BY Kode DESC"); 
								$baris=mysql_fetch_assoc($hasil);
								$nextkode=$baris['Kode']+1;
								echo $nextkode; 
							}
							else
							{
								echo $kodeFak;
								$hasil = mysql_query("SELECT * FROM fakultas WHERE Kode='$kodeFak'"); 
								$baris=mysql_fetch_assoc($hasil);
								
							}?></td>
				</tr>
				<tr>
					<td align="right"><b>Nama Fakultas</b></td>
					<td><input type="text" name="nama" id="nama" value="<? if(!empty($kodeFak)) echo $baris['Nama']; ?>"></td>
				</tr>
				<tr>
					<td colspan="2" align="center" ><br/><input type="submit" value="Simpan" name="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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

