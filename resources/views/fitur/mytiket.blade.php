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
                    <h5>My Ticket</h5>
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
    <!-- /.content -->
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
                                    <label class="form-check-label" for="objectid" disabled>Object Type :</label>
                                    <input type="text" name="objectid" class="form-control" id="objectid" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="moduleid" disabled>Module :</label>
                            <input type="text" name="moduleid" class="form-control" id="moduleid" readonly>
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
                            <textarea type="text" name="comment_body" class="form-control" id="comment_body" placeholder="Write a comment..."></textarea>
                            <button type="button" id="btncomment" class="btncomment btn btn-primary btn-xs">Save</button>
                        </div>
                        <!-- <div class="form-group">
                            <input type="file" name="filecomment" id="filecomment" class="form-control">
                        </div> -->
                        <div class="row">
                            <div class="col-md-3"> 
                                <button type="button" id="viewcomment" class="viewcomment btn btn-link btn-xs"><i class="fas fa-comment"></i> Show Details</button>
                                <div id="loadings">
                                    Loading...
                                </div>
                            </div>
                            <div class="col-md-1" id="comment2"> 
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
                <form action="{{route('close-mytiket')}}" method="post" name='closed'>
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
                        <button type="submit" class="btn btn-danger">Yes</button>
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
<script src="{{ asset('image-upload/image-uploader.js') }}"></script>
<script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/jquery/jquery-ui.js') }}"></script>
@if(session('file_url'))
    <script>
        $(document).ready(function(){
            window.open("{{session('file_url')}}"); // will open new tab on document ready
        });
    </script>
