@extends('layouts.header')
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
                    <h4 class="page-title">Order Dashboard
                        <div class="spinner-grow text-success" role="status" id="loaderimei"></div>
                        <div class="spinner-grow text-primary" role="status" id="loaderservice"></div></h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
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
        <hr>
        <!-- end row -->

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div id="container" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-2">Balance Supplier API </h4>

                        <div class="table-responsive">
                            <!-- Projects table -->
                            <div id="table-scroll">
                                <table class="table table-centered table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">Supplier</th>
                                        <th scope="col">Balance</th>
                                        <th scope="col">Currency</th>
                                    </tr>
                                    </thead>
                                    <div class="d-flex flex-row justify-content-center align-items-center">
                                        <div class="spinner-grow text-success" role="status" id="loader">
                                        </div>
                                    </div>
                                    <tbody id="creditsuppliers">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-2">Top Service IMEI Orders</h4>

                        <div class="table-responsive">
                            <!-- Projects table -->
                            <div id="table-scroll">
                                <table class="table table-centered table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">Service Name</th>
                                        <th scope="col">Total Orders</th>
                                        <th scope="col">Total Profit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($topservice['imei'] as $i)
                                        <tr>
                                            <th scope="row">{{$i->service_name}}</th>
                                            <td>{{$i->cnt}}</td>
                                            <td>${{round($i->profit)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-2">Top Service Server Orders</h4>

                        <div class="table-responsive">
                            <!-- Projects table -->
                            <div id="table-scroll">
                                <table class="table table-centered table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">Service Name</th>
                                        <th scope="col">Total Orders</th>
                                        <th scope="col">Total Profit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($topservice['server'] as $i)
                                        <tr>
                                            <th scope="row">{{$i->service_name}}</th>
                                            <td>{{$i->cnt}}</td>
                                            <td>${{round($i->profit)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->


            <!-- end col -->

        </div>
        <!-- end card-->
        <!-- end row-->
    </div> <!-- container -->


    <link rel="stylesheet" type="text/css" href="{{ asset('js/highcharts_date_range_grouping.css') }}">
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts_date_range_grouping.min.js') }}"></script>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            }, dateRangeGrouping: {
                dayFormat: {month: 'numeric', day: 'numeric', year: 'numeric'},
                weekFormat: {month: 'numeric', day: 'numeric', year: 'numeric'},
                monthFormat: {month: 'numeric', year: 'numeric'}
            },
            title: {
                text: 'Orders'
            },
            xAxis: {
                categories: {!! $ordercountchart['date'] !!}
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
                '#f5365c',
                '#fb6340',
            ],
            plotOptions: {
                column: {
                    stacking: 'normal',
                    colorByPoint: false,
                }
            },
            series: [{
                name: 'IMEI',
                data: {{$ordercountchart['imei']}}
            }, {
                name: 'Server',
                data: {{$ordercountchart['server']}}
            }, {
                name: 'IMEI REJECTED',
                data: {{$ordercountchart['imeiREJECTED']}}
            }, {
                name: 'Server REJECTED',
                data: {{$ordercountchart['serverREJECTED']}}
            }]
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "checkCreditSuppliers",
                type: "POST",
                success: function (data) {
                    $("#loader").removeAttr('pk').hide();
                    $("#creditsuppliers").append(data);
                },
                error: function (x, e) {

                }
            });
        }, false);
    </script>
@endsection
