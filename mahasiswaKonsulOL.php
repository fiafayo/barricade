<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$credential = $_SESSION['credential'];
	
	
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	if ($_SERVER['REQUEST_METHOD']=="POST")
	{

        }
	$query="SELECT k.*, m.Nama as NamaMhs, d.Nama as NamaDosen
					FROM konsulonline as k, mahasiswa as m, dosen as d
					WHERE k.KodeMahasiswa=$username AND k.KodeMahasiswa=m.NRP AND k.KodeDosen=$kodos AND k.KodeDosen=d.Kode ORDER BY k.Waktu";
			
	$hasil=mysql_query($query);		
	

	$itemPerPage = 12;
	$page		 = $_GET['page'];

	if (empty($page))
		{$page = 1;}
		
	$totalLaptop	= @mysql_num_rows($hasil);
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

	<title> Academic Advisor | Mahasiswa :: Konsultasi Online</title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <br/><div style='font-size:20px' align='center'>KONSULTASI ONLINE</div>
		<table border="0" align="center" width="100%" ><br/>
			<form action="mahasiswaKonsulOL.php" method="post">
			  <tr>
			  	<td align="right">
					<strong>Dosen</strong>
				</td>
				<td>
					<?
						$today=getdate();
						$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
						$ambildosen=mysql_query("SELECT d.* FROM dosen as d, detailjabatan as dt, mahasiswa as m, jabatan as t WHERE m.NRP=$username AND m.Fakultas=d.Fakultas AND m.Jurusan=d.Jurusan AND d.Kode=dt.KodeDosen AND dt.KodeJabatan=t.Kode AND t.Jabatan='Dosen' AND ('$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir)");
						$brsdsn=mysql_fetch_assoc($ambildosen);
						//if($brsdsn)
						{
						?>
						<select id="cboDosen" name="cboDosen">
							<option value="">[Pilih Dosen]</option>
							<? while($brsdsn)
							{  ?>
							<option value="<? echo $brsdsn['Kode']; ?>" <? if($kodos==$brsdsn['Kode']) echo "selected"; ?>><? echo $brsdsn['Nama']; ?> </option>
							<? $brsdsn=mysql_fetch_assoc($ambildosen);
							} 
						}?>
						</select>
				</td>
			  </tr>
			  <tr>
			  	<td align="right"><br/>
					<input type="submit" name="submit" value="Lihat"  style="height:30px;width:150px">
				</td>
				<td><br/><input type="submit" name="submit" value="Kirim Konsultasi Baru" style="height:30px;width:150px"></td>
			  </tr>
		  </form>
			 <tr>
			    <td colspan="2" align="center"> <br/>
				<? $baris=@mysql_fetch_assoc($hasil);
				$warna=1;
					if($baris)
					{
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
								if($baris['Status']==0)
								{
									echo "
									<tr><td colspan='2' align='right'><a href='mahasiswaUbahKonsulOL.php?kode=$kodos&kodeEdit=$frmKode'>Ubah</a></td></tr>";
								}
								else
								{
									
									echo "
									<tr><td colspan='2' align='right'><a href='mahasiswaJawabKonsulOL.php?kode=$kodos&frmKode=$frmKode'>Jawab</a></td></tr>";
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
					{
						if(!empty($kodos))
						{
							print "<font color='red' size='5'><i>Tidak ada data konsultasi.</i></font>";
						}
						else
						{
							print " ";
						}
						$isi=false;
					}
					 ?>
				
			    </td>
			  </tr>
			  <tr>
			  	<td align="right" colspan="2">
			  		<?php //PAGING
						if($isi==true)
						{
							if ($page != 1)
							{
								$prev = $page - 1;
								print "<a href='mahasiswaKonsulOL.php?cboDosen=$kodos'>&lt;&lt;First</a>";
								print "&nbsp;&nbsp;<a href='mahasiswaKonsulOL.php?page=$prev&cboDosen=$kodos'>&lt;Prev<a>&nbsp;&nbsp;";
							}
							
							if ($batasBawah > 1)
								print '. . . ';
							
							for ($i = $batasBawah; $i <= $batasAtas; $i++)
							{
								if ($i == $page)
									print "<b>[$i]</b> ";
								else
								{
									print "<a href='mahasiswaKonsulOL.php?page=$i&cboDosen=$kodos'>$i</a> ";
								}
							}
							
							if ($batasAtas < $totalPage)
								print ' . . .';
							
							if ($page != $totalPage)
							{
								$next = $page + 1;
								print "&nbsp;&nbsp;<a href='mahasiswaKonsulOL.php?page=$next&cboDosen=$kodos'>Next&gt;</a>&nbsp;&nbsp;";
								print "<a href='mahasiswaKonsulOL.php?page=$totalPage&cboDosen=$kodos'>Last&gt;&gt;</a>";
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
