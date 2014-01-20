<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	/*if($_SESSION['usernamee'] && $_SESSION['passwordd'])
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
			$query = "SELECT * FROM dosen WHERE Kode='$username' AND Status=0 AND '$tanggalskrg'>=d.TanggalAwal AND '$tanggalskrg'<=d.TanggalAkhir";
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
   }*/
   
	if($_SERVER['REQUEST_METHOD']=="POST")
   	{
	$username	= strtolower($_REQUEST['txtUsername']);
	$password	= $_REQUEST['txtPassword'];

	if(!empty($username) && !empty($password))
	{
		if($username=="admin") 
		{
			$query = "SELECT * FROM dosen WHERE Kode='$username'";
		}
		else if(strlen($username)<7)
		{	
			$today=getdate();
			$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
//			echo $tanggalskrg;
			$query = "SELECT d.*, dt.KodeJabatan FROM dosen d, detailjabatan as dt WHERE d.Kode='$username' AND d.Status=0 AND dt.KodeDosen=d.Kode AND '$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir";
		}
		else
		{
			$query = "SELECT * FROM mahasiswa WHERE NRP='$username' AND Status=0";
		}
		
		$hasil = mysql_query($query);
		if (mysql_num_rows($hasil) <= 0)
		{
			$pesan='NRP / NPK tidak ada';
		}
		else
		{
			$baris=mysql_fetch_assoc($hasil);
			if($baris["Password"]==$password)
			{	
				$_SESSION['usernamee']	= $username;
				$_SESSION['passwordd']	= $password;

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
			}
		}
	}
	else
	{
		$pesan='Anda harus mengisi username dan password dengan benar!';
	}
	header('Location: faq.php?pesan='.$pesan);
	exit;
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | FAQ</title>
	<link rel="stylesheet" href="style.css" media="screen" />	
</head>
<body>
	<div id="container">
		<div id="header"><img src="images/b.png" align="right" style="margin:0px 50px 0px 0px">
			<h1>Academic Advisor Universitas Surabaya</h1>
		</div>
		<table border="0" width="100%" bgcolor="#f5f5f5">
			<tr bgcolor="#A3ADF8">
			<? if(!$_SESSION['usernamee'] && !$_SESSION['passwordd'])
			{ ?>
			<td width="12%" align="center"  height="25"><a href="index.php">Halaman Utama</a></td>
			<? } ?>
				<td width="12%" align="center"  height="25"><a href="advisor.php">Advisor</a></td>
				<td width="12%"align="center"  height="25"> <a href="faq.php">FAQ's</a></td>
				<td> </td>
			</tr>
		</table>
		<div id="content">
		<? if(!empty($username) && !empty($password))
			{ ?>
		<div align="right">Selamat datang <?php print $username; ?>, <a href="logout.php">keluar</a></div><hr > <? } ?>
			
			<table border="0" height="220px">
			<tr><td colspan="2" bgcolor="#A3ADF8"><font size="">...:: FAQ's ::...</font></h1></td></tr>
				<tr>
					<td valign="top" width="25"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Apakah artinya bila saya menerima surat panggilan dari Academic Advisor?</font>
					</td>
				</tr>
				
				<tr>
				 
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Bila mahasiswa menerima surat panggilan dari Academic Advisor, berarti: mahasiswa tersebut kurang memiliki kinerja akademik yang memuaskan, yaitu: IPK kurang dari 2,00 dan SKS Kumulatif kurang dari 9 untuk semester I, kurang dari 18 untuk semester II, atau kurang dari 27 untuk semester III. 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size=""> 
					Apa yang harus saya lakukan, bila saya menerima surat panggilan dari Academic Advisor?
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Jika mahasiswa menerima surat panggilan dari Academic Advisor, maka mahasiswa HARUS segera menemui Academic Advisor di Fakultas masing-masing, agar memperoleh pendampingan akademik.
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Bilakah mahasiswa dinyatakan tidak dapat bergabung lagi dengan Universitas Surabaya?
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Mahasiswa dinyatakan TIDAK DAPAT BERGABUNG lagi dengan Universitas Surabaya, karena mahasiswa tersebut berstatus BERHENTI STUDI TETAP (BST) 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Bilakah status Berhenti Studi Tetap (BST) itu dialami mahasiswa?
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					BST adalah status seorang mahasiswa yang mengundurkan diri sebagai mahasiswa dari suatu program studi sebelum menyelesaikan seluruh program studinya.
			
			<br/><br/>BST ini dapat disebabkan karena, seorang mahasiswa:
			 <br/>
			1. &nbsp;Terkena Evaluasi Studi Tahap I <br/>
			    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(IPK kurang dari 2.00; SKS Kumulatif kurang dari 36, untuk 2 tahun pertama) <br/>
			2. &nbsp;Terkena Evaluasi Studi Tahap II <br/>
			    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			(IPK kurang dari 2.00; SKS Kumulatif kurang dari 78, untuk 2 tahun ke dua) <br/>
			3. &nbsp;Tidak membayar UPP selama 2 semester berturut-turut. <br/>
			4. &nbsp;Bermaksud mutasi ke program studi lain di lingkungan UBAYA <br/>
			5. &nbsp;Pindah ke perguruan tinggi lain <br/>
			6. &nbsp;Berhenti atas keinginan sendiri <br/>
			7. &nbsp;Memperoleh sangsi indisipliner, atas perilaku pribadi yang sangat tidak
			mungkin diterima di lingkungan pendidikan. 
			 </font></td></tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Saya berpeluang terkena Evaluasi Studi (Status Drop Out) , tetapi masih ingin tetap kuliah di lingkungan UBAYA, apa yang dapat saya lakukan? 
					 </font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					 Sebelum benar-benar terkena Evaluasi Studi (Status DO), misalnya pada akhir semester III, mahasiswa dapat mengajukan permohonan untuk mutasi (pindah) ke program studi lain (tertentu), setelah berkonsultasi dengan Academic Advisor. 
				 	</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Setelah terkena Evaluasi Studi (Status Drop Out) , saya masih ingin kembali kuliah di UBAYA, apa yang dapat saya lakukan? 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Mahasiswa yang telah terkena Evaluasi Studi, berstatus Berhenti Studi Tetap (BST), sehingga tidak dapat mendaftarkan diri secara langsung untuk kembali kuliah di UBAYA. Mahasiswa tersebut dapat kembali kuliah di lingkungan UBAYA setelah lulus Ujian Saringan Masuk (USM) Universitas Surabaya. 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Setelah menyusun perencanaan studi untuk suatu semester, dapatkah saya mengubahnya?  
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Perubahan rencana studi hanya dimungkinkan pada masa perencanaan studi, sesuai ketentuan dan jadwal yang ditentukan oleh Fakultas/ PPBMT.
					Setelah masa perencanaan studi berakhir, mahasiswa tidak dapat mengajukan perubahan rencana studi. 
				</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Di dalam suatu semester yang sedang berlangsung, dapatkah saya mengurungkan niat (tidak jadi) mengikuti mata kuliah tertentu? 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Tidak dapat. Mahasiswa sudah menyusun sendiri perencanaan studinya, sehingga diharapkan bertanggung jawab terhadap perencanaan studi tersebut. 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Di dalam suatu semester yang sedang berlangsung, dapatkah saya mengurungkan niat (tidak jadi) mengikuti semua mata kuliah yang telah saya pilih? 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Setelah berkonsultasi dengan Academic Advisor, mahasiswa dimungkinkan untuk mengundurkan diri dari semua kegiatan akademik dan non-akademik yang sedang berlangsung pada suatu semester, karena alasan tertentu.
					Dalam hal ini mahasiswa berstatus MUNDUR STUDI SEMENTARA (MSS). 
					Permohonan MSS dapat diajukan mulai saat terbitnya Daftar Peserta Matakuliah Tetap (DPMT), sampai hari terakhir minggu tenang (sebelum Ujian Akhir Semester/ UAS). 
					
					<br/><br/>Kondisi MSS:<br/><br/>
					 
					1. &nbsp;Masa MSS tidak diperhitungkan sebagai masa studi 2 <br/>
					2. &nbsp;MSS tidak boleh lebih dari 2 semester berturut-turut <br/>
					3. &nbsp;Total MSS yang diperbolehkan selama menjadi mahasiswa di suatu
					program studi di lingkungan UBAYA,adalah 4 semester. <br/>
					4. &nbsp;Mahasiswa yang sedang menyelesaikan skripsi/ tugas akhir tidak 
					diperbolehkan MSS. <br/>
					5. &nbsp;Mahasiswa berstatus MSS hanya membayar UPP sampai dengan bulan 
					diterimanya surat permohonan MSS. (Bila ada selisih pembayaran UPP, mahasiswa dapat mengajukan permohonan untuk pengembaliannya) 
					 </font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Apa yang dapat saya lakukan, bila untuk semester mendatang karena suatu alasan tertentu, saya tidak dapat melanjutkan studi? 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Mahasiswa dapat mengajukan permohonan untuk memperoleh status BERHENTI STUDI SEMENTARA (BSS), setelah berkonsultasi dengan Academic Advisor.
			Permohonan dapat diajukan sebelum masa perencanaan studi berakhir pada semester itu. <br/><br/>
			Kondisi BSS: <br/><br/>
			 
			1. &nbsp;Masa BSS tidak diperhitungkan sebagai masa studi <br/>
			2. &nbsp;BSS tidak boleh lebih dari 2 semester berturut-turut <br/>
			3. &nbsp;Total BSS yang diperbolehkan selama menjadi mahasiswa di suatu program studi di lingkungan UBAYA, adalah 4 semester. <br/>
			4. &nbsp;Mahasiswa yang sedang menyelesaikan skripsi/ tugas akhir tidak diperbolehkan BSS <br/>
			5. &nbsp;Mahasiswa berstatus BSS bebas UPP. (Apabila terlanjur membayar UPP, mahasiswa dapat mengajukan permohonan pengembalian UPP). <br/>
			 	 </font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Saya ingin memperbaiki Indeks Prestasi (IP) dengan mengambil kembali (mengulangi) matakuliah tertentu . Apa konsekuensi yang perlu saya pertimbangkan ? 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Mahasiswa dapat mengambil kembali (mengulangi) mata kuliah tertentu, dengan mempertimbangkan konsekuensi, sebagai berikut:<br/>
			 
			1. &nbsp;Bila nilai mata kuliah semula (sebelum diulang) adalah lebih besar atau sama dengan C (yaitu: C/BC/B/AB), maka yang diakui di dalam transkrip, adalah nilai terakhir (setelah diulang). <br/>
			2. &nbsp;Bila nilai mata kuliah semula (sebelum diulang) adalah D, maka yang diakui di dalam transkrip, adalah nilai yang lebih baik yang pernah diperoleh.
			<br/><br/>
			Misal: 
			Semula memperoleh nilai D, setelah diulang memperoleh nilai E, maka yang diakui di dalam transkrip, adalah nilai D.
			Semula memperoleh nilai D, setelah diulang memperoleh nilai lebih besar atau sama dengan C (yaitu: C/BC/B/AB/A), maka yang diakui di dalam transkrip adalah nilai terakhir (setelah di ulang). 
					 </font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Pada awal semester biasanya saya memerlukan informasi atau arahan terkait dengan strategi pengambilan mata kuliah, siapa yang dapat saya temui? 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Terkait dengan strategi pengambilan mata kuliah, pada awal semester mahasiswa dapat memperoleh informasi atau arahan dari setiap dosen dan Academic Advisor yang ada di program studi. Dapat juga menemui konselor di Pusat Layanan Konseling dan Pendampingan Akademik Mahasiswa (PLKPAM).  
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Selain sebagai mahasiswa, saya juga seorang atlit (atau seniman) yang cukup memiliki prestasi. Terkait dengan kebijakan akademik, sejauh mana UBAYA mendukung prestasi saya dalam hal ini? 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Bila mahasiswa yang memiliki prestasi di bidang olah raga atau seni, ditugaskan oleh UBAYA/ Daerah/Pusat untuk mengikuti pertandingan berskala nasional atau internasional pada saat UTS, atau UAS (dengan demikian pada suatu semester hanya memiliki salah satu nilai saja: nilai UTS saja, atau nilai UAS saja), maka nilai ujian yang diperhitungkan sebagai nilai akhir, adalah satu nilai tersebut. 
			<br/><br/>Prosedur:
			Mahasiswa memberikan surat tugas (dari pihak yang menugaskan) ke WR I u.p BAAK, untuk diterbitkan surat dispensasi dari WR I
					 </font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Saya ingin menemui Academic Advisor untuk mendiskusikan masalah akademik saya, dimana kantornya, dan bagaimana caranya? 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Mahasiswa dapat menemui Academic Advisor di ruangannya di Fakultas masing-masing, dengan terlebih dahulu membuat janji untuk bertemu melalui e-mail atau menghubungi secara langsung. Untuk itu mahasiswa dapat melihat menu Academic Advisor pada Website ini. 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					Q :</font></td>
					<td valign="top"><font size="">
					Saya ingin menemui Konselor untuk mendiskusikan masalah pribadi saya, dimana kantornya, dan bagaimana caranya? 
					</font></td>
				</tr>
				<tr>
					<td valign="top"><font size="">
					A :</font></td>
					<td valign="top"><font size="">
					Mahasiswa dapat menemui Konselor di ruangannya, di Pusat Layanan Konseling dan Pendampingan Akademik Mahasiswa (PLKPAM), Lantai 4 Perpustakaan UBAYA, secara langsung, dengan jam kerja sebagai berikut: 
			<br/>Hari Senin - Jumat: pukul 9.00 - pukul 15.00
			<br/>Hari Sabtu : pukul 9.00 - pukul 12.00
					 </font></td>
				</tr>	
		</table>
		</div>
		<div id="sidebar"><? if(empty($username) && empty($password))
		{ ?>	<br/><br/><br/><h2 align="center">MASUK</h2>
		<div class="widget">
		<br>
			<form id="frmSignIn" name="frmSignIn" method="POST" action="faq.php">
				<table border=0 align="center" valign="bottom">
 				 <tr>
  					  <td align="right">NRP/NPK: </td>
 					  <td><input type="text" name="txtUsername" size=15></td>
  				</tr>
 				 <tr>
					   <td align="right">Password: </td>
 					  <td><input type="password" name="txtPassword" size=15></td>
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
							$pesan=$_REQUEST['pesan'];
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
		<? } 
		else
		{
			
			if($username == 'admin')
			{
				echo" <ul>
				   <li><h2>MENU</h2>
					 </li>
					 <li><a href='admin.php'>Halaman Utama</a></li>
					 <li><a href='kategori.php'>Kategori Masalah</a></li>
					  <li><a href='fakultas.php'>Fakultas</a></li>
					   <li><a href='jurusan.php'>Jurusan</a></li>
					 <li><a href='karyawan.php'>Karyawan</a></li>
					 <li><a href='mahasiswa.php'>Mahasiswa</a></li>
					  <li><a href='forum.php'>Forum</a></li>
					   <li><a href='settingmail.php'>Setting E-mail</a></li>
				   </ul>";
			}
			else if(strlen($username)<7)
			{	
				$today=getdate();
				$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
				$strjabatan = "SELECT d.*, dt.KodeJabatan FROM dosen d, detailjabatan as dt WHERE d.Kode='$username' AND d.Status=0 AND dt.KodeDosen=d.Kode AND '$tanggalskrg'>=dt.TanggalAwal AND '$tanggalskrg'<=dt.TanggalAkhir";
				$qjabatan=mysql_query($strjabatan);
				$brsjabatan=mysql_fetch_assoc($qjabatan);
								
				if($brsjabatan['KodeJabatan']==5)
				{
					echo"<ul>
						 <li><h2>MENU</h2></li>
						 <li><a href='homeDosen.php'>Halaman Utama</a></li>
						  <li><a href='dsnKategori.php'>Kategori Masalah</a></li>
						  <li><a href='pengadaanKonsul.php'>Pengadaan Konsultasi</a></li>
						   <li><a href='pembatalanKonsul.php'>Pembatalan Konsultasi</a></li>
						 <li><a href='hasilKonsul.php'>Hasil Konsultasi</a></li>
						  <li><a href='ubahHasilKonsul.php'>Ubah Hasil Konsultasi</a></li>
						 <li><a href='riwayatKonsul.php'>Riwayat Konsultasi</a></li>
						 <li><a href='dsnlaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
						 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
						 <li><a href='dosenKonsulOL.php'>Konsultasi Online</a></li>
						  <li><a href='main_forum.php'>Forum</a></li>
					   </ul>";
				}
				else if($brsjabatan['KodeJabatan']==4)
				{
					echo" <ul>
					 <li><h2>MENU</h2></li>
					 <li><a href='homeKajur.php'>Halaman Utama</a></li>
					 <li><a href='kajurRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='laporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					 <li><a href='main_forum.php'>Forum</a></li>
					   </ul>";
				}
				else if($brsjabatan['KodeJabatan']==3 || $brsjabatan['KodeJabatan']==7)
				{
					echo"  <ul>
					 <li><h2>MENU</h2></li>
					 <li><a href='homeDkn.php'>Halaman Utama</a></li>
					 <li><a href='dekanRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
					 <li><a href='dekanLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
					 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
					  <li><a href='main_forum.php'>Forum</a></li>
					   </ul>";
				}
				else if($brsjabatan['KodeJabatan']==1 || $brsjabatan['KodeJabatan']==2)
				{
					echo"<ul>
				 <li><h2>MENU</h2></li>
				 <li><a href='homeWP.php'>Halaman Utama</a></li>
				 <li><a href='wpRiwayatKonsul.php'>Riwayat Konsultasi</a></li>
				 <li><a href='wpLaporanHasilKonsul.php'>Laporan Hasil Konsultasi</a></li>
				 <li><a href='resumeKategori.php'>Laporan Kategori Masalah</a></li>
				 <li><a href='laporanKinerjaDosen.php'>Laporan Kinerja Dosen</a></li>
				  <li><a href='main_forum.php'>Forum</a></li>
				   </ul>";
				}
			}
			else
			{
				echo" <ul>
				 <li><h2>MENU</h2></li>
				 <li><a href='homeMahasiswa.php'>Halaman Utama</a></li>
				  <li><a href='daftarKonsul.php'>Daftar Konsultasi</a></li>
				   <li><a href='batalDaftarKonsul.php'>Batal Daftar Konsultasi</a></li>
				<li><a href='riwayatKonsulMhs.php'>Riwayat Konsultasi</a></li>
				 <li><a href='mahasiswaKonsulOL.php'>Konsultasi Online</a></li>
				 
			   </ul>";
			}
		}
		?>
		</div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
		
 
  




























