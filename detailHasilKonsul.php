<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	include_once ("fckeditor/fckeditor.php") ;
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nopendaftaran=$_REQUEST['nopendaftaran'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	if(empty($nopendaftaran))
	{
	print "<script>
			alert('Harap memilih nomor pengadaan terlebih dahulu!');	
		 </script>";
		print "<script>
				window.location='hasilKonsul.php';	
			</script>";
		exit();
	}
	$periksa=mysql_query("SELECT * FROM hasilkonsultasi WHERE NoPendaftaran='$nopendaftaran'");
	$sudahada=mysql_fetch_assoc($periksa);
	if($sudahada)
	{
		print "<script>
			alert('Pencatatan dengan No.Pendaftaran($nopendaftaran) telah dilakukan!');	
		 </script>";
		print "<script>
				window.location='hasilKonsul.php';	
			</script>";
		exit();
	}

	$kesalahan="";
	if($_SERVER['REQUEST_METHOD']=="POST")
	{		
		$kategori=$_REQUEST['cboKategori'];
		$permasalahan=$_REQUEST['permasalahan'];
		$saran=$_REQUEST['saran'];
		$hasilkonsul=$_REQUEST['hasilkonsul'];
		$statusmhs=$_REQUEST['cboStatus'];
                $confidential=isset($_REQUEST['confidential']) ? intval($_REQUEST['confidential']) : 0;
		if($_POST['submit']=='Batal')
		{
			header('Location: hasilKonsul.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if($statusmhs=="Sudah Dilayani")
			{
				if(empty($permasalahan) || empty($saran) || empty($hasilkonsul))
				{
					$kesalahan="data kosong";
				}
				else
				{
					$insert=mysql_query("INSERT INTO hasilkonsultasi (KategoriMasalah, Permasalahan, HasilKonsultasi, Saran, NoPendaftaran, confidential) VALUES ('$kategori', '$permasalahan', '$hasilkonsul', '$saran', '$nopendaftaran', $confidential)");
					
					if($insert)
					{
						
						$update=mysql_query("UPDATE pendaftaran SET StatusMhs='$statusmhs' WHERE NoPendaftaran='$nopendaftaran'");
						
						print "<script>
							alert('Pencatatan hasil konsultasi telah berhasil!');	
							</script>";
						print "<script>
						window.location='hasilKonsul.php';
					   </script>";
					}
				
				}
			}
			else if($statusmhs=="Tidak Datang")
			{
				$update=mysql_query("UPDATE pendaftaran SET StatusMhs='$statusmhs' WHERE NoPendaftaran='$nopendaftaran'");
				print "<script>
							alert('Pencatatan hasil konsultasi telah berhasil!');	
							</script>";
				print "<script>
				window.location='hasilKonsul.php';
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

	<title>Academic Advisor | Dosen :: Detail Hasil Konsultasi </title>
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

function tampil(idx)
{
	if(idx==0)
	{
		document.getElementById('tampilDiv1').style.display="";
		document.getElementById('tampilDiv2').style.display="";
		document.getElementById('tampilDiv3').style.display="";
		document.getElementById('tampilDiv4').style.display="";
		document.getElementById('tampilDiv5').style.display="";
		document.getElementById('tampilDiv6').style.display="";
		document.getElementById('tampilDiv7').style.display="";
		document.getElementById('tampilDiv8').style.display="";
	}
	else if(idx==1)
	{
		document.getElementById('tampilDiv1').style.display="none";
		document.getElementById('tampilDiv2').style.display="none";
		document.getElementById('tampilDiv3').style.display="none";
		document.getElementById('tampilDiv4').style.display="none";
		document.getElementById('tampilDiv5').style.display="none";
		document.getElementById('tampilDiv6').style.display="none";
		document.getElementById('tampilDiv7').style.display="none";
		document.getElementById('tampilDiv8').style.display="none";
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
            <table border="0" align="center" width="100%" cellspacing="0">
			  <tr>
			  <td colspan="5">
			  <form action="detailHasilKonsul.php?nopendaftaran=<? echo $nopendaftaran; ?>" method="post">
			 <br/><div style="font-size:20px" align="center">Detail Hasil Konsultasi</div><br/><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" align="center" width="100%"> 
				<tr>
					<td align="right"><strong>No. Pendaftaran</strong></td>
					<td width="80%"><? 
						 $hasil = mysql_query("SELECT d.*, m.Nama FROM pendaftaran as d,mahasiswa as m WHERE NoPendaftaran='$nopendaftaran' AND d.NRP=m.NRP"); 
						 $barisEdit=mysql_fetch_assoc($hasil);
						echo $barisEdit['NoPendaftaran']; 
						?></td>
				</tr>
				<tr>
					<td align="right"><strong>NRP</strong></td>
					<td><? echo $barisEdit['NRP']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Nama</strong></td>
					<td><? 	echo $barisEdit['Nama'];	?>
							
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Status Mahasiswa</strong></td>
					<td><select id="cboStatus" name="cboStatus">
						<option value="Sudah Dilayani" onclick="tampil(0);" <? if($statusmhs=="Sudah Dilayani") echo"selected"; ?>>Sudah dilayani</option>
						<option value="Tidak Datang" onclick="tampil(1);" <? if($statusmhs=="Tidak Datang") echo"selected"; ?>>Tidak datang</option>
					</select>
					</td>
				</tr>
				
				<tr>
					<td align="right"><div id="tampilDiv5"><strong>Kategori Masalah</strong></div></td>
					<td><div id="tampilDiv1">
						<? 	$query="SELECT * FROM kategori";
							$masalah=mysql_query($query);
							$baris=mysql_fetch_assoc($masalah);
							echo "<select id='cboKategori' name='cboKategori'>";
							while($baris)
							{
								if($kategori==$baris['Kode'])
								{
									echo"<option value='".$baris['Kode']."' selected>".$baris['Nama']."</option>";
								}
								else
								{
									echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>";
								}
								
								$baris=mysql_fetch_assoc($masalah);
							}
							echo"</select>";
	
						?></div>
					</td>
				</tr>
                                    <tr>
                                        <td align="right"><strong>Confidential</strong></td>
                                        <td>
                                            <input type="radio" name="confidential" value="1" <?php if ($confidential) echo 'checked="true"';?> />Ya &nbsp;
                                            <input type="radio" name="confidential" value="0" <?php if (!$confidential) echo 'checked="true"';?>  />Tidak
                                        </td>
                                    </tr>
				<tr>
					<td align="right" valign="top"><div id="tampilDiv6"><strong>Permasalahan</strong></div></td>
					<td><div id="tampilDiv2"><?
							$oFCKeditor = new FCKeditor('permasalahan') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							if(!empty($kesalahan))
								{$oFCKeditor->Value = $permasalahan ;}
							else
								{$oFCKeditor->Value = '' ;}
							$oFCKeditor->Create() ;
					?></div></td>
				</tr>
				<tr>
					<td align="right" valign="top"><div id="tampilDiv7"><strong>Saran</strong></div></td>
					<td><div id="tampilDiv3"><?
							$oFCKeditor = new FCKeditor('saran') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							if(!empty($kesalahan))
								{$oFCKeditor->Value = $saran ;}
							else
								{$oFCKeditor->Value = '' ;}
							$oFCKeditor->Create() ;
					?></div></td>
				</tr>
				<tr>
					<td align="right" valign="top"><div id="tampilDiv8"><strong>Hasil Konsultasi</strong></div></td>
					<td><div id="tampilDiv4"><?
							$oFCKeditor = new FCKeditor('hasilkonsul') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							if(!empty($kesalahan))
								{$oFCKeditor->Value = $hasilkonsul ;}
							else
								{$oFCKeditor->Value = '' ;}
							$oFCKeditor->Create() ;
					?></div></td>
				</tr>
				
				<tr>
					<td align="center" colspan="2"><br/><input type="submit" value="Simpan" name="submit">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Batal" name="submit"></td>
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
    <li><a href="homeDosen.php">Halaman Utama</a></li>
	 <li><a href="dsnKategori.php">Kategori Masalah</a></li>
			  <li><a href="pengadaanKonsul.php">Pengadaan Konsultasi</a></li>
			   <li><a href="pembatalanKonsul.php">Pembatalan Konsultasi</a></li>
			 <li><a href="hasilKonsul.php">Hasil Konsultasi</a></li>
			   <li><a href="ubahHasilKonsul.php">Ubah Hasil Konsultasi</a></li>
			 <li><a href="riwayatKonsul.php">Riwayat Konsultasi</a></li>
			 <li><a href="dsnlaporanHasilKonsul.php">Laporan Hasil Konsultasi</a></li>
			  <li><a href="resumeKategori.php">Laporan Kategori Masalah</a></li>
			 <li><a href="dosenKonsulOL.php">Konsultasi Online</a></li>
			  <li><a href="main_forum.php">Forum</a></li>
   </ul>
 </div> 

   		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

