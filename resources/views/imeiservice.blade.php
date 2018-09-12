@extends('layouts.header')
@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(document).on("click", ".status", function () {
                var ida = $(this).parent().attr('id');
                var url = '';
                if ($(this).prop('checked') == true) {
                    url = 'imei/status/active';
                }
                else if ($(this).prop('checked') == false) {
                    url = 'imei/status/inactive';
                }
                $.ajax({
                    url: url,
                    type: 'get',
                    data: 'id=' + ida,
                    success: function (result) {
                       // $.notify({icon: "notifications", message: result});
                    },
                    error: function(result) {
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
                        <h3 class="card-title ">IMEI Services</h3>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-full-width table-hover">
                            <table class="table table-striped" >
                                <thead class="text-primary" id="myHeader">
                                <th width="2%"></th>
                                <th width="30%">Service Name</th>
                                <th width="10%">Service Type</th>
                                <th width="5%">Status</th>
                                <th width="5%">Supplier</th>
                                <th width="10%">Purchase Cost</th>
                                <th width="10%">Purchase Cost (Net)</th>
                                <th width="5%">Default</th>
                                @foreach($usergroup as $u)
                                    <th width="5%">{{$u->group_name}}</th>
                                @endforeach
                                <th width="3%"></th>
                                </thead>
                                <tbody>
                                @foreach($group as $g)
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
                                    @foreach($imei_service as $v)
                                        @if($v->imei->imei_service_group_id == $g->id )
                                            <tr>
                                                <td width="2%">{{$v->id}}</td>
                                                <td width="30%" @if($v->imei->status == 'soft_deleted' )style="text-decoration: line-through;"@endif>{{$v->imei->service_name}}</td>
                                                <td width="10%">@if($v->imei->api_id ==! null)<span
                                                            class="badge badge-pill badge-success">API<span>  @else<span
                                                                    class="badge badge-pill badge-info">Manual<span>  @endif
                                                </td>
                                                <td width="5%">
                                                    <div class="togglebutton">
                                                        <label id="{{$v->id}}">
                                                            <input onClick="window.location.reload()" class="status" id="check{{$v->id}}" type="checkbox" @if($v->imei->status == 'active' )checked="" @endif>
                                                            <span class="toggle"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="5%">@if($v->nhacungcap ==! null){{$v->nhacungcap->name}}@endif</td>
                                                @if($v->imei->api_id ==! null)
                                                    <td width="10%">@if($v->imei->apiserverservices ==! null)<a rel="tooltip"  data-original-title="{{number_format($v->imei->apiserverservices->credits*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->imei->apiserverservices->credits, 2); ?></a>@endif</td>
                                                @else
                                                    <td width="10%"><a rel="tooltip"  data-original-title="{{number_format($v->purchasecost*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->purchasecost, 2); ?></a></td>
                                                @endif


                                                <td width="10%"><a rel="tooltip"  data-original-title="{{number_format($v->imei->purchase_cost*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->imei->purchase_cost, 2); ?></a></td>
                                                <td width="5%"><a rel="tooltip" data-original-title="{{number_format($v->imei->credit*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->imei->credit, 2); ?></a></td>
                                                @foreach($usergroup as $u)
                                                    <td width="5%">  @foreach($v->imei->clientgroupprice as $cl)
                                                            @if($cl->currency == 'USD' && $cl->service_type == 'imei' && $cl->group_id == $u->id )
                                                                <a rel="tooltip" data-original-title="{{number_format(($v->imei->credit + $cl->discount)*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->imei->credit + $cl->discount, 2); ?></a>
                                                            @endif
                                                        @endforeach</td>
                                                @endforeach

                                                <td width="2%">@if($v->imei->status == 'active')<a
                                                            class="material-icons fancybox fancybox.iframe"
                                                            href="{{ asset('') }}imei/{{$v->id}}">edit</a>@endif</td>
                                            </tr>
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

