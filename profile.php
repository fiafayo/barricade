<?php
	session_start();
	include("config.php");
	$username   = $_SESSION['usernamee'];
	$password   = $_SESSION['passwordd'];
	$credential = $_SESSION['credential'];
	
	
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
                exit;
	}
        switch ($credential) {
            case 'dosen' :
            case 'admin' :
                $sql="SELECT * from dosen where Kode='$username'";
                break;
            default :
                $sql="SELECT * from mahasiswa where NRP='$username'";
                break;
        }
        $hasil=mysql_query($sql);
        $row=mysql_fetch_assoc($hasil);
         
        if (!$row) {
		print "<script>
				alert('Maaf username $credential=$username n=$n yang anda masukkan tidak valid!');
			        window.location='index.php';
		</script>";
                exit;
        }
	if ($_SERVER['REQUEST_METHOD']=="POST")
	{
            $noTelp=$_POST['NoTelp'];
            $noTelp = str_replace("'", '', $noTelp);
            $noTelp = str_replace("--", '', $noTelp);
            $emailAddr=strtolower($_POST['Email']);
            $emailAddr = str_replace("'", '', $emailAddr);
            $emailAddr = str_replace("--", '', $emailAddr);
            switch ($credential) {
                case 'dosen' :
                    $sql="UPDATE dosen SET NoTelp='$noTelp', Email='$emailAddr' where Kode='$username'";
                    break;
                default :
                    $sql="UPDATE dosen SET NoTelp='$noTelp', Email='$emailAddr' where Kode='$username'";
                    break;
            }
            mysql_query($sql);
            switch ($credential) {
                case 'dosen' :
                    print "<script>
                                    alert('Profile sudah berhasil diupdate!');
                                    window.location='homeDosen.php';
                    </script>";
                    exit;
                default :
                    print "<script>
                                    alert('Password baru sudah diberlakukan!');
                                    window.location='homeMahasiswa.php';
                    </script>";
                    exit;
            }
        }

        

	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title> Academic Advisor | Mahasiswa :: Ganti Password</title>
	<link rel="stylesheet" href="style.css" media="screen" />
        <script type="text/javascript">
function submitMe(frm) {
    var p0=document.getElementById('Email');
    var p1=document.getElementById('NoTelp');
     
    var v0=p0.value;
    var v1=p1.value;
     
    if (!v0) {
        alert('Email  harus diisi!');
        return false;
    }
    if (!v1) {
        alert('Nomor Telepon/HP harus diisi!');
        return false;
    }
     
    frm.submit();
}
        </script>
</head>
<body>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <br/><div style='font-size:20px' align='center'>GANTI PROFILE</div>
            <form action="profile.php" method="post">
		<table border="0" align="center" width="100%" ><br/>
			
			  <tr>
			  	<td align="right">
					<strong>Username :</strong>
				</td>
				<td>
					<?php echo $username;?>
				</td>
			  </tr>
			  <tr>
			  	<td align="right">
					<strong>Nama :</strong>
				</td>
				<td>
					<?php echo $row['Nama'];?>
				</td>
			  </tr>
			  <tr>
			  	<td align="right">
					<strong>Alamat Email :</strong>
				</td>
				<td>
                                    <input type="text" name="Email"  id="Email" value="<?php echo isset( $row['Email'] ) ? $row['Email'] : ''; ?>" />
				</td>
			  </tr>
			  <tr>
			  	<td align="right">
					<strong>Nomor Telepon/HP :</strong>
				</td>
				<td>
                                    <input type="text" name="NoTelp"  id="NoTelp" value="<?php echo isset( $row['NoTelp'] ) ? $row['NoTelp'] : ''; ?>"/>
				</td>
			  </tr>
			   
			  <tr>
			  	<td align="right">
					&nbsp;
				</td>
				<td>
                                    <input type="button" name="commit" value="Simpan Perubahan" onclick="submitMe(this.form)"/>
				</td>
			  </tr>
			</table>
                
                </form>
		</div><!-- end content -->
		
  <div id="sidebar">
   <ul>
		 <li><h2>MENU</h2></li>
<?php
if ($credential=='mahasiswa') {
?>
		<li><a href="homeMahasiswa.php">Halaman Utama</a></li>
<?php
} else  {
?>
		<li><a href="homeDosen.php">Halaman Utama</a></li>
<?php
}
?>

   </ul>
 </div>
		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>
