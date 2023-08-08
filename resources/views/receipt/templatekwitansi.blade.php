<!DOCTYPE html>  
 <html>   
 	<body>   
	    @if($type == 'UP')
		<table width="550" cellpadding="5" cellspacing="5" style="border: 1px solid #000;">  
			<tr>
				<td align="left">
					<table width="500" cellpadding="0" cellspacing="1">  
						<tr>  
							<td rowspan="9" valign="top" width="100" align="center" style="border:1px solid #000;">
							Mengetahui HRD</td>  
							<td width="100" valign="top" > <h4>{{ $nokwitansi }}</h4></td>  
							<td valign="top" ></td>  
						</tr>  
						<tr>  
							<td valign="top" > Telah terima dari </td>  
							<td valign="top"> : 	{{ $terimadari }}</td>  
						</tr>  
						<tr>  
							<td valign="top" > Uang sejumlah </td>  
							<td valign="top" style="background-color: grey;"> : 	# {{ $terbilang }} #</td>  
						</tr>  
						<tr>  
							<td valign="top" > Untuk pembayaran </td>  
							<td valign="top" > : 	{{ $keterangan }}</td>  
						</tr> 
						<tr>  
							<td valign="top" > Lama Kerja </td>  
							<td valign="top" > : 	{{ $lamakerja }}</td>   	
						<tr>  
							<td valign="top" > Tanggal Keluar </td>  
							<td valign="top" > : 	{{ $tglpisah }}</td>  
						</tr>   	
						<tr>  
							<td valign="bottom" > <h2 style="background-color: grey;"> {{ $nominal }}</h2></td>  
							<td valign="top" align="right" height="100"><h4>{{ $tanggal }}</h4></td>  
						</tr>
					</table> 
				</td>
			</tr>
        </table>
		@endif
		<table>
		</table>
		@if($type == '3' || $type == '1.5')
		<table width="550" cellpadding="5" cellspacing="5" style="border: 1px solid #000;">  
			<tr>
				<td align="left">
					<table width="500" cellpadding="0" cellspacing="1">  
						<tr>  
							<td rowspan="6" valign="top" width="100" align="center" style="border:1px solid #000;">
							Mengetahui HRD</td>  
							<td width="100" valign="top" > <h4>{{ $nokwitansi }}</h4></td>  
							<td valign="top" ></td>  
						</tr>  
						<tr>  
							<td valign="top" > Telah terima dari </td>  
							<td valign="top"> : 	{{ $terimadari }}</td>  
						</tr>  
						<tr>  
							<td valign="top" > Uang sejumlah </td>  
							<td valign="top" style="background-color: grey;"> : 	# {{ $terbilang }} #</td>  
						</tr>  
						<tr>  
							<td valign="top" > Untuk pembayaran </td>  
							<td valign="top" > : 	{{ $keterangan }}</td>  
						</tr> 
						<tr>  
							<td valign="top" > Tanggal Masuk </td>  
							<td valign="top" > : 	{{ $tglmasuk }}</td>  
						</tr>   	
						<tr>  
							<td valign="bottom" > <h2 style="background-color: grey;"> {{ $nominal }}</h2></td>  
							<td valign="top" align="right" height="100"><h4>{{ $tanggal }}</h4></td>  
						</tr>
					</table> 
				</td>
			</tr>
        </table>
		@endif
		<table>
		</table>
		@if($rapel == 'on')
		<table width="550" cellpadding="5" cellspacing="5" style="border: 1px solid #000;">  
			<tr>
				<td align="left">
					<table width="500" cellpadding="0" cellspacing="1">  
						<tr>  
							<td rowspan="6" valign="top" width="100" align="center" style="border:1px solid #000;">
							Mengetahui HRD</td>  
							<td width="100" valign="top" > <h4>{{ $nokwitansi }}</h4></td>  
							<td valign="top" ></td>  
						</tr>  
						<tr>  
							<td valign="top" > Telah terima dari </td>  
							<td valign="top"> : 	{{ $terimadari }}</td>  
						</tr>  
						<tr>  
							<td valign="top" > Uang sejumlah </td>  
							<td valign="top" style="background-color: grey;"> : 	# {{ $terbilang }} #</td>  
						</tr>  
						<tr>  
							<td valign="top" > Untuk pembayaran </td>  
							<td valign="top" > : {{ $keterangan }}</td>  
						</tr> 
						<tr>  
							<td valign="top" > Tanggal Masuk </td>  
							<td valign="top" > : 	{{ $tglmasuk }}</td>  
						</tr>   	
						<tr>  
							<td valign="bottom"> <h2 style="background-color: grey;"> {{ $nominal }}</h2></td>
							<td valign="bottom"><h2 style="background-color: grey;">Selisih : {{ $selisih }}</h2></td>  
							<td valign="top" align="right" height="100"><h4>{{ $tanggal }}</h4></td>  
						</tr>
						<tr>
							
						</tr>
					</table> 
				</td>
			</tr>
        </table>
		@endif
		<table>
		</table>
		@if($type == 'TJ001' || $type == 'TJ002' || $type == 'TJ003')
		<table width="550" cellpadding="5" cellspacing="5" style="border: 1px solid #000;">  
			<tr>
				<td align="left">
					<table width="500" cellpadding="0" cellspacing="1">  
						<tr>  
							<td rowspan="5" valign="top" width="100" align="center" style="border:1px solid #000;">
							Mengetahui HRD</td>  
							<td width="100" valign="top" > <h4>{{ $nokwitansi }}</h4></td>  
							<td valign="top" ></td>  
						</tr>  
						<tr>  
							<td valign="top" > Telah terima dari </td>  
							<td valign="top"> : 	{{ $terimadari }}</td>  
						</tr>  
						<tr>  
							<td valign="top" > Uang sejumlah </td>  
							<td valign="top" style="background-color: grey;"> : 	# {{ $terbilang }} #</td>  
						</tr>  
						<tr>  
							<td valign="top" > Untuk pembayaran </td>  
							<td valign="top" > : 	{{ $keterangan }}</td>  
						</tr> 	
						<tr>  
							<td valign="bottom" > <h2 style="background-color: grey;"> {{ $nominal }}</h2></td>  
							<td valign="top" align="right" height="100"><h4>{{ $tanggal }}</h4></td>  
						</tr>
					</table> 
				</td>
			</tr>
        </table>
		@endif
 	</body>   
 </html>   
