<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$key=$_GET['key'];
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
		$key="%".$search."%";
		/*if(@mysql_num_rows($hasil)==0)
		{
				print "<script>
						alert('Data tidak ditemukan!');						
					   </script>";
				print "<script>
						window.location='mahasiswa.php';
					   </script>";
					   exit();
		}*/
		//else
		//{$_SESSION['keym'] = $search;}
		//$query="SELECT m.*,f.Nama as NamaFakultas, j.Nama as NamaJurusan FROM mahasiswa as m, fakultas as f, jurusan as j WHERE (m.Fakultas=f.Kode AND m.Fakultas=j.KodeFakultas AND f.Kode=j.KodeFakultas AND m.jurusan=j.Kode) AND (m.NRP LIKE '%$search%' OR m.Nama LIKE '%$search%' OR f.Nama LIKE '%$search%' OR j.Nama LIKE '%$search%') ORDER BY m.NRP"; 
		//$hasil=mysql_query($query);
		
	}
	//$key=$_SESSION['keym'];
	if(empty($key))
	{
		$key="%";
		//$query="SELECT m.*,f.Nama as NamaFakultas, j.Nama as NamaJurusan FROM mahasiswa as m, fakultas as f, jurusan as j WHERE (m.Fakultas=f.Kode AND m.Fakultas=j.KodeFakultas AND f.Kode=j.KodeFakultas AND m.jurusan=j.Kode) AND (m.NRP LIKE '%$key%' OR m.Nama LIKE '%$key%' OR f.Nama LIKE '%$key%' OR j.Nama LIKE '%$key%') ORDER BY m.NRP"; 
		//$hasil=mysql_query($query);
		
		
	}
	/*else
	{
		$query="SELECT m.*,f.Nama as NamaFakultas, j.Nama as NamaJurusan FROM mahasiswa as m, fakultas as f, jurusan as j WHERE m.Fakultas=f.Kode AND m.Fakultas=j.KodeFakultas AND f.Kode=j.KodeFakultas AND m.jurusan=j.Kode ORDER BY m.NRP";
		
	}*/
	$query="SELECT m.*,f.Nama as NamaFakultas, j.Nama as NamaJurusan FROM mahasiswa as m, fakultas as f, jurusan as j WHERE (m.Status=0 AND m.Fakultas=f.Kode AND m.Fakultas=j.KodeFakultas AND f.Kode=j.KodeFakultas AND m.jurusan=j.Kode) AND (m.NRP LIKE '%$key%' OR m.Nama LIKE '%$key%' OR f.Nama LIKE '$key' OR j.Nama LIKE '$key') ORDER BY m.NRP"; 
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

	<title>Academic Advisor | Admin :: Mahasiswa </title>
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
            <table border="0"  align="center" width="100%" cellpadding="4" cellspacing="4">
			 <tr><td><a href="tambahUbahMahasiswa.php" border=0><input type=button value="Tambah Data Mahasiswa" border=0></a></td></tr>
			  <tr>
			  	<td>
					<form action="" method="post">Search: <input type="text" name="search" value=""> <input type="submit" value="Cari" name="Cari"></form>
				<br/><font size="1"><b>Pencarian berdasarkan NRP, nama, fakultas, dan jurusan</b></font>
				</td>
			  </tr>
			  <tr>
			    <td><br>
				<? $baris=mysql_fetch_assoc($hasil);
					if($baris)
					{
						echo"<table border='0' align='center' width='100%'><caption align='center' style='font-size:24px'>DATA MAHASISWA</caption>
							
							<tr>
								<th>NRP</th> <th>Nama</th> <th>Fakultas</th> <th>Jurusan</th> <th>Alamat</th> <th>No.Telp</th> <th>E-mail</th> <th colspan='3' width='80px'>Pilihan</th>
							</tr>";
					$warna=1;
					  	 while($baris)
					   {
					   		 if($warna%2==0)
							{
						   echo"
							<tr bgcolor='#E2E2E2'>
								<td>".$baris['NRP']."</td> <td>".$baris['Nama']."</td> <td>".$baris['NamaFakultas']."</td>
								<td>".$baris['NamaJurusan']."</td> <td>".$baris['Alamat']."</td> <td>".$baris['NoTelp']."</td>
								<td>".$baris['Email']."</td> <td align='center'><a href='tambahUbahMahasiswa.php?nrpFrom=".$baris['NRP']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='setPassMahasiswa.php?nrp=".$baris['NRP']."'><img src=images/setpass.png border=0 title='Atur Password' alt='Atur password'></a></td><td align='center'><a href='deleteMahasiswa.php?nrp=".$baris['NRP']."' onclick='return confirmDelete();'><img src=images/delete.png border=0 title='Hapus' alt='Hapus'></a></td>
							</tr>";
							
							}
							else
							{
							echo"<tr bgcolor='#F0F0F0'>
								<td>".$baris['NRP']."</td> <td>".$baris['Nama']."</td> <td>".$baris['NamaFakultas']."</td>
								<td>".$baris['NamaJurusan']."</td> <td>".$baris['Alamat']."</td> <td>".$baris['NoTelp']."</td>
								<td>".$baris['Email']."</td> <td align='center'><a href='tambahUbahMahasiswa.php?nrpFrom=".$baris['NRP']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='setPassMahasiswa.php?nrp=".$baris['NRP']."'><img src=images/setpass.png border=0 title='Atur Password' alt='Atur password'></a></td><td align='center'><a href='deleteMahasiswa.php?nrp=".$baris['NRP']."' onclick='return confirmDelete();'><img src=images/delete.png border=0 title='Hapus' alt='Hapus'></a></td>
							</tr>";
							}
							$warna=$warna+1;
							$baris=mysql_fetch_assoc($hasil); 
						}
						echo"</table>";
					} 
					else
					{
						print "<font color='red' size='5' ><i><center>Tidak ada data mahasiswa.</center></i></font>";
							$isi=false;
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
								print "<a href='mahasiswa.php?key=$key'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='mahasiswa.php?page=$prev&key=$key'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='mahasiswa.php?page=$i&key=$key'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='mahasiswa.php?page=$next&key=$key'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='mahasiswa.php?page=$totalPage&key=$key'>Last&gt;&gt;</a>";
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
		 <li><a href="admin.php">Halaman Utama</a></li>
		 <li><a href="kategori.php">Kategori Masalah</a></li>
		   <li><a href="fakultas.php">Fakultas</a></li>
			   <li><a href="jurusan.php">Jurusan</a></li>
		 <li><a href="karyawan.php">Karyawan</a></li>
		 <li><a href="mahasiswa.php" >Mahasiswa</a></li>
		  <li><a href="forum.php">Forum</a></li>
		    <li><a href="settingmail.php">Setting E-mail</a></li>
	   </ul>
	 </div> 

   		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

