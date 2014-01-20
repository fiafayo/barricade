<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$isi=true;
	$key=$_GET['key'];
	$itemPerPage = 20;
	$page		 = $_GET['page'];

	if (empty($page))
		{$page = 1;}
		
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
	
	$querycek=mysql_query("SELECT * FROM hasilkonsultasi");
	$adaisi=mysql_fetch_assoc($querycek);
	$today=getdate();
	$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
	
	if($adaisi)
	{
		$query="SELECT p.*, COUNT(d.NoPengadaan) as JumDaftar FROM pengadaan as p, pendaftaran as d
		WHERE (p.Kode='$username' AND p.Status='Belum Terlaksana' AND p.NoPengadaan=d.Nopengadaan AND p.Tanggal>'$tanggalskrg' AND d.StatusMhs='Belum Dilayani' AND p.NoPengadaan NOT IN (SELECT d.NoPengadaan FROM pendaftaran as d, hasilkonsultasi as h WHERE d.NoPendaftaran=h.NoPendaftaran)) AND (p.NoPengadaan LIKE '$key' OR p.Tanggal LIKE '$key' OR p.Hari LIKE '$key') GROUP BY p.NoPengadaan ORDER BY p.NoPengadaan";
	}
	else
	{
		$query="SELECT p.*, COUNT(d.NoPengadaan) as JumDaftar FROM pengadaan as p, pendaftaran as d 
		WHERE (p.Kode='$username' AND p.Status='Belum Terlaksana' AND p.NoPengadaan=d.Nopengadaan AND p.Tanggal>'$tanggalskrg' AND d.StatusMhs='Belum Dilayani') AND (p.NoPengadaan LIKE '$key' OR p.Tanggal LIKE '$key' OR p.Hari LIKE '$key') GROUP BY p.NoPengadaan ORDER BY p.NoPengadaan";
	}
	$hasil=mysql_query($query);
	

	
	
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

	<title>Academic Advisor | Dosen :: Pembatalan Konsultasi </title>
	<!-- <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" /> -->
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
<?php  include_once('header.php'); ?>
 
		<div id="content">
    <table border="0" align="center" width="100%">
			  <tr>
			  	<td>
					<form action="" method="post">Search: <input type="text" name="search" value=""> <input type="submit" value="Cari" name="Cari"></form>
				<br/><font size="1"><b>Pencarian berdasarkan nomor pengadaan, tanggal (format: yyyy-mm-dd), dan hari</b></font>
				</td>
			  </tr>
			  <tr>
			    <td><br/><br/>
				<? $baris=mysql_fetch_assoc($hasil);
					if($baris)
					{
						echo"<div style='font-size:20px' align='center'>PEMBATALAN KONSULTASI</div><br/>";
						echo "<table border='0' align='center'><caption align='center' style='font-size:12px'><i><b><font color='red'>Klik pada nomor pengadaan untuk membatalkan konsultasi</font></b></i></caption>
						
						<tr>
							<th>No.Pengadaan</th> <th>Tanggal</th> <th>Hari</th> <th>Jam Mulai</th> <th>Jam Selesai</th> <th>Max Mhs</th> <th>Total Pendaftar</th> <th>Total Pendaftar Batal</th> 
						</tr>";
						$warna=1;
					   while($baris)
					   {
					   	$nop=$baris['NoPengadaan'];
						$btl=mysql_query("SELECT * FROM pendaftaran WHERE NoPengadaan='$nop' AND Status=1");
						$totbtl=mysql_num_rows($btl);
							if($warna%2==0)
							{
									echo "
							<tr bgcolor='#E2E2E2'><td><a href='setBatalKonsul.php?nopengadaan=$nop'>".$baris['NoPengadaan']."</a></td><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['JumDaftar']."</td><td>$totbtl</td></tr>";
							}
							else
							{
								echo "
							<tr bgcolor='#F0F0F0'><td><a href='setBatalKonsul.php?nopengadaan=$nop'>".$baris['NoPengadaan']."</a></td><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['JumDaftar']."</td><td>$totbtl</td></tr>";
							}
							$warna=$warna+1;
							$baris=mysql_fetch_assoc($hasil); }
							echo"</table>";
					}
					else
						{print "<font color='red' size='5'><i>Tidak ada pengadaan yang dapat dibatalkan.</i></font>";
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
								print "<a href='pembatalanKonsul.php'?key=$key>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='pembatalanKonsul.php?page=$prev&key=$key'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='pembatalanKonsul.php?page=$i&key=$key'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='pembatalanKonsul.php?page=$next&key=$key'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='pembatalanKonsul.php?page=$totalPage&key=$key'>Last&gt;&gt;</a>";
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
