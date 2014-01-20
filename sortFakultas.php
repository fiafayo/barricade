<?php
	include("config.php");
	$sortby=$_GET[sortby];
	$keyword=$_GET[keyword];

	if(empty($keyword))
	{
		$query="SELECT * FROM fakultas WHERE Kode<>0 ORDER BY $sortby";
	}
	else
	{
		$query="SELECT * FROM fakultas where Kode<>0 AND (Kode LIKE '%$keyword%' OR Nama LIKE '%$keyword%') ORDER BY $sortby"; 
	}
	
	$hasil=mysql_query($query);
	
	$itemPerPage = 5;
	$page		 = $_GET['page'];

	if (empty($page))
		{$page = 1;}
		
	$totalLaptop	= mysql_num_rows($hasil);
	$totalPage		= ceil($totalLaptop / $itemPerPage);
	
	$start		= ($page - 1) * $itemPerPage;
	$end		= $itemPerPage;
	
	$query = $query." LIMIT $start,$end";
	$hasil = mysql_query($query);
	
	if (($page - 1) < 1)
		$batasBawah = 1;
	else
		$batasBawah = $page - 1;
		
	if (($page + 1) > $totalPage)
		$batasAtas = $totalPage;
	else
		$batasAtas = $page + 1;
		
	$baris=mysql_fetch_assoc($hasil);
	if($baris)
	{
		$warna=1;
		echo "<table border='0' align='center'><caption align='center' style='font-size:20px'>Data Fakultas</caption>
		<tr>
			<th><a href='#' title='Sort' onclick=\"getData('sortFakultas.php?sortby=Kode&keyword=$keyword','sortDiv')\">Kode Fakultas</a></th> 
			<th><a href='#' title='Sort' onclick=\"getData('sortFakultas.php?sortby=Nama&keyword=$keyword','sortDiv')\">Nama Fakultas</a></th> 
			<th><a href='#' title='Sort' onclick=\"getData('sortFakultas.php?sortby=Tanggal&keyword=$keyword','sortDiv')\">Waktu Simpan</a></th> 
			<th width='40px'>Ubah</th>
		</tr>";
	   while($baris)
	   {
			if($warna%2==0)
			{	echo "
				<tr bgcolor='#E2E2E2'><td>".$baris['Kode']."</td><td>".$baris['Nama']."</td><td>".$baris['Tanggal']."</td> <td align='center'><a href='tambahUbahFakultas.php?kode=".$baris['Kode']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td></tr>";
			}
			else
			{
				echo "
				<tr bgcolor='#F0F0F0'><td>".$baris['Kode']."</td><td>".$baris['Nama']."</td><td>".$baris['Tanggal']."</td> <td align='center'><a href='tambahUbahFakultas.php?kode=".$baris['Kode']."'><img src=images/edit.png border=0 title='Ubah' alt='Ubah'></a></td></tr>";
			}
			$warna=$warna+1;
			 $baris=mysql_fetch_assoc($hasil);
		}
		//PAGING
		echo"<tr><td colspan='4' align='right'>";
		
				if ($page != 1)
				{
					$prev = $page - 1;
					print "<a href='fakultas.php'>&lt;&lt;First</a>";
					print "&nbsp;&nbsp;<a href='fakultas.php?page=$prev'>&lt;Prev<a>&nbsp;&nbsp;";
				}
				
				if ($batasBawah > 1)
					print '. . . ';
				
				for ($i = $batasBawah; $i <= $batasAtas; $i++)
				{
					if ($i == $page)
						print "<b>[$i]</b> ";
					else
					{
						print "<a href='fakultas.php?page=$i'>$i</a> ";
					}
				}
				
				if ($batasAtas < $totalPage)
					print ' . . .';
				
				if ($page != $totalPage)
				{
					$next = $page + 1;
					print "&nbsp;&nbsp;<a href='fakultas.php?page=$next'>Next&gt;</a>&nbsp;&nbsp;";
					print "<a href='fakultas.php?page=$totalPage'>Last&gt;&gt;</a>";
				}
				
			echo"</td></tr>";
			//END OF PAGING
		  echo"</table>";
	 
	}
	
?>