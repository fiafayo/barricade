<?php
	include("config.php");
	$kode=$_GET['kode'];
	$j=$_GET['j'];
	
	$today=getdate();
	$qnmdsn=mysql_query("SELECT * FROM dosen WHERE Kode='$kode'");
	$brsdsn=mysql_fetch_assoc($qnmdsn);
	if($j==0)
	{
	echo"<a href='#$kode' onclick=\"getData('lihatJadwal.php?kode=$kode&j=1','$kode')\">".$brsdsn['Nama']."</a>";

		$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
		$h=date("H")-1;
		$jamskrg=$h.":".date("i:s");
		$query=mysql_query("SELECT p.NoPengadaan, p.Tanggal as Tanggal, p.Hari, p.JamMulai, p.JamSelesai, p.MaxMhs, p.Status 
			FROM pengadaan as p
			WHERE p.Kode='$kode' AND (p.Tanggal>'$tanggalskrg' OR (p.Tanggal='$tanggalskrg' AND p.JamSelesai>'$jamskrg')) ORDER BY p.Tanggal");
			$warna=1;
			echo"<table>
				<th>Tanggal</th> <th>Hari</th> <th>Jam Mulai </th> <th>Jam Selesai</th> <th>Max Mhs</th> <th>Status</th>";
		if(mysql_num_rows($query)>0)
		{
		$warna=1;
			while($baris=mysql_fetch_assoc($query))
			{
			
				if($warna%2==0)
				{
					if($baris['Status']=='Batal')
					{
						$nopengadaanygbatal=$baris['NoPengadaan'];
						$qalasan=mysql_query("SELECT * FROM pembatalan WHERE NoPengadaan='$nopengadaanygbatal'");
						$brsalasan=mysql_fetch_assoc($qalasan);
						$alasan=$brsalasan['Alasan'];
						echo"<tr bgcolor='#E2E2E2'><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['Status']." (Alasan: $alasan)"."</td></tr>";
					}
					else
					{
						echo"<tr bgcolor='#E2E2E2'><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['Status']."</td></tr>";
					}
				}
				else
				{
					if($baris['Status']=='Batal')
					{
						$nopengadaanygbatal=$baris['NoPengadaan'];
						$qalasan=mysql_query("SELECT * FROM pembatalan WHERE NoPengadaan='$nopengadaanygbatal'");
						$brsalasan=mysql_fetch_assoc($qalasan);
						$alasan=$brsalasan['Alasan'];
						echo"<tr bgcolor='#F0F0F0'><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['Status']." (Alasan: $alasan)"."</td></tr>";
					}
					else
					{
						echo"<tr bgcolor='#F0F0F0'><td>".$baris['Tanggal']."</td><td>".$baris['Hari']."</td><td>".$baris['JamMulai']."</td><td>".$baris['JamSelesai']."</td><td>".$baris['MaxMhs']."</td><td>".$baris['Status']."</td></tr>";
					}
				}
				$warna=$warna+1;
			}
		}
		else
		{
		echo"<tr bgcolor='#F0F0F0'><td align='center'>-</td>
		<td align='center'>-</td>
		<td align='center'>-</td>
		<td align='center'>-</td>
		<td align='center'>-</td>
		<td align='center'>-</td></tr>";
		}
			echo"</table>";
	}
	else
	{
		echo"<a href='#$kode' onclick=\"getData('lihatJadwal.php?kode=$kode&j=0','$kode')\">".$brsdsn['Nama']."</a>";

	}
	
?>