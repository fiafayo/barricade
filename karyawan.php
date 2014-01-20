<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$key=$_GET['key'];
	$hasilsearch="";
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
		//$query="SELECT d.*, f.Nama as NamaFakultas, j.nama as NamaJurusan FROM dosen as d, fakultas as f, jurusan as j WHERE (d.Fakultas=f.Kode AND d.Jurusan=j.Kode AND f.Kode=j.KodeFakultas) AND (d.Kode LIKE '%$search%' OR d.Nama LIKE '%$search%' OR j.Nama LIKE '%$search%' OR f.Nama LIKE '%$search%') ORDER BY d.Kode"; 
	}
	if(empty($key))
	{
		$key="%";
	}
	$today=getdate();
	$thnskrg=$today['year'];
	$query="SELECT d.*, f.Nama as NamaFakultas, j.Nama as NamaJurusan FROM dosen as d, fakultas as f, jurusan as j WHERE (d.Fakultas=f.Kode AND d.Jurusan=j.Kode AND f.Kode=j.KodeFakultas AND d.Status=0) AND (d.Kode LIKE '$key' OR d.Nama LIKE '$key' OR j.Nama LIKE '$key' OR f.Nama LIKE '$key') ORDER BY d.Kode"; //AND $thnskrg>=dt.TahunAwal AND $thnskrg<=dt.TahunAkhir
	/*else
	{
		$query="SELECT d.*, f.Nama as NamaFakultas, j.nama as NamaJurusan FROM dosen as d, fakultas as f, jurusan as j WHERE d.Fakultas=f.Kode AND d.Jurusan=j.Kode AND f.Kode=j.KodeFakultas ORDER BY d.Kode";
	}*/
	$hasil=mysql_query($query);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Admin :: Karyawan </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
