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
		$periksa=mysql_query("SELECT * FROM hasilkonsultasi WHERE KategoriMasalah='$kode'");
		if(@mysql_num_rows($periksa)>0)
		{
				print "<script>
						alert('Maaf anda tidak dapat menghapus kategori tersebut!');						
					   </script>";	
		}
		else
		{
			$hasil=mysql_query("DELETE FROM kategori WHERE Kode='$kode'");
			if($hasil)
			{	print "<script>
					alert('Kategori telah dihapus!');	
					</script>";
			}
			else
			{
					print "<script>
					alert('Kategori gagal dihapus!');	
					</script>";
			}
		
		}
		print "<script>
						window.location='kategori.php';
					   </script>";
	}
?>