<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$kode=$_REQUEST['kode'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	else
	{
		$today=getdate();
		$thnskrg=$today['year'];
		
		/*$hasil=mysql_query("SELECT d.*, t.Jabatan, dt.TahunAwal, dt.TahunAkhir, dt.KodeJabatan
						FROM dosen as d, detailjabatan as dt, jabatan as t 
						WHERE d.Kode='$kode' AND d.Status=0 AND d.Kode=dt.KodeDosen AND $thnskrg>=dt.TahunAwal AND $thnskrg<=dt.TahunAkhir AND dt.KodeJabatan=t.Kode");
		*/
		$hasil=mysql_query("SELECT d.*
						FROM dosen as d
						WHERE d.Kode='$kode' AND d.Status=0");
		$baris=mysql_fetch_assoc($hasil);
		if(!$baris)
		{
			print "<script>
						alert('Kode karyawan tidak terdaftar!');	
	  				 </script>";
			  print "<script>
				window.location='karyawan.php';	
			</script>";
				exit();
		}
	}
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$nama=$_REQUEST['nama'];
		$notelp=$_REQUEST['notelp'];
		$email=$_REQUEST['email'];
		$alamat=$_REQUEST['alamat'];
		$fakultas=$_REQUEST['cboFakultas'];
		$jurusan=$_REQUEST['cboJurusan'];
		$benar=eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
		
		if($_POST['submit']=='Batal')
		{
			header('Location: karyawan.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($kode) or empty($nama) or empty($alamat) or empty($notelp) or empty($email))
			{
				print "<script>
						alert('Semua data harap diisi!');	
	  				 </script>";
				 print "<script>
						window.location='ubahKaryawan.php?kode=$kode';	
					</script>";
				exit();
			}
			else
			{
				if(cekInteger($notelp)==false)
				{
					print "<script>
						alert('NoTelp harap diisi dengan angka!');	
	  				 </script>";
				 print "<script>
						window.location='ubahKaryawan.php?kode=$kode';	
					</script>";
				exit();
				}
				else if(!$benar)
				{
					print "<script> alert('Alamat email harap diisi dengan benar!') </script>";
					 print "<script>
						window.location='ubahKaryawan.php?kode=$kode';	
					</script>";
				exit();
				}
				else
				{	
					//$today=getdate();
					//$thnskrg=$today['year'];
					//$carijabatan=mysql_query("SELECT * FROM detailjabatan WHERE KodeDosen=$kode AND $thnskrg>=dt.TahunAwal AND $thnskrg<=dt.TahunAkhir");
					//$barisjabatan=@mysql_fetch_assoc($carijabatan);
					if($fakultas!=0)
					{
						$str="UPDATE dosen SET Nama='$nama' ,Fakultas='$fakultas' ,Jurusan='$jurusan' ,Alamat='$alamat',NoTelp='$notelp',Email='$email' WHERE Kode='$kode'";
					}
					else 
					{
						$str="UPDATE dosen SET Nama='$nama', Alamat='$alamat',NoTelp='$notelp',Email='$email' WHERE Kode='$kode'";
					}
					$query=mysql_query($str);
					if($query)
					{
						print "<script>
							alert('Data telah diubah!');	
						 </script>";
						print "<script>
								window.location='karyawan.php';	
							</script>";
						exit();
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

	<title>Academic Advisor | Admin :: Tambah Karyawan </title>
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

</script>


</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">

		<table border="0" align="center" width="100%">
			  <tr>
			  <td colspan="5">
			  <form action="ubahKaryawan.php?kode=<? echo $kode; ?>" method="post">
			 <br/><div style="font-size:20px" align="center">UBAH KARYAWAN</div><br/>
			  	<table border="0" align="center" cellpadding="4" cellspacing="0" style="margin:0px 50px 50px 290px">
				<tr>
					<td>
						<table align="center" border="0" cellpadding="4">
							<tr>
								<td align="right"><strong>Kode</strong></td>
								<td><? 
										echo $baris['Kode']; ?> 
								</td>
							</tr>
							<tr>
								<td align="right"><strong>Nama</strong></td>
								<td><input type="text" name="nama" value="<? echo $baris['Nama']; ?>" size="25"></td>
							</tr>
							
								<? if($baris['Fakultas']!=0)
								{
									echo"<tr><td align='right'><strong>Fakultas</strong></td>
									<td>";
								
									$hasil=mysql_query("SELECT DISTINCT f.* FROM fakultas as f, jurusan as j WHERE f.Kode<>0 AND f.Kode=j.KodeFakultas");
									$barisEdit=mysql_fetch_assoc($hasil);
									echo "<select id='cboFakultas' name='cboFakultas'>";
									while($barisEdit)
									{
										if($barisEdit['Kode']==$baris['Fakultas'])
										{
											echo"<option value='".$barisEdit['Kode']."' selected onclick=\"getData('aturJurusan.php?kode='+document.getElementById('cboFakultas').value,'divJurusan1')\">".$barisEdit['Nama']."</option>";
										}
										else
										{
											echo"<option value='".$barisEdit['Kode']."' onclick=\"getData('aturJurusan.php?kode='+document.getElementById('cboFakultas').value,'divJurusan1')\">".$barisEdit['Nama']."</option>";
										}
										$barisEdit=mysql_fetch_assoc($hasil);
									}
									echo"</select>"; 
									echo"</td></tr>"; 
								}
								
								/*else if($baris['KodeJabatan']==3)
								{
									echo"<tr><td align='right'>Fakultas</td>
									<td>";
								
									$hasil=mysql_query("SELECT DISTINCT f.* FROM fakultas as f, jurusan as j WHERE f.Kode<>0 AND f.Kode=j.KodeFakultas");
									$barisEdit=mysql_fetch_assoc($hasil);
									echo "<select id='cboFakultas' name='cboFakultas'>";
									while($barisEdit)
									{
										if($barisEdit['Kode']==$baris['Fakultas'])
										{
											echo"<option value='".$barisEdit['Kode']."' selected >".$barisEdit['Nama']."</option>";
										}
										else
										{
											echo"<option value='".$barisEdit['Kode']."'>".$barisEdit['Nama']."</option>";
										}
										$barisEdit=mysql_fetch_assoc($hasil);
									}
									echo"</select>"; 
									echo"</td></tr>"; 
								}*/
								?> 
							
							<? if($baris['Fakultas']!=0)
							{
								echo"<tr>
									<td align='right'><strong>Jurusan</strong></td>
									<td><div id='divJurusan1'>";
								
									$query=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='".$baris['Fakultas']."'");
									$barisEditJurusan=mysql_fetch_assoc($query);
									echo"<select id='cboJurusan' name='cboJurusan'>";
									while($barisEditJurusan)
									{
										if($barisEditJurusan['Kode']==$baris['Jurusan'])
										{
											echo"<option value='".$barisEditJurusan['Kode']."' selected>".$barisEditJurusan['Nama']."</option>"; 
										}
										else
										{
											echo"<option value='".$barisEditJurusan['Kode']."'>".$barisEditJurusan['Nama']."</option>"; 
										}
										$barisEditJurusan=mysql_fetch_assoc($query);
									}
									echo"</select>"; 
								echo"</div></td>
							</tr>"; 
							}
							?>
							<!-- <tr>
								<td align="right">Jabatan</td>
								<td><? //echo $baris['Jabatan']; ?></td>
							</tr>
							<tr>
								<td align="right">Tahun awal</td>
								<td><? //echo $baris['TahunAwal']; ?></td>
							</tr>
							<tr>
								<td align="right">Tahun akhir</td>
								<td><? //echo $baris['TahunAkhir']; ?></td>
							</tr> -->
							<tr>
								<td align="right"><strong>Alamat</strong></td>
								<td><input type="text" name="alamat" value="<? echo $baris['Alamat']; ?>"></td>
							</tr>
							<tr>
								<td align="right"><strong>No.Telp</strong></td>
								<td><input type="text" name="notelp" value="<? echo $baris['NoTelp']; ?>" size="15"></td>
							</tr>
							<tr>
								<td align="right"><strong>Email</strong></td>
								<td><input type="text" name="email" value="<? echo $baris['Email']; ?>" size="25"></td>
							</tr>
							<tr>
								<td colspan="2" align="left"><br/><input type="submit" name="submit" value="Simpan">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="submit" name="submit" value="Batal">
								</td>
								
							</tr>
						</table>
					
					</td>
				</tr>
				</table>
				</form>
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
			 <li><a href="mahasiswa.php">Mahasiswa</a></li>
			  <li><a href="forum.php">Forum</a></li>
			    <li><a href="settingmail.php">Setting E-mail</a></li>
		   </ul>
		 </div> 

   		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>