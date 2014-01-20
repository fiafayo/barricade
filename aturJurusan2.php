<?php
	include("config.php");
	$kodeFak=$_GET['kode'];
	$query=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='$kodeFak' AND KodeFakultas<>0");
	$baris=mysql_fetch_assoc($query);
	if($baris)
	{
		echo"<select id='jurusan' name='jurusan'>";
		echo"<option value='%' onclick=\"getData('aturDosen.php?kodeFak=$kodeFak&kodeJur='+document.getElementById('jurusan').value,'targetDiv2')\">[Semua Jurusan]</option>";
		while($baris)
		{
			//if($baris['Kode']==0)
			//{
			//	echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>";
			//}
			//else
			//{
				echo"<option value='".$baris['Kode']."' onclick=\"getData('aturDosen.php?kodeFak=$kodeFak&kodeJur='+document.getElementById('jurusan').value,'targetDiv2')\">".$baris['Nama']."</option>";
			//}
			$baris=mysql_fetch_assoc($query);
		}
		echo"</select>";
	}
	else
	{
		echo"<select id='jurusan' name='jurusan'>
			<option value='%' onclick=\"getData('aturDosen.php?kodeFak=$kodeFak&kodeJur='+document.getElementById('jurusan').value,'targetDiv2')\">[Semua Jurusan]</option>
			</select>";
	}
?>