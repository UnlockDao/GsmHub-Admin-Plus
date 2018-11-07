@extends('layouts.header')
@section('content')

    <div class="container-fluid mt--7">
        <div id="container" style="min-width:310px; height:300px;"><p class="dashbd-disbl">Account Status</p></div>
        <script src="{{ asset('js/highcharts.js') }}"></script>
        <script type="text/javascript">

            Highcharts.chart('container', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Account Status'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    name: 'Brands',
                    colorByPoint: true,
                    data: [{
                        name: 'Active',
                        y: {{$chart['active']}}
                    }, {
                        name: 'BLocked',
                        y: {{$chart['block']}}
                    }, {
                        name: 'New Register',
                        y: {{$chart['newregister']}}
                    }]
                }]
            });
        </script>
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Member</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="testTable" class="table align-items-center table-flush">
                            <thead class="text-primary">
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Status</th>
                                <th scope="col">LastLogin</th>
                                <th scope="col">Currency</th>
                                <th scope="col">Activated</th>
                            </thead>
                            <tbody>
                            @foreach($member as $v)
                                <tr>
                                    <td scope="row">{{$v->user_id}}</td>
                                    <td scope="row">{{$v->user_name}}</td>
                                    <td scope="row">{{$v->email}}</td>
                                    <td scope="row">{{$v->api_credits}}</td>
                                    <td scope="row">{{$v->user_status}}</td>
                                    <td scope="row">{{$v->last_login}}</td>
                                    <td scope="row">{{$v->default_currency}}</td>
                                    <td scope="row">@if($v->activated == 1) Active @else No @endif</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end mb-0">
                                {{ $member->appends($_GET)->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

@endsection
