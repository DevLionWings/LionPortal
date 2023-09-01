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
                    <h5>Master Karyawan</h5>
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
                                <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#modal-add-user"><i class="fa fa-plus" aria-hidden="true"></i>Add Karyawan</button>
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
                            <table id="list" class="table table-bordered table-hover display nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th>IdSmu</th>
                                        <!-- <th>Id</th> -->
                                        <th>Nama</th>
                                        <th>Tanggal In</th>
                                        <th>Sex</th>
                                        <th>Departement</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Gaji</th>
                                        <th>Turun Gaji</th>
                                        <th>Jabatan</th>
                                        <th>Spsi</th>
                                        <th>Koperasi</th>
                                        <!-- <th>Date</th> -->
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
    <div id="modal-add-user" class="modal fade show" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Karyawan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="form" name="form" action="{{ route('karyawan-insert') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-check-label" for="idsmu">Id Smu:</label>
                            <input type="text" name="idsmu" class="form-control" id="idsmu" maxlength="6">
                        </div>
                        <!-- <div class="form-group">
                            <label class="form-check-label" for="id">Id:</label>
                            <input type="text" name="id" class="form-control" id="id" maxlength="5">
                        </div> -->
                        <div class="mb-3">
                            <label class="form-check-label" for="nama">Nama:</label>
                            <input type="text" name="nama" id="nama" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="tgl_in">Tgl In:</label>
                            <input type="date" name="tgl_in" id="tgl_in" class="form-control">
                        </div>
                        <div class="form-group">
                            <div class="name">Sex:</div>
                            <div class="input-group value">
                                <select id="sex" name="sex" class="form-control input--style-6" required>
                                    <option value=""> Masukkan Pilihan :</option>
                                    <option value="L">L</option>
                                    <option value="P">P</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="bagian">Department:</label>
                            <input type="text" name="bagian" id="bagian" class="form-control" maxlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="tgl_lahir">Tanggal Lahir:</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" maxlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="gaji">Gaji:</label>
                            <input type="text" name="gaji" id="gaji" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="jabatan">Jabatan:</label>
                            <input type="text" name="jabatan" id="jabatan" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="spsi">Spsi:</label>
                            <input type="text" name="spsi" id="spsi" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="koperasi">Koperasi:</label>
                            <input type="text" name="koperasi" id="koperasi" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="save-btn" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>

    <div id="modal-update-karyawan"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Karyawan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('karyawan-update') }}" method="post" name='update-karyawan' id='update-karyawan'>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-check-label" for="idsmu">Id Smu:</label>
                            <input type="text" name="idsmu" class="form-control" id="idsmu" maxlength="6">
                        </div>
                        <!-- <div class="form-group">
                            <label class="form-check-label" for="id">Id:</label>
                            <input type="text" name="id" class="form-control" id="id" maxlength="5">
                        </div> -->
                        <div class="mb-3">
                            <label class="form-check-label" for="nama">Nama:</label>
                            <input type="text" name="nama" id="nama" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="tgl_in">Tgl In:</label>
                            <input type="date" name="tgl_in" id="tgl_in" class="form-control">
                        </div>
                        <div class="form-group">
                            <div class="name">Sex:</div>
                            <div class="input-group value">
                                <select id="sex" name="sex" class="form-control input--style-6" required>
                                    <option value=""> Masukkan Pilihan :</option>
                                    <option value="L">L</option>
                                    <option value="P">P</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="bagian">Department:</label>
                            <input type="text" name="bagian" id="bagian" class="form-control" maxlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="tgl_lahir">Tanggal Lahir:</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" maxlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="gaji">Gaji:</label>
                            <input type="text" name="gaji" id="gaji" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="jabatan">Jabatan:</label>
                            <input type="text" name="jabatan" id="jabatan" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="spsi">Spsi:</label>
                            <input type="text" name="spsi" id="spsi" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="koperasi">Koperasi:</label>
                            <input type="text" name="koperasi" id="koperasi" class="form-control">
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
    <!-- /.content -->
    <div class="modal fade show" id="modal-delete-user" aria-modal="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Karyawan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('karyawan-delete')}}" method="post">
                    @csrf
                    <input type="hidden" id="delete-karyawan-id" name="id"/>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus <span class="text-bold"></span></p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Ya, hapus</button>
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

