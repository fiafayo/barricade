<?php
	session_start();
	include("config.php");
	$username = $_SESSION['usernamee'];
	$password = $_SESSION['passwordd'];
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
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$klik=true;
		$nama=$_REQUEST['nama'];
		$tglMulai=$_REQUEST['tglMulai'];
		$tglSelesai=$_REQUEST['tglSelesai'];
		$nrp=$_REQUEST['nrp'];
		//$query="SELECT * FROM pengadaan where (Kode='$username'  AND Status='Belum Terlaksana') AND (NoPengadaan LIKE '%$search%' OR Tanggal LIKE '%$search%' OR Hari LIKE '%$search%') ORDER BY NoPengadaan"; 
		//$hasil=mysql_query($query);
		if((!empty($tglMulai) && empty($tglSelesai)) || (empty($tglMulai) && !empty($tglSelesai)))
		{
			$kesalahan="kedua tanggal harap diisi";
			$klik=false;
		}
		else
		{
			if((!empty($tglMulai) || !empty($tglSelesai)) && empty($nama) && empty($nrp))
			{
				$query="SELECT d.NRP, m.Nama FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
			WHERE o.Kode=$username AND o.Fakultas=m.Fakultas AND o.Jurusan=m.Jurusan AND d.NRP=m.NRP AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran=h.NoPendaftaran AND ( (p.Tanggal>='$tglMulai' AND p.Tanggal<='$tglSelesai')) GROUP BY d.NRP";
			}
			else if((!empty($tglMulai) || !empty($tglSelesai)) && !empty($nama) && empty($nrp))
			{
				$query="SELECT d.NRP, m.Nama FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
			WHERE o.Kode=$username AND o.Fakultas=m.Fakultas AND o.Jurusan=m.Jurusan AND d.NRP=m.NRP AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran=h.NoPendaftaran AND ( (p.Tanggal>='$tglMulai' AND p.Tanggal<='$tglSelesai') AND m.Nama LIKE '%$nama%') GROUP BY d.NRP";
			}
			else if((!empty($tglMulai) || !empty($tglSelesai)) && empty($nama) && !empty($nrp))
			{
				$query="SELECT d.NRP, m.Nama FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
			WHERE o.Kode=$username AND o.Fakultas=m.Fakultas AND o.Jurusan=m.Jurusan AND d.NRP=m.NRP AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran=h.NoPendaftaran AND ( (p.Tanggal>='$tglMulai' AND p.Tanggal<='$tglSelesai')  AND m.NRP LIKE '%$nrp%') GROUP BY d.NRP";
			}
			else if((empty($tglMulai) || empty($tglSelesai)) && !empty($nama) && !empty($nrp))
			{
				$query="SELECT d.NRP, m.Nama FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
				WHERE o.Kode=$username AND o.Fakultas=m.Fakultas AND o.Jurusan=m.Jurusan AND d.NRP=m.NRP AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran=h.NoPendaftaran AND ( m.Nama LIKE '%$nama%' AND m.NRP LIKE '%$nrp%') GROUP BY d.NRP";
			}
			else if((empty($tglMulai) || empty($tglSelesai)) && !empty($nama) && empty($nrp))
			{
				$query="SELECT d.NRP, m.Nama FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
				WHERE o.Kode=$username AND o.Fakultas=m.Fakultas AND o.Jurusan=m.Jurusan AND d.NRP=m.NRP AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran=h.NoPendaftaran AND ( m.Nama LIKE '%$nama%') GROUP BY d.NRP";
			}
			else if((empty($tglMulai) || empty($tglSelesai)) && empty($nama) && !empty($nrp))
			{
				$query="SELECT d.NRP, m.Nama FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
			WHERE o.Kode=$username AND o.Fakultas=m.Fakultas AND o.Jurusan=m.Jurusan AND d.NRP=m.NRP AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran=h.NoPendaftaran AND ( m.NRP LIKE '%$nrp%') GROUP BY d.NRP";
			}
			else if((!empty($tglMulai) || !empty($tglSelesai)) && !empty($nama) && !empty($nrp))
			{
				$query="SELECT d.NRP, m.Nama FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
			WHERE o.Kode=$username AND o.Fakultas=m.Fakultas AND o.Jurusan=m.Jurusan AND d.NRP=m.NRP AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran=h.NoPendaftaran AND ( (p.Tanggal>='$tglMulai' AND p.Tanggal<='$tglSelesai') AND m.Nama LIKE '%$nama%' AND m.NRP LIKE '%$nrp%') GROUP BY d.NRP";
			}
			else
			{
				$query="SELECT d.NRP, m.Nama FROM pendaftaran as d, hasilkonsultasi as h, pengadaan as p, mahasiswa as m, dosen as o
				WHERE o.Kode=$username AND o.Fakultas=m.Fakultas AND o.Jurusan=m.Jurusan AND d.NRP=m.NRP AND d.NoPengadaan=p.NoPengadaan AND d.NoPendaftaran=h.NoPendaftaran AND ( (p.Tanggal>='$tglMulai' AND p.Tanggal<='$tglSelesai') OR m.NRP LIKE '%$nrp%' OR m.Nama LIKE '%$nama%') GROUP BY d.NRP";
			}
			$hasil=mysql_query($query);
			
			if(mysql_num_rows($hasil)<1)
			{
				$kesalahan="data tdk ada";
				$klik=false;
			}
		}
	}
	

	
	/*$itemPerPage = 12;
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
		$batasAtas = $page + 1;*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>Academic Advisor | Dosen :: Riwayat Konsultasi </title>
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
if($kesalahan=="data tdk ada")
{
	$isitglMulai=$tglMulai;
	$isitglSelesai=$tglSelesai;
	$isinrp=$nrp;
	$isinama=$nama;
	print "<script>
			alert('Tidak ada data yang sesuai!');						
		   </script>";
}
else if($kesalahan=="kedua tanggal harap diisi")
{
	$isitglMulai=$tglMulai;
	$isitglSelesai=$tglSelesai;
	$isinrp=$nrp;
	if(empty($isitglMulai))
	{
		print "<script>
			alert('Tanggal awal harap diisi!');						
		   </script>";
	}
	else
	{
	print "<script>
			alert('Tanggal akhir harap diisi!');						
		   </script>";
	}
}
?>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">
            <table border="0" align="center" width="100%" cellpadding="4" cellspacing="4">
		<tr>
			<td>
			<form action="riwayatKonsul.php" method="post">
			<br/><div style="font-size:20px" align="center">RIWAYAT KONSULTASI</div><br/>
				<table border="0" align="center" cellpadding="2" cellspacing="2">
				  <tr>
						<td align="right">
						<strong>Tanggal</strong>
						</td>
						<td align="left">
						<input type="text" name="tglMulai" readonly="true" size="12"  id="tglMulai" value="<? echo $tglMulai; ?>"> 
							<input type=button value="pilih" onClick="displayDatePicker('tglMulai', this);" >
						</td>
				  </tr>
				  <tr>
					  <td></td>
					  <td><font size="-1">sampai</font></td>
				  </tr>
				  <tr>
						<td align="right">
						
						</td>
						<td align="left">
						<input type="text" name="tglSelesai" readonly="true" size="12"  id="tglSelesai" value="<? echo $tglSelesai; ?>"> 
							<input type=button value="pilih" onClick="displayDatePicker('tglSelesai', this);" >
						</td>
				  </tr>
				  <tr>
				  	<td align="right">
						<strong>NRP</strong>
					</td>
					<td>
						<input type="text" name="nrp" id="nrp" size="15" onkeypress="return isNumber(event,this);" value="<? echo $nrp; ?>">
					</td>
				  </tr>
				  <tr>
				  	<td align="right">
						<strong>Nama</strong>
					</td>
					<td>
						<input type="text" name="nama" id="nama"  size="25" value="<? echo $nama; ?>">
					</td>
				  </tr>
				  <tr>
				  	<td colspan="2" align="center"><input type="submit" name="submit" value="Lihat"></td>
				  </tr>
				  <tr>
				  	<td colspan="2" align="center"><br/><font  style="size:12px; color:red;"><i><b>Klik pada NRP untuk melihat riwayat konsultasi</b></i></font></td>
				  </tr>
				  <tr>
				  	<td colspan="2">
						<?
							if($klik==false)
							{
								echo "<table align='center'>
									<th width='50px'>NRP</th> <th width='50px'>Nama</th>
									<tr bgcolor='#F0F0F0'>
										<td align='center'>-</td>
										<td align='center'>-</td>
									</tr>
								</table>";
							}
							else
							{
							echo "<table align='center'>
									<th>NRP</th> <th>Nama</th>";
								$baris=mysql_fetch_assoc($hasil);
								$warna=1;
								while($baris)
								{
									if($warna%2==0)
									{
										echo"<tr bgcolor='#E2E2E2'><td><a href='detailRiwayatKonsul.php?nrp=".$baris['NRP']."'>".$baris['NRP']."</a></td><td>".$baris['Nama']."</td></tr>";
									}
									else
									{
										echo"<tr bgcolor='#F0F0F0'><td><a href='detailRiwayatKonsul.php?nrp=".$baris['NRP']."'>".$baris['NRP']."</a></td><td>".$baris['Nama']."</td></tr>";
									}
									$warna=$warna+1;
									$baris=mysql_fetch_assoc($hasil);
								}
								
								echo"</table>";
							}
						?>

					</td>
				  </tr>
				 </table>
				 </form>
			</td>
		</tr>
		  <tr>
			<td align="right">
				<?php //PAGING
					/*if($isi==true)
					{
						if ($page != 1)
						{
							$prev = $page - 1;
							print "<a href='pengadaanKonsul.php'>&lt;&lt;First</a>";
							print "&nbsp;&nbsp;<a href='pengadaanKonsul.php?page=$prev'>&lt;Prev<a>&nbsp;&nbsp;";
						}
						
						if ($batasBawah > 1)
							print '. . . ';
						
						for ($i = $batasBawah; $i <= $batasAtas; $i++)
						{
							if ($i == $page)
								print "<b>[$i]</b> ";
							else
							{
								print "<a href='pengadaanKonsul.php?page=$i'>$i</a> ";
							}
						}
						
						if ($batasAtas < $totalPage)
							print ' . . .';
						
						if ($page != $totalPage)
						{
							$next = $page + 1;
							print "&nbsp;&nbsp;<a href='pengadaanKonsul.php?page=$next'>Next&gt;</a>&nbsp;&nbsp;";
							print "<a href='pengadaanKonsul.php?page=$totalPage'>Last&gt;&gt;</a>";
						}
					}*/
						//END OF PAGING ?>
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
