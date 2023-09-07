@section('extend-css')  
@endsection
<html>
<body>
	@foreach($alldata as $data)
		@if($data->type == 'UangPisah')
		<table width=720px style="border: 2px solid #000; " cellspacing="10">  
			<tr>
				<td style="text-align: left;">
					<table>  
						<tr>  
							<td rowspan="8" width=70px style="vertical-align:bottom; text-align: center; font-size:13px; border:1px solid #000;"><hr><b>HRD</b></td>
							<td style="font-size:12px;"><b>&nbsp;&nbsp;&nbsp;{{ $data->nokwitansi }}</b></td> 
						</tr>
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Telah terima dari </td>  
							<td style="font-size:12px;">  {{ $data->terimadari }}</td>  
						</tr>  
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Uang sejumlah </td>  
							<td style="font-size:12px; background-color: #D8D8D8;"><b># {{ $data->terbilang }} #</b></td>  
						</tr>  
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Untuk pembayaran </td>  
							<td style="font-size:12px;">  {{ $data->keterangan }}</td>  
						</tr> 
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Lama kerja </td>  
							<td style="font-size:12px;">  {{ $data->lamakerja }}</td>   	
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Tanggal keluar </td>  
							<td style="font-size:12px;">  {{ $data->tglpisah }}</td>  
						</tr>  
						<tr>
							<td></td>	
							<td style="text-align: right; font-size:13px; vertical-align:bottom;">{{ $data->tanggal }}</td>
						</tr> 	
						<tr>  
							<td style="vertical-align:bottom; font-size:12px;" >Rp <b style="text-align: right; font-size:15px; background-color: #D8D8D8;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data->nominal }}</b></td>
						</tr>
					</table> 
				</td>
			</tr>
		</table>
		<table width=720px>
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		@endif
		@if($data->type == '3' || $data->type == '1.5')
		<table width=720px style="border: 2px solid #000;" cellspacing="10">  
			<tr>
				<td style="text-align: left;">
					<table>  
						<tr>  
							<td rowspan="8" width=70px style="vertical-align:bottom; text-align: center; font-size:13px; border:1px solid #000;"><hr><b>HRD</b></td>
							<td style="font-size:12px;"><b>&nbsp;&nbsp;&nbsp;{{ $data->nokwitansi }}</b></td> 
							<td style="text-align: right; font-size:8px;">{{ $data->periode }} </td> 
						</tr>   
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Telah terima dari</td>  
							<td style="font-size:12px;"><b>{{ $data->terimadari }}</b></td>  
						</tr>  
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Uang sejumlah</td>  
							<td  style="font-size:12px; background-color: #D8D8D8;"><b># {{ $data->terbilang }} #</b></td>  
						</tr>  
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Untuk pembayaran</td>  
							<td style="font-size:12px;">{{ $data->keterangan }}</td>  
						</tr> 
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Tangggal masuk</td>  
							<td style="font-size:12px;">{{ $data->tglmasuk }}</td>  
						</tr>   
						<tr>
							<td></td>	
							<td style="text-align: right; font-size:13px; vertical-align:bottom;">{{ $data->tanggal }}</td>
						</tr>	
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
						</tr> 
						<tr>  
							<td style="vertical-align:bottom; font-size:12px;" >Rp<b style="text-align: right; font-size:15px; background-color: #D8D8D8;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data->nominal }}</b></td>  
						</tr>
					</table> 
				</td>
			</tr>
		</table>
		<table width=720px>
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		@endif
		@if($data->type == 'on')
		<table width=720px style="border: 2px solid #000;" cellspacing="10">  
			<tr>
				<td style="text-align: left;">
					<table >  
						<tr>  
							<td rowspan="8" width=70px style="vertical-align:bottom; text-align: center; font-size:13px; border:1px solid #000;"><hr><b>HRD</b></td>
							<td style="font-size:12px;"><b>&nbsp;&nbsp;&nbsp;{{ $data->nokwitansi }}</b></td> 
							<td style="text-align: right; font-size:8px;">{{ $data->periode }} </td>
						</tr>   
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Telah terima dari </td>  
							<td style="font-size:12px;"><b>{{ $data->terimadari }}</b></td>  
						</tr>  
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Uang sejumlah </td>  
							<td style="font-size:12px; background-color: #D8D8D8;"><b># {{ $data->terbilang }} #</b></td>  
						</tr>  
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Untuk pembayaran </td>  
							<td style="font-size:12px;">{{ $data->keterangan }}</td>  
						</tr> 
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Tanggal masuk </td>  
							<td style="font-size:12px;">{{ $data->tglmasuk }}</td>  
						</tr>   
						<tr>
							<td></td>	
							<td style="text-align: right; font-size:13px; vertical-align:bottom;">{{ $data->tanggal }}</td>
						</tr>
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
						</tr> 
						<tr>  
						<td style="vertical-align:bottom; font-size:12px;" >Rp<b style="text-align: right; font-size:15px; background-color: #D8D8D8;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data->nominal }}</b></td>
							<td style="vertical-align:bottom; font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selisih Rp <b style="font-size:15px; background-color: #D8D8D8;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $data->selisih }}</b></td>  
						</tr>
					</table> 
				</td>
			</tr>
		</table>
		<table width=720px >
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		@endif
		@if($data->type == 'DUKA' || $data->type == 'PERNIKAHAN' || $data->type == 'KELAHIRAN')
		<table width=720px style="border: 2px solid #000;" cellspacing="10">  
			<tr>
				<td  style="text-align: left;">
					<table>  
						<tr>  
							<td rowspan="8" width=70px style="vertical-align:bottom; text-align: center; font-size:13px; border:1px solid #000;"><hr><b>HRD</b></td>
							<td style="font-size:12px;"><b>&nbsp;&nbsp;&nbsp;{{ $data->nokwitansi }}</b></td> 
						</tr>  
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Telah terima dari </td>  
							<td style="font-size:12px;">{{ $data->terimadari }}</td>  
						</tr>  
						<tr>  
							<td style="font-size:14px;">&nbsp;&nbsp;&nbsp;Uang sejumlah </td>  
							<td style="font-size:14px; background-color: #D8D8D8;"><b># {{ $data->terbilang }} # &nbsp;&nbsp;&nbsp;<b></td>  
						</tr>  
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;Untuk pembayaran &nbsp;&nbsp;&nbsp;</td>  
							<td style="font-size:12px;">{{ $data->keterangan }}</td>  
						</tr> 
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
						</tr>
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
						</tr> 	
						<tr>  
							<td style="font-size:12px;">&nbsp;&nbsp;&nbsp;</td>  
							<td style="font-size:13px; text-align: right; vertical-align:bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data->tanggal }}</td>    
						</tr> 
						<tr>  
							<td style="vertical-align:bottom; font-size:12px;" >Rp<b style="text-align: right; font-size:15px; background-color: #D8D8D8;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data->nominal }}</b></td>
						</tr>
					</table> 
				</td>
			</tr>
		</table>
		<table width=720px >
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		@endif
	@endforeach
</body>
</html>	