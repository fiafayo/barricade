<? 
	function cekInteger($string)
	{
		$i=0;
		$cek=true;
		while($i<strlen($string))
		{
			if($string[$i]>='0' && $string[$i]<='9')
			{
				$i++;
			}
			else
			{
				$cek=false;
				break;
			}
		}
		return $cek;
	}
?>