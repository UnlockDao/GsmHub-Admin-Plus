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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-rose">
                        <button type="button" onclick="tableToExcel('testTable', 'W3C Example Table')"
                                class="btn btn-info pull-right"><i class="material-icons">cloud_download</i>
                        </button>
                        <h3 class="card-title ">Server Services</h3>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-full-width table-hover">
                            <table id="testTable" class="table table-striped">
                                <thead class="text-primary" id="myHeader">
                                <th width="2%"></th>
                                <th width="30%">Service Name</th>
                                <th width="10%">Service Type</th>
                                <th width="5%">Status</th>
                                <th width="5%">Supplier</th>
                                <th width="10%">Purchase Cost</th>
                                <th width="10%">Purchase Cost (Net)</th>
                                <th width="5%">Credit</th>
                                @foreach($clientgroup as $cg)
                                    <th  width="5%">{{$cg->group_name}}</th>
                                @endforeach
                                <th width="2%">Edit</th>
                                </thead>
                                <tbody>
                                @foreach($server_service_group as $g)
                                    <tr class="table-warning">
                                        <td><i class="material-icons">monetization_on</i></td>
                                        <td><strong style="font-weight:700;">{{$g->group_name}}</strong></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @foreach($serverservice as $v)
                                        @if($v->server_service_group_id == $g->id )
                                            <tr class="table-info">
                                                <td width="2%">{{$v->id}}</td>
                                                <td width="30%">{{$v->service_name}}</td>
                                                <td width="10">@if($v->api_id ==! null)<span
                                                            class="badge badge-pill badge-success">API<span>  @else<span
                                                                    class="badge badge-pill badge-info">Manual<span>  @endif
                                                </td>
                                                <td width="5%">
                                                    <div class="togglebutton">
                                                        <label id="{{$v->id}}">
                                                            <input class="status" id="check{{$v->id}}" type="checkbox" onClick="window.location.reload()"
                                                                   @if($v->status == 'active' )checked="" @endif>
                                                            <span class="toggle"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="5%">@if($v->servicepricing->nhacungcap ==! null){{$v->servicepricing->nhacungcap->name}}@endif</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td width="2%"><a class="material-icons fancybox fancybox.iframe"
                                                       href="{{ asset('') }}serverservice/{{$v->id}}">edit</a></td>
                                            </tr>
                                            @if(!$v->serverservicequantityrange->isEmpty())
                                                @foreach($v->serverservicequantityrange as $serverservicequantityrange )
                                                    <tr>
                                                        <td width="2%"></td>

                                                        <td width="30%">Range</td>
                                                        <td>{{$serverservicequantityrange->from_range}}
                                                            - {{$serverservicequantityrange->to_range}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td width="10%">@if($v->api_id ==! null){{number_format($v->apiserverservices->credits,2)}}@elseif($v->servicepricing->purchasecost == null){{number_format($v->purchase_cost,2)}}@else{{number_format($v->servicepricing->purchasecost,2)}}@endif</td>
                                                        <td width="10%">{{number_format($v->purchase_cost,2)}}</td>
                                                        <td width="5%">@foreach($serverservicequantityrange->serverserviceusercredit as $serverserviceusercredit)@if($serverserviceusercredit->currency == 'USD')
                                                                {{number_format($serverserviceusercredit->credit,2)}}@endif @endforeach</td>
                                                        @foreach($clientgroup as $cg)
                                                            <td  width="5%">@foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
                                                                    @if($serverserviceclientgroupcredit->currency=='USD' && $serverserviceclientgroupcredit->client_group_id==$cg->id )
                                                                            @if($v->purchase_cost > $serverserviceclientgroupcredit->credit)
                                                                            <span class="badge badge-pill badge-danger">{{number_format($serverserviceclientgroupcredit->credit,2)}}<span>
                                                                            @else
                                                                            <a rel="tooltip"  data-original-title="{{number_format($serverserviceclientgroupcredit->credit*$exchangerate->exchange_rate_static)}} đ" >{{number_format($serverserviceclientgroupcredit->credit,2)}}</a>
                                                                            @endif
                                                                        @endif
                                                                @endforeach
                                                            </td>
                                                        @endforeach
                                                        <td width="1%"></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach($v->serverservicetypewiseprice as $a)
                                                    <tr>
                                                        <td>*</td>
                                                        <td>{{$a->service_type}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td width="10%">@if($v->api_id ==! null)
                                                                @foreach($v->apiserverservicetypeprice as $apiserverservicetypeprice)@if($apiserverservicetypeprice->service_type==$a->service_type)
                                                                    {{$apiserverservicetypeprice->api_price}}
                                                                @endif
                                                                @endforeach
                                                        @else{{ number_format($a->purchase_cost_not_net, 2) }}@endif</td>
                                                        <td width="10%">{{ number_format($a->purchase_cost, 2) }}</td>
                                                        <td width="5%">{{ $a->amount }}</td>
                                                        @foreach($clientgroup as $cg)
                                                            <td  width="5%">@foreach($a->serverservicetypewisegroupprice as $serverservicetypewisegroupprice)
                                                                    @if($serverservicetypewisegroupprice->service_type_id == $a->id &&$serverservicetypewisegroupprice->group_id == $cg->id)
                                                                           @if($a->purchase_cost > $serverservicetypewisegroupprice->amount)
                                                                            <span class="badge badge-pill badge-danger">{{ number_format( $serverservicetypewisegroupprice->amount , 2) }}<span>
                                                                           @else
                                                                            <a rel="tooltip"  data-original-title="{{number_format($serverservicetypewisegroupprice->amount*$exchangerate->exchange_rate_static)}} đ" >{{ number_format( $serverservicetypewisegroupprice->amount , 2) }}</a>
                                                                           @endif
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                        @endforeach

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
                    </div>
                </div>

            </div>
        </div>


    </div>





@endsection

