<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$kodeFak = $_REQUEST['kodefak'];
	$kodeJur = $_REQUEST['kodejur'];
	
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
		$namaJurusan=$_REQUEST['namaJurusan'];
		if(empty($kodeFak) && empty($kodeJur))
		{
			$kodeFakultas=$_REQUEST['cboFakultas'];
			$kodeJurusan=$_REQUEST['kodeJurusan'];
		}
		else
		{
			$kodeFakultas=$kodeFak;
			$kodeJurusan=$kodeJur;	
		}
		//$hasil=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='$kodeFakultas' ORDER BY Kode DESC");
		//$baris=mysql_fetch_assoc($hasil);
		//$nextKodeJurusan=$baris['Kode']+1;
	
		if($_POST['submit']=='Batal')
		{
			header('Location: jurusan.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($namaJurusan) || $kodeJurusan=="")
			{
				print "<script>
						alert('Nama dan kode jurusan harap diisi!');	
	  				 </script>";
				print "<script>
						window.location='tambahUbahJurusan.php?kodefak=$kodeFak&kodejur=$kodeJur';	
	   				</script>";
				exit();
			}
			else
			{
				if(cekInteger($kodeJurusan)==false)
				{
					print "<script>
						alert('kode jurusan harus berupa angka!');	
	  				 </script>";
				print "<script>
						window.location='tambahUbahJurusan.php?kodefak=$kodeFak&kodejur=$kodeJur';	
	   				</script>";
				exit();
				}
				else
				{
					$str="SELECT * FROM jurusan WHERE KodeFakultas='$kodeFakultas'";
					$hasil=mysql_query($str);
					$baris=mysql_fetch_assoc($hasil);
					
					$ada=false;
					$adaKode=false;
					while($baris)
					{
						if(strcmp(strtolower($baris['Nama']),strtolower($namaJurusan))==0)
						{
							$ada=true;
							break;
						}
						else if($baris['Kode']==$kodeJurusan)
						{
							$adaKode=true;
							break;
						}
						$baris=mysql_fetch_assoc($hasil);
					}
					if($ada==true)
					{
						print "<script>
							alert('Nama Jurusan sudah ada!');	
						 </script>";
						print "<script>
								window.location='tambahUbahJurusan.php?kodefak=$kodeFak&kodejur=$kodeJur';	
							</script>";
						exit();
					}
					
					else
					{
						if(empty($kodeFak) && empty($kodeJur))
						{
							if($adaKode==true)
							{
								print "<script>
									alert('Kode Jurusan sudah ada!');	
								 </script>";
								print "<script>
										window.location='tambahUbahJurusan.php?kodefak=$kodeFak&kodejur=$kodeJur';	
									</script>";
								exit();
							}
							$str="SELECT * FROM jurusan WHERE KodeFakultas='$kodeFakultas'";
							$hasil=mysql_query($str);
							$baris=mysql_fetch_assoc($hasil);
							if($kodeJurusan==0 && $baris)
							{
								print "<script>
									alert('Tidak dapat membuat kode jurusan menggunakan 0!');	
								 </script>";
								print "<script>
										window.location='tambahUbahJurusan.php?kodefak=$kodeFak&kodejur=$kodeJur';	
									</script>";
								exit();
							}
							$str="INSERT INTO jurusan(Kode,Nama,KodeFakultas) values ('$kodeJurusan','$namaJurusan','$kodeFakultas')";
							$query=mysql_query($str);
							print "<script>
								alert('Data telah disimpan!');	
							 </script>";
							print "<script>
									window.location='jurusan.php';	
								</script>";
							exit();
						}					
						else
						{
							$str="UPDATE jurusan SET Nama='$namaJurusan' WHERE KodeFakultas='$kodeFak' AND Kode='$kodejur'";
							$query=mysql_query($str);
							print "<script>
								alert('Data telah diubah!');	
							 </script>";
							print "<script>
									window.location='jurusan.php';	
								</script>";
						exit();
						}
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

	<title>Academic Advisor | Admin :: Tambah Jurusan </title>
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
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" cellspacing="0">
			  <tr>
			  <td colspan="5">
			  <form action="tambahUbahJurusan.php?kodefak=<? echo $kodeFak; ?>&kodejur=<? echo $kodeJur; ?>" enctype="multipart/form-data" method="post">
			 <br/><div style="font-size:20px" align="center"><? if(empty($kodeFak) && empty($kodeJur)) { echo "TAMBAH JURUSAN";}
			 		else { echo"UBAH JURUSAN";} ?>
			 </div><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" style="margin:0px 50px 50px 280px" > 
				<tr>
					<td align="right"><strong>Fakultas</strong></td>
					<td><? 	if(empty($kodeFak) &&  empty($kodeJur))
							{
								$hasil = mysql_query("SELECT * FROM fakultas WHERE Kode<>0"); 
								$baris=mysql_fetch_assoc($hasil);
								echo "<select id='cboFakultas' name='cboFakultas'>";
								while($baris)
								{
									echo"<option value='".$baris['Kode']."' onclick=\"getData('tampilKodeJurusan.php?kode='+document.getElementById('cboFakultas').value,'targetDiv')\">".$baris['Kode']."-".$baris['Nama']."</option>";
									//echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>";
									$baris=mysql_fetch_assoc($hasil);
								}
								echo"</select>";
							}
							else
							{
								
								$hasil = mysql_query("SELECT * FROM fakultas WHERE Kode='$kodeFak'"); 
								$baris=mysql_fetch_assoc($hasil);
								echo $baris['Nama'];
							}?></td>
				</tr>
				<tr>
					<td align="right"><strong>Kode Jurusan</strong></td>
					<td><? if(empty($kodeFak) &&  empty($kodeJur))
							{ $hasil=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas=1 ORDER BY Kode DESC");
								$baris=mysql_fetch_assoc($hasil);
								if($baris)
								{
									$nextKode=$baris['Kode']+1;
								}
								else
								{
									$nextKode=0;
								}
								
							echo"<div id='targetDiv'><input type='text' name='kodeJurusan' value='$nextKode' size=2 maxlength='2' onkeypress='return isNumber(event,this);'><font size='-2' color='red'><b>Massukkan 0 bila fakultas tidak mempunyai jurusan</b></font></div>";
							}
							else
							{
							
								echo $kodeJur;
								$hasil = mysql_query("SELECT * FROM jurusan WHERE Kode='$kodeJur' AND KodeFakultas='$kodeFak'"); 
								$baris=mysql_fetch_assoc($hasil);
								
							}
							?>
							
							</td>
				</tr>
				<tr>
					<td align="right"><strong>Nama Jurusan</strong></td>
					<td><input type="text" name="namaJurusan" value="<?php if(empty($kodeFak) && empty($kodeJur)) {echo"";} else echo $baris['Nama']; ?>"></td>
				</tr>
				<tr>
					<td align="right" ><br/><input type="submit" value="Simpan" name="submit"></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<td><br/><input type="submit" value="Batal" name="submit"></td>
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

