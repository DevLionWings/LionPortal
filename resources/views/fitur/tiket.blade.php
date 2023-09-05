@extends('parent.master')
@section('extend-css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('image-upload/image-uploader.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bootstrap/bootstrap.min.css') }}">
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
                        @if(session('roleid') == 'RD001' || session('roleid') == 'RD004' || session('roleid') == 'RD005' || session('roleid') == 'RD006' || session('roleid') == 'RD007' || session('roleid') == 'RD008')
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
                        @if(session('roleid') == 'RD006')
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
                        <div class="form-group">
                            <label class="form-check-label" for="group">Subject</label>
                            <input type="text" name="subject" class="form-control" id="subject" required>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="detail">Detail Issue</label>
                            <textarea type="text" name="detail" class="form-control" id="detail" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="files">File:</label>
                            <input type="file" name="files" id="files" class="form-control">
                        </div>
                        <!-- <div class="input-field">
                            <label class="active">Upload Photos<small>(max size: 1mb)</small></label>
                            <div class="input-images" style="padding-top: .5rem;"></div>
                        </div> -->
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
                    <button type="button" class="close" data-dismiss="modal" id=close-btn aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" name="view-user" id="view-user">
                    @csrf
                    <div class="modal-body">
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
                                    <label class="form-check-label" for="category" disabled>Category :</label>
                                    <input type="text" name="category" class="form-control" id="category" readonly>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <label class="form-check-label" for="priority" disabled>Priority :</label>
                                    <input type="text" name="priority" class="form-control" id="priority" readonly>
                                </div>
                            </div>
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
                        <div class="form-group">
                            <label class="form-check-label" for="created" disabled>Created Ticket :</label>
                            <input type="text" name="created" class="form-control" id="created" readonly>
                        </div>
                        <hr />
                        <!-- <label class="form-check-label">Display Comment :</label> -->
                        <h4 class="modal-title">Display Comment :</h4>
                        <hr />
                            <div class="form-group">
                                <label class="form-check-label" for="comment_body" disabled>Add Comment</label>
                                <textarea type="text" name="comment_body" class="form-control" id="comment_body" ></textarea>
                            </div>
                            <!-- <div class="form-group">
                                <input type="file" name="filecomment" id="filecomment" class="form-control">
                            </div> -->
                            <div class="form-group">
                                <button type="button" id="btncomment" class="btncomment btn btn-primary btn-xs"><i class="fas fa-comment"></i> Save</button>
                            </div>
                            <div class="form-group">
                                <button type="button" id="viewcomment" class="viewcomment btn btn-link btn-xs"><i class="fas fa-comment"></i> View Comment</button>
                            </div>

                            <div class="form-group" id="viewcomment">
                                <span type="text" name="comment" id="comment" class="modal-input" readonly></span>

                            </div>
                        
                    </div> 
                    <hr />
                    <div class="col-md-6">
                        <div class="mb-3">
                            <button type="button" id=close-btn2 class="btn btn-default" data-dismiss="modal">Close</button>
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
                <form action="{{route('update-tiket')}}" method="post" name='update'>
                    @csrf
                    <input type="hidden" id="update-ticketno" name="ticketno"/>
                    <input type="hidden" id="update-userid" name="userid"/>
                    <input type="hidden" id="update-rejectedby" name="rejectedby"/>
                    <div class="modal-body">
                    <p>Are You Sure ? <span class="text-bold"></span></p>
                        @if(session('roleid') == 'RD006')
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
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="statusid" name="statusid" class="form-control input--style-6" type="hidden" value="SD002">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="status" name="status" class="form-control input--style-6" type="hidden" value="IN PROGRESS">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="approvedbyit_date" name="approvedbyit_date" class="form-control input--style-6" type="hidden" value="<?php echo date('Y-m-d H:i:s'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="approvedbyit" name="approvedbyit" class="form-control input--style-6" type="hidden" value="{{ session('userid') }}">
                            </div>
                        </div>
                        @endif
                        @if(session('roleid') == 'RD002')
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="statusid" name="statusid" class="form-control input--style-6" type="hidden" value="SD001">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="status" name="status" class="form-control input--style-6" type="hidden" value="WAITING APPROVAL IT">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="approvedby1_date" name="approvedby1_date" class="form-control input--style-6" type="hidden" value="<?php echo date('Y-m-d H:i:s'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="approvedby1" name="approvedby1" class="form-control input--style-6" type="hidden" value="{{ session('userid') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="assignto" name="assignto" class="form-control input--style-6" type="hidden" value="101943">
                            </div>
                        </div>
                        @endif
                        @if(session('roleid') == 'RD004' || session('roleid') == 'RD005' || session('roleid') == 'RD007' || session('roleid') == 'RD008')
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="statusid" name="statusid" class="form-control input--style-6" type="hidden" value="SD002">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="status" name="status" class="form-control input--style-6" type="hidden" value="IN PROGRESS">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="approvedbyit" name="approvedbyit" class="form-control input--style-6" type="hidden" value="{{ session('mgrid') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="approvedby1" name="approvedby1" class="form-control input--style-6" type="hidden" value="{{ session('mgrid') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="assignto" name="assignto" class="form-control input--style-6" type="hidden" value="{{ session('userid') }}">
                            </div>
                        </div>
                        @endif
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

<script>
    $('.nav-link.active').removeClass('active');
    $('#m-tiket').addClass('active');
    $('#m-tiket').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script >
    $(function () {    
        var $btn_submit = $("button#btn-sumbit-ticket");

        //Initialize Select2 Elements
        $('.select2').select2()
        $('.datepicker').daterangepicker();

        $('#save-btn').on('click', function() {
            $('#form').submit();
            $(this).attr('disabled', true);
            $(this).text("Loading ...");
        });

        $(document).on('click', '.view', function() {
            // getComment($(this).attr('data-ticket'));
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
            var created  = $(this).attr('data-created');
            var approve  = $(this).attr('data-approve1name');
            var approveit  = $(this).attr('data-approveitname');
            var upload  = $(this).attr('data-upload');
            var $modal = $('#modal-view-user');
            var $form = $modal.find('form[name="view-user"]');
            
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
            $form.find('input[name="approve"]').val(approve);
            $form.find('input[name="approveit"]').val(approveit);
            $form.find('input[name="comment_body"]').val(comment_body);
            $form.find('input[name="upload"]').val(upload);
            $modal.modal('show');
        });

        $(document).on('click', '.update', function () {
            $('#modal-update-user').modal({backdrop: 'static', keyboard: false})  
            $('#update-ticketno').val($(this).attr("data-ticketno"));
            $('#update-userid').val($(this).attr("data-userid"));
            $('#update-assignto').val($(this).attr("data-assignto"));
            $('#update-approvedby1').val($(this).attr("data-approvedby1"));
            $('#update-approvedbyit').val($(this).attr("data-approvedbyit"));
            $('#update-rejectedby').val($(this).attr("data-rejectedby"));
            $('#update-statusid').val($(this).attr("data-statusid"));
            $('#update-approvedby1_date').val($(this).attr("data-approvedby1_date"));
            $('#update-approvedbyit_date').val($(this).attr("data-approvedbyit_date"));
            $('#modal-update-user').modal('show');
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
                processing: true,
                serverSide: true,
                responsive: true,
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
                order: [[ 0, "desc" ]],
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
                            return '<a href="javascript:void(0)" class="view btn btn-link" data-ticket="'+row["ticketno"]+'" data-id="'+row["userid"]+'" data-statusid="'+row["statusid"]+'" data-requestor="'+row["requestor"]+'" data-status="'+row["status"]+'" data-category="'+row["category"]+'" data-priority="'+row["priority"]+'" data-subject="'+row["subject"]+'" data-detail="'+row["detail"]+'" data-assignto="'+row["assigned_to"]+'" data-created="'+row["createdby"]+'" data-approve="'+row["approvedby_1"]+'" data-upload="'+row["attachment"]+'" data-approve1name="'+row["approvedby1Name"]+'" data-approveitname="'+row["approvedbyitName"]+'">'+data+'</a>'
                        }
                    },
                    {
                        data: 'category',
                        render: function(data) {
                            if(data == 'INCIDENT'){
                            statusText = `<span class="badge badge-danger">INCIDENT</span>`;
                            } else if (data == 'SAP CHANGE REQUEST'){
                                statusText = `<span class="badge badge-success">CHANGE REQUEST</span>`;
                            } else if (data == 'NON SAP CHANGE REQUEST'){
                                statusText = `<span class="badge badge-info">NON SAP CHANGE REQUEST</span>`;
                            } else if (data == 'USER SAP REQUEST'){
                                statusText = `<span class="badge badge-warning">USER SAP REQUEST</span>`;
                            } else if (data == 'SAP AUTHORIZATION'){
                                statusText = `<span class="badge badge-dark">SAP AUTHORIZATION</span>`;
                            } else if (data == 'INTERNET ACCESS'){
                                statusText = `<span class="badge badge-primary">INTERNET ACCESS</span>`;
                            } else if (data == 'EMAIL ACCESS'){
                                statusText = `<span class="badge badge-dark">EMAIL ACCESS</span>`;
                            } else if (data == 'NEW HARDWARE'){
                                statusText = `<span class="badge badge-success">NEW HARDWARE</span>`;
                            } else if (data == 'NON SAP ACCESS'){
                                statusText = `<span class="badge badge-info">NON SAP ACCESS</span>`;
                            } else if (data == 'OTHER'){
                                statusText = `<span class="badge badge-dark">OTHER</span>`;
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

        var table = $('#tiket_list').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: true,
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ],
            ajax: "{{ route('get-tiket') }}",
            order: [[ 0, "desc" ]],
            autoWidth: false,
            fixedColumns: false,
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
                        return '<a href="javascript:void(0)" class="view btn btn-link" data-ticket="'+row["ticketno"]+'" data-id="'+row["userid"]+'" data-statusid="'+row["statusid"]+'" data-requestor="'+row["requestor"]+'" data-status="'+row["status"]+'" data-category="'+row["category"]+'" data-priority="'+row["priority"]+'" data-subject="'+row["subject"]+'" data-detail="'+row["detail"]+'" data-assignto="'+row["assigned_to"]+'" data-created="'+row["createdby"]+'" data-approve="'+row["approvedby_1"]+'" data-upload="'+row["attachment"]+'" data-approve1name="'+row["approvedby1Name"]+'" data-approveitname="'+row["approvedbyitName"]+'">'+data+'</a>'
                    }
                },
                {
                    data: 'category',
                    render: function(data) {
                        if(data == 'INCIDENT'){
                            statusText = `<span class="badge badge-danger">INCIDENT</span>`;
                        } else if (data == 'SAP CHANGE REQUEST'){
                            statusText = `<span class="badge badge-success">CHANGE REQUEST</span>`;
                        } else if (data == 'NON SAP CHANGE REQUEST'){
                            statusText = `<span class="badge badge-info">NON SAP CHANGE REQUEST</span>`;
                        } else if (data == 'USER SAP REQUEST'){
                            statusText = `<span class="badge badge-warning">USER SAP REQUEST</span>`;
                        } else if (data == 'SAP AUTHORIZATION'){
                            statusText = `<span class="badge badge-dark">SAP AUTHORIZATION</span>`;
                        } else if (data == 'INTERNET ACCESS'){
                            statusText = `<span class="badge badge-primary">INTERNET ACCESS</span>`;
                        } else if (data == 'EMAIL ACCESS'){
                            statusText = `<span class="badge badge-dark">EMAIL ACCESS</span>`;
                        } else if (data == 'NEW HARDWARE'){
                            statusText = `<span class="badge badge-success">NEW HARDWARE</span>`;
                        } else if (data == 'NON SAP ACCESS'){
                            statusText = `<span class="badge badge-info">NON SAP ACCESS</span>`;
                        } else if (data == 'OTHER'){
                            statusText = `<span class="badge badge-dark">OTHER</span>`;
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
            ],
            oLanguage: {
				"sLengthMenu": "Tampilkan _MENU_ data",
				"sProcessing": "Loading...",
				"sSearch": "Keyword:",
				"sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ data" 	
			},
        });

        $(document).on('click', '.viewcomment', function() {
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
                    var $viewComment = $(' <div class="form-group"></div>');
                    $.each(response["disc"], function(key, data) {
                        var $nama = "<label class=form-check-label style=color:red>"+data["SENDER"]+ "</label>";
                        var $date = "<label class=form-check-label style=font-size:11px>" +data["DATE"]+"<label>";
                        var $comment = "<textarea type=text class=form-control style=font-family:'Courier New';font-size:30px readonly>" +data["COMMENT"]+"</textarea>";
                        var $filecomment = " <button download id=file name=file class=btn btn-link btn-sm style=font-size:13px>"+data["FILE"]+"</button>"
                        $viewComment.append($nama,$date,$filecomment,$comment);
                    });
    
                    $('#modal-view-user form[name="view-user"] span[name="comment"]').parent().html($viewComment);  
                             
                },
                error: function (error) {
                    console.error(error);
                },
            })
        });

        $(document).on('click', '.btncomment', function() {
            var ticketno = $('#modal-view-user input[name="ticketno"]').val();
            var comment_body = $('#modal-view-user  form[name="view-user"] textarea[name="comment_body"]').val();
            var file_data = $('#modal-view-user  form[name="view-user"] input[name="filecomment"]').val();
            // const file_data = $('#filecomment').prop('files')[0];
            // var filecomment = document.getElementById("filecomment").files[0].name;
            // var file_data = $('#filecomment').prop('files')[0];  
            // var formData = new FormData(); 
            // formData.append("filecomment", file_data);

            // console.log(formData);
         
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/add/comment",
                type: 'POST',
                data: {
                    'ticketno' : ticketno, 
                    'comment_body' : comment_body,
                    'filecomment' : file_data
                },
                // contentType: true, 
                // processData: true,
                success: function(response){ 
                    // console.log(response["disc"]);
                    var $viewComment = $('.modal-content .modal-body');
                    var target = $viewComment.find('form-group .modal-input');
                   
                    $.each(response["disc"], function(key, data) {
                        var $nama = "<label class=form-check-label style=color:red>"+data["SENDER"]+"</label>";
                        var $date = "<label class=form-check-label style=font-size:10px>"+data["DATE"]+"<label>";
                        var $comment = "<textarea type=text class=form-control style=font-family:'Courier New';font-size:20px readonly>"+data["COMMENT"]+"</textarea>";
                        var $filecomment = "<a type=submit id=file name=file class=btn btn-link btn-sm style=font-size:15px readonly>"+data["FILE"]+"</a>"
                        $viewComment.append($nama,$date,$filecomment,$comment,);
                    });
    
                    $('#modal-view-user form[name="view-user"] input[name="comment"]').parent().modal('show');
        
                },
                error: function (error) {
                    console.error(error);
                },
            });
        })

        $(document).on('click', '.upload', function() {
            var upload = $('#modal-view-user input[name="upload"]').val();
         
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "/download/file",
                data: {
                    'upload' : upload, 
                },
                success: function(response){ 
                    console.log(response);
                    window.open(response);
                    // var blob = new Blob([response]);
                    // var link = document.createElement('a');
                    // link.href = window.URL.createObjectURL(blob);
                    // link.download = response;
                    // link.click();
                }
            });
        })

    });

    // function getComment(ticketno) {
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: "POST",
    //         url: "/get/comment",
    //         data: {
    //             'ticketno' : ticketno, 
    //         },
    //         success: function(response) {
    //             // console.log(response["disc"])
    //             var $viewComment = $(' <div class="form-group"></div>');
    //             $.each(response["disc"], function(key, data) {
    //                 var $nama = "<label class=form-check-label style=color:red>"+data["SENDER"]+ "</label>";
    //                 var $date = "<label class=form-check-label style=font-size:11px>" +data["DATE"]+"<label>";
    //                 var $comment = "<textarea type=text class=form-control style=font-family:'Courier New';font-size:30px readonly>" +data["COMMENT"]+"</textarea>";
    //                 var $filecomment = " <button download id=file name=file class=btn btn-link btn-sm style=font-size:13px>"+data["FILE"]+"</button>"
    //                 $viewComment.append($nama,$date,$filecomment,$comment);
    //             });
                
    //             $('#modal-view-user form[name="view-user"] input[name="comment"]').parent().html($viewComment);
    //             // $('#modal-view-user form[name="view-user"] input[name="comment"]').remove();
    //         }
    //     })
    // }
</script>

<script>
    window.setTimeout(function() {
    $(".alert-message").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 5000);
</script>
<script>
    // $('#document').ready(function(){
    //         $('#close-btn').on('click', function(){
    //             location.reload();
    //     });
    // });
    // $('#document').ready(function(){
    //         $('#close-btn2').on('click', function(){
    //             location.reload();
    //     });
    // });
</script>
<script>
    $('.toast').toast('show');
</script>
@endsection
