<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$itemPerPage = 10;
	$page		 = $_GET['page'];
	$kodos=$_GET['kodos'];
	$semester=$_GET['semester'];
	$kategori=$_GET['kategori'];
	$jurusan=$_GET['jurusan'];
	$fakultas=$_GET['fakultas'];
	
	if (empty($page))
		{$page = 1;}
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
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$jurusan=$_REQUEST['jurusan'];
		$kodos=$_REQUEST['cboDosen'];
		$semester=$_REQUEST['cboSemester'];
		$kategori=$_REQUEST['cboKategori'];
		$fakultas=$_REQUEST['fakultas'];
	}
	if(!empty($kodos) && !empty($semester) && !empty($kategori))
	{
		if($kodos=="%")
		{
		$query="SELECT m.NRP, m.Nama as NamaMhs, h.*,p.Tanggal, k.Nama, o.Nama as NamaDsn, f.Nama as NamaFakultas, j.Nama as NamaJurusan
FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, kategori as k, mahasiswa as m, dosen as o, fakultas as f, jurusan as j
WHERE p.Kode LIKE '%$kodos%' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' AND o.Kode=p.Kode AND o.Fakultas=f.Kode AND o.Jurusan=j.Kode AND f.Kode=j.KodeFakultas AND o.Fakultas LIKE '%$fakultas%' AND o.Jurusan LIKE '%$jurusan%' AND d.NoPengadaan=p.NoPengadaan AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND h.KategoriMasalah=k.Kode ORDER BY p.Kode,d.NRP, p.Tanggal";
		}
		else
		{
		$query="SELECT m.NRP, m.Nama as NamaMhs, h.*,p.Tanggal, k.Nama
FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, kategori as k, mahasiswa as m, dosen as o
WHERE p.Kode LIKE '%$kodos%' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' AND o.Kode=p.Kode AND o.Fakultas LIKE '%$fakultas%' AND o.Jurusan LIKE '%$jurusan%' AND d.NoPengadaan=p.NoPengadaan AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND h.KategoriMasalah=k.Kode ORDER BY p.Kode,d.NRP, p.Tanggal";
		}
		$hasil=mysql_query($query);
		//$j=mysql_num_rows($hasil);
		//echo $j;
		
		if(mysql_num_rows($hasil)<1)
		{
			$kesalahan="data tdk ada";
			$klik=false;
		}
		else
		{
			$totalLaptop	= mysql_num_rows($hasil);
			$totalPage		= ceil($totalLaptop / $itemPerPage);
			
			$start		= ($page - 1) * $itemPerPage;
			$end		= $itemPerPage;
			
			$query = $query." LIMIT $start,$end";
			$hasil = mysql_query($query);
			
			if (($page - 1) < 1)
				$batasBawah = 1;
			else
				$batasBawah = $page - 1;
				
			if (($page + 1) > $totalPage)
				$batasAtas = $totalPage;
			else
				$batasAtas = $page + 1;
			

			$baris=mysql_fetch_assoc($hasil);
		}
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
	<title>Academic Advisor | <? if($brsjbtn['KodeJabatan']==1) echo"Wakil Rektor"; else echo"PLKPAM"; ?> :: Laporan Hasil Konsultasi <? if($brsjbtn['KodeJabatan']==1) echo"Wakil Rektor"; else echo"PLKPAM"; ?> </title>
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
if($kesalahan=="data tdk ada")
{
	print "<script>
			alert('Tidak ada data laporan hasil konsultasi!');						
		   </script>";
}
?>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" cellpadding="4" cellspacing="4">
		<tr>
			<td>
			<br/><div style="font-size:20px" align="center">LAPORAN HASIL KONSULTASI</div><br/>
			<form action="wpLaporanHasilKonsul.php" method="post">
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
						<? $queryjurusan=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='$fakultas' AND KodeFakultas<>0");
							$barisjurusan=mysql_fetch_assoc($queryjurusan);
							if($barisjurusan)
							{
								echo"<select id='jurusan' name='jurusan'>";
								echo"<option value='%' onclick=\"getData('aturDosen.php?kodeFak=$kodeFak&kodeJur='+document.getElementById('jurusan').value,'targetDiv2')\">[Semua Jurusan]</option>";
								while($barisjurusan)
								{
									if($jurusan==$barisjurusan['Kode'])
									{
									echo"<option value='".$barisjurusan['Kode']."' selected onclick=\"getData('aturDosen.php?kodeFak=$kodeFak&kodeJur='+document.getElementById('jurusan').value,'targetDiv2')\">".$barisjurusan['Nama']."</option>";
									}
									else
									{
										echo"<option value='".$barisjurusan['Kode']."' onclick=\"getData('aturDosen.php?kodeFak=$kodeFak&kodeJur='+document.getElementById('jurusan').value,'targetDiv2')\">".$barisjurusan['Nama']."</option>";
									}
									$barisjurusan=mysql_fetch_assoc($queryjurusan);
								}
								echo"</select>";
							}
							else
							{ ?>
							<select name="jurusan" id="jurusan"  onclick="getData('aturDosen.php?kodeFak=$kodeFak&kodeJur='+document.getElementById('jurusan').value,'targetDiv2')">
							<option value="%">[Semua Jurusan]</option>
							
							</select><?  
							 }?>
						</div>
						</td>
					</tr>
				  <tr>
				  	<td align="right" width="38%">
						<strong>Dosen</strong>
					</td>
					<td>
						<div id="targetDiv2">
							<? 
							if(!empty($fakultas) && !empty($jurusan))
							{
								$querydosen=mysql_query("SELECT d.* FROM dosen as d, detailjabatan as dt 
								WHERE d.Fakultas LIKE '%$fakultas%' AND d.Jurusan LIKE '%$jurusan%' AND d.Kode=dt.KodeDosen AND dt.KodeJabatan=5 AND d.Status=0");
								$barisdosen=mysql_fetch_assoc($querydosen);
								if($barisdosen)
								{
									echo"<select id='cboDosen' name='cboDosen'>";
									echo"<option value='%'>[Semua Dosen]</option>";
									while($barisdosen)
									{
										if($kodos==$barisdosen['Kode'])
										{
											echo"<option value='".$barisdosen['Kode']."' selected>".$barisdosen['Nama']."</option>";
										}
										else
										{
											echo"<option value='".$barisdosen['Kode']."'>".$barisdosen['Nama']."</option>";
										}
										
										$barisdosen=mysql_fetch_assoc($querydosen);
									}
									echo"</select>";
								}
							}
							else
							{
								echo"<select id='cboDosen' name='cboDosen'>
									<option value='%'>[Semua Dosen]</option>
									</select>";
							} ?>
						</div>
					</td>
				  </tr>
				   <tr>
				  	<td align="right" width="46%">
						<strong>Kategori Masalah</strong>
					</td>
					<td>
							<select name="cboKategori" id="cboKategori">
							<option value="%">[Semua Kategori]</option>
							<? 
								
								$qkategori=mysql_query("SELECT * FROM kategori");
								
								while($brskategori=mysql_fetch_assoc($qkategori))
								{ 
									if(!empty($kesalahan))
									{
										if($kategori==$brskategori['Kode'])
										{?>
											<option value="<? echo $brskategori['Kode']; ?>" selected><? echo $brskategori['Nama']; ?></option>
								<? 		}
										else
										{	?>
											<option value="<? echo $brskategori['Kode']; ?>"><? echo $brskategori['Nama']; ?></option>
								<?		}
									}
									else
									{	if($kategori==$brskategori['Kode'])
										{?>
											<option value="<? echo $brskategori['Kode']; ?>" selected><? echo $brskategori['Nama']; ?></option>
								<? 		}
										else
										{	?>
											<option value="<? echo $brskategori['Kode']; ?>"><? echo $brskategori['Nama']; ?></option>
								<?		}
									}
							 } ?>
							</select>
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
				  	<td colspan="2" align="center"><input type="submit" name="submit" value="Lihat"><br/><br/></td>
				  </tr>
				  
				  <tr>
				  	<td colspan="2">
						<?	
							
							if(!$baris)
							{
									echo "<table align='center' width='100%'>
									<tr>
										<td colspan='8'><b>Pendamping Akademik:</b>&nbsp;-</td>
										
									</tr>
									<tr>
										<td colspan='8'><b>Jurusan/Prodi/Sub Sistem: </b>&nbsp;-</td>
									</tr>
									<th width='50px'>No</th> <th>Nama Mahasiswa</th> <th width='50px'>NRP</th> <th width='90px'>Frekuensi Kedatangan</th> <th>Kategori Masalah</th> <th>Identifikasi Masalah</th> <th>Saran</th> <th>Hasil yang diperoleh</th>
									<tr>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
										<td align='center'>-</td>
									</tr>
								</table>";
							}
							else
							{
								if($kodos<>"%")
								{
									$klik=true;
									$dsn=mysql_query("SELECT d.Nama as namadsn, f.Nama as namafak, j.Nama as namajur FROM dosen as d, fakultas as f, jurusan as j WHERE d.Kode='$kodos' AND d.Fakultas=f.Kode AND d.Jurusan=j.Kode AND j.KodeFakultas=f.Kode");
									$brsdsn=mysql_fetch_assoc($dsn);
									$warna=$_GET['warna'];
									if(empty($warna))
										{	$warna=1; }
									echo "<table border=0 align='center' width='100%'>
										<tr>
											<td colspan='8'><b>Pendamping Akademik:</b>&nbsp;".$brsdsn['namadsn']."</td>
										</tr>
										<tr>
											<td colspan='8'><b>Jurusan/Prodi/Sub Sistem: </b>&nbsp;".$brsdsn['namafak']." / ".$brsdsn['namajur']."</td>
										</tr>
										<th width='50px'>No</th> <th>Nama Mahasiswa</th> <th width='50px'>NRP</th> <th width='90px'>Frekuensi Kedatangan</th> <th>Kategori Masalah</th> <th>Identifikasi Masalah</th> <th>Saran</th> <th>Hasil yang diperoleh</th>";
										while($baris)
										{
											$nrp=$baris['NRP'];
											$qjumdtg="SELECT d.NRP, m.Nama as NamaMhs, count(d.NRP) as JumDatang
			FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
			WHERE p.Kode LIKE '%$kodos%' AND d.NRP='$nrp' AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND o.Kode=p.Kode AND o.Fakultas LIKE '%$fakultas%' AND o.Jurusan LIKE '%$jurusan%' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' GROUP BY d.NRP ORDER BY d.NRP";
											$ambil=mysql_query($qjumdtg);
											$brsdtg=mysql_fetch_assoc($ambil);
											if($warna%2==0)
											{ 
											echo"<tr bgcolor='#E2E2E2'>
												<td align='center' valign='top'><br/>$warna</td>
												<td align='center' valign='top'><br/>".$baris['NamaMhs']."</td>
												<td align='center' valign='top'><br/>".$baris['NRP']."</td>
												<td align='center' valign='top'><br/>".$brsdtg['JumDatang']."</td>
												<td align='left' valign='top'><br/>".$baris['Nama']."</td>
												<td align='left' valign='top'>".$baris['Permasalahan']."(".$baris['Tanggal'].")"."</td>
												<td align='left' valign='top'>".$baris['Saran']."</td>
												<td align='left' valign='top'>".$baris['HasilKonsultasi']."</td></tr>";
											}
											else
											{
											echo"<tr bgcolor='#F0F0F0'>
												<td align='center' valign='top'><br/>$warna</td>
												<td align='center' valign='top'><br/>".$baris['NamaMhs']."</td>
												<td align='center' valign='top'><br/>".$baris['NRP']."</td>
												<td align='center' valign='top'><br/>".$brsdtg['JumDatang']."</td>
												<td align='left' valign='top'><br/>".$baris['Nama']."</td>
												<td align='left' valign='top'>".$baris['Permasalahan']."(".$baris['Tanggal'].")"."</td>
												<td align='left' valign='top'>".$baris['Saran']."</td>
												<td align='left' valign='top'>".$baris['HasilKonsultasi']."</td></tr>";
											}
													
													
											$warna=$warna+1;
											$baris=mysql_fetch_assoc($hasil);
											
										}
										echo"</table>";
										
								}
								else //-------------SEMUA DOSEN-----------------
								{
									$klik=true;
									
									$warna=$_GET['warna'];
									if(empty($warna))
										{	$warna=1; }
									echo "<table border=0 align='center' width='100%'>
										
										<th width='50px'>No</th> <th>Nama Dosen</th> <th>Nama Mahasiswa</th> <th width='50px'>NRP</th> <th width='90px'>Frekuensi Kedatangan</th> <th>Kategori Masalah</th> <th>Identifikasi Masalah</th> <th>Saran</th> <th>Hasil yang diperoleh</th>";
										while($baris)
										{
											
											$nrp=$baris['NRP'];
											$qjumdtg="SELECT d.NRP, m.Nama as NamaMhs, count(d.NRP) as JumDatang
			FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
			WHERE p.Kode LIKE '%$kodos%' AND d.NRP='$nrp' AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND o.Kode=p.Kode AND o.Fakultas LIKE '%$fakultas%' AND o.Jurusan LIKE '%$jurusan%' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' GROUP BY d.NRP ORDER BY d.NRP";
											$ambil=mysql_query($qjumdtg);
											$brsdtg=mysql_fetch_assoc($ambil);
											if($warna%2==0)
											{ 
											echo"<tr bgcolor='#E2E2E2'>
												<td align='center' valign='top'><br/>$warna</td>
												<td align='left' valign='top'><br/>".$baris['NamaDsn']."<br/>(".$baris['NamaJurusan'].")"."</td>
												<td align='center' valign='top'><br/>".$baris['NamaMhs']."</td>
												<td align='center' valign='top'><br/>".$baris['NRP']."</td>
												<td align='center' valign='top'><br/>".$brsdtg['JumDatang']."</td>
												<td align='left' valign='top'><br/>".$baris['Nama']."</td>
												<td align='left' valign='top'>".$baris['Permasalahan']."(".$baris['Tanggal'].")"."</td>
												<td align='left' valign='top'>".$baris['Saran']."</td>
												<td align='left' valign='top'>".$baris['HasilKonsultasi']."</td></tr>";
											}
											else
											{
											echo"<tr bgcolor='#F0F0F0'>
												<td align='center' valign='top'><br/>$warna</td>
												<td align='left' valign='top'><br/>".$baris['NamaDsn']."<br/>(".$baris['NamaJurusan'].")"."</td>
												<td align='center' valign='top'><br/>".$baris['NamaMhs']."</td>
												<td align='center' valign='top'><br/>".$baris['NRP']."</td>
												<td align='center' valign='top'><br/>".$brsdtg['JumDatang']."</td>
												<td align='left' valign='top'><br/>".$baris['Nama']."</td>
												<td align='left' valign='top'>".$baris['Permasalahan']."(".$baris['Tanggal'].")"."</td>
												<td align='left' valign='top'>".$baris['Saran']."</td>
												<td align='left' valign='top'>".$baris['HasilKonsultasi']."</td></tr>";
											}
											$warna=$warna+1;
											$baris=mysql_fetch_assoc($hasil);
										}
										echo"</table>";
										

								}
							}
						?>

					</td>
				  </tr>
				 </table>
				 </form>
			</td>
		</tr>
		  <tr>
			<td align="right">
				<?php //PAGING
					if($klik==true)
					{
						if ($page != 1)
						{
							$prev = $page - 1;
							$warna=($prev-1)*($itemPerPage)+1;
							print "<a href='wpLaporanHasilKonsul.php?kodos=$kodos'>&lt;&lt;First</a>";
							print "&nbsp;&nbsp;<a href='wpLaporanHasilKonsul.php?page=$prev&kodos=$kodos&warna=$warna'>&lt;Prev<a>&nbsp;&nbsp;";
						}
						
						if ($batasBawah > 1)
							print '. . . ';
						
						for ($i = $batasBawah; $i <= $batasAtas; $i++)
						{
							if ($i == $page)
								print "<b>[$i]</b> ";
							else
							{
								$warna=($i-1)*($itemPerPage)+1;
								print "<a href='wpLaporanHasilKonsul.php?page=$i&kodos=$kodos&warna=$warna'>$i</a> ";
							}
						}
						
						if ($batasAtas < $totalPage)
							print ' . . .';
						
						if ($page != $totalPage)
						{
							$next = $page + 1;
							$warna=($next-1)*($itemPerPage)+1;
							print "&nbsp;&nbsp;<a href='wpLaporanHasilKonsul.php?page=$next&kodos=$kodos&warna=$warna'>Next&gt;</a>&nbsp;&nbsp;";
							$warna=($totalPage-1)*($itemPerPage)+1;
							print "<a href='wpLaporanHasilKonsul.php?page=$totalPage&kodos=$kodos&warna=$warna'>Last&gt;&gt;</a>";
						}
					}
						//END OF PAGING ?>
			</td>
		  </tr>
			</table>
		</div><!-- end content -->
		
  <div id="sidebar">
   <ul> 
    <li><h2>MENU</h2></li>
  <li><a href="homeWP.php">Halaman Utama</a></li>
			 <li><a href="wpRiwayatKonsul.php">Riwayat Konsultasi</a></li>
			 <li><a href="wpLaporanHasilKonsul.php">Laporan Hasil Konsultasi</a></li>
			 <li><a href="resumeKategori.php">Laporan Kategori Masalah</a></li>
			 <li><a href="laporanKinerjaDosen.php">Laporan Kinerja Dosen</a></li>
			 <li><a href="main_forum.php">Forum</a></li>
   </ul>
 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
