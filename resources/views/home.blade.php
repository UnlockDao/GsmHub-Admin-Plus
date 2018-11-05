@extends('layouts.header')
@section('dashboard')
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Lợi nhuận Server</h5>
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
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Lợi nhuận IMEI</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$imeioder->profit}} USD</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm" style="font-weight: bold;">
                                <span class="text-success mr-2">{{number_format($imeioder->profit*22000)}}</span>
                                <span class="text-nowrap">VND</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Tổng lợi nhuận</h5>
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
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12" style="padding-top: 10px">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div id="pricing" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>


        <script src="{{ asset('js/highcharts.js') }}"></script>
        <script>
            var imeistring = {!! $imeichart->pluck('profit') !!};
            var tofiximei = imeistring.map(Number);
            var serverstring = {!! $serverchart->pluck('profit') !!};
            var tofixserver = serverstring.map(Number);
            imei = tofiximei.map(function(each_element){
                return Number(each_element.toFixed(0));
            });
            server = tofixserver.map(function(each_element){
                return Number(each_element.toFixed(0));
            });

            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Total Order'
                },
                xAxis: {
                    categories: {!! $serverchart->pluck('date') !!}
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
                    data: {!! $imeichart->pluck('value') !!}
                }, {
                    name: 'Server',
                    data: {!! $serverchart->pluck('value') !!}
                }]
            });


            Highcharts.chart('pricing', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Total profit'
                },
                xAxis: {
                    categories:  {!! $serverchart->pluck('date') !!}
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
                    data: imei
                }, {
                    name: 'Server',
                    data: server
                }]
            });
        </script>
@endsection
