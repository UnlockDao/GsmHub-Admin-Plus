@extends('layouts.header')
@section('content')
    <?php $profit = 0 ?>
    <?php $credit = 0 ?>
    <?php $count = 0 ?>
    <style>
        .max-lines {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            max-width: 150px;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical
        }
    </style>
    <!-- Page content -->
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-control-label">Service Name</label>
                                       <select class="form-control form-control-alternative selectpicker"
                                                data-live-search="true" name="service_name">
                                            <option value="">...</option>
                                            @foreach($groupsearch as $g )
                                                <option value="{{$g->id}}"
                                                        @if($cachesearch->service_name == $g->id) selected @endif>{{$g->service_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-control-label">Time</label>
                                            <input class="form-control form-control-alternative" type="text" name="datefilter" value="{{$cachesearch->datefilter}}" style="width:300px" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-control-label">Status</label>
                                        <select class="form-control form-control-alternative" name="status">
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
                                    <div class="col-md-2">
                                        <label class="form-control-label">Update By</label>
                                        <select class="form-control form-control-alternative" name="updated_by">
                                            @foreach($updateby as $yb)
                                                <option value="{{$yb->updated_by}}" @if($cachesearch->updated_by == $yb->updated_by)selected @endif>{{$yb->updated_by}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-control-label">View</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-alternative" name="view" autocomplete="off"
                                                   value="{{$cachesearch->view}}">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <br>
                                        <button class="btn btn-info" type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                    <div class="col-md-1">
                                        <br>
                                        <button type="button" onclick="tableToExcel('testTable', 'IMEI Order')" class="btn btn-info pull-right"><i class="ni ni-cloud-download-95"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-header border-0">
                        <h3 class="mb-0">Hóa đơn</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="testTable" class="table align-items-center table-flush">
                            <thead class="text-primary">
                                <th scope="col">ID</th>
                                <th scope="col">Order code</th>
                                <th scope="col">Name</th>
                                <th scope="col">Credit</th>
                                <th scope="col">Profit</th>
                                <th scope="col">Added</th>
                                <th scope="col">Rejected</th>
                                <th scope="col">Completed</th>
                                <th scope="col">Status</th>
                            </thead>
                            <tbody>
                            @foreach($imeiorder as $v)
                                <tr>
                                    <td scope="row">{{$v->id}}</td>
                                    <td scope="row">{{$v->order_code}}</td>
                                    <td scope="row">@if($v->imeiservice == null) @else <a data-toggle="tooltip" data-placement="top" class="max-lines"
                                                                                                              data-original-title="{{$v->imeiservice->service_name}}">{{$v->imeiservice->service_name}}</a> @endif</td>
                                    <td scope="row">{{number_format($v->credit_default_currency,2)}}</td>
                                    <td scope="row">{{$v->credit_default_currency-$v->purchase_cost}}</td>
                                    <td scope="row">{{CUtil::convertDate($v->date_added, 'd-m-Y h:i a') }}</td>
                                    <td scope="row">{{CUtil::convertDate($v->date_rejected, 'd-m-Y h:i a') }}</td>
                                    <td scope="row">{{CUtil::convertDate($v->completed_on, 'd-m-Y h:i a') }}</td>
                                    <td scope="row">{{$v->status}}</td>
                                </tr>
                                <?php $profit += $v->credit_default_currency-$v->purchase_cost ?>
                                <?php $credit += $v->credit_default_currency ?>
                                <?php $count += 1 ?>
                            @endforeach
                            <tr>
                                <td style="font-weight: bold">Count</td>
                                <td style="font-weight: bold">{{$count}}</td>
                                <td></td>
                                <td style="font-weight: bold"><a rel="tooltip"  data-original-title="{{number_format($credit*22000)}} đ" >{{$credit}} USD</a></td>
                                <td style="font-weight: bold"><a rel="tooltip"  data-original-title="{{number_format($profit*22000)}} đ" >{{$profit}} USD</a></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end mb-0">
                                {{ $imeiorder->appends($_GET)->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

@endsection
