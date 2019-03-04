@extends('layouts.header')
@section('content')
    <?php $profit = 0 ?>
    <?php $credit = 0 ?>
    <?php $count = 0 ?>
    {{--<style>--}}
        {{--.max-lines {--}}
            {{--overflow: hidden;--}}
            {{--text-overflow: ellipsis;--}}
            {{--display: -webkit-box;--}}
            {{--max-width: 150px;--}}
            {{---webkit-line-clamp: 1;--}}
            {{---webkit-box-orient: vertical--}}
        {{--}--}}
    {{--</style>--}}
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Imei Order</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-control-label">Service Name</label>
                                                <select class="form-control select2" data-toggle="select2"
                                                        name="service_name">
                                                    <option value="">...</option>
                                                    @foreach($groupsearch as $g )
                                                        <option value="{{$g->id}}"
                                                                @if($cachesearch->service_name == $g->id) selected @endif>{{$g->service_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-control-label">Username</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-alternative"
                                                           name="user_name"
                                                           autocomplete="off"
                                                           value="{{$cachesearch->user_name}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-control-label">Time</label>
                                                <input class="form-control"
                                                       type="text" name="datefilter"
                                                       value="{{$cachesearch->datefilter}}" autocomplete="off"/>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-control-label">Status</label>
                                                <select class="form-control form-control-alternative" name="status">
                                                    <option value="">...</option>
                                                    <option value="COMPLETED"
                                                            @if($cachesearch->status == 'COMPLETED')selected @endif>
                                                        COMPLETED
                                                    </option>
                                                    <option value="ACTIVE"
                                                            @if($cachesearch->status == 'ACTIVE')selected @endif>ACTIVE
                                                    </option>
                                                    <option value="REJECTED"
                                                            @if($cachesearch->status == 'REJECTED')selected @endif>
                                                        REJECTED
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-control-label">Update By</label>
                                                <select class="form-control form-control-alternative" name="updated_by">
                                                    @foreach($updateby as $yb)
                                                        <option value="{{$yb->updated_by}}"
                                                                @if($cachesearch->updated_by == $yb->updated_by)selected @endif>{{$yb->updated_by}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-control-label">View</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-alternative"
                                                           name="view" autocomplete="off"
                                                           value="{{$cachesearch->view}}">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-control-label">Search</label>
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-control-label">Export</label>
                                                <button type="button"
                                                        onclick="tableToExcel('testTable', 'Server Order')"
                                                        class="btn btn-success">Export
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100"
                                   id="testTable">
                                <thead class="text-primary">
                                <th scope="col">ID</th>
                                <th scope="col">User</th>
                                <th scope="col">Order code</th>
                                <th scope="col">Name</th>
                                <th scope="col">Credit</th>
                                <th scope="col">Profit</th>
                                <th scope="col">Result</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Edit</th>
                                </thead>
                                <tbody>
                                @foreach($serverorder as $v)
                                    <tr>
                                        <td scope="row">{{$v->id}}</td>
                                        <td scope="row">{{$v->user->user_name}}</td>
                                        <td scope="row">{{$v->order_code}}</td>
                                        <td scope="row">@if($v->serverservice == null) @else <a data-toggle="tooltip"
                                                                                                data-placement="top"
                                                                                                class="max-lines"
                                                                                                data-original-title="{{$v->serverservice->service_name}}">{{$v->serverservice->service_name}}</a> @endif
                                        </td>
                                        <td scope="row">{{number_format($v->credit_default_currency,2)}}</td>
                                        <td scope="row">{{$v->credit_default_currency-($v->purchase_cost*$v->quantity)}}</td>
                                        <td scope="row"><a data-toggle="tooltip" data-placement="top" class="max-lines"
                                                           data-original-title="{!! $v->result !!}">@if($v->status == 'REJECTED') {!! $v->reject_reason !!} @else {!! $v->result !!} @endif </a>
                                        </td>
                                        <td scope="row">@if($v->status == 'COMPLETED') {{CUtil::convertDate($v->completed_on, 'd-m-Y h:i a') }}
                                            @elseif($v->status == 'REJECTED'){{CUtil::convertDate($v->date_rejected, 'd-m-Y h:i a') }}
                                            @else{{CUtil::convertDate($v->date_added, 'd-m-Y h:i a') }}
                                            @endif
                                        </td>
                                        <td scope="row">{{$v->status}}</td>
                                        <td scope="row"><a
                                                    class=" fancybox fancybox.iframe"
                                                    href="{{ asset('') }}serverorder/{{$v->id}}"><i
                                                        class="mdi mdi-eye"></i></a></td>
                                    </tr>
                                    <?php $profit += $v->credit_default_currency - ($v->purchase_cost * $v->quantity) ?>
                                    <?php $credit += $v->credit_default_currency ?>
                                    <?php $count += 1 ?>
                                @endforeach
                                <tr>
                                    <td style="font-weight: bold">Count</td>
                                    <td style="font-weight: bold">{{$count}}</td>
                                    <td></td>
                                    <td style="font-weight: bold"><a rel="tooltip"
                                                                     data-original-title="{{number_format($credit*22000)}} đ">{{$credit}}
                                            USD</a></td>
                                    <td style="font-weight: bold"><a rel="tooltip"
                                                                     data-original-title="{{number_format($profit*22000)}} đ">{{$profit}}
                                            USD</a></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            <nav aria-label="...">
                                <ul class="pagination justify-content-end mb-0">
                                    {{ $serverorder->appends($_GET)->links() }}
                                </ul>
                            </nav>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->

@endsection

