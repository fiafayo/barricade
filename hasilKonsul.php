<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$isi=true;
	$key=$_GET['key'];
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
		$search=$_REQUEST['search'];
		$key="%".$search."%";
	}
	if(empty($key))
	{
		$key="%";
	}

	$today=getdate();
	$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
	$querycek=mysql_query("SELECT * FROM hasilkonsultasi");
	$adaisi=mysql_fetch_assoc($querycek);
	if($adaisi)
	{	$query="SELECT  d.*, p.Tanggal, p.Hari, p.JamMulai, p.JamSelesai, m.Nama FROM pendaftaran as d, pengadaan as p, mahasiswa as m WHERE (p.Kode='$username' AND p.Status='Belum Terlaksana' AND p.Tanggal<='$tanggalskrg' AND d.NoPengadaan=p.NoPengadaan AND d.StatusMhs='Belum Dilayani' AND d.Status=0 AND d.NRP=m.NRP AND d.NoPendaftaran NOT IN (SELECT NoPendaftaran FROM hasilkonsultasi)) AND (d.NRP LIKE '$key' OR m.Nama LIKE '$key' OR p.Tanggal LIKE '$key' OR p.Hari LIKE '$key') GROUP BY d.NoPendaftaran ORDER BY d.NoPendaftaran";	}
	else
	{	$query="SELECT  d.*, p.Tanggal, p.Hari, p.JamMulai, p.JamSelesai, m.Nama FROM pendaftaran as d, pengadaan as p, mahasiswa as m WHERE (p.Kode='$username' AND p.Status='Belum Terlaksana' AND p.Tanggal<='$tanggalskrg' AND d.NoPengadaan=p.NoPengadaan AND d.StatusMhs='Belum Dilayani' AND d.Status=0 AND d.NRP=m.NRP) AND (d.NRP LIKE '$key' OR m.Nama LIKE '$key' OR p.Tanggal LIKE '$key' OR p.Hari LIKE '$key') GROUP BY d.NoPendaftaran ORDER BY d.NoPendaftaran";	}
	$hasil=mysql_query($query);		

	
	$itemPerPage = 12;
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

	<title>Academic Advisor | Dosen :: Hasil Konsultasi </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>

		<div id="content">
		<table border="0" align="center" width="100%">
		<tr><td >Mahasiswa belum daftar? <a href="konsultasiLangsung.php" border="0"><input type="button" value="Daftarkan Sekarang" border="0" /></a><hr /></td></tr>
			  <tr>
			  	<td>
					<form action="" method="post">Search: <input type="text" name="search" value=""> <input type="submit" value="Cari" name="Cari"></form>
				<br/><font size="1"><b>Pencarian berdasarkan NRP, nama, tanggal (format: yyyy-mm-dd), dan hari</b></font>
				</td>
			  </tr> 
			  <tr>
			    <td><br/><br/>
				<? $baris=mysql_fetch_assoc($hasil);
				
					if($baris)
					{
						echo"<div style='font-size:20px' align='center'>CATAT HASIL KONSULTASI</div><br/>";
						echo "<table border='0' align='center'><caption style='font-size:12px;'><i><b><font color='red'>Klik pada No.Pendaftaran untuk mencatat hasil konsultasi</font></b></i></caption>
						<tr>
							<th>No.Pendaftaran</th> <th>NRP</th> <th>Nama</th> <th>No.Pengadaan</th> <th>Tanggal</th> <th>Hari</th> 
						</tr>";
						$warna=1;
					   while($baris)
					   {
					   	$nopendaftaran=$baris['NoPendaftaran'];
							if($warna%2==0)
							{
									echo "
							<tr bgcolor='#E2E2E2'><td align='center'><a href='detailHasilKonsul.php?nopendaftaran=$nopendaftaran'>".$baris['NoPendaftaran']."</a></td><td align='center'>".$baris['NRP']."</td><td align='center'>".$baris['Nama']."</td><td align='center'>".$baris['NoPengadaan']."</td><td align='center'>".$baris['Tanggal']."</td><td align='center'>".$baris['Hari']."</td></tr>";
							}
							else
							{
								echo "
							<tr bgcolor='#F0F0F0'><td align='center'><a href='detailHasilKonsul.php?nopendaftaran=$nopendaftaran'>".$baris['NoPendaftaran']."</a></td><td align='center'>".$baris['NRP']."</td><td align='center'>".$baris['Nama']."</td><td align='center'>".$baris['NoPengadaan']."</td><td align='center'>".$baris['Tanggal']."</td><td align='center'>".$baris['Hari']."</td></tr>";
							}
							$warna=$warna+1;
							$baris=mysql_fetch_assoc($hasil); }
						echo"</table>";
					}
					else
						{print "<font color='red' size='5'><i>Tidak ada data pendaftaran yang dapat dicatat.</i></font>";
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
								print "<a href='hasilKonsul.php?key=$key'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='hasilKonsul.php?page=$prev&key=$key'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='hasilKonsul.php?page=$i&key=$key'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='hasilKonsul.php?page=$next&key=$key'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='hasilKonsul.php?page=$totalPage&key=$key'>Last&gt;&gt;</a>";
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
