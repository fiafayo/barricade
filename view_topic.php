<?php
	session_start();
	include("config.php");
	include_once ("fckeditor/fckeditor.php") ;
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
		
	$tbl_name="forum_question"; // Table name 
	// get value of id that sent from address bar 
	$id=$_GET['id'];
	$frmid=$_GET['frmid'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	else if($_SESSION['usernamee'] && $_SESSION['passwordd'])
  	{
		$usr=$_SESSION['usernamee'];
		$dsn=mysql_query("SELECT * FROM dosen WHERE Kode='$usr'");
		if(mysql_num_rows($dsn)<=0)
		{
			print "<script>
						  window.location='index.php';	
					   </script>";	
		}
   }
   $caritopic=mysql_query("SELECT fq.*,fg.g_id, fg.g_group FROM forum_question as fq, forum_group as fg WHERE fq.id='$id' AND fg.g_id=fq.g_id");
   if(@mysql_num_rows($caritopic)<=0)
   {
		header("Location: forum.php");
		exit();
   }

function recs($kodepost,$id)
{
	if ($kodepost==0)
	{
		return;
	}
	else
	{
	//$tbl_name3="forum_answer"; // Switch to table "forum_answer" 
		$query="SELECT fa.*,d.Nama FROM forum_answer fa, dosen as d WHERE fa.question_id='$id' AND fa.a_id='$kodepost' AND fa.a_kode=d.Kode";
		$hasil = mysql_query($query);
		$datapostingan = mysql_fetch_assoc($hasil);
		echo "<table align='center' bgcolor='#F0F0F0' cellpadding='4' width='100%' style='border:1px solid #ccc;'>";
		echo "<tr><td>".$datapostingan['Nama']."</td><td align='right'>".$datapostingan['a_datetime']."</td></tr>";
		
		echo "<tr><td colspan='2' bgcolor='#E2E2E2'>";
		recs($datapostingan['a_fromid'],$id);
		echo $datapostingan['a_answer']."</td></tr>";
		
		echo "</table>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor :: Forum </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" width="100%">
			  <tr>
			    <td>
					<? $brstopic=mysql_fetch_assoc($caritopic);?>
					
					<?php
						if(empty($frmid))
						{
							$sql="SELECT f.*,d.Nama FROM forum_question f, dosen as d WHERE f.id='$id' AND f.Kode=d.Kode";
							$result=mysql_query($sql);
							
							$rows=mysql_fetch_array($result);
							?><a href="forum.php">Forum</a> >> <a href="main_forum.php?id=<? echo $brstopic['g_id']; ?>"><? echo $brstopic['g_group']; ?></a> >> <? echo $brstopic['topic']; ?> <br/><br/>
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
							<tr>
							<td><table width="100%" border="0" cellpadding="3" cellspacing="1" bordercolor="1" bgcolor="#FFFFFF">
							<tr>
							<td bgcolor="#F8F7F1"><strong><? echo $rows['topic']; ?></strong></td>
							</tr>
							
							<tr>
							<td bgcolor="#F8F7F1"><? echo $rows['detail']; ?></td>
							</tr>
							
							<tr>
							<td bgcolor="#F8F7F1"><strong>Dibuat oleh :</strong> <? echo $rows['Nama']; ?> </td>
							</tr>
							
							<tr>
							<td bgcolor="#F8F7F1"><strong>Tanggal/jam : </strong><? echo $rows['datetime']; ?></td>
							</tr>
							</table></td>
							</tr>
							</table>
							<BR>
							<?php
							$tbl_name2="forum_answer"; // Switch to table "forum_answer" 
							
							$sql2="SELECT fa.* FROM forum_answer as fa WHERE fa.question_id='$id'";
							$result2=mysql_query($sql2);
							
							while($rows=mysql_fetch_assoc($result2)){
								recs($rows['a_id'],$id);		
								if($rows['a_kode']==$username)
								{?>								
								<table align="center" bgcolor="#F8F7F1" width="100%" style="border:1px solid #ccc;">
								<tr>
								<td align="right"><strong><a href="view_topic.php?id=<? echo $id; ?>&frmid=<? echo $rows['a_id']; ?>">Ubah</a></strong></td>
								</tr></table>
							<?
								}
								else
								{?>
								<table align="center" bgcolor="#F8F7F1" width="100%" style="border:1px solid #ccc;">
								<tr>
								<td align="right"><strong><a href="view_topic.php?id=<? echo $id; ?>&frmid=<? echo $rows['a_id']; ?>">Balas</a></strong></td>
								</tr></table>
							<? }
							if($rows)
								echo"<hr>";
							} ?>
							</td>
							</tr>
							</table><br>
							
							<?
							$sql3="SELECT view FROM $tbl_name WHERE id='$id'";
							$result3=mysql_query($sql3);
							
							$rows=mysql_fetch_array($result3);
							$view=$rows['view'];
							
							// if have no counter value set counter = 1
							if(empty($view)){
							$view=1;
							$sql4="INSERT INTO $tbl_name(view) VALUES('$view') WHERE id='$id'";
							$result4=mysql_query($sql4);
							}
							
							// count more value
							$addview=$view+1;
							$sql5="update $tbl_name set view='$addview' WHERE id='$id'";
							$result5=mysql_query($sql5);
							
							//mysql_close();
						}
						else
						{  
							$sql2="SELECT fa.*,d.Nama FROM forum_answer fa, dosen as d WHERE fa.question_id='$id' AND fa.a_id='$frmid' AND fa.a_kode=d.Kode";
							$result2=mysql_query($sql2);
							$rows=mysql_fetch_array($result2);
							if($rows['a_kode']!=$username)
							{
							?><a href="forum.php">Forum</a> >> <a href="main_forum.php?id=<? echo $brstopic['g_id']; ?>"><? echo $brstopic['g_group']; ?></a> >> <a href="view_topic.php?id=<? echo $brstopic['id']; ?>"><? echo $brstopic['topic']; ?></a> >> Balas<br/><br/>
							<?php
								recs($rows['a_id'],$id);
							}
							else
							{	?><a href="forum.php">Forum</a> >> <a href="main_forum.php?id=<? echo $brstopic['g_id']; ?>"><? echo $brstopic['g_group']; ?></a> >> <a href="view_topic.php?id=<? echo $brstopic['id']; ?>"><? echo $brstopic['topic']; ?></a> >> Ubah<br/><br/>
								
							<?
							}
						}
						
						
						?>
						<BR>
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
						<tr>
						<form name="form1" method="post" action="add_answer.php">
						<td>
						<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF" align="center">
						<tr>
						<td valign="top" width="10%" align="right"><br/><strong>Jawab</strong></td>
						<td valign="top"><br/>:</td>
						<td><br/>
						<?php 
						$sql2="SELECT fa.*,d.Nama FROM forum_answer fa, dosen as d WHERE fa.question_id='$id' AND fa.a_id='$frmid' AND fa.a_kode=d.Kode";
						$result2=mysql_query($sql2);
						$rows=mysql_fetch_array($result2);
						if($rows['a_kode']==$username)
						{
							$oFCKeditor = new FCKeditor('a_answer') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							$oFCKeditor->Value = $rows['a_answer'] ;
							$oFCKeditor->Create() ;
						}
						else
						{
							$oFCKeditor = new FCKeditor('a_answer') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							$oFCKeditor->Value = '' ;
							$oFCKeditor->Create() ;
						}
						?><!--<textarea name="a_answer" cols="45" rows="3" id="a_answer"></textarea> --></td>
						</tr>
						<tr>
						<td>&nbsp;</td>
						<td><input name="id" type="hidden" value="<? echo $id; ?>"><input name="frmid" type="hidden" value="<? echo $frmid; ?>"></td>
						<td><input type="submit" name="Submit" value="Simpan"> <!-- <input type="reset" name="Submit2" value="Reset">--><br/><br/></td>
						</tr>
						</table>
						</td>
						</form>
						</tr>
						</table>
					<br/>
			    </td>
			</tr>
			</table>
		</div>
		
		<div id="sidebar">
		   <ul>
		   <li><h2>MENU</h2>
			 </li>
			  <? if($username=="admin")
		   { ?>
		   	<li><a href="admin.php">Halaman Utama</a></li>
			 <li><a href="kategori.php">Kategori Masalah</a></li>
			  <li><a href="fakultas.php">Fakultas</a></li>
			   <li><a href="jurusan.php">Jurusan</a></li>
			 <li><a href="karyawan.php">Karyawan</a></li>
			 <li><a href="mahasiswa.php">Mahasiswa</a></li>
			  <li><a href="forum.php">Forum</a></li>
			    <li><a href="settingmail.php">Setting E-mail</a></li>
		   <?
		   }
		   else
		  { 	$today=getdate();
			$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
		   	$qjbtan=mysql_query("SELECT * FROM detailjabatan WHERE KodeDosen='$username' AND '$tanggalskrg'>=TanggalAwal AND '$tanggalskrg'<=TanggalAkhir");
			$brsjbtn=mysql_fetch_assoc($qjbtan);
				if($brsjbtn['KodeJabatan']==5)
				{
				?>
				<li><a href="homeDosen.php">Halaman Utama</a></li>
				  <li><a href="dsnKategori.php">Kategori Masalah</a></li>
				  <li><a href="pengadaanKonsul.php">Pengadaan Konsultasi</a></li>
				   <li><a href="pembatalanKonsul.php">Pembatalan Konsultasi</a></li>
				 <li><a href="hasilKonsul.php">Hasil Konsultasi</a></li>
				 <li><a href="ubahHasilKonsul.php">Ubah Hasil Konsultasi</a></li>
				 <li><a href="riwayatKonsul.php">Riwayat Konsultasi</a></li>
				 <li><a href="dsnlaporanHasilKonsul.php">Laporan Hasil Konsultasi</a></li>
				 <li><a href="resumeKategori.php">Laporan Kategori Masalah</a></li>
				 <li><a href="dosenKonsulOL.php">Konsultasi Online</a></li>
				  <li><a href="forum.php">Forum</a></li>
			 <?	}
			 	else if($brsjbtn['KodeJabatan']==4)
				{ 
					
					echo" <li><a href='homeKajur.php'>Halaman Utama</a></li>
					 <li><a href='kajurRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='laporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					  <li><a href='main_forum.php'>Forum</a></li>";
					  
				}
				else if($brsjbtn['KodeJabatan']==3 || $brsjbtn['KodeJabatan']==7)
				{
					echo"
					 <li><a href='homeDkn.php'>Halaman Utama</a></li>
					 <li><a href='dekanRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='dekanLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					  <li><a href='main_forum.php'>Forum</a></li>
					  ";
				}
				else if($brsjbtn['KodeJabatan']==1 || $brsjbtn['KodeJabatan']==2)
				{
					echo"
				 <li><a href='homeWP.php'>Halaman Utama</a></li>
				 <li><a href='wpRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
				 <li><a href='wpLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
				 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
				 <li><a href='laporanKinerjaDosen.php'>Laporan Kinerja Dosen</a></li>
				  <li><a href='main_forum.php'>Forum</a></li>
				  ";
				}
			 } ?>
		   </ul>
		 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

