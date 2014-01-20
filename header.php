<?phP
$username = isset($_SESSION['usernamee']) ? $_SESSION['usernamee'] : null;
$namaUser = isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : null;
$pesan = isset($_SESSION['pesan']) ? $_SESSION['pesan'] : null;

?>
		<div id="header"><img src="images/b.png" align="right" style="margin:0px 50px 0px 0px" />
			<h1>Academic Advisor Universitas Surabaya</h1>
		</div>
		<table border="0" width="100%" bgcolor="#f5f5f5">
			<tr bgcolor="#A3ADF8">
				<td width="12%" align="center"  height="25"><a href="advisor.php">Advisor</a></td>
				<td width="12%"align="center"  height="25"> <a href="faq.php">FAQ's</a></td>
				<td> </td>
			</tr>
		</table>
		
<div align="right">Selamat datang <font color="blue"><?php print "$namaUser ($username)"; ?></font> , <a href="profile.php">ubah</a>&nbsp;|&nbsp;<a href="ubahPassword.php">password</a>&nbsp;|&nbsp;<a href="logout.php">keluar</a></div><hr />
<?php
if ($pesan) :
    ?>

<div id="flash_pesan" style="color:#800; background-color: #1e90ff; width: 80%; margin: 10px; border: 2px ridge; padding:4px;">
  <?php echo $pesan; $_SESSION['pesan']='';  ?>  
</div>
<?php
endif;
?>