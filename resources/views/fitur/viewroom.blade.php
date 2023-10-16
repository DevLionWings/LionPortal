<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <meta http-equiv="Content-Security-Policy" content="script-src 'self' http://10.80.80.23:443/ 'unsafe-inline' 'unsafe-eval';"> -->
    <title>Lion-Portal</title>
    <link href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/dropzone/min/dropzone.min.css')}}" rel="stylesheet">
    <!-- add icon link -->
    <link rel="icon" href = "{{asset('images/iconlion.png')}}" type="image/x-icon">
    <link href="{{asset('css/adminlte.css')}}" rel="stylesheet">
    <link href="{{asset('dist/css/adminlte.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .main-sidebar > .brand-link {
            background-color: white;
            color: black;
        }
        .sidebar-dark-primary {
            background-color: #FFFFFF;
        }
        [class*=sidebar-dark-] .sidebar a {
            color: #3ea555;
        }
        [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link {
            color: #3ea555;
        }

        [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active {
            background-color: #3ea555;
            color: #FFFFFF;
        }

        [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:focus, [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:hover {
            background-color: #3ea555;
            color: #FFFFFF;
        }
        
        [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link:hover {
            color: #FFFFFF;
            background-color: #3ea555;
        }
        [class*=sidebar-dark-] .nav-sidebar>.nav-item.menu-open>.nav-link, [class*=sidebar-dark-] .nav-sidebar>.nav-item:hover>.nav-link, [class*=sidebar-dark-] .nav-sidebar>.nav-item>.nav-link:focus {
            background-color: rgba(255,255,255,.1);
            color: #3ea555;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active, .nav-treeview>.nav-item>.nav-link.active {
            background-color: #3ea555;
            color: #FFFFFF;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link:hover, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link:hover, .nav-treeview>.nav-item>.nav-link:hover {
            background-color: #3ea555;
            color: #FFFFFF;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link:focus, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link:focus, .nav-treeview>.nav-item>.nav-link:focus {
            background-color: rgba(15, 104, 168, 0.192);
            color: #3ea555;
        }
        
        [class*=sidebar-dark] .user-panel {
            border-bottom: 1px solid #e5e5e5;
        }
        [class*=sidebar-dark] .brand-link {
            border-bottom: 1px solid #e5e5e5;
        }
        [class*=sidebar-dark-] .sidebar a:hover {
            color: #0F68A8;
        }
        .main-sidebar > .brand-link:hover {
            color: #0F68A8;
        }
        a.edit {
            margin-right: 5px;
        }
        .main-footer {
            border-left: 1px solid #dee2e6;
        }
        .btn-primary {
            color: #fff;
            background-color: #0F68A8;
            border-color: #0F68A8;
            box-shadow: none;
        }
        .loading {
            z-index: 20;
            position: absolute;
            top: 0;
            left:-5px;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .loading-content {
            position: absolute;
            border: 16px solid #f3f3f3;
            border-top: 16px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            top: 40%;
            left:50%;
            animation: spin 2s linear infinite;
            }
              
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body  class="hold-transition sidebar-mini">
    <div class="wrapper">
        <section id="loading">
            <div id="loading-content"></div>
        </section>
        <section class="content">
            <div>
                <h2 style="text-align:center">Meeting Room List</h2>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-body">
                                <table id="room_list" class="table table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <b><th style="font-size:20px;">Room</th></b>
                                            <b><th style="font-size:20px;">Status</th></b>
                                            <b><th style="font-size:20px;">Subject</th></b>
                                            <b><th style="font-size:20px;">Booked By</th></b>
                                            <b><th style="font-size:20px;">Date</th></b>
                                            <!-- <b><th style="font-size:20px;">End Date</th></b> -->
                                            <b><th style="font-size:20px;">Start Time</th></b>
                                            <b><th style="font-size:20px;">End Time</th></b>
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
</body>
</html>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>

<script type="text/javascript">
    $('.nav-link.active').removeClass('active');
    $('#m-viewroom').addClass('active');
    $('#m-viewroom').parent().parent().parent().addClass('menu-is-opening menu-open');
</script>
<script type="text/javascript">
    $(function () {    
        var table = $('#room_list').DataTable({
            scrollX: false,
            processing: true,
            serverSide: true,
            responsive: false,
            searching: false,
            lengthChange: false,
            paging: false,
            info: false, 
            ajax: "{{ route('list-room') }}",
            order: [[ 3, "desc" ]],
            columns: [
                {
                    data: 'roomname',
                    render: function(data){
                        statusText = ` <i class="nav-icon fas fa-door-open"></i>`;
                        return statusText + ' ' + data;
                    }
                },
                {
                    data: 'statusroom',
                    render: function(data){
                        if(data == '0'){
                            statusText = `<span class="badge badge-success">Available</span>`;
                        } else if(data == '1'){
                            statusText = `<span class="badge badge-danger">Booked</span>`;
                        } else {
                            statusText = `<span class="badge badge-info">Canceled</span>`;
                        }
                        return statusText;

                    }
                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'username',
                    render: function(data){
                        statusText = `<i class="fas fa-user-clock"></i>`;
                        return statusText + ' ' + data;
                    }
                },
                {
                    data: 'startdate',
                    render: function(data){
                        var today = new Date(data);
                        var day = today.getDate() + "";
                        var month = (today.getMonth() + 1) + "";
                        var year = today.getFullYear() + "";
                        var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
                        var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
                        var seconds = today.getSeconds() + "";
                        if (day < 10) {
                            day = '0' + day;
                        }

                        if (month < 10) {
                            month = `0${month}`;
                        }
                        year = year;
                        hour = hour;
                        minutes = minutes;
                        seconds = seconds;
                        // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
                        var date = day + "-" + month + "-" + year;
                        statusText = `<i class="far fa-calendar-alt"></i>`;
                        return statusText + ' ' + data;  
                    }
                },
                // {
                //     data: 'enddate',
                //     render: function(data){
                //         var today = new Date(data);
                //         var day = today.getDate() + "";
                //         var month = (today.getMonth() + 1) + "";
                //         var year = today.getFullYear() + "";
                //         var hour = (today.getHours() < 10 ? '0' : '') + today.getHours();
                //         var minutes = (today.getMinutes() < 10 ? '0' : '' ) + today.getMinutes();
                //         var seconds = today.getSeconds() + "";
                //         if (day < 10) {
                //             day = '0' + day;
                //         }

                //         if (month < 10) {
                //             month = `0${month}`;
                //         }
                //         year = year;
                //         hour = hour;
                //         minutes = minutes;
                //         seconds = seconds;
                //         // console.log(day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds);
                //         var date = day + "-" + month + "-" + year;
                //         statusText = `<i class="far fa-calendar-alt"></i>`;
                //         return statusText + ' ' + date;  
                //     }
                // },
                {
                    data: 'starttime',
                    render: function(data){
                        statusText = `<i class="fas fa-clock"></i>`;
                        return statusText + ' ' + data;
                    }
                },
                {
                    data: 'endtime',
                    render: function(data){
                        statusText = `<i class="fas fa-clock"></i>`;
                        return statusText + ' ' + data;
                    }
                },
            ],
            rowCallback: function(row, data, index){
                // console.log(data['starttime']);
                var today = new Date();
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
                var datetime = hour + ":" + minutes + ":" + seconds;
                var datenow =  year + "-" + month + "-" + day;
                // console.log(today);

                if(data['enddate'] < datenow && data['endtime'] < datetime){
                    $(row).find('td:eq(1)').html( '<span class="badge badge-dark">Check out</span>' );
                } else if (data['startdate'] < datenow && data['starttime'] < datetime) {
                    $(row).find('td:eq(1)').html( '<span class="badge badge-success">Check in</span>' );
                }   
            },
        });

    });
</script>
<script>
setTimeout(function(){
   window.location.reload(1);
}, 300000);
</script>

