@extends('email.layoutbooking')

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
Room Meeting
@endsection

@section('content')

<p>{{ $mailData['bookingname'] }} detail your booking room  : </p>

<table style="text-align: left;">
    <tr>
        <td style="text-align: left;">Booking Id</td>
        <td>:&nbsp;&nbsp;{{ $mailData['bookid'] }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Subject</td>
        <td>:&nbsp;&nbsp;{{ $mailData['subject'] }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Description</td>
        <td>:&nbsp;&nbsp;{{ $mailData['desc'] }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Date</td>
        <td>:&nbsp;&nbsp;{{ $mailData['date'] }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Start Time</td>
        <td>:&nbsp;&nbsp;{{ $mailData['starttime'] }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">End Time</td>
        <td>:&nbsp;&nbsp;{{ $mailData['endtime'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left;">Booking Name</td>
        <td>:&nbsp;&nbsp;{{ $mailData['bookingname'] }}</td>
    </tr>
</table>

<hr>
<p>Please Do Not Reply This Email</p>
@endsection