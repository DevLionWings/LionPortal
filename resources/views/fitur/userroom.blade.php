@extends('parent.master')
@section('extend-css')
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
                    <h5>Booking Room Meeting</h5>
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
                            <div class="float-sm-left">
                                <button type="button" class="btnbookroom btn btn-success" id="btnbookroom"><i class="fa fa-plus" aria-hidden="true"></i> Book Room</button>
                            </div>
                            <!-- <div class="row align-items-end">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Pilih Tanggal Meeting:</label>
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
                            </div> -->
                        </div>
                        <div class="card-body">
                            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-animation="true" data-delay="5000" data-autohide="false" style="max-width: 5000px;">
                                <div class="toast-header">
                                    <span class="fa fa-info-circle" style="width: 15px;height: 15px"></span>
                                    <strong class="mr-auto">Information</strong>
                                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="toast-body">
                                    Bukti Booking akan di kirimkan ke email pemesan.
                                </div>
                            </div>
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
                                        <!-- <th>Room ID</th> -->
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
                                        <th>Book Date</th>
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
                    <input id="userid" name="userid" class="form-control input--style-6" type="hidden" value="{{ session('userid') }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-check-label" for="subject" disabled>Subject :</label>
                            <input type="text" name="subject" class="form-control" id="subject" required>
                        </div>
                        <input type="checkbox" class="largerCheckbox" name="range" id="range" value="1">
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
                                                <option value="" selected>--:--:--</option>
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
                                            <select  type="text" id="endtime" name="endtime" class="form-control" style="width: 100%;" onchange="myFunction(event)">
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
                            <textarea type="text" name="detail" class="form-control" id="detail" rows="4" cols="50" required></textarea>
                        </div> 
                        <div class="form-group">
                            <button type="button" id="btnroom" name="btnroom" class="btnroom btn btn-link"><i class="fas fa-sync fa" aria-hidden="true"> Chose Room Meeting</i></button>
                            <div id="loadings">
                                Loading...
                            </div>
                        </div> 
                        <div class="form-group" id="hideroom">   
                            <label class="form-check-label" for="roomAvail">Room Meeting Available :</label>
                            <select type="text" id="roomAvail" name="roomAvail" class="select2" style="width: 100%;">
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
    <div id="modal-view-booked"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Room Meeting</h4>
                    <button type="button" class="close btnclose" id="btnclose" nama="btnclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" method="post" name='view-booked' id='view-booked'>
                    @csrf
                    <input id="userid" name="userid" class="form-control input--style-6" type="hidden" value="{{ session('userid') }}">
                    <div class="modal-body">
                        <div class="form-group">   
                            <label class="form-check-label" for="roomname">Room Name :</label>
                            <input type="text" name="roomname" id="roomname" class="form-control" readonly> 
                        </div> 
                        <div class="form-group">
                            <label class="form-check-label" for="subject" disabled>Subject :</label>
                            <input type="text" name="subject" class="form-control" id="subject" readonly>
                        </div>  
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label" for="startdate">Start Date:</label>
                                        <input type="text" name="startdate" id="startdate" class="btndate form-control" readonly> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group" id="hidedate">
                                        <label class="form-check-label" for="enddate">End Date:</label>
                                        <input type="text" name="enddate" id="enddate" class="btndate form-control" readonly> 
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label">Start Time :</label>
                                        <input type="text" name="starttime" id="starttime" class="form-control" readonly> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-label">End Time :</label>
                                        <input type="text" name="endtime" id="endtime" class="form-control" readonly> 
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label class="form-check-label" for="description">Description :</label>
                            <input type="text" name="description" class="form-control" id="description" readonly>
                        </div> 
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default btnclose" id="btnclose" data-dismiss="modal">Close</button>
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
<script src="{{ asset('plugins/jquery/jquery-ui.js') }}"></script>
<script>
    $('.nav-link.active').removeClass('active');
    $('#m-userroom').addClass('active');
    $('#m-userroom').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script>
function Initialize()
    {  
        $('.select2').select2({
            allowClear: true,
            width: '100%'
        });
    } 
