@extends('parent.master')
@section('extend-css')
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
                    <h5>Admin Room Meeting</h5>
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
                    <div class="row mb-2">
                        <!-- <div class="col-sm-12">
                            <h6>Tickets for a Week</h6>
                        </div> -->
                        <!-- <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-door-open"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Available</span>
                                <span class="info-box-number" name="available">
                                    0
                                </span>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-book"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Booked</span>
                                <span class="info-box-number" name="booked">
                                    0
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fa fa-window-close"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Canceled</span>
                                <span class="info-box-number" name="canceled">
                                    0
                                </span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-plus"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">New Room</span>
                                <span class="info-box-number" name="newroom">
                                    0
                                </span>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="float-sm-right">
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#modal-add-room"><i class="fa fa-plus" aria-hidden="true"></i> New Room</button>
                            </div>
                            <div class="float-sm-left">
                                <button type="button" class="btnbookroom btn btn-success" id="btnbookroom" ><i class="fa fa-plus" aria-hidden="true"></i> Book Room</button>
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
                            <table id="room_list" class="table table-bordered table-hover display nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th>Room ID</th>
                                        <th>Room Name</th>
                                        <th>Floor</th>
                                        <th>Capacity</th>
                                        <th>Status Room</th>
                                        <th>Subject</th>
                                        <th>User Booked</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Booked Date</th>
                                        <th>Booked By</th>
                                        <th>Action</th>
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
    <div id="modal-add-room" class="modal fade show" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Room</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="form" name="form" action="{{ route('add-room') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-check-label" >Room Name :</label>
                            <input type="text" name="roomname" id="roomname" class="form-control"> 
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="roomfloor">Floor :</label>
                            <input type="text" name="roomfloor" class="form-control" id="roomfloor" maxlength="3"required>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="roomcapacity">Capacity :</label>
                            <textarea type="text" name="roomcapacity" class="form-control" id="roomcapacity" maxlength="6" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label">Room Public :</label>
                            <div class="input-group value">
                                <select id="roompublic" name="roompublic" class="form-control input--style-6">
                                    <option value="1">yes</option>
                                    <option value="0">no</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label">Active :</label>
                            <div class="input-group value">
                                <select id="active" name="active" class="form-control input--style-6">
                                    <option value="1">yes</option>
                                    <option value="0">no</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label">Area :</label>
                            <div class="input-group value">
                                <select id="plantid" name="plantid" class="form-control input--style-6">
                                    <option value="PD001">Head Office</option>
                                    <option value="PD002">Cakung</option>
                                    <option value="PD0023">Gresik</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="save-btn" class="btn btn-primary">Add Room</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <div id="modal-booked-user"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Booking Room Meeting</h4>
                    <button type="button" class="close btnclose" id="btnclose" nama="btnclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('book-room')}}" method="post" name='booked-user' id='booked-user'>
                    @csrf
                    <div class="modal-body">
                    <input id="userid" name="userid" class="form-control input--style-6" type="hidden" value="{{ session('userid') }}">
                        <div class="form-group">
                            <label class="form-check-label">Booking By :</label>
                            <div class="input-group value">
                                <select id="bookedby" name="bookedby" class="btnbooked" style="width: 100%;">
                                <option value="10"> Pilih User Request</option>
                                    @foreach($usreq as $usreqcode)
                                    <option value="{{ $usreqcode['NAME'] }}">{{ $usreqcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="subject" disabled>Subject :</label>
                            <input type="text" name="subject" class="form-control" id="subject">
                        </div>
                        <input type="checkbox" class="largerCheckbox" name="range" id="range">
                        <label for="">Range Date</label><br>    
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label" for="startdate">Start Date:</label>
                                        <input type="text" name="startdate" id="startdate" class="btndate form-control" value=""> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group" id="hidedate">
                                        <label class="form-check-label" for="enddate">End Date:</label>
                                        <input type="text" name="enddate" id="enddate" class="btndate form-control" value=""> 
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label">Start Time :</label>
                                        <div class="input-group value">
                                            <select  type="text" id="starttime" name="starttime" class="form-control" style="width: 100%;" onchange="myFunction(event)">
                                                <option value="">--:--:--</option>
                                                @foreach($tm as $tmcode)
                                                <option value="{{ $tmcode['START'] }}">{{ $tmcode['START'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label">End Time :</label>
                                        <div class="input-group value">
                                            <select  type="text" id="endtime" name="endtime" class="form-control" style="width: 100%;" onchange="myFunction()">
                                                <option value="">--:--:--</option>
                                                @foreach($tm as $tmcode)
                                                <option value="{{ $tmcode['END'] }}">{{ $tmcode['END'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label class="form-check-label" for="detail" disabled>Description :</label>
                            <textarea type="text" name="detail" class="form-control" id="detail" rows="4" cols="50"></textarea>
                        </div> 
                        <div class="form-group">
                            <button type="button" id="btnroom" name="btnroom" class="btnroom btn btn-link"><i class="fas fa-sync fa" aria-hidden="true"> Chose Room Meeting</i></button>
                            <div id="loadings">
                                Loading...
                            </div>
                        </div> 
                        <div class="form-group" id="hideroom">   
                            <label class="form-check-label" for="roomAvail">Room Meeting Available :</label>
                            <select type="text" id="roomAvail" name="roomAvail" class="btnbooked" style="width: 100%;">
                                <option value="">Chose Room Meeting</option>
                            </select>  
                        </div> 
                        <div class="form-group" id="hideroombooked">   
                            <span class="form-check-label" id="roomBook" name="roomBook">Not Available : </span>
                        </div> 
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default btnclose" id="btnclose" data-dismiss="modal">Close</button>
                        <button type="button" id="book-btn" class="btn btn-success" >Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-edit-room"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Room</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('edit-room')}}" method="post" name='edit-room'>
                    @csrf
                    <div class="modal-body">
                    <input type="hidden" id="bookid" name="bookid">
                    <input type="hidden" id="userid" name="userid"/>
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label" for="startdate1">Start Date:</label>
                                        <input type="text" name="startdate1" id="startdate1" class="btndate1 form-control" > 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label" for="enddate1">End Date:</label>
                                        <input type="text" name="enddate1" id="enddate1" class="btndate1 form-control" > 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label">Start Time :</label>
                                        <div class="input-group value">
                                            <select  type="text" id="starttime1" name="starttime1" class="form-control" style="width: 100%;"  onchange="myFunction1(event)">
                                                <option value="">--:--:--</option>
                                                @foreach($tm as $tmcode)
                                                <option value="{{ $tmcode['START'] }}">{{ $tmcode['START'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label">End Time :</label>
                                        <div class="input-group value">
                                            <select  type="text" id="endtime1" name="endtime1" class="form-control" style="width: 100%;"  onchange="myFunction1(event)">
                                                <option value="">--:--:--</option>
                                                @foreach($tm as $tmcode)
                                                <option value="{{ $tmcode['END'] }}">{{ $tmcode['END'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="form-group">
                            <button type="button" id="editroom" name="editroom" class="editroom btn btn-link"><i class="fas fa-sync fa" aria-hidden="true"> Chose Room Meeting</i></button>
                        </div> 
                        <div class="form-group" id="hideroomedit">   
                            <label class="form-check-label" for="roomAvail1">Room Meeting Available :</label>
                            <select type="text" id="roomAvail1" name="roomAvail1" class="form-control" style="width: 100%;">
                                <option value="">Chose Room Meeting</option>
                            </select>  
                        </div> 
                        <div class="form-group" id="hideroombookededit">   
                            <span class="form-check-label" id="roomBook1" name="roomBook1">Not Available : </span>
                        </div> 
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default btnclose" id="btnclose" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-cancel-room"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Canceled Room</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('cancel-room')}}" method="post" name='cancel'>
                    @csrf
                    <input type="hidden" id="roomAvail" name="roomAvail"/>
                    <input type="hidden" id="bookid" name="bookid"/>
                    <input type="hidden" id="userid" name="userid" value="{{ session('userid') }}"/>
                    <input type="hidden" id="username" name="username" value="{{ session('username') }}"/>
                    <div class="modal-body">
                        <p>Are You Sure ? <span class="text-bold"></span></p>
                        <div class="form-group">
                            <div class="input-group value">
                                <input id="desc" name="desc" class="form-control input--style-6" type="text" value="Canceled Room">
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
    <div id="modal-avail-room"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Avail Room</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('avail-room')}}" method="post" name='avail'>
                    @csrf
                    <input type="text" id="bookid" name="bookid"/>
                    <div class="modal-body">
                        <p>Are You Sure ? <span class="text-bold"></span></p>
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
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/jquery/jquery-ui.js') }}"></script>

<script>
    $('.nav-link.active').removeClass('active');
    $('#m-adminroom').addClass('active');
    $('#m-adminroom').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script>
    function Initialize()
    {  
        $('.btnbooked').select2({
            allowClear: true,
            width: '100%'
        });
        $('.editbooked').select2({
            allowClear: true,
            width: '100%'
        });
    } 
</script>
<script>
    $(function () {  
        var hide1 = $("#hideroom");
        var hide2 = $("#hideroombooked");
        var hide3 = $("#hideroomedit");
        var hide4 = $("#hideroombookededit");
        var hide5 = $("#loadings");
        hide1.hide();
        hide2.hide();
        hide3.hide();
        hide4.hide();
        hide5.hide();

        $('#save-btn').on('click', function() {
            $('#form').submit();
            $(this).attr('disabled', true);
            $(this).text("Loading ...");
        });
        $('#book-btn').prop('disabled', true);
        $('#book-btn').on('click', function() {
            $('#booked-user').submit();
            $(this).attr('disabled', true);
            $(this).text("Loading ...");
        });

        //Initialize Select2 Elements
        $('.btnbooked').select2({
            allowClear: true,
            width: '100%'
        });
        $('.editbooked').select2({
            allowClear: true,
            width: '100%'
        });

        // format date time //
        var today = new Date();
        var day = today.getDate() + "";
        var month = (today.getMonth() + 1) + "";
        var year = today.getFullYear() + "";
        var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
        var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
        var seconds = today.getSeconds() + "";

        if (day < 10) {
            day = '0' + day;
        }

        if (month < 10) {
            month = `0${month}`;
        }

        var time = hour + ":" + minutes + ":" + seconds;
        var date = year + "-" + month + "-" + day;
        // end //

        //Initialize datepicker Elements
        var optSimple = {
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            onSelect: function(a) {
                $('.btnbooked').select2();
                var startdate = $('#modal-booked-user input[name="startdate"]').val();
                var enddate = $('#modal-booked-user input[name="enddate"]').val();
                // $( '#enddate' ).datepicker( 'setDate', startdate );
                // Modals booked //
                $("select option").each(function() {
                    var $thisOption = $(this);
                    if(startdate == date){
                        if($thisOption.val() < time) {
                            $thisOption.attr("disabled", true);
                        }
                    } else {
                        $thisOption.attr("disabled", false);
                    }
                }); 
            }
        };
        var optSimple1 = {
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            onSelect: function(a) {
                $('.editbooked').select2();
                var startdate1 = $('#modal-edit-room input[name="startdate1"]').val();
                var enddate1 = $('#modal-edit-room input[name="enddate1"]').val();
                // $( '#enddate1' ).datepicker( 'setDate', startdate1 );
                // Modals Edit //
                $("select option").each(function() {
                    var $thisOption = $(this);
                    if(startdate1 == date){
                        if($thisOption.val() < time) {
                            $thisOption.attr("disabled", true);
                        }
                    } else {
                        $thisOption.attr("disabled", false);
                    }
                });
            }
        };
        $( '#startdate' ).datepicker( optSimple );
        $( '#enddate' ).datepicker( optSimple );
        $( '#startdate1' ).datepicker( optSimple1 );
        $( '#enddate1' ).datepicker( optSimple1 );
        $( '#startdate, #startdate1').datepicker( 'setDate', date );
        // end //

        // Checked option time //
        var startdate = $('#modal-booked-user input[name="startdate"]').val();
        var enddate = $('#modal-booked-user input[name="enddate"]').val();
        $("select option").each(function() {
            var $thisOption = $(this);
            if(startdate == date){
                if($thisOption.val() < time) {
                    $thisOption.attr("disabled", true);
                }
            } else {
                $thisOption.attr("disabled", false);
            }
        });
        //  Cheked option time //

        $( '#enddate, #enddate1').datepicker( 'setDate', startdate );

        $(document).on('click', '.cancel', function () {
            var bookid = $(this).data('bookid');
            var roomid = $(this).data('roomid');
            var $modal = $('#modal-cancel-room');
            var $form = $modal.find('form[name="cancel"]');
            $form.find('input[name="bookid"]').val(bookid);
            $form.find('input[name="roomAvail"]').val(roomid);
            $modal.modal('show');
        })

        // $(document).on('click', '.avail', function () {
        //     $('#bookid').val($(this).attr("data-bookid"));
        //     $('#modal-avail-room').modal('show');
        // })

        var table = $('#room_list').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            responsive: false,
            searching: true,
            lengthChange: false,
            ajax: "{{ route('room-list') }}",
            order: [[ 3, "desc" ]],
            dom: 'Blfrtip',
                buttons: [
                    'excel'
            ],
            columns: [
                {
                    data: 'roomid',
                    name: 'roomid'
                },
                {
                    data: 'roomname',
                    render: function(data){
                        statusText = ` <i class="nav-icon fas fa-door-open"></i>`;
                        return statusText + ' ' + data;
                    }
                },
                {
                    data: 'roomfloor',
                    render: function(data){
                        statusText = `<i class="fas fa-building" aria-hidden="true"></i>`;
                        return statusText + ' ' + data;
                    }
                },
                {
                    data: 'roomcapacity',
                    render: function(data){
                        statusText = `<i class="fa fa-users" aria-hidden="true"></i>`;
                        return statusText + ' ' + data;
                    }
                },
                {
                    data: 'statusroom',
                    render: function(data){
                        if(data == '0'){
                            statusText = `<span class="badge badge-success">Available</span>`;
                        } else if(data == '1'){
                            statusText = `<span class="badge badge-danger">Booked</span>`;
                        } else {
                            statusText = `<span class="badge badge-info">Canceled</span>`;
                        }
                        return statusText;

                    }
                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'username',
                    render: function(data){
                        statusText = `<i class="fas fa-user-clock"></i>`;
                        return statusText + ' ' + data;
                    }
                },
                 {
                    data: 'startdate',
                    render : function(data) {
                        var today = new Date(data);
                        var day = today.getDate() + "";
                        var month = (today.getMonth() + 1) + "";
                        var year = today.getFullYear() + "";
                        var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
                        var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
                        var seconds = today.getSeconds() + "";
                        if (day < 10) {
                            day = '0' + day;
                        }

                        if (month < 10) {
                            month = `0${month}`;
                        }
                        year = year;
                        hour = hour;
                        minutes = minutes;
                        seconds = seconds;
                        // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
                        var date = day + "-" + month + "-" + year;
                        statusText = `<i class="far fa-calendar-alt"></i>`;
                        return statusText + ' ' + data;    
                    }
                },
                {
                    data: 'enddate',
                    render: function(data){
                        var today = new Date(data);
                        var day = today.getDate() + "";
                        var month = (today.getMonth() + 1) + "";
                        var year = today.getFullYear() + "";
                        var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
                        var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
                        var seconds = today.getSeconds() + "";
                        if (day < 10) {
                            day = '0' + day;
                        }

                        if (month < 10) {
                            month = `0${month}`;
                        }
                        year = year;
                        hour = hour;
                        minutes = minutes;
                        seconds = seconds;
                        // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
                        var date = day + "-" + month + "-" + year;
                        statusText = `<i class="far fa-calendar-alt"></i>`;
                        return statusText + ' ' + data;  
                    }
                },
                {
                    data: 'starttime',
                    render: function(data){
                        statusText = `<i class="fas fa-clock"></i>`;
                        return statusText + ' ' + data;
                    }
                },
                {
                    data: 'endtime',
                    render: function(data){
                        statusText = `<i class="fas fa-clock"></i>`;
                        return statusText + ' ' + data;
                    }
                },
                {
                    data: 'bookedon',
                    render: function(data) {
                        var today = new Date(data);
                        var day = today.getDate() + "";
                        var month = (today.getMonth() + 1) + "";
                        var year = today.getFullYear() + "";
                        var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
                        var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
                        var seconds = today.getSeconds() + "";
                        if (day < 10) {
                            day = '0' + day;
                        }

                        if (month < 10) {
                            month = `0${month}`;
                        }
                        year = year;
                        hour = hour;
                        minutes = minutes;
                        seconds = seconds;
                        // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
                        var date = day + "-" + month + "-" + year;
                        statusText = `<i class="far fa-calendar-alt"></i>`;
                        return statusText + ' ' + data;   
                    }
                },
                {
                    data: 'bookedby',
                    name: 'bookedby'
                },
                {
                    data: 'action',
                    name: 'action',
                },
            ],
            oLanguage: {
				"sLengthMenu": "Tampilkan _MENU_ data",
				"sProcessing": "Loading...",
				"sSearch": "Keyword :",
				"sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ data" 	
			},
        });

        $(document).on('click', '.btnbookroom', function () {
            $('#modal-booked-user').modal({backdrop: 'static', keyboard: false})
            var $modal = $('#modal-booked-user');
            $modal.modal('show');
        }) 
  
        $(document).on('click', '.edit', function() {
            $('#modal-edit-room').modal({backdrop: 'static', keyboard: false})  
            var startdate = $(this).data('startdate');
            var enddate = $(this).data('enddate');
            var starttime = $(this).data('starttime');
            var endtime = $(this).data('endtime');
            var roomid = $(this).data('roomid');
            var bookid = $(this).data('bookid');
            var userid = $(this).data('userid');
            var $modal = $('#modal-edit-room');
            var $form = $modal.find('form[name="edit-room"]');
            $form.find('input[name="startdate1"]').val(startdate);
            $form.find('input[name="enddate1"]').val(enddate);
            $form.find('input[name="bookid"]').val(bookid)
            $form.find('input[name="userid"]').val(userid)
            var room_options = $form.find('select[name="roomAvail1"]').children();
            $.each(room_options, function(key, value) {
                if($(value).val() === roomid) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            var startdate_options = $form.find('select[name="startdate1"]').children();
            $.each(startdate_options, function(key, value) {
                if($(value).val() === startdate) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            var enddate_options = $form.find('select[name="enddate1"]').children();
            $.each(enddate_options, function(key, value) {
                if($(value).val() === enddate) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            var starttime_options = $form.find('select[name="starttime1"]').children();
            $.each(starttime_options, function(key, value) {
                console.log(value);
                if($(value).val() === starttime) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            var endtime_options = $form.find('select[name="endtime1"]').children();
            $.each(endtime_options, function(key, value) {
                if($(value).val() === endtime) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            $modal.modal('show');
        });

        $(document).ready(function () {
            $(document).ajaxStart(function () {
                $('#btnroom').prop('disabled', true);
            }).ajaxStop(function () {
                $('#btnroom').prop('disabled', false);
            });
        });

        $(document).on('click', '.btnroom', function(e) {
            // Initialize();
            // $("#hideroom").load(" #hideroom");
            // $("#hideroombooked").load(" #hideroombooked"); 
            var hide1 = $("#hideroom");
            var hide2 = $("#hideroombooked");
            hide1.show();
            hide2.show();
            var startdate = $('#modal-booked-user input[name="startdate"]').val();
            var enddate = $('#modal-booked-user input[name="enddate"]').val();
            var starttime = $('#modal-booked-user select[name="starttime"]  option:selected').val();
            var endtime = $('#modal-booked-user select[name="endtime"]  option:selected').val();
            $( '#enddate').datepicker( 'setDate', startdate );
            if(startdate.length < 1){
                alert('startdate required');
                return;
            } else if(enddate.length < 1){
                alert('endate required');
                return;
            } else if(starttime.length < 1){
                alert('starttime required');
                return;
            } else if(endtime.length < 1){
                alert('endtime required');
                return;
            } else if(starttime == endtime){
                alert('something wrong invalid time');
                return;
            } else if(endtime < starttime){
                alert('something wrong invalid time');
                return;
            } else if(startdate == date && starttime <= time){
                alert('something wrong time (backtime)');
                return;
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "/get/room",
                    data: {
                        'startdate' : startdate, 
                        'enddate' : enddate, 
                        'starttime' : starttime, 
                        'endtime' : endtime, 
                    },
                    success: function(response) {
                        // console.log(response);
                        var hide1 = $("#hideroom");
                        var hide2 = $("#hideroombooked");
                        hide1.show();
                        hide2.show();
                        // $("#hideroom").css("display","inline");
                        // $("#hideroombooked").css("display","inline");
                        var $select_room_avail = $('#roomAvail');
                        var $select_room_book = $('#roomBook');
                        $.each(response["dataAvail"], function(key, data) {
                            var $options1 = "<option value='"+data["roomid"]+"'>"+data["roomname"]+" | Capacity: "+data["roomcapacity"]+"</option>";
                            $select_room_avail.append($options1);
                        });
                        $.each(response["dataBook"], function(key, data) {
                            var $options2 = "<label class=form-check-label style=color:red value='"+data["roomid"]+"'><p>" +data["roomname"]+   ",</p></label>";
                            $select_room_book.append($options2);
                        });
                        // $('#modal-booked-user form[name="book-user"] select[name="roomBook"]').prop('disabled', true);
                        $('#modal-booked-user form[name="book-user"]').html($select_room_avail);  
                        $('#modal-booked-user form[name="book-user"]').html($select_room_book);
                        $('#book-btn').prop('disabled', false);
                        Initialize();
                    },
                    error: function (error) {
                        console.error(error);
                    },
                })
            }
        });

        $(document).on('click', '.editroom', function(e) {
            // Initialize();
            // $("#hideroomedit").load(" #hideroomedit");
            // $("#hideroombookededit").load(" #hideroombookededit");
            var hide1 = $("#hideroomedit");
            var hide2 = $("#hideroombookededit");
            hide1.show();
            hide2.show();
            var bookid = $('#modal-edit-room input[name="bookid"]').val();
            var roomid = $('#modal-edit-room input[name="roomAvail1"]').val();
            var startdate = $('#modal-edit-room input[name="startdate1"]').val();
            var enddate = $('#modal-edit-room input[name="enddate1"]').val();
            var starttime = $('#modal-edit-room select[name="starttime1"]  option:selected').val();
            var endtime = $('#modal-edit-room select[name="endtime1"]  option:selected').val();
         
            if(startdate.length < 1){
                alert('startdate required');
                return;
            } else if(enddate.length < 1){
                alert('endate required');
                return;
            } else if(starttime.length < 1){
                alert('starttime required');
                return;
            } else if(endtime.length < 1){
                alert('endtime required');
                return;
            } else if(starttime == endtime){
                alert('something wrong invalid time');
                return;
            } else if(enddate < startdate){
                alert('something wrong enddate (backdate)');
                return;
            } else if(endtime < starttime){
                alert('something wrong endtime (backtime)');
                return;
            } else if(startdate == date && starttime <= time){
                alert('something wrong time (backtime)');
                return;
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "/get/room",
                    data: {
                        'startdate' : startdate, 
                        'enddate' : enddate, 
                        'starttime' : starttime, 
                        'endtime' : endtime, 
                        'bookid' : bookid,
                        'roomid' : roomid
                    },
                    success: function(response) {
                        // console.log(response);
                        // $("#hideroomedit").css("display","inline");
                        // $("#hideroombookededit").css("display","inline");
                        var $select_room_avail = $('#roomAvail1');
                        var $select_room_book = $('#roomBook1');
                        $.each(response["dataAvail"], function(key, data) {
                            var $options1 = "<option value='"+data["roomid"]+"'>"+data["roomname"]+" | Capacity: "+data["roomcapacity"]+"</option>";
                            $select_room_avail.append($options1);
                        });
                        $.each(response["dataBook"], function(key, data) {
                            var $options2 = "<label class=form-check-label style=color:red value='"+data["roomid"]+"'><p>" +data["roomname"]+   ",</p></label>";
                            $select_room_book.append($options2);
                        });
                        // $('#modal-edit-room form[name="edit-room"] select[name="roomBook1"]').prop('disabled', true);
                        $('#modal-edit-room form[name="edit-room"] select[name="roomAvail1"]').parent().html($select_room_avail);  
                        $('#modal-edit-room form[name="edit-room"] select[name="roomBook1"]').parent().html($select_room_book);
                        Initialize();
                    },
                    error: function (error) {
                        console.error(error);
                    },
                })
            }
        });

        $(document).on('click', '.btnclose', function(e) {
            document.getElementById("booked-user").reset();
            $('#startdate').datepicker( 'setDate', date );
            var startdate = $('#modal-booked-user input[name="startdate"]').val();
            $('#enddate').datepicker( 'setDate', startdate );
            // $("#starttime").val('').trigger('change');
            // $("#endtime").val('').trigger('change');
            $("#hideroom").load(" #hideroom");
            $("#hideroombooked").load(" #hideroombooked");
            $("#hideroomedit").load(" #hideroomedit");
            $("#hideroombookededit").load(" #hideroombookededit");
            var hide1 = $("#hideroom");
            var hide2 = $("#hideroombooked");
            var hide3 = $("#hideroomedit");
            var hide4 = $("#hideroombookededit");
            var hide5 = $("#hidedate");
            hide1.hide();
            hide2.hide();
            hide3.hide();
            hide4.hide();
            hide5.hide();
        });

        $(document).on('click', '.btndate', function(e) {
            // var startdate = $('#modal-booked-user input[name="startdate"]').val();
            // $( '#enddate').datepicker( 'setDate', startdate );
            $("#starttime").val('').trigger('change');
            $("#endtime").val('').trigger('change');
            // $("#hideroom").load(" #hideroom");
            // $("#hideroombooked").load(" #hideroombooked"); 
            var hide1 = $("#hideroom");
            var hide2 = $("#hideroombooked");
            hide1.hide();
            hide2.hide();
            
        });

        $(document).on('click', '.btndate1', function(e) {
            var startdate1 = $('#modal-edit-room input[name="startdate1"]').val();
            $( '#enddat1').datepicker( 'setDate', startdate1 );
            $("#hideroomedit").load(" #hideroomedit");
            $("#hideroombookededit").load(" #hideroombookededit");
            var hide3 = $("#hideroomedit");
            var hide4 = $("#hideroombookededit");
            hide3.hide();
            hide4.hide();
            
        });

        // stats count booked & canceled //
        $.ajax({
            url: '{{ route("get-count") }}',
            type: 'GET',
            success: function(response) {
                $('span[name="available"]').text(response['ava'])
                $('span[name="booked"]').text(response['book'])
                $('span[name="canceled"]').text(response['cncl'])
                $('span[name="newroom"]').text(response['nwrm'])    
            }, error: function(err) {
                console.log(err)
                alert('Opps, something wrong with dashboard chart');
            }
        })
        // end //
    });
</script>
<script>
    function myFunction(e) {
        Initialize();
        $("#hideroom").load(" #hideroom");
        $("#hideroombooked").load(" #hideroombooked");
        var hide1 = $("#hideroom");
        var hide2 = $("#hideroombooked");
        hide1.hide();
        hide2.hide();
    }

    function myFunction1(e) {
        Initialize();
        $("#hideroomedit").load(" #hideroomedit");
        $("#hideroombookededit").load(" #hideroombookededit");
        var hide3 = $("#hideroomedit");
        var hide4 = $("#hideroombookededit");
        hide3.hide();
        hide4.hide();
    }
</script>
<script>
    $(function() {
        var form = $("#booked-user");
        var checked = $("#range");
        var hide1 = $("#hideroom");
        var hide2 = $("#hideroombooked");
        var hide3 = $("#hidedate");
        hide1.hide();
        hide2.hide();
        hide3.hide();
   

        checked.change(function() {
            if (checked.is(':checked')) {
                var startdate = $('#modal-booked-user input[name="startdate"]').val();
                $('#enddate').datepicker( 'setDate', startdate );
                hide3.show();
            } else {
                var startdate = $('#modal-booked-user input[name="startdate"]').val();
                hide3.hide();
                $('#enddate').datepicker( 'setDate', startdate );
            }
        });
    });
</script>
<script>
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
