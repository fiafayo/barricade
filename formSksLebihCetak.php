<?php
session_start();
include("config.php");
include('DataFormatter.class.php');
$username = isset($_SESSION['usernamee']) ? $_SESSION['usernamee'] : null;
$password = isset($_SESSION['passwordd']) ? $_SESSION['passwordd'] : null;
$nrp = isset($_SESSION['nrp_cetak']) ? $_SESSION['nrp_cetak'] : null;
$token=DataFormatter::generateId('AA');
$semesterAktif = isset($_SESSION['semester']) ? $_SESSION['semester'] : DEFAULT_SEMESTER_AKTIF;
$namaUser = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : null;
$pesan = isset($_SESSION['pesan']) ? $_SESSION['pesan'] : null;

if(empty($username) && empty($password))
{
        print "<script>
                        alert('Maaf sesi login anda sudah habis!');						
                   </script>";
        print "<script>
                        window.location='index.php';
                   </script>";
        exit;
}
if(empty($nrp))
{
        print "<script>
                        alert('Maaf NRP mahasiswa tidak valid!');						
                   </script>";
        print "<script>
                        window.location='index.php';
                   </script>";
        exit;
}
$rs=mysql_query("SELECT nrp,sks,alasan,npk FROM tambah_sks WHERE nrp=$nrp and tahun_semester=$semesterAktif");
$tsks=mysql_fetch_assoc($rs);

$rs=mysql_query("SELECT Nama,Jurusan FROM mahasiswa WHERE NRP='$nrp'") ;
$mhs=mysql_fetch_assoc($rs);

function terbilang($angka) {
    $index=intval($angka)%10;
    $terbilangs=array('nol','satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan');
    return $terbilangs[$index];
} 

function alasan($als) {
    switch ($als) {
        case 'do' : 
            return 'mahasiswa terancam DO';
        case 'tuntas':
            return 'mahasiswa tuntas';
        default:
            return 'mahasiswa berprestasi (IPK>3.75)';
    }
}
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <style media="print" type="text/css">
            .noprint { display: none }
        </style>
        <style type="text/css">
            body,td,th,p,li {font-family: Arial;font-size: medium}
        </style>
    </head>
    <body style="width:800px">
        <div class="noprint">
<?php
if ($pesan) :
    ?>

<div id="flash_pesan" style="color:#800; background-color: #1e90ff; width: 80%; margin: 10px; border: 2px ridge">
  <?php echo $pesan; $_SESSION['pesan']='';  ?>  
</div>
<?php
endif;
?>
            <table border="0">
                <tr>
                    <td>
<input type="button" name="printBtn" value="Cetak" onclick="javascript:window.print()" />                        
                    </td>
                    <td>
<input type="button" name="homeBtn" value="Home" onclick="javascript:document.location='index.php'" />                        
                    </td>
                </tr>
            </table>
        </div>
        
<?php
function cetakForm() {
    global $mhs,$tsks,$namaUser,$nrp;
?>
<h2 style="text-align: center">SURAT REKOMENDASI DEKAN<br/>tentang<br/>DISPENSASI MENGAMBIL SKS LEBIH</h2>
        <div align="center">
            
        
        <table border="0" cellpadding="2" cellspacing="2"  >
            <tr>
                <td colspan="3"><p>Yang bertanda tangan di bawah ini saya :</p></td>
            </tr>
            <tr>
                <td>Nama</td><td>:</td><td><?php echo $mhs['Nama']; ?></td>
            </tr>
            <tr>
                <td>NRP</td><td>:</td><td><?php echo $nrp; ?></td>
            </tr>
            <tr>
                <td colspan="3">
mengajukan permohonan rekomendasi SKS lebih sebanyak <u><b><?php echo $tsks['sks'].' ('.terbilang($tsks['sks']).')'; ?></b></u> SKS <br/>
karena pertimbangan: 
<u><b><?php echo alasan($tsks['alasan']); ?></b></u>
                </td>
            </tr>
            
        </table>
        </div>
         <div align="center">
        
        <table border="1" cellpadding="2" cellspacing="2" style="margin-top:20px; text-align: center">
            <tr>
                <td align="center"  >
                    Mahasiswa, <br/><br/><br/><br/><br/><br/><br/>
                    ( <b><u><?php echo $mhs['Nama'];?></u></b> )
                </td>
                <td align="center"  >
                    Mengetahui,<br/>
                    Academic Advisor, <br/><br/><br/><br/><br/><br/>
                    ( <b><u><?php echo $namaUser;?></u></b> )
                </td>
                <td align="center"  >
                    Mengetahui, <br/>
                    Wakil Dekan,<br/><br/><br/><br/><br/><br/>
                    ( <b><u>Ir. Hudiyo Firmanto, M.Sc.</u></b> )
                </td>
            </tr>
        </table>
        </div>         
        
<?php        
}

cetakForm();
echo '<br/><hr/><br/>';
cetakForm();
?>


    </body>
</html>