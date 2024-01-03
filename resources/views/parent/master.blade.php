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
    @yield('extend-css')
    <link href="{{asset('css/adminlte.css')}}" rel="stylesheet">
    <link href="{{asset('dist/css/adminlte.min.css')}}" rel="stylesheet">
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

        /* Important part */
        .modal-dialog{
            overflow-y: initial !important;
            zoom: 85%;
        }
        .modal-backdrop{
            zoom: 150%  !important;
        }
        .modal-body{
            height: 85vh;
            overflow-y: auto;
        }
    </style>
</head>
<body  class="hold-transition sidebar-mini">
    <div class="wrapper">
        <section id="loading">
            <div id="loading-content"></div>
        </section>
  
        @include('auth.header')
        @include('auth.menu')
        @yield('body')
        @include('parent.footer')
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- <script type="text/javascript"> 
        /*------------------------------------------
        --------------------------------------------
        Add Loading When fire Ajax Request
        --------------------------------------------
        --------------------------------------------*/
        $(document).ajaxStart(function() {
            $('#loading').addClass('loading');
            $('#loading-content').addClass('loading-content');
            $('#modal-add-ticket').removeClass('modal-add-ticket');
            $('#modal-update-ticket').removeClass('modal-update-ticket');
            $('#save-btn').removeClass('save-btn');
        });
    
        /*------------------------------------------
        --------------------------------------------
        Remove Loading When fire Ajax Request
        --------------------------------------------
        --------------------------------------------*/
        $(document).ajaxStop(function() {
            $('#loading').removeClass('loading');
            $('#loading-content').removeClass('loading-content');
            $('#modal-add-ticket').removeClass('modal-add-ticket');
            $('#modal-update-ticket').removeClass('modal-update-ticket');
            $('#save-btn').removeClass('save-btn');

        });
    </script> -->
    @yield('extend-js')
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- Chech Idle Script -->
    <script type="text/javascript">
        var idleTime = 0;
        $(document).ready(function() {
            //Increment the idle time counter every minute.
            var idleInterval = setInterval(timerIncrement, 60000); // 1 minute
            //Zero the idle timer on mouse movement.
            $(this).mousemove(function(e) {
                idleTime = 0;
                isIdle = false;
            });
            $(this).keypress(function(e) {
                idleTime = 0;
                isIdle = false;
            });
        });

        var isIdle = false;

        function timerIncrement($userid) {
            idleTime = idleTime + 1;
            var userid = {{ Session::get('userid') }};
            // console.log("This is javascript session" + userid);
            if( userid != '111111' || userid != '000000'){
                if (idleTime > 5 && !isIdle) { // 15 minutes
                    isIdle = true;
                    // alert("Session timeout");
                    var url = "{{ route('login') }}";
                    $.ajax({
                        type: "POST",
                        url:  "{{ route('logout') }}",
                        data: { 
                            '_token': "{{csrf_token()}}",
                        },
                        success: function(response) {
                            document.location.href=url;
                        }
                    });
                }
            }
        }
    </script>
</body>
</html>