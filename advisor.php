<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	/*if($_SESSION['usernamee'] && $_SESSION['passwordd'])
  	{
		$usr=$_SESSION['usernamee'];
		if($usr=="admin")
		{
			print "<script>
					  window.location='admin.php';	
				   </script>";	
		}
		else
		{
			$today=getdate();
			$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
			$query = "SELECT * FROM dosen WHERE Kode='$username' AND Status=0 AND '$tanggalskrg'>=d.TanggalAwal AND '$tanggalskrg'<=d.TanggalAkhir";
			$cek = mysql_query($query);
			$bariscek=mysql_fetch_assoc($cek);
			if($bariscek)
			{
				if($bariscek['KodeJabatan']==5)
				{
					print "<script>
						  window.location='homeDosen.php';	
					   </script>";	
				}
			}
			else
			{
				print "<script>
						  window.location='homeMahasiswa.php';	
					   </script>";	
			}
		}
   }*/
   
	if($_SERVER['REQUEST_METHOD']=="POST")
   	{
	$username	= strtolower($_REQUEST['txtUsername']);
	$password	= $_REQUEST['txtPassword'];

	if(!empty($username) && !empty($password))
	{
		if($username=="admin") 
		{
			$query = "SELECT * FROM dosen WHERE Kode='$username'";
		}
		else if(strlen($username)<7)
		{	
			$today=getdate();
			$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
//			echo $tanggalskrg;
			$query = "SELECT d.*, dt.KodeJabatan FROM dosen d, detailjabatan as dt WHERE d.Kode='$username' AND d.Status=0 AND dt.KodeDosen=d.Kode AND '$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir";
		}
		else
		{
			$query = "SELECT * FROM mahasiswa WHERE NRP='$username' AND Status=0";
		}
		
		$hasil = mysql_query($query);
		if (mysql_num_rows($hasil) <= 0)
		{
			$pesan='NRP / NPK tidak ada';
		}
		else
		{
			$baris=mysql_fetch_assoc($hasil);
			if($baris["Password"]==$password)
			{	
				$_SESSION['usernamee']	= $username;
				$_SESSION['passwordd']	= $password;

				if($username == 'admin')
				{
					header('Location: admin.php');
					exit;
				}
				else if($baris['KodeJabatan']==5)
				{
					header('Location: homeDosen.php');
					exit;
				}
				else if($baris['KodeJabatan']==4)
				{
					header('Location: homeKajur.php');
					exit;
				}
				else if($baris['KodeJabatan']==3 || $baris['KodeJabatan']==7)
				{
					header('Location: homeDkn.php');
					exit;
				}
				else if($baris['KodeJabatan']==1 || $baris['KodeJabatan']==2)
				{
					header('Location: homeWP.php');
					exit;
				}
				else
				{
					header('Location: homeMahasiswa.php');
					exit;
				}
			}
			else
			{
				$pesan='Password salah! silahkan ulangi.';
			}
		}
	}
	else
	{
		$pesan='Anda harus mengisi username dan password dengan benar!';
	}
	header('Location: advisor.php?pesan='.$pesan);
	exit;
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Advisor</title>
	<link rel="stylesheet" href="style.css" media="screen" />	
<script language = "javascript">

 

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
		<div id="header"><img src="images/b.png" align="right" style="margin:0px 50px 0px 0px">
			<h1>Academic Advisor Universitas Surabaya</h1>
		</div>
		<table border="0" width="100%" bgcolor="#f5f5f5">
			<tr bgcolor="#A3ADF8">
			<? if(!$_SESSION['usernamee'] && !$_SESSION['passwordd'])
			{ ?>
			<td width="12%" align="center"  height="25"><a href="index.php">Halaman Utama</a></td>
			<? } ?>
				<td width="12%" align="center"  height="25"><a href="advisor.php">Advisor</a></td>
				<td width="12%"align="center"  height="25"> <a href="faq.php">FAQ's</a></td>
				<td> </td>
			</tr>
		</table>
		<div id="content">
		<? if(!empty($username) && !empty($password))
			{ ?>
		<div align="right">Selamat datang <?php print $username; ?>, <a href="logout.php">keluar</a></div><hr > <? } 
			
			$qfak=mysql_query("SELECT f.*,COUNT(j.KodeFakultas) as jumJurusan FROM fakultas as f, jurusan as j WHERE f.Kode=j.KodeFakultas AND f.Kode<>0 GROUP BY j.KodeFakultas ORDER BY f.Kode");		
		?>
			
			<table border="0" width="100%" bgcolor="#999999">
			<tr><td colspan="2" bgcolor="#A3ADF8"><font size="">...:: Nama Dosen Pendamping Akademik (Academic Advisors) ::...</font></td></tr>
			<? while($brsfak=mysql_fetch_assoc($qfak))
			{ ?> 
				<tr bgcolor="#FFFFFF">
					<td width="25%" valign="top"><font size="" color="#666666">Fakultas <? echo $brsfak['Nama']; ?></font></td>
					<td><? 
						$today=getdate();
						$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
						//if($brsfak['jumJurusan']>1) 
							{	
								$jm="";
								$fak=$brsfak['Kode'];
								$qjur=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='$fak'");
								if(mysql_num_rows($qjur)>1)
									{$jm="lebih";}
								while($brsjur=mysql_fetch_assoc($qjur))
								{
									echo"<table border=0><tr><td>";
									if($jm=="lebih")
									{	echo"<font size=''><i><b>Jurusan ".$brsjur['Nama']."</b></i></font>";}
									$jur=$brsjur['Kode'];
									
									$qdsn=mysql_query("SELECT d.*
											FROM dosen as d, detailjabatan as dt
											WHERE d.Fakultas='$fak' AND d.Jurusan='$jur' AND d.Kode=dt.KodeDosen AND dt.KodeJabatan=5 AND '$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir");
									while($brsdsn=mysql_fetch_assoc($qdsn))
									{
										$kode=$brsdsn['Kode'];
										echo"<table border=0><tr><td><div id='$kode'><a href='#$kode' onclick=\"getData('lihatJadwal.php?kode=$kode&j=0','$kode')\">".$brsdsn['Nama']."</a></div></td></tr><tr><td></td></tr></table>";
											
									}
									echo"</td></tr></table>";
								}
								
							}
							
					?>
					</td>
				</tr>
			<? } ?>
		</table>
		</div>
		<div id="sidebar"><? if(empty($username) && empty($password))
		{ ?>	<br/><br/><br/><h2 align="center">MASUK</h2>
		<div class="widget">
		<br>
			<form id="frmSignIn" name="frmSignIn" method="POST" action="advisor.php">
				<table border=0 align="center" valign="bottom">
 				 <tr>
  					  <td align="right">NRP/NPK: </td>
 					  <td><input type="text" name="txtUsername" size=15></td>
  				</tr>
 				 <tr>
					   <td align="right">Password: </td>
 					  <td><input type="password" name="txtPassword" size=15></td>
				  </tr>
 				 <tr>
  				    <td colspan="2" align="right">
					  <input type="submit" name="Login" value="Masuk" >
				  </td>
  				</tr>
				  <tr>
 				     <td colspan="2" align="center">
					  <font color="#FF0000">
						<?php
							$pesan=$_REQUEST['pesan'];
							if($pesan){
							print $pesan;
						}
						?>
	 				 </font>
      				     </td>
 				 </tr>
				</table>
				
		</form>
		</div>
		<? } 
		else
		{
			
			if($username == 'admin')
			{
				echo" <ul>
				   <li><h2>MENU</h2>
					 </li>
					 <li><a href='admin.php'>Halaman Utama</a></li>
					 <li><a href='kategori.php'>Kategori Masalah</a></li>
					  <li><a href='fakultas.php'>Fakultas</a></li>
					   <li><a href='jurusan.php'>Jurusan</a></li>
					 <li><a href='karyawan.php'>Karyawan</a></li>
					 <li><a href='mahasiswa.php'>Mahasiswa</a></li>
					  <li><a href='forum.php'>Forum</a></li>
					   <li><a href='settingmail.php'>Setting E-mail</a></li>
				   </ul>";
			}
			else if(strlen($username)<7)
			{	
				$today=getdate();
				$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
				$strjabatan = "SELECT d.*, dt.KodeJabatan FROM dosen d, detailjabatan as dt WHERE d.Kode='$username' AND d.Status=0 AND dt.KodeDosen=d.Kode AND '$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir";
				$qjabatan=mysql_query($strjabatan);
				$brsjabatan=mysql_fetch_assoc($qjabatan);
								
				if($brsjabatan['KodeJabatan']==5)
				{
					echo"<ul>
						 <li><h2>MENU</h2></li>
						 <li><a href='homeDosen.php'>Halaman Utama</a></li>
						  <li><a href='dsnKategori.php'>Kategori Masalah</a></li>
						  <li><a href='pengadaanKonsul.php'>Pengadaan Konsultasi</a></li>
						   <li><a href='pembatalanKonsul.php'>Pembatalan Konsultasi</a></li>
						 <li><a href='hasilKonsul.php'>Hasil Konsultasi</a></li>
						  <li><a href='ubahHasilKonsul.php'>Ubah Hasil Konsultasi</a></li>
						 <li><a href='riwayatKonsul.php'>Riwayat Konsultasi</a></li>
						  <li><a href='dsnlaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
						   <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
						 <li><a href='dosenKonsulOL.php'>Konsultasi Online</a></li>
						  <li><a href='main_forum.php'>Forum</a></li>
					   </ul>";
				}
				else if($brsjabatan['KodeJabatan']==4)
				{
					echo" <ul>
					 <li><h2>MENU</h2></li>
					 <li><a href='homeKajur.php'>Halaman Utama</a></li>
					 <li><a href='kajurRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='laporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					<li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					  <li><a href='main_forum.php'>Forum</a></li>
					   </ul>";
				}
				else if($brsjabatan['KodeJabatan']==3 || $brsjabatan['KodeJabatan']==7)
				{
					echo"  <ul>
					 <li><h2>MENU</h2></li>
					 <li><a href='homeDkn.php'>Halaman Utama</a></li>
					 <li><a href='dekanRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='dekanLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					  <li><a href='main_forum.php'>Forum</a></li>
					   </ul>";
				}
				else if($brsjabatan['KodeJabatan']==1 || $brsjabatan['KodeJabatan']==2)
				{
					echo"<ul>
				 <li><h2>MENU</h2></li>
				 <li><a href='homeWP.php'>Halaman Utama</a></li>
				 <li><a href='wpRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
				 <li><a href='wpLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
				 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
				 <li><a href='laporanKinerjaDosen.php'>Laporan Kinerja Dosen</a></li>
				  <li><a href='main_forum.php'>Forum</a></li>
				   </ul>";
				}
			}
			else
			{
				echo" <ul>
				 <li><h2>MENU</h2></li>
				 <li><a href='homeMahasiswa.php'>Halaman Utama</a></li>
				  <li><a href='daftarKonsul.php'>Daftar Konsultasi</a></li>
				   <li><a href='batalDaftarKonsul.php'>Batal Daftar Konsultasi</a></li>
				<li><a href='riwayatKonsulMhs.php'>Riwayat Konsultasi</a></li>
				 <li><a href='mahasiswaKonsulOL.php'>Konsultasi Online</a></li>
			   </ul>";
			}
		}
		?>
		</div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
		
 
  




























