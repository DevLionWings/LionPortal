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
                    <h5>Master Category</h5>
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
                                <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#modal-add-counter"><i class="fa fa-plus" aria-hidden="true"></i>Add Category</button>
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
                                        <th>System Id</th>
                                        <th>Category Id</th>
                                        <th>Description</th>
                                        <th>Aktif/Non Aktif</th>
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
    <div id="modal-add-counter" class="modal fade show" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="form" name="form" action="{{ route('category-insert') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-check-label" for="desc">Category :</label>
                            <input type="text" name="desc" class="form-control" id="desc" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="row">
                            <div class="name">Need Approval ?</div>
                            <div class="col-md-4"> 
                                <input type="radio" id="approve" name="approve" value="X" checked> 
                                <label for="">ya</label><br>
                            </div>
                            <div class="col-md-4"> 
                                <input type="radio" id="approve" name="approve" value="">
                                <label for="">Tidak</label><br>  
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <div class="name">Need Approval ?</div>
                            <div class="input-group value">
                                <select id="approve" name="approve" class="form-control input--style-6" required>
                                    <option value="X">ya</option>
                                    <option value=" ">Tidak</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="name">Assign to System :</div>
                            <div class="col-md-4"> 
                                <input type="checkbox" class="largerCheckbox" name="SY001" id="SY001"> 
                                <label for="">SAP</label><br>
                            </div>
                            <div class="col-md-4"> 
                                <input type="checkbox" class="largerCheckbox" name="SY002" id="SY002">
                                <label for="">NON SAP</label><br>  
                            </div>
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
    <!-- /.content -->
    <div class="modal fade show" id="modal-delete-user" aria-modal="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('category-delete')}}" method="post">
                    @csrf
                    <input type="hidden" id="delete-category-id" name="id"/>
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
    $('#m-category').addClass('active');
    $('#m-category').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script>
    $(function () {    
        $('#save-btn').on('click', function() {
            $('#form').submit();
            $(this).attr('disabled', true);
            $(this).text("Loading ...");
        });

        $(document).on('click', '.delete', function () {
            $('#delete-category-id').val($(this).attr("data-categoryid"));
            $('#modal-delete-user').modal('show');;
        })

        var table = $('#list').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: true,
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ],
            ajax: "{{ route('category-list') }}",
            order: [[ 1, "desc" ]],
            columns: [
                {
                    data: 'systemid',
                    name: 'systemid'
                },
                {
                    data: 'categoryid',
                    name: 'categoryid'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'flagging',
                    render: function (data){
                        if(data == "1"){
                            statusText = `<span class="badge badge-success">ON</span>`;
                        } else {
                            statusText = `<span class="badge badge-danger">OFF</span>`;
                        }
                        return statusText;
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
