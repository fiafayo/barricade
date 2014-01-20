<?php
	include("config.php");
	$nopengadaan=$_GET[nopengadaan];
	$query=mysql_query("SELECT * FROM pengadaan WHERE NoPengadaan='$nopengadaan'");
	$baris=mysql_fetch_assoc($query);
	if($baris)
	{
	$jammulai=substr($baris['JamMulai'],0,2);
	$menitmulai=substr($baris['JamMulai'],3,2);
	$jamselesai=substr($baris['JamSelesai'],0,2);
	$menitselesai=substr($baris['JamSelesai'],3,2);
	echo "<input type='text' name='jamMulai' maxlength='2' size='1' value='$jammulai' onkeypress='return isNumber(event);'> : <input type='text' name='menitMulai' maxlength='2' size='1' value='$menitmulai' onkeypress='return isNumber(event);'> -
		<input type='text' name='jamSelesai' maxlength='2' size='1' value='$jamselesai' onkeypress='return isNumber(event);'> : <input type='text' name='menitSelesai' maxlength='2' size='1' value='$menitselesai' onkeypress='return isNumber(event);'><font size='-2' color='#FF0000'><i> contoh inputan jam: 09:00 - 13:00</i></font>";
	}
	else
	{
	echo "<input type='text' name='jamMulai' maxlength='2' size='1' onkeypress='return isNumber(event);'> : <input type='text' name='menitMulai' maxlength='2' size='1' onkeypress='return isNumber(event);'> -
		<input type='text' name='jamSelesai' maxlength='2' size='1' onkeypress='return isNumber(event);'> : <input type='text' name='menitSelesai' maxlength='2' size='1' onkeypress='return isNumber(event);'><font size='-2' color='#FF0000'><i> contoh inputan jam: 09:00 - 13:00</i></font>";
	}
?>