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
	
	//$today=getdate();
	//$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);

	/*$querycek="SELECT d.NoPendaftaran, d.JamDatang, p.Tanggal, p.Hari
			FROM pengadaan as p, pendaftaran as d
			WHERE p.Tanggal>='$tanggalskrg' AND p.Status<>'Batal' AND p.NoPengadaan=d.NoPengadaan AND d.NoPendaftaran='$nopendaftaran' 
			AND d.NRP='$username' AND d.Status=0 AND d.NoPendaftaran NOT IN (SELECT NoPendaftaran FROM hasilkonsultasi) ORDER BY p.Tanggal";*/
	/*$querycek="SELECT d.NoPendaftaran, d.JamDatang, p.Tanggal, p.Hari
			FROM pengadaan as p, pendaftaran as d
			WHERE p.Tanggal>='$tanggalskrg'  AND p.Status<>'Batal' AND p.NoPengadaan=d.NoPengadaan AND d.NRP='$username' AND d.Status=0  AND d.NoPendaftaran='$nopendaftaran' 
			";
	echo $tanggalskrg."<br/>".$querycek;
	echo $nopendaftaran."<br/>";
	echo $username."<br/>";
	$hasilcek=mysql_query($querycek);
	$num=mysql_num_rows($hasilcek);*/	
	/*print "num rowssss'e:".$num."-".$nopendaftaran;
	if($num<1)
	{
		print "ccc";
		
	}
	else
	{*/	
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$nopendaftaran=$_POST["Batal"];
		//print "$nopendaftaran";
			$qinsert="INSERT INTO batalpendaftaran (NoPendaftaran) VALUES('$nopendaftaran')";
			$hslinsert=mysql_query($qinsert);
			$qupdatestatus="UPDATE pendaftaran SET Status=1 WHERE NoPendaftaran='$nopendaftaran'";
			//echo $qupdatestatus;
			$hslinsert=mysql_query($qupdatestatus);
			
			$qnopengadaan=mysql_query("SELECT * FROM pendaftaran WHERE NoPendaftaran='$nopendaftaran'");
			$brsnopengadaan=mysql_fetch_assoc($qnopengadaan);
			$nopengadaan=$brsnopengadaan['NoPengadaan'];
			$jamdtg=$brsnopengadaan['JamDatang'];
			$jamdtgupdate=$jamdtg;
			
			$detailpengadaan=mysql_query("SELECT * FROM pengadaan WHERE NoPengadaan='$nopengadaan'");
			$brsdetailpengadaan=mysql_fetch_assoc($detailpengadaan);
			$tglpengadaan=$brsdetailpengadaan['Tanggal'];
			$jmmulaipengadaan=$brsdetailpengadaan['JamMulai'];
			$jmselesaipengadaan=$brsdetailpengadaan['JamSelesai'];
			
			$qambilbwh="SELECT * FROM pendaftaran WHERE NoPengadaan='$nopengadaan' AND JamDatang>'$jamdtg' ORDER BY JamDatang";
			$hslambilbwh=mysql_query($qambilbwh);
			if(mysql_num_rows($hslambilbwh)>0)
			{ 
				//$brsambilbwh=mysql_fetch_assoc($hslambilbwh);
				while($brsambilbwh=mysql_fetch_assoc($hslambilbwh))
				{
					$nodafbwh=$brsambilbwh['NoPendaftaran'];
					
					$nrp=$brsambilbwh['NRP'];
					$emailmhs=mysql_query("SELECT * FROM mahasiswa WHERE NRP='$nrp'");
					$brsemail=mysql_fetch_assoc($emailmhs);
					$emailto=$brsemail['Email'];
					//echo $emailto."ioio";
					$qfrom=mysql_query("SELECT * FROM mailfrom WHERE Variabel='mailfrom'");
					$brsfrom=mysql_fetch_assoc($qfrom);
					$emailfrom=$brsfrom['Isi'];
					$to      = $emailto;
					$subject = 'Perubahan jam datang';
					$message = 'Pendaftaran anda pada tanggal: '.$tglpengadaan.', yang semula perkiraan jam datangnya: '.$brsambilbwh['JamDatang']. "\r\n" .
								'Telah berubah, dengan jam datang menjadi: '.$jamdtgupdate.', dikarenakan adanya pembatalan yang dilakukan oleh mahasiswa lain yang mendaftar';
					$headers = 'From: '.$emailfrom. "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					mail($to, $subject, $message, $headers);
					//Update Jam datang
					$str="UPDATE pendaftaran SET JamDatang='$jamdtgupdate' WHERE NoPendaftaran='$nodafbwh'";
					$jamdtgupdate=$brsambilbwh['JamDatang'];
					
					$updatejam=mysql_query($str);
				}
			}
			print "<script>
					alert('Pembatalan berhasil!');						
			   		</script>";
			print "<script>
					window.location='batalDaftarKonsul.php';
				   </script>";
		//}
		//else if($adano==false)
		//{
			//print"bb";
		//	header("Location: batalDaftarKonsul.php");
		//}
	}
	
?>