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
                    <h5>Master User</h5>
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
                                <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#modal-add-user"><i class="fa fa-plus" aria-hidden="true"></i>Add User</button>
                            </div>
                            <div class="row align-items-end">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>User Login :</label>
                                        <div class="input-group value">
                                            <select id="status" name="status" class="form-control input--style-6" style="width: 100%;">
                                            <option value=""> all</option>
                                                <option value="1">Login</option>
                                                <option value="0">Logout</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button id="login" name="login" class="login btn-submit btn btn-secondary" ><i class="fas fa-search"></i></button>
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
                            <table id="list" class="table table-bordered table-hover display nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th>User Id</th>
                                        <th>Username</th>
                                        <th>Departmentid</th>
                                        <th>Plantid</th>
                                        <th>Roleid</th>
                                        <th>Spvid</th>
                                        <th>Mgrid</th>
                                        <th>Usermail</th>
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
                    <h4 class="modal-title">Add User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="form" name="form" action="{{ route('user-insert') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-check-label" for="userid">Userid Id:</label>
                            <input type="text" name="userid" class="form-control" id="userid" maxlength="6">
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="username">Username:</label>
                            <input type="text" name="username" class="form-control" id="username"></input>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="pass">Password:</label>
                            <input type="text" name="pass" id="pass" class="form-control">
                        </div>
                        <div class="form-group">
                            <div class="name">Departmentid:</div>
                            <div class="input-group value">
                                <select id="deptid" name="deptid" class="form-control input--style-6" required>
                                    <option value=""> Masukkan Pilihan :</option>
                                    @foreach($dept as $deptcode)
                                    <option value="{{ $deptcode['ID'] }}">{{ $deptcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="name">Plantid:</div>
                            <div class="input-group value">
                                <select id="plantid" name="plantid" class="form-control input--style-6" required>
                                    <option value=""> Masukkan Pilihan :</option>
                                    @foreach($plnt as $plntcode)
                                    <option value="{{ $plntcode['ID'] }}">{{ $plntcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="name">Roleid:</div>
                            <div class="input-group value">
                                <select id="roleid" name="roleid" class="form-control input--style-6" required>
                                    <option value=""> Masukkan Pilihan :</option>
                                    @foreach($rol as $rolcode)
                                    <option value="{{ $rolcode['ID'] }}">{{ $rolcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="spvid">spvid:</label>
                            <input type="text" name="spvid" id="spvid" class="form-control" maxlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="mgrid">mgrid:</label>
                            <input type="text" name="mgrid" id="mgrid" class="form-control" maxlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="email">Usermail:</label>
                            <input type="text" name="email" id="email" class="form-control">
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

    <div id="modal-update-user"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('user-update') }}" method="post" name='update-user' id='update-user'>
                    @csrf
                    <div class="modal-body">
                    <div class="form-group">
                            <label class="form-check-label" for="userid">Userid:</label>
                            <input type="text" name="userid" class="form-control" id="userid" maxlength="6">
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="username">Username:</label>
                            <input type="text" name="username" class="form-control" id="username"></input>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="pass">Password:</label>
                            <input type="text" name="pass" id="pass" class="form-control">
                        </div>
                        <div class="form-group">
                            <div class="name">Departmentid:</div>
                            <div class="input-group value">
                                <select id="deptid" name="deptid" class="form-control input--style-6" required>
                                    @foreach($dept as $deptcode)
                                    <option value="{{ $deptcode['ID'] }}">{{ $deptcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="name">Plantid:</div>
                            <div class="input-group value">
                                <select id="plantid" name="plantid" class="form-control input--style-6" required>
                                    @foreach($plnt as $plntcode)
                                    <option value="">Masukan Pilihan</option>
                                    <option value="{{ $plntcode['ID'] }}">{{ $plntcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="name">Roleid:</div>
                            <div class="input-group value">
                                <select id="roleid" name="roleid" class="form-control input--style-6" required>
                                    @foreach($rol as $rolcode)
                                    <option value="">Masukan Pilihan</option>
                                    <option value="{{ $rolcode['ID'] }}">{{ $rolcode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="spvid">spvid:</label>
                            <input type="text" name="spvid" id="spvid" class="form-control" maxlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="mgrid">mgrid:</label>
                            <input type="text" name="mgrid" id="mgrid" class="form-control" maxlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label" for="usermail">Usermail:</label>
                            <input type="text" name="usermail" id="usermail" class="form-control">
                        </div>
                        <div class="form-group">
                            <div class="name">Status Login:</div>
                            <div class="input-group value">
                                <select id="status" name="status" class="form-control input--style-6" required>
                                    <option value="1">Login</option>
                                    <option value="0">Logout</option>
                                </select>
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
    <div id="modal-updatelogin-user"  class="modal fade show"  aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Login</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('login-update') }}" method="post" name='updatelogin-user' id='updatelogin-user'>
                    @csrf
                    <div class="modal-body">
                    <div class="form-group">
                            <label class="form-check-label" for="userid">Userid:</label>
                            <input type="text" name="userid" class="form-control" id="userid" maxlength="6">
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="username">Username:</label>
                            <input type="text" name="username" class="form-control" id="username"></input>
                        </div>
                        <div class="form-group">
                            <div class="name">Status Login:</div>
                            <div class="input-group value">
                                <select id="status" name="status" class="form-control input--style-6" required>
                                    <option value="0">Logout</option>
                                    <option value="1">Login</option>
                                </select>
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
    <!-- /.content -->
    <div class="modal fade show" id="modal-delete-user" aria-modal="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('user-delete')}}" method="post">
                    @csrf
                    <input type="hidden" id="delete-user-id" name="id"/>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus <span class="text-bold" id="delete-user-no"></span></p>
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
    $('#m-user').addClass('active');
    $('#m-user').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script>
    $(function () {    
        $('#save-btn').on('click', function() {
            $('#form').submit();
            $(this).attr('disabled', true);
            $(this).text("Loading ...");
        });

        $(document).on('click', '.delete', function () {
            $('#delete-user-id').val($(this).attr("data-userid"));
            $('#modal-delete-user').modal('show');;
        })

        $(document).on('click', '.edit', function() {
            $('#modal-update-user').modal({backdrop: 'static', keyboard: false})  
            var userid = $(this).attr('data-userid');
            var username = $(this).attr('data-username');
            var pass = $(this).attr('data-pass');
            var departmentid = $(this).attr('data-departmentid');
            var plantid = $(this).attr('data-plantid');
            var roleid = $(this).attr('data-roleid');
            var spvid  = $(this).attr('data-spvid');
            var mgrid  = $(this).attr('data-mgrid');
            var status  = $(this).attr('data-status');
            var usermail  = $(this).attr('data-usermail');
            var $modal = $('#modal-update-user');
            var $form_update = $modal.find('form[name="update-user"]');
            $form.find('input[name="userid"]').val(userid);
            $form.find('input[name="username"]').val(username);
            $form.find('input[name="pass"]').val(pass);
            $form.find('input[name="spvid"]').val(spvid);
            $form.find('input[name="mgrid"]').val(mgrid);
            $form.find('input[name="usermail"]').val(usermail);
            var deptid_options = $form_update.find("select[name='deptid'").children();
            $.each(deptid_options, function(key, value) {
                if($(value).val() === departmentid[0]) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            var plantid_options = $form_update.find("select[name='plantid'").children();
            $.each(plantid_options, function(key, value) {
                if($(value).val() === plantid[0]) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            var roleid_options = $form_update.find("select[name='roleid'").children();
            $.each(roleid_options, function(key, value) {
                if($(value).val() === roleid[0]) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            var status_options = $form_update.find("select[name='status'").children();
            $.each(status_options, function(key, value) {
                if($(value).val() === status[0]) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            $modal.modal('show');
        });

        $(document).on('click', '.editlogin', function() {
            $('#modal-updatelogin-user').modal({backdrop: 'static', keyboard: false})  
            var userid = $(this).attr('data-userid');
            var username = $(this).attr('data-username');
            var $modal = $('#modal-updatelogin-user');
            var $form = $modal.find('form[name="updatelogin-user"]');
            $form.find('input[name="userid"]').val(userid);
            $form.find('input[name="username"]').val(username);
            var status_options = $form.find("select[name='status'").children();
            $.each(status_options, function(key, value) {
                if($(value).val() === status[0]) {
                    $(value).attr('selected', true);
                } else {
                    $(value).attr('selected', false);
                }
            });
            $modal.modal('show');
        });

        $(document).on('click', '.login', function submit() {
            var login = $('select[name="status"] option:selected').val();
           
            $('#list').DataTable().clear().destroy();
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
                ajax: {
                    url: "{{ route('login-list') }}",
                    "data": function (d) {
                        d.login = $('select[name="status"] option:selected').val();
                    }
                },
                order: [[ 4, "desc" ]],
                columns: [
                    {
                        data: 'userid',
                        name: 'userid'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'departmentid',
                        name: 'departmentid'
                    },
                    {
                        data: 'plantid',
                        name: 'plantid'
                    },
                    {
                        data: 'roleid',
                        name: 'roleid'
                    },
                    {
                        data: 'spvid',
                        name: 'spvid'
                    },
                    {
                        data: 'mgrid',
                        name: 'mgrid'
                    },
                    {
                        data: 'usermail',
                        name: 'usermail'
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
            });
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
            ajax: "{{ route('user-list') }}",
            order: [[ 4, "desc" ]],
            columns: [
                {
                    data: 'userid',
                    name: 'userid'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'departmentid',
                    name: 'departmentid'
                },
                {
                    data: 'plantid',
                    name: 'plantid'
                },
                {
                    data: 'roleid',
                    name: 'roleid'
                },
                {
                    data: 'spvid',
                    name: 'spvid'
                },
                {
                    data: 'mgrid',
                    name: 'mgrid'
                },
                {
                    data: 'usermail',
                    name: 'usermail'
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
