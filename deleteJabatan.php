<?
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$kodos=$_REQUEST['kodos'];
	$thnawl=$_REQUEST['thnawl'];
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
		$hasil=mysql_query("DELETE FROM detailjabatan WHERE KodeDosen='$kodos' AND TanggalAwal='$thnawl'");
		if($hasil)
		{	print "<script>
				alert('Jabatan telah dihapus!');	
				</script>";
		}
		else
		{
				print "<script>
				alert('Jabatan gagal dihapus!');	
				</script>";
		}
		print "<script>
				window.location='setJabatan.php?kode=$kodos';
			   </script>";
	}
?>