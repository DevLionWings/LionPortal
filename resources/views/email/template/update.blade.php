@extends('email.layout')

@section('title')
Helpdesk Ticket
@endsection

@section('content')

<p style="font-weight: bold;">Halo, {{ $mailData['createdname'] }}</p>
<p>your ticket has been updated, here is the detail : </p>

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
    <tr>
        <td style="vertical-align:top;" >Comment</td>
        <td style="text-align: left;">: {{ $mailData['comment'] }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Remark</td>
        <td style="text-align: left;">: {{ $mailData['note'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left;">AssignTo</td>
        <td style="text-align: left;">: {{ $mailData['assigned_to'] }}</td>
    </tr>
    
</table>

<hr>
<p>Please Do Not Reply This Email</p>
@endsection