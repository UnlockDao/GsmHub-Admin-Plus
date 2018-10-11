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
                    <div class="card-header border-0">
                        <h3 class="mb-0">Server</h3>
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Service Group</strong>
                                    <select class="form-control form-control-alternative" name="group_name">
                                        <option value="">...</option>
                                        @foreach($groupsearch as $g )
                                            <option value="{{$g->id}}" @if($cachesearch->group_name == $g->id) selected @endif>{{$g->group_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <strong>Service Type</strong>
                                    <select class="form-control form-control-alternative" name="type">
                                        <option value="">...</option>
                                        <option value="api" @if($cachesearch->type == 'api')selected @endif>API</option>
                                        <option value="manual" @if($cachesearch->type == 'manual')selected @endif>MANUAL</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <strong>Status</strong>
                                    <select class="form-control form-control-alternative" name="status">
                                        <option value="">...</option>
                                        <option value="active" @if($cachesearch->status == 'active')selected @endif>Active</option>
                                        <option value="inactive" @if($cachesearch->status == 'inactive')selected @endif>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <strong>Supplier</strong>
                                    <select class="form-control form-control-alternative" name="supplier">
                                        <option value="">...</option>
                                        @foreach($supplier as $s)
                                            <option value="{{$s->id}}" @if($cachesearch->supplier == $s->id)selected @endif>{{$s->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <br>
                                    <button class="btn btn-info" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
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
                                            <td><a href="https://s-unlock.com/admin/server-service/edit/{{$v->id}}" class="max-lines" target="_blank">{{$v->service_name}}</a> </td>
                                            <td>@if($v->api_id ==! null)<span
                                                        class="badge badge-pill badge-success">API<span>  @else<span
                                                                class="badge badge-pill badge-info">Manual<span>  @endif
                                            </td>
                                            <td>
                                                <div class="togglebutton">
                                                    <label id="{{$v->id}}" class="custom-toggle">
                                                        <input class="status" id="check{{$v->id}}" type="checkbox" onfocus="window.location.reload()"
                                                               @if($v->status == 'active' )checked="" @endif>
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>@if($v->servicepricing->nhacungcap ==! null){{$v->servicepricing->nhacungcap->name}}@endif</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @foreach($clientgroup as $cg)
                                                <td></td>
                                            @endforeach
                                            <td><a class="material-icons fancybox fancybox.iframe"
                                                              href="{{ asset('') }}serverservice/{{$v->id}}"><i class="ni ni-zoom-split-in"></i></a></td>
                                            <td><a href="{{ asset('') }}serverdelete/{{$v->id}}" onclick="return confirm('OK to delete!');"><i class="ni ni-fat-remove"></i></a></td>
                                        </tr>
                                        @if(!$v->serverservicetypewiseprice->isEmpty())
                                            @foreach($v->serverservicetypewiseprice as $a)
                                                <tr>
                                                    <td>*</td>
                                                    <td colspan="4"><a>{{$a->service_type}}</a></td>
                                                    <td>@if($v->api_id ==! null)
                                                            @foreach($v->apiserverservicetypeprice as $apiserverservicetypeprice)@if($apiserverservicetypeprice->service_type==$a->service_type)
                                                                {{$apiserverservicetypeprice->api_price}}
                                                            @endif
                                                            @endforeach
                                                        @else{{ number_format($a->purchase_cost_not_net, 2) }}@endif</td>
                                                    <td>{{ number_format($a->purchase_cost, 2) }}</td>
                                                    <td>
                                                        @if($a->purchase_cost > $a->amount)
                                                            <span class="badge badge-pill badge-danger">{{ number_format( $a->amount , 2) }}<span>
                                                        @else
                                                            {{ number_format( $a->amount , 2) }}
                                                        @endif
                                                    </td>
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
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach($v->serverservicequantityrange as $serverservicequantityrange )
                                                <tr>
                                                    <td></td>

                                                    <td>Range</td>
                                                    <td>{{$serverservicequantityrange->from_range}}
                                                        - {{$serverservicequantityrange->to_range}}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>@if($v->api_id ==! null){{number_format($v->apiserverservices->credits,2)}}@elseif($v->servicepricing->purchasecost == null){{number_format($v->purchase_cost,2)}}@else{{number_format($v->servicepricing->purchasecost,2)}}@endif</td>
                                                    <td>{{number_format($v->purchase_cost,2)}}</td>
                                                    <td>@foreach($serverservicequantityrange->serverserviceusercredit as $serverserviceusercredit)
                                                            @if($serverserviceusercredit->currency == 'USD')
                                                                @if($v->purchase_cost > $serverserviceusercredit->credit)
                                                                    <span class="badge badge-pill badge-danger">{{number_format($serverserviceusercredit->credit,2)}}<span>
                                                                @else
                                                                    {{number_format($serverserviceusercredit->credit,2)}}
                                                                @endif
                                                            @endif
                                                        @endforeach</td>
                                                    @foreach($clientgroup as $cg)
                                                        <td>@foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
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
