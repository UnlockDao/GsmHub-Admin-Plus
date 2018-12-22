@extends('layouts.header')
@section('content')
    <div class="container-fluid mt--7">
    @if (CUtil::checkauth())
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <form action="{{ url('runcronrange') }}" method="GET">
                        <label class="form-control-label"></label>
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-control-label">Time</label>
                                        <input class="form-control form-control-alternative" type="text"
                                               name="datefilter" value="{{$cachesearch->datefilter}}"
                                               autocomplete="off"/>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-control-label">Reload</label>
                                        <input type="submit" value="Reload" class="form-control btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                            @foreach($profit as $v)
                                <tr>
                                    <td scope="row">{{$v->id}}</td>
                                    <td scope="row">{{$v->date_profit}}</td>
                                    <td scope="row">{{$v->imei_profit_amount}}</td>
                                    <td scope="row">{{$v->server_profit_amount}}</td>
                                    <td scope="row"><strong>{{$v->server_profit_amount+$v->imei_profit_amount}}</strong></td>
                                    <td scope="row">
                                        <form action="{{ url('reloadprofit') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input name="date" value="{{$v->date_profit}}" hidden>
                                            <input type="submit" value="Reload" class="btn btn-sm btn-primary">
                                        </form>
                                    </td>
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
@endif
@endsection
