<?php
	include("config.php");
	$tipe=$_GET['tipe'];
	if($tipe==5 || $tipe==4 || $tipe==3 || $tipe==1 || $tipe==7)
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
	else if($tipe==2)
	{
		echo" <table align='center' border='0' cellpadding='4'>
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
								<td><strong>Tanggal awal</strong></td>
								<td>"; ?>
								<input type="text" name="tglMulai" readonly="true" size="12" id="tglMulai"> 
								<input type=button value="pilih" onClick="displayDatePicker('tglMulai', this);" >
								<? echo"
								</td>
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