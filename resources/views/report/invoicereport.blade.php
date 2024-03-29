@extends('layouts.header')
@section('content')
    <?php $credit = 0 ?>
    <?php $count = 0 ?>
    <!-- Page content -->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a onclick="tableToExcel('testTable', 'Invoice Report')" class="btn btn-sm btn-primary"
                           style="color: #fff">Export</a>
                    </div>
                    <h4 class="page-title">Invoice Report</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <form action="" method="GET">
                        <label class="form-control-label"></label>
                        <div class="row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-control-label">Transaction Ref Id</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-alternative" name="payment_gateway_ref_id"
                                                   autocomplete="off"
                                                   value="{{$cachesearch->payment_gateway_ref_id}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-control-label">Username</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-alternative" name="user_name"
                                                   autocomplete="off"
                                                   value="{{$cachesearch->user_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-control-label">Time</label>
                                        <input class="form-control form-control-alternative" type="text"
                                               name="datefilter" value="{{$cachesearch->datefilter}}"
                                               autocomplete="off"/>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-control-label">Status</label>
                                        <select class="form-control form-control-alternative" name="status">
                                            <option value="">...</option>
                                            <option value="paid" @if($cachesearch->status == 'paid')selected @endif>
                                                Paid
                                            </option>
                                            <option value="cancelled"
                                                    @if($cachesearch->status == 'cancelled')selected @endif>Cancelled
                                            </option>
                                            <option value="pending_approval"
                                                    @if($cachesearch->status == 'pending_approval')selected @endif>
                                                Pending Approval
                                            </option>
                                            <option value="pending_payment"
                                                    @if($cachesearch->status == 'pending_payment')selected @endif>
                                                Pending Payment
                                            </option>
                                            <option value="partially_paid"
                                                    @if($cachesearch->status == 'partially_paid')selected @endif>
                                                Partially Paid
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-control-label">Method</label>
                                        <select class="form-control form-control-alternative" name="payment">
                                            <option value="">...</option>
                                            @foreach($payment as $p)
                                                <option value="{{$p->gateway_key}}" @if($cachesearch->payment == $p->gateway_key)selected @endif>{{$p->gateway_label}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-control-label">Payment Status</label>
                                        <select class="form-control form-control-alternative" name="payment_gateway_status">
                                            <option value="">...</option>
                                            @foreach($payment_gateway_status as $p)
                                                <option value="{{$p->payment_gateway_status}}" @if($cachesearch->payment_gateway_status == $p->payment_gateway_status)selected @endif>{{$p->payment_gateway_status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-control-label">Currency</label>
                                        <select class="form-control form-control-alternative" name="currency">
                                            <option value="">...</option>
                                            @foreach($currency as $p)
                                                <option value="{{$p->currency}}" @if($cachesearch->currency == $p->currency)selected @endif>{{$p->currency}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-control-label">Log</label>
                                        <select class="form-control form-control-alternative" name="log">
                                            <option value="">...</option>
                                            <option value="1">Show</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-control-label">View</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-alternative" name="view"
                                                   autocomplete="off"
                                                   value="{{$cachesearch->view}}">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-control-label">Search</label>
                                        <input type="submit" value="Search" class="form-control btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Invoice Report</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="testTable" class="table align-items-center table-flush">
                            <thead class="text-primary">
                            <th scope="col">ID</th>
                            <th scope="col">User Name</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Date Paid</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Status</th>
                            @if($cachesearch->log == 1)
                            <th scope="col">Log</th>
                            @endif
                            <th scope="col">View</th>
                            </thead>
                            <tbody>
                            @foreach($serverorder as $v)
                                <tr>
                                    <td scope="row">{{$v->id}}</td>
                                    <td scope="row">@if($v->user){{$v->user->user_name}}@endif</td>
                                    <td scope="row">{{CUtil::convertDate($v->date_added, 'd-m-Y h:i a') }}</td>
                                    <td scope="row">{{CUtil::convertDate($v->date_paid, 'd-m-Y h:i a') }}</td>
                                    <td scope="row">{{$v->invoice_amount}} {{$v->currency}}</td>
                                    <td scope="row">{{$v->invoice_status}}<br>
                                        {{ isset($v->payment) ? $v->payment->gateway_label : '' }}<br>
                                        {{$v->payment_gateway_receiver}}<br>
                                        {{$v->payment_gateway_ref_id}}<br>

                                    </td>
                                    @if($cachesearch->log == 1)
                                    <td scope="row">@if($v->paypal_transaction)
                                                       <pre>{{$v->paypal_transaction->paypal_post_vars }}</pre>
                                        @endif</td>
                                    @endif
                                    <td scope="row"><a href="{{env('GSMHUB_URL')}}/admin/payment/view-invoice/{{$v->id}}" target="_blank" class="btn btn-sm btn-primary">View</a></td>
                                </tr>
                                <?php $credit += $v->invoice_amount ?>
                                <?php $count += 1 ?>
                            @endforeach
                            <tr>
                                <td style="font-weight: bold">Count</td>
                                <td style="font-weight: bold">{{$count}}</td>
                                <td></td>
                                <td></td>
                                <td style="font-weight: bold">{{$credit}}</td>
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
