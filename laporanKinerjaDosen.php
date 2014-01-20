<?php
	session_start();
	include("config.php");
	include("library/jpgraph.php");
	include("library/jpgraph_pie.php");
	include("library/jpgraph_pie3d.php");

	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	
	$klik=false;
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<? 
	$today=getdate();
	$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
	$ambiljbtn=mysql_query("SELECT * FROM detailjabatan WHERE KodeDosen='$username' AND (TanggalAwal<='$tanggalskrg' AND '$tanggalskrg'<=TanggalAkhir)");
	$brsjbtn=mysql_fetch_assoc($ambiljbtn);
	?>
	<title>Academic Advisor | <? if($brsjbtn['KodeJabatan']==1) echo"Wakil Rektor"; else if($brsjbtn['KodeJabatan']==2) echo"PLKPAM";else if($brsjbtn['KodeJabatan']==3) echo"Dekan"; 
	else if($brsjbtn['KodeJabatan']==4) echo"Kajur"; else if($brsjbtn['KodeJabatan']==5) echo"Dosen"; else echo"Wakil Dekan"; ?> :: Resume Kategori  </title>
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


function poptastic(url)
{
var newwindow;
	newwindow=window.open(url,'name','auto');//,width=1000');
	if (window.focus) {newwindow.focus()}
}

</script>
</head>
<body>

	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" cellpadding="4" cellspacing="4" height="300px">
		<tr>
			<td valign="top">
			<br/><div style="font-size:20px" align="center">LAPORAN KINERJA DOSEN ACADEMIC ADVISOR</div><br/>
			<form action="resumeKategori.php" method="post">
				<table border="0" align="center" cellpadding="2" cellspacing="2" width="100%">
					<tr>
				  	<td align="right" width="46%"><strong>Fakultas</strong></td>
					<td align="left">
						<select name="fakultas" id="fakultas" onclick="getData('aturJurusan2.php?kode='+document.getElementById('fakultas').value,'targetDiv')">
							<option value="%">[Semua Fakultas]</option>
							<? $qfakultas=mysql_query("SELECT DISTINCT f.* FROM fakultas as f, jurusan as j WHERE f.Kode<>0 AND f.Kode=j.KodeFakultas");
								while($brsfakultas=mysql_fetch_assoc($qfakultas))
								{ 
									if($fakultas==$brsfakultas['Kode'])
									{
									?>
										<option value="<? echo $brsfakultas['Kode']; ?>" selected><? echo $brsfakultas['Nama']; ?></option>
								<?	}
									else
									{  ?>
										<option value="<? echo $brsfakultas['Kode']; ?>" ><? echo $brsfakultas['Nama']; ?></option>
								<?	}	
							 } ?>
						</select>
						</td>
				  </tr>
				  
				  <tr>
						<td align="right">
							<strong>Jurusan</strong>
						</td>
						<td>
						<div id="targetDiv">
						<?  $queryjurusan=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='$fakultas' AND KodeFakultas<>0");
							$barisjurusan=mysql_fetch_assoc($queryjurusan);
							if($barisjurusan)
							{
								echo"<select id='jurusan' name='jurusan'>";
								echo"<option value='%' >[Semua Jurusan]</option>";
								while($barisjurusan)
								{
									if($jurusan==$barisjurusan['Kode'])
									{
									echo"<option value='".$barisjurusan['Kode']."' selected >".$barisjurusan['Nama']."</option>";
									}
									else
									{
										echo"<option value='".$barisjurusan['Kode']."' >".$barisjurusan['Nama']."</option>";
									}
									$barisjurusan=mysql_fetch_assoc($queryjurusan);
								}
								echo"</select>";
							}
							else
							{ ?>
							<select name="jurusan" id="jurusan" >
							<option value="%">[Semua Jurusan]</option>
							
							</select><?  
							 }?>
						</div>
						</td>
					</tr>
				    <tr>
				  	<td align="right" width="46%">
						<strong>Semester</strong>
					</td>
					<td>
							<select name="cboSemester" id="cboSemester">
							<option value="%">[Semua Semester]</option>
							<? 
								$qsemester=mysql_query("SELECT NoPendaftaran FROM hasilkonsultasi");
								$i=0;
								$arr=array();
								while($brssemester=mysql_fetch_assoc($qsemester))
								{
									$semAda=false;
									$jumArr=count($arr);
									for($j=0; $j<$jumArr; $j++)
									{
										if(substr($brssemester['NoPendaftaran'],1,5)==$arr[$j])
										{
											$semAda=true;
											break;
										}
									}
									if($semAda==false)
									{
										array_push($arr,substr($brssemester['NoPendaftaran'],1,5));
									}
									$i++;
								}
								$jumArr=count($arr);
								
								for($k=0; $k<$jumArr; $k++)
								{
									if(substr($arr[$k],0,1)=="A")
									{	$ganjilgenap="Ganjil"; }
									else
									{	$ganjilgenap="Genap"; }
									
									if($semester==$arr[$k])
									{?>
										<option value="<? echo $arr[$k]; ?>" selected><? echo $ganjilgenap." ".substr($arr[$k],1,2)."-".substr($arr[$k],3,2); ?></option>
								<? 	}
									else
									{	?>
										<option value="<? echo $arr[$k]; ?>"><? echo $ganjilgenap." ".substr($arr[$k],1,2)."-".substr($arr[$k],3,2); ?></option>
								<?	}
									
							   } ?>
							</select>
					</td>
				  </tr>		  
				  <tr>
				  	<td colspan="2" align="center"><input type="button" onClick="javascript:poptastic('detailLaporanKinerjaDosen.php?fakultas='+document.getElementById('fakultas').value+'&jurusan='+document.getElementById('jurusan').value+'&semester='+document.getElementById('cboSemester').value);" value="Lihat Grafik" /><br/><br/></td>
				  </tr>
				  
				  <tr>
				  	<td colspan="2">
						

					</td>
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
  	<? $today=getdate();
			$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
		   	$qjbtan=mysql_query("SELECT * FROM detailjabatan WHERE KodeDosen='$username' AND '$tanggalskrg'>=TanggalAwal AND '$tanggalskrg'<=TanggalAkhir");
			$brsjbtn=mysql_fetch_assoc($qjbtan);
				if($brsjbtn['KodeJabatan']==5)
				{
				?>
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
				  <li><a href="forum.php">Forum</a></li>
			 <?	}
			 	else if($brsjbtn['KodeJabatan']==4)
				{ 
					
					echo" <li><a href='homeKajur.php'>Halaman Utama</a></li>
					 <li><a href='kajurRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='laporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					   <li><a href='main_forum.php'>Forum</a></li>";
					
					  
				}
				else if($brsjbtn['KodeJabatan']==3 || $brsjbtn['KodeJabatan']==7)
				{
					echo"
					 <li><a href='homeDkn.php'>Halaman Utama</a></li>
					 <li><a href='dekanRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='dekanLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					  <li><a href='main_forum.php'>Forum</a></li> ";
				}
				else if($brsjbtn['KodeJabatan']==1 || $brsjbtn['KodeJabatan']==2)
				{
					echo"
				 <li><a href='homeWP.php'>Halaman Utama</a></li>
				 <li><a href='wpRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
				 <li><a href='wpLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
				 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
				 <li><a href='laporanKinerjaDosen.php'>Laporan Kinerja Dosen</a></li>
				  <li><a href='main_forum.php'>Forum</a></li>
				  ";
				}
			 ?>
   </ul>
 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
