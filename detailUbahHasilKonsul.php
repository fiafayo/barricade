<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	include_once ("fckeditor/fckeditor.php") ;
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nopendaftaran=$_REQUEST['nopendaftaran'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	if(empty($nopendaftaran))
	{
	print "<script>
			alert('Harap memilih nomor pengadaan terlebih dahulu!');	
		 </script>";
		print "<script>
				window.location='ubahHasilKonsul.php';	
			</script>";
		exit();
	}
	$qperiksa="SELECT  d.*, p.Tanggal, p.Hari, p.JamMulai, p.JamSelesai 
	FROM pendaftaran as d, pengadaan as p 
	WHERE (p.Kode='$username' AND p.Status='Belum Terlaksana' AND d.NoPengadaan=p.NoPengadaan AND d.Status=0 AND d.StatusMhs<>'Belum Dilayani') GROUP BY d.NoPendaftaran ORDER BY p.Tanggal DESC";
	$periksa=mysql_query($qperiksa);
	if(@mysql_num_rows($periksa)<=0)
	{print "<script>
				window.location='ubahHasilKonsul.php';	
			</script>";		
	}
	else
	{
		$brsperiksa=mysql_fetch_assoc($periksa);
		$blh=false;
		while($brsperiksa)
		{
			if($nopendaftaran==$brsperiksa['NoPendaftaran'])
			{
				$blh=true;
				break;
			}
			$brsperiksa=mysql_fetch_assoc($periksa);
		}
	}
	if(!$blh)
	{
		print "<script>
			alert('Tidak dapat mengubah hasil konsultasi!');	
		 </script>";
		print "<script>
				window.location='ubahHasilKonsul.php';	
			</script>";
		exit();
	}

	$kesalahan="";
	if($_SERVER['REQUEST_METHOD']=="POST")
	{		
		$kategori=$_REQUEST['cboKategori'];
		$permasalahan=$_REQUEST['permasalahan'];
		$saran=$_REQUEST['saran'];
		$hasilkonsul=$_REQUEST['hasilkonsul'];
		$statusmhs=$_REQUEST['cboStatus'];
                $confidential=isset($_REQUEST['confidential']) ? intval($_REQUEST['confidential']) : 0;
		if($_POST['submit']=='Batal')
		{
			header('Location: ubahHasilKonsul.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			//echo $statusmhs;
			if($statusmhs=="Sudah Dilayani")
			{
				if(empty($permasalahan) || empty($saran)  )
				{
					$kesalahan="data kosong";
				}
				else
				{
					$cekawal=mysql_query("SELECT * FROM pendaftaran WHERE NoPendaftaran='$nopendaftaran'");
					$brscekawal=mysql_fetch_assoc($cekawal);
					if($brscekawal['StatusMhs']=="Tidak Datang")
					{
						$insert=mysql_query("INSERT INTO hasilkonsultasi (KategoriMasalah, Permasalahan, HasilKonsultasi, Saran, NoPendaftaran,confidential) VALUES ('$kategori', '$permasalahan', '$hasilkonsul', '$saran', '$nopendaftaran', $confidential)");
						if($insert)
						{
							//update statusMhs
							$update=mysql_query("UPDATE pendaftaran SET StatusMhs='$statusmhs' WHERE NoPendaftaran='$nopendaftaran'");
							
							print "<script>
								alert('Pengubahan hasil konsultasi telah berhasil!');	
								</script>";
							print "<script>
							window.location='ubahHasilKonsul.php';
						   </script>";
						}
					}
					else if($brscekawal['StatusMhs']=="Sudah Dilayani")
					{
						$update=mysql_query("UPDATE hasilkonsultasi SET KategoriMasalah='$kategori', Permasalahan='$permasalahan', HasilKonsultasi='$hasilkonsul', Saran='$saran', confidential=$confidential WHERE NoPendaftaran='$nopendaftaran'");
						if($update)
						{
                                                    $hasil = mysql_query("SELECT d.*, m.Nama FROM pendaftaran as d,mahasiswa as m WHERE NoPendaftaran='$nopendaftaran' AND d.NRP=m.NRP"); 
						    $barisEdit=mysql_fetch_assoc($hasil);
                                                    $nrp=$barisEdit['NRP'];
                                                    $sql="UPDATE tk_mhs SET konsultasi=1, aa=1 where nrp='$nrp'";
                                                    
                                                    $rs=mysql_query($sql,$connFT);
                                                    $pesanErr = mysql_error($connFT);
						        print "<script>
							alert('Pengubahan hasil konsultasi telah berhasil! err=$pesanErr');	
							</script>";                                                    
							 
							print "<script>
							window.location='ubahHasilKonsul.php';
						   </script>";
						}
					}
				
				}
			}
			else if($statusmhs=="Tidak Datang")
			{
				$cekawal=mysql_query("SELECT * FROM pendaftaran WHERE NoPendaftaran='$nopendaftaran'");
				$brscekawal=mysql_fetch_assoc($cekawal);
				if($brscekawal['StatusMhs']=="Tidak Datang")
				{
					$update=mysql_query("UPDATE pendaftaran SET StatusMhs='$statusmhs' WHERE NoPendaftaran='$nopendaftaran'");
					print "<script>
								alert('Pengubahan hasil konsultasi telah berhasil!');	
								</script>";
					print "<script>
					window.location='ubahHasilKonsul.php';
				   </script>";
				}
				else if($brscekawal['StatusMhs']=="Sudah Dilayani")
				{
					$delete=mysql_query("DELETE FROM hasilkonsultasi WHERE NoPendaftaran='$nopendaftaran'");
					if($delete)
					{
						//update statusMhs
						$update=mysql_query("UPDATE pendaftaran SET StatusMhs='$statusmhs' WHERE NoPendaftaran='$nopendaftaran'");
						
						print "<script>
							alert('Pengubahan hasil konsultasi telah berhasil!');	
							</script>";
						print "<script>
						window.location='ubahHasilKonsul.php';
					   </script>";
					}

				}
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Dosen :: Detail Ubah Hasil Konsultasi </title>
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

function tampil(idx)
{
	if(idx==0)
	{
		document.getElementById('tampilDiv1').style.display="";
		document.getElementById('tampilDiv2').style.display="";
		document.getElementById('tampilDiv3').style.display="";
		document.getElementById('tampilDiv4').style.display="";
		document.getElementById('tampilDiv5').style.display="";
		document.getElementById('tampilDiv6').style.display="";
		document.getElementById('tampilDiv7').style.display="";
		document.getElementById('tampilDiv8').style.display="";
	}
	else if(idx==1)
	{
		document.getElementById('tampilDiv1').style.display="none";
		document.getElementById('tampilDiv2').style.display="none";
		document.getElementById('tampilDiv3').style.display="none";
		document.getElementById('tampilDiv4').style.display="none";
		document.getElementById('tampilDiv5').style.display="none";
		document.getElementById('tampilDiv6').style.display="none";
		document.getElementById('tampilDiv7').style.display="none";
		document.getElementById('tampilDiv8').style.display="none";
	}
}
</script>
</head>
<body>
<?
if($kesalahan=="data kosong")
{
	print "<script>
		alert('Data harap diisi!');	
		</script>";
	
}
?>
	<div id="container">
		<?php  include_once('header.php'); ?>

		<div id="content">
		<table border="0" align="center" width="100%" cellspacing="0">
			  <tr>
			  <td colspan="5">
			  <form action="detailUbahHasilKonsul.php?nopendaftaran=<? echo $nopendaftaran; ?>" method="post">
			 <br/><div style="font-size:20px" align="center">UBAH HASIL KONSULTASI</div><br/><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" align="center" width="100%"> 
				<tr>
					<td align="right"><strong>No. Pendaftaran</strong></td>
					<td width="80%"><? 
						 $hasil = mysql_query("SELECT d.*, m.Nama FROM pendaftaran as d,mahasiswa as m WHERE NoPendaftaran='$nopendaftaran' AND d.NRP=m.NRP"); 
						 $barisEdit=mysql_fetch_assoc($hasil);
						 
						echo $barisEdit['NoPendaftaran']; 
						?></td>
				</tr>
				<tr>
					<td align="right"><strong>NRP</strong></td>
					<td><? echo $barisEdit['NRP']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Nama</strong></td>
					<td><? 	echo $barisEdit['Nama'];	?>
							
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Status Mahasiswa</strong></td>
					<td><select id="cboStatus" name="cboStatus">
						<option value="Sudah Dilayani" onclick="tampil(0);" <? if(!empty($kesalahan) && $statusmhs=="Sudah Dilayani") echo"selected"; else if($barisEdit['StatusMhs']=="Sudah Dilayani") echo"selected";?>>Sudah dilayani</option>
						<option value="Tidak Datang" onclick="tampil(1);" <? if(!empty($kesalahan) && $statusmhs=="Tidak Datang") echo"selected"; else if( $barisEdit['StatusMhs']=="Tidak Datang") echo"selected";?>>Tidak datang</option>
					</select>
					
					</td>
				</tr>
				
				<tr>
					<td align="right"><div id="tampilDiv5"><strong>Kategori Masalah</strong></div></td>
					<td><div id="tampilDiv1">
						<? 	$query="SELECT * FROM kategori";
							$masalah=mysql_query($query);
							$baris=mysql_fetch_assoc($masalah);
							
							$ambilhasil=mysql_query("SELECT h.* FROM hasilkonsultasi as h, pendaftaran as d, pengadaan as p WHERE h.NoPendaftaran='$nopendaftaran' AND h.NoPendaftaran=d.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND p.Kode='$username'");
							$brshasil=mysql_fetch_assoc($ambilhasil);
							$mslh=$brshasil['Permasalahan'];
							$srnEdit=$brshasil['Saran'];
							$hslEdit=$brshasil['HasilKonsultasi'];
							$katmslh=$brshasil['KategoriMasalah'];
							
						
							echo "<select id='cboKategori' name='cboKategori'>";
							while($baris)
							{
								if(!empty($kesalahan))
								{
									if($kategori==$baris['Kode'])
									{
										echo"<option value='".$baris['Kode']."' selected>".$baris['Nama']."</option>";
									}
									else
									{
										echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>";
									}
								}
								else
								{
									if($katmslh==$baris['Kode'])
									{
										echo"<option value='".$baris['Kode']."' selected>".$baris['Nama']."</option>";
									}
									else
									{
										echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>";
									}
								}
								
								$baris=mysql_fetch_assoc($masalah);
							}
							echo"</select>";
	
						?></div>
					</td>
				</tr>
                                    <tr>
                                        <td align="right"><strong>Confidential</strong></td>
                                        <td>
                                            <input type="radio" name="confidential" value="1" <?php if ($brshasil['confidential']) echo 'checked="true"';?> />Ya &nbsp;
                                            <input type="radio" name="confidential" value="0" <?php if (!$brshasil['confidential']) echo 'checked="true"';?>  />Tidak
                                        </td>
                                    </tr>

				<tr>
					<td align="right" valign="top"><div id="tampilDiv6"><strong>Permasalahan</strong></div></td>
					<td><div id="tampilDiv2"><?
						
							$oFCKeditor = new FCKeditor('permasalahan') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							if(!empty($kesalahan))
								{$oFCKeditor->Value = $permasalahan ;}
							else
								{$oFCKeditor->Value = $mslh ;}
							$oFCKeditor->Create() ;
					?></div></td>
				</tr>
				<tr>
					<td align="right" valign="top"><div id="tampilDiv7"><strong>Saran</strong></div></td>
					<td><div id="tampilDiv3"><?
							$oFCKeditor = new FCKeditor('saran') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							if(!empty($kesalahan))
								{$oFCKeditor->Value = $saran ;}
							else
								{$oFCKeditor->Value = $srnEdit ;}
							$oFCKeditor->Create() ;
					?></div></td>
				</tr>
				<tr>
					<td align="right" valign="top"><div id="tampilDiv8"><strong>Hasil Konsultasi</strong></div></td>
					<td><div id="tampilDiv4"><?
							$oFCKeditor = new FCKeditor('hasilkonsul') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							if(!empty($kesalahan))
								{$oFCKeditor->Value = $hasilkonsul ;}
							else
								{$oFCKeditor->Value = $hslEdit ;}
							$oFCKeditor->Create() ;
					?></div></td>
				</tr>
				
				<tr>
					<td align="center" colspan="2"><br/><input type="submit" value="Simpan" name="submit">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Batal" name="submit"></td>
				</tr>
				
				</table>
				</form> 
				</td>
			  </tr>
			  </table><?
			  if ($barisEdit['StatusMhs'] == 'Tidak Datang')
						 {
						 	echo '<script type=text/javascript language = javascript>tampil(1)</script>';
						 }?>
		</div><!-- end content -->
		
		<div id="sidebar">
   <ul>
     <li><h2>MENU</h2></li>
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
			  <li><a href="main_forum.php">Forum</a></li>
   </ul>
 </div> 

   		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

