@extends('layouts.header')
@section('content')
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Profit Report</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="testTable" class="table align-items-center table-flush">
                            <thead class="text-primary">
                                <th scope="col">ID</th>
                                <th scope="col">Date</th>
                                <th scope="col">IMEI Profit</th>
                                <th scope="col">Server Profit</th>
                                <th scope="col">Profit Amount</th>
                            </thead>
                            <tbody>
                            @foreach($profit as $v)
                                <tr>
                                    <td scope="row">{{$v->id}}</td>
                                    <td scope="row">{{$v->date_profit}}</td>
                                    <td scope="row">{{$v->imei_profit_amount}}</td>
                                    <td scope="row">{{$v->server_profit_amount}}</td>
                                    <td scope="row"><strong>{{$v->server_profit_amount+$v->imei_profit_amount}}</strong></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end mb-0">
                                {{ $profit->appends($_GET)->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

@endsection
