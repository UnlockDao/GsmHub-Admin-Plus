@extends('layouts.header')
@section('content')
    <style>
        .pagination > li > a, .pagination > li > span {
            border: 0;
            border-radius: 30px !important;
            transition: all .3s;
            padding: 0 11px;
            margin: 0 3px;
            min-width: 30px;
            height: 30px;
            line-height: 30px;
            color: #999;
            font-weight: 400;
            font-size: 12px;
            text-transform: uppercase;
            background: transparent;
        }

        .pagination > .active > a, .pagination > .active > span {
            color: #999;
            text-align: center;
        }

        .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
            background-color: #9c27b0;
            border-color: #4caf50;
            color: #fff;
            box-shadow: 0 4px 5px 0 rgba(156, 39, 176, .14), 0 1px 10px 0 rgba(156, 39, 176, .12), 0 2px 4px -1px rgba(156, 39, 176, .2);
        }

        .max-lines {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            max-width: 150px;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical
        }
    </style>
    <div class="container-fluid">
        <form action="" method="GET">
            <div class="row">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <strong>Service Group</strong>
                            <select class="form-control" name="service_name">
                                <option value="">...</option>
                                @foreach($groupsearch as $g )
                                    <option value="{{$g->id}}"
                                            @if($cachesearch->service_name == $g->id) selected @endif>{{$g->service_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>In</strong>
                            <div class="form-group bmd-form-group is-filled">
                                <input type="text" class="form-control datepicker" name="timein"
                                       value="{{$cachesearch->timein}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <strong>To</strong>
                            <div class="form-group bmd-form-group is-filled">
                                <input type="text" class="form-control datepicker" name="timeto"
                                       value="{{$cachesearch->timeto}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <strong>Status</strong>
                            <select class="form-control" name="status">
                                <option value="">...</option>
                                <option value="COMPLETED" @if($cachesearch->status == 'COMPLETED')selected @endif>
                                    COMPLETED
                                </option>
                                <option value="ACTIVE" @if($cachesearch->status == 'ACTIVE')selected @endif>ACTIVE
                                </option>
                                <option value="REJECTED" @if($cachesearch->status == 'REJECTED')selected @endif>
                                    REJECTED
                                </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-info" type="submit"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-rose">
                        <button type="button" onclick="tableToExcel('testTable', 'W3C Example Table')"
                                class="btn btn-info pull-right"><i class="material-icons">cloud_download</i>
                            <div class="ripple-container"></div>
                        </button>
                        <h3 class="card-title ">IMEI Services</h3>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="testTable" class="table">
                                <thead class="text-primary">
                                <th width="2%">ID</th>
                                <th>Order code</th>
                                <th>Name</th>
                                <th>Credit</th>
                                <th>Profit</th>
                                <th>Result</th>
                                <th>Added</th>
                                <th>Completed</th>
                                <th>Status</th>
                                </thead>
                                <tbody>
                                @foreach($serverorder as $v)
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->order_code}}</td>
                                        <td>@if($v->serverservice == null) @else {{$v->serverservice->service_name}} @endif</td>
                                        <td>{{number_format($v->credit,2)}}</td>
                                        <td>{{$v->credit-$v->purchase_cost}}</td>
                                        <td><a rel="tooltip" class="max-lines"
                                               data-original-title="{{$v->result}}">{{$v->result}}</a></td>
                                        <td>{{$v->date_added}}</td>
                                        <td>{{$v->completed_on}}</td>
                                        <td>{{$v->status}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $serverorder->appends($_GET)->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

