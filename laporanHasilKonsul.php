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
	$qusr=mysql_query("SELECT * FROM dosen WHERE Kode='$username' ");
	$brsusr=mysql_fetch_assoc($qusr);
	$fak=$brsusr['Fakultas'];
	$jur=$brsusr['Jurusan'];
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		
		$kodos=$_REQUEST['cboDosen'];
		$semester=$_REQUEST['cboSemester'];
		$kategori=$_REQUEST['cboKategori'];
		//if(empty($kodos))
		//{
			//$kesalahan="cbo kosong";
		//}
		
	}
	if(!empty($kodos) && !empty($semester) && !empty($kategori))
	{
		//if($semester=="%" && $kategori=="%")
		
		/*$query="SELECT d.NRP, m.Nama as NamaMhs, count(d.NRP) as JumDatang
			FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
			WHERE p.Kode LIKE '%$kodos%' AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND o.Kode=p.Kode AND o.Fakultas='$fak' AND o.Jurusan='$jur' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' GROUP BY d.NRP ORDER BY d.NRP";
		*/
		if($kodos=="%")
		{
		$query="SELECT m.NRP, m.Nama as NamaMhs, h.*,p.Tanggal, k.Nama, o.Nama as NamaDsn, f.Nama as NamaFakultas, j.Nama as NamaJurusan
FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, kategori as k, mahasiswa as m, dosen as o, fakultas as f, jurusan as j
WHERE p.Kode LIKE '%$kodos%' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' AND o.Kode=p.Kode AND o.Fakultas=f.Kode AND o.Jurusan=j.Kode AND f.Kode=j.KodeFakultas AND o.Fakultas='$fak' AND o.Jurusan='$jur' AND d.NoPengadaan=p.NoPengadaan AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND h.KategoriMasalah=k.Kode ORDER BY p.Kode, p.Tanggal";
		}
		else
		{
		$query="SELECT m.NRP, m.Nama as NamaMhs, h.*,p.Tanggal, k.Nama
FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, kategori as k, mahasiswa as m, dosen as o
WHERE p.Kode LIKE '%$kodos%' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' AND o.Kode=p.Kode AND o.Fakultas='$fak' AND o.Jurusan='$jur' AND d.NoPengadaan=p.NoPengadaan AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND h.KategoriMasalah=k.Kode ORDER BY d.NRP, p.Tanggal";
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

	<title>Academic Advisor | Kajur :: Laporan Hasil Konsultasi </title>
	<link rel="stylesheet" href="style.css" media="screen" />	

</head>
<body>
<?
if($kesalahan=="data tdk ada")
{
	$isitglMulai=$tglMulai;
	$isitglSelesai=$tglSelesai;
	$isinrp=$nrp;
	$isikategori=$kategori;
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
			<form action="laporanHasilKonsul.php" method="post">
				<table border="0" align="center" cellpadding="2" cellspacing="2" width="100%">
				  <tr>
				  	<td align="right" width="46%">
						<strong>Dosen</strong>
					</td>
					<td>
							<select name="cboDosen" id="cboDosen">
							<option value="%">[Semua Dosen]</option>
							<? 
								$today=getdate();
								$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
								$qdsn=mysql_query("SELECT d.* FROM dosen as d, detailjabatan as dt WHERE d.Fakultas='$fak' AND d.Jurusan='$jur' AND dt.KodeDosen=d.Kode AND dt.KodeJabatan=5");//  AND '$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir");
								
								while($brsdsn=mysql_fetch_assoc($qdsn))
								{ 
									if(!empty($kesalahan))
									{
										if($kodos==$brsdsn['Kode'])
										{?>
											<option value="<? echo $brsdsn['Kode']; ?>" selected><? echo $brsdsn['Nama']; ?></option>
								<? 		}
										else
										{	?>
											<option value="<? echo $brsdsn['Kode']; ?>"><? echo $brsdsn['Nama']; ?></option>
								<?		}
									}
									else
									{	if($kodos==$brsdsn['Kode'])
										{?>
											<option value="<? echo $brsdsn['Kode']; ?>" selected><? echo $brsdsn['Nama']; ?></option>
								<? 		}
										else
										{	?>
											<option value="<? echo $brsdsn['Kode']; ?>"><? echo $brsdsn['Nama']; ?></option>
								<?		}
									}
							 } ?>
							</select>
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
									if(!empty($kesalahan))
									{
										if($semester==$arr[$j])
										{?>
											<option value="<? echo $arr[$k]; ?>" selected><? echo $ganjilgenap." ".substr($arr[$k],1,2)."-".substr($arr[$k],3,2); ?></option>
								<? 		}
										else
										{	?>
											<option value="<? echo $arr[$k]; ?>"><? echo $ganjilgenap." ".substr($arr[$k],1,2)."-".substr($arr[$k],3,2); ?></option>
								<?		}
									}
									else
									{	if($semester==$arr[$k])
										{?>
											<option value="<? echo $arr[$k]; ?>" selected><? echo $ganjilgenap." ".substr($arr[$k],1,2)."-".substr($arr[$k],3,2); ?></option>
								<? 		}
										else
										{	?>
											<option value="<? echo $arr[$k]; ?>"><? echo $ganjilgenap." ".substr($arr[$k],1,2)."-".substr($arr[$k],3,2); ?></option>
								<?		}
									}
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
			WHERE p.Kode LIKE '%$kodos%' AND d.NRP='$nrp' AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND o.Kode=p.Kode AND o.Fakultas='$fak' AND o.Jurusan='$jur' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' GROUP BY d.NRP ORDER BY d.NRP";
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
			WHERE p.Kode LIKE '%$kodos%' AND d.NRP='$nrp' AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND o.Kode=p.Kode AND o.Fakultas='$fak' AND o.Jurusan='$jur' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' GROUP BY d.NRP ORDER BY d.NRP";
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
		  <tr >
			<td align="right">
				<?php //PAGING
					if($klik==true)
					{
						if ($page != 1)
						{
							$prev = $page - 1;
							$warna=($prev-1)*($itemPerPage)+1;
							print "<a href='laporanHasilKonsul.php?kodos=$kodos&semester=$semester&kategori=$kategori'>&lt;&lt;First</a>";
							print "&nbsp;&nbsp;<a href='laporanHasilKonsul.php?page=$prev&kodos=$kodos&warna=$warna&semester=$semester&kategori=$kategori'>&lt;Prev<a>&nbsp;&nbsp;";
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
								print "<a href='laporanHasilKonsul.php?page=$i&kodos=$kodos&warna=$warna&semester=$semester&kategori=$kategori'>$i</a> ";
							}
						}
						
						if ($batasAtas < $totalPage)
							print ' . . .';
						
						if ($page != $totalPage)
						{
							$next = $page + 1;
							$warna=($next-1)*($itemPerPage)+1;
							print "&nbsp;&nbsp;<a href='laporanHasilKonsul.php?page=$next&kodos=$kodos&warna=$warna&semester=$semester&kategori=$kategori'>Next&gt;</a>&nbsp;&nbsp;";
							$warna=($totalPage-1)*($itemPerPage)+1;
							print "<a href='laporanHasilKonsul.php?page=$totalPage&kodos=$kodos&warna=$warna&semester=$semester&kategori=$kategori'>Last&gt;&gt;</a>";
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
 	<li><a href="homeKajur.php">Halaman Utama</a></li>
	 <li><a href="kajurRiwayatKonsul.php">Riwayat Konsultasi</a></li>
	 <li><a href="laporanHasilKonsul.php">Laporan Hasil Konsultasi</a></li>
	 <li><a href="resumeKategori.php">Laporan Kategori Masalah</a></li>
	 <li><a href="main_forum.php">Forum</a></li>
   </ul>
 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
