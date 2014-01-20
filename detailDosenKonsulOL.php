<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nrp=$_REQUEST['nrp'];
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
	function recs($kodepost)
	{
		if ($kodepost==0)
		{
			return;
		}
		else
		{
			$query = "SELECT k.*, m.Nama as namaMhs, d.Nama as namaDsn FROM konsulonline as k, mahasiswa as m, dosen as d WHERE k.Kode='$kodepost' AND k.KodeMahasiswa=m.NRP AND k.KodeDosen=d.Kode";
			$hasil = mysql_query($query);
			$datapostingan = mysql_fetch_assoc($hasil);
			echo "<table align='center' bgcolor='#F0F0F0' cellpadding='4' width='100%' style='border:1px solid #ccc;'>";
			if($datapostingan['Status']==0)
			{ 
				echo "<tr><td>".$datapostingan['namaMhs']."</td><td align='right'>".$datapostingan['Waktu']."</td></tr>";
			}
			else
			{
				echo "<tr><td>".$datapostingan['namaDsn']."</td><td align='right'>".$datapostingan['Waktu']."</td></tr>";
			}
			echo "<tr><td colspan='2' bgcolor='#E2E2E2'>";
			recs($datapostingan['FromKode']);
			echo $datapostingan['Pesan']."</td></tr>";
			
			echo "</table>";
		}
	}
	
	$queryceknrp="SELECT DISTINCT k.KodeMahasiswa,m.Nama as NamaMhs FROM konsulonline as k, mahasiswa as m WHERE k.status=0 AND k.KodeDosen=$username AND k.KodeMahasiswa=m.NRP";	
	$hasilceknrp=mysql_query($queryceknrp);
	$brsceknrp=mysql_fetch_assoc($hasilceknrp);
	if(@mysql_num_rows($hasilceknrp)<=0)
	{
		print "<script>
				alert('Tidak ada daftar mahasiswa yang melakukan konsultasi online!');						
			   </script>";
		print "<script>
				window.location='dosenKonsulOL.php';
			   </script>";
	}
	else
	{
		$ada=false;
		while($brsceknrp)
		{
			if($nrp==$brsceknrp['KodeMahasiswa'])
			{	$ada=true;	
				break;
			}
			$brsceknrp=mysql_fetch_assoc($hasilceknrp);
		}
	}
	if($ada==false)
	{
		print "<script>
				alert('Mahasiswa $nrp tidak melakukan konsultasi online!');						
			   </script>";
		print "<script>
				window.location='dosenKonsulOL.php';
			   </script>";
	}
	//$today=getdate();
	//$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
	$query="SELECT k.*, m.Nama as NamaMhs, d.Nama as NamaDosen
			FROM konsulonline as k, mahasiswa as m, dosen as d
			WHERE k.KodeMahasiswa=$nrp AND k.KodeMahasiswa=m.NRP AND k.KodeDosen=$username AND k.KodeDosen=d.Kode ORDER BY k.Waktu";
	
	
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

	<title> Academic Advisor | Dosen :: Detail Konsultasi Online</title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" >
			  <tr>
			    <td>
				<? $baris=mysql_fetch_assoc($hasil);
				$warna=1;
					if($baris)
					{
						$update=mysql_query("UPDATE konsulonline SET StatusPesan=1 WHERE KodeDosen=$username AND KodeMahasiswa=$nrp");
						echo $nrp."-->".$baris['NamaMhs']."--><a href='dosenJawabKonsulOL.php?kode=$nrp'>Kirim Konsultasi Baru</a><br/><br/>";
						echo "<table align='center' cellpadding='4' width='100%' style='border:1px solid #ccc;'>
						<tr><td>";
					   while($baris)
					   {
							/*echo "<table align='center' bgcolor='#F0F0F0' cellpadding='4' width='100%' style='border:1px solid #ccc;'>";
							if($baris['Status']==0)
							{   echo "
								<tr><td>".$baris['NamaMhs']."</td><td align='right'>".$baris['Waktu']."</td></tr>";
							}
							else
							{
								echo "
								<tr ><td>".$baris['NamaDosen']."</td><td align='right'>".$baris['Waktu']."</td></tr>";
							}
							
							echo "
							<tr bgcolor='#E2E2E2'><td colspan='2'>".$baris['Pesan']."</td></tr>";
							 	
							
							if($baris['Status']==1)
							{
								echo "
								<tr><td colspan='2' align='right'><a href=''>Ubah</a></td></tr>";
							}
								$baris=mysql_fetch_assoc($hasil); 	
								echo "</td></tr></table>";*/
								recs($baris['Kode']);
								echo"<table align='center' bgcolor='#F0F0F0' width='100%' style='border:1px solid #ccc;'>";
								$frmKode=$baris['Kode'];
								if($baris['Status']==1)
								{
									echo "
									<tr><td colspan='2' align='right'><a href='dosenUbahKonsulOL.php?kode=$nrp&kodeEdit=$frmKode'>Ubah</a></td></tr>";
								}
								else
								{
									
									echo "
									<tr><td colspan='2' align='right'><a href='dosenJawabKonsulOL.php?kode=$nrp&frmKode=$frmKode'>Jawab</a></td></tr>";
								}
								echo"</table>";
								$baris=mysql_fetch_assoc($hasil); 	
								if($baris)
									echo"<hr>";
						}
						/*echo "
							<tr><td colspan='2' align='right'><a href='jawabKonsulOL.php?kode=$nrp'>Jawab</a></td></tr>";*/
						echo"</table>";
						
					}
					else
						{print "<font color='red' size='5'><i>Tidak ada data konsultasi.</i></font>";
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
								print "<a href='detailDosenKonsulOL.php?nrp=$nrp'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='detailDosenKonsulOL.php?page=$prev&nrp=$nrp'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='detailDosenKonsulOL.php?page=$i&nrp=$nrp'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='detailDosenKonsulOL.php?page=$next&nrp=$nrp'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='detailDosenKonsulOL.php?page=$totalPage&nrp=$nrp'>Last&gt;&gt;</a>";
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
