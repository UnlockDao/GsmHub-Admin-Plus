@extends('layouts.app')
@section('content')
    <div class="form-body">
        <div class="row">
            <fieldset class="col-md-6 mb40">
                <h4 class="form-section">User ID [<strong class="text-muted">{{$serverorder->user->user_id}}</strong>]
                </h4>
                <div class="row static-info">
                    <div class="col-md-5 name">Username:</div>
                    <div class="col-md-7 value"> {{$serverorder->user->user_name}}</div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Email:</div>
                    <div class="col-md-7 value">{{$serverorder->user->email}}</div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Available Credits:</div>
                    <div class="col-md-7 value">{{$serverorder->user->api_credits}} </div>
                </div>
            </fieldset>
            <fieldset class="col-md-6 mb40">
                <h4 class="form-section">Service ID [<strong
                            class="text-muted">{{$serverorder->serverservice->id}}</strong>]</h4>
                <div class="row static-info">
                    <div class="col-md-5 name">Service Name:</div>
                    <div class="col-md-7">
                        {{$serverorder->serverservice->service_name}}    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Credit:</div>
                    <div class="col-md-7 value">{{$serverorder->credit_default_currency}} USD</div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Is Debited:</div>
                    <div class="col-md-7 value">
                        <span class="badge badge-success">@if($serverorder->amount_debitted == 1) Yes @else
                                No @endif</span>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Profit:</div>
                    <div class="col-md-7 value">
                        <span title="Profit"> {{$serverorder->credit_default_currency-($serverorder->purchase_cost*$serverorder->quantity)}} USD </span>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">API Information:</div>
                    <div class="col-md-7">

                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">API Code:</div>
                    <div class="col-md-7 value">
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="row">
            <fieldset class="col-md-6 mb40">
                <h4 class="form-section">Order Details [<strong class="text-muted">{{$serverorder->order_code}}</strong>]
                </h4>
                <div class="row static-info">
                    <div class="col-md-5 name">Added on:</div>
                    <div class="col-md-7 value text-muted">{{CUtil::convertDate($serverorder->date_added, 'd-m-Y h:i a') }}</div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Replied on:</div>
                    <div class="col-md-7 value text-muted">{{CUtil::convertDate($serverorder->completed_on, 'd-m-Y h:i a') }}</div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Quantity :</div>
                    <div class="col-md-7 value"> {{$serverorder->quantity }}</div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Unlock Code:</div>
                    <div class="col-md-7 value wid280">{!! $serverorder->result !!}</div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Status:</div>
                    <div class="col-md-7 value">
                        <span class="label label-sm label-success">{{$serverorder->status }}</span>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name">Updated By:</div>
                    <div class="col-md-7 value">
                        {{$serverorder->updated_by }}
                    </div>
                </div>
            </fieldset>
            <fieldset class="col-md-6 mb40">
                <div class="form-horizontal ordviw-action">
                    <h4 class="form-section">Action</h4>
                    <form method="POST" action="" accept-charset="UTF-8" id="orderRejectFrm" class="mb20">
                        {{ csrf_field() }}
                        <div class="form-group ">
                            <label for="reject_reason" class="control-label col-md-3 required-icon">Reason</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="5" cols="50" name="reject_reason"
                                          id="reject_reason"></textarea> <label class="error"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-3">
                                @if($serverorder->status == 'COMPLETED')
                                    <input name="status" value="REJECTED" hidden>
                                    <button type="submit" disabled name="" class="btn btn-danger"><i
                                                class="fa fa-times-circle"></i> Mark as rejected
                                    </button>
                                @else
                                    <input name="status" value="COMPLETED" hidden>
                                    <button type="submit" disabled name="" class="btn btn-success"><i
                                                class="fa fa-times-circle"></i> Mark as completed
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </fieldset>
        </div>
    </div>
@endsection

