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
		$pilihan=$_REQUEST['pilihan'];
		$tanggal=$_REQUEST['StartDate'];
		if($pilihan==1)
		{
			$kodeJam=$_REQUEST['cboJam'];
			$mulai=substr($kodeJam,0,8);
			$selesai=substr($kodeJam,9,8);
		}
		else
		{
			$jamMulai=$_REQUEST['jamMulai'];
			$jamSelesai=$_REQUEST['jamSelesai'];
			$menitMulai=$_REQUEST['menitMulai'];
			$menitSelesai=$_REQUEST['menitSelesai'];
			$mulai=str_pad($jamMulai, 2, "0",STR_PAD_LEFT).":".str_pad($menitMulai, 2, "0",STR_PAD_LEFT).":00";
			$selesai=str_pad($jamSelesai, 2, "0",STR_PAD_LEFT).":".str_pad($menitSelesai, 2, "0",STR_PAD_LEFT).":00";
		}
		
		$maxMhs=$_REQUEST['maxMhs'];
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
		
		if($_POST['submit']=='Batal')
		{
			header('Location: pengadaanKonsul.php');
			exit;
		}
		else if ($_POST['submit']=='Simpan')
		{
			if(empty($tanggal) || empty($maxMhs) || empty($pilihan) || ($pilihan==2 && (empty($jamMulai) || $menitMulai=="" || empty($jamSelesai) || $menitSelesai=="")) || ($pilihan==1 && empty($kodeJam)))
			{
				/*echo "<script>
						alert('Semua data harap diisi!');	
	  				 </script>";
				 print "<script>
						window.location='tambahPengadaanKonsul.php';	
					</script>";
				exit();*/
				$kesalahan="data kosong";
			}
			else
			{
				$today=getdate();
				$tanggalskrg=$today['year']."-".str_pad($today['mon'],2,"0",STR_PAD_LEFT)."-".str_pad($today['mday'],2,"0",STR_PAD_LEFT);
				$h=date("H")-1;
				$wktskrg=$h.":".date("i:s");
		
				if(cekInteger($jamMulai)==false || cekInteger($menitMulai)==false || cekInteger($jamSelesai)==false || cekInteger($menitSelesai)==false || cekInteger($maxMhs)==false)
				{
					$kesalahan="jam dan max bukan angka";
				}
				else if($jamMulai>23 || $jamSelesai>23)
				{
					$kesalahan="jam salah";
				}
				else if($menitMulai>59 || $menitSelesai>59)
				{
					$kesalahan="menit salah";
				}
				
				else if($selesai<$mulai)
				{
					/*print "<script>
							alert('Jam selesai harus lebih besar dari jam mulai!');	
						 </script>";
						 print "<script>
							window.location='tambahPengadaanKonsul.php';	
						</script>";
						 exit();*/
					$kesalahan="jam selesai lebih kecil dari jam mulai";
				}
				else if($tanggal==$tanggalskrg && $mulai<$wktskrg)
				{
					$kesalahan="jam harap lebih besar dari waktu sekarang";
				}
				else
				{
					$noPengadaan="";
					$today=getdate();
					//echo $today['year']."-".$today['mon']."-".$today['mday'];
					
					$thnskrg=substr($today['year'],2,2);
					$thnlalu=substr($today['year'],2,2)-1;
					if ($today['mon']>=2 && $today['mon']<=7)
					{	$noPengadaan="PE".str_pad($thnlalu, 2, "0",STR_PAD_LEFT).$thnskrg; 	}
					else
					{
						if($today['mon']==1)
						{	$noPengadaan="PA".str_pad($thnlalu, 2, "0",STR_PAD_LEFT).$thnskrg; 	}
						else
						{	$noPengadaan="PA".$thnskrg.str_pad($thnskrg+1, 2, "0",STR_PAD_LEFT); 	}
					}
					
					$query=mysql_query("SELECT * FROM pengadaan WHERE NoPengadaan LIKE '$noPengadaan%' AND Kode='$username' AND Status='Belum Terlaksana' ORDER BY NoPengadaan ASC");
					$dtPengadaan=mysql_fetch_assoc($query);
					$overlap=false;
					while($dtPengadaan)
					{
						if($tanggal==$dtPengadaan['Tanggal'] && (($mulai>=$dtPengadaan['JamMulai'] && $mulai<=$dtPengadaan['JamSelesai']) || ($selesai>=$dtPengadaan['JamMulai'] && $selesai<=$dtPengadaan['JamSelesai']) || ($mulai<=$dtPengadaan['JamMulai'] && $selesai>=$dtPengadaan['JamSelesai'])))
						{
							$overlap=true;
							break;					
						}
						$dtPengadaan=mysql_fetch_assoc($query);
					}
					if($overlap==true)
					{
							/*print "<script>
								alert('Jam konsultasi yang diinputkan bertubrukan dengan jadwal yang sudah ada!');	
							 </script>";
						 print "<script>
								window.location='tambahPengadaanKonsul.php';	
							</script>";
						exit();*/
						$kesalahan="jam bertubrukan";
					}
					else
					{
						$query=mysql_query("SELECT * FROM pengadaan WHERE NoPengadaan LIKE '$noPengadaan%' ORDER BY NoPengadaan ASC");
						$baris=mysql_fetch_assoc($query);
						$ctr=1;
						while($baris)
						{
							if(substr($baris['NoPengadaan'],6,4)==$ctr)
							{
								$ctr=$ctr+1;
							}
							else
							{	break;}
							$baris=mysql_fetch_assoc($query);
						}
						$noPengadaan=$noPengadaan.str_pad($ctr,4,"0",STR_PAD_LEFT);
						$str="INSERT INTO pengadaan (NoPengadaan,Tanggal,Hari,JamMulai,JamSelesai,MaxMhs,Status,Kode) VALUES ('$noPengadaan','$tanggal','$hari','$mulai','$selesai','$maxMhs','Belum Terlaksana','$username')";
						$hasil=mysql_query($str);
						if($hasil)
						{
						print "<script>
								alert('Pengadaan berhasil dibuat!');	
							 </script>";
						  print "<script>
								window.location='tambahPengadaanKonsul.php';	
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

	<title>Academic Advisor | Dosen :: Tambah Pengadaan Konsultasi </title>
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
document.frm.jamMulai.disabled = true;
	document.frm.jamSelesai.disabled = true;
	document.frm.menitMulai.disabled = true;
	document.frm.menitSelesai.disabled = true;
	document.frm.cboJam.disabled=true;
	document.frm.pilihan1.checked=false;
	document.frm.pilihan2.checked=false;
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
function tampil(idx)
{
	if(idx==1)
	{
		document.getElementById('divLama').style.disabled;
	}
	else if(idx==2)
	{
		document.getElementById('divBaru').style.disabled;
	}
}

function acdeac(idx)
{
	if (idx==1)
	{
		document.frm.jamMulai.disabled = true;
		document.frm.jamSelesai.disabled = true;
		document.frm.menitMulai.disabled = true;
		document.frm.menitSelesai.disabled = true;
		document.frm.cboJam.disabled=false;
		document.frm.jamMulai.value='';
		document.frm.jamSelesai.value='';
		document.frm.menitMulai.value='';
		document.frm.menitSelesai.value='';
		
	}
	else
	{
		document.frm.jamMulai.disabled = false;
		document.frm.jamSelesai.disabled = false;
		document.frm.menitMulai.disabled = false;
		document.frm.menitSelesai.disabled = false;
		document.frm.cboJam.disabled=true;
	}
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
	color: #4060ff;
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
	$isitanggal=$tanggal;
	$isijammulai=$jamMulai;
	$isijamselesai=$jamSelesai;
	$isimenitmulai=$menitMulai;
	$isimenitselesai=$menitSelesai;
	$isimaxmhs=$maxMhs;
	echo "<script>
		alert('Semua data harap diisi!');	
	 </script>";
}
else if($kesalahan=="jam dan max bukan angka")
{
	$isitanggal=$tanggal;
	$isijammulai=$jamMulai;
	$isijamselesai=$jamSelesai;
	$isimenitmulai=$menitMulai;
	$isimenitselesai=$menitSelesai;
	$isimaxmhs=$maxMhs;
	if(cekInteger($isijammulai)==false)
		$isijammulai="";
	if(cekInteger($isijamselesai)==false)
		$isijamselesai="";
	if(cekInteger($isimenitmulai)==false)
		$isimenitmulai="";
	if(cekInteger($isimenitselesai)==false)
		$isimenitselesai="";
	if(cekInteger($isimaxmhs)==false)
		$isimaxmhs="";
		
	echo "<script>
		alert('Jam dan max mhs harap diisi angka!');	
	 </script>";
}
else if($kesalahan=="jam selesai lebih kecil dari jam mulai")
{
	$isitanggal=$tanggal;
	$isijammulai=$jamMulai;
	$isijamselesai=$jamSelesai;
	$isimenitmulai=$menitMulai;
	$isimenitselesai=$menitSelesai;
	$isimaxmhs=$maxMhs;
	print "<script>
		alert('Jam selesai harus lebih besar dari jam mulai!');	
	 </script>";
}
else if($kesalahan=="jam bertubrukan")
{
	$isitanggal=$tanggal;
	$isijammulai=$jamMulai;
	$isijamselesai=$jamSelesai;
	$isimenitmulai=$menitMulai;
	$isimenitselesai=$menitSelesai;
	$isimaxmhs=$maxMhs;
	print "<script>
		alert('Jam konsultasi yang diinputkan bertubrukan dengan jadwal yang sudah ada!');	
	 </script>";
}
else if($kesalahan=="jam salah")
{
	$isitanggal=$tanggal;
	$isijammulai=$jamMulai;
	$isijamselesai=$jamSelesai;
	$isimenitmulai=$menitMulai;
	$isimenitselesai=$menitSelesai;
	$isimaxmhs=$maxMhs;
	if($isijammulai>23)
		$isijammulai="";
	if($isijamselesai>23)
		$isijamselesai="";
		
	echo "<script>
		alert('Jam tidak dapat lebih dari 23!');	
	 </script>";
}
else if($kesalahan=="menit salah")
{
	$isitanggal=$tanggal;
	$isijammulai=$jamMulai;
	$isijamselesai=$jamSelesai;
	$isimenitmulai=$menitMulai;
	$isimenitselesai=$menitSelesai;
	$isimaxmhs=$maxMhs;
	if($isimenitmulai>59)
		$isijammulai="";
	if($isimenitselesai>59)
		$isijamselesai="";
		
	echo "<script>
		alert('Menit tidak dapat lebih dari 59!');	
	 </script>";
}
else if($kesalahan=="jam harap lebih besar dari waktu sekarang")
{
	$isitanggal=$tanggal;
	$isijammulai=$jamMulai;
	$isijamselesai=$jamSelesai;
	$isimenitmulai=$menitMulai;
	$isimenitselesai=$menitSelesai;
	$isimaxmhs=$maxMhs;
	print "<script>
		alert('Waktu yang diinputkan harap lebih besar dari waktu sekarang!');	
	 </script>";
}

?>
	<div id="container">
<?php  include_once('header.php'); ?>
 
		<div id="content">

		<table border="0" align="center" width="100%">
			  <tr>
			  <td colspan="5">
			  <form action="tambahPengadaanKonsul.php" method="post" id="frm" name="frm">
			 <br/><div style="font-size:20px" align="center">TAMBAH PENGADAAN</div><br/>
						<table align="center" border="0" cellpadding="4" width="100%">
							<tr>
								<td align="right" width="45%"><strong>Tanggal</strong></td>
								<td colspan="3"><input type="text" name="StartDate" readonly="true" size="12"  id="tanggal" value="<? if(!empty($kesalahan)) echo $isitanggal; ?>" onfocus="getData('ambilHariJadwal.php?tgl='+document.getElementById('tanggal').value,'aturDiv')"> 
								<input type=button value="pilih" onClick="displayDatePicker('StartDate', this);" >
								</td>
							</tr>
							
							<tr>
								<td align="right"><strong>Pilihan Jam</strong></td>
								<td colspan="2" width="19%"><input type="radio" id="pilihan1" name="pilihan" value="1" onclick="acdeac(1);" <? if (!empty($kesalahan) && $pilihan==1) echo"checked"; //else if(empty($kesalahan)) echo"checked"; ?> >
								Jam pernah dibuat</td>
								<td  ><div id='aturDiv'>
								<?php
								if(!empty($kesalahan))
								{
									$a=strtotime($isitanggal);
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
									$today = getdate();
									$mon=str_pad($today['mon'], 2, "0",STR_PAD_LEFT);
									$day=str_pad($today['mday'], 2, "0",STR_PAD_LEFT);
									$tglskrg=$today['year']."-".$mon."-".$day;
									//echo $today;
									if($tanggal>=$tglskrg)
									{		
										$query=mysql_query("SELECT DISTINCT Hari, JamMulai, JamSelesai FROM pengadaan WHERE Hari='$hari' AND Kode='$username' ORDER BY NoPengadaan DESC, Tanggal DESC Limit 5");
										$baris=mysql_fetch_assoc($query);
										if($baris)
										{
											echo"<select id='cboJam' name='cboJam' disabled>";
											while($baris)
											{
												// onclick=\"getData('aturJam.php?nopengadaan='+document.getElementById('cboJam').value,'targetDiv')\"
												if($baris['JamMulai']."-".$baris['JamSelesai']==$kodeJam)
												{	echo"<option value='".$baris['JamMulai']."-".$baris['JamSelesai']."' selected >".$baris['Hari'].", ".$baris['JamMulai']." - ".$baris['JamSelesai']."</option>"; }
												else
												{	echo"<option value='".$baris['JamMulai']."-".$baris['JamSelesai']."'>".$baris['Hari'].", ".$baris['JamMulai']." - ".$baris['JamSelesai']."</option>"; }
									
												$baris=mysql_fetch_assoc($query);
											}
											echo"</select>";
										}
										else
										{
											echo"<select id='cboJam' name='cboJam' disabled>
											<option value=''>-</option>
											</select>";
										}
									}
									else
									{
									echo"<select id='cboJam' name='cboJam' disabled>
											<option value=''>-</option>
											</select>";
									}
								}
								else
								{
									echo "<select id='cboJam' name='cboJam' disabled>
									<option value=''>-</option>
									</select>";
								}
								?>
								
								</div>
								</td>
							</tr> 
							
							<tr>
								<td></td>
								<td colspan="2"> <input type="radio" id="pilihan2" name="pilihan" value="2" onclick="acdeac(2);" <? if (!empty($kesalahan) && $pilihan==2) echo"checked"; ?> > 
								Buat Jam Baru</td>
								<td><div id="targetDiv">
								<input type="text" name="jamMulai" maxlength="2" size="1" disabled onkeypress="return isNumber(event,this);" value="<? if(!empty($kesalahan)) echo $isijammulai; ?>" > : <input type="text" name="menitMulai" maxlength="2" size="1" disabled onkeypress="return isNumber(event,this);" value="<? if(!empty($kesalahan)) echo $isimenitmulai; ?>"> -
								<input type="text" name="jamSelesai" maxlength="2" size="1" disabled onkeypress="return isNumber(event,this);" value="<? if(!empty($kesalahan)) echo $isijamselesai; ?>"> : <input type="text" name="menitSelesai" maxlength="2" size="1" disabled onkeypress="return isNumber(event,this);" value="<? if(!empty($kesalahan)) echo $isimenitselesai; ?>"><br/><font size="-2" color="#FF0000"><i> contoh inputan jam: 09:00 - 13:00</i></font>
								</div></td>
							</tr>
							<tr>
								<td align="right"><strong>Max. Mahasiswa</strong></td>
								<td colspan="3"><input type="text" name="maxMhs" onkeypress="return isNumber(event,this);" value="<? if(!empty($kesalahan)) echo $isimaxmhs; ?>" size="2" maxlength="2" ></td>
							</tr>
							
							<tr>
								<td align="right"><br/><input type="submit" name="submit" value="Simpan"></td>
								<td colspan="3"><br/><input type="submit" name="submit" value="Batal">
								</td>
								
							</tr>
						</table>
						 <? if (!empty($kesalahan) && $pilihan == '2') 
						 {
						 	echo '<script type=text/javascript language = javascript>acdeac(2)</script>';
						 }
						 else if(!empty($kesalahan) && $pilihan == '1')
						 {echo '<script type=text/javascript language = javascript>acdeac(1)</script>';}
						 ?>
				</form>
				<br/><br/>
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