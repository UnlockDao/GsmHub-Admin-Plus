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
                        $.notify({icon: "notifications", message: result});
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
                        <div class="card-icon">
                            <i class="material-icons">monetization_on</i>
                        </div>
                        <button type="button" onclick="tableToExcel('testTable', 'W3C Example Table')"
                                class="btn btn-info pull-right"><i class="material-icons">cloud_download</i>
                        </button>
                        <h3 class="card-title ">Server Services</h3>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-full-width table-hover">
                            <table id="testTable" class="table table-striped">
                                <thead class="text-primary">
                                <th width="2%"></th>
                                <th>Service Name</th>
                                <th>Service Type</th>
                                <th>Status</th>
                                <th>Supplier</th>
                                <th>Purchase Cost</th>
                                <th>Purchase Cost (VIP)</th>
                                @foreach($clientgroup as $cg)
                                    <th>{{$cg->group_name}}</th>
                                @endforeach
                                <th width="1%">Edit</th>
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
                                    </tr>
                                    @foreach($serverservice as $v)
                                        @if($v->server_service_group_id == $g->id )
                                            <tr class="table-info">
                                                <td>{{$v->id}}</td>
                                                <td width="30%">{{$v->service_name}}</td>
                                                <td>@if($v->api_id ==! null)<span
                                                            class="badge badge-pill badge-success">API<span>  @else<span
                                                                    class="badge badge-pill badge-info">Manual<span>  @endif
                                                </td>
                                                <td>
                                                    <div class="togglebutton">
                                                        <label id="{{$v->id}}">
                                                            <input class="status" id="check{{$v->id}}" type="checkbox"
                                                                   @if($v->status == 'active' )checked="" @endif>
                                                            <span class="toggle"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>@if($v->servicepricing->nhacungcap ==! null){{$v->servicepricing->nhacungcap->name}}@endif</td>
                                                <td>@if($v->api_id ==! null){{number_format($v->apiserverservices->credits,2)}}@elseif($v->servicepricing->purchasecost == null){{number_format($v->purchase_cost,2)}}@else{{number_format($v->servicepricing->purchasecost,2)}}@endif</td>
                                                <td>{{number_format($v->purchase_cost,2)}}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><a class="material-icons fancybox fancybox.iframe"
                                                       href="{{ asset('') }}serverservice/{{$v->id}}">edit</a></td>
                                            </tr>
                                            @if(!$v->serverservicequantityrange->isEmpty())
                                                @foreach($v->serverservicequantityrange as $serverservicequantityrange )
                                                    <tr>
                                                        <td></td>

                                                        <td>Range</td>
                                                        <td>{{$serverservicequantityrange->from_range}}
                                                            - {{$serverservicequantityrange->to_range}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        @foreach($clientgroup as $cg)
                                                            <td>@foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
                                                                    @if($serverserviceclientgroupcredit->currency=='USD' && $serverserviceclientgroupcredit->client_group_id==$cg->id )
                                                                        {{number_format($serverserviceclientgroupcredit->credit,2)}}@endif
                                                                @endforeach
                                                            </td>
                                                        @endforeach
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach($v->serverservicetypewiseprice as $a)
                                                    <?php $server_service_type_wise_groupprice = DB::table('server_service_type_wise_groupprice')
                                                        ->where('service_type_id', $a->id)
                                                        ->where('server_service_id', $v->id)
                                                        ->get();
                                                    ?>
                                                    <tr>
                                                        <td>*</td>
                                                        <td>{{$a->service_type}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>@foreach($v->apiserverservicetypeprice as $apiserverservicetypeprice)
                                                                @if($apiserverservicetypeprice->service_type == $a->service_type)
                                                                    {{$apiserverservicetypeprice->api_price}}
                                                                @endif
                                                            @endforeach</td>
                                                        <td>{{ number_format($a->purchase_cost, 2) }}</td>
                                                        @foreach($clientgroup as $cg)
                                                            <td> @foreach($server_service_type_wise_groupprice as $s)
                                                                    @if($s->group_id==$cg->id)
                                                                        {{ number_format($s->amount, 2) }}
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

