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
                    <h1>Absensi & Kasus</h1>
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>NIP :</label>
                                            <div class="input-group value">
                                                <select class="select2" data-placeholder="Include NIP" multiple="multiple" id="nip" name="data_nip" style="width: 100%;">
                                                    <option value=[] >all</option>
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
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button id="absen" name="absen" class="absen btn-submit btn btn-success" >Search</button>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <div class="form-group">
                                            <button class="btn btn-success" ><i class="fas fa-file-excel"></i> Export</button>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <form name="formData" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <table id="dataabsenpayroll" class="table table-bordered table-hover display nowrap" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>NIP</th>
                                                        <th>Name</th>
                                                        <th>Code Division</th>
                                                        <th>Code Department</th>
                                                        <th>Code Group</th>
                                                        <th>Date In</th>
                                                        <th>Clock In</th>
                                                        <th>Date Out</th>
                                                        <th>Length of Working</th>
                                                        <th>Overtime</th>
                                                        <th>Shift</th>
                                                        <th>Long Time Off</th>
                                                        <th>No Case</th>
                                                        <th>CardX</th>
                                                        <th>Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="form-row">
                                <button id="checked" class="btn btn-block btn-primary btn-default" style="background-color: #007bff; !important;color: #fff;">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <!-- <div class="card-footer">
                            Indo Sukses Logistic
                        </div> -->
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>
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
        if ($(check).is(':checked')) {
            $(checked).prop("checked", true);
            arr_values.push($(check).val());
        } else {
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
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                dom: 'Blfrtip',
                buttons: [
                    'excel'
                ],
                ajax: {
                    url: '{{ route("get-absensipayroll") }}',
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
                        data: 'Nip',
                        name: 'Nip'
                    },
                    {
                        data: 'Nama',
                        name: 'Nama'
                    },
                    {
                        data: 'Kode Divisi',
                        name: 'Kode Divisi'
                    },
                    {
                        data: 'Kode Bagian',
                        name: 'Kode Bagian'
                    },
                    {
                        data: 'Kode Group',
                        name: 'Kode Group'
                    },
                    {
                        data: 'Tgl In',
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
                        data: 'Jam In',
                        name: 'Jam In'
                    },
                    {
                        data: 'Tgl Out',
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
                        data: 'Lama Kerja',
                        name: 'Lama Kerja'
                    },
                    {
                        data: 'Jam Lembur',
                        name: 'Jam Lembur'
                    },
                    {
                        data: 'Shift',
                        name: 'Shift'
                    },
                    {
                        data: 'Lama Off',
                        name: 'Lama Off'
                    },
                    {
                        data: 'No Kasus',
                        name: 'No Kasus'
                    },
                    {
                        data: 'CardX',
                        name: 'CardX'
                    },
                    {
                        data: 'tipekaryawan',
                        name: 'Tipe'
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
    });
</script>
@endsection
