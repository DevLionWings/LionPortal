@extends('email.layout')

@section('title')
Helpdesk Ticket
@endsection

@section('content')

@if($mailData['statusid'] == 'SD006')
    <p style="font-weight: bold;">Halo, {{ $mailData['username'] }}</p>
    <p>your ticket has been created, here is the detail : </p>
@endif

@if($mailData['statusid'] == 'SD001')
    <p style="font-weight: bold;">Halo, {{ $mailData['assigned_to'] }}</p>
    <p>this ticket need your approval, please open helpdesk website : </p>
@endif

@if($mailData['statusid'] == 'SD002')
    <p style="font-weight: bold;">Halo, {{ $mailData['assigned_to'] }}</p>
    <p>this ticket assigned to you, here is the detail :</p>
@endif

@if($mailData['statusid'] == 'SD003')
    <p style="font-weight: bold;">Halo, {{ $mailData['username'] }}</p>
    <p>this ticket has been closed, here is the detail : </p>
@endif

@if($mailData['statusid'] == 'SD005')
    <p style="font-weight: bold;">Halo, {{ $mailData['username'] }}</p>
    <p>this ticket has been rejected, here is the detail : </p>
@endif

<table style="text-align: left;">
    <tr>
        <td style="text-align: left;">TicketNo</td>
        <td style="text-align: left;">: {{ $mailData['ticketno'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left;">Category</td>
        <td style="text-align: left;">: {{ $mailData['categoryname'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left;">Priority</td>
        <td style="text-align: left;">: {{ $mailData['priorityname'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left;">Subject</td>
        <td style="text-align: left;">: {{ $mailData['subject'] }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;" >Detail</td>
        <td style="text-align: left;">: {{ $mailData['detail'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left;">Status</td>
        <td style="text-align: left;">: {{ $mailData['status'] }}</td>
    </tr>
    @if($mailData['note'] != '')
    <tr>
        <td style="vertical-align:top;">Remark</td>
        <td style="text-align: left;">: {{ $mailData['note'] }}</td>
    </tr>
    @endif
    <tr>
        <td style="text-align: left;">AssignTo</td>
        <td style="text-align: left;">: {{ $mailData['assigned_to'] }}</td>
    </tr>
    
</table>

<hr>
<p>Please Do Not Reply This Email</p>
@endsection