<script>
    $('.nav-link.active').removeClass('active');
    $('#m-karyawan').addClass('active');
    $('#m-karyawan').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script>
    $(function () {    
        $('#save-btn').on('click', function() {
            $('#form').submit();
            $(this).attr('disabled', true);
            $(this).text("Loading ...");
        });

        $(document).on('click', '.delete', function () {
            $('#delete-karyawan-id').val($(this).attr("data-idsmu"));
            $('#modal-delete-user').modal('show');;
        })

        $(document).on('click', '.edit', function() {
            $('#modal-update-karyawan').modal({backdrop: 'static', keyboard: false})  
            var idsmu = $(this).attr('data-idsmu');
            var nama = $(this).attr('data-nama');
            var tgl_in = $(this).attr('data-tgl_in');
            var department = $(this).attr('data-bagian');
            var tgl_lahir = $(this).attr('data-tgl_lahir');
            var gaji = $(this).attr('data-gaji');
            var jabatan  = $(this).attr('data-jabatan');
            var spsi  = $(this).attr('data-spsi');
            var koperasi  = $(this).attr('data-koperasi');
            var $modal = $('#modal-update-karyawan');
            var $form = $modal.find('form[name="update-karyawan"]');
            $form.find('input[name="idsmu"]').val(idsmu);
            $form.find('input[name="nama"]').val(nama);
            $form.find('input[name="tgl_in"]').val(tgl_in);
            $form.find('input[name="department"]').val(department);
            $form.find('input[name="tgl_lahir"]').val(tgl_lahir);
            $form.find('input[name="gaji"]').val(gaji);
            $form.find('input[name="jabatan"]').val(jabatan);
            $form.find('input[name="spsi"]').val(spsi);
            $form.find('input[name="koperasi"]').val(koperasi);
            var sex_options = $form_update.find("select[name='sex'").children();
            $.each(sex_options, function(key, value) {
                if($(value).val() === sex[0]) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            $modal.modal('show');
        });

        var table = $('#list').DataTable({
            processing: true,
            scrollX: true,
            serverSide: true,
            responsive: false,
            searching: true,
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ],
            ajax: "{{ route('karyawan-list') }}",
            order: [[ 3, "desc" ]],
            columns: [
                {
                    data: 'idsmu',
                    name: 'idsmu'
                },
                // {
                //     data: 'id',
                //     name: 'id'
                // },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'tgl_in',
                    name: 'tgl_in'
                },
                {
                    data: 'sex',
                    name: 'sex'
                },
                {
                    data: 'bagian',
                    name: 'bagian'
                },
                {
                    data: 'tgl_lahir',
                    name: 'tgl_lahir'
                },
                {
                    data: 'gaji',
                    name: 'gaji'
                },
                {
                    data: 'turun_gaji',
                    name: 'turun_gaji'
                },
                {
                    data: 'jabatan',
                    name: 'jabatan'
                },
                {
                    data: 'spsi',
                    name: 'spsi'
                },
                {
                    data: 'koperasi',
                    name: 'koperasi'
                },
                // {
                //     data: 'data_update',
                //     name: 'data_update'
                // },
                {
                    data: 'action',
                    name: 'action',
                },
            ],
            oLanguage: {
				"sLengthMenu": "Tampilkan _MENU_ data",
				"sProcessing": "Loading...",
				"sSearch": "Keyword:",
				"sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ data" 	
			},
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
    $('#document').ready(function(){
            $('#close-btn').on('click', function(){
                location.reload();
        });
    });
    $('#document').ready(function(){
            $('#close-btn2').on('click', function(){
                location.reload();
        });
    });
</script>
<script>
    $('.toast').toast('show');
</script>
@endsection
