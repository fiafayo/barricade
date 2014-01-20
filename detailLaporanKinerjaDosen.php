<?php
session_start();
include ("library/jpgraph.php");
include ("library/jpgraph_bar.php");
include("config.php");

$username = $_SESSION['usernamee'];
$password = $_SESSION['passwordd'];

$fakultas=$_REQUEST['fakultas'];
$jurusan=$_REQUEST['jurusan'];
$semester=$_REQUEST['semester'];
if(empty($username) && empty($password))
{
	print "<script>
			alert('Maaf username atau kata kunci yang anda masukkan salah!');						
		   </script>";
	print "<script>
			window.location='index.php';
		   </script>";
}
$datay=array();
$datax=array();
$query="SELECT o.Kode, COUNT(o.Kode) as Jumlah
FROM hasilkonsultasi as h, pendaftaran as d, pengadaan as p, dosen as o
WHERE h.NoPendaftaran LIKE '%$semester%' AND h.NoPendaftaran=d.NoPendaftaran AND d.NoPengadaan=p.NoPengadaan AND p.Kode=o.Kode AND o.Fakultas LIKE '%$fakultas%' AND o.Jurusan LIKE '%$jurusan%' GROUP BY o.Kode";
$hasil=mysql_query($query);
if(mysql_num_rows($hasil)>0)
{
	while($rows=mysql_fetch_assoc($hasil))
	{
		array_push($datay,$rows['Jumlah']);
		array_push($datax,$rows['Kode']);
	}
	// Create the graph. These two calls are always required
	$graph = new Graph(1000,500,"auto");    
	$graph->SetScale("textlin");
//	$graph->yaxis->scale->SetGrace(10);
	$graph->xaxis->SetTickLabels($datax);
	
	// Add a drop shadow
	$graph->SetShadow();
	
	// Adjust the margin a bit to make more room for titles
	$graph->img->SetMargin(50,50,70,50);
	
	// Create a bar pot
	$bplot = new BarPlot($datay);
	
	// Adjust fill color
	$bplot->SetShadow();
	$bplot->value->Show();
	$bplot->value->SetFont(FF_ARIAL,FS_BOLD,10);
	$bplot->value->SetFormat('%0.0f');
	$graph->Add($bplot);
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
	// Setup the titles
	$graph->title->Set("LAPORAN KINERJA DOSEN $judul $periode");
	$graph->xaxis->title->Set("Kode Dosen");
	$graph->yaxis->title->Set("Jumlah Melayani Konsultasi");
	
	
	$graph->title->SetFont(FF_ARIAL,FS_BOLD,15);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	
	// Display the graph
	$graph->Stroke();
}
else
{
	echo"Data tidak ada";
}
?>
