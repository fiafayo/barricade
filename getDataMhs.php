<?php
	session_start();
	include("config.php");
	 
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	$nrp=$_REQUEST['nrp'];
	if(empty($username) && empty($password))
	{
            die("Akses tidak diijinkan");
	}
        $nrp=$_REQUEST['nrp'];
        $rs=mysql_query("select Nama from mahasiswa where NRP='$nrp'");
        $data=  mysql_fetch_assoc($rs);
        if ($data) {
            echo $data['Nama'];
        } else {
            echo 'invalid';
        }
        
?>