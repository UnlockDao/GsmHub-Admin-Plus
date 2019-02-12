@extends('layouts.header')
@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(document).on("click", ".status", function () {
                var ida = $(this).parent().attr('id');
                var url = '';
                if ($(this).prop('checked') == true) {
                    url = 'service/status/active';
                }
                else if ($(this).prop('checked') == false) {
                    url = 'service/status/inactive';
                }
                $.ajax({
                    url: url,
                    type: 'get',
                    data: 'id=' + ida,
                    success: function (result) {
                        //  $.notify({icon: "notifications", message: result});
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
        }, false);
    </script>
    <!-- Page content -->
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Server</h3>
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Service Group</strong>
                                    <select class="form-control form-control-alternative selectpicker"
                                            data-live-search="true" name="group_name">
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
                                    <strong>Currency</strong>
                                    <select class="form-control form-control-alternative" name="currency">
                                        <option value="">...</option>
                                        <option value="{{$exchangerate->currency_code}}"
                                                @if($cachesearch->currency == $exchangerate->currency_code)selected @endif>{{$exchangerate->currency_code}}</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <strong></strong><br>
                                    <input class="btn btn-info form-control" type="submit" value="Search">
                                </div>
                                <div class="col-md-1">
                                    <strong></strong><br>
                                    <a class="fancybox fancybox.iframe btn btn-info"
                                       href="{{ asset('serversales') }}?group_name={{$cachesearch->group_name}}">Sales</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="parent" class="table-responsive">
                        <table id="fixTable" class="table align-items-center table-flush">
                            <thead class="text-primary" id="myHeader">
                            <th></th>
                            <th>Service Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Supplier</th>
                            <th>PC</th>
                            <th>PC(Net)</th>
                            <th>Credit</th>
                            @foreach($clientgroup as $cg)
                                <th>{{$cg->group_name}}</th>
                            @endforeach
                            <th>Edit</th>
                            <th></th>
                            </thead>
                            <tbody>
                            @foreach($server_service_group->where('servergroup','<>','') as $g)
                                <tr class="table-warning">
                                    <td><i class="ni ni-ungroup"></td>
                                    <td colspan="9"><strong style="font-weight:700;">{{$g->group_name}}</strong></td>
                                    @foreach($clientgroup as $cg)
                                        <td></td>
                                    @endforeach
                                </tr>
                                @foreach($serverservice as $v)
                                    @if($v->server_service_group_id == $g->id )
                                        <tr class="table-info">
                                            <td>{{$v->id}}</td>
                                            <td><a @if($v->serverservicequantityrange->isEmpty() &&$v->serverservicetypewiseprice->isEmpty())
                                                   style="color: red; background: yellow"
                                                   @endif href="https://s-unlock.com/admin/server-service/edit/{{$v->id}}"target="_blank" data-toggle="tooltip" data-placement="top"  data-original-title="{{$v->service_name}}">{{$v->service_name}}</a></td>
                                            <td>@if($v->api_id ==! null)<span
                                                        class="badge badge-pill badge-success">API<span>  @else<span
                                                                class="badge badge-pill badge-info">Manual<span>  @endif
                                            </td>
                                            <td>
                                                <div class="togglebutton">
                                                    <label id="{{$v->id}}" class="custom-toggle">
                                                        <input class="status" id="check{{$v->id}}" type="checkbox"
                                                               onfocus="window.location.reload()"
                                                               @if($v->status == 'active' )checked="" @endif>
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>@if($v->servicepricing->nhacungcap ==! null)@if (CUtil::issuperadmin()){{$v->servicepricing->nhacungcap->name}} @else Supplier #{{$v->servicepricing->nhacungcap->id}} @endif @endif</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @foreach($clientgroup as $cg)
                                                <td></td>
                                            @endforeach
                                            <td><a class="material-icons fancybox fancybox.iframe"
                                                   href="{{ asset('') }}serverservice/{{$v->id}}"><i
                                                            class="ni ni-zoom-split-in"></i></a></td>
                                            <td><a href="{{ asset('') }}serverdelete/{{$v->id}}"
                                                   onclick="return confirm('OK to delete!');"><i
                                                            class="ni ni-fat-remove"></i></a></td>
                                        </tr>
                                        @if(!$v->serverservicetypewiseprice->isEmpty())
                                            @foreach($v->serverservicetypewiseprice as $a)
                                                <tr>
                                                    <td>*</td>
                                                    <td colspan="4"><a class="@if($a->sale >0) badge1 @endif"
                                                                       @if($a->sale >0) data-badge="{{$a->sale}}" @endif>{{$a->service_type}}</a></td>
                                                    <td>@if($v->api_id ==! null)
                                                            @foreach($a->apiservicetypewisepriceid as $apiserverservicetypeprice)
                                                                @if($apiserverservicetypeprice->id == $a->api_service_type_wise_price_id)
                                                                <a data-toggle="tooltip"
                                                                   data-placement="top"
                                                                   data-original-title="{{number_format($apiserverservicetypeprice->api_price*$exchangerate->exchange_rate_static)}} đ">{{$apiserverservicetypeprice->api_price}}</a>
                                                            @endif
                                                            @endforeach
                                                        @else<a data-toggle="tooltip"
                                                                data-placement="top"
                                                                data-original-title="{{number_format($a->purchase_cost_not_net*$exchangerate->exchange_rate_static)}} đ">{{ number_format($a->purchase_cost_not_net, 2) }}</a>@endif</td>
                                                    <td><a data-toggle="tooltip"
                                                           data-placement="top"
                                                           data-original-title="{{number_format($a->purchase_cost*$exchangerate->exchange_rate_static)}} đ">{{ number_format($a->purchase_cost, 2) }}</a></td>
                                                    <td>
                                                        @if($a->purchase_cost > $a->amount)
                                                            <span class="badge badge-pill badge-danger">{{ number_format( $a->amount , 2) }}
                                                                <span>
                                                        @else
                                                                        <a data-toggle="tooltip"
                                                                           data-placement="top"
                                                                           data-original-title="{{number_format($a->amount*$exchangerate->exchange_rate_static)}} đ">{{ number_format( $a->amount , 2) }}</a>
                                                        @endif
                                                    </td>
                                                    @foreach($clientgroup as $cg)
                                                        <td width="5%">@foreach($a->serverservicetypewisegroupprice as $serverservicetypewisegroupprice)
                                                                @if($serverservicetypewisegroupprice->service_type_id == $a->id &&$serverservicetypewisegroupprice->group_id == $cg->id)
                                                                    @if($a->purchase_cost == $serverservicetypewisegroupprice->amount)
                                                                        <span class="badge badge-pill badge-warning"><a data-toggle="tooltip"
                                                                                                                        data-placement="top"
                                                                                                                        data-original-title="{{number_format($serverservicetypewisegroupprice->amount*$exchangerate->exchange_rate_static)}} đ">@if($cachesearch->currency == $exchangerate->currency_code) {{number_format($serverservicetypewisegroupprice->amount*$exchangerate->exchange_rate_static)}}  @else{{ round( $serverservicetypewisegroupprice->amount , 2) }}@endif</a></span>
                                                                    @elseif($a->purchase_cost > $serverservicetypewisegroupprice->amount)
                                                                        <span class="badge badge-pill badge-danger"><a data-toggle="tooltip"
                                                                                                                       data-placement="top"
                                                                                                                       data-original-title="{{number_format($serverservicetypewisegroupprice->amount*$exchangerate->exchange_rate_static)}} đ">@if($cachesearch->currency == $exchangerate->currency_code) {{number_format($serverservicetypewisegroupprice->amount*$exchangerate->exchange_rate_static)}}  @else{{ round( $serverservicetypewisegroupprice->amount , 2) }}@endif</a></span>
                                                                           @else
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       data-original-title="{{number_format($serverservicetypewisegroupprice->amount*$exchangerate->exchange_rate_static)}} đ">@if($cachesearch->currency == $exchangerate->currency_code) {{number_format($serverservicetypewisegroupprice->amount*$exchangerate->exchange_rate_static)}}  @else{{ round( $serverservicetypewisegroupprice->amount , 2) }}@endif</a>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endforeach
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach($v->serverservicequantityrange as $serverservicequantityrange )
                                                <tr>
                                                    <td></td>

                                                    <td>Range</td>
                                                    <td><a class="@if($serverservicequantityrange->sale >0) badge1 @endif"
                                                           @if($serverservicequantityrange->sale >0) data-badge="{{$serverservicequantityrange->sale}}" @endif>{{$serverservicequantityrange->from_range}}
                                                            - {{$serverservicequantityrange->to_range}}</a></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>@if($v->api_id ==! null)
                                                            <a data-toggle="tooltip"
                                                               data-placement="top"
                                                               data-original-title="{{number_format($v->apiserverservices->credits*$exchangerate->exchange_rate_static)}} đ">{{number_format($v->apiserverservices->credits,2)}}</a>
                                                        @elseif($v->servicepricing->purchasecost == null)
                                                            <a data-toggle="tooltip"
                                                               data-placement="top"
                                                               data-original-title="{{number_format($v->purchase_cost*$exchangerate->exchange_rate_static)}} đ">{{number_format($v->purchase_cost,2)}}</a>
                                                        @else
                                                            <a data-toggle="tooltip"
                                                               data-placement="top"
                                                               data-original-title="{{number_format($v->servicepricing->purchasecost*$exchangerate->exchange_rate_static)}} đ">{{number_format($v->servicepricing->purchasecost,2)}}</a>
                                                        @endif</td>
                                                    <td><a data-toggle="tooltip"
                                                           data-placement="top"
                                                           data-original-title="{{number_format($v->purchase_cost*$exchangerate->exchange_rate_static)}} đ">{{number_format($v->purchase_cost,2)}}</a></td>
                                                    <td>@foreach($serverservicequantityrange->serverserviceusercredit as $serverserviceusercredit)
                                                            @if($serverserviceusercredit->currency == $currenciessite->config_value)
                                                                @if($v->purchase_cost > $serverserviceusercredit->credit)
                                                                    <span class="badge badge-pill badge-danger">{{number_format($serverserviceusercredit->credit,2)}}</span>
                                                                @else
                                                                                <a data-toggle="tooltip"
                                                                                   data-placement="top"
                                                                                   data-original-title="{{number_format($serverserviceusercredit->credit*$exchangerate->exchange_rate_static)}} đ">{{number_format($serverserviceusercredit->credit,2)}}</a>
                                                                @endif
                                                            @endif
                                                        @endforeach</td>
                                                    @foreach($clientgroup as $cg)
                                                        <td>@foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
                                                                @if($serverserviceclientgroupcredit->currency== $currenciessite->config_value && $serverserviceclientgroupcredit->client_group_id==$cg->id )
                                                                @if($v->purchase_cost == $serverserviceclientgroupcredit->credit)
                                                                        <span class="badge badge-pill badge-warning"><a data-toggle="tooltip"
                                                                                                                        data-placement="top"
                                                                                                                        data-original-title="{{number_format($serverserviceclientgroupcredit->credit*$exchangerate->exchange_rate_static)}} đ">@if($cachesearch->currency == $exchangerate->currency_code) {{number_format($serverserviceclientgroupcredit->credit*$exchangerate->exchange_rate_static)}}  @else{{round($serverserviceclientgroupcredit->credit,2)}}@endif</a>
                                                                            </span>
                                                                    @elseif($v->purchase_cost > $serverserviceclientgroupcredit->credit)
                                                                        <span class="badge badge-pill badge-danger"><a data-toggle="tooltip"
                                                                                                                       data-placement="top"
                                                                                                                       data-original-title="{{number_format($serverserviceclientgroupcredit->credit*$exchangerate->exchange_rate_static)}} đ">@if($cachesearch->currency == $exchangerate->currency_code) {{number_format($serverserviceclientgroupcredit->credit*$exchangerate->exchange_rate_static)}}  @else{{round($serverserviceclientgroupcredit->credit,2)}}@endif</a>
                                                                            </span>
                                                                            @else
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       data-original-title="{{number_format($serverserviceclientgroupcredit->credit*$exchangerate->exchange_rate_static)}} đ">@if($cachesearch->currency == $exchangerate->currency_code) {{number_format($serverserviceclientgroupcredit->credit*$exchangerate->exchange_rate_static)}}  @else{{round($serverserviceclientgroupcredit->credit,2)}}@endif</a>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endforeach
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end mb-0">

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

@endsection
