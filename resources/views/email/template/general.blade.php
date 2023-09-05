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

<table style="text-align: top;" cellspacing="10">
    <tbody>
        <tr>
            <td style="text-align: top;">Ticket No :</td>
            <td>: {{ $mailData['ticketno'] }}</td>
        </tr>
        <tr>
            <td style="text-align: top;">Category</td>
            <td>: {{ $mailData['categoryname'] }}</td>
        </tr>
        <tr>
            <td style="text-align: top;">Priority</td>
            <td>: {{ $mailData['priorityname'] }}</td>
        </tr>
        <tr>
            <td style="text-align: top;">Subject</td>
            <td>: {{ $mailData['subject'] }}</td>
        </tr>
        <tr>
            <td style="text-align: top;">Detail</td>
            <td>: {{ $mailData['detail'] }}</td>
        </tr>
        <tr>
            <td style="text-align: top;">Status</td>
            <td>: {{ $mailData['status'] }}</td>
        </tr>
        @if($mailData['note'] != '')
        <tr>
            <td style="text-align: top;">Remark</td>
            <td>: {{ $mailData['note'] }}</td>
        </tr>
        @endif
        <tr>
            <td style="text-align: top;">Assign To</td>
            <td>: {{ $mailData['assigned_to'] }}</td>
        </tr>
    </tbody>
</table>

<hr>
<p>Please Do Not Reply This Email</p>
@endsection