<?php
	session_start();
	include("config.php");
	
	if( isset($_SESSION['usernamee']) && isset($_SESSION['passwordd']))
  	{
		$usr=$_SESSION['usernamee'];
		if($usr=="admin")
		{
			print "<script>
					  window.location='admin.php';	
				   </script>";	
		}
		else
		{
			$today=getdate();
			$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
			$query = "SELECT dt.* FROM dosen as d, detailjabatan as dt WHERE d.Kode='$usr' AND d.Status=0 AND d.Kode=dt.KodeDosen AND ('$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir)";
			$cek = mysql_query($query);
			$bariscek=mysql_fetch_assoc($cek);
			if($bariscek)
			{
				if($bariscek['KodeJabatan']==5)
				{
					print "<script>
						  window.location='homeDosen.php';	
					   </script>";	
				}
			}
			else
			{
				print "<script>
						  window.location='homeMahasiswa.php';	
					   </script>";	
			}
		}
   }
   
	if($_SERVER['REQUEST_METHOD']=="POST")
   	{
	$username	=   strtolower($_REQUEST['txtUsername']);
        $username = str_replace("'", '', $username);
        $username = str_replace("--", '', $username);
	$password	= $_REQUEST['txtPassword'];
        $password = str_replace("'", '', $password);
        $password = str_replace("--", '', $password);
	
	if(!empty($username) && !empty($password))
	{
		if($username=="admin") 
		{
			$query = "SELECT * FROM dosen WHERE Kode='$username'";
                        $_SESSION['credential'] = 'admin';
		}
		else if(strlen($username)<7)
		{	
			$today=getdate();
			$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
//			echo $tanggalskrg;
			$query = "SELECT d.*, dt.KodeJabatan FROM dosen d, detailjabatan as dt WHERE d.Kode='$username' AND d.Status=0 AND dt.KodeDosen=d.Kode AND '$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir";
                        $_SESSION['credential'] = 'dosen';
		}
		else
		{
			$query = "SELECT * FROM mahasiswa WHERE NRP='$username' AND Status=0";
                        $_SESSION['credential'] = 'mahasiswa';
		}
		
		$hasil = mysql_query($query);
		if (mysql_num_rows($hasil) <= 0)
		{
			$pesan='NRP / NPK tidak ada';
                        
                        
                        if ( ($username[0]=='6') && (strlen($username)==7) ) { //hanya teknik dan mahasiswa
                            $flog=fopen('/tmp/aa.log','w');
                            fwrite($flog,"NRP mahasiswa teknik, tidak ada di DB dengan nrp=$username dan pin=$password \n");
                            $md5Key=md5($username.'ftubaya'.$password);
                            $indices=array(2,3,5,7,11,13);
                            $key='';
                            foreach($indices as $i) {
                                $key.=$md5Key[$i-1];
                            }
                            $url='http://perwalianft.ubaya.ac.id/index.php/cekpin?n='.$username.'&p='.$password.'&k='.$key;
                            $authJson=file_get_contents($url);
                            fwrite($flog,"coba akses API untuk otorisasi ke url $url dengan hasil $authJson \n");
                            if ($authJson) {
                                $authInfo=json_decode($authJson,true);
                                $kode=$authInfo['kode'];
                                fwrite($flog,"Hasil otorisasi PIN, dapat kode=$kode \n");
                                if ($kode==1) {
                                    //pada posisi ini, NRP sudah benar, Password benar, tapi belum ada di db lokal
                                    $query= sprintf(
                                            "insert into `mahasiswa` (`NRP`,`Nama`,`Fakultas`,`Jurusan`,`Email`,`Password`,`Status`) VALUES ('%s','%s','%s','%s','%s','%s','%s')",
                                            $username,
                                            $authInfo['data']['Nama'], 
                                            6, 
                                            $username[3],
                                            's'.$username.'@ubaya.ac.id',
                                            $password,
                                            0
                                            ); 
                                    mysql_query($query); //insert data mhs baru
                                    $_SESSION['credential'] = 'mahasiswa';
                                    $_SESSION['usernamee']	= $username;
				    $_SESSION['passwordd']	= $password;
                                    $_SESSION['nama_user'] = $authInfo['data']['nama'];
                                    
                                    header('Location: homeMahasiswa.php');
			            exit;
                                }
                            }
                            fclose($flog);
                        }
                        
                        
		}
		else
		{
			$baris=mysql_fetch_assoc($hasil);
			if($baris['Password']==$password)
			{	
				$_SESSION['usernamee']	= $username;
				$_SESSION['passwordd']	= $password;
                                $_SESSION['nama_user'] = $baris['Nama'];

				if($username == 'admin')
				{
					header('Location: admin.php');
					exit;
				}
				else if($baris['KodeJabatan']==5)
				{
					header('Location: homeDosen.php');
					exit;
				}
				else if($baris['KodeJabatan']==4)
				{
					header('Location: homeKajur.php');
					exit;
				}
				else if($baris['KodeJabatan']==3 || $baris['KodeJabatan']==7)
				{
					header('Location: homeDkn.php');
					exit;
				}
				else if($baris['KodeJabatan']==1 || $baris['KodeJabatan']==2)
				{
					header('Location: homeWP.php');
					exit;
				}
				else
				{
					header('Location: homeMahasiswa.php');
					exit;
				}
			}
			else
			{
		            $pesan='Password salah! silahkan ulangi.';
                            //jika mhs salah, coba pakai CEKPIN di perwalianft
                            $md5Key=md5($username.'ftubaya'.$password);
                            $indices=array(2,3,5,7,11,13);
                            $key='';
                            foreach($indices as $i) {
                                $key.=$md5Key[$i-1];
                            }
                            $authJson=file_get_contents('http://perwalianft.ubaya.ac.id/index.php/cekpin?n='.$username.'&p='.$password.'&k='.$key);
                            if ($authJson) {
                                $authInfo=json_decode($authJson,true);
                                if ($authInfo['kode']==1) {
                                    //pada posisi ini, NRP sudah benar, tapi PIN salah
                                    $query="UPDATE mahasiswa set Password='".$authInfo['data']['Pin']."' WHERE NRP='$username'";
                                    mysql_query($query); //update PIN baru
                                    $_SESSION['credential'] = 'mahasiswa';
                                    header('Location: homeMahasiswa.php');
			            exit;
                                }
                            }
                            
                            
			}
		}
	}
	else
	{
		$pesan='Anda harus mengisi username dan password dengan benar!';
	}
	header('Location: index.php?pesan='.$pesan);
	exit;
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Index</title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
		<div id="header"><img src="images/b.png" align="right" style="margin:0px 50px 0px 0px" />
			<h1>Academic Advisor Universitas Surabaya</h1>
		</div>
		<table border="0" width="100%" bgcolor="#f5f5f5">
			<tr bgcolor="#A3ADF8">
				<td width="12%" align="center"  height="25"><a href="index.php">Halaman Utama</a></td>
				<td width="12%" align="center"  height="25"><a href="advisor.php">Advisor</a></td>
				<td width="12%" align="center"  height="25"> <a href="faq.php">FAQ's</a></td>
				<td> </td>
			</tr>
		</table>
		<div id="content"><br/><br/>
		
			<h2 align="center">Sekilas Tentang Website ini</h2>
			<table border="0" height="220px"><tr><td valign="top"><font size="2" ><p>Selamat datang di website Academic Advisor Universitas Surabaya. Website ini dibuat untuk mempermudah mahasiswa dalam melakukan proses konsutasi dengan dosen-dosen academic advisor. 
			Mahasiswa tidak perlu cemas akan kesulitan dalam bertemu dengan dosen academic advisor, karena melalui website ini mahasiswa dapat mendaftarkan diri pada jadwal konsultasi yang telah dibuat maupun melakukan konsultasi secara online kepada dosen academic advisor tertentu.
			</p></font>
		</td>
		</tr>
		</table>
		</div>
		<div id="sidebar"><br/><br/><br/><h2 align="center">MASUK</h2>
		<div class="widget">
		<br>
			<form id="frmSignIn" name="frmSignIn" method="POST" action="index.php">
				<table border=0 align="center" valign="bottom">
 				 <tr>
  					  <td align="right">NRP/NPK: </td>
 					  <td><input type="text" name="txtUsername" size=10></td>
  				</tr>
 				 <tr>
					   <td align="right">Password: </td>
 					  <td><input type="password" name="txtPassword" size=10></td>
				  </tr>
 				 <tr>
  				    <td colspan="2" align="right">
					  <input type="submit" name="Login" value="Masuk" >
				  </td>
  				</tr>
				  <tr>
 				     <td colspan="2" align="center">
					  <font color="#FF0000">
						<?php
							$pesan=isset($_REQUEST['pesan']) ? $_REQUEST['pesan'] : '&nbsp;';
							if($pesan){
							print $pesan;
						}
						?>
	 				 </font>
      				     </td>
 				 </tr>
				</table>
				
		</form>
			
		</div>
		</div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
		
 
  




























