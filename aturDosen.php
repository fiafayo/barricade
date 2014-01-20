<?php
	include("config.php");
	$kodeJur=$_GET['kodeJur'];
	$kodeFak=$_GET['kodeFak'];
	
	//$today=getdate();
	//$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
	$query=mysql_query("SELECT d.* FROM dosen as d, detailjabatan as dt 
	WHERE d.Fakultas LIKE '%$kodeFak%' AND d.Jurusan LIKE '%$kodeJur%' AND d.Kode=dt.KodeDosen AND dt.KodeJabatan=5 AND d.Status=0");
	$baris=mysql_fetch_assoc($query);
	if($baris)// && $kodeFak<>"" && $kodeJur<>"")
	{
		echo"<select id='cboDosen' name='cboDosen'>";
		echo"<option value='%'>[Semua Dosen]</option>";
		while($baris)
		{
			//if($baris['Kode']==0)
			//{
			//	echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>";
			//}
			//else
			//{
				echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>";
			//}
			$baris=mysql_fetch_assoc($query);
		}
		echo"</select>";
	}
	else
	{
		echo"<select id='cboDosen' name='cboDosen'>
			<option value='%'>[Semua Dosen]</option>
			</select>";
	}
?>