<script type="text/javascript" language = "javascript">
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
			  <tr><td><a href="tambahKaryawan.php" border=0><input type=button value="Tambah Data Karyawan" border=0></a></td></tr>
			  <tr>
			  	<td>
					<form action="" method="post">Search: <input type="text" name="search" value=""> <input type="submit" value="Cari" name="Cari"></form>
				<br/><font size="1"><b>Pencarian berdasarkan kode karyawan, nama karyawan, fakultas, dan jurusan</b></font>
				</td>
			  </tr>
			  <tr>
			    <td><br>
				<? 
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
					$baris=mysql_fetch_assoc($hasil);
					if($baris)
					{
						echo "<table border='0' align='center' width='100%'><caption align='center' style='font-size:24px'>DATA KARYAWAN</caption>
						
						<tr>
							<th>Kode</th> <th>Nama</th> <th>Fakultas</th> <th>Jurusan</th> <th>Alamat</th> <th>No.Telp</th> <th>E-mail</th> <th colspan='4' width='80px'>Pilihan</th>
						</tr>";
						$warna=1;
					   while($baris)
					   {
					    if($warna%2==0)
						{
					   		if($baris['Kode']=="admin")
							{
								echo "
						<tr bgcolor='#E2E2E2'><td>".$baris['Kode']."</td><td>".$baris['Nama']."</td><td>".$baris['NamaFakultas']."</td><td>".$baris['NamaJurusan']."</td><td>".$baris['Alamat']."</td> <td>".$baris['NoTelp']."</td><td>".$baris['Email']."</td> <td align='center'><a href='ubahKaryawan.php?kode=".$baris['Kode']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='setPassKaryawan.php?kode=".$baris['Kode']."'><img src=images/setpass.png border=0 title='Atur Password' alt='Atur password'></a></td></tr>";
							}
							else
							{
								echo "
						<tr bgcolor='#E2E2E2'><td>".$baris['Kode']."</td><td>".$baris['Nama']."</td><td>".$baris['NamaFakultas']."</td><td>".$baris['NamaJurusan']."</td><td>".$baris['Alamat']."</td> <td>".$baris['NoTelp']."</td><td>".$baris['Email']."</td> <td align='center'><a href='ubahKaryawan.php?kode=".$baris['Kode']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='setPassKaryawan.php?kode=".$baris['Kode']."'><img src=images/setpass.png border=0 title='Atur Password' alt='Atur password'></a></td><td align='center'><a href='deleteKaryawan.php?kode=".$baris['Kode']."' onclick='return confirmDelete();'><img src=images/delete.png border=0 title='Hapus' alt='Hapus'></a></td><td><a href='setJabatan.php?kode=".$baris['Kode']."'><input type='button' border='0' value='Jabatan'></a></td></tr>";					}
						}
						else
						{
							if($baris['Kode']=="admin")
							{
								echo "
						<tr bgcolor='#F0F0F0'><td>".$baris['Kode']."</td><td>".$baris['Nama']."</td><td>".$baris['NamaFakultas']."</td><td>".$baris['NamaJurusan']."</td><td>".$baris['Alamat']."</td> <td>".$baris['NoTelp']."</td><td>".$baris['Email']."</td> <td align='center'><a href='ubahKaryawan.php?kode=".$baris['Kode']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='setPassKaryawan.php?kode=".$baris['Kode']."'><img src=images/setpass.png border=0 title='Atur Password' alt='Atur password'></a></td></tr>";
							}
							else
							{
								echo "
						<tr bgcolor='#F0F0F0'><td>".$baris['Kode']."</td><td>".$baris['Nama']."</td><td>".$baris['NamaFakultas']."</td><td>".$baris['NamaJurusan']."</td><td>".$baris['Alamat']."</td> <td>".$baris['NoTelp']."</td><td>".$baris['Email']."</td> <td align='center'><a href='ubahKaryawan.php?kode=".$baris['Kode']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='setPassKaryawan.php?kode=".$baris['Kode']."'><img src=images/setpass.png border=0 title='Atur Password' alt='Atur password'></a></td><td align='center'><a href='deleteKaryawan.php?kode=".$baris['Kode']."' onclick='return confirmDelete();'><img src=images/delete.png border=0 title='Hapus' alt='Hapus'></a></td><td><a href='setJabatan.php?kode=".$baris['Kode']."'><input type='button' style='bordercolor:white' value='Jabatan'></a></td></tr>";					}
						}
						$warna=$warna+1;
					 $baris=mysql_fetch_assoc($hasil); 
					 }
					 //PAGING
					 echo"<tr><td colspan='14' align='right'>";
							if ($page != 1)
							{
								$prev = $page - 1;
								print "<a href='karyawan.php?key=$key'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='karyawan.php?page=$prev&key=$key'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='karyawan.php?page=$i&key=$key'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='karyawan.php?page=$next&key=$key'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='karyawan.php?page=$totalPage&key=$key'>Last&gt;&gt;</a>";
							}
							echo"</td></tr>";
							//END OF PAGING 
					 echo"</table>";
					}
					else
						{print "<font color='red' size='5' ><i><center>Tidak ada data karyawan.</center></i></font>";
							}
							
					
					 ?>
				
			    </td>
			  </tr>
			  <tr>
			  	<td align="right">
			  		<?php ?>
			  	</td>
			  </tr>
			</table>
		</div><!-- end content -->
		
  <div id="sidebar">
   <ul>
     <li><h2>MENU</h2></li>
     <li><a href="admin.php">Halaman Utama</a></li>
	 <li><a href="kategori.php">Kategori Masalah</a></li>
	 <li><a href="fakultas.php">Fakultas</a></li
    ><li><a href="jurusan.php">Jurusan</a></li>
     <li><a href="karyawan.php">Karyawan</a></li>
     <li><a href="mahasiswa.php">Mahasiswa</a></li>
	  <li><a href="forum.php">Forum</a></li>
	    <li><a href="settingmail.php">Setting E-mail</a></li>
   </ul>
 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
