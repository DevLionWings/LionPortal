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
    <p>{{ $mailData['username'] }} this ticket need your approval, please open helpdesk website : </p>
@endif

@if($mailData['statusid'] == 'SD002')
    <p style="font-weight: bold;">Halo, {{ $mailData['assigned_to'] }}</p>
    <p>{{ $mailData['username'] }} has been assigned this ticket  to you, here is the detail :</p>
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
    <tbody>
        <tr>
            <td>Ticket No</td>
            <td>: {{ $mailData['ticketno'] }}</td>
        </tr>
        <tr>
            <td>Category</td>
            <td>: {{ $mailData['categoryname'] }}</td>
        </tr>
        <tr>
            <td>Priority</td>
            <td>: {{ $mailData['priorityname'] }}</td>
        </tr>
        <tr>
            <td>Subject</td>
            <td>: {{ $mailData['subject'] }}</td>
        </tr>
        <tr>
            <td>Detail</td>
            <td>: {{ $mailData['detail'] }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: {{ $mailData['status'] }}</td>
        </tr>
        <tr>
            <td>Remark</td>
            <td>: {{ $mailData['note'] }}</td>
        </tr>

        <tr>
            <td>Assigned To</td>
            <td>: {{ $mailData['assigned_to'] }}</td>
        </tr>
    </tbody>
</table>

<hr>
<p>Please Do Not Reply This Email</p>
@endsection