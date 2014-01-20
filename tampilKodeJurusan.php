<?
	
	include("config.php");
	$kodeFakultas=$_GET[kode];
	$hasil=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas='$kodeFakultas' ORDER BY Kode DESC");
	$baris=mysql_fetch_assoc($hasil);
	if($baris)
	{
		$nextKode=$baris['Kode']+1;
	}
	else
	{
		$nextKode=0;
	}
		echo"<input type='text' name='kodeJurusan' value='$nextKode' size=2 maxlength='2'><font size='-2' color='red'><b>Massukkan 0 bila fakultas tidak mempunyai jurusan</b></font>";


?>