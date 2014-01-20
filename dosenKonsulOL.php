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

	$query="SELECT k.KodeMahasiswa,m.Nama as NamaMhs, MAX(k.Waktu) as Waktu, MIN(k.StatusPesan) as StsPsn FROM konsulonline as k, mahasiswa as m WHERE k.status=0 AND k.KodeDosen='$username' AND k.KodeMahasiswa=m.NRP AND (k.KodeMahasiswa LIKE '$key' or m.Nama LIKE '$key') GROUP BY k.KodeMahasiswa, m.Nama ORDER BY StsPsn ASC, Waktu DESC";	
	
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

	<title>Academic Advisor | Dosen :: Konsultasi Online </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%">
			  <tr>
			  	<td>
					<form action="" method="post">Search: <input type="text" name="search" value=""> <input type="submit" value="Cari" name="Cari"></form>
				<br/><font size="1"><b>Pencarian berdasarkan NRP dan nama</b></font>
				</td>
			  </tr> 
			  <tr>
			    <td><br/><br/>
				<? $baris=mysql_fetch_assoc($hasil);
				
					if($baris)
					{
						echo"<div style='font-size:20px' align='center'>KONSULTASI ONLINE</div><br/>";
						echo "<table border='0' align='center'><caption style='font-size:12px;'><i><b><font color='red'>Klik pada NRP untuk melihat detail konsultasi online</font></b></i></caption>
						<tr>
							<th>NRP</th> <th>Nama</th> <th>Waktu</th>
						</tr>";
						$warna=1;
					   while($baris)
					   {
					   	$nrp=$baris['KodeMahasiswa'];
						$cekbaru=mysql_query("SELECT * FROM konsulonline WHERE KodeDosen='$username' AND KodeMahasiswa=$nrp AND StatusPesan=0");
						//$ambilwkt=mysql_query("SELECT * FROM konsulonline WHERE Status=0 AND KodeDosen='$username' AND KodeMahasiswa='$nrp' ORDER BY Waktu DESC");
						//$brswkt=mysql_fetch_assoc($ambilwkt);
							if($warna%2==0)
							{
								if(@mysql_num_rows($cekbaru)>0)
								{
									echo "
							<tr bgcolor='#E2E2E2'><td><a href='detailDosenKonsulOL.php?nrp=$nrp'><i>".$baris['KodeMahasiswa']."</i></a></td><td>".$baris['NamaMhs']."</td><td>".$baris['Waktu']."</td></tr>";
								}
								else
								{
									echo "
								<tr bgcolor='#E2E2E2'><td><a href='detailDosenKonsulOL.php?nrp=$nrp'>".$baris['KodeMahasiswa']."</a></td><td>".$baris['NamaMhs']."</td><td>".$baris['Waktu']."</td></tr>";
								}
							}
							else
							{
								if(@mysql_num_rows($cekbaru)>0)
								{
									echo "
								<tr bgcolor='#F0F0F0'><td><a href='detailDosenKonsulOL.php?nrp=$nrp'><i>".$baris['KodeMahasiswa']."</i></a></td><td>".$baris['NamaMhs']."</td><td>".$baris['Waktu']."</td></tr>";
								}
								else
								{
									echo "
								<tr bgcolor='#F0F0F0'><td><a href='detailDosenKonsulOL.php?nrp=$nrp'>".$baris['KodeMahasiswa']."</a></td><td>".$baris['NamaMhs']."</td><td>".$baris['Waktu']."</td></tr>";
								}
								
							}
							$warna=$warna+1;
							$baris=mysql_fetch_assoc($hasil); }
						echo"</table>";
					}
					else
						{print "<font color='red' size='5'><i>Tidak ada mahasiswa yang melakukan konsultasi online.</i></font>";
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
								print "<a href='dosenKonsulOL.php?key=$key'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='dosenKonsulOL.php?page=$prev&key=$key'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='dosenKonsulOL.php?page=$i&key=$key'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='dosenKonsulOL.php?page=$next&key=$key'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='dosenKonsulOL.php?page=$totalPage&key=$key'>Last&gt;&gt;</a>";
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
