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
                    <h5>Print Kwitansi Cuti</h5>
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
                            <form action="{{ route('print-cuti') }}" id="form" name="form" method="get" >
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="name">Type Kwitansi :</div>
                                                    <div class="input-group value">
                                                        <select id="opsi" name="opsi" class="form-control input--style-6" onchange="myFunction(event)">
                                                            <option value=""> Masukkan Pilihan :</option>
                                                            <option value="3">Cuti Hamil</option>
                                                            <option value="1.5">Cuti Keguguran</option> 
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" id="label">Lama Cuti:</label>
                                                    <input type="text" name="lamacuti" id="lamacuti" class="form-control" readonly> 
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="idkaryawan">ID Karyawan:</label>
                                                    <input type="text" name="idkaryawan" class="form-control" id="idkaryawan" placeholder="wajib di isi" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="tglcuti">Tanggal Cuti:</label>
                                                    <input type="date" name="tglcuti" id="tglcuti" class="form-control" required> 
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="tglmasuk">Tanggal Masuk:</label>
                                                    <input type="date" name="tglmasuk" id="tglmasuk" class="form-control" required> 
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="chh">Jumlah CHH(Cuti Haid Hadir):</label>
                                                    <input type="text" name="chh" class="form-control" id="chh" placeholder="wajib di isi"  required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" >Uang Makan:</label>
                                                    <input type="text" name="um" id="um" class="form-control" placeholder="di isi/ubah jika perlu">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" >SPSI:</label>
                                                    <input type="text" name="spsi" id="spsi" class="form-control" placeholder="di isi/ubah jika perlu">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" >Koperasi:</label>
                                                    <input type="text" name="koperasi" id="koperasi" class="form-control" placeholder="di isi/ubah jika perlu">
                                                </div>
                                                <div class="mb-3">
                                                    <input type="checkbox" id="rapel" name="rapel" value="on">
                                                    <label for="rapel">Rapel</label><br>
                                                </div>
                                                <div class="mb-3" id="hide1">
                                                    <label class="form-check-label" for="amountrapel">Total Nominal Kwitansi Lama:</label>
                                                    <input type="text" name="amountrapel" id="amountrapel" class="form-control">
                                                </div>
                                                <div class="mb-3" id="hide2">
                                                    <label class="form-check-label" for="totalamount">Jumlah Bulan:</label>
                                                    <input type="text" name="totalamount" id="totalamount" class="form-control">
                                                </div>
                                                <div class="mb-3" id="hide3">
                                                    <label class="form-check-label" for="selisih">Selisih:</label>
                                                    <input type="text" name="selisih" id="selisih" class="form-control" readonly>
                                                </div>
                                                <div class="mb-6">
                                                    <button type="button" class="btn btn-danger btn-md float-left" onclick="window.location='{{ url()->previous() }}'">Back</button>
                                                </div>
                                                <div class="mb-6">
                                                    <button type="button" id="check" class="check btn btn-primary btn-md float-right">Check</button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="detail">Nama Karyawan:</label>
                                                    <input type="text" name="nama" id="nama" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="detail">Bagian Karyawan:</label>
                                                    <input type="text" name="bagian" id="bagian" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" >Gaji:</label>
                                                    <input type="text" name="gaji" id="gaji" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" >Jabatan:</label>
                                                    <input type="text" name="jabatan" id="jabatan" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" >Jamsostek:</label>
                                                    <input type="text" name="jstk" id="jstk" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="to">Untuk:</label>
                                                    <input type="text" name="to" id="to" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="keterangan">Keterangan:</label>
                                                    <textarea type="text" name="keterangan" class="form-control" id="keterangan" readonly></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="total">Total:</label>
                                                    <input type="text" name="total" id="total" class="form-control" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-check-label" >Terbilang:</label>
                                                    <textarea type="text" name="terbilang" id="terbilang" class="form-control" readonly></textarea>
                                                </div>
                                                <div class="mb-6">
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
    $('#m-kwitansicuti').addClass('active');
    $('#m-kwitansicuti').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script>
    $(document).on('click', '.check', function() {
        var type = $('select[name="opsi"] option:selected').val();
        var id = $('input[name="idkaryawan"]').val();
        var tglcuti = $('input[name="tglcuti"]').val();
        var tglmasuk = $('input[name="tglmasuk"]').val();
        var lamacuti = $('input[name="lamacuti"]').val();
        var jmlhchh = $('input[name="chh"]').val();
        var bpjs = $('input[name="bpjs"]').val();
        var uangmakan = $('input[name="um"]').val();
        var spsi = $('input[name="spsi"]').val();
        var koperasi = $('input[name="koperasi"]').val();
        var amountrapel = $('input[name="amountrapel"]').val();
        var totalamount = $('input[name="totalamount"]').val();
        var selisih = $('input[name="selisih"]').val();
        var rapel = $("input[type='checkbox']").val();
        // console.log(rapel);

        if(id.length < 1) {
            alert('id harus di isi');
            return;
        } else if (tglcuti.length < 1){
            alert('Tanggal Cuti harus di isi');
            return;
        } else if (tglmasuk.length < 1){
            alert('Tanggal Masuk harus di isi');
            return;
        } else if (lamacuti.lengtg < 1){
            alert('Lama Cuti harus di isi');
            return;
        } else if (jmlhchh.length < 1){
            alert('Jumlah Cuti Haid harus di isi');
            return;
        } else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "/simulasi/cuti",
                data: {
                    'type' : type,
                    'idkaryawan' : id, 
                    'tglcuti' : tglcuti,
                    'tglmasuk' : tglmasuk,
                    'lamacuti' : lamacuti,
                    'jmlhchh' : jmlhchh,
                    'bpjs' : bpjs,
                    'uangmakan' : uangmakan,
                    'spsi' : spsi,
                    'koperasi' : koperasi,
                    'amountrapel' : amountrapel,
                    'totalamount' : totalamount,
                    'selisih' : selisih,
                    'rapel' : rapel
                },
                success: function(response) {
                    $('#nama').val(response[0]['NAME']);
                    $('#bagian').val(response[0]['BAGIAN']);
                    $('#gaji').val(response[0]['GAJI']);
                    $('#jabatan').val(response[0]['JABATAN']);
                    $('#um').val(response[0]['UANGMAKAN']);
                    $('#jstk').val(response[0]['JAMSOSTEK']);
                    $('#spsi').val(response[0]['SPSI']);
                    $('#koperasi').val(response[0]['KOPERASI']);
                    $('#to').val(response[0]['UNTUK']);
                    $('#keterangan').val(response[0]['KETERANGAN']);
                    $('#total').val(response[0]['TOTAL']);
                    $('#terbilang').val(response[0]['TERBILANG']);
                    $('#selisih').val(response[0]['SELISIH']);
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
    // $("input[type=date]").datepicker({
    // dateFormat: 'yy-mm-dd',
    // onSelect: function(dateText, inst) {
    //     $(inst).val(dateText); // Write the value in the input
    // }
    // });

    // Code below to avoid the classic date-picker
    // $("input[type=date]").on('click', function() {
    // return false;
    // });

    /* Dengan Rupiah */
    var dengan_rupiah = document.getElementById('amountrapel');
    dengan_rupiah.addEventListener('keyup', function(e)
    {
        dengan_rupiah.value = formatRupiah(this.value);
    });
    
    /* Fungsi */
    function formatRupiah(angka, prefix)
    {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split    = number_string.split(','),
            sisa     = split[0].length % 3,
            rupiah     = split[0].substr(0, sisa),
            ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>
<script type="text/javascript">
    function myFunction(e) {
        // console.log(e.target.value);
        document.getElementById("lamacuti").value = e.target.value
    }
</script>
<script>
    $(function() {
        var checkbox = $("#rapel");
        var hide1 = $("#hide1");
        var hide2 = $("#hide2");
        var hide3 = $("#hide3");

        hide1.hide();
        hide2.hide();
        hide3.hide();

        checkbox.change(function() {
            if (checkbox.is(':checked')) {
                hide1.show();
                hide2.show();
                hide3.show();
            } else {
                hide1.hide();
                hide2.hide();
                hide3.hide();
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
