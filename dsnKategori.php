<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	
	$query="SELECT * FROM kategori ORDER BY Kode"; 
	/*else
	{
		$query="SELECT * FROM kategori WHERE Kode<>0 ";
	}*/
	$hasil=mysql_query($query);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Dosen :: Kategori Masalah </title>
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
			 
			  <tr>
 			    <td><br>
				<? 
										
					$baris=mysql_fetch_assoc($hasil);
					if($baris)
					{
						$warna=1;
						echo "<table border='0' align='center'><caption align='center' style='font-size:20px'>DATA KATEGORI PERMASALAHAN</caption>
						<tr>
							 
							<th>Nama</th> 
							<th>Deskripsi</th>
							
						</tr>";
					   while($baris)
					   {
					   	if($warna%2==0)
						{	echo "
							<tr bgcolor='#E2E2E2'><td>".$baris['Nama']."</td><td>".$baris['Deskripsi']."</td></tr>";
						}
						else
						{
							echo "
							<tr bgcolor='#F0F0F0'><td>".$baris['Nama']."</td><td>".$baris['Deskripsi']."</td></tr>";
						}
						$warna=$warna+1;
						 $baris=mysql_fetch_assoc($hasil);
						  
					 }
					
					echo"</table>";
					}
					 else
						{print "<font color='red' size='5' ><i><center>Tidak ada data kategori.</center></i></font>";
						}
						
					
				 ?>
				
			    </td>
			  </tr>
			  <tr>
			  	<td align="right">
			  		
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
