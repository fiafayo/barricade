<?php
class DataFormatter{
  const SMALL_NUMBER=0.000001;
  public static function getMonthNames(){
    $names=array(
      1=>'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'Nopember',
      'Desember'
    );
    return $names;
  }
  public static function getShortMonthNames(){
    $names=array(
      1=>'Jan',
      'Feb',
      'Mar',
      'Apr',
      'Mei',
      'Jun',
      'Jul',
      'Agt',
      'Sep',
      'Okt',
      'Nop',
      'Des'
    );
    return $names;
  }
  public static function getDayNames(){
    $names=array(
      0=>'Minggu',
      'Senin',
      'Selasa',
      'Rabu',
      'Kamis',
      'Jumat',
      'Sabtu'
    );
    return $names;
  }
  public static function getMonthName($bulan=null){
    if (!$bulan) $bulan=date('m');
    $bulan=intval($bulan);
    $names=self::getMonthNames();
    $name='';
    if ( array_key_exists($bulan,$names) ) $name=$names[$bulan];
    return $name;
  }
  public static function getShortMonthName($bulan=null){
    if (!$bulan) $bulan=date('m');
    $bulan=intval($bulan);
    $names=self::getShortMonthNames();
    $name='';
    if ( array_key_exists($bulan,$names) ) $name=$names[$bulan];
    return $name;
  }

  public static function stripCommas($val){
    $return=str_replace(',','',$val);
    //$return=str_replace(',','.',$return);
    return $return;
  }

  public static function addCommas($val,$digit=0){
    return number_format($val,$digit,'.',',');
  }
  public static function generateId($prefix='PR')
  {
    $now = time();
    $y = self::convert2Id(date("Y",$now) % 100) ;
    $m = self::convert2Id(date('m',$now));
    $d = self::convert2Id(date('d',$now)) ;
    $h = self::convert2Id(date('G',$now));
    $n = self::convert2Id(date('i',$now));
    $s = self::convert2Id(date('s',$now));
    $time = explode(' ',microtime());
    $ms = intval($time[0] * 10000);
    $id = $prefix.$y.$m.$d.$h.$n.$s.$ms;
    return $id;
  }

  public static function convert2Id($num) {
    $n = intval($num);
    if ($n<=9) $d = chr (  ord('0') + $n  );
    else if ($n<=35) $d = chr (  ord('A') + $n - 10 );
    else $d= chr ( ord('a') + $n - 36);
    return $d;
  }
  
  public static function formatTanggal($sqlDate='1977-06-15') {
      $angkas=explode('-',$sqlDate);
      $tgl=intval($angkas[2]);
      $bln=intval($angkas[1]);
      $thn=intval($angkas[0]);
      
      return $tgl.'/'.self::getShortMonthName($bln).'/'.$thn;
      
  }

}