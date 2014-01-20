<?php
session_start();
include("config.php");
include('DataFormatter.class.php');
$username = isset($_SESSION['usernamee']) ? $_SESSION['usernamee'] : null;
$password = isset($_SESSION['passwordd']) ? $_SESSION['passwordd'] : null;
$token=DataFormatter::generateId('SL');
if(empty($username) && empty($password))
{
        print "<script>
                        alert('Maaf sessi login anda sudah habis!');						
                   </script>";
        print "<script>
                        window.location='index.php';
                   </script>";
}
if($_SERVER['REQUEST_METHOD']=="POST") {
    $nrp=isset($_REQUEST['nrp']) ? $_REQUEST['nrp'] : null ;
    $nama=isset($_REQUEST['nama']) ? $_REQUEST['nama'] : 'invalid' ;

    if(empty($nrp) || ($nama=='invalid'))
    {
            $_SESSION['pesan']='Maaf data Mahasiswa yang anda masukkan salah!';
            header('Location: formSksLebih.php?token='.$token);
            exit;
    }    
     
    $updatedAt=date('Y-m-d H:i:s');
    $rs=mysql_query("SELECT id FROM semester WHERE status_aktif=1");
    $sms=mysql_fetch_array($rs);
    if ($sms) {
        $semesterAktif=$sms[0];
    } else {
        $semesterAktif=DEFAULT_SEMESTER_AKTIF;
    }
    $_SESSION['semester']=$semesterAktif;
    $pesan="Maaf update tambah SKS untuk Mahasiswa $nrp gagal!";
     
        $sql="INSERT INTO buka_lock_ip2(nrp,tahun_semester,npk,updated_at)
            VALUES($nrp,$semesterAktif,$username,'$updatedAt')";
        //file_put_contents('/tmp/aa.sql', $sql);
        $rs=mysql_query($sql);
        
                                                    $sql="UPDATE tk_mhs SET konsultasi=1, aa=1 where nrp='$nrp'";
                                                    
                                                    $rs=mysql_query($sql,$connFT);
        
        $_SESSION['pesan']="Record baru Buka Kuncian IPK/IPS<2 untuk mahasiswa $nrp $nama dengan  sudah berhasil dijalankan";   
            header('Location: formBukaIp2.php?token='.$token);
            exit;    
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Buka Kuncian IPK/IPS kurang dari 2 </title>
	<link rel="stylesheet" href="style.css" media="screen" />	
        <script src="prototype/js/prototype.js" type="text/javascript"></script>
        
    <script type="text/javascript">
 
function getDataMhs(fieldNrp) {
    
  var nrp=fieldNrp.value;
   
  var url='getDataMhs.php?nrp='+nrp;
  var aj = new Ajax.Request(  
  url, {  
   method:'get',   
   onComplete: function(oReq) {
       $('nama').value = oReq.responseText;  
   }  
   }  
  );
    
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
			  <form action="formBukaIp2.php" method="post">
			 <br/><div style="font-size:20px" align="center">Form Buka Kuncian IPK/IPS kurang dari 2 di Perwalian</div><br/><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" align="center" width="100%"> 
				<tr>
					<td align="right"><strong>NRP</strong></td>
					<td width="80%"><input type="text" name="nrp" id="nrp" value="" onblur="getDataMhs(this)" /></td>
				</tr>
				 
				<tr>
					<td align="right"><strong>Nama</strong></td>
					<td width="80%"><input type="text" name="nama" id="nama" value="" size="80" readonly="true" /></td>
							
				
				</tr>
				 
 
				
				<tr>
					<td align="center" colspan="2"><br/><input type="submit" value="Buka Kuncian Perwalian" name="submit" />
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Batal" name="batal" onclick="document.location='index.php'" />
					 </td>
				</tr>
				
				</table>
				</form> 
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