@endif
<script>
    $('.nav-link.active').removeClass('active');
    $('#m-mytiket').addClass('active');
    $('#m-mytiket').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script>
    $(function () {    
        var today = new Date();
        var day = today.getDate() + "";
        var month = (today.getMonth() + 1) + "";
        var year = today.getFullYear() + "";
        var hour = today.getHours() + "";
        var minutes = today.getMinutes() + "";
        var seconds = today.getSeconds() + "";

        day = day;
        month = month;
        year = year;
        hour = hour;
        minutes = minutes;
        seconds = seconds;

        var date_range = year + "-" + month + "-" + day;
        var $btn_submit = $("button#btn-sumbit-ticket");

        //Initialize Select2 Elements
        $('.select2').select2()
        $('.datepicker').daterangepicker();

        $('#save-btn').on('click', function () {
            // console.log("clicked");
            var $inputs = $('modal-add-ticket form[name="ticket"] :input');
            $inputs.each(function () {
                if ($(this).attr('type') !== "button" && $(this).attr('type') !== undefined) {
                    if ($(this).val() === "") {
                        if ($(this).attr('name') === "category") {
                            $(this).parent().parent().parent().append(
                                '<span class="text-danger">Invalid input</span>');
                        } else {
                            $(this).parent().append(
                                '<span class="text-danger">Invalid input</span>');
                        }
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                        if ($(this).attr('name') === "subject") {
                            $(this).parent().parent().parent().children("span").remove();
                        } else {
                            $(this).parent().children("span").remove();
                        }
                    }
                }
            });
            var $form_valid = $('form[name="ticket"] :input.is-invalid');
            if ($form_valid.length === 0) {
                $('form[name="ticket"]').submit();
            }
        });

        $(document).on('click', '.view', function() {
            $("#comment1").load(" #comment1");
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
            var moduleid  = $(this).attr('data-moduleid');
            var objectid  = $(this).attr('data-objectid');
            var upload  = $(this).attr('data-upload');
            var createdon  = $(this).attr('data-createdon');
            var $modal = $('#modal-view-user');
            var $form = $modal.find('form[name="view-user"]');
            var hide = $("#hidecmnt");
            hide.show();
            if(statusid == 'SD003'){
                hide.hide();
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
            $form.find('input[name="systemid"]').val(systemid);
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

        var table = $('#tiket_list').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            responsive: false,
            searching: true,
            ajax: "{{ route('my-tiket') }}",
            order: [[ 7, "desc" ]],
            dom: 'Blfrtip',
                buttons: [
                    'excel'
            ],
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
                        return '<a href="javascript:void(0)" class="view btn btn-link" data-ticket="'+row["ticketno"]+'" data-id="'+row["userid"]+'" data-statusid="'+row["statusid"]+'" data-requestor="'+row["requestor"]+'" data-status="'+row["status"]+'" data-category="'+row["category"]+'" data-priority="'+row["priority"]+'" data-subject="'+row["subject"]+'" data-detail="'+row["detail"]+'" data-assignto="'+row["assigned_to"]+'" data-created="'+row["createdby"]+'" data-approve="'+row["approvedby_1"]+'" data-upload="'+row["attachment"]+'" data-approve1name="'+row["approvedby1Name"]+'" data-approveitname="'+row["approvedbyitName"]+'" data-createdname="'+row["createdname"]+'" data-targetdate="'+row["targetdate"]+'" data-approvedby_1="'+row["approvedby_1"]+'" data-approvedby_it="'+row["approvedby_it"]+'" data-systemid="'+row["systemid"]+'" data-moduleid="'+row["moduleid"]+'" data-objectid="'+row["objectid"]+'" data-createdon="'+row["createdon"]+'">'+data+'</a>'
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
                        console.log(data);
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
				"sSearch": "Keyword :",
				"sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ data" 	
			},
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

        $(document).ready(function () {
            $(document).ajaxStart(function () {
                $("#loadings").show();
            }).ajaxStop(function () {
                $("#loadings").hide();
            });
        });

        $(document).on('click', '.btncomment', function() {
            $("#comment2").load(" #comment2");
            var ticketno = $('#modal-view-user form[name="view-user"] input[name="ticketno"]').val();
            var requestor = $('#modal-view-user form[name="view-user"] input[name="requestorid"]').val();
            var approve = $('#modal-view-user form[name="view-user"] input[name="approveId"]').val();
            var approveit = $('#modal-view-user form[name="view-user"] input[name="approveItId"]').val();
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
                    'requestor' : requestor,
                    'approve' : approve,
                    'approveit' : approveit, 
                    'comment_body' : comment_body,
                    'filecomment' : file_data
                },
                success: function(response){ 
                    // console.log(response);
                    // var $viewComment = $('.modal-content .modal-body');
                    // var target = $viewComment.find('form-group .modal-input');
                    // var $viewComment = $(' <div class="form-group"></div>');
                    var $viewComment = $('<div class=form-group id=comment1>'); 
                    $.each(response["disc"], function(key, data) {
                        var $nama = "<label class=form-check-label style=color:red>"+data["SENDER"]+"</label>";
                        var $date = "<label class=form-check-label style=font-size:11px>"+data["DATE"]+"<label>";
                        var $comment = "<textarea type=text class=form-control style=font-family:'Courier New';font-size:20px readonly>"+data["COMMENT"]+"</textarea>";
                        var $filecomment = "<a type=submit id=file name=file class=btn btn-link btn-sm style=font-size:15px readonly>"+data["FILE"]+"</a>"
                        $viewComment.append($nama,$date,$filecomment,$comment);
                    });
                    document.getElementById("comment_body").value = "";
                    $('#modal-view-user form[name="view-user"] input[name="comment"]').parent().html($viewComment);
                    getComment(ticketno);
        
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
                    // console.log(response);
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
                var $countComment = $(' <div class="form-group"></div>');
                
                var $count = "<span style=font-size:11px; color:blue; text-align: left;>(" +response["disc"]+ ")</span>";
                $countComment.append($count);
                
                $('#modal-view-user form[name="view-user"] span[name="countcomment"]').parent().html($countComment);  
                
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
    $( '#targetdates' ).datepicker( optSimple );

    window.setTimeout(function() {
    $(".alert-message").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 5000);
</script>
<script>
     $('#document').ready(function(){
        $('#close-btn').on('click', function(){
            $("#comment1").load(" #comment1");
            // $("#comment2").load(" #comment2");
        });
    });
    $('#document').ready(function(){
        $('#close-btn2').on('click', function(){    
            $("#comment1").load(" #comment1"); 
            // $("#comment2").load(" #comment2");
        });
    });
</script>
<script>
    $('.toast').toast('show');
</script>
@endsection
