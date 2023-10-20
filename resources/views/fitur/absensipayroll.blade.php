@extends('parent.master')
@section('extend-css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.checkboxes.css') }}">
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
                    <h5>Shift Bermasalah</h5>
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
                            <div class="card-header">
                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Divisi :</label>
                                            <div class="input-group value">
                                                <select id="divisi" name="divisi" class="form-control input--style-6" data-placeholder="Pilih Divisi">
                                                <option value="">all</option>
                                                    @foreach($div as $divcode)
                                                    <option value="{{ $divcode['ID'] }}">{{ $divcode['NAME'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Bagian :</label>
                                            <div class="input-group value">
                                                <select id="bagian" name="bagian" class="form-control input--style-6" data-placeholder="Pilih Bagian">
                                                <option value="">all</option>
                                                    @foreach($bag as $bagcode)
                                                    <option value="{{ $bagcode['ID'] }}">{{ $bagcode['NAME'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Group :</label>
                                            <div class="input-group value">
                                                <select id="group" name="group" class="form-control input--style-6" data-placeholder="Pilih Group">
                                                <option value="">all</option>
                                                    @foreach($grp as $grpcode)
                                                    <option value="{{ $grpcode['ID'] }}">{{ $grpcode['NAME'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Admin :</label>
                                            <div class="input-group value">
                                                <select id="admin" name="admin" class="form-control input--style-6" data-placeholder="Pilih Admin">
                                                <option value="">all</option>
                                                    @foreach($adm as $admcode)
                                                    <option value="{{ $admcode['ID'] }}">{{ $admcode['NAME'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Periode :</label>
                                            <div class="input-group value">
                                                <select id="periode" name="periode" class="form-control input--style-6" data-placeholder="Pilih Periode">
                                                <option value="">all</option>
                                                    @foreach($period as $periodcode)
                                                    <option value="{{ $periodcode['ID'] }}">{{ $periodcode['NAME'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Kontrak :</label>
                                            <div class="input-group value">
                                                <select id="kontrak" name="kontrak" class="form-control input--style-6" data-placeholder="Pilih Kontrak">
                                                <option value=""> all</option>
                                                  
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>NIP :</label>
                                            <div class="input-group value">
                                                <select class="select2" data-placeholder="Include NIP" multiple="multiple" id="nip" name="data_nip" style="width: 100%;">
                                                    @foreach($nip as $nipcode)
                                                    <option value="{{ $nipcode['ID'] }}">{{ $nipcode['ID'] }} | {{ $nipcode['NAME'] }}</option>
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
                                    <div class="col-md-0">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <button id="absen" name="absen" class="absen btn-submit btn btn-success" ><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <button id="checked" name="checked" class="checked btn-submit btn-primary" style="display: none">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <div class="form-group">
                                            <button class="btn btn-success" ><i class="fas fa-file-excel"></i> Export</button>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <form id="formData" name="formData" action="">
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
                                    <div class="row">
                                        <div class="col-12">
                                            <table id="dataabsenpayroll" class="table table-bordered table-hover display nowrap" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>NIP</th>
                                                        <th>Name</th>
                                                        <th>Code Division</th>
                                                        <th>Code Department</th>
                                                        <th>Code Group</th>
                                                        <th>Date In</th>
                                                        <th>Clock In</th>
                                                        <th>Date Out</th>
                                                        <th>Clock Out</th>
                                                        <th>Length of Working</th>
                                                        <th>Overtime</th>
                                                        <th>Shift</th>
                                                        <th>Long Time Off</th>
                                                        <th>No Case</th>
                                                        <th>CardX</th>
                                                        <th>Shift In</th>
                                                        <th>Shift Out</th>
                                                        <th>Time Validation</th>
                                                        <th></th>
                                                        <th>New Shift</th>
                                                        <th>Edit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- <div class="form-row">
                                <button id="checked" class="btn btn-block btn-primary btn-default" style="background-color: #007bff; !important;color: #fff;">Save</button>
                            </div> -->
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>
    <div id="modal-update-shift"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Shift</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('update-shift')}}" method="post" name='shift'>
                    @csrf
                    <div class="modal-body">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                        <input type="hidden" id="nip" name="nip"/>
                        <input type="hidden" id="jamin" name="jamin"/>
                        <input type="hidden" id="tglin" name="tglin"/>
                        <input type="hidden" id="tglout" name="tglout"/>
                        <input type="hidden" id="jamout" name="jamout"/>
                        <input type="hidden" id="timevalid" name="timevalid"/>
                        <input type="hidden" id="jamlembur" name="jamlembur"/>
                        <input type="hidden" id="off" name="off"/>
                        <div class="form-group">
                            <div class="name">Update Shift :</div>
                            <div class="input-group value">
                                <select id="selectshift" name="selectshift" class="form-control input--style-6" required>
                                    <option value=""> Masukkan Pilihan :</option>
                                        <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="update-btn" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ./wrapper -->
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

<script>
    $('.nav-link.active').removeClass('active');
    $('#m-absensipayroll').addClass('active');
    $('#m-absensipayroll').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script>
    var arr_values = [];

    function getValue(check) {
        var values = [];
        if ($(check).is(':checked') == true) {
            $("#checked").show();
            $(checked).prop("checked", true);
            arr_values.push($(check).val());
        } else {
            $("#checked").hide();
            $(checked).prop("checked", false);
            arr_values.forEach(function (value, i) {
                if (value === $(check).val()) {
                    arr_values.splice(i, 1);
                }
            });
        }

        console.log(arr_values);
    }

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
        // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
        // var date = day + "/" + month + "/" + year;

        var date_range = day + "/" + month + "/" + year;
        var $btn_submit = $("button#btn-sumbit-absen");

        //Initialize Select2 Elements
        $('.select2').select2()
        $('.datepicker').daterangepicker();
        // $(function() {
        //     $('.datepicker').daterangepicker({
        //         timePicker: true,
        //         startDate: moment().startOf('hour'),
        //         endDate: moment().startOf('hour').add(32, 'hour'),
        //         locale: {
        //         format: 'YYYY/MM/DD'
        //         }
        //     });
        // });

        $(document).on('click', '.newshift', function () {
            $('#modal-update-shift').modal({backdrop: 'static', keyboard: false})  
            getShiftJson($(this).attr('data-newshift'));
            var nip = $(this).attr('data-nip');
            var newshift = $(this).attr('data-newshift');
            var jamin = $(this).attr('data-jamin');
            var tglin = $(this).attr('data-tglin');
            var tglout = $(this).attr('data-tglout');
            var jamout = $(this).attr('data-jamout');
            var timevalid = $(this).attr('data-timevalid');
            var jamlembur = $(this).attr('data-jamlembur');
            var off = $(this).attr('data-off');
            var $modal = $('#modal-update-shift');
            var $form = $modal.find('form[name="shift"]');
            $form.find('input[name="nip"]').val(nip);
            $form.find('input[name="selectshift"]').val(selectshift);
            $form.find('input[name="newshift"]').val(newshift);
            $form.find('input[name="jamin"]').val(jamin);
            $form.find('input[name="tglin"]').val(tglin);
            $form.find('input[name="tglout"]').val(tglout);
            $form.find('input[name="jamout"]').val(jamout);
            $form.find('input[name="timevalid"]').val(timevalid);
            $form.find('input[name="jamlembur"]').val(jamlembur);
            $form.find('input[name="off"]').val(off);
            $modal.modal('show');
        }) 

        $(document).on('click', '.absen', function submit() {
            data_divisi = $('select[name="divisi"]').val();
            data_bagian = $('select[name="bagian"]').val();
            data_group = $('select[name="group"]').val();
            data_admin = $('select[name="admin"]').val();
            data_periode = $('select[name="periode"]').val();
            data_kontrak = $('select[name="kontrak"]').val();
            data_nip = $('select[name="data_nip"]').val();
            daterange = $('input[name="data_date_range"]').val();

            $('#dataabsenpayroll').DataTable().clear().destroy();
            var $dataabsen = $('#dataabsenpayroll').DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive: false,
                searching: true,
                pageLength: 50,
                dom: 'Blfrtip',
                buttons: [
                    'excel'
                ],
                ajax: {
                    url: '{{ route("filter-absensipayroll") }}',
                    "data": function (d) {
                        d.daterange = $('input[name="data_date_range"]').val();
                        d.data_nip = $('select[name="data_nip"]').val();
                        d.data_divisi = $('select[name="divisi"]').val();
                        d.data_bagian = $('select[name="bagian"]').val();
                        d.data_group = $('select[name="group"]').val();
                        d.data_admin = $('select[name="admin"]').val();
                        d.data_periode = $('select[name="periode"]').val();
                        d.data_kontrak = $('select[name="kontrak"]').val();
                    },
                    "dataSrc": function (settings) {
                        $btn_submit.text("Submit");
                        $btn_submit.prop('disabled', false);
                        return settings.data;
                    },
                },
                
                columns: [
                    {
                        data: 'Nip',
                        name: 'Nip'
                    },
                    {
                        data: 'Nama',
                        name: 'Nama'
                    },
                    {
                        data: 'KodeDivisi',
                        name: 'KodeDivisi'
                    },
                    {
                        data: 'KodeBagian',
                        name: 'KodeBagian'
                    },
                    {
                        data: 'KodeGroup',
                        name: 'KodeGroup'
                    },
                    {
                        data: 'TglIn',
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
                            var date = day + "/" + month + "/" + year + " " + hour + ":" + minutes;
                            return date;   
                        }
                    },
                    {
                        data: 'JamIn',
                        name: 'JamIn'
                    },
                    {
                        data: 'TglOut',
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
                            var date = day + "/" + month + "/" + year + " " + hour + ":" + minutes;
                            return date;   
                        }
                    },
                    {
                        data: 'JamOut',
                        name: 'JamOut'
                    },
                    {
                        data: 'LamaKerja',
                        name: 'LamaKerja'
                    },
                    {
                        data: 'JamLembur',
                        name: 'JamLembur'
                    },
                    {
                        data: 'Shift',
                        name: 'Shift'
                    },
                    {
                        data: 'LamaOff',
                        name: 'LamaOff'
                    },
                    {
                        data: 'NoKasus',
                        name: 'NoKasus'
                    },
                    {
                        data: 'CardX',
                        name: 'CardX'
                    },
                    {
                        data: 'ShiftIn',
                        name: 'ShiftIn'
                    },
                    {
                        data: 'ShiftOut',
                        name: 'ShiftOut'
                    },
                    {
                        data: 'TimeValidation',
                        name: 'TimeValidation'
                    },
                    {
                        data: 'Nip',
                        targets: 0,
                        className: 'select-checkbox',
                        render: function (data) {
                            if (data) {
                                return '<input type="checkbox" value="' + data +
                                    '" id="chckBox" name="checked" onclick="getValue(this);">';
                            }
                        }
                    },
                    {
                        data: 'NewShift',
                        name: 'NewShift'
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },

                ],
                oLanguage: {
                    "sLengthMenu": "Tampilkan _MENU_ data",
                    "sProcessing": "Loading...",
                    "sSearch": "Search:",
                    "sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ data"
                },
                drawCallback: function() {
                    $btn_submit.text("Sumbit");
                    $btn_submit.prop('disabled', false);
                }
            });
        }); 
        
        /* After Checked */
        $('#checked').on('click', function () {
            var nip = arr_values;
            var daterange = $('input[name="data_date_range"]').val();
            var data_divisi = $('select[name="divisi"]').val();
            var data_bagian = $('select[name="bagian"]').val();
            var data_group = $('select[name="group"]').val();
            var data_admin = $('select[name="admin"]').val();
            var data_periode = $('select[name="periode"]').val();
            var data_kontrak = $('select[name="kontrak"]').val();
         
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/update/shiftbulk",
                type: 'POST',
                data: {
                    'nip' : nip,
                    'daterange' : daterange,
                    'data_divisi' : data_divisi,
                    'data_bagian' : data_bagian,
                    'data_group' : data_group,
                    'data_admin' : data_admin,
                    'data_periode' : data_periode,
                    'data_kontrak' : data_kontrak
                },
                success: function(response){ 
                    // console.log(response);
                    $('#dataabsenpayroll').DataTable().ajax.reload();
                        
                }
            });
        })


        var $dataabsen = $('#dataabsenpayroll').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            responsive: false,
            searching: true,
            lengthChange: false,
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ],
            ajax: {
                url: '{{ route("get-absensipayroll") }}',
                "data": function (d) {
                    d.daterange = $('input[name="data_date_range"]').val();
                    d.data_nip = $('select[name="data_nip"]').val();
                },
                "dataSrc": function (settings) {
                    $btn_submit.text("Submit");
                    $btn_submit.prop('disabled', false);
                    return settings.data;
                },
            },
            columns: [
                {
                    data: 'Nip',
                    name: 'Nip'
                },
                {
                    data: 'Nama',
                    name: 'Nama'
                },
                {
                    data: 'KodeDivisi',
                    name: 'KodeDivisi'
                },
                {
                    data: 'KodeBagian',
                    name: 'KodeBagian'
                },
                {
                    data: 'KodeGroup',
                    name: 'KodeGroup'
                },
                {
                    data: 'TglIn',
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
                        var date = day + "/" + month + "/" + year + " " + hour + ":" + minutes;
                        return date;   
                    }
                },
                {
                    data: 'JamIn',
                    name: 'JamIn'
                },
                {
                    data: 'TglOut',
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
                        var date = day + "/" + month + "/" + year + " " + hour + ":" + minutes;
                        return date;   
                    }
                },
                {
                    data: 'JamOut',
                    name: 'JamOut'
                },
                {
                    data: 'LamaKerja',
                    name: 'LamaKerja'
                },
                {
                    data: 'JamLembur',
                    name: 'JamLembur'
                },
                {
                    data: 'Shift',
                    name: 'Shift'
                },
                {
                    data: 'LamaOff',
                    name: 'LamaOff'
                },
                {
                    data: 'NoKasus',
                    name: 'NoKasus'
                },
                {
                    data: 'CardX',
                    name: 'CardX'
                },
                {
                    data: 'ShiftIn',
                    name: 'ShiftIn'
                },
                {
                    data: 'ShiftOut',
                    name: 'ShiftOut'
                },
                {
                    data: 'TimeValidation',
                    name: 'TimeValidation'
                },
                {
                    data: 'Nip',
                    targets: 0,
                    className: 'select-checkbox',
                    render: function (data) {
                        if (data) {
                            return '<input type="checkbox" value="' + data +
                                '" id="chckBox" name="checked" onclick="getValue(this);">';
                        }
                    }
                },
                {
                    data: 'NewShift',
                    name: 'NewShift'
                },
                {
                    data: 'action',
                    name: 'action'
                },

            ],
            oLanguage: {
                "sLengthMenu": "Tampilkan _MENU_ data",
                "sProcessing": "Loading...",
                "sSearch": "Search:",
                "sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ data"
            },
            drawCallback: function() {
                $btn_submit.text("Sumbit");
                $btn_submit.prop('disabled', false);
            }
        });
    });

    function getShiftJson(shiftSelected) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "/shift",
            data: {
                'shift' : shiftSelected, 
            },
            success: function(response) {
                // console.log(response['shft'])
                var $select_shift = $('<select type="text" id="selectshift" name="selectshift" class="form-control input--style-6" required></select>');
                $.each(response["shft"], function(key, data) {
                    var isSelected = (data["CODE"]===shiftSelected)?"selected":"";
                    var $options = "<option value='"+data["CODE"]+"' "+isSelected+">"+data["CODE"]+"</option>";
                    $select_shift.append($options);
                });
                $('#modal-update-shift form[name="shift"] select[name="selectshift"]').parent().html($select_shift);
            }
        })
    }
    
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
