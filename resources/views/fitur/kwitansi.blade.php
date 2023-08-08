@extends('parent.master')
@section('extend-css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery/jquery-ui.css') }}">
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
                            <form action="{{ route('print-kwitansi') }}" id="form" name="form" method="get" >
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
                                                            <option value="UP">Uang Pisah</option>
                                                            <option value="TJ001">Uang Duka</option> 
                                                            <option value="TJ002">Uang Pernikahan</option> 
                                                            <option value="TJ003">Uang Kelahiran</option> 
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3" id="hide1">
                                                    <label class="form-check-label" for="idkaryawan">ID Karyawan:</label>
                                                    <input type="text" name="idkaryawan" class="form-control" id="idkaryawan" required>
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
                                                    <textarea type="text" name="keterangan" class="form-control" id="keterangan" readonly></textarea>
                                                </div>
                                                <div class="mb-6" id="hide11">
                                                    <button type="button" class="btn btn-danger btn-md float-left" onclick="window.location='{{ url()->previous() }}'">Back</button>
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
                                                <div class="mb-3" id="hide6">
                                                    <label class="form-check-label" >Tanggal Masuk:</label>
                                                    <input type="text" name="tglmasuk" id="tglmasuk" class="form-control" readonly> 
                                                </div>
                                                <div class="mb-3" id="hide7">
                                                    <label class="form-check-label" >Gaji+Jabatan:</label>
                                                    <input type="text" name="gaji" id="gaji" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3" id="hide8">
                                                    <label class="form-check-label" for="total">Total:</label>
                                                    <input type="text" name="total" id="total" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3" id="hide9">
                                                    <label class="form-check-label" >Lama Masa Kerja:</label>
                                                    <input type="text" name="masakerja" id="masakerja" class="form-control" readonly>
                                                </div>
                                                <div class="mb-6" id="hide10">
                                                    <button type="submit" class="btn btn-success btn-md float-right">Print</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/jquery/jquery-ui.js') }}"></script>
<script>
    $('.nav-link.active').removeClass('active');
    $('#m-kwitansi').addClass('active');
    $('#m-kwitansi').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script>
    
    $(document).on('click', '.check', function() {
        var id = $('input[name="idkaryawan"]').val();
        var tglpisah = $('input[name="tglpisah"]').val();
        var type = $('select[name="opsi"] option:selected').val();
        var category = $('select[name="category"] option:selected').val();
        if(type == 'UP'){
            if(id.length < 1){
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
                        $('#nama').val(response[0]['NAME']);
                        $('#tglmasuk').val(response[0]['TGLMASUK']);
                        $('#gaji').val(response[0]['GAJI']);
                        $('#total').val(response[0]['TOTAL']);
                        $('#masakerja').val(response[0]['LAMAKERJA']);
                        $('#keterangan').val(response[0]['KETERANGAN']);
                    }
                });
            }
        } else if (type == 'TJ001') {
            if(id.length < 1){
                alert('id harus di isi');
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
                        $('#tglmasuk').val(response[0]['TGLMASUK']);
                        $('#gaji').val(response[0]['GAJI']);
                        $('#total').val(response[0]['TOTAL']);
                        $('#masakerja').val(response[0]['LAMAKERJA']);
                        $('#keterangan').val(response[0]['KETERANGAN']);
                    }
                });
            }
        } else if (type == 'TJ002' || type == 'TJ003') {
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
                        $('#tglmasuk').val(response[0]['TGLMASUK']);
                        $('#gaji').val(response[0]['GAJI']);
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
                    $('#tglmasuk').val(response[0]['TGLMASUK']);
                    $('#gaji').val(response[0]['GAJI']);
                    $('#total').val(response[0]['TOTAL']);
                    $('#masakerja').val(response[0]['LAMAKERJA']);
                    $('#keterangan').val(response[0]['KETERANGAN']);
                }
            });
        }
    })

    // $('#btn-submit-form').on('click', function() {
    //     $('#form').submit();
    //     $(this).attr('disabled', true);
    //     $(this).text("Loading ...");
    // });

    document.getElementById("formData").addEventListener("click", function(event){
        event.preventDefault()
    });

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

        select.change(function() {
            value = $(this).find(":selected").val()
            if (value == 'UP') {
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
            } else if(value == 'TJ001'){
                hide1.show();
                hide2.hide();
                hide3.show();
                hide4.show();
                hide5.show();
                hide6.hide();
                hide7.hide();
                hide8.show();
                hide9.hide();
                hide10.show();
                hide11.show();
                hide12.show();
            } else {
                hide1.show();
                hide2.hide();
                hide3.hide();
                hide4.show();
                hide5.show();
                hide6.hide();
                hide7.hide();
                hide8.show();
                hide9.hide();
                hide10.show();
                hide11.show();
                hide12.show();
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
