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

@if($mailData['statusid'] == 'SD006')
    <p style="font-weight: bold;">Halo, {{ $mailData['username'] }}</p>
    <p>Hi, your ticket has been created, here is the detail : </p>
@endif

@if($mailData['statusid'] == 'SD001')
    <p style="font-weight: bold;">Halo, {{ $mailData['assigned_to'] }}</p>
    <p>Hi, this ticket need your approval, please open helpdesk website : </p>
@endif

@if($mailData['statusid'] == 'SD002')
    <p style="font-weight: bold;">Halo, {{ $mailData['assigned_to'] }}</p>
    <p>Hi, this ticket has been assigned to you, here is the detail :</p>
@endif

@if($mailData['statusid'] == 'SD003')
    <p style="font-weight: bold;">Halo, {{ $mailData['username'] }}</p>
    <p>Hi, this ticket has been closed, here is the detail : </p>
@endif

@if($mailData['statusid'] == 'SD005')
    <p style="font-weight: bold;">Halo, {{ $mailData['username'] }}</p>
    <p>Hi, this ticket has been rejected, here is the detail : </p>
@endif

<table style="margin-bottom: 5px;">
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
            <td>Assigned To</td>
            <td>: {{ $mailData['assigned_to'] }}</td>
        </tr>
    </tbody>
</table>

<hr>
<p>Please Do Not Reply This Email</p>
@endsection