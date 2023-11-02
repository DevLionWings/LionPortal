@extends('parent.master')
@section('extend-css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('image-upload/image-uploader.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bootstrap/bootstrap.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection
@section('body')
<!-- Site wrapper -->
<div class="content-wrapper" style="min-height: 278px;">
    <!-- Navbar -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Ticket All</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <div class="float-sm-right">
                                <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#modal-add-ticket"><i class="fa fa-plus" aria-hidden="true"></i> New Ticket</button>
                                <!-- <form id="formData" name="formData" method="get" action="{{ route('add.form') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">+ Tambah Ticket</button> -->
                            <!-- </form> -->
                            </div>
                            <div class="row align-items-end">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Requestor :</label>
                                        <div class="input-group value">
                                            <select id="requestor" name="requestor" class="select2" style="width: 100%;">
                                            <option value="10"> all</option>
                                                @foreach($usreq as $usreqcode)
                                                <option value="{{ $usreqcode['ID'] }}">{{ $usreqcode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Ticket :</label>
                                        <div class="input-group value">
                                            <select id="ticketno" name="ticketno" class="form-control input--style-6">
                                            <option value="HLP"> all</option>
                                                @foreach($tick as $tickcode)
                                                <option value="{{ $tickcode['ID'] }}">{{ $tickcode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Assign To :</label>
                                        <div class="input-group value">
                                            <select id="assignto" name="assignto" class="form-control input--style-6">
                                                <option value="10"> all</option>
                                                @foreach($assn as $assncode)
                                                <option value="{{ $assncode['ID'] }}">{{ $assncode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Status :</label>
                                        <div class="input-group value">
                                            <select id="status" name="status" class="form-control input--style-6">
                                                <option value="SD00"> all</option>
                                                @foreach($stat as $statcode)
                                                <option value="{{ $statcode['ID'] }}">{{ $statcode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date Range:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control float-right datepicker"
                                                name="data_date_range">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button id="ticket" name="ticket" class="ticket btn-submit btn btn-secondary" ><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible alert-message" >
                                    <i class="icon fas fa-check"></i>
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible alert-message">
                                    <i class="icon fas fa-ban"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-message">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <table id="tiket_list" class="table table-bordered table-hover display nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Tiket No</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Subject</th>
                                        <th>Requestor</th>
                                        <th>Assigned To</th>
                                        <th>Created On</th>
                                        <th>Target Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="modal-add-ticket" class="modal fade show" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Ticket</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="form" name="form" action="{{ route('add-tiket') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="ticketno" name="ticketno">
                    <input type="hidden" id="statusid" name="statusid">
                    <input type="hidden" id="status" name="status">
                    <input type="hidden" id="roleid" name="roleid">
                    <div class="modal-body">
                        @if(session('roleid') == 'RD001' || session('roleid') == 'RD004' || session('roleid') == 'RD005' || session('roleid') == 'RD006' || session('roleid') == 'RD007' || session('roleid') == 'RD008' || session('roleid') == 'RD009')
                        <div class="form-group">
                            <div class="name">User Request :</div>
                            <div class="input-group value">
                                <select id="user" name="user" class="select2" style="width: 100%;" required>
                                    <option value=""> Masukkan Pilihan :</option>
                                    @foreach($usreq as $usreqcode)
                                    <option value="{{ $usreqcode['ID'] }}">{{ $usreqcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(session('roleid') == 'RD006' || session('roleid') == 'RD009')
                        <div class="form-group">
                            <div class="name">Assigned To :</div>
                            <div class="input-group value">
                                <select id="assignto" name="assignto" class="form-control input--style-6" required>
                                    <option value=""> Masukkan Pilihan :</option>
                                    @foreach($assn as $assncode)
                                    <option value="{{ $assncode['ID'] }}">{{ $assncode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <div class="name">System :</div>
                                        <div class="input-group value">
                                            <select id="system" name="system" class="form-control input--style-6" required>
                                                <option value=""> Masukkan Pilihan :</option>
                                                @foreach($sys as $syscode)
                                                <option value="{{ $syscode['ID'] }}">{{ $syscode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <div class="name">Category :</div>
                                        <div class="input-group value">
                                            <select id="category" name="category" class="form-control input--style-6" required>
                                                <option value=""> Masukkan Pilihan :</option>
                                                @foreach($categ as $categcode)
                                                <option value="{{ $categcode['ID'] }}">{{ $categcode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <div class="name">Priority :</div>
                                        <div class="input-group value">
                                            <select id="priority" name="priority" class="form-control input--style-6" required>
                                                <option value=""> Masukkan Pilihan :</option>
                                                @foreach($prior as $priorcode)
                                                <option value="{{ $priorcode['ID'] }}">{{ $priorcode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="priorityname" name="priorityname" value="{{ $priorcode['NAME'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <div class="name">Object Type(optional):</div>
                                        <div class="input-group value">
                                            <select id="objecttype" name="objecttype" class="form-control input--style-6" required>
                                                <option value=""> Masukkan Pilihan :</option>
                                                @foreach($obj as $objcode)
                                                <option value="{{ $objcode['ID'] }}">{{ $objcode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="module">
                            <div class="name">Module :</div>
                            <div class="input-group value">
                                <select id="module" name="module" class="form-control input--style-6" required>
                                    <option value=""> Masukkan Pilihan :</option>
                                    @foreach($mdl as $mdlcode)
                                    <option value="{{ $mdlcode['ID'] }}">{{ $mdlcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="form-check-label" >Target Date :</label>
                            <input type="date" name="targetdate" id="targetdate" class="form-control"> 
                        </div> -->
                        <div class="form-group">
                            <label class="form-check-label" for="group">Subject :</label>
                            <input type="text" name="subject" class="form-control" id="subject" required>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="detail">Detail Issue :</label>
                            <textarea type="text" name="detail" class="form-control" id="detail" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="files">File :</label>
                            <input type="file" name="files" id="files" class="form-control">
                        </div>
                        <!-- <div class="input-field">
                            <label class="active">Upload Photos<small>(max size: 1mb)</small></label>
                            <div class="input-images" style="padding-top: .5rem;"></div>
                        </div> -->
                        <div class="form-group">
                            <label class="form-check-label" >Created Date :</label>
                            <div class="input-group value">
                                <input type="text"  id="createdate" name="createdate" class="form-control input--style-6" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="save-btn" class="btn btn-primary">Add Ticket</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <div id="modal-view-user" class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Ticket</h4>
                    <button type="button" class="close-btn close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" name="view1" id="view1">
                    @csrf
                    <div class="modal-body">
                    <input type="hidden" id="approveId" name="approveId">
                    <input type="hidden" id="approveItId" name="approveItId">
                    <input type="hidden" id="requestorid" name="requestorid">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                        <!-- <div class="form-group">
                            <label class="form-check-label" for="id" disabled>ID Requestor</label>
                            <input type="text" name="id" class="form-control" readonly>
                        </div> -->
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="ticketno" disabled>Ticket No :</label>
                                    <input type="text" name="ticketno" class="form-control" id="ticketno" readonly>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="requestor" disabled>User Request :</label>
                                    <input type="text" name="requestor" class="form-control" id="requestor" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="systemid" disabled>System :</label>
                                    <input type="text" name="systemid" class="form-control" id="systemid" readonly>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="category" disabled>Category :</label>
                                    <input type="text" name="category" class="form-control" id="category" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="priority" disabled>Priority :</label>
                                    <input type="text" name="priority" class="form-control" id="priority" readonly>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="objectid" disabled>Object Type :</label>
                                    <input type="text" name="objectid" class="form-control" id="objectid" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="modulehide">
                            <label class="form-check-label" for="moduleid" disabled>Module :</label>
                            <input type="text" name="moduleid" class="form-control" id="moduleid" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="subject" disabled>Subject :</label>
                            <input type="text" name="subject" class="form-control" id="subject" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="detail" disabled>Detail Issue :</label>
                            <textarea type="text" name="detail" class="form-control" id="detail" rows="4" cols="50" readonly></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="form-group">
                                    <label class="form-check-label" for="assignto" disabled>Assigned To :</label>
                                    <input type="text" name="assignto" class="form-control" id="assignto" readonly>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="form-group">
                                    <label class="form-check-label" for="status" disabled>Status :</label>
                                    <input type="text" name="status" class="label-success" id="status" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="upload" disabled>Attachment :</label>
                            <!-- <a href="/download" id="upload" name="upload" class="btn btn-large pull-right"><i class="icon-download-alt"> -->
                            <!-- <input type="hidden" id="upload" name="upload" class="form-control">
                            <button style="margin-left: 5px" class="upload btn btn-link btn-sm">Download File</button> -->
                            <a style="margin-left: 5px"><input type="button" id="upload" name="upload" class=" upload btn btn-link btn-sm" readonly></a>
                        </div>
                        <div class="row">
                            <div class="col-md-6">  
                                <div class="mb-3">
                                    <label class="form-check-label" for="approve" disabled>Approve Manager User :</label>
                                    <input type="text" name="approve" class="form-control" id="approve" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-check-label" for="approveit" disabled>Approve Manager IT:</label>
                                    <input type="text" name="approveit" class="form-control" id="approveit" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">  
                                <div class="mb-3">
                                    <label class="form-check-label" for="dateapprove" disabled>Date Approve User :</label>
                                    <input type="text" name="dateapprove" class="form-control" id="dateapprove" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-check-label" for="dateapproveit" disabled>Date Approve IT:</label>
                                    <input type="text" name="dateapproveit" class="form-control" id="dateapproveit" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">  
                                <div class="mb-3">
                                    <label class="form-check-label" for="created" disabled>Created Ticket :</label>
                                    <input type="text" name="created" class="form-control" id="created" readonly>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-check-label" for="createdon" disabled>Created Date:</label>
                                    <input type="text" name="createdon" class="form-control" id="createdon" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="targetdate" disabled>Target Date:</label>
                            <input type="text" name="targetdate" class="form-control" id="targetdate" readonly>
                        </div>
                        <hr />
                        <!-- <label class="form-check-label">Display Comment :</label> -->
                        
                        <h4 class="modal-title">Activity :</h4>
                        <div class="form-group" id="hidecmnt">
                            <!-- <label class="form-check-label" for="comment_body" disabled></label> -->
                            <textarea type="text" name="comment_body" class="form-control" id="comment_body" placeholder="Write a comment..."></textarea>
                            <button type="button" id="btncomment" class="btncomment btn btn-primary btn-xs">Save</button>
                        </div>
                        <!-- <div class="form-group">
                            <input type="file" name="filecomment" id="filecomment" class="form-control">
                        </div> -->
                        <!-- <div class="form-group" >
                            <input type="text" name="countcomment" id="countcomment" class="modal-input" readonly>
                        </div>
                        <div class="form-group" >
                            <button type="button" id="viewcomment" class="viewcomment btn btn-link btn-xs"><i class="fas fa-comment"></i> View Comment</button>
                        </div> -->
                        <div class="row">
                            <div class="col-3"> 
                                <button type="button" id="viewcomment" class="viewcomment btn btn-link btn-xs"><i class="fas fa-comment"></i> Show Details</button>
                                <div id="loadings">
                                    Loading...
                                </div>
                            </div>
                            <div class="col-1" id="comment2"> 
                                <span type="text" name="countcomment" id="countcomment" class="modal-input" readonly></span>
                            </div>
                        </div>
                        <div class="form-group" id="comment1">
                            <span type="text" name="comment" id="comment" class="modal-input" readonly></span>
                        </div>   
                    </div>
                    <hr />
                    <div class="col-md-6">
                        <div class="mb-3">
                            <button type="button" class="close-btn2 btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>   
                </form>
                <!-- <div class="modal-body">
                    <div class="form-group">
                        <button id="comment" name="comment" class="comment btn-submit btn btn-secondary" ><i class="fas fa-comments"></i>View Comment</button>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <div id="modal-comment" class="modal fade show" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Display Comment :</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" name="comment">
                    @csrf
                    <div class="modal-body">
                        <!-- <label class="form-check-label">Display Comment :</label> -->
                        <input type="text" name="ticketno" class="form-control" id="ticketno" readonly>
                        
                            <div class="form-group">
                                <label class="form-check-label" style="color:red"></label>
                                <label class="form-check-label" style="font-size:10px"></label>
                                <input type="text" style="font-family:'Courier New';font-size:20px" value="" class="form-control" readonly>
                            </div>
                        
                        <hr />
                        <div class="form-group">
                            <label class="form-check-label" for="comment_body" disabled>Comment</label>
                            <textarea type="text" name="comment_body" class="form-control" id="comment_body"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="button" id="btncomment" class="btn btn-warning">Save</button>
                        </div>
                        
                    </div>
                    <!-- <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="update-btn" class="btn btn-primary">Save</button>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
    <div id="modal-update-user"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Ticket</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('edit-tiket')}}" method="post" name="update" id="update">
                    @csrf
                    <input type="hidden" id="update-ticketno" name="ticketno"/>
                    <input type="hidden" id="update-userid" name="userid"/>
                    <input type="hidden" id="update-rejectedby" name="rejectedby"/>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="ticketno" disabled>Ticket No :</label>
                                    <input type="text" name="ticketno" class="form-control" id="ticketno" readonly>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="requestor" disabled>User Request :</label>
                                    <input type="text" name="requestor" class="form-control" id="requestor" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="systemid" disabled>System :</label>
                                    <input type="text" name="systemid" class="form-control" id="systemid" readonly>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <div class="name">Category :</div>
                                        <div class="input-group value">
                                            <select id="category" name="category" class="form-control input--style-6" required>
                                                @foreach($categ as $categcode)
                                                <option value="{{ $categcode['ID'] }}">{{ $categcode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="priority" disabled>Priority :</label>
                                    <input type="text" name="priority" class="form-control" id="priority" readonly>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <div class="name">Object Type:</div>
                                        <div class="input-group value">
                                            <select id="objecttype" name="objecttype" class="form-control input--style-6" required>
                                                <option value=""> Masukkan Pilihan :</option>
                                                @foreach($obj as $objcode)
                                                <option value="{{ $objcode['ID'] }}">{{ $objcode['NAME'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="hidemodule">
                            <div class="name">Module :</div>
                            <div class="input-group value">
                                <select id="moduleid" name="moduleid" class="form-control input--style-6">
                                    <option value=""> Masukkan Pilihan :</option>
                                    @foreach($mdl as $mdlcode)
                                    <option value="{{ $mdlcode['ID'] }}">{{ $mdlcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="subject" disabled>Subject :</label>
                            <input type="text" name="subject" class="form-control" id="subject" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="detail" disabled>Detail Issue :</label>
                            <textarea type="text" name="detail" class="form-control" id="detail" rows="4" cols="50" ></textarea>
                        </div>
                        <div class="form-group">
                            <div class="name">Assigned To :</div>
                            <div class="input-group value">
                                <select id="assignto" name="assignto" class="form-control input--style-6" required>
                                    @foreach($assn as $assncode)
                                    <option value="{{ $assncode['ID'] }}">{{ $assncode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="targetdate" disabled>Target Date:</label>
                            <input type="text" name="targetdates" class="form-control" id="targetdates">
                        </div>
                        <div class="row">
                            <div class="col-md-6">  
                                <div class="mb-3">
                                    <label class="form-check-label" for="approve" disabled>Approve Manager User :</label>
                                    <input type="text" name="approve" class="form-control" id="approve" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-check-label" for="approveit" disabled>Approve Manager IT:</label>
                                    <input type="text" name="approveit" class="form-control" id="approveit" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="modal-reject-user"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Reject Ticket</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('update-tiket')}}" method="post" name='reject'>
                    @csrf
                    <input type="hidden" id="reject-ticketno" name="ticketno"/>
                    <input type="hidden" id="reject-userid" name="userid"/>
                    <input type="hidden" id="reject-assignto" name="assignto"/>
                    <input type="hidden" id="reject-approvedby1" name="approvedby1"/>
                    <input type="hidden" id="reject-approveby_it" name="approveby_it"/>
                    <input type="hidden" id="reject-approveby_1_date" name="approveby_1_date"/>
                    <input type="hidden" id="reject-approveby_it_date" name="approveby_it_date"/>
                    <div class="modal-body">
                        <p>Are You Sure ? <span class="text-bold"></span></p>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="rejectedby" name="rejectedby" class="form-control input--style-6" type="hidden" value="{{ session('userid') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="statusid" name="statusid" class="form-control input--style-6" type="hidden" value="SD005">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="status" name="status" class="form-control input--style-6" type="hidden" value="REJECTED">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="modal-closed-user"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Closed Ticket</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('close-tiket')}}" method="post" name='closed'>
                    @csrf
                    <input type="hidden" id="closed-ticketno" name="ticketno"/>
                    <input type="hidden" id="closed-userid" name="userid"/>
                    <input type="hidden" id="closed-assignto" name="assignto"/>
                    <input type="hidden" id="closed-approvedby1" name="approvedby1"/>
                    <input type="hidden" id="closed-approveby_it" name="approveby_it"/>
                    <input type="hidden" id="closed-approveby_1_date" name="approveby_1_date"/>
                    <input type="hidden" id="closed-approveby_it_date" name="approveby_it_date"/>
                    <div class="modal-body">
                        <p>Are You Sure ? <span class="text-bold"></span></p>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="remark" name="remark" class="form-control input--style-6" type="text" value="Ticket Closed">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="assignto" name="assignto" class="form-control input--style-6" type="hidden" value="{{ session('userid') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="statusid" name="statusid" class="form-control input--style-6" type="hidden" value="SD003">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="status" name="status" class="form-control input--style-6" type="hidden" value="CLOSED">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" >Yes</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="modal-transport"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Transport</h4>
                    <button type="button" class="close-btn-trans close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('send-transport') }}" method="post" name="transport" id="transport">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-check-label" for="ticketno" disabled>Ticket No :</label>
                            <input type="text" name="ticketno" class="form-control" id="ticketno" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" >Opsi Transport :</label>
                            <div class="input-group value">
                                <select id="opsi" name="opsi" class="form-control input--style-6">
                                    <option value="">Select Option</option>
                                    <option value="exist">Existing</option>
                                    <option value="new">New Transport</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="transid">
                            <label class="form-check-label" for="transportid">Transposrt id :</label>
                            <div class="input-group value">
                                <select class="select2" data-placeholder="pilih transport" multiple="multiple" id="transportid" name="data_transportid[]" style="width: 100%;">
                                    @foreach($trq as $trqcode)
                                    <option value="{{ $trqcode['ID'] }}">{{ $trqcode['ID'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="transnumb">
                            <label class="form-check-label" for="transnumber">Transport Number :</label>
                            <textarea type="text" name="transnumber" class="form-control" id="transnumber" rows="4" cols="50" ></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4"> 
                                <input type="checkbox" id="lqa" name="lqa" value="1"> 
                                <label for="">LQA</label><br>
                            </div>
                            <div class="col-md-4"> 
                                <input type="checkbox" id="lpr" name="lpr" value="1">
                                <label for="">LPR</label><br>  
                            </div>
                        </div>
                        <hr/>
                        <h5 class="modal-title">History :</h5>
                        <button type="button" id="btnhistorytrans" class="btnhistorytrans btn btn-link btn-xs">Show History</button>
                        <div id="loadings1">
                            Loading...
                        </div>
                        <div class="form-group" id="history">
                            <span type="text" name="listhistory" id="listhistory" class="modal-input" readonly></span>
                        </div>  
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="close-btn2-trans btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" >Send</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="modal-transport-approve"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Approval Transport</h4>
                    <button type="button" class="close-btn-trans close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('approve-transport') }}" method="post" name="transport-approve" id="transport-approve">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="sendlqa" name="sendlqa">
                        <input type="hidden" id="sendlpr" name="sendlpr">
                        <div class="form-group">
                            <label class="form-check-label" for="ticketno" disabled>Ticket No :</label>
                            <input type="text" name="ticketno" class="form-control" id="ticketno" readonly>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="transid" disabled>Transport ID :</label>
                            <input type="text" name="transid" class="form-control" id="transid" readonly>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="transno" disabled>Transport Number:</label>
                            <textarea type="text" name="transno" class="form-control" id="transno" readonly></textarea>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="viewsendlqa" disabled>Status LQA :</label>
                            <input type="text" name="viewsendlqa" class="form-control" id="viewsendlqa" readonly>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="viewsendlpr" disabled>Status LPR :</label>
                            <input type="text" name="viewsendlpr" class="form-control" id="viewsendlpr" readonly>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="remark" disabled>Remark :</label>
                            <input type="text" name="remark" class="form-control" id="remark">
                        </div> 
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="rejecttrans btn btn-danger">Reject</button>
                        <button type="submit" class="btn btn-primary" >Approve</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="modal-transport-mgr"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Transport</h4>
                    <button type="button" class="close-btn-trans close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" method="post" name="transport-mgr" id="transport-mgr">
                    @csrf
                    <div class="modal-body">
                    <input type="hidden" id="ticketno" name="ticketno">
                    <h5 class="modal-title">History :</h5>
                        <button type="button" id="btnhistorytrans1" class="btnhistorytrans1 btn btn-link btn-xs">Show History</button>
                        <div id="loadings2">
                            Loading...
                        </div>
                        <div class="form-group" id="history1">
                            <span type="text" name="listhistory" id="listhistory" class="modal-input" readonly></span>
                        </div>  
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="close-btn2-trans btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="modal-transported"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Transported</h4>
                    <button type="button" class="close-btn-trans close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('transported-transport') }}" method="post" name="transport-transported" id="transport-transported">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="sendlqa" name="sendlqa">
                        <input type="hidden" id="sendlpr" name="sendlpr">
                        <div class="form-group">
                            <label class="form-check-label" for="ticketno" disabled>Ticket No :</label>
                            <input type="text" name="ticketno" class="form-control" id="ticketno" readonly>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="transid" disabled>Transport ID :</label>
                            <input type="text" name="transid" class="form-control" id="transid" readonly>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="transno" disabled>Transport Number:</label>
                            <textarea type="text" name="transno" class="form-control" id="transno" readonly></textarea>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="viewsendlqa" disabled>Status LQA :</label>
                            <input type="text" name="viewsendlqa" class="form-control" id="viewsendlqa" readonly>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="viewsendlpr" disabled>Status LPR :</label>
                            <input type="text" name="viewsendlpr" class="form-control" id="viewsendlpr" readonly>
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="remark" disabled>Remark :</label>
                            <input type="text" name="remark" class="form-control" id="remark">
                        </div> 
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="rejecttrans btn btn-danger">Reject</button>
                        <button type="submit" class="btn btn-primary" >Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div> 
@endsection
@section('extend-js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('image-upload/image-uploader.js') }}"></script>
<script src="{{ asset('plugins/jquery/jquery-ui.js') }}"></script>

<script>
    $('.nav-link.active').removeClass('active');
    $('#m-tiket').addClass('active');
    $('#m-tiket').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script >
    $(function () {    
        var $btn_submit = $("button#btn-sumbit-ticket");

        //Initialize Select2 Elements
        $('.select2').select2();
        $('.datepicker').daterangepicker();

        $('#save-btn').on('click', function() {
            $('#form').submit();
            $(this).attr('disabled', true);
            $(this).text("Loading ...");
        });

        $(document).on('click', '.trans', function() {
            // $("#history").load(" #history"); 
            $('#modal-transport').modal({backdrop: 'static', keyboard: false})
            var user_id = $(this).attr('data-id');
            var ticketno = $(this).attr('data-ticket');
            var requestor = $(this).attr('data-requestor');
            var category = $(this).attr('data-category');
            var status = $(this).attr('data-status');
            var statusid  = $(this).attr('data-statusid');
            var priority  = $(this).attr('data-priority');
            var subject  = $(this).attr('data-subject');
            var detail  = $(this).attr('data-detail');
            var assign  = $(this).attr('data-assignto');
            var statusid  = $(this).attr('data-statusid');
            var roleid  = $(this).attr('data-roleid');
            var targetdate  = $(this).attr('data-targetdate');
            var created  = $(this).attr('data-createdname');
            var approveId  = $(this).attr('data-approvedby_1');
            var approveItId  = $(this).attr('data-approvedby_it');
            var requestorId  = $(this).attr('data-requestorid');
            var approve  = $(this).attr('data-approve1name');
            var approveit  = $(this).attr('data-approveitname');
            var approvedate  = $(this).attr('data-approvedby1');
            var approveitdate  = $(this).attr('data-approvedbyit');
            var systemid  = $(this).attr('data-systemid');
            var systemname  = $(this).attr('data-systemname');
            var moduleid  = $(this).attr('data-moduleid');
            var objectid  = $(this).attr('data-objectid');
            var upload  = $(this).attr('data-upload');
            var createdon  = $(this).attr('data-createdon');
            var $modal = $('#modal-transport');
            var $form = $modal.find('form[name="transport"]');
            var hide1 = $("#transid");
            var hide2 = $("#transnumb");
            hide1.hide();
            hide2.hide();
            $form.find('input[name="id"]').val(user_id);
            $form.find('input[name="ticketno"]').val(ticketno);
            $modal.modal('show');
        });

        $(document).on('click', '.approvetrans', function() {
            $('#modal-transport-approve').modal({backdrop: 'static', keyboard: false})
            var user_id = $(this).attr('data-id');
            var ticketno = $(this).attr('data-ticket');
            var transid = $(this).attr('data-transportid');
            var transno = $(this).attr('data-transportno');
            var sendlqa = $(this).attr('data-sendto_lqa');
            var sendlpr = $(this).attr('data-sendto_lpr');
            var createdon  = $(this).attr('data-createdon');
            var $modal = $('#modal-transport-approve');
            var $form = $modal.find('form[name="transport-approve"]');
            if(sendlqa == '1' && sendlpr == '1'){
                lqa = 'Requested';
                lpr = 'Requested';
            } else if(sendlqa == '1'){
                lqa = 'Requested';
                lpr = '-'
            } else if(sendlpr == '1'){
                lqa = '-';
                lpr = 'requested';
            } else {
                lqa = '-';
                lpr = '-';
            }
            $form.find('input[name="id"]').val(user_id);
            $form.find('input[name="ticketno"]').val(ticketno);
            $form.find('input[name="transid"]').val(transid);
            $form.find('textarea[name="transno"]').val(transno);
            $form.find('input[name="viewsendlqa"]').val(lqa);
            $form.find('input[name="viewsendlpr"]').val(lpr);
            $form.find('input[name="sendlqa"]').val(sendlqa);
            $form.find('input[name="sendlpr"]').val(sendlpr);
            $modal.modal('show');
        });

        $(document).on('click', '.viewtrans', function() {
            $('#modal-transport-mgr').modal({backdrop: 'static', keyboard: false})
            var ticketno = $(this).attr('data-ticket');
            var $modal = $('#modal-transport-mgr');
            var $form = $modal.find('form[name="transport-mgr"]');
            $form.find('input[name="ticketno"]').val(ticketno);
            $modal.modal('show');
        });

        $(document).on('click', '.transted', function() {
            $('#modal-transported').modal({backdrop: 'static', keyboard: false})
            var user_id = $(this).attr('data-id');
            var ticketno = $(this).attr('data-ticket');
            var transid = $(this).attr('data-transportid');
            var transno = $(this).attr('data-transportno');
            var status_lqa = $(this).attr('data-status_lqa');
            var status_lpr = $(this).attr('data-status_lpr');
            var createdon  = $(this).attr('data-createdon');
            var $modal = $('#modal-transported');
            var $form = $modal.find('form[name="transport-transported"]');
            if(status_lqa == '1' && status_lpr == '1'){
                lqa = 'Approved';
                lpr = 'Approved';
            } else if(status_lqa == '1'){
                lqa = 'Approved';
                lpr = '-'
            } else if(status_lpr == '1'){
                lqa = '-';
                lpr = 'Approved';
            } else {
                lqa = '-';
                lpr = '-';
            }
            $form.find('input[name="id"]').val(user_id);
            $form.find('input[name="ticketno"]').val(ticketno);
            $form.find('input[name="transid"]').val(transid);
            $form.find('textarea[name="transno"]').val(transno);
            $form.find('input[name="viewsendlqa"]').val(lqa);
            $form.find('input[name="viewsendlpr"]').val(lpr);
            $form.find('input[name="sendlqa"]').val(status_lqa);
            $form.find('input[name="sendlpr"]').val(status_lpr);
            $modal.modal('show');
        });

        $(document).on('click', '.view', function() {
            // $("#comment1").load(" #comment1");
            $('#modal-view-user').modal({backdrop: 'static', keyboard: false})  
            getComment($(this).attr('data-ticket'));
            var user_id = $(this).attr('data-id');
            var ticketno = $(this).attr('data-ticket');
            var requestor = $(this).attr('data-requestor');
            var category = $(this).attr('data-category');
            var status = $(this).attr('data-status');
            var statusid  = $(this).attr('data-statusid');
            var priority  = $(this).attr('data-priority');
            var subject  = $(this).attr('data-subject');
            var detail  = $(this).attr('data-detail');
            var assign  = $(this).attr('data-assignto');
            var statusid  = $(this).attr('data-statusid');
            var roleid  = $(this).attr('data-roleid');
            var targetdate  = $(this).attr('data-targetdate');
            var created  = $(this).attr('data-createdname');
            var approveId  = $(this).attr('data-approvedby_1');
            var approveItId  = $(this).attr('data-approvedby_it');
            var requestorId  = $(this).attr('data-requestorid');
            var approve  = $(this).attr('data-approve1name');
            var approveit  = $(this).attr('data-approveitname');
            var approvedate  = $(this).attr('data-approvedby1');
            var approveitdate  = $(this).attr('data-approvedbyit');
            var systemid  = $(this).attr('data-systemid');
            var systemname  = $(this).attr('data-systemname');
            var moduleid  = $(this).attr('data-moduleid');
            var objectid  = $(this).attr('data-objectid');
            var upload  = $(this).attr('data-upload');
            var createdon  = $(this).attr('data-createdon');
            var $modal = $('#modal-view-user');
            var $form = $modal.find('form[name="view1"]');
            var hide = $("#hidecmnt");
            hide.show();
            if(statusid == 'SD003'){
                hide.hide();
            }
            console.log(modulehide);
            var modulehide = $("#modulehide");
            modulehide.show();
            if(moduleid == ''){
                modulehide.hide();
            }
            $form.find('input[name="id"]').val(user_id);
            $form.find('input[name="ticketno"]').val(ticketno);
            $form.find('input[name="requestor"]').val(requestor);
            $form.find('input[name="category"]').val(category);
            $form.find('input[name="priority"]').val(priority);
            $form.find('input[name="subject"]').val(subject);
            $form.find('textarea[name="detail"]').val(detail);
            $form.find('input[name="assignto"]').val(assign);
            $form.find('input[name="statusid"]').val(statusid);
            $form.find('input[name="roleid"]').val(roleid);
            $form.find('input[name="status"]').val(status);
            $form.find('input[name="created"]').val(created);
            $form.find('input[name="targetdate"]').val(targetdate);
            $form.find('input[name="approve"]').val(approve);
            $form.find('input[name="approveit"]').val(approveit);
            $form.find('input[name="dateapprove"]').val(approvedate);
            $form.find('input[name="approveId"]').val(approveId);
            $form.find('input[name="approveItId"]').val(approveItId);
            $form.find('input[name="requestorid"]').val(user_id);
            $form.find('input[name="dateapproveit"]').val(approveitdate);
            $form.find('input[name="comment_body"]').val(comment_body);
            $form.find('input[name="systemid"]').val(systemname);
            $form.find('input[name="moduleid"]').val(moduleid);
            $form.find('input[name="objectid"]').val(objectid);
            $form.find('input[name="upload"]').val(upload);
            $form.find('input[name="createdon"]').val(createdon);
            $modal.modal('show');
        });

        $(document).on('click', '.update', function () {
            $('#modal-update-user').modal({backdrop: 'static', keyboard: false})  
            getCategoryJson($(this).attr('data-systemid'),  $(this).attr('data-categoryid'));
            var user_id = $(this).attr('data-id');
            var ticketno = $(this).attr('data-ticket');
            var requestor = $(this).attr('data-requestor');
            var category = $(this).attr('data-category');
            var categoryid = $(this).attr('data-categoryid');
            var status = $(this).attr('data-status');
            var statusid  = $(this).attr('data-statusid');
            var priority  = $(this).attr('data-priority');
            var subject  = $(this).attr('data-subject');
            var detail  = $(this).attr('data-detail');
            var assign  = $(this).attr('data-assignto');
            var assignedto  = $(this).attr('data-assignedto');
            var statusid  = $(this).attr('data-statusid');
            var roleid  = $(this).attr('data-roleid');
            var targetdate  = $(this).attr('data-targetdate');
            var created  = $(this).attr('data-createdname');
            var approveId  = $(this).attr('data-approvedby_1');
            var approveItId  = $(this).attr('data-approvedby_it');
            var requestorId  = $(this).attr('data-requestorid');
            var approve  = $(this).attr('data-approve1name');
            var approveit  = $(this).attr('data-approveitname');
            var approvedate  = $(this).attr('data-approvedby1');
            var approveitdate  = $(this).attr('data-approvedbyit');
            var systemid  = $(this).attr('data-systemid');
            var systemname  = $(this).attr('data-systemname');
            var moduleid  = $(this).attr('data-moduleid');
            var objectid  = $(this).attr('data-objectid');
            var createdon  = $(this).attr('data-createdon');
            var $modal = $('#modal-update-user');
            var $form = $modal.find('form[name="update"]');
            var hide1 = $("#hidemodule");
            hide1.show()
            if(systemid == 'SY002'){
                hide1.hide();
            }
            $form.find('input[name="userid"]').val(user_id);
            $form.find('input[name="ticketno"]').val(ticketno);
            $form.find('input[name="requestor"]').val(requestor);
            $form.find('input[name="priority"]').val(priority);
            $form.find('input[name="subject"]').val(subject);
            $form.find('textarea[name="detail"]').val(detail);
            $form.find('input[name="statusid"]').val(statusid);
            $form.find('input[name="roleid"]').val(roleid);
            $form.find('input[name="status"]').val(status);
            $form.find('input[name="created"]').val(created);
            $form.find('input[name="targetdates"]').val(targetdate);
            $form.find('input[name="approve"]').val(approve);
            $form.find('input[name="approveit"]').val(approveit);
            $form.find('input[name="dateapprove"]').val(approvedate);
            $form.find('input[name="approveId"]').val(approveId);
            $form.find('input[name="approveItId"]').val(approveItId);
            $form.find('input[name="requestorid"]').val(user_id);
            $form.find('input[name="dateapproveit"]').val(approveitdate);
            $form.find('input[name="systemid"]').val(systemname);
            $form.find('input[name="moduleid"]').val(moduleid);
            $form.find('input[name="objectid"]').val(objectid);
            $form.find('input[name="createdon"]').val(createdon);
            // var category_options = $form.find('select[name="category"]').children();
            // $.each(category_options, function(key, value) {
            //     if($(value).val() == categoryid) {
            //         $(value).attr('selected', true);
            //     } else {
            //         $(value).attr('selected', false);
            //     }
            // });
            var assignedto_options = $form.find('select[name="assignto"]').children();
            $.each(assignedto_options, function(key, value) {
                if($(value).val() == assignedto) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            var moduleid_options = $form.find('select[name="moduleid"]').children();
            $.each(moduleid_options, function(key, value) {
                if($(value).val() == moduleid) {
                    $(value).attr('selected', true);
                } else {
                    $(value).val() == "";
                    $(value).attr('selected', false);
                }
            });
            var objectid_options = $form.find('select[name="objecttype"]').children();
            $.each(objectid_options, function(key, value) {
                if($(value).val() == objectid) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            $modal.modal('show');
        })

        $(document).on('click', '.reject', function () {
            // alert('bisa');
            $('#modal-reject-user').modal({backdrop: 'static', keyboard: false})  
            $('#reject-ticketno').val($(this).attr("data-ticketno"));
            $('#reject-userid').val($(this).attr("data-userid"));
            $('#reject-assignto').val($(this).attr("data-assignto"));
            $('#reject-approvedby1').val($(this).attr("data-approvedby1"));
            $('#reject-approvedbyit').val($(this).attr("data-approvedbyit"));
            $('#reject-rejectedby').val($(this).attr("data-rejectedby"));
            $('#reject-statusid').val($(this).attr("data-statusid"));
            $('#reject-approvedby1_date').val($(this).attr("data-approvedby1_date"));
            $('#reject-approvedbyit_date').val($(this).attr("data-approvedbyit_date"));
            $('#modal-reject-user').modal('show');
        })

        $(document).on('click', '.closed', function () {
            // alert('bisa');
            $('#modal-closed-user').modal({backdrop: 'static', keyboard: false})  
            $('#closed-ticketno').val($(this).attr("data-ticketno"));
            $('#closed-userid').val($(this).attr("data-userid"));
            $('#closed-statusid').val($(this).attr("data-statusid"));
            $('#closed-assignto').val($(this).attr("data-assignto"));
            $('#closed-approvedby1').val($(this).attr("data-approvedby1"));
            $('#closed-approvedbyit').val($(this).attr("data-approvedbyit"));
            $('#closed-rejectedby').val($(this).attr("data-rejectedby"));
            $('#closed-statusid').val($(this).attr("data-statusid"));
            $('#closed-approvedby1_date').val($(this).attr("data-approvedby1_date"));
            $('#closed-approvedbyit_date').val($(this).attr("data-approvedbyit_date"));
            $('#modal-closed-user').modal('show');
        })

        $(document).on('click', '.ticket', function submit() {
            var daterange = $('input[name="data_date_range"]').val();
            var requestor = $('select[name="requestor"]  option:selected').val();
            var assignto = $('select[name="assignto"] option:selected').val();
            var status = $('select[name="status"] option:selected').val();
            var ticketno = $('select[name="ticketno"] option:selected').val();
           
            $('#tiket_list').DataTable().clear().destroy();
            var $dataticket = $('#tiket_list').DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive: false,
                searching: true,
                dom: 'Blfrtip',
                buttons: [
                    'excel'
                ],
                ajax: {
                    url: "{{ route('filter-tiket') }}",
                    "data": function (d) {
                        d.daterange = $('input[name="data_date_range"]').val();
                        d.requestor = $('select[name="requestor"] option:selected').val();
                        d.assignto = $('select[name="assignto"] option:selected').val();
                        d.status = $('select[name="status"] option:selected').val();
                        d.ticketno = $('select[name="ticketno"] option:selected').val();
                    },
                    "dataSrc": function (settings) {
                        $btn_submit.text("Submit");
                        $btn_submit.prop('disabled', false);
                        return settings.data;
                    },
                },
                order: [[ 7, "desc" ]],
                columns: [
                    // {
                    //     data: 'ticketno',
                    //     render: function(data){
                    //         if(data != null){
                    //             return '';
                    //         } else {
                    //             return '';
                    //         }
                    //     }
                    // },
                    {
                        data: 'action',
                        name: 'action',
                    },
                    {
                        data: 'ticketno',
                        render: function(data, type, row){
                            return '<a href="javascript:void(0)" class="view btn btn-link" data-ticket="'+row["ticketno"]+'" data-id="'+row["userid"]+'" data-statusid="'+row["statusid"]+'" data-requestor="'+row["requestor"]+'" data-status="'+row["status"]+'" data-category="'+row["category"]+'" data-priority="'+row["priority"]+'" data-subject="'+row["subject"]+'" data-detail="'+row["detail"]+'" data-assignto="'+row["assigned_to"]+'" data-created="'+row["createdby"]+'" data-approve="'+row["approvedby_1"]+'" data-upload="'+row["attachment"]+'" data-approve1name="'+row["approvedby1Name"]+'" data-approveitname="'+row["approvedbyitName"]+'" data-createdname="'+row["createdname"]+'" data-targetdate="'+row["targetdate"]+'" data-approvedby1="'+row["approvedby1_date"]+'" data-approvedbyit="'+row["approvedbyit_date"]+'" data-systemid="'+row["systemid"]+'" data-moduleid="'+row["moduleid"]+'" data-objectid="'+row["objectid"]+'" data-createdon="'+row["createdon"]+'" data-systemname="'+row["systemname"]+'">'+data+'</a>'
                        }
                    },
                    {
                        data: 'category',
                        render: function(data) {
                            if(data == 'INCIDENT'){
                            statusText = `<span class="badge badge-danger">INCIDENT</span>`;
                            } else if (data == 'CHANGE REQUEST'){
                                statusText = `<span class="badge badge-success">CHANGE REQUEST</span>`;
                            } else if (data == 'HARDWARE'){
                                statusText = `<span class="badge badge-info">HARDWARE</span>`;
                            } else if (data == 'USER REQUEST'){
                                statusText = `<span class="badge badge-warning">USER REQUEST</span>`;
                            } else if (data == 'AUTHORIZATION'){
                                statusText = `<span class="badge badge-dark">AUTHORIZATION</span>`;
                            } else if (data == 'INTERNET ACCESS'){
                                statusText = `<span class="badge badge-primary">INTERNET ACCESS</span>`;
                            } else if (data == 'EMAIL'){
                                statusText = `<span class="badge badge-dark">EMAIL ACCESS</span>`;
                            } else if (data == 'INTERNET'){
                                statusText = `<span class="badge badge-primary">INTERNET</span>`;
                            } else if (data == 'DATA'){
                                statusText = `<span class="badge badge-info">DATA</span>`;
                            } else if (data == 'OTHER'){
                                statusText = `<span class="badge badge-warning">OTHER</span>`;
                            } else if (data == 'IMPROVEMENT'){
                                statusText = `<span class="badge badge-primary">IMPROVEMENT</span>`;
                            } else if (data == 'PROCEDURE'){
                                statusText = `<span class="badge badge-info">PROCEDURE</span>`;
                            } else if (data == 'INFRASTRUCTURE'){
                                statusText = `<span class="badge badge-success">INFRASTRUCTURE</span>`;
                            } else if (data == 'SOFTWARE'){
                                statusText = `<span class="badge badge-dark">SOFTWARE</span>`;
                            } else if (data == 'VPN'){
                                statusText = `<span class="badge badge-warning">VPN</span>`;
                            } else if (data == 'DEPLOY'){
                                statusText = `<span class="badge badge-dark">DEPLOY</span>`;
                            }
                            
                            return statusText;
                        }
                    },
                    {
                        data: 'status',
                        render: function (data){
                            if(data == "CLOSED"){
                                statusText = `<span class="badge badge-danger">Closed</span>`;
                            } else if(data == "IN PROGRESS"){
                                statusText = `<span class="badge badge-success">In Progress</span>`;
                            } else if(data == "WAITING FOR APPROVAL"){
                                statusText = `<span class="badge badge-warning">Waiting Approval</span>`;
                            } else {
                                statusText = `<span class="badge badge-primary">Open</span>`;
                            }
                            return statusText;
                        }
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'requestor',
                        name: 'requestor'
                    },
                    {
                        data: 'assigned_to',
                        name: 'assigned_to'
                    },
                    {
                        data: 'createdon',
                        render: function(data) {
                            var today = new Date(data);
                            var day = today.getDate() + "";
                            var month = (today.getMonth() + 1) + "";
                            var year = today.getFullYear() + "";
                            var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
                            var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
                            var seconds = today.getSeconds() + "";

                            day = day;
                            month = month;
                            year = year;
                            hour = hour;
                            minutes = minutes;
                            seconds = seconds;
                            // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
                            var date = day + "/" + month + "/" + year;
                            return date;   
                        }
                    },
                    {
                    data: 'targetdate',
                        render: function(data) {
                            // console.log(data);
                            if(data == ''){
                                var date = "";
                            } else {
                                var today = new Date(data);
                                var day = today.getDate() + "";
                                var month = (today.getMonth() + 1) + "";
                                var year = today.getFullYear() + "";
                                var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
                                var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
                                var seconds = today.getSeconds() + "";

                                day = day;
                                month = month;
                                year = year;
                                hour = hour;
                                minutes = minutes;
                                seconds = seconds;
                                // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
                                var date = day + "/" + month + "/" + year;
                            }
                            return date;   
                        }
                    },
                ],
                oLanguage: {
                    "sLengthMenu": "Tampilkan _MENU_ data",
                    "sProcessing": "Loading...",
                    "sSearch": "Keyword:",
                    "sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ data" 	
                },
                drawCallback: function() {
                    $btn_submit.text("Sumbit");
                    $btn_submit.prop('disabled', false);
                }
            });
        });
             
        $(document).on('click', '.viewcomment', function(e) {
            var ticketno = $('#modal-view-user input[name="ticketno"]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "/get/comment",
                data: {
                    'ticketno' : ticketno, 
                },
                success: function(response) {
                    // console.log(response["disc"])
                    $("#comment").css("display","inline");
                    // var hide = $("#hideviewcmnt");
                    var $viewComment = $(' <div class="form-group"></div>');
                    $.each(response["disc"], function(key, data) {
                        var $nama = "<label class=form-check-label style=color:red>" +data["SENDER"]+ "</label>";
                        var $date = "&nbsp;<label class=form-check-label style=font-size:9px>" +data["DATE"]+"</label>";
                        var $comment = "<b><textarea type=text class=form-control style=font-family:'Courier New';font-size:30px readonly>" +data["COMMENT"]+"</textarea></b>";
                        var $filecomment = " <button download id=file name=file class=btn btn-link btn-sm style=font-size:13px>"+data["FILE"]+"</button>"
                        $viewComment.append($nama,$date,$filecomment,$comment);
                    });
                    
                    $('#modal-view-user form[name="view1"] span[name="comment"]').parent().html($viewComment);  
                    
                },
                error: function (error) {
                    console.error(error);
                },
            })
        });

        $(document).ready(function () {
            $(document).ajaxStart(function () {
                $("#loadings").show();
            }).ajaxStop(function () {
                $("#loadings").hide();
            });
        });

        $(document).on('click', '.btncomment', function() {
            $("#comment2").load(" #comment2");
            var ticketno = $('#modal-view-user form[name="view1"] input[name="ticketno"]').val();
            var requestor = $('#modal-view-user form[name="view1"] input[name="requestorid"]').val();
            var approve = $('#modal-view-user form[name="view1"] input[name="approveId"]').val();
            var approveit = $('#modal-view-user form[name="view1"] input[name="approveItId"]').val();
            var comment_body = $('#modal-view-user  form[name="view1"] textarea[name="comment_body"]').val();
            var file_data = $('#modal-view-user  form[name="view1"] input[name="filecomment"]').val();
            // const file_data = $('#filecomment').prop('files')[0];
            // var filecomment = document.getElementById("filecomment").files[0].name;
            // var file_data = $('#filecomment').prop('files')[0];  
            // var formData = new FormData(); 
            // formData.append("filecomment", file_data);

         
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/add/comment",
                type: 'POST',
                data: {
                    'ticketno' : ticketno,
                    'requestor' : requestor,
                    'approve' : approve,
                    'approveit' : approveit, 
                    'comment_body' : comment_body,
                    'filecomment' : file_data
                },
                success: function(response){ 
                    // console.log(response);
                    // var $viewComment = $('.modal-content .modal-body .<div class=form-group id=comment1');
                    // var $viewComment = $modal.find('<div class="form-group" id="comment1">');
                   
                    var $viewComment = $('<div class=form-group>'); 
                    $.each(response["disc"], function(key, data) {
                        var $nama = "<label class=form-check-label style=color:red>"+data["SENDER"]+"</label>";
                        var $date = "<label class=form-check-label style=font-size:9px>"+data["DATE"]+"<label>";
                        var $comment = "<textarea type=text class=form-control style=font-family:'Courier New';font-size:20px readonly>"+data["COMMENT"]+"</textarea>";
                        var $filecomment = "<a type=submit id=file name=file class=btn btn-link btn-sm style=font-size:15px readonly>"+data["FILE"]+"</a>"
                        $viewComment.append($nama,$date,$filecomment,$comment);
                    });
                    document.getElementById("comment_body").value = "";
                    $('#modal-view-user form[name="view1"] input[name="comment"]').parent().html($viewComment);
                    getComment(ticketno);
                    
        
                },
                error: function (error) {
                    console.error(error);
                },
            });
        })

        // $(document).on('click', '.upload', function() {
        //     var upload = $('#modal-view-use1 input[name="upload"]').val();
         
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         type: 'POST',
        //         url: "/download/file",
        //         data: {
        //             'upload' : upload, 
        //         },
        //         success: function(response){ 
        //             console.log(response);
        //             window.open(response);
        //             // var blob = new Blob([response]);
        //             // var link = document.createElement('a');
        //             // link.href = window.URL.createObjectURL(blob);
        //             // link.download = response;
        //             // link.click();
        //         }
        //     });
        // })

        $(document).ready(function () {
            $(document).ajaxStart(function () {
                $("#loadings1").show();
            }).ajaxStop(function () {
                $("#loadings1").hide();
            });
        });

        $(document).on('click', '.btnhistorytrans', function(e) {
            var ticketno = $('#modal-transport input[name="ticketno"]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "/get/historytrans",
                data: {
                    'ticketno' : ticketno, 
                },
                success: function(response) {
                    // console.log(response["trq"])
                    var $viewHistory = $(' <div class="form-group"></div>');
                    $.each(response["trq"], function(key, data) {
                        if(data["LQA"] == 1 && data["LPR"] == 1){
                            lqa = 'LQA Requested';
                            lpr = 'LPR Requested';
                        } else if(data["LQA"] == 1){
                            lqa = 'LQA Requested';
                            lpr = '';
                        } else if (data["LPR"] == 1){
                            lpr = 'LPR Requested';
                            lqa = '';
                        } else {
                            lpr = '';
                            lqa = '';
                        }
                        if(data["STATUSLQA"] == 1 && data["STATUSLPR"] == 1){
                            applqa = 'LQA Approved by';
                            applpr = 'LPR Approved by';
                        } else if(data["STATUSLQA"] == 1){
                            applqa = 'LQA Approved by';
                            applpr = '';
                        } else if (data["STATUSLPR"] == 1){
                            applpr = 'LPR Approved by';
                            applqa = '';
                        } else {
                            applpr = '';
                            applqa = '';
                        }

                        if(data["STATUSTRANSLQA"] == 1 && data["STATUSTRANSLPR"] == 1){
                            translqa = 'LQA Transported by';
                            translpr = 'LPR Transported by';
                        } else if(data["STATUSTRANSLQA"] == 1){
                            translqa = 'LQA Transported by';
                            translpr = '';
                        } else if (data["STATUSTRANSLPR"] == 1){
                            translpr = 'LPR Transported by';
                            translqa = '';
                        } else {
                            translpr = '';
                            translqa = '';
                        }

                        var $transid = "<br><br><label class=form-check-label style=color:black>" +data["TRANSID"]+ "</label>";
                        var $date = "&nbsp;<label class=form-check-label style=font-size:13px>(" +data["DATE"]+")</label>";
                        var $transno = "<b><textarea type=text class=form-control style=color:black disabled>" +data["TRANSNO"]+"</textarea></b>";
                        if(lqa == '' && lpr == ''){
                            var $sendlqa = '';
                            var $datelqa = '';
                            var $sendlpr = '';
                            var $datelpr = '';
                        } else if(lqa == ''){
                            var $sendlqa = '';
                            var $datelqa = '';
                            var $sendlpr = "<br><label class=form-check-label style=color:red>"+lpr+"</label>";
                            var $datelpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELPR"]+"</label>";
                        } else if (lpr == ''){
                            var $sendlqa = "<label class=form-check-label style=color:red>"+lqa+"</label>";
                            var $datelqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELQA"]+"</label>";
                            var $sendlpr = '';
                            var $datelpr = '';
                        } else {
                            var $sendlqa = "<label class=form-check-label style=color:red>"+lqa+"</label>";
                            var $datelqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELQA"]+"</label>";
                            var $sendlpr = "<br><label class=form-check-label style=color:red>"+lpr+"</label>";
                            var $datelpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELPR"]+"</label>";
                        }
                        if(applqa == '' && applpr == ''){
                            var $approvelqa = '';
                            var $dateapprovelqa = '';
                            var $approvelpr = '';
                            var $dateapprovelpr = '';
                        } else if(applqa == ''){
                            var $approvelqa = '';
                            var $dateapprovelqa = '';
                            var $approvelpr = "<br><label class=form-check-label style=color:blue>"+applpr+" " +data["APPROVEBYLPR"]+"</label>";
                            var $dateapprovelpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELPR"]+"</label>";
                        } else if (applpr == ''){
                            var $approvelqa = "<br><label class=form-check-label style=color:blue>"+applqa+" " +data["APPROVEBYLQA"]+"</label>";
                            var $dateapprovelqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELQA"]+"</label>";
                            var $approvelpr = '';
                            var $dateapprovelpr = '';
                        } else {
                            var $approvelqa = "<br><label class=form-check-label style=color:blue>"+applqa+" " +data["APPROVEBYLQA"]+"</label>";
                            var $dateapprovelqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATEAPPROVEBYLQA"]+"</label>";
                            var $approvelpr = "<br><label class=form-check-label style=color:blue>"+applpr+" " +data["APPROVEBYLPR"]+"</label>";
                            var $dateapprovelpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATEAPPROVEBYLPR"]+"</label>";
                        }
                        if(translqa == '' && translpr == ''){
                            var $transportedlqa = '';
                            var $datetransportedlqa = '';
                            var $transportedlpr = '';
                            var $datetransportedlpr = '';
                        } else if(translqa == ''){
                            var $transportedlqa = '';
                            var $datetransportedlqa = '';
                            var $transportedlpr = "<br><label class=form-check-label style=color:green>"+translpr+" " +data["TRANSBYLPR"]+"</label>";
                            var $datetransportedlpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELPR"]+"</label>";
                        } else if (translpr == ''){
                            var $transportedlqa = "<br><label class=form-check-label style=color:green>"+translqa+" " +data["TRANSBYLQA"]+"</label>";
                            var $datetransportedlqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELQA"]+"</label>";
                            var $transportedlpr = '';
                            var $datetransportedlpr = '';
                        } else {
                            var $transportedlqa = "<br><label class=form-check-label style=color:green>"+translqa+" " +data["TRANSBYLQA"]+"</label>";
                            var $datetransportedlqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATETRANSBYLQA"]+"</label>";
                            var $transportedlpr = "<br><label class=form-check-label style=color:green>"+translpr+" " +data["TRANSBYLPR"]+"</label>";
                            var $datetransportedlpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATETRANSBYLPR"]+"</label>";
                        }
                        $viewHistory.append($transid,$date,$transno,$sendlqa,$datelqa,$sendlpr,$datelpr,$approvelqa,$dateapprovelqa,$approvelpr,$dateapprovelpr,$transportedlqa,$datetransportedlqa,$transportedlpr,$datetransportedlpr);
                    });
                    
                    $('#modal-transport form[name="transport"] span[name="listhistory"]').parent().html($viewHistory);  
                  
                        
                },
                error: function (error) {
                    console.error(error);
                },
            })
        });

        $(document).ready(function () {
            $(document).ajaxStart(function () {
                $("#loadings2").show();
            }).ajaxStop(function () {
                $("#loadings2").hide();
            });
        });
        
        $(document).on('click', '.btnhistorytrans1', function(e) {
            var ticketno = $('#modal-transport-mgr input[name="ticketno"]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "/get/historytrans",
                data: {
                    'ticketno' : ticketno, 
                },
                success: function(response) {
                    console.log(response["trq"])
                    var $viewHistory = $(' <div class="form-group"></div>');
                    $.each(response["trq"], function(key, data) {
                        if(data["LQA"] == 1 && data["LPR"] == 1){
                            lqa = 'LQA Requested';
                            lpr = 'LPR Requested';
                        } else if(data["LQA"] == 1){
                            lqa = 'LQA Requested';
                            lpr = '';
                        } else if (data["LPR"] == 1){
                            lpr = 'LPR Requested';
                            lqa = '';
                        } else {
                            lpr = '';
                            lqa = '';
                        }
                        if(data["STATUSLQA"] == 1 && data["STATUSLPR"] == 1){
                            applqa = 'LQA Approved by';
                            applpr = 'LPR Approved by';
                        } else if(data["STATUSLQA"] == 1){
                            applqa = 'LQA Approved by';
                            applpr = '';
                        } else if (data["STATUSLPR"] == 1){
                            applpr = 'LPR Approved by';
                            applqa = '';
                        } else {
                            applpr = '';
                            applqa = '';
                        }

                        if(data["STATUSTRANSLQA"] == 1 && data["STATUSTRANSLPR"] == 1){
                            translqa = 'LQA Transported by';
                            translpr = 'LPR Transported by';
                        } else if(data["STATUSTRANSLQA"] == 1){
                            translqa = 'LQA Transported by';
                            translpr = '';
                        } else if (data["STATUSTRANSLPR"] == 1){
                            translpr = 'LPR Transported by';
                            translqa = '';
                        } else {
                            translpr = '';
                            translqa = '';
                        }

                        var $transid = "<br><br><label class=form-check-label style=color:black>" +data["TRANSID"]+ "</label>";
                        var $date = "&nbsp;<label class=form-check-label style=font-size:13px>(" +data["DATE"]+")</label>";
                        var $transno = "<b><textarea type=text class=form-control style=color:black disabled>" +data["TRANSNO"]+"</textarea></b>";
                        if(lqa == '' && lpr == ''){
                            var $sendlqa = '';
                            var $datelqa = '';
                            var $sendlpr = '';
                            var $datelpr = '';
                        } else if(lqa == ''){
                            var $sendlqa = '';
                            var $datelqa = '';
                            var $sendlpr = "<br><label class=form-check-label style=color:red>"+lpr+"</label>";
                            var $datelpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELPR"]+"</label>";
                        } else if (lpr == ''){
                            var $sendlqa = "<label class=form-check-label style=color:red>"+lqa+"</label>";
                            var $datelqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELQA"]+"</label>";
                            var $sendlpr = '';
                            var $datelpr = '';
                        } else {
                            var $sendlqa = "<label class=form-check-label style=color:red>"+lqa+"</label>";
                            var $datelqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELQA"]+"</label>";
                            var $sendlpr = "<br><label class=form-check-label style=color:red>"+lpr+"</label>";
                            var $datelpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATELPR"]+"</label>";
                        }
                        if(applqa == '' && applpr == ''){
                            var $approvelqa = '';
                            var $dateapprovelqa = '';
                            var $approvelpr = '';
                            var $dateapprovelpr = '';
                        } else if(applqa == ''){
                            var $approvelqa = '';
                            var $dateapprovelqa = '';
                            var $approvelpr = "<br><label class=form-check-label style=color:blue>"+applpr+" " +data["APPROVEBYLPR"]+"</label>";
                            var $dateapprovelpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATEAPPROVEBYLPR"]+"</label>";
                        } else if (applpr == ''){
                            var $approvelqa = "<br><label class=form-check-label style=color:blue>"+applqa+" " +data["APPROVEBYLQA"]+"</label>";
                            var $dateapprovelqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATEAPPROVEBYLQA"]+"</label>";
                            var $approvelpr = '';
                            var $dateapprovelpr = '';
                        } else {
                            var $approvelqa = "<br><label class=form-check-label style=color:blue>"+applqa+" " +data["APPROVEBYLQA"]+"</label>";
                            var $dateapprovelqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATEAPPROVEBYLQA"]+"</label>";
                            var $approvelpr = "<br><label class=form-check-label style=color:blue>"+applpr+" " +data["APPROVEBYLPR"]+"</label>";
                            var $dateapprovelpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATEAPPROVEBYLPR"]+"</label>";
                        }
                        if(translqa == '' && translpr == ''){
                            var $transportedlqa = '';
                            var $datetransportedlqa = '';
                            var $transportedlpr = '';
                            var $datetransportedlpr = '';
                        } else if(translqa == ''){
                            var $transportedlqa = '';
                            var $datetransportedlqa = '';
                            var $transportedlpr = "<br><label class=form-check-label style=color:green>"+translpr+" " +data["TRANSBYLPR"]+"</label>";
                            var $datetransportedlpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATETRANSBYLPR"]+"</label>";
                        } else if (translpr == ''){
                            var $transportedlqa = "<br><label class=form-check-label style=color:green>"+translqa+" " +data["TRANSBYLQA"]+"</label>";
                            var $datetransportedlqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATETRANSBYLQA"]+"</label>";
                            var $transportedlpr = '';
                            var $datetransportedlpr = '';
                        } else {
                            var $transportedlqa = "<br><label class=form-check-label style=color:green>"+translqa+" " +data["TRANSBYLQA"]+"</label>";
                            var $datetransportedlqa = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATETRANSBYLQA"]+"</label>";
                            var $transportedlpr = "<br><label class=form-check-label style=color:green>"+translpr+" " +data["TRANSBYLPR"]+"</label>";
                            var $datetransportedlpr = "&nbsp;<label class=form-check-label style=font-size:13px>" +data["DATETRANSBYLPR"]+"</label>";
                        }
                        $viewHistory.append($transid,$date,$transno,$sendlqa,$datelqa,$sendlpr,$datelpr,$approvelqa,$dateapprovelqa,$approvelpr,$dateapprovelpr,$transportedlqa,$datetransportedlqa,$transportedlpr,$datetransportedlpr);
                    });
                    
                    $('#modal-transport-mgr form[name="transport-mgr"] span[name="listhistory"]').parent().html($viewHistory);
                  
                        
                },
                error: function (error) {
                    console.error(error);
                },
            })
        });

        var table = $('#tiket_list').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            responsive: false,
            searching: true,
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ],
            ajax: "{{ route('get-tiket') }}",
            order: [[ 7, "desc" ]],
            columns: [
                // {
                //     data: 'ticketno',
                //     render: function(data){
                //         if(data != null){
                //             return '';
                //         } else {
                //             return '';
                //         }
                //     }
                // },
                {
                    data: 'action',
                    name: 'action',
                },
                {
                    data: 'ticketno',
                    render: function(data, type, row){
                        return '<a href="javascript:void(0)" class="view btn btn-link" data-ticket="'+row["ticketno"]+'" data-id="'+row["userid"]+'" data-statusid="'+row["statusid"]+'" data-requestor="'+row["requestor"]+'" data-status="'+row["status"]+'" data-category="'+row["category"]+'" data-priority="'+row["priority"]+'" data-subject="'+row["subject"]+'" data-detail="'+row["detail"]+'" data-assignto="'+row["assigned_to"]+'" data-created="'+row["createdby"]+'" data-approve="'+row["approvedby_1"]+'" data-upload="'+row["attachment"]+'" data-approve1name="'+row["approvedby1Name"]+'" data-approveitname="'+row["approvedbyitName"]+'" data-createdname="'+row["createdname"]+'" data-targetdate="'+row["targetdate"]+'" data-approvedby1="'+row["approvedby1_date"]+'" data-approvedbyit="'+row["approvedbyit_date"]+'" data-approvedby_1="'+row["approvedby_1"]+'" data-approvedby_it="'+row["approvedby_it"]+'" data-systemid="'+row["systemid"]+'" data-moduleid="'+row["moduleid"]+'" data-objectid="'+row["objectid"]+'" data-createdon="'+row["createdon"]+'" data-systemname="'+row["systemname"]+'">'+data+'</a>'
                    }
                },
                {
                    data: 'category',
                    render: function(data) {
                        if(data == 'INCIDENT'){
                            statusText = `<span class="badge badge-danger">INCIDENT</span>`;
                        } else if (data == 'CHANGE REQUEST'){
                            statusText = `<span class="badge badge-success">CHANGE REQUEST</span>`;
                        } else if (data == 'HARDWARE'){
                            statusText = `<span class="badge badge-info">HARDWARE</span>`;
                        } else if (data == 'USER REQUEST'){
                            statusText = `<span class="badge badge-warning">USER REQUEST</span>`;
                        } else if (data == 'AUTHORIZATION'){
                            statusText = `<span class="badge badge-dark">AUTHORIZATION</span>`;
                        } else if (data == 'INTERNET ACCESS'){
                            statusText = `<span class="badge badge-primary">INTERNET ACCESS</span>`;
                        } else if (data == 'EMAIL'){
                            statusText = `<span class="badge badge-dark">EMAIL ACCESS</span>`;
                        } else if (data == 'INTERNET'){
                            statusText = `<span class="badge badge-primary">INTERNET</span>`;
                        } else if (data == 'DATA'){
                            statusText = `<span class="badge badge-info">DATA</span>`;
                        } else if (data == 'OTHER'){
                            statusText = `<span class="badge badge-warning">OTHER</span>`;
                        } else if (data == 'IMPROVEMENT'){
                            statusText = `<span class="badge badge-primary">IMPROVEMENT</span>`;
                        } else if (data == 'PROCEDURE'){
                            statusText = `<span class="badge badge-info">PROCEDURE</span>`;
                        } else if (data == 'INFRASTRUCTURE'){
                            statusText = `<span class="badge badge-success">INFRASTRUCTURE</span>`;
                        } else if (data == 'SOFTWARE'){
                            statusText = `<span class="badge badge-dark">SOFTWARE</span>`;
                        } else if (data == 'VPN'){
                            statusText = `<span class="badge badge-warning">VPN</span>`;
                        } else if (data == 'DEPLOY'){
                            statusText = `<span class="badge badge-dark">DEPLOY</span>`;
                        }
                        
                            
                        
                        return statusText;
                    }
                },
                {
                    data: 'status',
                    render: function (data){
                        if(data == "CLOSED"){
                            statusText = `<span class="badge badge-danger">Closed</span>`;
                        } else if(data == "IN PROGRESS"){
                            statusText = `<span class="badge badge-success">In Progress</span>`;
                        } else if(data == "WAITING FOR APPROVAL"){
                            statusText = `<span class="badge badge-warning">Waiting Approval</span>`;
                        } else {
                            statusText = `<span class="badge badge-primary">Open</span>`;
                        }
                        return statusText;
                    }
                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'requestor',
                    name: 'requestor'
                },
                {
                    data: 'assigned_to',
                    name: 'assigned_to'
                },
                {
                    data: 'createdon',
                    render: function(data) {
                        var today = new Date(data);
                        var day = today.getDate() + "";
                        var month = (today.getMonth() + 1) + "";
                        var year = today.getFullYear() + "";
                        var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
                        var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
                        var seconds = today.getSeconds() + "";

                        day = day;
                        month = month;
                        year = year;
                        hour = hour;
                        minutes = minutes;
                        seconds = seconds;
                        // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
                        var date = day + "/" + month + "/" + year;
                        return date;   
                    }
                },
                {
                    data: 'targetdate',
                    render: function(data) {
                        // console.log(data);
                        if(data == ''){
                            var date = "";
                        } else {
                            var today = new Date(data);
                            var day = today.getDate() + "";
                            var month = (today.getMonth() + 1) + "";
                            var year = today.getFullYear() + "";
                            var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
                            var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
                            var seconds = today.getSeconds() + "";

                            day = day;
                            month = month;
                            year = year;
                            hour = hour;
                            minutes = minutes;
                            seconds = seconds;
                            // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
                            var date = day + "/" + month + "/" + year;
                        }
                        return date;   
                    }
                },
            ],
            oLanguage: {
				"sLengthMenu": "Tampilkan _MENU_ data",
				"sProcessing": "Loading...",
				"sSearch": "Keyword:",
				"sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ data" 	
			},
        });

        /* close button reload */
        $(document).on('click', '.close-btn2', function() {
                $("#comment1").load(" #comment1");
                $("#comment2").load(" #comment2");
        });
        $(document).on('click', '.close-btn', function() {
                $("#comment1").load(" #comment1");
                $("#comment2").load(" #comment2");
        });
        $(document).on('click', '.close-btn2-trans', function() {
                $("#history").load(" #history");
                $("#history1").load(" #history1"); 
        });
        $(document).on('click', '.close-btn-trans', function() {
                $("#history").load(" #history");
                $("#history1").load(" #history1");
        });
        /* end */

    });
    function getComment(ticketno) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/get/count/comment",
            data: {
                'ticketno' : ticketno, 
            },
            success: function(response) {
                // console.log(response["disc"])
                var $countComment = $('<div class=form-group></div>');
                
                var $count = "<span style=font-size:11px; color:blue; left: 0px;>(" +response["disc"]+ ")</span>";
                $countComment.append($count);
                
                $('#modal-view-user form[name="view1"] span[name="countcomment"]').parent().html($countComment);  
                
            },
            error: function (error) {
                console.error(error);
            },
        })
    }

    function getCategoryJson(systemid,categoryid) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "/get/category",
            data: {
                'systemid' : systemid, 
            },
            success: function(response) {
                // console.log(categoryid);
                var $select_client = $('<select type="text" id="category" name="category" class="form-control input--style-6" required></select>');
                $.each(response["disc"], function(key, data) {
                    var isSelected = (data["CATEGORYID"]===categoryid)?"selected":"";
                    var $options = "<option value='"+data["CATEGORYID"]+"' "+isSelected+">"+data["DESC"]+"</option>";
                    $select_client.append($options);
                });
                $('#modal-update-user form[name="update"] select[name="category"]').parent().html($select_client);
                
            },
            error: function (error) {
                console.error(error);
            },
        })
    }
</script>
<script>
    $(function() {
        var form = $("#form");
        var select = $("#system");
        var hide1 = $("#module");
        
        hide1.hide();

        select.change(function() {
            value = $(this).find(":selected").val()
            if (value == 'SAP') {
                hide1.show();
            } else {
                hide1.hide();
            }
        });
    });
</script>
<script>
    $(function() {
        var form = $("#transport");
        var select = $("#opsi");
        var hide1 = $("#transid");
        var hide2 = $("#transnumb");

        hide1.hide();
        hide2.hide();

        select.change(function() {
            value = $(this).find(":selected").val()
            if (value == 'exist') {
                hide1.show();
                hide2.hide();
            } else if (value == 'new'){
                hide1.hide();
                hide2.show();
            } else {
                hide1.hide();
                hide2.hide();
            }
        });
    });
</script>
<script>
    var today = new Date();
    var day = today.getDate() + "";
    var month  = (today.getMonth() + 1) + "";
    var year = today.getFullYear() + "";

    var date = day + "-" + month + "-" + year;

    var optSimple = {
        dateFormat: 'yy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
    };
    $( '#createdate' ).datepicker( optSimple );
    $( '#targetdates' ).datepicker( optSimple );
    $( '#createdate' ).datepicker( 'setDate', date );

    window.setTimeout(function() {
        $(".alert-message").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 5000);
</script>
<script>
    $('.toast').toast('show');
</script>
@endsection
