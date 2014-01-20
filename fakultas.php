<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$sortby=$_GET['sortby'];
	$key=$_GET['key'];
	$keyword="";
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
		//$keyword=$search;
		//$query="SELECT * FROM fakultas where Kode<>0 AND (Kode LIKE '%$search%' OR Nama LIKE '%$search%')"; 
	}
	if(empty($key))
	{
		$key="%";
	}
	if(empty($sortby))
	{	$sortby="Kode";}
	$query="SELECT * FROM fakultas where Kode<>0 AND (Kode LIKE '$key' OR Nama LIKE '$key') ORDER BY $sortby";
	/*else
	{
		$query="SELECT * FROM fakultas WHERE Kode<>0 ORDER BY Kode";
	}*/
	$hasil=mysql_query($query);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Admin :: Fakultas </title>
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
			  <tr><td><a href="tambahUbahFakultas.php" border=0><input type=button value="Tambah Data Fakultas" border=0></a></td></tr>
			  <tr>
			  	<td >
					<form action="fakultas.php" method="post">Search: <input type="text" name="search" value=""> <input type="submit" value="Cari" name="Cari"></form><br/>
				<font size="1"><b>Pencarian berdasarkan kode dan nama fakultas</b></font>
				</td>
			  </tr>
			
			  <tr>
 			    <td><br><div id="sortDiv">
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
						$warna=1;
						/*echo "<table border='0' align='center'><caption align='center' style='font-size:20px'>Data Fakultas</caption>
						<tr>
							<th><a href='fakultas.php?sortby=Kode&key=$key' title='Sort' >Kode Fakultas</a></th> 
							<th><a href='fakultas.php?sortby=Nama&key=$key' title='Sort' >Nama Fakultas</a></th> 
							<th><a href='fakultas.php?sortby=Tanggal&key=$key' title='Sort' >Waktu Simpan</a></th> 
							<th width='50px' colspan='2'>Pilihan</th>
						</tr>";*/
						echo "<table border='0' align='center'><caption align='center' style='font-size:24px'>DATA FAKULTAS</caption>
						<tr>
							<th>Kode Fakultas</th> 
							<th>Nama Fakultas</th> 
							<th>Waktu Simpan</th> 
							<th width='50px' colspan='2'>Pilihan</th>
						</tr>";
					   while($baris)
					   {
					   	if($warna%2==0)
						{	echo "
							<tr bgcolor='#E2E2E2'><td>".$baris['Kode']."</td><td>".$baris['Nama']."</td><td>".$baris['Tanggal']."</td> <td align='center'><a href='tambahUbahFakultas.php?kode=".$baris['Kode']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='deleteFakultas.php?kode=".$baris['Kode']."' onclick='return confirmDelete();'><img src=images/delete.png border=0 title='Hapus' alt='Hapus'></a></td></tr>";
						}
						else
						{
							echo "
							<tr bgcolor='#F0F0F0'><td>".$baris['Kode']."</td><td>".$baris['Nama']."</td><td>".$baris['Tanggal']."</td> <td align='center'><a href='tambahUbahFakultas.php?kode=".$baris['Kode']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td><td align='center'><a href='deleteFakultas.php?kode=".$baris['Kode']."' onclick='return confirmDelete();'><img src=images/delete.png border=0 title='Hapus' alt='Hapus'></a></td></tr>";
						}
						$warna=$warna+1;
						 $baris=mysql_fetch_assoc($hasil);
						  
					 }
					//PAGING
					echo"<tr><td colspan='5' align='right'>";
					
							if ($page != 1)
							{
								$prev = $page - 1;
								print "<a href='fakultas.php?key=$key&sortby=$sortby'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='fakultas.php?page=$prev&key=$key&sortby=$sortby'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='fakultas.php?page=$i&key=$key&sortby=$sortby'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='fakultas.php?page=$next&key=$key&sortby=$sortby'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='fakultas.php?page=$totalPage&key=$key&sortby=$sortby'>Last&gt;&gt;</a>";
							}
							
						echo"</td></tr>";
						//END OF PAGING
					echo"</table>";
					}
					 else
						{print "<font color='red' size='5' ><i><center>Tidak ada data fakultas.</center></i></font>";
						}
						
					
				 ?>
				</div>
			    </td>
			  </tr>
			  <tr>
			  	<td align="right">
			  		
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
