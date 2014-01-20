<?php
	session_start();
	include("config.php");
	include("cekInteger.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
	if(empty($username) && empty($password))
	{
		print "<script>
				alert('Maaf username atau kata kunci yang anda masukkan salah!');						
			   </script>";
		print "<script>
				window.location='index.php';
			   </script>";
	}
	if($_SERVER['REQUEST_METHOD']=="POST")
	{	
		$kode=$_REQUEST['kode'];
		$tipe=$_REQUEST['tipe'];
		$nama=$_REQUEST['nama'];
		$notelp=$_REQUEST['notelp'];
		$email=$_REQUEST['email'];
		$alamat=$_REQUEST['alamat'];
		$fakultas=$_REQUEST['cboFakultas'];
		$jurusan=$_REQUEST['cboJurusan'];
		$tahunawal=$_REQUEST['tglMulai'];
		$tahunakhir=$_REQUEST['tglSelesai'];
		$benar=eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
		
		if($_POST['submit']=='Batal')
		{
			header('Location: karyawan.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($kode) or empty($nama) or empty($alamat) or empty($notelp) or empty($email) or empty($tahunawal) or empty($tahunakhir))
			{
				$kesalahan="data kosong";
			}
			else
			{
				/*$hasil=mysql_query("SELECT d.*, dt.KodeJabatan, dt.TahunAwal, dt.TahunAkhir FROM dosen as d, detailjabatan as dt WHERE d.Kode='$kode' AND d.Status=0 AND d.Kode=dt.KodeDosen");
				$baris=mysql_fetch_assoc($hasil);
				$ada=false;
				while($baris)
				{
					if(($tahunawal>=$baris['TahunAwal'] && $tahunawal<$baris['TahunAkhir']) ||  ($tahunakhir>=$baris['TahunAwal'] && $tahunakhir<=$baris['TahunAkhir']) || ($tahunawal<=$baris['TahunAwal'] && $tahunakhir>=$baris['TahunAkhir']))
					{
						$ada=true;
						break;
					}
					$baris=mysql_fetch_assoc($hasil);
				}*/
				$hasil=mysql_query("SELECT * FROM dosen WHERE Kode='$kode'");
				$ada=@mysql_num_rows($hasil);
				
				if(cekInteger($kode)==false)
				{
					$kesalahan="kode bukan angka";
				}
				else if($ada>0)
				{
					$kesalahan="kode sudah ada";
				}
				else if($tahunakhir<$tahunawal)
				{
					$kesalahan="tahun akhir lebih kecil";
				}
				else if(cekInteger($notelp)==false)
				{
					$kesalahan="notelp bukan angka";
				}
				else if(!$benar)
				{
					$kesalahan="email salah";
				}
				else
				{	
					if($tipe==3 || $tipe==4)
					{
						if($tipe==4)
						{
						$cari=mysql_query("SELECT dt.*
									FROM detailjabatan as dt, dosen as d 
									WHERE d.Fakultas='$fakultas' AND d.Jurusan='$jurusan' AND d.Kode=dt.KodeDosen AND dt.KodeJabatan='$tipe'");
						}
						else
						{
						$cari=mysql_query("SELECT dt.*
									FROM detailjabatan as dt, dosen as d 
									WHERE d.Fakultas='$fakultas' AND d.Kode=dt.KodeDosen AND dt.KodeJabatan='$tipe'");

						}
						
						if(@mysql_num_rows($cari)>0)
						{
							$brscari=mysql_fetch_assoc($cari);
							$kembar=false;
							while($brscari)
							{
								if(($tahunawal>=$brscari['TanggalAwal'] && $tahunawal<=$brscari['TanggalAkhir']) ||  ($tahunakhir>=$brscari['TanggalAwal'] && $tahunakhir<=$brscari['TanggalAkhir']) || ($tahunawal<=$brscari['TanggalAwal'] && $tahunakhir>=$brscari['TanggalAkhir']))
								{
									$kembar=true;
									break;
								}
								$brscari=mysql_fetch_assoc($cari);
							}
							if($kembar==true)
							{
								$kesalahan="overlap";
							}
							else
							{
								if($tipe==2)
								{
									$strInsertDsn="INSERT INTO dosen(Kode,Nama,Fakultas,Jurusan,Alamat,NoTelp,Email) values('$kode','$nama',0,0,'$alamat','$notelp','$email')";
								}
								else
								{
									$strInsertDsn="INSERT INTO dosen(Kode,Nama,Fakultas,Jurusan,Alamat,NoTelp,Email) values('$kode','$nama','$fakultas','$jurusan','$alamat','$notelp','$email')";
								}
								
								$queryInsertDsn=mysql_query($strInsertDsn);
								$strInserDetail="INSERT INTO detailjabatan(KodeDosen,KodeJabatan,TanggalAwal,TanggalAkhir) values('$kode','$tipe','$tahunawal','$tahunakhir')";
								$queryInserDetail=mysql_query($strInserDetail);
								if($queryInsertDsn && $queryInserDetail)
								{
									print "<script>
										alert('Data telah disimpan!');	
									 </script>";
									print "<script>
											window.location='tambahKaryawan.php';	
										</script>";
									exit();
								}
							}
						}
						else
						{
							if($tipe==2)
							{
								$strInsertDsn="INSERT INTO dosen(Kode,Nama,Fakultas,Jurusan,Alamat,NoTelp,Email) values('$kode','$nama',0,0,'$alamat','$notelp','$email')";
							}
							else
							{
								$strInsertDsn="INSERT INTO dosen(Kode,Nama,Fakultas,Jurusan,Alamat,NoTelp,Email) values('$kode','$nama','$fakultas','$jurusan','$alamat','$notelp','$email')";
							}
							
							$queryInsertDsn=mysql_query($strInsertDsn);
							$strInserDetail="INSERT INTO detailjabatan(KodeDosen,KodeJabatan,TanggalAwal,TanggalAkhir) values('$kode','$tipe','$tahunawal','$tahunakhir')";
							$queryInserDetail=mysql_query($strInserDetail);
							if($queryInsertDsn && $queryInserDetail)
							{
								print "<script>
									alert('Data telah disimpan!');	
								 </script>";
								print "<script>
										window.location='tambahKaryawan.php';	
									</script>";
								exit();
							}
						}
					}
					else
					{
						if($tipe==2)
						{
							$strInsertDsn="INSERT INTO dosen(Kode,Nama,Fakultas,Jurusan,Alamat,NoTelp,Email) values('$kode','$nama',0,0,'$alamat','$notelp','$email')";
						}
						else
						{
							$strInsertDsn="INSERT INTO dosen(Kode,Nama,Fakultas,Jurusan,Alamat,NoTelp,Email) values('$kode','$nama','$fakultas','$jurusan','$alamat','$notelp','$email')";
						}
						
						$queryInsertDsn=mysql_query($strInsertDsn);
						$strInserDetail="INSERT INTO detailjabatan(KodeDosen,KodeJabatan,TanggalAwal,TanggalAkhir) values('$kode','$tipe','$tahunawal','$tahunakhir')";
						$queryInserDetail=mysql_query($strInserDetail);
						if($queryInsertDsn && $queryInserDetail)
						{
							print "<script>
								alert('Data telah disimpan!');	
							 </script>";
							print "<script>
									window.location='tambahKaryawan.php';	
								</script>";
							exit();
						}
					}
					
				}
			}
			
		}
		
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Admin :: Tambah Karyawan </title>
	<link rel="stylesheet" href="style.css" media="screen" />
<script type="text/javascript" language = "javascript">

var XMLHttpRequestObject = false;

//initialisasi object XMLHttpRequest beda antara IE dengan FireFox, dan lain-lain

//jika bukan IE
if (window.XMLHttpRequest) {
XMLHttpRequestObject = new XMLHttpRequest();
}

//jika IE
else if (window.ActiveXObject) {
XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
}

 

//fungsi untuk mengambil data di datasource dimasukkan ke div dengan id divID
function getData(dataSource, divID)

{
//jalankan jika object httprequest telah terbuat
if(XMLHttpRequestObject) {

//ambil object div
var obj = document.getElementById(divID);

//buka data di datasource
XMLHttpRequestObject.open("GET", dataSource);

//jika ada perubahan state
XMLHttpRequestObject.onreadystatechange = function()

{

//jika sudah complete dan sukses
if (XMLHttpRequestObject.readyState == 4 &&
XMLHttpRequestObject.status == 200) {

//ambil data masukkan dalam div
obj.innerHTML = XMLHttpRequestObject.responseText;

}

}

XMLHttpRequestObject.send(null);

}

}

var datePickerDivID = "datepicker";
var iFrameDivID = "datepickeriframe";

var dayArrayShort = new Array('Mgg', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab');
var dayArrayMed = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
var dayArrayLong = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var monthArrayShort = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
var monthArrayMed = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
var monthArrayLong = new Array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
 

var defaultDateSeparator = "-";        // common values would be "/" or "."
var defaultDateFormat = "ymd"    // valid values are "mdy", "dmy", and "ymd"
var dateSeparator = defaultDateSeparator;
var dateFormat = defaultDateFormat;

function displayDatePicker(dateFieldName, displayBelowThisObject, dtFormat, dtSep)
{
  var targetDateField = document.getElementsByName (dateFieldName).item(0);
 
  if (!displayBelowThisObject)
    displayBelowThisObject = targetDateField;
 
  if (dtSep)
    dateSeparator = dtSep;
  else
    dateSeparator = defaultDateSeparator;
 
  if (dtFormat)
    dateFormat = dtFormat;
  else
    dateFormat = defaultDateFormat;
 
  var x = displayBelowThisObject.offsetLeft;
  var y = displayBelowThisObject.offsetTop + displayBelowThisObject.offsetHeight ;
 
  var parent = displayBelowThisObject;
  while (parent.offsetParent) {
    parent = parent.offsetParent;
    x += parent.offsetLeft;
    y += parent.offsetTop ;
  }
 
  drawDatePicker(targetDateField, x, y);
}

function drawDatePicker(targetDateField, x, y)
{
  var dt = getFieldDate(targetDateField.value );
 
  if (!document.getElementById(datePickerDivID)) {
    var newNode = document.createElement("div");
    newNode.setAttribute("id", datePickerDivID);
    newNode.setAttribute("class", "dpDiv");
    newNode.setAttribute("style", "visibility: hidden;");
    document.body.appendChild(newNode);
  }
 
 
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.position = "absolute";
  pickerDiv.style.left = x + "px";
  pickerDiv.style.top = y + "px";
  pickerDiv.style.visibility = (pickerDiv.style.visibility == "visible" ? "hidden" : "visible");
  pickerDiv.style.display = (pickerDiv.style.display == "block" ? "none" : "block");
  pickerDiv.style.zIndex = 10000;
 
 
  refreshDatePicker(targetDateField.name, dt.getFullYear(), dt.getMonth(), dt.getDate());
}


function refreshDatePicker(dateFieldName, year, month, day)
{
 
  var thisDay = new Date();
 
  if ((month >= 0) && (year > 0)) {
    thisDay = new Date(year, month, 1);
  } else {
    day = thisDay.getDate();
    thisDay.setDate(1);
  }
 
 
  var crlf = "\r\n";
  var TABLE = "<table cols=7 class='dpTable'>" + crlf;
  var xTABLE = "</table>" + crlf;
  var TR = "<tr class='dpTR'>";
  var TR_title = "<tr class='dpTitleTR'>";
  var TR_days = "<tr class='dpDayTR'>";
  var TR_todaybutton = "<tr class='dpTodayButtonTR'>";
  var xTR = "</tr>" + crlf;
  var TD = "<td class='dpTD' onMouseOut='this.className=\"dpTD\";' onMouseOver=' this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
  var TD_title = "<td colspan=5 class='dpTitleTD'>";
  var TD_buttons = "<td class='dpButtonTD'>";
  var TD_todaybutton = "<td colspan=7 class='dpTodayButtonTD'>";
  var TD_days = "<td class='dpDayTD'>";
  var TD_selected = "<td class='dpDayHighlightTD' onMouseOut='this.className=\"dpDayHighlightTD\";' onMouseOver='this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
  var xTD = "</td>" + crlf;
  var DIV_title = "<div class='dpTitleText'>";
  var DIV_selected = "<div class='dpDayHighlight'>";
  var xDIV = "</div>";

  var html = TABLE;
 
 
  html += TR_title;
  html += TD_buttons + getButtonCode(dateFieldName, thisDay, -1, "&lt;") + xTD;
  html += TD_title + DIV_title + monthArrayLong[ thisDay.getMonth()] + " " + thisDay.getFullYear() + xDIV + xTD;
  html += TD_buttons + getButtonCode(dateFieldName, thisDay, 1, "&gt;") + xTD;
  html += xTR;
 
  html += TR_days;
  for(i = 0; i < dayArrayShort.length; i++)
    html += TD_days + dayArrayShort[i] + xTD;
  html += xTR;
 
  html += TR;
 
  for (i = 0; i < thisDay.getDay(); i++)
    html += TD + "&nbsp;" + xTD;
 
  do {
    dayNum = thisDay.getDate();
    TD_onclick = " onclick=\"updateDateField('" + dateFieldName + "', '" + getDateString(thisDay) + "');\">";
    
    if (dayNum == day)
      html += TD_selected + TD_onclick + DIV_selected + dayNum + xDIV + xTD;
    else
      html += TD + TD_onclick + dayNum + xTD;
    
    if (thisDay.getDay() == 6)
      html += xTR + TR;
    
    thisDay.setDate(thisDay.getDate() + 1);
  } while (thisDay.getDate() > 1)
 
 
  if (thisDay.getDay() > 0) {
    for (i = 6; i > thisDay.getDay(); i--)
      html += TD + "&nbsp;" + xTD;
  }
  html += xTR;
 
  var today = new Date();
  var todayString = "Today is " + dayArrayMed[today.getDay()] + ", " + monthArrayMed[ today.getMonth()] + " " + today.getDate();
  html += TR_todaybutton + TD_todaybutton;
  html += "<button class='dpTodayButton' onClick='refreshDatePicker(\"" + dateFieldName + "\");'>Tgl sekarang</button> ";
  html += "<button class='dpTodayButton' onClick='updateDateField(\"" + dateFieldName + "\");'>Tutup</button>";
  html += xTD + xTR;
 

  html += xTABLE;
 
  document.getElementById(datePickerDivID).innerHTML = html;
 
  adjustiFrame();
}


function getButtonCode(dateFieldName, dateVal, adjust, label)
{
  var newMonth = (dateVal.getMonth () + adjust) % 12;
  var newYear = dateVal.getFullYear() + parseInt((dateVal.getMonth() + adjust) / 12);
  if (newMonth < 0) {
    newMonth += 12;
    newYear += -1;
  }
 
  return "<button class='dpButton' onClick='refreshDatePicker(\"" + dateFieldName + "\", " + newYear + ", " + newMonth + ");'>" + label + "</button>";
}

function getDateString(dateVal)
{
  var dayString = "00" + dateVal.getDate();
  var monthString = "00" + (dateVal.getMonth()+1);
  dayString = dayString.substring(dayString.length - 2);
  monthString = monthString.substring(monthString.length - 2);
 
  switch (dateFormat) {
    case "dmy" :
      return dayString + dateSeparator + monthString + dateSeparator + dateVal.getFullYear();
    case "ymd" :
      return dateVal.getFullYear() + dateSeparator + monthString + dateSeparator + dayString;
    case "mdy" :
    default :
      return monthString + dateSeparator + dayString + dateSeparator + dateVal.getFullYear();
  }
}

function getFieldDate(dateString)
{
  var dateVal;
  var dArray;
  var d, m, y;
 
  try {
    dArray = splitDateString(dateString);
    if (dArray) {
      switch (dateFormat) {
        case "dmy" :
          d = parseInt(dArray[0], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
        case "ymd" :
          d = parseInt(dArray[2], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[0], 10);
          break;
        case "mdy" :
        default :
          d = parseInt(dArray[1], 10);
          m = parseInt(dArray[0], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
      }
      dateVal = new Date(y, m, d);
    } else if (dateString) {
      dateVal = new Date(dateString);
    } else {
      dateVal = new Date();
    }
  } catch(e) {
    dateVal = new Date();
  }
 
  return dateVal;
}

function splitDateString(dateString)
{
  var dArray;
  if (dateString.indexOf("/") >= 0)
    dArray = dateString.split("/");
  else if (dateString.indexOf(".") >= 0)
    dArray = dateString.split(".");
  else if (dateString.indexOf("-") >= 0)
    dArray = dateString.split("-");
  else if (dateString.indexOf("\\") >= 0)
    dArray = dateString.split("\\");
  else
    dArray = false;

 
  return dArray;
}

function datePickerClosed(dateField)
{
  var dateObj = getFieldDate(dateField.value);
  var today = new Date();
  today = new Date(today.getFullYear(), today.getMonth(), today.getDate());
 
  if (dateField.name == "StartDate") {
    if (dateObj < today) {
      // if the date is before today, alert the user and display the datepicker again
      alert("Tidak dapat memilih tanggal yang telah lewat");
      dateField.value = "";
      document.getElementById(datePickerDivID).style.visibility = "";
      adjustiFrame();
    } else {
      // if the date is okay, set the EndDate field to 7 days after the StartDate
      dateObj.setTime(dateObj.getTime() + (7 * 24 * 60 * 60 * 1000));
      var endDateField = document.getElementsByName ("EndDate").item(0);
      endDateField.value = getDateString(dateObj);
    }
  }
}


function updateDateField(dateFieldName, dateString)
{
  var targetDateField = document.getElementsByName (dateFieldName).item(0);
  if (dateString)
    targetDateField.value = dateString;
 
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.visibility = "hidden";
  pickerDiv.style.display = "none";
 
  adjustiFrame();
  targetDateField.focus();
 
  if ((dateString) && (typeof(datePickerClosed) == "function"))
    datePickerClosed(targetDateField);
}

function adjustiFrame(pickerDiv, iFrameDiv)
{
  var is_opera = (navigator.userAgent.toLowerCase().indexOf("opera") != -1);
  if (is_opera)
    return;
  
 
  try {
    if (!document.getElementById(iFrameDivID)) {
      var newNode = document.createElement("iFrame");
      newNode.setAttribute("id", iFrameDivID);
      newNode.setAttribute("src", "javascript:false;");
      newNode.setAttribute("scrolling", "no");
      newNode.setAttribute ("frameborder", "0");
      document.body.appendChild(newNode);
    }
    
    if (!pickerDiv)
      pickerDiv = document.getElementById(datePickerDivID);
    if (!iFrameDiv)
      iFrameDiv = document.getElementById(iFrameDivID);
    
    try {
      iFrameDiv.style.position = "absolute";
      iFrameDiv.style.width = pickerDiv.offsetWidth;
      iFrameDiv.style.height = pickerDiv.offsetHeight ;
      iFrameDiv.style.top = pickerDiv.style.top;
      iFrameDiv.style.left = pickerDiv.style.left;
      iFrameDiv.style.zIndex = pickerDiv.style.zIndex - 1;
      iFrameDiv.style.visibility = pickerDiv.style.visibility ;
      iFrameDiv.style.display = pickerDiv.style.display;
    } catch(e) {
    }
 
  } catch (ee) {
  }
 
}

function isNumber(str,nama)
{
	var charCode = (str.which)?str.which:str.keyCode;	
	var field = eval(nama);
	
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	
		/*if (field.value > 23)
		{
			alert(field.value);
			return false;
		}*/
	
	return true;
}
</script>
<style>
body {
	/*font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;*/
	/*font-size: .8em;*/
	}

/* the div that holds the date picker calendar */
.dpDiv {
	}


/* the table (within the div) that holds the date picker calendar */
.dpTable {
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-align: center;
	color: #505050;
	background-color: #ece9d8;
	border: 1px solid #AAAAAA;
	}


/* a table row that holds date numbers (either blank or 1-31) */
.dpTR {
	}


/* the top table row that holds the month, year, and forward/backward buttons */
.dpTitleTR {
	}


/* the second table row, that holds the names of days of the week (Mo, Tu, We, etc.) */
.dpDayTR {
	}


/* the bottom table row, that has the "This Month" and "Close" buttons */
.dpTodayButtonTR {
	}


/* a table cell that holds a date number (either blank or 1-31) */
.dpTD {
	border: 1px solid #ece9d8;
	}


/* a table cell that holds a highlighted day (usually either today's date or the current date field value) */
.dpDayHighlightTD {
	background-color: #CCCCCC;
	border: 1px solid #AAAAAA;
	}


/* the date number table cell that the mouse pointer is currently over (you can use contrasting colors to make it apparent which cell is being hovered over) */
.dpTDHover {
	background-color: #aca998;
	border: 1px solid #888888;
	cursor: pointer;
	color: red;
	}


/* the table cell that holds the name of the month and the year */
.dpTitleTD {
	}


/* a table cell that holds one of the forward/backward buttons */
.dpButtonTD {
	}


/* the table cell that holds the "This Month" or "Close" button at the bottom */
.dpTodayButtonTD {
	}


/* a table cell that holds the names of days of the week (Mo, Tu, We, etc.) */
.dpDayTD {
	background-color: #CCCCCC;
	border: 1px solid #AAAAAA;
	color: white;
	}


/* additional style information for the text that indicates the month and year */
.dpTitleText {
	font-size: 12px;
	color: gray;
	font-weight: bold;
	}


/* additional style information for the cell that holds a highlighted day (usually either today's date or the current date field value) */ 
.dpDayHighlight {
	color: 4060ff;
	font-weight: bold;
	}


/* the forward/backward buttons at the top */
.dpButton {
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: gray;
	background: #d8e8ff;
	font-weight: bold;
	padding: 0px;
	}


/* the "This Month" and "Close" buttons at the bottom */
.dpTodayButton {
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: gray;
	background: #d8e8ff;
	font-weight: bold;
	}

</style>

</head>
<body>
<?
if($kesalahan=="data kosong")
{
	$isikode=$kode;
	$isitipe=$tipe;
	$isinama=$nama;
	$isinotelp=$notelp;
	$isiemail=$email;
	$isialamat=$alamat;
	$isitahunawal=$tahunawal;
	$isitahunakhir=$tahunakhir;
	print "<script>
		alert('Semua data harap diisi!');	
	 </script>";
}
else if($kesalahan=="kode bukan angka")
{
	$isikode="";
	$isitipe=$tipe;
	$isinama=$nama;
	$isinotelp=$notelp;
	$isiemail=$email;
	$isialamat=$alamat;
	$isitahunawal=$tahunawal;
	$isitahunakhir=$tahunakhir;
	print "<script>
		alert('Kode harap diisi dengan angka!');	
	 </script>";
}
else if($kesalahan=="kode sudah ada")
{
	$isikode="";
	$isitipe=$tipe;
	$isinama=$nama;
	$isinotelp=$notelp; 
	$isiemail=$email;
	$isialamat=$alamat;
	$isitahunawal=$tahunawal;
	$isitahunakhir=$tahunawal;
	print "<script> alert('Kode tersebut sudah pernah dibuat sebelumnya!') </script>";
}
else if($kesalahan=="tahun akhir lebih kecil")
{
	$isikode=$kode;
	$isitipe=$tipe;
	$isinama=$nama;
	$isinotelp=$notelp; 
	$isiemail=$email;
	$isialamat=$alamat;
	$isitahunawal=$tahunawal;
	$isitahunakhir="";
	print "<script> alert('Tanggal akhir lebih kecil dari Tanggal awal!') </script>";
}
else if($kesalahan=="notelp bukan angka")
{
	$isikode=$kode;
	$isitipe=$tipe;
	$isinama=$nama;
	$isinotelp="";
	$isiemail=$email;
	$isialamat=$alamat;
	$isitahunawal=$tahunawal;
	$isitahunakhir=$tahunakhir;
	print "<script>
	alert('NoTelp harap diisi dengan angka!');	
 </script>";
}
else if($kesalahan=="email salah")
{
	$isikode=$kode;
	$isitipe=$tipe;
	$isinama=$nama;
	$isinotelp=$notelp;
	$isiemail="";
	$isialamat=$alamat;
	$isitahunawal=$tahunawal;
	$isitahunakhir=$tahunakhir;
	print "<script> alert('Alamat email harap diisi dengan benar!') </script>";
}
else if($kesalahan=="overlap")
{
	$isikode=$kode;
	$isitipe=$tipe;
	$isinama=$nama;
	$isinotelp=$notelp;
	$isiemail=$email;
	$isialamat=$alamat;
	$isitahunawal=$tahunawal;
	$isitahunakhir=$tahunakhir;
	print "<script> alert('Jabatan pada waktu yang diinputkan sudah terisi!') </script>";
	//header("Location: Konfirmasi.php?
}

?>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">

		<table border="0" align="center"  cellspacing="0" width="100%">
			  <tr>
			  <td colspan="5">
			  <form action="tambahKaryawan.php" method="post">
			 <br/><div style="font-size:20px" align="center">TAMBAH KARYAWAN</div><br/>
			  	<table border="1" align="center" cellpadding="4" cellspacing="0" >
				<tr>
					<td>
					<input type="radio" name="tipe" value="5"  id="tipe"  onClick="getData('aturTambahKaryawan.php?tipe='+document.getElementById('tipe').value,'targetDiv')" <? if(!empty($kesalahan) && $tipe==5) {echo"checked"; } else if(empty($kesalahan)) { echo"checked";}?>>Dosen
					</td>
					<td>
					<input type="radio" name="tipe" value="4" id="tipe1" onClick="getData('aturTambahKaryawan.php?tipe='+document.getElementById('tipe1').value,'targetDiv')" <? if(!empty($kesalahan) && $tipe==4) {echo"checked";} ?>>Kajur
					</td>
					<td>
					<input type="radio" name="tipe" value="7" id="tipe4" onClick="getData('aturTambahKaryawan.php?tipe='+document.getElementById('tipe4').value,'targetDiv')" <? if(!empty($kesalahan) && $tipe==7) {echo"checked";} ?>>Wakil Dekan
					</td>
					<td>
					<input type="radio" name="tipe" value="3" id="tipe2" onClick="getData('aturTambahKaryawan.php?tipe='+document.getElementById('tipe2').value,'targetDiv')" <? if(!empty($kesalahan) && $tipe==3) echo"checked"; ?>>Dekan
					</td>
					<td>
					<input type="radio" name="tipe" value="2" id="tipe3" onClick="getData('aturTambahKaryawan.php?tipe='+document.getElementById('tipe3').value,'targetDiv')" <? if(!empty($kesalahan) && $tipe==2) echo"checked"; ?>>PLKPAM
					</td>
					<td>
					<input type="radio" name="tipe" value="1" id="tipe4" onClick="getData('aturTambahKaryawan.php?tipe='+document.getElementById('tipe4').value,'targetDiv')" <? if(!empty($kesalahan) && $tipe==1) echo"checked"; ?>>Wakil Rektor
					</td>
				</tr>
				<tr>
					<td colspan="6">
					<div id="targetDiv">
					<? if(!empty($kesalahan) && ($tipe==5 || $tipe==4 || $tipe==3 || $tipe==1 || $tipe==7))
					{
						echo" <table align='center' border='0' cellpadding='4'>
							<tr>
								<td align='right'><strong>Kode</strong></td>
								<td><input type='text' name='kode' onkeypress='return isNumber(event,this);' value='$isikode' size='10' maxlength='6'> 
								</td>
							</tr>
							<tr>
								<td align='right'><strong>Nama</strong></td>
								<td><input type='text' name='nama' value='$isinama' size='25'></td>
							</tr>
							<tr>
								<td align='right'><strong>Fakultas</strong></td>
								<td>";
							
								$hasil=mysql_query("SELECT DISTINCT f.* FROM fakultas as f, jurusan as j WHERE f.Kode<>0 AND f.Kode=j.KodeFakultas");
								$baris=mysql_fetch_assoc($hasil);
								echo "<select id='cboFakultas' name='cboFakultas'>";
								while($baris)
								{
									if($baris['Kode']==$fakultas)
									{	echo"<option value='".$baris['Kode']."' selected onclick=\"getData('aturJurusan.php?kode='+document.getElementById('cboFakultas').value,'divJurusan1')\">".$baris['Nama']."</option>";}
									else
									{	echo"<option value='".$baris['Kode']."' onclick=\"getData('aturJurusan.php?kode='+document.getElementById('cboFakultas').value,'divJurusan1')\">".$baris['Nama']."</option>";}
									$baris=mysql_fetch_assoc($hasil);
								}
								echo"</select>"; 
								echo"</td>
							</tr> 
							<tr>
								<td align='right'><strong>Jurusan</strong></td>
								<td><div id='divJurusan1'>";
							
									$query=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas=$fakultas");
									$baris=mysql_fetch_assoc($query);
									echo"<select id='cboJurusan' name='cboJurusan'>";
									while($baris)
									{
										if($baris['Kode']==$jurusan)
										{	echo"<option value='".$baris['Kode']."' selected>".$baris['Nama']."</option>";  }
										else
										{	echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>"; }
										$baris=mysql_fetch_assoc($query);
									}
									echo"</select>"; 
								echo"</div></td>
							</tr>
							<tr>
								<td><strong>Tanggal awal</strong></td>
								<td>";
									if(empty($isitahunawal))
									{	
										?>
										<input type="text" name="tglMulai" readonly="true" size="12" id="tglMulai"> 
										<input type=button value="pilih" onClick="displayDatePicker('tglMulai', this);" >
									<? 
									}
									else
									{
										?>
										<input type="text" name="tglMulai" readonly="true" size="12" value="<? echo $isitahunawal; ?>" id="tglMulai"> 
										<input type=button value="pilih" onClick="displayDatePicker('tglMulai', this);" >
									<?
									}
								echo"</td>
							</tr>
							<tr>
								<td><strong>Tanggal akhir</strong></td>
								<td>";
								if(empty($isitahunakhir))
								{	?>
									<input type="text" name="tglSelesai" readonly="true" size="12" id="tglSelesai"> 
									<input type=button value="pilih" onClick="displayDatePicker('tglSelesai', this);" >
								<? }
								else
								{?>
									<input type="text" name="tglSelesai" readonly="true" size="12" value="<? echo $isitahunakhir; ?>" id="tglSelesai"> 
									<input type=button value="pilih" onClick="displayDatePicker('tglSelesai', this);" >
								<? }
								
								echo"</td>
							</tr>
							<tr>
								<td align='right'><strong>Alamat</strong></td>
								<td><input type='text' name='alamat' value='$isialamat'></td>
							</tr>
							<tr>
								<td align='right'><strong>No.Telp</strong></td>
								<td><input type='text' name='notelp' onkeypress='return isNumber(event,this);' value='$isinotelp' size='15'></td>
							</tr>
							<tr>
								<td align='right'><strong>Email</strong></td>
								<td><input type='text' name='email' value='$isiemail' size='25'></td>
							</tr>
							<tr>
								<td colspan='2' align='center'><br/><input type='submit' name='submit' value='Simpan'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type='submit' name='submit' value='Batal'>
								</td>
								
							</tr>
						</table>";
					}
					else if(!empty($kesalahan) && $tipe==2)
					{
						echo" <table align='center' border='0' cellpadding='4'>
											<tr>
												<td align='right'><strong>Kode</strong></td>
												<td><input type='text' name='kode' onkeypress='return isNumber(event,this);' value='$isikode' size='10' maxlength='6'> 
												</td>
											</tr>
											<tr>
												<td align='right'><strong>Nama</strong></td>
												<td><input type='text' name='nama' value='$isinama' size='25'></td>
											</tr>
											<tr>
												<td><strong>Tanggal awal</strong></td>
												<td>";
												if(empty($isitahunawal))
												{	
													?>
													<input type="text" name="tglMulai" readonly="true" size="12" id="tglMulai"> 
													<input type=button value="pilih" onClick="displayDatePicker('tglMulai', this);" >
												<? 
												}
												else
												{
													?>
													<input type="text" name="tglMulai" readonly="true" size="12" value="<? echo $isitahunawal; ?>" id="tglMulai"> 
													<input type=button value="pilih" onClick="displayDatePicker('tglMulai', this);" >
												<?
												}
												echo"</td>
											</tr>
											<tr>
												<td><strong>Tanggal akhir</strong></td>
												<td>";
												if(empty($isitahunakhir))
												{	?>
													<input type="text" name="tglSelesai" readonly="true" size="12" id="tglSelesai"> 
													<input type=button value="pilih" onClick="displayDatePicker('tglSelesai', this);" >
												<? }
												else
												{?>
													<input type="text" name="tglSelesai" readonly="true" size="12" value="<? echo $isitahunakhir; ?>" id="tglSelesai"> 
													<input type=button value="pilih" onClick="displayDatePicker('tglSelesai', this);" >
												<? }
												
												echo"
												</td>
											</tr>
											<tr>
												<td align='right'><strong>Alamat</strong></td>
												<td><input type='text' name='alamat' value='$isialamat'></td>
											</tr>
											<tr>
												<td align='right'><strong>No.Telp</strong></td>
												<td><input type='text' name='notelp' onkeypress='return isNumber(event,this);' value='$isinotelp' size='15'></td>
											</tr>
											<tr>
												<td align='right'><strong>Email</strong></td>
												<td><input type='text' name='email' value='$isiemail' size='25'></td>
											</tr>
											<tr>
												<td colspan='2' align='center'><br/><input type='submit' name='submit' value='Simpan'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input type='submit' name='submit' value='Batal'>
												</td>
												
											</tr>
										</table>";
					}
					else
					{
						echo"<table align='center' border='0' cellpadding='4'>
							<tr>
								<td align='right'><strong>Kode</strong></td>
								<td><input type='text' name='kode' onkeypress='return isNumber(event,this);' value='' size='10' maxlength='6'> 
								</td>
							</tr>
							<tr>
								<td align='right'><strong>Nama</strong></td>
								<td><input type='text' name='nama' value='' size='25'></td>
							</tr>
							<tr>
								<td align='right'><strong>Fakultas</strong></td>
								<td>";
								
								$hasil=mysql_query("SELECT DISTINCT f.* FROM fakultas as f, jurusan as j WHERE f.Kode<>0 AND f.Kode=j.KodeFakultas");
								$baris=mysql_fetch_assoc($hasil);
								echo "<select id='cboFakultas' name='cboFakultas'>";
								while($baris)
								{
									echo"<option value='".$baris['Kode']."' onclick=\"getData('aturJurusan.php?kode='+document.getElementById('cboFakultas').value,'divJurusan1')\">".$baris['Nama']."</option>";
									$baris=mysql_fetch_assoc($hasil);
								}
								echo"</select>"; 
								echo"</td>
							</tr> 
							<tr>
								<td align='right'><strong>Jurusan</strong></td>
								<td><div id='divJurusan1'>";
								
									$query=mysql_query("SELECT * FROM jurusan WHERE KodeFakultas=1");
									$baris=mysql_fetch_assoc($query);
									echo"<select id='cboJurusan' name='cboJurusan'>";
									while($baris)
									{
										echo"<option value='".$baris['Kode']."'>".$baris['Nama']."</option>"; 
										$baris=mysql_fetch_assoc($query);
									}
									echo"</select>"; 
								echo"</div></td>
							</tr>
							<tr>
								<td><strong>Tanggal awal</strong></td>
								<td>"; ?>
								<input type="text" name="tglMulai" readonly="true" size="12" id="tglMulai"> 
								<input type=button value="pilih" onClick="displayDatePicker('tglMulai', this);" >
								<? echo"</td>
							</tr>
							<tr>
								<td><strong>Tanggal akhir</strong></td>
								<td>"; ?>
								<input type="text" name="tglSelesai" readonly="true" size="12" id="tglSelesai"> 
								<input type=button value="pilih" onClick="displayDatePicker('tglSelesai', this);" >
								<? echo"</td>
							</tr>
							<tr>
								<td align='right'><strong>Alamat</strong></td>
								<td><input type='text' name='alamat' value=''></td>
							</tr>
							<tr>
								<td align='right'><strong>No.Telp</strong></td>
								<td><input type='text' name='notelp' onkeypress='return isNumber(event,this);' value='' size='15'></td>
							</tr>
							<tr>
								<td align='right'><strong>Email</strong></td>
								<td><input type='text' name='email' value='' size='25'></td>
							</tr>
							<tr>
								<td colspan='2' align='center'><br/><input type='submit' name='submit' value='Simpan'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type='submit' name='submit' value='Batal'>
								</td>
								
							</tr>
						</table>";
					}
					?>
					</div>
					</td>
				</tr>
				</table>
				</form>
				<br/><br/>
				</td>
			  </tr>
			  </table>
		</div><!-- end content -->


		<div id="sidebar">
		   <ul>
		     <li><h2>MENU</h2></li>
			 <li><a href="admin.php">Halaman Utama</a></li>
			 <li><a href="kategori.php">Kategori Masalah</a></li>
 			 <li><a href="fakultas.php">Fakultas</a></li>
			   <li><a href="jurusan.php">Jurusan</a></li>
			 <li><a href="karyawan.php">Karyawan</a></li>
			 <li><a href="mahasiswa.php">Mahasiswa</a></li>
			  <li><a href="forum.php">Forum</a></li>
			    <li><a href="settingmail.php">Setting E-mail</a></li>
		   </ul>
		 </div> 

   		<div id="footer">Copyright 2009 Universitas Surabaya</div>
	</div>
</body>
</html>