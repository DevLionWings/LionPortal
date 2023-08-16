<!DOCTYPE html>
@section('extend-css')  
@endsection
<html>
<body>
	<div class="row">
		<div class="card-body">
			@if($type == 'UP')
			<table width=725px style="border: 2px solid #000; " cellspacing="10">  
				<tr>
					<td style="text-align: left;">
						<table>  
							<tr>  
								<td rowspan="7" width=50px style="font-size:13px; border:1px solid #000;"><p rowspan="4" style="vertical-align:top; text-align: center;">Mengetahui</p> <p rowspan="3" style="vertical-align:bottom; text-align: center">HRD</p></td>
								<td style="font-size:12px;"><b>{{ $nokwitansi }}</b></td> 
							</tr>
							<tr>  
								<td style="font-size:12px;"> Telah terima dari </td>  
								<td style="font-size:12px;">  {{ $terimadari }}</td>  
							</tr>  
							<tr>  
								<td style="font-size:12px;"> Uang sejumlah </td>  
								<td style=" font-size:12px; background-color: #D8D8D8;"><b># {{ $terbilang }} #</b></td>  
							</tr>  
							<tr>  
								<td style="font-size:12px;"> Untuk pembayaran </td>  
								<td style="font-size:12px;">  {{ $keterangan }}</td>  
							</tr> 
							<tr>  
								<td style="font-size:12px;"> Lama Kerja </td>  
								<td style="font-size:12px;">  {{ $lamakerja }}</td>   	
							<tr>  
								<td style="font-size:12px;"> Tanggal Keluar </td>  
								<td style="font-size:12px;">  {{ $tglpisah }}</td>  
							</tr>   	
							<tr>  
								<td style=" vertical-align:bottom" > <p><b style=" font-size:13px; background-color: #D8D8D8;"> {{ $nominal }}</b></p></td>  
								<td style=" font-size:14px; text-align: right; vertical-align:top" >{{ $tanggal }}</td>  
							</tr>
						</table> 
					</td>
				</tr>
			</table>
		</div>
		@endif
		<div class="card-body">
			@if($type == '3' || $type == '1.5')
			<table width=725px style="border: 2px solid #000;" cellspacing="10">  
				<tr>
					<td style="text-align: left;">
						<table>  
							<tr>  
								<th rowspan="6" width=50px style="font-size:13px; border:1px solid #000;"><p rowspan="4" style="vertical-align:top; text-align: center;">Mengetahui</p> <p rowspan="3" style="vertical-align:bottom; text-align: center">HRD</p></th>
								<td style="font-size:12px;"><b>{{ $nokwitansi }}</b></td> 
							</tr>  
							<tr>  
								<td style="font-size:12px;">Telah terima dari</td>  
								<td style="font-size:12px;"><b>{{ $terimadari }}</b></td>  
							</tr>  
							<tr>  
								<td style="font-size:12px;">Uang sejumlah</td>  
								<td  style="font-size:12px; background-color: #D8D8D8;"><b># {{ $terbilang }} #</b></td>  
							</tr>  
							<tr>  
								<td style="font-size:12px;">Untuk pembayaran</td>  
								<td style="font-size:12px;">{{ $keterangan }}</td>  
							</tr> 
							<tr>  
								<td style="font-size:12px;">TGL Masuk Kembali</td>  
								<td style="font-size:12px;">{{ $tglmasuk }}</td>  
							</tr>   	
							<tr>  
								<td style="vertical-align:bottom;" > <p><b style="font-size:13px; background-color: #D8D8D8;">{{ $nominal }}</b></p></td>  
								<td style="font-size:14px; text-align: right; vertical-align:top;">{{ $tanggal }}</td>  
							</tr>
						</table> 
					</td>
				</tr>
			</table>
		</div>
		@endif
		<div class="card-body">
			@if($rapel == 'on')
			<table width=720px style="border: 2px solid #000;" cellspacing="15">  
				<tr>
					<td style="text-align: left;">
						<table >  
							<tr>  
								<td rowspan="7" width=50px style="font-size:13px; border:1px solid #000;"><p rowspan="4" style="vertical-align:top; text-align: center;">Mengetahui</p> <p rowspan="3" style="vertical-align:bottom; text-align: center">HRD</p></td>
								<td style="font-size:12px;"><b>{{ $nokwitansi }}</b></td> 
							</tr>   
							<tr>  
								<td style="font-size:12px;"> Telah terima dari </td>  
								<td style="font-size:12px;"><b>{{ $terimadari }}</b></td>  
							</tr>  
							<tr>  
								<td style="font-size:12px;"> Uang sejumlah </td>  
								<td style="font-size:12px; background-color: #D8D8D8;"> #{{ $terbilang }}#</td>  
							</tr>  
							<tr>  
								<td style="font-size:12px;"> Untuk pembayaran </td>  
								<td style="font-size:12px;">{{ $keterangan }}</td>  
							</tr> 
							<tr>  
								<td style="font-size:12px;"> Tanggal Masuk </td>  
								<td style="font-size:12px;">{{ $tglmasuk }}</td>  
							</tr>   
							<tr>
								<td></td>	
								<td style="text-align: right; font-size:12px; vertical-align:bottom;"><b>{{ $tanggal }}</b></td>
							</tr>
							<tr>  
								<td style="vertical-align:bottom;" ><b style="font-size:13px; background-color: #D8D8D8;">{{ $nominal }}</b></td>  
								<td style="vertical-align:bottom;"><b style="font-size:13px; background-color: #D8D8D8;">Selisih {{ $selisih }}</b></td>  
								
							</tr>
						</table> 
					</td>
				</tr>
			</table>
			@endif
		</div>
		<div class="card-body">
			@if($type == 'TJ001' || $type == 'TJ002' || $type == 'TJ003')
			<table width=725px style="border: 2px solid #000;" cellspacing="15">  
				<tr>
					<td  style="text-align: left;">
						<table>  
							<tr>  
								<td rowspan="5" width=50px style="font-size:13px; border:1px solid #000;"><p rowspan="4" style="vertical-align:top; text-align: center;">Mengetahui</p> <p rowspan="3" style="vertical-align:bottom; text-align: center">HRD</p></td>
								<td style="font-size:12px;"><b>{{ $nokwitansi }}</b></td> 
							</tr>  
							<tr>  
								<td style="font-size:12px;"> Telah terima dari </td>  
								<td>{{ $terimadari }}</td>  
							</tr>  
							<tr>  
								<td style="font-size:12px;"> Uang sejumlah </td>  
								<td style="background-color: #D8D8D8;"> # {{ $terbilang }} #</td>  
							</tr>  
							<tr>  
								<td style="font-size:12px;"> Untuk pembayaran </td>  
								<td>{{ $keterangan }}</td>  
							</tr> 	
							<tr>  
								<td style="vertical-align:bottom" > <p><b style="font-size:13px; background-color: #D8D8D8;">{{ $nominal }}</b></p></td>  
								<td style="font-size:14px; text-align: right; vertical-align:top">{{ $tanggal }}</td>  
							</tr>
						</table> 
					</td>
				</tr>
			</table>
			@endif
		</div>
	</div>
</body>
</html>	