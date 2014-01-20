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
	
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$pengadaan=$_POST['cboPengadaan'];
		$nrp=$_POST['nrp'];
		$jam=$_POST['jam'];
		$menit=$_POST['menit'];
		$jamdtg=str_pad($jam, 2, "0",STR_PAD_LEFT).":".str_pad($menit, 2, "0",STR_PAD_LEFT).":00";
		if($_POST['submit']=='Batal')
		{
			header('Location: hasilKonsul.php');
			exit;
		}
		else if ($_POST['submit']=='Daftarkan')
		{
			$ambilpengadaan=mysql_query("SELECT * FROM pengadaan WHERE NoPengadaan='$pengadaan'");
			$brspengadaan=mysql_fetch_assoc($ambilpengadaan);
			$ceknrp=mysql_query("SELECT * FROM mahasiswa WHERE NRP='$nrp'");
			$sudahada=mysql_query("SELECT * FROM pendaftaran WHERE NoPengadaan='$pengadaan' AND NRP='$nrp' AND Status=0");

			if(empty($pengadaan) || empty($nrp) || empty($jamdtg))
			{
				$kesalahan="data kosong";
			}
			else if(mysql_num_rows($ceknrp)<=0)
			{
				$kesalahan="nrp tidak ada";
			}
			else if($jam>23)
			{
				$kesalahan="jam salah";
			}
			else if($menit>59)
			{
				$kesalahan="menit salah";
			}
			else if($jamdtg<$brspengadaan['JamMulai'] || $jamdtg>$brspengadaan['JamSelesai'])
			{
				$kesalahan="jam datang di luar range";
			}
			else if(mysql_num_rows($sudahada)>0)
			{
				$kesalahan="sudah terdaftar";
			}
			else
			{
				$noPendaftaran="";
				$today=getdate();
				$thnskrg=substr($today['year'],2,2);
				$thnlalu=substr($today['year'],2,2)-1;
				if ($today['mon']>=2 && $today['mon']<=7)
				{	$noPendaftaran="DE".str_pad($thnlalu, 2, "0",STR_PAD_LEFT).$thnskrg; 	}
				else
				{
				if($today['mon']==1)
				{	$noPendaftaran="DA".str_pad($thnlalu, 2, "0",STR_PAD_LEFT).$thnskrg; 	}
				else
				{	$noPendaftaran="DA".$thnskrg.str_pad($thnskrg+1, 2, "0",STR_PAD_LEFT); 	}
				}
				$query=mysql_query("SELECT * FROM pendaftaran WHERE NoPendaftaran LIKE '$noPendaftaran%' ORDER BY NoPendaftaran ASC");
				$baris=mysql_fetch_assoc($query);
				$ctr=1;
				while($baris)
				{
					if(substr($baris['NoPendaftaran'],6,4)==$ctr)
					{
						$ctr=$ctr+1;
					}
					else
					{	break;}
					$baris=mysql_fetch_assoc($query);
				}
				$noPendaftaran=$noPendaftaran.str_pad($ctr,4,"0",STR_PAD_LEFT);
			
				$insert=mysql_query("INSERT INTO pendaftaran (NoPendaftaran, JamDatang, StatusMhs, NRP, NoPengadaan) VALUES('$noPendaftaran','$jamdtg','Belum Dilayani','$nrp','$pengadaan')");
				if($insert)
				{
					print "<script>
					alert('Pendaftaran langsung oleh dosen telah berhasil!');	
					 </script>";
					print "<script>
							window.location='hasilKonsul.php';	
						</script>";
					exit();
				}
			}
		}	
	}
	$kodos=$username;
	$today=getdate();
	$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
	$h=date("H")-1;
	$jamskrg=$h.":".date("i:s");
	$queryp="(SELECT p.NoPengadaan, p.Tanggal as Tanggal, p.Hari, p.JamMulai, p.JamSelesai, p.MaxMhs, COUNT(d.NoPengadaan) as JumlahDaftar, p.Status 
	FROM pengadaan as p, pendaftaran as d 
	WHERE p.Kode='$kodos' AND (p.Tanggal>'$tanggalskrg' OR (p.Tanggal='$tanggalskrg' AND p.JamSelesai>'$jamskrg')) AND p.NoPengadaan=d.NoPengadaan AND d.Status=0 GROUP BY p.NoPengadaan )
	UNION
	(
	SELECT p.NoPengadaan, p.Tanggal as Tanggal, p.Hari, p.JamMulai, p.JamSelesai, p.MaxMhs, '0' as JumlahDaftar, p.Status 
	FROM pengadaan as p
	WHERE p.Kode='$kodos' AND (p.Tanggal>'$tanggalskrg' OR (p.Tanggal='$tanggalskrg' AND p.JamSelesai>'$jamskrg')) AND p.NoPengadaan NOT IN (SELECT NoPengadaan FROM pendaftaran) 
	) ORDER BY Tanggal";
	$hasil=mysql_query($queryp);	


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Dosen :: Daftar Langsung </title>
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
function isNumber(str,nama)
{
	var charCode = (str.which)?str.which:str.keyCode;	
	var field = eval(nama);
	
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	
		/*if (field.value > 23)
		{
			alert(field.value);
			return false;
		}*/
	
	return true;
}

