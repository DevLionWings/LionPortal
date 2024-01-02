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
                <!-- <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div> -->
                <!-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item" type=date><a href="#"></a></li>
                    </ol>
                </div> -->
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
                            <h6>System :</h6>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="input-group value">
                                <select id="systemfilter" name="systemfilter" class="form-control input--style-6">
                                    <option value="%"> all</option>
                                    @foreach($sys as $syscode)
                                        <option value="{{ $syscode['ID'] }}">{{ $syscode['NAME'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-1">
                            <button id="button" name="button" class="button bbtn-submit btn btn-secondary" ><i class="fas fa-search"></i></button>
                        </div>
                        <div class="col-sm-12">
                            <h6>Count Status Ticket</h6>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box" id="today-progress">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tasks"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">In Progress</span>
                                <span class="info-box-number" name="today-progress">
                                    0
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times"></i></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Closed</span>
                                <span class="info-box-number" name="today-out">
                                    0
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Waiting For User</span>
                                <span class="info-box-number" name="today-foruser">
                                    0
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-truck-moving"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Transport Process</span>
                                <span class="info-box-number" name="today-transport">
                                    0
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-default elevation-1"><i class="fas fa-sync-alt"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Not Started</span>
                                <span class="info-box-number" name="today-notstarted">
                                    0
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-check"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Waiting Approval</span>
                                <span class="info-box-number" name="today-approval">
                                    0
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-basic elevation-1"><i class="fas fa-minus-circle"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Hold</span>
                                <span class="info-box-number" name="today-hold">
                                    0
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-laptop-code"></i></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Monitoring</span>
                                <span class="info-box-number" name="today-monitoring">
                                    0
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>
                
                                <div class="info-box-content">
                                <span class="info-box-text">Waiting Purchase</span>
                                <span class="info-box-number" name="today-purchase">
                                    0
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-4">
                            <!-- Default box -->
                            
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">All Time</h3>
                                        <a href="javascript:void(0);"></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <label name="loading-chart">Loading...</label>
                                    <canvas id="alltime" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;display: none;"></canvas>
                                </div>
                            
                        </div>
                        <div class="col-4">
                            <!-- Default box -->
                            
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Monthly</h3>
                                        <a href="javascript:void(0);"></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <label name="loading-chart">Loading...</label>
                                    <canvas id="monthly" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;display: none;"></canvas>
                                </div>
                            
                        </div>
                        <div class="col-4">
                            <!-- Default box -->
                            
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Today</h3>
                                        <a href="javascript:void(0);"></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <label name="loading-chart">Loading...</label>
                                    <canvas id="today" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;display: none;"></canvas>
                                </div>
                            
                        </div>
                    </div>
                </div>
                <!-- <div class="col-6"> -->
                    <!-- Default box -->
                    <!-- <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Tickets for a Years</h3>
                                <a href="javascript:void(0);"></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <label name="loading-year">Loading...</label>
                            <canvas id="ticketyear" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;display: none;"></canvas>
                        </div>
                    </div> -->
                    <!-- /.card -->
                <!-- </div> -->
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
            // console.log(response);
            $('label[name="loading-chart"]').css('display', 'none')
            $('canvas[id="ticket"]').css('display', 'block')
        }, error: function(err) {
            console.log(err)
            alert('Opps, something wrong with dashboard chart');
        }
    })

    $.ajax({
        type: "GET",
        url: "/get/stattoday",
        data: {
            'systemid' : '%'
        },
        success: function(response) {
            // console.log(response);
            $('span[name="today-out"]').text(response['clsd'])
            $('span[name="today-progress"]').text(response['prg'])
            $('span[name="today-foruser"]').text(response['usr'])
            $('span[name="today-transport"]').text(response['trans'])
            $('span[name="today-notstarted"]').text(response['notstr'])
            $('span[name="today-approval"]').text(response['apprv'])
            $('span[name="today-hold"]').text(response['hold'])
            $('span[name="today-purchase"]').text(response['purchs'])
            $('span[name="today-monitoring"]').text(response['mntr'])
        }, error: function(err) {
            // console.log(err)
            alert('Opps, something wrong with dashboard chart');
        }
    })

    // Ajax get chart now
    function getShowCount(systemid) {
        $.ajax({
            type: "GET",
            url: "/get/stattoday",
            data: {
                'systemid' : systemid
            },
            success: function(response) {
                // console.log(response);
                $('span[name="today-out"]').text(response['clsd'])
                $('span[name="today-progress"]').text(response['prg'])
                $('span[name="today-foruser"]').text(response['usr'])
                $('span[name="today-transport"]').text(response['trans'])
                $('span[name="today-notstarted"]').text(response['notstr'])
                $('span[name="today-approval"]').text(response['apprv'])
                $('span[name="today-hold"]').text(response['hold'])
                $('span[name="today-purchase"]').text(response['purchs'])
                $('span[name="today-monitoring"]').text(response['mntr'])
            }, error: function(err) {
                // console.log(err)
                alert('Opps, something wrong with dashboard chart');
            }
        })
    }


    $.ajax({
        url: '{{ route("get.all") }}',
        type: 'GET',
        success: function(response) {
            // console.log(response);
            $('label[name="loading-chart"]').css('display', 'none')
            $('canvas[id="alltime"]').css('display', 'block')
            ticketingChartAll(response["prg"], response["clsd"], response["usr"], response["trans"], response["notstr"], response["apprv"], response["hold"], response["purchs"], response["mntr"])
        }, error: function(err) {
            // console.log(err)
            alert('Opps, something wrong with dashboard chart');
        }
    })
    
    $.ajax({
        url: '{{ route("get.month") }}',
        type: 'GET',
        success: function(response) {
            // console.log(response);
            $('label[name="loading-month"]').css('display', 'none')
            $('canvas[id="monthly"]').css('display', 'block')
            ticketingChartMonth(response["prg"], response["clsd"], response["usr"], response["trans"], response["notstr"], response["apprv"], response["hold"], response["purchs"], response["mntr"])
        }, error: function(err) {
            // console.log(err)
            alert('Opps, something wrong with dashboard chart');
        }
    })

    $.ajax({
        url: '{{ route("get.today") }}',
        type: 'GET',
        success: function(response) {
            // console.log(response);
            $('label[name="loading-today"]').css('display', 'none')
            $('canvas[id="today"]').css('display', 'block')
            ticketingChartToday(response["prg"], response["clsd"], response["usr"], response["trans"], response["notstr"], response["apprv"], response["hold"], response["purchs"], response["mntr"])
        }, error: function(err) {
            // console.log(err)
            alert('Opps, something wrong with dashboard chart');
        }
    }) 

    $(document).on('click', '.button', function() {
        var systemid = $('select[name="systemfilter"]  option:selected').val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "/get/stattoday",
            data: {
                'systemid' : systemid
            },
            success: function(response) {
                // console.log(response)
                getShowCount(systemid);
            },
            error: function (error) {
                console.error(error);
            },
        })
    })

    // $.ajax({
    //     url: '{{ route("get.year") }}',
    //     type: 'GET',
    //     success: function(response) {
    //         $('label[name="loading-year"]').css('display', 'none')
    //         $('canvas[id="ticketyear"]').css('display', 'block')
    //         ticketingYearChart(response["date"], response["opn"], response["clsd"], response["prg"])
    //     }, error: function(err) {
    //         alert('Opps, something wrong with dashboard chart');
    //     }
    // })

    function ticketingChartAll(dataTicketProgress, dataTicketClosed, dataTicketForuser, dataTicketTransport, dataTicketNotstarted, dataTicketApproval, dataTicketHold, dataTicketPurchase, dataTicketMonitoring) {
        // Chart
        const DATA_COUNT = 365;
        const datapoints = dataTicketProgress;
        const datapoints1 = dataTicketClosed;
        const datapoints2 = dataTicketForuser;
        const datapoints3 = dataTicketTransport;
        const datapoints4 = dataTicketNotstarted;
        const datapoints5 = dataTicketApproval;
        const datapoints6 = dataTicketHold;
        const datapoints7 = dataTicketPurchase;
        const datapoints8 = dataTicketMonitoring;
    
        var data = {
            labels: ['Progress', 'Closed', 'Waiting For User', 'Transport Process', 'Not Started', 'Waiting Approval', 'Hold', 'Waiting Purchase', 'Monitoring'],
            datasets: [
                {
                label: 'Count',
                data: [datapoints, datapoints1, datapoints2, datapoints3, datapoints4, datapoints5, datapoints6, datapoints7, datapoints8],
                backgroundColor: ["#28a745", "#dc3545", "#17a2b8", "#343a40", "#f8f9fa", "#20c997", "#fd7e14", "#007bff", "#ffc107"]
                }
            ]
        }

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#alltime').get(0).getContext('2d')
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

    function ticketingChartMonth(dataTicketProgress, dataTicketClosed, dataTicketForuser, dataTicketTransport, dataTicketNotstarted, dataTicketApproval, dataTicketHold, dataTicketPurchase, dataTicketMonitoring) {
        // Chart
        const DATA_COUNT = 365;
        const datapoints = dataTicketProgress;
        const datapoints1 = dataTicketClosed;
        const datapoints2 = dataTicketForuser;
        const datapoints3 = dataTicketTransport;
        const datapoints4 = dataTicketNotstarted;
        const datapoints5 = dataTicketApproval;
        const datapoints6 = dataTicketHold;
        const datapoints7 = dataTicketPurchase;
        const datapoints8 = dataTicketMonitoring;
    
        var data = {
            labels: ['Progress', 'Closed', 'Waiting For User', 'Transport Process', 'Not Started', 'Waiting Approval', 'Hold', 'Waiting Purchase', 'Monitoring'],
            datasets: [
                {
                label: 'Count',
                data: [datapoints, datapoints1, datapoints2, datapoints3, datapoints4, datapoints5, datapoints6, datapoints7, datapoints8],
                backgroundColor: ["#28a745", "#dc3545", "#17a2b8", "#343a40", "#f8f9fa", "#20c997", "#fd7e14", "#007bff", "#ffc107"]
                }
            ]
        }

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#monthly').get(0).getContext('2d')
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

    function ticketingChartToday(dataTicketProgress, dataTicketClosed, dataTicketForuser, dataTicketTransport, dataTicketNotstarted, dataTicketApproval, dataTicketHold, dataTicketPurchase, dataTicketMonitoring) {
        // Chart
        const DATA_COUNT = 365;
        const datapoints = dataTicketProgress;
        const datapoints1 = dataTicketClosed;
        const datapoints2 = dataTicketForuser;
        const datapoints3 = dataTicketTransport;
        const datapoints4 = dataTicketNotstarted;
        const datapoints5 = dataTicketApproval;
        const datapoints6 = dataTicketHold;
        const datapoints7 = dataTicketPurchase;
        const datapoints8 = dataTicketMonitoring;
    
        var data = {
            labels: ['Progress', 'Closed', 'Waiting For User', 'Transport Process', 'Not Started', 'Waiting Approval', 'Hold', 'Waiting Purchase', 'Monitoring'],
            datasets: [
                {
                label: 'Count',
                data: [datapoints, datapoints1, datapoints2, datapoints3, datapoints4, datapoints5, datapoints6, datapoints7, datapoints8],
                backgroundColor: ["#28a745", "#dc3545", "#17a2b8", "#343a40", "#f8f9fa", "#20c997", "#fd7e14", "#007bff", "#ffc107"]
                }
            ]
        }

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#today').get(0).getContext('2d')
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

    function ticketingYearChart(forLabel, dataTicketOpen, dataTicketClosed, dataTicketProgress) {
        const DATA_COUNT = 4;
        const labels = forLabel;
        const datapoints = dataTicketOpen;
        const datapoints1 = dataTicketClosed;
        const datapoints2 = dataTicketProgress;
    
        var data = {
            labels: labels,
            datasets: [
                {
                    data: [datapoints, datapoints1, datapoints2],
                    label: 'Data Year',
                    borderColor: ["#28a745", "#dc3545", "#17a2b8",],
                    backgroundColor: ["#28a745", "#dc3545", "#17a2b8",]
                }
            ]
        }

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var barChartCanvas = $('#ticketyear').get(0).getContext('2d')
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(barChartCanvas, {
            type: 'bar',
            data: data,
            options: {
                indexAxis: 'y',
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each horizontal bar to be 2px wide
                elements: {
                bar: {
                    borderWidth: 2,
                }
                },
                responsive: true,
                plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Chart.js Horizontal Bar Chart'
                }
                }
            },    
        })
    }
</script>
@endsection
