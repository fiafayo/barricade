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
		$periksa=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='$kode'");
		if(@mysql_num_rows($periksa)>0)
		{
				print "<script>
						alert('Maaf anda tidak dapat menghapus fakultas tersebut!');						
					   </script>";	
		}
		else
		{
			$hasil=mysql_query("DELETE FROM fakultas WHERE Kode='$kode'");
			if($hasil)
			{	print "<script>
					alert('Fakultas telah dihapus!');	
					</script>";
			}
			else
			{
					print "<script>
					alert('Fakultas gagal dihapus!');	
					</script>";
			}
		
		}
		print "<script>
						window.location='fakultas.php';
					   </script>";
	}
?>