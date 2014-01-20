<?php
	include("config.php");
	$kodeFak=$_GET[kode];
	$query=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='$kodeFak'");
	$baris=mysql_fetch_assoc($query);
	if($baris)
	{
		echo"<select id='cboJurusan' name='cboJurusan'>";
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
		/*echo"<select id='cboJurusan' name='cboJurusan'>
			<option value='-'>-</option>
			</select>";*/
	}
?>