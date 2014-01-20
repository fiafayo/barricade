<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	$tanggal=$_GET[tgl];
	$a=strtotime($tanggal);
	$hari=date("l",$a);
	if($hari=="Sunday")
	{
		$hari="Minggu";
	}
	else if($hari=="Monday")
	{
		$hari="Senin";
	}
	else if($hari=="Tuesday")
	{
		$hari="Selasa";
	}
	else if($hari=="Wednesday")
	{
		$hari="Rabu";
	}
	else if($hari=="Thursday")
	{
		$hari="Kamis";
	}
	else if($hari=="Friday")
	{
		$hari="Jumat";
	}
	else if($hari=="Saturday")
	{
		$hari="Sabtu";
	}
	$today = getdate();
	//$today=date("Y-m-d");
	$mon=str_pad($today['mon'], 2, "0",STR_PAD_LEFT);
	$day=str_pad($today['mday'], 2, "0",STR_PAD_LEFT);
	$tglskrg=$today['year']."-".$mon."-".$day;
	//echo $today;
	if($tanggal>=$tglskrg)
	{		
		$query=mysql_query("SELECT DISTINCT  Hari, JamMulai, JamSelesai FROM pengadaan WHERE Hari='$hari' AND Kode='$username' ORDER BY NoPengadaan DESC, Tanggal DESC Limit 5");
		$baris=mysql_fetch_assoc($query);
		if($baris)
		{
			echo"<select id='cboJam' name='cboJam' disabled>";
			while($baris)
			{	//onclick=\"getData('aturJam.php?nopengadaan='+document.getElementById('cboJam').value,'targetDiv')\"
				echo"<option value='".$baris['JamMulai']."-".$baris['JamSelesai']."' >".$baris['Hari'].", ".$baris['JamMulai']." - ".$baris['JamSelesai']."</option>";
	
				$baris=mysql_fetch_assoc($query);
			}
			echo"</select>";
		}
		else
		{
			echo"<select id='cboJam' name='cboJam' disabled>
			<option value=''>-</option>
			</select>";
		}
	}
	else
	{
	echo"<select id='cboJam' name='cboJam' disabled>
			<option value=''>-</option>
			</select>";
	}
	
?>