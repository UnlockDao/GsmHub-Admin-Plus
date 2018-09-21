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
                        <button type="button" onfocus="tableToExcel('testTable', 'W3C Example Table')"
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
                                <th></th>
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
                                        @foreach($usergroup as $u)
                                            <td></td>
                                        @endforeach
                                    </tr>
                                    @foreach($imei_service as $v)
                                        @if($v->imei_service_group_id == $g->id )
                                            <tr>
                                                <td width="2%">{{$v->id}}</td>
                                                <td width="30%" @if($v->status == 'soft_deleted' )style="text-decoration: line-through;"@endif><a href="https://s-unlock.com/admin/imei-service/edit/{{$v->id}}" target="_blank">{{$v->service_name}}</a> </td>
                                                <td width="10%">@if($v->api_id ==! null)<span
                                                            class="badge badge-pill badge-success">API<span>  @else<span
                                                                    class="badge badge-pill badge-info">Manual<span>  @endif
                                                </td>
                                                <td width="5%">
                                                    <div class="togglebutton">
                                                        <label id="{{$v->id}}">
                                                            <input onfocus="window.location.reload()" class="status" id="check{{$v->id}}" type="checkbox" @if($v->status == 'active' )checked="" @endif>
                                                            <span class="toggle"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="5%">@if($v->imeipricing->nhacungcap ==! null){{$v->imeipricing->nhacungcap->name}}@endif</td>
                                                @if($v->api_id ==! null)
                                                    <td width="10%">@if($v->apiserverservices ==! null)<a rel="tooltip"  data-original-title="{{number_format($v->apiserverservices->credits*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->apiserverservices->credits, 2); ?></a>@endif</td>
                                                @else
                                                    <td width="10%"><a rel="tooltip"  data-original-title="{{number_format($v->purchasecost*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->purchasecost, 2); ?></a></td>
                                                @endif


                                                <td width="10%"><a rel="tooltip"  data-original-title="{{number_format($v->purchase_cost*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->purchase_cost, 2); ?></a></td>
                                                <td width="5%">
                                                    @if($v->purchase_cost > $v->credit)
                                                        <span class="badge badge-pill badge-danger">{{number_format($v->credit, 2)}}<span>
                                                   @else
                                                                    <a rel="tooltip" data-original-title="{{number_format($v->credit*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->credit, 2); ?></a>
                                                    @endif
                                                </td>
                                                @foreach($usergroup as $u)
                                                    <td width="5%">  @foreach($v->clientgroupprice as $cl)
                                                            @if($cl->currency == 'USD' && $cl->service_type == 'imei' && $cl->group_id == $u->id )
                                                                @if($v->purchase_cost > $v->credit + $cl->discount)
                                                                    <span class="badge badge-pill badge-danger"><?php echo number_format($v->credit + $cl->discount, 2); ?><span>
                                                                @else
                                                                                <a rel="tooltip" data-original-title="{{number_format(($v->credit + $cl->discount)*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->credit + $cl->discount, 2); ?></a>
                                                                @endif
                                                            @endif
                                                        @endforeach</td>
                                                @endforeach

                                                <td width="2%">@if($v->status == 'active')<a
                                                            class="material-icons fancybox fancybox.iframe"
                                                            href="{{ asset('') }}imei/{{$v->id}}">edit</a>@endif</td>
                                                <td><a href="{{ asset('') }}imeidelete/{{$v->id}}" onclick="return confirm('OK to delete!');"><i class="material-icons">close</i></a></td>
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

