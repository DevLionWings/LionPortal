@extends('parent.master')
@section('extend-css')

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
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item" type=date><a href="#"></a></li>
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
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h6>Tickets for a Week</h6>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-sign-in-alt"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Ticket Open</span>
                                <span class="info-box-number" name="today-in">
                                    10
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-sign-out-alt"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Ticket Closed</span>
                                <span class="info-box-number" name="today-out">
                                    10
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-invoice"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Ticket Progress</span>
                                <span class="info-box-number" name="today-progress">
                                    10
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                </div>
                <div class="col-12">
                        <h6>Statistics</h6>
                </div>
                <div class="col-6">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Tickets for a Week</h3>
                                <a href="javascript:void(0);"></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <label name="loading-chart">Loading...</label>
                            <canvas id="ticket" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;display: none;"></canvas>
                        </div>
                    </div>
                    <!-- /.card -->
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
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

<script>
    $('.nav-link.active').removeClass('active');
    $('#m-dashboard').addClass('active');
</script>
<script type="text/javascript">

   // Ajax get data ticketing
   $.ajax({
        url: '{{ route("get.stat") }}',
        type: 'GET',
        success: function(response) {
            $('label[name="loading-chart"]').css('display', 'none')
            $('canvas[id="ticket"]').css('display', 'block')
            ticketingChart(response["opn"], response["clsd"], response["prg"])
        }, error: function(err) {
            console.log(err)
            alert('Opps, something wrong with dashboard chart');
        }
    })
    // Ajax get chart now
    $.ajax({
        url: '{{ route("get.stat") }}',
        type: 'GET',
        success: function(response) {
            $('span[name="today-in"]').text(response['opn'])
            $('span[name="today-out"]').text(response['clsd'])
            $('span[name="today-progress"]').text(response['prg'])
        }, error: function(err) {
            console.log(err)
            alert('Opps, something wrong with dashboard chart');
        }
    })

    function ticketingChart(dataTicketOpen, dataTicketClosed, dataTicketProgress) {
        // Chart
        const DATA_COUNT = 4;
        const datapoints = dataTicketOpen;
        const datapoints1 = dataTicketClosed;
        const datapoints2 = dataTicketProgress;
    
        var data = {
            labels: ['Ticket Open', 'Ticket Closed', 'Ticket Progress'],
            datasets: [
                {
                label: 'Count',
                data: [datapoints, datapoints1, datapoints2],
                backgroundColor: ["#28a745", "#dc3545", "#17a2b8",]
                }
            ]
        }

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#ticket').get(0).getContext('2d')
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                        legend: {
                        position: 'top',
                    },
                    title: {
                    display: true,
                    text: 'Chart.js Pie Chart'
                    }
                }
            },
        })
    }
</script>
@endsection