</script>
</head>
<body>
<?
if($kesalahan=="data kosong")
{
	echo "<script>
		alert('Semua data harap diisi!');	
	 </script>";
}
else if($kesalahan=="nrp tidak ada")
{
	echo "<script>
		alert('NRP yang diinputkan tidak ada!');	
	 </script>";
}

else if($kesalahan=="jam salah")
{
	echo "<script>
		alert('Jam tidak dapat lebih dari 23!');	
	 </script>";
}
else if($kesalahan=="menit salah")
{
	echo "<script>
		alert('Menit tidak dapat lebih dari 59!');	
	 </script>";
}
else if($kesalahan=="jam datang di luar range")
{
	echo "<script>
		alert('Jam datang yang diinputkan berada diluar jam mulai dan selesai!');	
	 </script>";
}
else if($kesalahan=="sudah terdaftar")
{
	print "<script>
			alert('Mahasiswa sudah terdaftar dalam pengadaan tersebut!');	
			 </script>";
}

?>
	<div id="container">
<?php  include_once('header.php'); ?>

            <div id="content">
                <table border="0" align="center" width="100%" cellpadding="4" cellspacing="4">
		<tr>
			<td>
			<form action="daftarLangsung.php" method="post">
			 <br/><div style="font-size:20px" align="center">PENDAFTARAN KONSULTASI LANGSUNG</div><br/>
				<table border="0" align="center" cellpadding="2" cellspacing="2" width="100%">
				  <tr>
				  	<td align="right" width="350px">
						<strong>Pengadaan</strong>
					</td>
					<td>
						<select name="cboPengadaan" id="cboPengadaan">
							<option value="">[Pilih Pengadaan]</option>
							<?
							if(mysql_num_rows($hasil)>0)
							{
							 while($brsp=mysql_fetch_assoc($hasil)) 
							  { 
							 	if($brsp['MaxMhs']>$brsp['JumlahDaftar'])
								{
							 ?>
							<option value="<? echo $brsp['NoPengadaan']; ?>" <? if($pengadaan==$brsp['NoPengadaan']) echo"selected"; ?> ><? echo $brsp['Tanggal'].", ".$brsp['JamMulai']." - ".$brsp['JamSelesai']; ?></option>
							<? 	}
							  } ?>
						</select>
						<? 	} ?>
							
					</td>
				 <tr>
					 <td align="right">
						 <strong>NRP</strong>
					 </td>
					 <td>
					 	<input type="text" name="nrp" id="nrp" value="<? echo $nrp; ?>" onkeypress="return isNumber(event,this);"/>
					 </td>
				 </tr>
				  <tr>
					 <td align="right">
						 <strong>Jam Datang</strong>
					 </td>
					 <td>
					 	<input type="text" name="jam" id="jam" size="1" maxlength="2" value="<? echo $jam; ?>" onkeypress="return isNumber(event,this);"/> : <input type="text" name="menit" id="menit"  maxlength="2" size="1" value="<? echo $menit; ?>" onkeypress="return isNumber(event,this);"/>
					 	<font size="1" color="red">format jam (hh:mm)</font>
					 </td>
				 </tr>
				  <tr>
				  	<td  colspan="2" align="center"><br/><input type="submit" name="submit" value="Daftarkan">
					
					<input type="submit" name="submit" value="Batal"></td>

				  </tr>
				 
				 </table>
				 </form>
			</td>
		</tr>
		  <tr>
			<td align="right">
				
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
