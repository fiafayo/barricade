<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$itemPerPage = 20;
	$page		 = $_GET['page'];
	$key=$_GET['key'];
	if (empty($page))
		{$page = 1;}
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
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$search=$_REQUEST['search'];
		/*$query="SELECT * FROM pengadaan where (Kode='$username'  AND Status='Belum Terlaksana') AND (NoPengadaan LIKE '%$search%' OR Tanggal LIKE '%$search%' OR Hari LIKE '%$search%') ORDER BY NoPengadaan"; 
		$hasil=mysql_query($query);
		if(@mysql_num_rows($hasil)==0)
		{
				print "<script>
						alert('Data tidak ditemukan!');						
					   </script>";
				print "<script>
						window.location='pengadaanKonsul.php';
					   </script>";
					   exit();
		}
		else
		{$_SESSION['key'] = $search;}*/
		$key="%".$search."%";
		
	}
	if(empty($key))
	{
		$key="%";
	}
	//$key=$_SESSION['key'];
	//if($key)
	//{

		$query="SELECT * FROM pengadaan where (Kode='$username'  AND Status='Belum Terlaksana') AND (NoPengadaan LIKE '$key' OR Tanggal LIKE '$key' OR Hari LIKE '$key') ORDER BY Tanggal DESC"; 
		//$hasil=mysql_query($query);
	//}
	/*else
	{
		$query="SELECT * FROM pengadaan WHERE Kode='$username' AND Status='Belum Terlaksana' ORDER BY Tanggal DESC";
		
	}*/$hasil=mysql_query($query);
	//PAGING
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

	<title>Academic Advisor | Dosen :: Pengadaan Konsultasi </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
<script language="JavaScript" type="text/javascript">
function confirmDelete()
{
	return confirm('Apakah anda yakin ingin menghapus data ?');
	
}
</script>
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" cellpadding="4" cellspacing="4">
			   <tr><td ><a href="tambahPengadaanKonsul.php" border="0"><input type=button value="Tambah Pengadaan Konsultasi" border=0></a></td></tr>
			  <tr>
			  	<td>
					<form action="" method="post">Search: <input type="text" name="search" value=""> <input type="submit" value="Cari" name="Cari"></form>
				<br/><font size="1"><b>Pencarian berdasarkan nomor pengadaan, tanggal (format: yyyy-mm-dd), dan hari</b></font>
				</td>
			  </tr>
			 
			  <tr>
			    <td><br>
				<? $baris=mysql_fetch_assoc($hasil);
				
					if($baris)
					{
						echo "<table border='0' align='center'><caption align='center' style='font-size:24px'>DATA PENGADAAN</caption>
						
						<tr>
							<th>No.Pengadaan</th> <th>Tanggal</th> <th>Hari</th> <th>Jam Mulai</th> <th>Jam Selesai</th> <th>Max Mhs</th> <th>Total Pendaftar</th> <th>Total Batal</th> <th colspan='3'>Pilihan</th>
						</tr>";
						$warna=1;
					   while($baris)
					   {
					   		$no=$baris['NoPengadaan'];
					   		$dftr=mysql_query("SELECT * FROM pendaftaran WHERE NoPengadaan='$no'");
							$jumDaftar=mysql_num_rows($dftr);
							$btl=mysql_query("SELECT * FROM pendaftaran WHERE NoPengadaan='$no' AND Status=1");
							$jumbtl=mysql_num_rows($btl);
					   		if($warna%2==0)
							{
							echo "
					<tr bgcolor='#E2E2E2'><td>".$baris['NoPengadaan']."</td><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>$jumDaftar</td><td>$jumbtl</td><td align='center'><a href='ubahPengadaanKonsul.php?nopengadaan=".$baris['NoPengadaan']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='deletePengadaan.php?nopengadaan=".$baris['NoPengadaan']."' onclick='return confirmDelete();'><img src=images/delete.png border=0 title='Hapus' alt='Hapus'></a></td><td><a href='detailPengadaanKonsul.php?nopengadaan=".$baris['NoPengadaan']."' border=0><input type='button' value='Detail' border='0'></a></td></tr>";
							}
							else
							{
							echo "
					<tr  bgcolor='#F0F0F0'><td>".$baris['NoPengadaan']."</td><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>$jumDaftar</td><td>$jumbtl</td><td align='center'><a href='ubahPengadaanKonsul.php?nopengadaan=".$baris['NoPengadaan']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='deletePengadaan.php?nopengadaan=".$baris['NoPengadaan']."' onclick='return confirmDelete();'><img src=images/delete.png border=0 title='Hapus' alt='Hapus'></a></td><td><a href='detailPengadaanKonsul.php?nopengadaan=".$baris['NoPengadaan']."' border=0><input type='button' value='Detail' border='0'></a></td></tr>";
							}
							$warna=$warna+1;
						$baris=mysql_fetch_assoc($hasil); 
						}
					}
					else
					{
							print "<font color='red' size='5' ><i><center>Tidak ada data pengadaan.</center></i></font>";
							$isi=false;
					}
					 ?>
				</table>
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
								print "<a href='pengadaanKonsul.php?key=$key'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='pengadaanKonsul.php?page=$prev&key=$key'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='pengadaanKonsul.php?page=$i&key=$key'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='pengadaanKonsul.php?page=$next&key=$key'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='pengadaanKonsul.php?page=$totalPage&key=$key'>Last&gt;&gt;</a>";
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
