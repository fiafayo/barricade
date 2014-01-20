<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	
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
	$query="SELECT p.Tanggal, p.Hari, d.JamDatang, d.StatusMhs, m.NRP, m.Nama as NamaMhs, f.Nama as NamaFakultas, j.Nama as NamaJurusan 
	FROM pendaftaran as d, pengadaan as p, mahasiswa as m, fakultas as f, jurusan as j 
	WHERE d.NRP='$username' AND d.NRP=m.NRP AND m.Fakultas=f.Kode AND m.Jurusan=j.Kode AND f.Kode=j.KodeFakultas AND d.NoPengadaan=p.NoPengadaan AND d.Status=0 ORDER BY p.Tanggal";

	
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

	<title> Academic Advisor | Mahasiswa :: Detail Riwayat Konsultasi
		  </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" >
		<? $baris=mysql_fetch_assoc($hasil); 
				if($baris)
				{?>
				<br/><div style="font-size:20px" align="center">Riwayat Konsultasi</div><br/><br/>
		
				<tr>
					<td align="right" width="370"><strong>NRP:</strong></td>
					<td><? echo $baris['NRP']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Nama Mahasiswa:</strong></td>
					<td><? echo $baris['NamaMhs']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Fakultas:</strong></td>
					<td><? echo $baris['NamaFakultas']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Jurusan:</strong></td>
					<td><? echo $baris['NamaJurusan']; ?></td>
				</tr>
			  	<tr>
					<td colspan="2"><br/>
					<? 
					
							echo "<table border='0' align='center' cellpadding='2'>
							
							<tr>
								 <th>Tanggal</th> <th>Hari</th> <th>Jam datang</th> <th>Status</th>
							</tr>
							";
							
							$warna=1;
						   while($baris)
						   {
						   
								if($warna%2==0)
								{
										echo "
								<tr bgcolor='#E2E2E2'><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamDatang']."</td><td>".$baris['StatusMhs']."</td></tr>";
								}
								else
								{
									echo "
								<tr bgcolor='#F0F0F0'><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamDatang']."</td><td>".$baris['StatusMhs']."</td></tr>";
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
			  	<td  colspan="2"align="right">
			  		<?php //PAGING
						if($isi==true)
						{
							if ($page != 1)
							{
								$prev = $page - 1;
								print "<a href='riwayatKonsulMhs.php'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='riwayatKonsulMhs.php?page=$prev'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='riwayatKonsulMhs.php?page=$i'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='riwayatKonsulMhs.php?page=$next'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='riwayatKonsulMhs.php?page=$totalPage'>Last&gt;&gt;</a>";
							}
						}
							//END OF PAGING ?>
					
			  	</td>
			  </tr>
			</table>
		</div><!-- end content -->
		
  <div id="sidebar">
<?php  include_once('menuMahasiswa.php'); ?>   

 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
