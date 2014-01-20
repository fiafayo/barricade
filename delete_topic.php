<?
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$id=$_GET['id'];
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
		$usr=$_SESSION['usernamee'];
		$dsn=mysql_query("SELECT * FROM dosen WHERE Kode='$usr'");
		if(mysql_num_rows($dsn)>0)
		{
			if($usr<>"admin")
			{
				print "<script>
						  window.location='forum.php';	
					   </script>";	
			}
			else
			{
				$hasil=mysql_query("DELETE FROM forum_question WHERE id='$id'");
				if($hasil)
				{	print "<script>
						alert('Data telah dihapus!');	
						</script>";
				}
				else
				{
						print "<script>
						alert('Data gagal dihapus!');	
						</script>";
				}
				print "<script>
						window.location='forum.php';
					   </script>";
			}		
		}
		else
		{
			print "<script>
						  window.location='index.php';	
					   </script>";	
		}	
	}
?>