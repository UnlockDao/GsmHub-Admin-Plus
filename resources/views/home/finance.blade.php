@extends('layouts.header')
@section('dashboard')
    @if (!CUtil::checkauth())
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">IMEI Service Status</h5>
                                        <span class="h2 font-weight-bold mb-0"><a
                                                    href="https://s-unlock.com/admin/order-history/service-wise"
                                                    target="_blank">New: {{current($pendingoder['ImeiServiceOrder'])}}</a> | <a
                                                    href="https://s-unlock.com/admin/order-history/reply-accepted-order?type=quick"
                                                    target="_blank">Accepted: {{next($pendingoder['ImeiServiceOrder'])}}</a></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm" style="font-weight: bold;">

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Server Service Status</h5>
                                        <span class="h2 font-weight-bold mb-0"><a
                                                    href="https://s-unlock.com/admin/server-order-history/service-wise"
                                                    target="_blank">New: {{current($pendingoder['ServerServiceOrder'])}}</a> | <a
                                                    href="https://s-unlock.com/admin/server-order-history/reply-accepted-order?type=quick"
                                                    target="_blank">Accepted: {{next($pendingoder['ServerServiceOrder'])}}</a></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                            <i class="ni ni-money-coins"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm" style="font-weight: bold;">
                                    <span class="float-right text-success"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "preLoadimei",
                type: "get",
                success: function (data) {
                    $("#loaderimei").removeAttr('pk').hide();
                },
                error: function (x, e) {

                }
            });
            $.ajax({
                url: "preLoadservice",
                type: "get",
                success: function (data) {
                    $("#loaderservice").removeAttr('pk').hide();
                },
                error: function (x, e) {

                }
            });
        }, false);
    </script>
    <style>
        .table .thead-light th {
            min-width: 100px;
        }

        .highcharts-background {
            display: none;
        }

        #table-scroll {
            height: 500px;
            overflow: auto;
        }

        ::-webkit-scrollbar {
            width: 0px;
        }
    </style>
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard
                        <div class="spinner-grow text-success" role="status" id="loaderimei"></div>
                        <div class="spinner-grow text-primary" role="status" id="loaderservice"></div>
                    </h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="Campaign Sent">IMEI
                                    Service Profit</h5>
                                <h3 class="my-2 py-1">{{round($imeioder->profit)}} USD</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success mr-2"> {{number_format($imeioder->profit*22000)}} VND</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <div class="text-right">
                                    <div id="campaign-sent-chart"></div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="New Leads">Server
                                    Service Profit</h5>
                                <h3 class="my-2 py-1">{{round($serveroder->profit)}} USD</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-danger mr-2"> {{number_format($serveroder->profit*22000)}} VND</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <div class="text-right">
                                    <div id="new-leads-chart"></div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="Deals">Total
                                    Profit</h5>
                                <h3 class="my-2 py-1">{{round($serveroder->profit+$imeioder->profit)}}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success mr-2">{{number_format(($serveroder->profit+$imeioder->profit)*22000)}} VND </span>
                                </p>
                            </div>
                            <div class="col-6">
                                <div class="text-right">
                                    <div id="deals-chart"></div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="Booked Revenue">
                                    Balance Paypal</h5>
                                <h3 class="my-2 py-1">{{$balance_payment['L_AMT0']}}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success mr-2"> {{number_format(($balance_payment['L_AMT0'])*22000)}}</span>
                                </p>
                            </div>
                            <div class="col-6">
                                <div class="text-right">
                                    <div id="booked-revenue-chart"></div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div>
        <!-- end row -->


        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3">Chart</h4>
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card-body">
                            <div id="profitchart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12" style="padding-top: 10px">
                        <div class="card-body">
                            <div id="revenuechart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12" style="padding-top: 10px">
                        <div class="card-body">
                            <div id="invoice" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- end card-body-->
        </div>
        <!-- end card-->
        <!-- end row-->
    </div> <!-- container -->


    <link rel="stylesheet" type="text/css" href="{{ asset('js/highcharts_date_range_grouping.css') }}">
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts_date_range_grouping.min.js') }}"></script>
    <script>
        Highcharts.chart('profitchart', {
            chart: {
                type: 'column'
            }, dateRangeGrouping: {
                dayFormat: {month: 'numeric', day: 'numeric', year: 'numeric'},
                weekFormat: {month: 'numeric', day: 'numeric', year: 'numeric'},
                monthFormat: {month: 'numeric', year: 'numeric'}
            },
            title: {
                text: 'Profit'
            },
            xAxis: {
                categories:  {!! $profitchart['date'] !!}
            },
            yAxis: {
                title: {
                    text: ''
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            colors: [
                '#5e72e4',
                '#2dce89',
            ],
            plotOptions: {
                column: {
                    stacking: 'normal',
                    colorByPoint: false,
                }
            },
            series: [{
                name: 'IMEI',
                data:  {{$profitchart['imei']}}
            }, {
                name: 'Server',
                data: {{$profitchart['server']}}
            }]
        });
        Highcharts.chart('revenuechart', {
            chart: {
                type: 'column'
            }, dateRangeGrouping: {
                dayFormat: {month: 'numeric', day: 'numeric', year: 'numeric'},
                weekFormat: {month: 'numeric', day: 'numeric', year: 'numeric'},
                monthFormat: {month: 'numeric', year: 'numeric'}
            },
            title: {
                text: 'Revenue'
            },
            xAxis: {
                categories:  {!! $revenuechart['date'] !!}
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            colors: [
                '#5e72e4',
                '#2dce89',
            ],
            plotOptions: {
                column: {
                    stacking: 'normal',
                    colorByPoint: false,
                }
            },
            series: [{
                name: 'IMEI',
                data:  {{$revenuechart['imei']}}
            }, {
                name: 'Server',
                data: {{$revenuechart['server']}}
            }]
        });
        Highcharts.chart('invoice', {
            chart: {
                type: 'column'
            }, dateRangeGrouping: {
                dayFormat: {month: 'numeric', day: 'numeric', year: 'numeric'},
                weekFormat: {month: 'numeric', day: 'numeric', year: 'numeric'},
                monthFormat: {month: 'numeric', year: 'numeric'}
            },
            title: {
                text: 'Income'
            },
            xAxis: {
                categories:  {!! $incomechart['date'] !!}
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            colors: [
                '#5e72e4',
                '#2dce89',
            ],
            plotOptions: {
                column: {
                    stacking: 'normal',
                    colorByPoint: false,
                }
            },
            series: [{
                name: 'Income',
                data: {{$incomechart['income']}}
            }]
        });
    </script>
@endsection
