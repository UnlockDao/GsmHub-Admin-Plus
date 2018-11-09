@extends('layouts.header')
@section('dashboard')
    <style>
        .table .thead-light th{
            min-width: 100px;
        }.highcharts-background {
             display: none;
         }
        #table-scroll {
            height:500px;
            overflow:auto;
        }
        ::-webkit-scrollbar {
            width: 0px;
        }
    </style>
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">IMEI Service Profit</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$imeioder->profit}} USD</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm" style="font-weight: bold;">
                                <span class="text-success mr-2">{{number_format($imeioder->profit*22000)}} </span>
                                <span class="text-nowrap">VND</span>
                                <span class="float-right text-success"><a
                                            href="https://s-unlock.com/admin/order-history/service-wise"
                                            target="_blank">New: {{current($pendingoder['ImeiServiceOrder'])}}</a> | <a
                                            href="https://s-unlock.com/admin/order-history/reply-accepted-order?type=quick"
                                            target="_blank">Accepted: {{next($pendingoder['ImeiServiceOrder'])}}</a></span>

                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Server Service Profit</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$serveroder->profit}} USD</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="ni ni-money-coins"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm" style="font-weight: bold;">
                                <span class="text-success mr-2">{{number_format($serveroder->profit*22000)}} </span>
                                <span class="text-nowrap">VND</span>
                                <span class="float-right text-success"><a
                                            href="https://s-unlock.com/admin/server-order-history/service-wise"
                                            target="_blank">New: {{current($pendingoder['ServerServiceOrder'])}}</a> | <a
                                            href="https://s-unlock.com/admin/server-order-history/reply-accepted-order?type=quick"
                                            target="_blank">Accepted: {{next($pendingoder['ServerServiceOrder'])}}</a></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Profit</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$serveroder->profit+$imeioder->profit}}
                                        USD</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm" style="font-weight: bold;">
                                <span class="text-success mr-2">{{number_format(($serveroder->profit+$imeioder->profit)*22000)}}</span>
                                <span class="text-nowrap">VND</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Income</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$invoice->where('currency','USD')->first()->amt}}
                                        USD</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm" style="font-weight: bold;">
                                <span class="text-success mr-2">{{number_format(($invoice->where('currency','USD')->first()->amt)*22000)}}</span>
                                <span class="text-nowrap">VND</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid mt--7">
        <div class="row">

            <div class="col-xl-12 col-lg-12" style="padding-top: 10px">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div id="profitchart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div id="container" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12" style="padding-top: 10px">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div id="invoice" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6" style="padding-top: 10px">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Top Service IMEI Orders</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <div id="table-scroll">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
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
                                    <td>${{$i->profit}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6" style="padding-top: 10px">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Top Service Server Orders</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <div id="table-scroll">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
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
                                    <td>${{$i->profit}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <link rel="stylesheet" type="text/css" href="{{ asset('js/highcharts_date_range_grouping.css') }}">
        <script src="{{ asset('js/highcharts.js') }}"></script>
        <script src="{{ asset('js/highcharts_date_range_grouping.min.js') }}"></script>
        <script>
            Highcharts.chart('profitchart', {
                chart: {
                    type: 'column'
                },dateRangeGrouping: {
                    dayFormat: { month: 'numeric', day: 'numeric', year: 'numeric' },
                    weekFormat: { month: 'numeric', day: 'numeric', year: 'numeric' },
                    monthFormat: { month: 'numeric', year: 'numeric'  }
                },
                title: {
                    text: 'Profit'
                },
                xAxis: {
                    categories:  {!! $profitchart['date'] !!}
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
                    data:  {{$profitchart['imei']}}
                }, {
                    name: 'Server',
                    data: {{$profitchart['server']}}
                }]
            });

            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },dateRangeGrouping: {
                    dayFormat: { month: 'numeric', day: 'numeric', year: 'numeric' },
                    weekFormat: { month: 'numeric', day: 'numeric', year: 'numeric' },
                    monthFormat: { month: 'numeric', year: 'numeric'  }
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
            Highcharts.chart('invoice', {
                chart: {
                    type: 'column'
                },dateRangeGrouping: {
                    dayFormat: { month: 'numeric', day: 'numeric', year: 'numeric' },
                    weekFormat: { month: 'numeric', day: 'numeric', year: 'numeric' },
                    monthFormat: { month: 'numeric', year: 'numeric'  }
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
