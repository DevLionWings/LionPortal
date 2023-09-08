@extends('email.layout')

@push('styles')
<style>
    div.status {
        display: inline-block;
        border-radius: 100%;
        padding: 8px 14px;
        font-size: 12px;
        text-align: center;
        font-weight: bold;
        color: white;
    }

    .status.success {
        background-color: #49D198;
    }

    .status.danger {
        background-color: #F85359;
    }

    .status.warning {
        background-color: #EFB403;
    }

    .status.muted {
        background-color: #6e6e6e;
    }
</style>
@endpush

@section('title')
Helpdesk Ticket
@endsection

@section('content')

<p>{{ $mailData['assigned_to'] }} Your ticket has a new comment  : </p>

<table style="text-align: left;">
    <tr>
        <td style="text-align: left;">TicketNo</td>
        <td>:&nbsp;&nbsp;{{ $mailData['ticketno'] }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Detail</td>
        <td>:&nbsp;&nbsp;{{ $mailData['detail'] }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Comment</td>
        <td>:&nbsp;&nbsp;{{ $mailData['comment'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left;">AssignTo</td>
        <td>:&nbsp;&nbsp;{{ $mailData['assigned_to'] }}</td>
    </tr>
</table>

<hr>
<p>Please Do Not Reply This Email</p>
@endsection