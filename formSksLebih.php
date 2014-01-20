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
    $sks=isset($_REQUEST['sks']) ? $_REQUEST['sks'] : null ;
    $alasan=isset($_REQUEST['alasan']) ? $_REQUEST['alasan'] : null ;
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
    $rs=mysql_query("SELECT id FROM tambah_sks WHERE nrp=$nrp AND tahun_semester=$semesterAktif");
    $tsks=mysql_fetch_array($rs);
    if ($tsks) {
        $id=$tsks[0];
        $rs=mysql_query("UPDATE tambah_sks SET sks=$sks, alasan='$alasan', updated_at='$updatedAt' where id=$id ");
        $_SESSION['pesan']="UPDATE Tambah SKS untuk mahasiswa $nrp $nama dengan  sks=$sks sudah berhasil dijalankan";
    } else {
        $sql="INSERT INTO tambah_sks(sks,nrp,alasan,tahun_semester,npk,created_at,updated_at)
            VALUES($sks,$nrp,'$alasan',$semesterAktif,$username,'$updatedAt','$updatedAt')";
        //file_put_contents('/tmp/aa.sql', $sql);
        $rs=mysql_query($sql);
        $_SESSION['pesan']="Record baru Tambah SKS untuk mahasiswa $nrp $nama dengan  sks=$sks sudah berhasil dijalankan";
    }
            $_SESSION['nrp_cetak']=$nrp;
            header('Location: formSksLebihCetak.php?token='.$token);
            exit;    
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Dispensasi SKS Lebih </title>
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
			  <form action="formSksLebih.php" method="post">
			 <br/><div style="font-size:20px" align="center">Form Dispensasi Mengambil SKS Lebih</div><br/><br/>
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
					<td align="right"><strong>SKS Tambahan</strong></td>
					<td width="80%"><select name="sks" id="sks">
                                                <?php
                                                for ($i=1;$i<10;$i++) {
                                                    echo "<option value='$i'>$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
							
				
				</tr>
				<tr>
					<td align="right"><strong>Alasan</strong></td>
					<td width="80%">
                                            <select name="alasan" id="alasan"  >
                                                <option value="prestasi">Mahasiswa Berprestasi (IPK>=3.75)</option>
                                                <option value="tuntas">Mahasiswa tuntas</option>
                                                <option value="do">Mahasiswa Terancam DO</option>
                                            </select>
                                        
                                        </td>
							
				
				</tr>
				
				<tr>
					<td align="center" colspan="2"><br/><input type="submit" value="Simpan" name="submit" />
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