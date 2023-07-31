@extends('parent.master')
@section('extend-css')
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('image-upload/image-uploader.min.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
                    <h1>Form Container</h1>
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
                            <h3 class="card-title">Add Container</h3>
                        </div>
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
                            <form id="form" name="form" method="POST" action="{{ route('add-tiket') }}">
							    @csrf
                                <div class="row">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                            @if(session('roleid') == 'RD004' || session('roleid') == 'RD005' || session('roleid') == 'RD006')
                                                <div class="form-group">
                                                    <div class="name">User Request :</div>
                                                    <div class="input-group value">
                                                        <select id="user" name="user" class="form-control input--style-6" required>
                                                            <option value=""> Masukkan Pilihan :</option>
                                                            @foreach($usreq as $usreqcode)
                                                            <option value="{{ $usreqcode['ID'] }}">{{ $usreqcode['NAME'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="form-group">
                                                    <div class="name">Category :</div>
                                                    <div class="input-group value">
                                                        <select id="category" name="category" class="form-control input--style-6" required>
                                                            <option value=""> Masukkan Pilihan :</option>
                                                            @foreach($categ as $categcode)
                                                            <option value="{{ $categcode['ID'] }}">{{ $categcode['NAME'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="name">Priority :</div>
                                                    <div class="input-group value">
                                                        <select id="priority" name="priority" class="form-control input--style-6" required>
                                                            <option value=""> Masukkan Pilihan :</option>
                                                            @foreach($prior as $priorcode)
                                                            <option value="{{ $priorcode['ID'] }}">{{ $priorcode['NAME'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" id="priorityname" name="priorityname" value="{{ $priorcode['NAME'] }}">
                                                    </div>
                                                </div>
                                                @if(session('roleid') == 'RD006')
                                                <div class="form-group">
                                                    <div class="name">Assigned To :</div>
                                                    <div class="input-group value">
                                                        <select id="assignto" name="assignto" class="form-control input--style-6" required>
                                                            <option value=""> Masukkan Pilihan :</option>
                                                            @foreach($assn as $assncode)
                                                            <option value="{{ $assncode['ID'] }}">{{ $assncode['NAME'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="form-group">
                                                    <label class="form-check-label" for="group">Subject</label>
                                                    <input type="text" name="subject" class="form-control" id="subject" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-check-label" for="detail">Detail Issue</label>
                                                    <textarea type="text" name="detail" class="form-control" id="detail" required></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="files">File:</label>
                                                    <input type="file" name="files" id="files" class="form-control">
                                                </div>
                                                <!-- <div class="input-field">
                                                    <label class="active">Upload Photos<small>(max size: 1mb)</small></label>
                                                    <div class="input-images" style="padding-top: .5rem;"></div>
                                                </div> -->
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" id="btn-submit-form" class="btn btn-primary">Add Ticket</button>
                                            </div>
                                                            
                                        <!-- <div class="form-row">
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-primary btn-md float-left" onclick="window.location='{{ url()->previous() }}'">< back</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" id="btn-submit-form" class="btn btn-primary btn-md float-right">save</button>
                                            </div>
                                        </div> -->
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
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('select2/select2.min.js') }}"></script>
<script src="{{ asset('js/misc.js') }}"></script>

<script>
    $('#btn-submit-form').on('click', function() {
        $('#form').submit();
        $(this).attr('disabled', true);
        $(this).text("Loading ...");
    });

    document.getElementById("formData").addEventListener("click", function(event){
        event.preventDefault()
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
