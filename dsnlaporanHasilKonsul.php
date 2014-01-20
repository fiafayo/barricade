<?php
	session_start();
	include("config.php");
        if(isset($_REQUEST['xls'])) $xls=1; else $xls=0;
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$itemPerPage = 10;
	$page		 = $_GET['page'];
	
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
		$semester=$_REQUEST['cboSemester'];
		$kategori=$_REQUEST['cboKategori'];
		//if(empty($kodos))
		//{
			//$kesalahan="cbo kosong";
		//}
		
	}
	if(!empty($semester) && !empty($kategori))
	{
		$query="SELECT m.NRP, m.Nama as NamaMhs, h.*,p.Tanggal, k.Nama
FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, kategori as k, mahasiswa as m, dosen as o
WHERE p.Kode LIKE '%$username%' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' AND o.Kode=p.Kode AND o.Fakultas='$fak' AND o.Jurusan='$jur' AND d.NoPengadaan=p.NoPengadaan AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND h.KategoriMasalah=k.Kode ORDER BY d.NRP, p.Tanggal";
		
		$hasil=mysql_query($query);
		
		if(mysql_num_rows($hasil)<1)
		{
			$kesalahan="data tdk ada";
			$klik=false;
		}
		else
		{
                    if (!$xls) {
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
                    }		    
		    $baris=mysql_fetch_assoc($hasil);
                    
		}
	}


if (!$xls) {
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Dosen :: Laporan Hasil Konsultasi </title>
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
			<form action="dsnlaporanHasilKonsul.php" method="post">
				<table border="0" align="center" cellpadding="2" cellspacing="2" width="100%">
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
									$klik=true;
									$dsn=mysql_query("SELECT d.Nama as namadsn, f.Nama as namafak, j.Nama as namajur FROM dosen as d, fakultas as f, jurusan as j WHERE d.Kode='$username' AND d.Fakultas=f.Kode AND d.Jurusan=j.Kode AND j.KodeFakultas=f.Kode");
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
			WHERE p.Kode LIKE '%$username%' AND d.NRP='$nrp' AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND o.Kode=p.Kode AND o.Fakultas='$fak' AND o.Jurusan='$jur' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' GROUP BY d.NRP ORDER BY d.NRP";
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
						?>

					</td>
				  </tr>
				 </table>
				 </form>
                                            <div>
                                                <?php
                                                list($usec, $sec) = explode(" ", microtime());
                                                $kode=$sec.'_'.$usec;
                                                ?>
                                                <form action="dsnlaporanHasilKonsul.php?t=<?php echo $kode;?>" method="post">


                                                <?php
                                            $kat=isset($_REQUEST['cboKategori']) ? $_REQUEST['cboKategori'] : '%';
                                            $sem=isset($_REQUEST['cboSemester']) ? $_REQUEST['cboSemester'] : '%';

                                            ?>
                                                    <input type="hidden" name="xls" value="1"/>
                                                    <input type="hidden" name="cboKategori" value="<?php echo $kat?>" />
                                                    <input type="hidden" name="cboSemester" value="<?php echo $sem?>" />

                                            <input type="submit" name="commit" value="Export ke XLS" />
                                                </form></div>
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
							print "<a href='laporanHasilKonsul.php?semester=$semester&kategori=$kategori'>&lt;&lt;First</a>";
							print "&nbsp;&nbsp;<a href='laporanHasilKonsul.php?page=$prev&warna=$warna&semester=$semester&kategori=$kategori'>&lt;Prev<a>&nbsp;&nbsp;";
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
								print "<a href='laporanHasilKonsul.php?page=$i&warna=$warna&semester=$semester&kategori=$kategori'>$i</a> ";
							}
						}
						
						if ($batasAtas < $totalPage)
							print ' . . .';
						
						if ($page != $totalPage)
						{
							$next = $page + 1;
							$warna=($next-1)*($itemPerPage)+1;
							print "&nbsp;&nbsp;<a href='laporanHasilKonsul.php?page=$next&warna=$warna&semester=$semester&kategori=$kategori'>Next&gt;</a>&nbsp;&nbsp;";
							$warna=($totalPage-1)*($itemPerPage)+1;
							print "<a href='laporanHasilKonsul.php?page=$totalPage&warna=$warna&semester=$semester&kategori=$kategori'>Last&gt;&gt;</a>";
						}
					}
						//END OF PAGING ?>
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

<?php
} else {
    list($usec, $sec) = explode(" ", microtime());
    $kode=$sec.'_'.$usec;
    header("Content-Type:application/vnd.ms-excel");
    header('Content-Disposition:attachment; filename='.$kode.'.xls');
    echo '<html><body>';

									$dsn=mysql_query("SELECT d.Nama as namadsn, f.Nama as namafak, j.Nama as namajur FROM dosen as d, fakultas as f, jurusan as j WHERE d.Kode='$username' AND d.Fakultas=f.Kode AND d.Jurusan=j.Kode AND j.KodeFakultas=f.Kode");
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
			WHERE p.Kode LIKE '%$username%' AND d.NRP='$nrp' AND d.NRP=m.NRP AND d.NoPendaftaran=h.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND o.Kode=p.Kode AND o.Fakultas='$fak' AND o.Jurusan='$jur' AND h.NoPendaftaran LIKE '%$semester%' AND h.KategoriMasalah LIKE '%$kategori%' GROUP BY d.NRP ORDER BY d.NRP";
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
										echo"</table></body></html>";



}
?>