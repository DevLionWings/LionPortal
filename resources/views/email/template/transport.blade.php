@extends('email.layout')

@section('title')
Helpdesk Ticket
@endsection

@section('content')

<p>{{ $mailData['sendtoname'] }}, Transport Detail : </p>

<table style="text-align: left;">
    <tr>
        <td style="text-align: left; vertical-align:top;">Ticket No &nbsp;&nbsp;</td>
        <td>:&nbsp;&nbsp;{{ $mailData['ticketno'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left; vertical-align:top;">TransportId</td>
        <td>:&nbsp;&nbsp;{{ $mailData['transportid'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left; vertical-align:top;">TransportNo</td>
        <td>:&nbsp;&nbsp;{{ $mailData['transno'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left; vertical-align:top;">Sender &nbsp;&nbsp;</td>
        <td>:&nbsp;&nbsp;{{ $mailData['sendername'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left; vertical-align:top;">Status &nbsp;&nbsp;</td>
        <td>:&nbsp;&nbsp;{{ $mailData['status'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left; vertical-align:top;">Remark</td>
        <td>:&nbsp;&nbsp;{{ $mailData['remark'] }}</td>
    </tr>
</table>

<hr>
<p>Please Do Not Reply This Email</p>
@endsection