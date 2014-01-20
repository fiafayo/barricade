<?
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nopengadaan=$_REQUEST['nopengadaan'];
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
		$str="SELECT * FROM pendaftaran WHERE NoPengadaan='$nopengadaan'";
		$baris=mysql_query($str);
		if(mysql_num_rows($baris)>0)
		{
				print "<script>
				alert('Anda tidak dapat menghapus pengadaan ini, karena sudah ada mahasiswa yang pernah mendaftar!');	
				</script>";
		}
		else
		{
			$hasil=mysql_query("DELETE FROM pengadaan WHERE NoPengadaan='$nopengadaan'");
			if($hasil)
			{	print "<script>
					alert('Pengadaan dengan nomor: $nopengadaan telah dihapus!');	
					</script>";
			}
			else
			{
					print "<script>
					alert('Pengadaan dengan nomor: $nopengadaan gagal dihapus!');	
					</script>";
			}
		}
		print "<script>
				window.location='pengadaanKonsul.php';
			   </script>";
	}
?>