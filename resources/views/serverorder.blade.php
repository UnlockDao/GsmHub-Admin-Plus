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
    <script>
        $(document).ready(function(){
            $("input").click(function(){
                $(this).next().show();
                $(this).next().hide();
            });

        });
    </script>
    <!-- Page content -->
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <form action="" method="GET">
                        <label class="form-control-label">{{$nameserver}}</label>
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-control-label">Service Name</label>
                                        <input list="brow" name="service_name" class="form-control form-control-alternative" value="{{$cachesearch->service_name}}">
                                        <datalist id="brow">
                                            @foreach($groupsearch as $g )
                                                <option value="{{$g->id}}">{{$g->service_name}}</option>
                                            @endforeach
                                        </datalist>
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
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-header border-0">
                        <h3 class="mb-0">Hóa đơn</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="text-primary">
                                <th scope="col">ID</th>
                                <th scope="col">Order code</th>
                                <th scope="col">Name</th>
                                <th scope="col">Credit</th>
                                <th scope="col">Profit</th>
                                <th scope="col">Result</th>
                                <th scope="col">Added</th>
                                <th scope="col">Completed</th>
                                <th scope="col">Status</th>
                            </thead>
                            <tbody>
                            @foreach($serverorder as $v)
                                <tr>
                                    <td scope="row">{{$v->id}}</td>
                                    <td scope="row">{{$v->order_code}}</td>
                                    <td scope="row">@if($v->serverservice == null) @else <a data-toggle="tooltip" data-placement="top" class="max-lines"
                                                                                                              data-original-title="{{$v->serverservice->service_name}}">{{$v->serverservice->service_name}}</a> @endif</td>
                                    <td scope="row">{{number_format($v->credit_default_currency,2)}}</td>
                                    <td scope="row">{{$v->credit_default_currency-($v->purchase_cost*$v->quantity)}}</td>
                                    <td scope="row"><a data-toggle="tooltip" data-placement="top" class="max-lines"
                                           data-original-title="{{$v->result}}">{{$v->result}}</a></td>
                                    <td scope="row">{{CUtil::convertDate($v->date_added, 'd-m-Y h:i a') }}</td>
                                    <td scope="row">{{CUtil::convertDate($v->completed_on, 'd-m-Y h:i a') }}</td>
                                    <td scope="row">{{$v->status}}</td>
                                </tr>
                                <?php $profit += $v->credit_default_currency-($v->purchase_cost*$v->quantity) ?>
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
                                {{ $serverorder->appends($_GET)->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

@endsection
