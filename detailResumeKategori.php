<?php
	session_start();
	include("config.php");
	include("library/jpgraph.php");
	include("library/jpgraph_pie.php");
	include("library/jpgraph_pie3d.php");

	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	
	$fakultas=$_REQUEST['fakultas'];
	$jurusan=$_REQUEST['jurusan'];
	$semester=$_REQUEST['semester'];
	$klik=false;
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	$data=array();
	$namaLegend=array();
	
		
		
		$str1="SELECT  h.KategoriMasalah, k.Nama, COUNT(h.KategoriMasalah) as Jumlah
FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, kategori as k, dosen as o
WHERE h.NoPendaftaran LIKE '%$semester%' AND p.Kode=o.Kode AND o.Fakultas LIKE '%$fakultas%' AND o.Jurusan LIKE '%$jurusan%' AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran=h.NoPendaftaran AND h.KategoriMasalah=k.Kode GROUP BY h.KategoriMasalah";
		$query=mysql_query($str1);
		
		if(mysql_num_rows($query)>0)
		{
			while($rows=mysql_fetch_assoc($query))
			{
				array_push($data,$rows['Jumlah']);
				array_push($namaLegend,$rows['Nama']);
			}
			$graph = new PieGraph(1000,500,"auto");
			$graph->SetFrame(false);
			$graph->SetShadow();
			$s=substr($semester,0,1);
			if($semester=="%")
			{
				$periode="(SEMUA PERIODE)";
			}
			else if($semester<>"%" && $s=="A")
			{
				$th1=substr($semester,1,2);
				$th2=substr($semester,3,2);
				$periode="(GANJIL $th1 - $th2)";
			}
			else if($semester<>"%" && $s=="E")
			{
				$th1=substr($semester,1,2);
				$th2=substr($semester,3,2);
				$periode="(GENAP $th1 - $th2)";
			}
			
			if($fakultas=="%")
			{
				$judul=strtoupper("UNTUK SEMUA FAKULTAS DAN JURUSAN \n");
			}
			else if($fakultas<>"%")
			{
				if($jurusan=="%")
				{
				$fj=mysql_query("SELECT Nama FROM fakultas WHERE Kode='$fakultas'");
					$brsfj=mysql_fetch_assoc($fj);
					$namafak=$brsfj['Nama'];
					$judul=strtoupper("UNTUK FAKULTAS $namafak\n");
				}
				else
				{
					$fj=mysql_query("SELECT f.Nama as NamaFakultas, j.Nama as NamaJurusan FROM fakultas as f, jurusan as j WHERE f.Kode='$fakultas' AND j.Kode='$jurusan' AND f.Kode=j.KodeFakultas");
					$brsfj=mysql_fetch_assoc($fj);
					$f=$brsfj['NamaFakultas'];
					$j=$brsfj['NamaJurusan'];
					$judul=strtoupper("UNTUK FAKULTAS $f \n JURUSAN $j");
					
				}
			}
			$graph->title->Set("LAPORAN KATEGORI MASALAH $judul $periode");
			$graph->title->SetFont(FF_ARIAL,FS_BOLD,20);
			
			$p1 = new PiePlot3D($data);
			$p1->SetLegends($namaLegend);
			$p1->SetCenter(0.50);
			$p1->ExplodeAll(10);

			
			$graph->Add($p1);
			$graph->Stroke();
		}
		else
		{
			echo "data tidak ada";
		}			
?>

					