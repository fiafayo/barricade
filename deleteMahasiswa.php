<?
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nrp=$_REQUEST['nrp'];
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
		$hasil=mysql_query("UPDATE mahasiswa SET Status=1 WHERE NRP='$nrp'");
		if($hasil)
		{	print "<script>
				alert('Data Mahasiswa: $nrp telah dihapus!');	
				</script>";
		}
		else
		{
				print "<script>
				alert('Data Mahasiswa: $nrp gagal dihapus!');	
				</script>";
		}
		print "<script>
				window.location='mahasiswa.php';
			   </script>";
	}
?>