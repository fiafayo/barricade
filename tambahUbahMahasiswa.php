<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nrpFrom=$_REQUEST['nrpFrom'];
	$kesalahan="";
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
		if(empty($nrpFrom))
		{$nrp=$_REQUEST['nrp'];}
		else
		{$nrp=$nrpFrom;}
		$nama=$_REQUEST['nama'];
		$fakultas=$_REQUEST['cboFakultas'];
		$jurusan=$_REQUEST['cboJurusan'];
		$alamat=$_REQUEST['alamat'];
		$notelp=$_REQUEST['notelp'];
		$email=$_REQUEST['email'];
		$benar=eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
		
		if($_POST['submit']=='Batal')
		{
			header('Location: mahasiswa.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($nrp) or empty($nama) or empty($alamat) or empty($notelp) or empty($email))
			{
				/*print "<script>
						alert('Semua data harap diisi!');	
	  				 </script>";*/
				if(empty($nrpFrom))
				{
					$kesalahan="data kosong";
						/*print "<script>
							window.location='tambahUbahMahasiswa.php';	
						</script>";*/
				}
				else
				{
				print "<script>
						alert('Semua data harap diisi!');	
	  				 </script>";
					print "<script>
							window.location='tambahUbahMahasiswa.php?nrpFrom=$nrpFrom';	
						</script>";
				exit();
				}
				
			}
			else
			{
				if(empty($nrpFrom))
				{
					$cekNrp=mysql_query("SELECT NRP FROM mahasiswa");
					$barisNrp=mysql_fetch_assoc($cekNrp);
					$adaNrp=false;
					while($barisNrp)
					{
						if($barisNrp['NRP']==$nrp)
						{
							$adaNrp=true;
							break;
						}
						$barisNrp=mysql_fetch_assoc($cekNrp);
					}
				}
				if(cekInteger($nrp)==false)
				{
					/*print "<script> alert('NRP harus angka!') </script>";*/
					if(empty($nrpFrom))
					{
						/*print "<script>
								window.location='tambahUbahMahasiswa.php';	
							</script>";*/
							$kesalahan="nrp bukan angka";
					}
					else
					{
						print "<script> alert('NRP harus angka!') </script>";
						print "<script>
								window.location='tambahUbahMahasiswa.php?nrpFrom=$nrpFrom';	
							</script>";
							exit();
					}
					
				}
				else if($adaNrp==true && empty($nrpFrom))
				{
					/*print "<script> alert('NRP sudah ada!') </script>";*/
					if(empty($nrpFrom))
					{
						/*print "<script>
								window.location='tambahUbahMahasiswa.php';	
							</script>";*/
							$kesalahan="nrp sudah ada";
					}
					else
					{
					print "<script> alert('NRP sudah ada!') </script>";
					print "<script>
								window.location='tambahUbahMahasiswa.php?nrpFrom=$nrpFrom';	
							</script>";
							exit();
					}
					
				}
				else if(substr($nrp,0,strlen($fakultas))!=$fakultas)
				{
					/*print "<script> alert('Format NRP tidak sesuai dengan fakultas!') </script>";*/
					if(empty($nrpFrom))
					{
						/*print "<script>
								window.location='tambahUbahMahasiswa.php';	
							</script>";*/
							$kesalahan="nrp tidak sesuai dengan fakultas";
					}
					else
					{
					print "<script> alert('Format NRP tidak sesuai dengan fakultas!') </script>";
					print "<script>
								window.location='tambahUbahMahasiswa.php?nrpFrom=$nrpFrom';	
							</script>";
							exit();
					}
					
				}
				else if(/*$jurusan!="-" && */substr($nrp,strlen($fakultas)+2,strlen($jurusan))!=$jurusan)
				{
					/*print "<script> alert('Format NRP tidak sesuai dengan jurusan!') </script>";*/
					if(empty($nrpFrom))
					{
						/*print "<script>
								window.location='tambahUbahMahasiswa.php';	
							</script>";*/
							$kesalahan="nrp tidak sesuai dengan jurusan";
					}
					else
					{
					print "<script> alert('Format NRP tidak sesuai dengan jurusan!') </script>";
					print "<script>
								window.location='tambahUbahMahasiswa.php?nrpFrom=$nrpFrom';	
							</script>";
							exit();
					}
					
				}
				else if(cekInteger($notelp)==false)
				{
					/*print "<script> alert('Nomor telepon harus angka!') </script>";*/
					if(empty($nrpFrom))
					{
						/*print "<script>
								window.location='tambahUbahMahasiswa.php';	
							</script>";*/
						$kesalahan="nomor telepon bukan angka";
					}
					else
					{
					print "<script> alert('Nomor telepon harus angka!') </script>";
					print "<script>
								window.location='tambahUbahMahasiswa.php?nrpFrom=$nrpFrom';	
							</script>";
							exit();
					}
					
				}
				else if(!$benar)
				{
					/*print "<script> alert('Alamat email harap diisi dengan benar!') </script>";*/
					if(empty($nrpFrom))
					{
						/*print "<script>
								window.location='tambahUbahMahasiswa.php';	
							</script>";*/
						$kesalahan="alamat email salah";
					}
					else
					{
					print "<script> alert('Alamat email harap diisi dengan benar!') </script>";
					print "<script>
								window.location='tambahUbahMahasiswa.php?nrpFrom=$nrpFrom';	
							</script>";
							exit();
					}
					
				}
				else
				{
					if(empty($nrpFrom))
					{
						$str="INSERT INTO mahasiswa(NRP,Nama,Fakultas,Jurusan,Alamat,NoTelp,Email) values ('$nrp','$nama','$fakultas','$jurusan','$alamat','$notelp','$email')";
						$query=mysql_query($str);
						print "<script>
							alert('Data telah disimpan!');	
						 </script>";
						print "<script>
								window.location='tambahUbahMahasiswa.php';	
							</script>";
						exit();
						
					}
					else
					{
						$str="UPDATE mahasiswa SET Nama='$nama',Fakultas='$fakultas',Jurusan='$jurusan',Alamat='$alamat',NoTelp='$notelp',Email='$email' WHERE NRP='$nrp'";
						$query=mysql_query($str);
						print "<script>
							alert('Data telah diubah!');	
						 </script>";
						print "<script>
								window.location='mahasiswa.php';	
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

	<title>Academic Advisor | Admin :: Tambah Mahasiswa </title>
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

function isNumber(str,nama)
{
	var charCode = (str.which)?str.which:str.keyCode;	
	var field = eval(nama);
	
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	
		/*if (field.value > 23)
		{
			alert(field.value);
			return false;
		}*/
	
	return true;
}
</script>

</head>
			
<body>
<? if($kesalahan=="data kosong")
	{
		print "<script>
				alert('Semua data harap diisi!');	
			 </script>";
	}
	else if($kesalahan=="nrp bukan angka")
	{
		print "<script> alert('NRP harus angka!') </script>";
	}
	else if($kesalahan=="nrp sudah ada")
	{
		print "<script> alert('NRP sudah ada!') </script>";
	}
	else if($kesalahan=="nrp tidak sesuai dengan fakultas")
	{
		print "<script> alert('Format NRP tidak sesuai dengan fakultas!') </script>";
	}
	else if($kesalahan=="nrp tidak sesuai dengan jurusan")
	{
		print "<script> alert('Format NRP tidak sesuai dengan jurusan!') </script>";
	}
	else if($kesalahan=="nomor telepon bukan angka")
	{
		print "<script> alert('Nomor telepon harus angka!') </script>";
	}else if($kesalahan=="alamat email salah")
	{
		print "<script> alert('Alamat email harap diisi dengan benar!') </script>";
	}
?>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0"  cellspacing="0" width="100%" >
			  <tr>
			  <td colspan="5">
			  <form action="tambahUbahMahasiswa.php?nrpFrom=<? echo $nrpFrom; ?>" method="post">
			 <br/><div style="font-size:20px" align="center"><? if (empty($nrpFrom)) {echo"TAMBAH MAHASISWA";}
			 else {echo"UBAH MAHASISWA";} ?> </div><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" style="margin:0px 50px 50px 280px" > 
				<tr>
					<td align="right"><strong>NRP</strong></td>
					<td><? if(empty($nrpFrom))
						{ 	if($kesalahan=="data kosong" || $kesalahan=="nomor telepon bukan angka" || $kesalahan=="alamat email salah")
							{	$isi=$nrp;}
							else
							{	$isi="";}
								echo"<input type='text' onkeypress='return isNumber(event,this);' name='nrp' value='".$isi."' id='nrp' size=15>"; }
						else
						{
						 $hasil = mysql_query("SELECT * FROM mahasiswa WHERE NRP='$nrpFrom'"); 
						 $barisEdit=mysql_fetch_assoc($hasil);
						echo $barisEdit['NRP']; 
						}?></td>
				</tr>
				<tr>
					<td align="right"><strong>Nama</strong></td>
					<td><input type="text" name="nama" value="<?  if(!empty($nrpFrom)) echo $barisEdit['Nama']; 
							else
							{
								if(!empty($kesalahan))
									echo $nama;
							}
						?>" size="25"></td>
				</tr>
				<tr>
					<td align="right"><strong>Fakultas</strong></td>
					<td><? 	//if(empty($nrpFrom))
							
								$hasil = mysql_query("SELECT DISTINCT f.Kode,f.Nama FROM fakultas as f,jurusan as j WHERE f.Kode IN(j.KodeFakultas) AND f.Kode<>0 ORDER BY f.Kode"); 
								$baris=mysql_fetch_assoc($hasil);
								echo "<select id='cboFakultas' name='cboFakultas'>";
								while($baris)
								{
									if($barisEdit['Fakultas']==$baris['Kode'])
									{
										echo"<option value='".$baris['Kode']."' selected onclick=\"getData('aturJurusan.php?kode='+document.getElementById('cboFakultas').value,'targetDiv')\">".$baris['Nama']."</option>";
									}
									else if($baris['Kode']==$fakultas)
									{
										echo"<option value='".$baris['Kode']."' selected onclick=\"getData('aturJurusan.php?kode='+document.getElementById('cboFakultas').value,'targetDiv')\">".$baris['Nama']."</option>";
									}
									else
									{
										echo"<option value='".$baris['Kode']."' onclick=\"getData('aturJurusan.php?kode='+document.getElementById('cboFakultas').value,'targetDiv')\">".$baris['Nama']."</option>";
									}
									$baris=mysql_fetch_assoc($hasil);
								}
								echo"</select>";
							
							?>
							
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Jurusan</strong></td>
					<td>
						
						<div id="targetDiv">
							<?
							if(empty($nrpFrom))
							{	
								if(!empty($kesalahan))
									$query=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='$fakultas'");
								else
									$query=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas=1");
							}
							else
							{	
								$query=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas=".$barisEdit['Fakultas']."");
							}
								
							$baris=mysql_fetch_assoc($query);
							echo"<select id='cboJurusan' name='cboJurusan'>";
							while($baris)
							{
								if($barisEdit['Jurusan']==$baris['Kode'])
								{	echo"<option value='".$baris['Kode']."' selected>".$baris['Nama']."</option>"; }
								else if($baris['Kode']==$jurusan)
								{	echo"<option value='".$baris['Kode']."' selected>".$baris['Nama']."</option>";}
								else
								{	echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>"; }
								
								$baris=mysql_fetch_assoc($query);
							}
							echo"</select>";
							
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Alamat</strong></td>
					<td><input type="text" name="alamat" value="<?  if(!empty($nrpFrom)) echo $barisEdit['Alamat']; 
						else
						{
							if(!empty($kesalahan))
								echo $alamat;
						}
						?>"></td>
				</tr>
				<tr>
					<td align="right"><strong>No.Telp</strong></td>
					<td><input type="text" name="notelp" onkeypress="return isNumber(event,this);" value="<?  if(!empty($nrpFrom)) echo $barisEdit['NoTelp']; 
						else
						{
							if($kesalahan!="nomor telepon bukan angka")
								echo $notelp;
						}
					?>" size="15"></td>
				</tr>
				<tr>
					<td align="right"><strong>E-mail</strong></td>
					<td><input type="text" name="email" value="<?  if(!empty($nrpFrom)) echo $barisEdit['Email']; 
						else
						{
							if($kesalahan!="alamat email salah")
								echo $email;
						}
					?>" size="25"></td>
				</tr>
				<tr>
					<td colspan="2" align="left" ><br/><input type="submit" value="Simpan" name="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" value="Batal" name="submit"></td>
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