</script>
<script>
    $(function () {    
        var hide1 = $("#hideroom");
        var hide2 = $("#hideroombooked");
        var hide3 = $("#loadings");
        hide1.hide();
        hide2.hide();
        hide3.hide();

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
      $('.select2').select2({
            allowClear: true,
            width: '100%'
        });

        // format datenow
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
        //end

        //datepicker//
        var optSimple = {
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            onSelect: function(a) {
                $('.select2').select2()
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
            }
        };
        $( '#startdate' ).datepicker( optSimple );
        $( '#enddate' ).datepicker( optSimple );
        $( '#startdate').datepicker( 'setDate', date );
        //end//

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
        
        $( '#enddate').datepicker( 'setDate', startdate );

        $(document).on('click', '.cancel', function () {
            var bookid = $(this).data('bookid');
            var roomid = $(this).data('roomid');
            var $modal = $('#modal-cancel-room');
            var $form = $modal.find('form[name="cancel"]');
            $form.find('input[name="bookid"]').val(bookid);
            $form.find('input[name="roomAvail"]').val(roomid);
            $modal.modal('show');
        })

        $(document).on('click', '.btnbookroom', function () {
            $('#modal-booked-user').modal({backdrop: 'static', keyboard: false})
            var $modal = $('#modal-booked-user');
            $modal.modal('show');
        }) 

        $(document).on('click', '.view', function() {
            $('#modal-view-booked').modal({backdrop: 'static', keyboard: false})
            var bookid = $(this).attr('data-bookid');
            var roomname = $(this).attr('data-roomname');
            var subject = $(this).attr('data-subject');
            var startdate = $(this).attr('data-startdate');
            var enddate = $(this).attr('data-enddate');
            var starttime = $(this).attr('data-starttime');
            var endtime = $(this).attr('data-endtime');
            var description = $(this).attr('data-description');
            var $modal = $('#modal-view-booked');
            var $form = $modal.find('form[name="view-booked"]');
            $form.find('input[name="bookid"]').val(bookid);
            $form.find('input[name="roomname"]').val(roomname);
            $form.find('input[name="subject"]').val(subject);
            $form.find('input[name="startdate"]').val(startdate);
            $form.find('input[name="enddate"]').val(enddate);
            $form.find('input[name="starttime"]').val(starttime);
            $form.find('input[name="endtime"]').val(endtime);
            $form.find('input[name="description"]').val(description);
            $modal.modal('show');
        });
        
        var table = $('#room_list').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            responsive: false,
            searching: true,
            lengthChange: false,
            ajax: "{{ route('room-list-user') }}",
            order: [[ 3, "desc" ]],
            dom: 'Blfrtip',
                buttons: [
                    'excel'
            ],
            columns: [
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
                    render: function(data){
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

        $(document).ready(function () {
            $(document).ajaxStart(function () {
                $('#btnroom').prop('disabled', true);
            }).ajaxStop(function () {
                $('#btnroom').prop('disabled', false);
            });
        });

        $(document).on('click', '.btnroom', function(e) {
            var startdate = $('#modal-booked-user input[name="startdate"]').val();
            var enddate = $('#modal-booked-user input[name="enddate"]').val();
            var starttime = $('#modal-booked-user select[name="starttime"]  option:selected').val();
            var endtime = $('#modal-booked-user select[name="endtime"]  option:selected').val();
            var checkedValue = $('#modal-booked-user input[type=checkbox]:checked').val();
            if (checkedValue != '1'){
                $('#enddate').datepicker( 'setDate', startdate );
            } else {
                $('#enddate').datepicker( 'setDate', enddate );

                if(enddate < startdate){
                    alert('something wrong date (backdate)');
                    return;
                }
            }
            if (enddate < startdate){
                var enddate = $('#modal-booked-user input[name="startdate"]').val();
            } else {
                var enddate = $('#modal-booked-user input[name="enddate"]').val();
            }
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
            } else if(enddate < startdate){
                alert('something wrong time (backdate)');
                return;
            } else {
                $("#btnroom").attr("disabled", true);
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

        $(document).on('click', '.btnclose', function(e) {
            document.getElementById("booked-user").reset();
            $('#startdate').datepicker( 'setDate', date );
            var startdate = $('#modal-booked-user input[name="startdate"]').val();
            $('#enddate').datepicker( 'setDate', startdate );
            $("#starttime").val('').trigger('change');
            $("#endtime").val('').trigger('change');
            $("#hideroom").load(" #hideroom");
            $("#hideroombooked").load(" #hideroombooked");
            $('#book-btn').prop('disabled', true);
            var hide1 = $("#hideroom");
            var hide2 = $("#hideroombooked");
            var hide3 = $("#hidedate");
            hide1.hide();
            hide2.hide();
            hide3.hide();
        });

        $(document).on('click', '.btndate', function(e) {
            $('.select2').select2()
            $("#starttime").val('').trigger('change');
            $("#endtime").val('').trigger('change');
            $("#hideroom").load(" #hideroom");
            $("#hideroombooked").load(" #hideroombooked");
            var hide1 = $("#hideroom");
            var hide2 = $("#hideroombooked");
            hide1.hide();
            hide2.hide();
        });
    });
</script>
<script type="text/javascript">
    function myFunction(e) {
        Initialize();
        $("#hideroom").load(" #hideroom");
        $("#hideroombooked").load(" #hideroombooked");
        var hide1 = $("#hideroom");
        var hide2 = $("#hideroombooked");
        hide1.hide();
        hide2.hide();
    }
</script>
<script>
    $(function() {
        // $('.select2').select2()
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
                hide1.hide();
                hide2.hide();
                hide3.hide();
                Initialize();
                $("#hideroom").load(" #hideroom");
                $("#hideroombooked").load(" #hideroombooked");
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
