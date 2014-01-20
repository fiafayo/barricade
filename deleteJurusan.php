<?
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$kodefak=$_REQUEST['kodefak'];
	$kodejur=$_REQUEST['kodejur'];
	
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
		$periksadsn=mysql_query("SELECT * FROM dosen WHERE Fakultas='$kodefak' AND Jurusan='$kodejur'");
		$periksamhs=mysql_query("SELECT * FROM dosen WHERE Fakultas='$kodefak' AND Jurusan='$kodejur'");
		if(@mysql_num_rows($periksadsn)>0 || @mysql_num_rows($periksamhs)>0)
		{
				print "<script>
						alert('Maaf anda tidak dapat menghapus jurusan tersebut!');						
					   </script>";	
		}
		
		else
		{
			$hasil=mysql_query("DELETE FROM jurusan WHERE Kode='$kodejur' AND KodeFakultas='$kodefak'");
			if($hasil)
			{	print "<script>
					alert('Jurusan telah dihapus!');	
					</script>";
			}
			else
			{
					print "<script>
					alert('Jurusan gagal dihapus!');	
					</script>";
			}
		
		}
		print "<script>
						window.location='jurusan.php';
					   </script>";
	}
?>