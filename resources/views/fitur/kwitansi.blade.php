@extends('parent.master')
@section('extend-css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.checkboxes.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('plugins/jquery/jquery-ui.css') }}"> -->
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
                    <h5>Print Kwitansi</h5>
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
                                <div class="alert alert-success alert-dismissible alert-message">
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
                                    @foreach ($errors->all() as $error)
                                        {{$error}}<br/>
                                    @endforeach
                                </div>
                            @endif
                            <form action="{{ route('print-kwitansi') }}" id="form1" name="form1" method="get" >
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="name">Type Kwitansi :</div>
                                                    <div class="input-group value">
                                                        <select id="opsi" name="opsi" class="form-control input--style-6">
                                                            <option value=""> Masukkan Pilihan :</option>
                                                            <option value="UangPisah">Uang Pisah</option>
                                                            <option value="DUKA">Uang Duka</option> 
                                                            <option value="PERNIKAHAN">Uang Pernikahan</option> 
                                                            <option value="KELAHIRAN">Uang Kelahiran</option> 
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3" id="hide1">
                                                    <label class="form-check-label" for="idkaryawan">ID Karyawan:</label>
                                                    <input type="text" name="idkaryawan" class="form-control" id="idkaryawan">
                                                </div>
                                                <div class="mb-3" id="hide2">
                                                    <label class="form-check-label" for="tglpisah">Tanggal Pisah:</label>
                                                    <input type="date" name="tglpisah" id="tglpisah" class="form-control" > 
                                                </div>
                                                <div class="form-group" id="hide3">
                                                    <div class="name">Category Duka :</div>
                                                    <div class="input-group value">
                                                        <select id="category" name="category" class="form-control input--style-6">
                                                            <option value=""> Masukkan Pilihan :</option>
                                                        @foreach($cate as $catecode)
                                                            <option value="{{ $catecode['ID'] }}">{{ $catecode['NAME'] }}</option>
                                                        @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3" id="hide4">
                                                    <label class="form-check-label" for="detail">Keterangan:</label>
                                                    <textarea type="text" name="keterangan" class="form-control" id="keterangan"></textarea>
                                                </div>
                                                <div class="mb-6" id="hide12">
                                                    <button type="button" id="check" class="check btn btn-primary btn-md float-right">Check</button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3" id="hide5">
                                                    <label class="form-check-label" for="nama">Nama Karyawan:</label>
                                                    <input type="text" name="nama" id="nama" class="form-control"  $value="" readonly>
                                                </div>
                                                <div class="mb-3" id="hide15">
                                                    <label class="form-check-label" for="bagian">Bagian Karyawan:</label>
                                                    <input type="text" name="bagian" id="bagian" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3" id="hide6">
                                                    <label class="form-check-label" >Tanggal Masuk:</label>
                                                    <input type="text" name="tglmasuk" id="tglmasuk" class="form-control" readonly> 
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3" id="hide7">
                                                            <label class="form-check-label" >Gaji:</label>
                                                            <input type="text" name="gaji" id="gaji" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3" id="hide14">
                                                            <label class="form-check-label" >Jabatan:</label>
                                                            <input type="text" name="jabatan" id="jabatan" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3" id="hide8">
                                                    <label class="form-check-label" for="total">Total:</label>
                                                    <input type="text" name="total" id="total" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3" id="hide9">
                                                    <label class="form-check-label" >Lama Masa Kerja:</label>
                                                    <input type="text" name="masakerja" id="masakerja" class="form-control" readonly>
                                                </div>
                                                <div class="mb-6" id="hide13">
                                                    <button type="button" id="save" class="save btn btn-info btn-md float-right">Save</button>
                                                </div>
                                                <!-- <div class="mb-6" id="hide10">
                                                    <button type="submit" class="btn btn-success btn-md float-right"><i class="fa fa-print" aria-hidden="true"></i></button>
                                                </div> -->
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
                                    <div class="mb-6">
                                        <button type="submit" class="btn btn-success btn-md float-right"><i class="fa fa-print" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="mb-6">
                                        <button type="button" id="delete" class="delete btn btn-danger btn-md float-right"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </div>
                                    <table id="datakwitansi" class="table table-bordered table-hover display nowrap" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No Kwitansi</th>
                                                <th>type</th>
                                                <th>Nik</th>
                                                <th>Nama</th>
                                                <!-- <th>Gaji</th> -->
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- ./wrapper -->   
@endsection
@section('extend-js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
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
    $('#m-kwitansi').addClass('active');
    $('#m-kwitansi').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>

<script type="text/javascript">
    $(function () { 
        $(document).on('click', '.check', function() {
            var id = $('input[name="idkaryawan"]').val();
            var bagian = $('input[name="bagian"]').val();
            var tglpisah = $('input[name="tglpisah"]').val();
            var type = $('select[name="opsi"] option:selected').val();
            var category = $('select[name="category"] option:selected').val();
            if(type == 'UangPisah'){
                if(id.length < 6){
                    alert('id harus di isi');
                    return;
                } else if (tglpisah.length < 1) {
                    alert('tgl pisah harus di isi');
                    return;
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "/simulasi",
                        data: {
                            'idkaryawan' : id, 
                            'tglpisah' : tglpisah,
                            'type' : type,
                            'category' : category
                        },
                        success: function(response) {
                            // console.log(response);
                            $('#nama').val(response[0]['NAME']);
                            $('#bagian').val(response[0]['BAGIAN']);
                            $('#tglmasuk').val(response[0]['TGLMASUK']);
                            $('#gaji').val(response[0]['GAJI']);
                            $('#jabatan').val(response[0]['TUNJANGAN']);
                            $('#total').val(response[0]['TOTAL']);
                            $('#masakerja').val(response[0]['LAMAKERJA']);
                            $('#keterangan').val(response[0]['KETERANGAN']);
                        }
                    });
                }
            } else if (type == 'DUKA') {
                if(id.length < 1){
                    alert('digit ID kurang');
                    return;
                } else if (category.length < 1) {
                    alert('category harus di isi');
                    return;
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "/simulasi",
                        data: {
                            'idkaryawan' : id, 
                            'tglpisah' : tglpisah,
                            'type' : type,
                            'category' : category
                        },
                        success: function(response) {
                            $('#nama').val(response[0]['NAME']);
                            $('#bagian').val(response[0]['BAGIAN']);
                            $('#tglmasuk').val(response[0]['TGLMASUK']);
                            $('#gaji').val(response[0]['GAJI']);
                            $('#jabatan').val(response[0]['TUNJANGAN']);
                            $('#total').val(response[0]['TOTAL']);
                            $('#masakerja').val(response[0]['LAMAKERJA']);
                            $('#keterangan').val(response[0]['KETERANGAN']);
                        }
                    });
                }
            } else if (type == 'PERNIKAHAN' || type == 'KELAHIRAN') {
                if(id.length < 1){
                    alert('id harus di isi');
                    return;
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "/simulasi",
                        data: {
                            'idkaryawan' : id, 
                            'tglpisah' : tglpisah,
                            'type' : type,
                            'category' : category
                        },
                        success: function(response) {
                            $('#nama').val(response[0]['NAME']);
                            $('#bagian').val(response[0]['BAGIAN']);
                            $('#tglmasuk').val(response[0]['TGLMASUK']);
                            $('#gaji').val(response[0]['GAJI']);
                            $('#jabatan').val(response[0]['TUNJANGAN']);
                            $('#total').val(response[0]['TOTAL']);
                            $('#masakerja').val(response[0]['LAMAKERJA']);
                            $('#keterangan').val(response[0]['KETERANGAN']);
                        }
                    });
                }
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "/simulasi",
                    data: {
                        'idkaryawan' : id, 
                        'tglpisah' : tglpisah,
                        'type' : type,
                        'category' : category
                    },
                    success: function(response) {
                        $('#nama').val(response[0]['NAME']);
                        $('#bagian').val(response[0]['BAGIAN']);
                        $('#tglmasuk').val(response[0]['TGLMASUK']);
                        $('#gaji').val(response[0]['GAJI']);
                        $('#tunjangan').val(response[0]['TUNJANGAN']);
                        $('#total').val(response[0]['TOTAL']);
                        $('#masakerja').val(response[0]['LAMAKERJA']);
                        $('#keterangan').val(response[0]['KETERANGAN']);
                    }
                });
            }
        })

        $(document).on('click', '.save', function() {
            var id = $('input[name="idkaryawan"]').val();
            var bagian = $('input[name="bagian"]').val();
            var tglpisah = $('input[name="tglpisah"]').val();
            var type = $('select[name="opsi"] option:selected').val();
            var category = $('select[name="category"] option:selected').val();
            var keterangan = $('textarea[name="keterangan"]').val();
            var namakaryawan = $('input[name="nama"]').val();
            var tglmasuk = $('input[name="tglmasuk"]').val();
            var gaji = $('input[name="gaji"]').val();
            var jabatan = $('input[name="jabatan"]').val();
            var total = $('input[name="total"]').val();
            var masakerja = $('input[name="masakerja"]').val();
            if(type == 'UangPisah'){
                if(id.length < 6){
                    alert('id harus di isi');
                    return;
                } else if (tglpisah.length < 1) {
                    alert('tgl pisah harus di isi');
                    return;
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/insert",
                        type: 'POST',
                        data: {
                            'idkaryawan' : id, 
                            'opsi' : type,
                            'category' : category,
                            'keterangan' : keterangan,
                            'nama' : namakaryawan,
                            'tglmasuk' : tglmasuk,
                            'gaji' : gaji,
                            'jabatan' : jabatan,
                            'total' : total,
                            'masakerja' : masakerja,
                            'tglpisah' : tglpisah
                        },
                        success: function(response){ 
                            console.log(response);
                            if(response == "Max"){
                                alert("Maximum Input 4 Kwitansi");
                            } else if(response == "Duplicate"){
                                alert("Type Tidak Boleh Sama");
                            } else if(response == "already exist"){
                                alert("Kwitansi sudah pernah dibuat");
                            } else {
                                document.getElementById("form1").reset();
                                $('#datakwitansi').DataTable().ajax.reload();
                            }  
                        
                        },
                        error: function (error) {
                            console.error(error);
                        },
                    });
                }
            } else if (type == 'DUKA') {
                if(id.length < 1){
                    alert('digit ID kurang');
                    return;
                } else if (category.length < 1) {
                    alert('category harus di isi');
                    return;
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/insert",
                        type: 'POST',
                        data: {
                            'idkaryawan' : id, 
                            'opsi' : type,
                            'category' : category,
                            'keterangan' : keterangan,
                            'nama' : namakaryawan,
                            'tglmasuk' : tglmasuk,
                            'gaji' : gaji,
                            'jabatan' : jabatan,
                            'total' : total,
                            'masakerja' : masakerja,
                            'tglpisah' : tglpisah
                        },
                        success: function(response){ 
                            console.log(response);
                            if(response == "Max"){
                                alert("Maximum Input 4 Kwitansi");
                            } else if(response == "Duplicate"){
                                alert("Type Tidak Boleh Sama");
                            } else if(response == "already exist"){
                                alert("Kwitansi sudah pernah dibuat");
                            } else {
                                document.getElementById("form1").reset();
                                $('#datakwitansi').DataTable().ajax.reload();
                            }  
                        
                        },
                        error: function (error) {
                            console.error(error);
                        },
                    });
                }
            } else if (type == 'PERNIKAHAN' || type == 'KELAHIRAN') {
                if(id.length < 1){
                    alert('id harus di isi');
                    return;
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/insert",
                        type: 'POST',
                        data: {
                            'idkaryawan' : id, 
                            'opsi' : type,
                            'category' : category,
                            'keterangan' : keterangan,
                            'nama' : namakaryawan,
                            'tglmasuk' : tglmasuk,
                            'gaji' : gaji,
                            'jabatan' : jabatan,
                            'total' : total,
                            'masakerja' : masakerja,
                            'tglpisah' : tglpisah
                        },
                        success: function(response){ 
                            // console.log(response);
                            if(response == "Max"){
                                alert("Maximum Input 4 Kwitansi");
                            } else if(response == "Duplicate"){
                                alert("Type Tidak Boleh Sama");
                            } else if(response == "already exist"){
                                alert("Kwitansi sudah pernah dibuat");
                            } else {
                                document.getElementById("form1").reset();
                                $('#datakwitansi').DataTable().ajax.reload();
                            }  
                        
                        },
                        error: function (error) {
                            console.error(error);
                        },
                    });
                }
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/insert",
                    type: 'POST',
                    data: {
                        'idkaryawan' : id, 
                        'opsi' : type,
                        'category' : category,
                        'keterangan' : keterangan,
                        'nama' : namakaryawan,
                        'tglmasuk' : tglmasuk,
                        'gaji' : gaji,
                        'jabatan' : jabatan,
                        'total' : total,
                        'masakerja' : masakerja,
                        'tglpisah' : tglpisah
                    },
                    success: function(response){ 
                        // console.log(response);
                        if(response == "Max"){
                            alert("Maximum Input 4 Kwitansi");
                        } else if(response == "Duplicate"){
                            alert("Type Tidak Boleh Sama");
                        } else if(response == "already exist"){
                                alert("Kwitansi sudah pernah dibuat");
                        } else {
                            document.getElementById("form1").reset();
                            $('#datakwitansi').DataTable().ajax.reload();
                        }  
                    
                    },
                    error: function (error) {
                        console.error(error);
                    },
                });
            }
        })

        $(document).on('click', '.delete', function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/delete",
                type: 'POST',
                success: function(response){ 
                    console.log(response);
                    if(response == 'success'){
                        $('#datakwitansi').DataTable().ajax.reload();
                        alert("Delete Successfully");
                    } else {
                        alert("Delete Failed");
                    }
                },
                error: function (error) {
                    console.error(error);
                },
            });
        })

        var table = $('#datakwitansi').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            searching: false,
            ajax: {
                "url": '{{ route("list-kwitansi") }}'
            },
            columns: [
                {
                    data: 'idkwitansi',
                    nama: 'idkwitansi'
                },
                {
                    data: 'type',
                    nama: 'type'
                },
                {
                    data: 'nik',
                    name: 'nik'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                // {
                //     data: 'gaji',
                //     nama: 'gaji'
                // },
                {
                    data: 'total',
                    name: 'total'
                },
            ],
            oLanguage: {
                "sLengthMenu": "Tampilkan _MENU_ data",
                "sProcessing": "Loading...",
                "sSearch": "Search:",
                "sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ data"
            },
        });

        // $('#btn-submit-form').on('click', function() {
        //     $('#form').submit();
        //     $(this).attr('disabled', true);
        //     $(this).text("Loading ...");
        // });

        // Use datepicker on the date inputs
        $("input[type=date]").datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function(dateText, inst) {
            $(inst).val(dateText); // Write the value in the input
        }
        });

        // Code below to avoid the classic date-picker
        $("input[type=date]").on('click', function() {
        return false;
        });
    });
