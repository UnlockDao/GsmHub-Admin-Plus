@extends('layouts.salehead')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Service Group</strong>
                            <select class="form-control form-control-alternative" data-live-search="true"
                                    name="group_name">
                                <option value="">...</option>
                                @foreach($groupsearch as $g )
                                    <option value="{{$g->id}}"
                                            @if($cachesearch->group_name == $g->id) selected @endif>{{$g->group_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Service Type</strong>
                            <select class="form-control form-control-alternative" name="type">
                                <option value="">...</option>
                                <option value="api" @if($cachesearch->type == 'api')selected @endif>API</option>
                                <option value="manual" @if($cachesearch->type == 'manual')selected @endif>
                                    MANUAL
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Status</strong>
                            <select class="form-control form-control-alternative" name="status">
                                <option value="">...</option>
                                <option value="active" @if($cachesearch->status == 'active')selected @endif>
                                    Active
                                </option>
                                <option value="inactive" @if($cachesearch->status == 'inactive')selected @endif>
                                    Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Supplier</strong>
                            <select class="form-control form-control-alternative" name="supplier">
                                <option value="">...</option>
                                @foreach($supplier as $s)
                                    <option value="{{$s->id}}"
                                            @if($cachesearch->supplier == $s->id)selected @endif>{{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <br>
                            <button class="btn btn-info" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
                <div id="parent" class="card-body">
                    <form action="" method="post">
                        {{ csrf_field() }}
                        <div id="parent" class="table-responsive">
                        <table id="fixTable" class="table align-items-center table-flush">
                            <thead class="text-primary" id="myHeader">
                            <th style="width:20px;"><input type="checkbox"></th>
                            <th></th>
                            <th>Service Name</th>
                            <th>Type</th>
                            <th>Supplier</th>
                            <th>PC</th>
                            <th>PC(Net)</th>
                            <th>Credit</th>
                            @foreach($clientgroup as $cg)
                                <th>{{$cg->group_name}}</th>
                            @endforeach
                            </thead>
                            <tbody>
                            @foreach($server_service_group->where('servergroup','<>','') as $g)
                                <tr class="table-warning">
                                    <td><i class="ni ni-ungroup"></td>
                                    <td colspan="7"><strong style="font-weight:700;">{{$g->group_name}}</strong></td>
                                    @foreach($clientgroup as $cg)
                                        <td></td>
                                    @endforeach
                                </tr>
                                @foreach($serverservice as $v)
                                    @if($v->server_service_group_id == $g->id )
                                        <tr class="table-info">
                                            @if(!$v->serverservicetypewiseprice->isEmpty())
                                                <td><input type="checkbox" name="chkwise[]"
                                                       value="{{$v->id}}"></td>
                                            @else
                                                <td><input type="checkbox" name="chkrange[]"
                                                           value="{{$v->id}}"></td>
                                            @endif
                                            <td>{{$v->id}}</td>
                                            <td>{{$v->service_name}}</td>
                                            <td>@if($v->api_id ==! null)<span
                                                        class="badge badge-pill badge-success">API<span>  @else<span
                                                                class="badge badge-pill badge-info">Manual<span>  @endif
                                            </td>
                                            <td>@if($v->servicepricing->nhacungcap ==! null){{$v->servicepricing->nhacungcap->name}}@endif</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @foreach($clientgroup as $cg)
                                                <td></td>
                                            @endforeach

                                        </tr>
                                        @if(!$v->serverservicetypewiseprice->isEmpty())
                                            @foreach($v->serverservicetypewiseprice as $a)
                                                <tr>
                                                    <td></td>
                                                    <td>*</td>
                                                    <td colspan="3"><a class="@if($a->sale >0) badge1 @endif"
                                                                       @if($a->sale >0) data-badge="{{$a->sale}}" @endif>{{$a->service_type}}</a>
                                                    </td>
                                                    <td>@if($v->api_id ==! null)
                                                            @foreach($v->apiserverservicetypeprice as $apiserverservicetypeprice)@if($apiserverservicetypeprice->service_type==$a->service_type)
                                                                {{$apiserverservicetypeprice->api_price}}
                                                            @endif
                                                            @endforeach
                                                        @else{{ number_format($a->purchase_cost_not_net, 2) }}@endif</td>
                                                    <td>{{ number_format($a->purchase_cost, 2) }}</td>
                                                    <td>{{ number_format( $a->amount , 2) }}</td>
                                                    @foreach($clientgroup as $cg)
                                                        <td>@foreach($a->serverservicetypewisegroupprice as $serverservicetypewisegroupprice)
                                                                @if($serverservicetypewisegroupprice->service_type_id == $a->id &&$serverservicetypewisegroupprice->group_id == $cg->id)
                                                                    {{ number_format( $serverservicetypewisegroupprice->amount , 2) }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach($v->serverservicequantityrange as $serverservicequantityrange )
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <a class="@if($serverservicequantityrange->sale >0) badge1 @endif"
                                                           @if($serverservicequantityrange->sale >0) data-badge="{{$serverservicequantityrange->sale}}" @endif>{{$serverservicequantityrange->from_range}}
                                                            - {{$serverservicequantityrange->to_range}}</a></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>@if($v->api_id ==! null)
                                                            {{number_format($v->apiserverservices->credits,2)}}
                                                        @elseif($v->servicepricing->purchasecost == null)
                                                            {{number_format($v->purchase_cost,2)}}
                                                        @else
                                                            {{number_format($v->servicepricing->purchasecost,2)}}
                                                        @endif</td>
                                                    <td>{{number_format($v->purchase_cost,2)}}</td>
                                                    <td>@foreach($serverservicequantityrange->serverserviceusercredit as $serverserviceusercredit)
                                                            @if($serverserviceusercredit->currency == $currenciessite->config_value)
                                                                {{number_format($serverserviceusercredit->credit,2)}}
                                                            @endif
                                                        @endforeach</td>
                                                    @foreach($clientgroup as $cg)
                                                        <td>@foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
                                                                @if($serverserviceclientgroupcredit->currency==$currenciessite->config_value && $serverserviceclientgroupcredit->client_group_id==$cg->id )
                                                                    {{number_format($serverserviceclientgroupcredit->credit,2)}}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div class="form-group">
                            <label for="sales">Sales %</label>
                            <input name="sales" type="number" value="" min="0" max="100" required
                                   placeholder="Phần trăm giảm giá" class="form-control" id="sales">
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" name="type">
                                <option value="1">Giảm theo % giá</option>
                                <option value="2">Giảm theo % lợi nhuận</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>






@endsection

