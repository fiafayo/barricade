<?
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$kode=$_REQUEST['kode'];
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
		$hasil=mysql_query("UPDATE dosen SET Status=1 WHERE Kode='$kode'");
		if($hasil)
		{	print "<script>
				alert('Data Karyawan: $kode telah dihapus!');	
				</script>";
		}
		else
		{
				print "<script>
				alert('Data Karyawan: $kode gagal dihapus!');	
				</script>";
		}
		print "<script>
				window.location='karyawan.php';
			   </script>";
	}
?>