<?php
define('IS_DEBUG',false);
session_start();
include("config.php");
include("cekInteger.php");
include_once ("fckeditor/fckeditor.php") ;
include('DataFormatter.class.php');
$username = isset($_SESSION['usernamee']) ? $_SESSION['usernamee'] : null;
$password = isset($_SESSION['passwordd']) ? $_SESSION['passwordd'] : null;
$token=DataFormatter::generateId('KL');
if(empty($username) && empty($password))
{
        print "<script>
                        alert('Maaf sessi login anda sudah habis!');						
                   </script>";
        print "<script>
                        window.location='index.php';
                   </script>";
        exit;
}

 
if($_SERVER['REQUEST_METHOD']=="POST")
{		
    $nrp=isset($_REQUEST['nrp']) ? $_REQUEST['nrp'] : null ;
    $nama=isset($_REQUEST['nama']) ? $_REQUEST['nama'] : 'invalid' ;

    if(empty($nrp) || ($nama=='invalid'))
    {
            $_SESSION['pesan']='Maaf data Mahasiswa yang anda masukkan salah!';
            header('Location: konsultasiLangsung.php?token='.$token);
            exit;
    }    
    
    $kategori=$_REQUEST['cboKategori'];
    $permasalahan=$_REQUEST['permasalahan'];
    $saran=$_REQUEST['saran'];
    $hasilkonsul=$_REQUEST['hasilkonsul'];
    $nrp=$_REQUEST['nrp'];

    $confidential=isset($_REQUEST['confidential']) ? intval($_REQUEST['confidential']) : 0;

    if(empty($permasalahan) || empty($saran) )
    {
        $_SESSION['pesan']="isian permasalahan dan saran  tidak boleh kosong";
        header('Location: konsultasiLangsung.php?token='.$token);
        exit;
    }
    if (IS_DEBUG) {
        $flog=fopen('/tmp/aa_debug.log','a');
    }
        
    $rs=mysql_query("SELECT id FROM semester WHERE status_aktif=1");
    $sms=mysql_fetch_array($rs);
    if ($sms) {
        $semesterAktif=$sms[0];
    } else {
        $semesterAktif=DEFAULT_SEMESTER_AKTIF;
    }
    $_SESSION['semester']=$semesterAktif;       
    $tahun=intval($semesterAktif / 10);
    $semester=$semesterAktif % 10;
    $tahun=$tahun % 100;
    $kodeSemester=($semester==1) ? 'LA' : 'LE';
    $kodeTahun=$tahun.($tahun+1);
    $kodePengadaan=$kodeSemester.$kodeTahun;
    $tanggalHariIni=date('Y-m-d');
    $rs=mysql_query("SELECT NoPengadaan FROM pengadaan where NoPengadaan LIKE '$kodePengadaan%' AND Kode='$username' and Tanggal='$tanggalHariIni'");
    $pada=mysql_fetch_array($rs);
    if (!$pada) {
        $rs=mysql_query("SELECT MAX(NoPengadaan) FROM pengadaan where NoPengadaan LIKE '$kodePengadaan%'");
        $pmax=mysql_fetch_array($rs);
        if ($pmax) {
            $kodeMax=intval(substr($pmax[0],6))+1;
            
            
        } else {
            $kodeMax=1;
        }
        $noPengadaan=$kodePengadaan.str_pad($kodeMax,4,"0",STR_PAD_LEFT);
        $tanggal=date('Y-m-d');
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
        $rs=mysql_query("INSERT INTO pengadaan(NoPengadaan,Tanggal,Hari,JamMulai,JamSelesai,MaxMhs,Status,Kode) 
            VALUES ('$noPengadaan','$tanggal','$hari','08:00:00','17:00:00',100,'Belum Terlaksana','$username')");
        
    } else {
        $noPengadaan=$pada['NoPengadaan'];        
    }
    if (IS_DEBUG) {
        fwrite($flog, date('YmdHis').": didapatkan kodePengadaan=$noPengadaan");
        
    }

    $rs=mysql_query("SELECT MAX(NoPendaftaran) FROM pendaftaran where NoPendaftaran LIKE '$kodePengadaan%'");
    $pmax=mysql_fetch_array($rs);
    if ($pmax) {
        $kodeMax=intval(substr($pmax[0],6))+1;
    } else {
        $kodeMax=1;
    }
    $noPendaftaran=$kodePengadaan.str_pad($kodeMax,4,"0",STR_PAD_LEFT);
    $jamDatang=date("H:i:s");
    $rs=mysql_query("INSERT INTO pendaftaran(NoPendaftaran,NoPengadaan,Nrp,Status,StatusMhs,JamDatang,JamRencana) 
        VALUES ('$noPendaftaran','$noPengadaan','$nrp',0,'Sudah Dilayani','$jamDatang','00:00:00')");
    $insert=mysql_query("INSERT INTO hasilkonsultasi (KategoriMasalah, Permasalahan, HasilKonsultasi, Saran, NoPendaftaran, confidential) VALUES ('$kategori', '$permasalahan', '$hasilkonsul', '$saran', '$noPendaftaran', $confidential)");
    $rs=mysql_query("UPDATE tk_mhs SET konsultasi=1, aa=1 where nrp='$nrp'",$connFT);
    $pesanErr = mysql_error($connFT);
    if ( IS_DEBUG ) {
        fwrite($flog,date('YmdHis'). ": disimpan sebagai hasilkonsultasi dengan no pendaftaran $noPendaftaran \n");
        fclose($flog);
    }

    $_SESSION['pesan']="Hasil pengadaan konsultasi sudah berhasil disimpan err=$pesanErr";
    header("Location:ubahHasilKonsul.php?token=".$token);
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Dosen :: Konsultasi Langsung</title>
	<link rel="stylesheet" href="style.css" media="screen" />	
<!-- <script src="prototype/js/prototype.js" type="text/javascript"></script> -->
<script src="jquery.js" type="text/javascript"></script>
        
    <script type="text/javascript">
 function FCKCopy() {
      for (var i = 0; i < parent.frames.length; ++i ) {
          if ( parent.frames[i].FCK )
         parent.frames[i].FCK.UpdateLinkedField();
           }
       }        
function getNamaMhs() {
                        
                    $('#nama').val('');
                    var nrp=$('#nrp').val();
                        $.ajax({
                            url: 'getDataMhs.php?nrp='+nrp,
                            type: 'GET',
                            dataType: 'text',
                             
                            success: function(data, textStatus, xhr) {
 
                                $('#nama').val(data);
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                alert("Ambil data mahasiswa gagal, "+textStatus);
                            }
                        });
                    
}        
// 
//function getDataMhs(fieldNrp) {
//    
//  var nrp=fieldNrp.value;
//   
//  var url='getDataMhs.php?nrp='+nrp;
//  var aj = new Ajax.Request(  
//  url, {  
//   method:'get',   
//   onComplete: function(oReq) {
//       $('nama').value = oReq.responseText;  
//   }  
//   }  
//  );
//    
//}        

function trim(str) {
  return str.replace(/^\s+|\s+$/g, '') ;
}
function validasiSubmit() {
    FCKCopy();
  var nrp = $.trim( $('#nrp').val() ); 
  var permasalahan = $.trim( $('#permasalahan').val() ); 
   
  var saran = $.trim( $('#saran').val() ); 
  var hasilkonsul = $.trim( $('#hasilkonsul').val() ); 
   
      
      if (nrp==='') {
         alert("Isian NRP Mahasiswa tidak boleh kosong!");
         return false;
      }
      if (permasalahan==='') {
         alert("Isian Permasalahan Mahasiswa tidak boleh kosong!");
         return false;
      }
      if (hasilkonsul==='') {
         alert("Isian Hasil Konsultasi Mahasiswa tidak boleh kosong!");
         return false;
      }
      if (saran==='') {
         alert("Isian Saran Untuk Mahasiswa tidak boleh kosong!");
         return false;
      }
      return true;
      
  
}
    </script>
</head>
<body>
<?
if($kesalahan=="data kosong")
{
	print "<script>
		alert('Data harap diisi!');	
		</script>";
	
}
?>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" cellspacing="0">
			  <tr>
			  <td colspan="5">
			  <form action="konsultasiLangsung.php" method="post" onsubmit="return validasiSubmit()">
			 <br/><div style="font-size:20px" align="center">Hasil Konsultasi Langsung</div><br/><br/>
			  	<table border="0" cellpadding="4" cellspacing="0" align="center" width="100%"> 
				<tr>
					<td align="right"><strong>NRP</strong></td>
					<td><input type="text" name="nrp" id="nrp" value="" onblur="getNamaMhs()" /></td>
				</tr>
				<tr>
					<td align="right"><strong>Nama</strong></td>
					<td><input type="text" name="nama" id="nama" value="" size="80" readonly="true" />
							
					</td>
				</tr>
				 
				
				<tr>
					<td align="right"><div id="tampilDiv5"><strong>Kategori Masalah</strong></div></td>
					<td><div id="tampilDiv1">
						<? 	$query="SELECT * FROM kategori";
							$masalah=mysql_query($query);
							$baris=mysql_fetch_assoc($masalah);
							echo "<select id='cboKategori' name='cboKategori'>";
							while($baris)
							{
								if($kategori==$baris['Kode'])
								{
									echo"<option value='".$baris['Kode']."' selected>".$baris['Nama']."</option>";
								}
								else
								{
									echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>";
								}
								
								$baris=mysql_fetch_assoc($masalah);
							}
							echo"</select>";
	
						?></div>
					</td>
				</tr>
                                    <tr>
                                        <td align="right"><strong>Confidential</strong></td>
                                        <td>
                                            <input type="radio" name="confidential" value="1" <?php if ($confidential) echo 'checked="true"';?> />Ya &nbsp;
                                            <input type="radio" name="confidential" value="0" <?php if (!$confidential) echo 'checked="true"';?>  />Tidak
                                        </td>
                                    </tr>
				<tr>
					<td align="right" valign="top"><div id="tampilDiv6"><strong>Permasalahan</strong></div></td>
					<td><div id="tampilDiv2"><?
							$oFCKeditor = new FCKeditor('permasalahan') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							if(!empty($kesalahan))
								{$oFCKeditor->Value = $permasalahan ;}
							else
								{$oFCKeditor->Value = '' ;}
							$oFCKeditor->Create() ;
					?></div></td>
				</tr>
				<tr>
					<td align="right" valign="top"><div id="tampilDiv7"><strong>Saran</strong></div></td>
					<td><div id="tampilDiv3"><?
							$oFCKeditor = new FCKeditor('saran') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							if(!empty($kesalahan))
								{$oFCKeditor->Value = $saran ;}
							else
								{$oFCKeditor->Value = '' ;}
							$oFCKeditor->Create() ;
					?></div></td>
				</tr>
				<tr>
					<td align="right" valign="top"><div id="tampilDiv8"><strong>Hasil Konsultasi</strong></div></td>
					<td><div id="tampilDiv4"><?
							$oFCKeditor = new FCKeditor('hasilkonsul') ;//kluarin fck
							$oFCKeditor->BasePath = 'fckeditor/' ;
							if(!empty($kesalahan))
								{$oFCKeditor->Value = $hasilkonsul ;}
							else
								{$oFCKeditor->Value = '' ;}
							$oFCKeditor->Create() ;
					?></div></td>
				</tr>
				
				<tr>
					<td align="center" colspan="2"><br/><input type="submit" value="Simpan" name="submit" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Batal" name="batal" onclick="document.location='index.php'" /></td>
				</tr>
				
				</table>
				</form> 
				</td>
			  </tr>
			  </table>
		</div><!-- end content -->
		
		<div id="sidebar">
 <?php include_once('menuDosen.php'); ?>

 </div> 

   		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>

