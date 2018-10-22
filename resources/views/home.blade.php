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
                        <p class="mt-3 mb-0 text-muted text-sm">
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
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-success mr-2">{{number_format($imeioder->profit*22000)}}</span>
                            <span class="text-nowrap">VND</span>
                        </p>
                    </div>
                </div>
            </div>
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">Tổng lợi nhuận FILE</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0"></span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-info text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-percent"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-success mr-2"><i class="fas fa-arrow-up"></i> </span>--}}
                            {{--<span class="text-nowrap"></span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-xl-4 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Tổng lợi nhuận</h5>
                                <span class="h2 font-weight-bold mb-0">{{$serveroder->profit+$imeioder->profit}} USD</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-success mr-2">{{number_format(($serveroder->profit+$imeioder->profit)*22000)}}</span>
                            <span class="text-nowrap">VND</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{--<br><!--server--><hr>--}}
        {{--<div class="row">--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">SERVER Hôm nay</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$serveroderday->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-danger text-white rounded-circle shadow">--}}
                                    {{--<i class="ni ni-money-coins"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{number_format($serveroderday->profit*22000)}} </span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">Hôm qua</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$serveroderyesterday->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-warning text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-chart-pie"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{number_format($serveroderyesterday->profit*22000)}}</span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">7 ngày</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$serveroderweek->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-yellow text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-users"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> {{number_format($serveroderweek->profit*22000)}}</span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">Tháng này</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$serverodermonth->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-info text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-percent"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-success mr-2"><i class="fas fa-arrow-up"></i>{{number_format($serverodermonth->profit*22000)}}</span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<br><!--imei-->--}}
        {{--<div class="row">--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">IMEI Hôm nay</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$imeioderday->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-danger text-white rounded-circle shadow">--}}
                                    {{--<i class="ni ni-money-coins"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{number_format($imeioderday->profit*22000)}} </span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">Hôm qua</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$imeioderyesterday->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-warning text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-chart-pie"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{number_format($imeioderyesterday->profit*22000)}}</span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">7 ngày</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$imeioderweek->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-yellow text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-users"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> {{number_format($imeioderweek->profit*22000)}}</span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">Tháng này</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$imeiodermonth->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-info text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-percent"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-success mr-2"><i class="fas fa-arrow-up"></i>{{number_format($imeiodermonth->profit*22000)}}</span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<br><!--server+imei--><hr>--}}
        {{--<div class="row">--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">Hôm nay</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$imeioderday->profit+$serveroderday->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-danger text-white rounded-circle shadow">--}}
                                    {{--<i class="ni ni-money-coins"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{number_format(($imeioderday->profit+$serveroderday->profit)*22000)}} </span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">Hôm qua</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$imeioderyesterday->profit+$serveroderyesterday->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-warning text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-chart-pie"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> {{number_format(($imeioderyesterday->profit+$serveroderyesterday->profit)*22000)}}</span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">7 ngày</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$imeioderweek->profit+$serveroderweek->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-yellow text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-users"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> {{number_format(($imeioderweek->profit+$serveroderweek->profit)*22000)}}</span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-xl-3 col-lg-6">--}}
                {{--<div class="card card-stats mb-4 mb-xl-0">--}}
                    {{--<div class="card-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col">--}}
                                {{--<h5 class="card-title text-uppercase text-muted mb-0">Tháng này</h5>--}}
                                {{--<span class="h2 font-weight-bold mb-0">{{$imeiodermonth->profit+$serverodermonth->profit}} USD</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-auto">--}}
                                {{--<div class="icon icon-shape bg-info text-white rounded-circle shadow">--}}
                                    {{--<i class="fas fa-percent"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="mt-3 mb-0 text-muted text-sm">--}}
                            {{--<span class="text-success mr-2"><i class="fas fa-arrow-up"></i>{{number_format(($imeiodermonth->profit+$serverodermonth->profit)*22000)}}</span>--}}
                            {{--<span class="text-nowrap">VND</span>--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
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

    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Total Order'
        },
        xAxis: {
            categories: {!! $chartserverdate !!}
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
            data: {!! $chartcountimeivalue !!}
        }, {
            name: 'Server',
            data: {!!  $chartcountservervalue!!}
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
            categories:  {!! $chartserverdate !!}
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
            data: {!! $chartimeivalue !!}
        }, {
            name: 'Server',
            data: {!! $chartservervalue !!}
        }]
    });


</script>
@endsection