</script>
<script>
    $(function() {
        var form = $("#form");
        var select = $("#opsi");
        var hide1 = $("#hide1");
        var hide2 = $("#hide2");
        var hide3 = $("#hide3");
        var hide4 = $("#hide4");
        var hide5 = $("#hide5");
        var hide6 = $("#hide6");
        var hide7 = $("#hide7");
        var hide8 = $("#hide8");
        var hide9 = $("#hide9");
        var hide10 = $("#hide10");
        var hide11 = $("#hide11");
        var hide12 = $("#hide12");
        var hide13 = $("#hide13");
        var hide14 = $("#hide14");
        var hide15 = $("#hide15");
        
        hide1.hide();
        hide2.hide();
        hide3.hide();
        hide4.hide();
        hide5.hide();
        hide6.hide();
        hide7.hide();
        hide8.hide();
        hide9.hide();
        hide10.hide();
        hide11.hide();
        hide12.hide();
        hide13.hide();
        hide14.hide();
        hide15.hide();

        select.change(function() {
            value = $(this).find(":selected").val()
            if (value == 'UangPisah') {
                hide1.show();
                hide2.show();
                hide3.hide();
                hide4.show();
                hide5.show();
                hide6.show();
                hide7.show();
                hide8.show();
                hide9.show();
                hide10.show();
                hide11.show();
                hide12.show();
                hide13.show();
                hide14.show();
                hide15.show();
            } else if(value == 'DUKA'){
                hide1.show();
                hide2.hide();
                hide3.show();
                hide4.show();
                hide5.show();
                hide6.hide();
                hide7.hide();
                hide14.hide();
                hide8.show();
                hide9.hide();
                hide10.show();
                hide11.show();
                hide12.show();
                hide13.show();
                hide14.hide();
                hide15.show();
            } else {
                hide1.show();
                hide2.hide();
                hide3.hide();
                hide4.show();
                hide5.show();
                hide6.hide();
                hide7.hide();
                hide14.hide();
                hide8.show();
                hide9.hide();
                hide10.show();
                hide11.show();
                hide12.show();
                hide13.show();
                hide14.hide();
                hide15.show();
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
@endsection
