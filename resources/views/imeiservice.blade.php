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
    <style>
        .max-lines {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            max-width: 150px;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical
        }
        /*.sticky {*/
            /*position: fixed;*/
            /*top: 0;*/
        /*}*/
        /*#myHeader {*/
            /*background: #fff;*/
            /*z-index: 10;*/
        /*}*/
    </style>
    <!-- Page content -->
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0">IMEI</h3>
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
                            <th>PC (Net)</th>
                            <th>Default</th>
                            @foreach($usergroup as $u)
                                <th>{{$u->group_name}}</th>
                            @endforeach
                            <th></th>
                            <th></th>
                            </thead>
                            <tbody>
                            @foreach($group->where('imeigroup','<>','') as $g)
                                <tr class="table-warning">
                                    <td><i class="ni ni-ungroup"></i></td>
                                    <td colspan="9"><strong style="font-weight:700;">{{$g->group_name}}</strong></td>
                                    @foreach($usergroup as $u)
                                        <td></td>
                                    @endforeach
                                </tr>
                                @foreach($imei_service as $v)
                                    @if($v->imei_service_group_id == $g->id )
                                        <tr>
                                            <td >{{$v->id}}</td>
                                            <td @if($v->status == 'soft_deleted' )style="text-decoration: line-through;"@endif><a href="https://s-unlock.com/admin/imei-service/edit/{{$v->id}}" class="max-lines" data-toggle="tooltip" data-placement="top"  data-original-title="{{$v->service_name}}" target="_blank">{{$v->service_name}}</a> </td>
                                            <td>@if($v->api_id ==! null)<span
                                                        class="badge badge-pill badge-success">API<span>  @else<span
                                                                class="badge badge-pill badge-info">Manual<span>  @endif
                                            </td>
                                            <td>
                                                <div class="togglebutton">
                                                    <label id="{{$v->id}}" class="custom-toggle">
                                                        <input onfocus="window.location.reload()" class="status" id="check{{$v->id}}" type="checkbox" @if($v->status == 'active' )checked="" @endif>
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>@if($v->imeipricing->nhacungcap ==! null){{$v->imeipricing->nhacungcap->name}}@endif</td>
                                            @if($v->api_id ==! null)
                                                <td>@if($v->apiserverservices ==! null)<a data-toggle="tooltip" data-placement="top"  data-original-title="{{number_format($v->apiserverservices->credits*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->apiserverservices->credits, 2); ?></a>@endif</td>
                                            @else
                                                <td><a data-toggle="tooltip" data-placement="top"  data-original-title="{{number_format($v->imeipricing->purchasecost*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->imeipricing->purchasecost, 2); ?></a></td>
                                            @endif


                                            <td><a data-toggle="tooltip" data-placement="top"  data-original-title="{{number_format($v->purchase_cost*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->purchase_cost, 2); ?></a></td>
                                            <td>
                                                @if($v->purchase_cost == $v->credit)
                                                    <span class="badge badge-pill badge-warning">{{number_format($v->credit, 2)}}<span>
                                                @elseif($v->purchase_cost > $v->credit)
                                                                <span class="badge badge-pill badge-danger">{{number_format($v->credit, 2)}}<span>
                                                   @else
                                                                <a data-toggle="tooltip" data-placement="top" data-original-title="{{number_format($v->credit*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->credit, 2); ?></a>
                                                @endif
                                            </td>
                                            @foreach($usergroup as $u)
                                                <td>  @foreach($v->clientgroupprice as $cl)
                                                        @if($cl->currency == 'USD' && $cl->service_type == 'imei' && $cl->group_id == $u->id )
                                                            @if($v->purchase_cost == $v->credit + $cl->discount)
                                                                <span class="badge badge-pill badge-warning"><?php echo number_format($v->credit + $cl->discount, 2); ?><span>
                                                            @elseif($v->purchase_cost > $v->credit + $cl->discount)
                                                                            <span class="badge badge-pill badge-danger"><?php echo number_format($v->credit + $cl->discount, 2); ?><span>
                                                                    @else
                                                                            <a data-toggle="tooltip" data-placement="top" data-original-title="{{number_format(($v->credit + $cl->discount)*$exchangerate->exchange_rate_static)}} đ" ><?php echo number_format($v->credit + $cl->discount, 2); ?></a>
                                                            @endif
                                                        @endif
                                                    @endforeach</td>
                                            @endforeach

                                            <td>@if($v->status == 'active')<a
                                                        class="material-icons fancybox fancybox.iframe"
                                                        href="{{ asset('') }}imei/{{$v->id}}"><i class="ni ni-zoom-split-in"></i></a>@endif</td>
                                            <td><a href="{{ asset('') }}imeidelete/{{$v->id}}" onclick="return confirm('OK to delete!');"><i class="ni ni-fat-remove"></i></a></td>
                                        </tr>
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
