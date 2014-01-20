<?php
session_start();
session_unset();
print "<script>
		alert('Anda telah keluar!');	
	   </script>";
print "<script>
		window.location='index.php';	
	   </script>";
exit();
?>