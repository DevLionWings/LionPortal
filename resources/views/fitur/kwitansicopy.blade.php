@extends('parent.master')
@section('extend-css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap/bootstrap.min.css') }}">
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
</div>
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
<script type="text/javascript">
    $(document).on('click', '.check', function() {
        var id = $('input[name="idkaryawan"]').val();
        var tglpisah = $('input[name="tglpisah"]').val();
        var type = $('select[name="opsi"] option:selected').val();
        var category = $('select[name="category"] option:selected').val();
        if(type == 'UP'){
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

    // document.getElementById("formData").addEventListener("click", function(event){
    //     event.preventDefault()
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
