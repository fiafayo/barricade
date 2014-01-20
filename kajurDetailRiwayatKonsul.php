<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nrp=$_REQUEST['nrp'];
	$kategori=$_REQUEST['kategori'];
	$isi=true;
	
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	
	$today=getdate();
	$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
	if(empty($kategori))
	{
		$query="SELECT h.*, d.JamDatang, d.NRP, p.Tanggal, m.Nama as NamaMhs, f.Nama as NamaFakultas, j.Nama as NamaJurusan, o.Nama as NamaDsn 
			FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, fakultas as f, jurusan as j, dosen as o
			WHERE d.NRP='$nrp' AND m.NRP=d.NRP AND d.NoPendaftaran=h.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND m.Fakultas=f.Kode AND j.Kode=m.Jurusan AND j.KodeFakultas=f.Kode AND o.Kode=p.Kode AND o.Fakultas=f.Kode AND o.Jurusan=j.Kode ORDER BY p.Tanggal";
	}
	else
	{
		$query="SELECT h.*, d.JamDatang, d.NRP, p.Tanggal, m.Nama as NamaMhs, f.Nama as NamaFakultas, j.Nama as NamaJurusan, o.Nama as NamaDsn 
			FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, fakultas as f, jurusan as j, dosen as o
			WHERE d.NRP='$nrp' AND m.NRP=d.NRP AND d.NoPendaftaran=h.NoPendaftaran AND h.KategoriMasalah='$kategori' AND d.NoPengadaan=p.NoPengadaan AND m.Fakultas=f.Kode AND j.Kode=m.Jurusan AND j.KodeFakultas=f.Kode AND o.Kode=p.Kode AND o.Fakultas=f.Kode AND o.Jurusan=j.Kode ORDER BY p.Tanggal";
	}
	/*$adaisi=mysql_fetch_assoc($querycek);
	if($adaisi)
	{	$query="SELECT  d.*, p.Tanggal, p.Hari, p.JamMulai, p.JamSelesai FROM pendaftaran as d, pengadaan as p WHERE (p.Kode='$username' AND p.Status='Belum Terlaksana' AND p.Tanggal<='$tanggalskrg' AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran NOT IN (SELECT NoPendaftaran FROM hasilkonsultasi)) AND (d.NRP LIKE '$key' OR p.Tanggal LIKE '$key' OR p.Hari LIKE '$key') GROUP BY d.NoPendaftaran ORDER BY d.NoPendaftaran";	}
	else
	{	$query="SELECT  d.*, p.Tanggal, p.Hari, p.JamMulai, p.JamSelesai FROM pendaftaran as d, pengadaan as p WHERE (p.Kode='$username' AND p.Status='Belum Terlaksana' AND p.Tanggal<='$tanggalskrg' AND d.NoPengadaan=p.NoPengadaan) AND (d.NRP LIKE '$key' OR p.Tanggal LIKE '$key' OR p.Hari LIKE '$key') GROUP BY d.NoPendaftaran ORDER BY d.NoPendaftaran";	}
	*/
	
	$hasil=mysql_query($query);		
	
	
	$itemPerPage = 20;
	$page		 = $_GET['page'];

	if (empty($page))
		{$page = 1;}
		
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title> Academic Advisor | Kajur :: Detail Riwayat Konsultasi</title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" >
			  <tr>
			    <td><br/>
				<? $baris=mysql_fetch_assoc($hasil);
				
					if($baris)
					{
						echo "<table border='0' align='center' cellpadding='2'>
						<tr>
							<td colspan='7'><b>NRP & Nama Mahasiswa: </b>".$nrp." & ".$baris['NamaMhs']."</td>
						</tr>
						<tr>
							<td colspan='7'><b>Jurusan/Prodi/Subsistem: </b>".$baris['NamaJurusan']."</td>
						</tr>
						<tr>
							<th>No</th> <th>Tanggal</th> <th>Waktu konsultasi</th> <th>Dosen academic advisor</th> <th>Permasalahan</th> <th>Saran</th> <th>Hasil yang diperoleh</th> <th>Pilihan</th>
						</tr>
						";
						$warna=$_GET['warna'];
						if(empty($warna))
							{$warna=1;}
					   while($baris)
					   {
					   		$mslh=strip_tags($baris['Permasalahan']);
							$srn=strip_tags($baris['Saran']);
							$hslkon=strip_tags($baris['HasilKonsultasi']);
					   		$potonganmslh=substr($mslh,0,20)." ...";
							$potongansrn=substr($srn,0,20)." ...";
							$potonganhsl=substr($hslkon,0,20)." ...";
					   		if(strlen($baris['Permasalahan'])<=20)
							{$potonganmslh=substr($baris['Permasalahan'],0,20);}
							if(strlen($baris['Saran'])<=20)
							{$potongansrn=substr($baris['Saran'],0,20);}
							if(strlen($baris['HasilKonsultasi'])<=20)
							{$potonganhsl=substr($baris['HasilKonsultasi'],0,20);}
							
							if($warna%2==0)
							{
									echo "
							<tr bgcolor='#E2E2E2'><td>".$warna."</td><td>".$baris['Tanggal']."</td><td>".$baris['JamDatang']."</td><td>".$baris['NamaDsn']."</td><td>$potonganmslh</td><td>$potongansrn</td><td>$potonganhsl</td><td><a href='kajurBacaSelengkapnya.php?kode=".$baris['Kode']."&kategori=".$kategori."'><input type='button' value='Detail' border='0'></a></td></tr>";
							}
							else
							{
								echo "
							<tr bgcolor='#F0F0F0'><td>".$warna."</td><td>".$baris['Tanggal']."</td><td>".$baris['JamDatang']."</td><td>".$baris['NamaDsn']."</td><td>$potonganmslh</td><td>$potongansrn</td><td>$potonganhsl</td><td><a href='kajurBacaSelengkapnya.php?kode=".$baris['Kode']."&kategori=".$kategori."'><input type='button' value='Detail' border='0'></a></td></tr>";
							}
							$warna=$warna+1;
							$baris=mysql_fetch_assoc($hasil); }
						echo"</table>";
					}
					else
						{print "<font color='red' size='5'><i>Tidak ada data riwayat konsultasi.</i></font>";
						$isi=false;
						/*print "<script>
								window.location='pembatalanKonsul.php';
							   </script>";*/
							}
					 ?>
				
			    </td>
			  </tr>
			  <tr>
			  	<td align="right">
			  		<?php //PAGING
						if($isi==true)
						{
							if ($page != 1)
							{
								$prev = $page - 1;
								$warna=($prev-1)*($itemPerPage)+1;
								print "<a href='kajurDetailRiwayatKonsul.php?nrp=$nrp&kategori=$kategori'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='kajurDetailRiwayatKonsul.php?page=$prev&nrp=$nrp&warna=$warna&kategori=$kategori'>&lt;Prev<a>&nbsp;&nbsp;";
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
									print "<a href='kajurDetailRiwayatKonsul.php?page=$i&nrp=$nrp&warna=$warna&kategori=$kategori'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								$warna=($next-1)*($itemPerPage)+1;
								print "&nbsp;&nbsp;<a href='kajurDetailRiwayatKonsul.php?page=$next&nrp=$nrp&warna=$warna&kategori=$kategori'>Next&gt;</a>&nbsp;&nbsp;";
								$warna=($totalPage-1)*($itemPerPage)+1;
								print "<a href='kajurDetailRiwayatKonsul.php?page=$totalPage&nrp=$nrp&warna=$warna&kategori=$kategori'>Last&gt;&gt;</a>";
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
