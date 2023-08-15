<!DOCTYPE html>  
 
	    @if($type == 'UP')
		<table width=720px style="border: 3px solid #000;" cellspacing="15">  
			<tr>
				<td style="text-align: left;">
					<table>  
						<tr>  
							<td rowspan="7" width=100px height=10px style="border:2px solid #000;"><h4 style="text-align: center; vertical-align:top">Mengetahui</h4><h4 style="text-align: center; vertical-align:bottom">HRD</h4></td>  
							<td><h4>{{ $nokwitansi }}</h4></td>  
						</tr>  
						<tr>  
							<td width="100"> Telah terima dari </td>  
							<td> : {{ $terimadari }}</td>  
						</tr>  
						<tr>  
							<td width="100"> Uang sejumlah </td>  
							<td style="background-color: #D8D8D8;"> : # {{ $terbilang }} #</td>  
						</tr>  
						<tr>  
							<td width="100"> Untuk pembayaran </td>  
							<td> : {{ $keterangan }}</td>  
						</tr> 
						<tr>  
							<td width="100"> Lama Kerja </td>  
							<td> : {{ $lamakerja }}</td>   	
						<tr>  
							<td width="100"> Tanggal Keluar </td>  
							<td> : {{ $tglpisah }}</td>  
						</tr>   	
						<tr>  
							<td style="text-align: right; vertical-align:bottom" > <h2 style="background-color: #D8D8D8;"> {{ $nominal }}</h2></td>  
							<td style="text-align: right; vertical-align:top" height="80"><h4>{{ $tanggal }}</h4></td>  
						</tr>
					</table> 
				</td>
			</tr>
        </table>
		@endif
		@if($type == '3' || $type == '1.5')
		<table width=720px style="border: 3px solid #000;" cellspacing="15">  
			<tr>
				<td style="text-align: left;">
					<table>  
						<tr>  
							<td rowspan="7" width=100px height=10px style="border:2px solid #000;"><h4 style="text-align: center; vertical-align:top">Mengetahui</h4><h4 style="text-align: center; vertical-align:bottom">HRD</h4></td>  
							<td><h4>{{ $nokwitansi }}</h4></td> 
						</tr>  
						<tr>  
							<td width="100"> Telah terima dari </td>  
							<td> : 	{{ $terimadari }}</td>  
						</tr>  
						<tr>  
							<td width="100"> Uang sejumlah </td>  
							<td  style="background-color: #D8D8D8;"> : 	# {{ $terbilang }} #</td>  
						</tr>  
						<tr>  
							<td width="100"> Untuk pembayaran </td>  
							<td> : 	{{ $keterangan }}</td>  
						</tr> 
						<tr>  
							<td width="100"> Tanggal Masuk </td>  
							<td> : 	{{ $tglmasuk }}</td>  
						</tr>   	
						<tr>  
							<td style="text-align: right; vertical-align:bottom" > <h2 style="background-color: #D8D8D8;"> {{ $nominal }}</h2></td>  
							<td style="text-align: right; vertical-align:top" height="80"><h4>{{ $tanggal }}</h4></td>  
						</tr>
					</table> 
				</td>
			</tr>
        </table>
		@endif
		<table>
		</table>
		@if($rapel == 'on')
		<table width=720px style="border: 3px solid #000;" cellspacing="15">  
			<tr>
				<td style="text-align: left;">
					<table >  
						<tr>  
							<td rowspan="7" width=100px height=10px style="border:2px solid #000;"><h4 style="text-align: center; vertical-align:top">Mengetahui</h4><h4 style="text-align: center; vertical-align:bottom">HRD</h4></td>  
							<td><h4>{{ $nokwitansi }}</h4></td>  
						</tr>  
						<tr>  
							<td width="100"> Telah terima dari </td>  
							<td> : 	{{ $terimadari }}</td>  
						</tr>  
						<tr>  
							<td width="100"> Uang sejumlah </td>  
							<td style="background-color: #D8D8D8;"> : 	# {{ $terbilang }} #</td>  
						</tr>  
						<tr>  
							<td width="100"> Untuk pembayaran </td>  
							<td> : {{ $keterangan }}</td>  
						</tr> 
						<tr>  
							<td width="100"> Tanggal Masuk </td>  
							<td> : 	{{ $tglmasuk }}</td>  
						</tr>   
						<tr>	
							<td></td>
							<td style="text-align: right;"><h4>{{ $tanggal }}</h4></td>
						</tr>
						<tr>  
							<td style="text-align: right; vertical-align:bottom"> <h2 style="background-color: #D8D8D8;"> {{ $nominal }}</h2></td>  
							<td style="vertical-align:bottom"><h2 style="background-color: #D8D8D8;">Selisih : {{ $selisih }}</h2></td>
							
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
		<table width=720px style="border: 3px solid #000;" cellspacing="15">  
			<tr>
				<td  style="text-align: left;">
					<table>  
						<tr>  
							<td rowspan="5" width=100px height=10px style="border:2px solid #000;"><h4 style="text-align: center; vertical-align:top">Mengetahui</h4><h4 style="text-align: center; vertical-align:bottom">HRD</h4></td>  
							<td><h4>{{ $nokwitansi }}</h4></td>  
						</tr>  
						<tr>  
							<td width="100"> Telah terima dari </td>  
							<td> : 	{{ $terimadari }}</td>  
						</tr>  
						<tr>  
							<td width="100" > Uang sejumlah </td>  
							<td style="background-color: #D8D8D8;"> : 	# {{ $terbilang }} #</td>  
						</tr>  
						<tr>  
							<td width="100" > Untuk pembayaran </td>  
							<td> : 	{{ $keterangan }}</td>  
						</tr> 	
						<tr>  
							<td style="text-align: right; vertical-align:bottom" > <h2 style="background-color: #D8D8D8;"> {{ $nominal }}</h2></td>  
							<td style="text-align: right; vertical-align:top" height="80"><h4>{{ $tanggal }}</h4></td>   
						</tr>
					</table> 
				</td>
			</tr>
        </table>
		@endif
 