<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nopengadaan=$_REQUEST['nopengadaan'];
	$blhmasuk=false;
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	$today=getdate();
	$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
	$periksa=mysql_query("SELECT p.*, o.Fakultas, o.Jurusan FROM pengadaan as p, dosen as o
						WHERE p.Nopengadaan='$nopengadaan' AND p.Kode=o.Kode AND p.Tanggal>='$tanggalskrg' AND p.Status<>'Batal'
						");
	$mhs=mysql_query("SELECT * FROM mahasiswa WHERE NRP='$username'");
	$brsperiksa=mysql_fetch_assoc($periksa);
	$brsmhs=mysql_fetch_assoc($mhs);
	if($brsperiksa)
	{
		if($brsperiksa['Fakultas']==$brsmhs['Fakultas'] && $brsperiksa['Jurusan']==$brsmhs['Jurusan'])
		{
			$sudahada=mysql_query("SELECT * FROM pendaftaran WHERE NoPengadaan='$nopengadaan' AND NRP='$username' AND Status=0");
			if(mysql_num_rows($sudahada)>0)
			{
				print "<script>
					alert('Anda sudah terdaftar dalam pengadaan tersebut!');	
					 </script>";
					print "<script>
							window.location='daftarKonsul.php';	
						</script>";
					exit();
			}
			else
			{
				$qpengadaan=mysql_query("SELECT p.* FROM pengadaan as p WHERE p.NoPengadaan='$nopengadaan'");
				$brspengadaan=mysql_fetch_assoc($qpengadaan);
				$qpendaftaran=mysql_query("SELECT d.* FROM pendaftaran as d WHERE d.Nopengadaan='$nopengadaan'");
				if(mysql_num_rows($qpendaftaran)==$brspengadaan['MaxMhs'])
				{
					print "<script>
					alert('Pengadaan sudah terisi penuh');	
					 </script>";
					print "<script>
							window.location='daftarKonsul.php';	
						</script>";
					exit();
				}
			}
		}
		else
		{
			print "<script>
			alert('Anda tidak dapat mendaftar pada pengadaan tersebut!');	
			 </script>";
			print "<script>
					window.location='daftarKonsul.php';	
				</script>";
			exit();
		}
	}
	elseif(!$brsperiksa)
	{
		print "<script>
			alert('Tidak ada pengadaan dengan nomor $nopengadaan !');	
			 </script>";
			print "<script>
					window.location='daftarKonsul.php';	
				</script>";
			exit();
	}
	
	if($_SERVER['REQUEST_METHOD']=="POST")
	{		
		if($_POST['submit']=='Batal')
		{
			header('Location: daftarKonsul.php');
			exit;
		}
		else if ($_POST['submit']=='Daftar')
		{
		
			$noPendaftaran="";
			$today=getdate();
			$thnskrg=substr($today['year'],2,2);
			$thnlalu=substr($today['year'],2,2)-1;
			if ($today['mon']>=2 && $today['mon']<=7)
			{	$noPendaftaran="DE".str_pad($thnlalu, 2, "0",STR_PAD_LEFT).$thnskrg; 	}
			else
			{
				if($today['mon']==1)
				{	$noPendaftaran="DA".str_pad($thnlalu, 2, "0",STR_PAD_LEFT).$thnskrg; 	}
				else
				{	$noPendaftaran="DA".$thnskrg.str_pad($thnskrg+1, 2, "0",STR_PAD_LEFT); 	}
			}
			$query=mysql_query("SELECT * FROM pendaftaran WHERE NoPendaftaran LIKE '$noPendaftaran%' ORDER BY NoPendaftaran ASC");
			$baris=mysql_fetch_assoc($query);
			$ctr=1;
			while($baris)
			{
				if(substr($baris['NoPendaftaran'],6,4)==$ctr)
				{
					$ctr=$ctr+1;
				}
				else
				{	break;}
				$baris=mysql_fetch_assoc($query);
			}
			$noPendaftaran=$noPendaftaran.str_pad($ctr,4,"0",STR_PAD_LEFT);
			
			$qpengadaan=mysql_query("SELECT p.*, o.Nama, o.Email FROM pengadaan as p, dosen as o WHERE p.NoPengadaan='$nopengadaan' AND p.Kode=o.Kode");
			$brspengadaan=mysql_fetch_assoc($qpengadaan);
			$j=mysql_num_rows($qpendaftaran);
			$menitselesai=substr($brspengadaan['JamSelesai'],3,2);
			$menitmulai=substr($brspengadaan['JamMulai'],3,2);
			$waktuselesai=($brspengadaan['JamSelesai']*60)+$menitselesai;
			$waktumulai=($brspengadaan['JamMulai']*60)+$menitmulai;
			$selisih=$waktuselesai-$waktumulai;
			$rata2waktu=$selisih/$brspengadaan['MaxMhs'];
			$perkiraandtg=$waktumulai+($rata2waktu*$j);
			$jamdtg=floor($perkiraandtg/60);
			$menitdtg=$perkiraandtg%60;
			$jamDatang=str_pad($jamdtg,2,"0",STR_PAD_LEFT).":".str_pad($menitdtg,2,"0",STR_PAD_LEFT).":00";
			
			$insert=mysql_query("INSERT INTO pendaftaran (NoPendaftaran, JamDatang, StatusMhs, NRP, NoPengadaan) VALUES('$noPendaftaran','$jamDatang','Belum Dilayani','$username','$nopengadaan')");
			if($insert)
			{
                            include_once('./lib/swift_required.php');
                            
                            // Create the Transport
                            $transport = Swift_SmtpTransport::newInstance('mail.ubaya.ac.id', 25)                       ;

                            /*
                            You could alternatively use a different transport such as Sendmail or Mail:

                            // Sendmail
                            $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');

                            // Mail
                            $transport = Swift_MailTransport::newInstance();
                            */

                            // Create the Mailer using your created Transport
                            
//                            $flog=fopen('/tmp/aa.log','w');
//                            fwrite($flog,"Kirim email ke ".$brspengadaan['Email']." ".$brspengadaan['Nama']." dengan subject "."Mahasiswa $username telah mendaftar pada jadwal anda tanggal ".$brspengadaan['Tanggal']." jam ".$brspengadaan['JamMulai']."\n");
//                            fclose($flog);
                            $mailer = Swift_Mailer::newInstance($transport);                            
                            $eml=Swift_Message::newInstance()
                                    ->setSubject("Mahasiswa $username telah mendaftar pada jadwal anda tanggal ".$brspengadaan['Tanggal']." jam ".$brspengadaan['JamMulai'].'-'.$brspengadaan['JamSelesai'] )
                                    ->setFrom( array('advisor@ubaya.ac.id'=>'Academic Advisor FTUBAYA'))
                                    ->setTo( array( $brspengadaan['Email']=>$brspengadaan['Nama'] ) )
                                    ->setBody( "Yth. ".$brspengadaan['Nama'].",\n Seorang Mahasiswa $username telah mendaftar pada jadwal anda tanggal ".$brspengadaan['Tanggal']." jam ".$brspengadaan['JamMulai'].'-'.$brspengadaan['JamSelesai']."\n Kode Referensi Pengadaan=".$brspengadaan['NoPengadaan']."\n\nSystem Web Academic Advisor FTUBAYA (http://perwalianft.ubaya.ac.id/aa/"); 
                            $mailer->send($eml);
                            
				print "<script>
				alert('Pendaftaran telah berhasil!');	
				 </script>";
				print "<script>
						window.location='daftarKonsul.php';	
					</script>";
				exit();
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

	<title>Academic Advisor | Mahasiswa :: Detail Pendaftaran </title>
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
            <table border="0" align="center" width="100%" cellspacing="0">
			  <tr>
			  <td colspan="5">
			  <form action="detailDaftarKonsul.php?nopengadaan=<? echo $nopengadaan; ?>" method="post">
			 <br/><div style="font-size:20px" align="center">Detail Pendaftaran Konsultasi</div><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" style="margin:0px 50px 50px 140px" > 
				<tr>
					<td align="right"><strong>No. Pengadaan</strong></td>
					<td><? 
						 $qpengadaan=mysql_query("SELECT p.*, o.Nama FROM pengadaan as p, dosen as o WHERE p.NoPengadaan='$nopengadaan' AND p.Kode=o.Kode");
						$brspengadaan=mysql_fetch_assoc($qpengadaan);
						$qpendaftaran=mysql_query("SELECT d.* FROM pendaftaran as d WHERE d.Nopengadaan='$nopengadaan'");
						$j=mysql_num_rows($qpendaftaran);
						$menitselesai=substr($brspengadaan['JamSelesai'],3,2);
						$menitmulai=substr($brspengadaan['JamMulai'],3,2);
						$waktuselesai=($brspengadaan['JamSelesai']*60)+$menitselesai;
						$waktumulai=($brspengadaan['JamMulai']*60)+$menitmulai;
						$selisih=$waktuselesai-$waktumulai;
						$rata2waktu=$selisih/$brspengadaan['MaxMhs'];
						$perkiraandtg=$waktumulai+($rata2waktu*$j);
						$jamdtg=floor($perkiraandtg/60);
						$menitdtg=$perkiraandtg%60;
						echo $brspengadaan['NoPengadaan']; 
												
						?></td>
				</tr>
				<tr>
					<td align="right"><strong>Dosen</strong></td>
					<td><? echo $brspengadaan['Nama']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Tanggal</strong></td>
					<td><? echo $brspengadaan['Tanggal']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Hari</strong></td>
					<td><? 	echo $brspengadaan['Hari'];	?>
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Jam mulai</strong></td>
					<td>
						<? 	echo $brspengadaan['JamMulai'];	?>
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Jam selesai</strong></td>
					<td><? echo $brspengadaan['JamSelesai']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Max. Mhs</strong></td>
					<td><? echo $brspengadaan['MaxMhs']; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Jumlah pendaftar</strong></td>
					<td><? echo $j; ?></td>
				</tr>
				<tr>
					<td align="right"><strong>Perkiraan jam datang untuk mahasiswa</strong></td>
					<td><? echo str_pad($jamdtg,2,"0",STR_PAD_LEFT).":".str_pad($menitdtg,2,"0",STR_PAD_LEFT); ?></td>
				</tr>
				
				<tr>
					<td align="right" ><br/><input type="submit" value="Daftar" name="submit"></td>
					<td align="left"><br/><input type="submit" value="Batal" name="submit"></td>
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
    <li><a href="homeMahasiswa.php">Halaman Utama</a></li>
			  <li><a href="daftarKonsul.php">Daftar Konsultasi</a></li>
			   <li><a href="batalDaftarKonsul.php">Batal Daftar Konsultasi</a></li>
			<li><a href="riwayatKonsulMhs.php">Riwayat Konsultasi</a></li>
			 <li><a href="konsulOnline.php">Konsultasi Online</a></li>
   </ul>
 </div> 

   		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

