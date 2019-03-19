@extends('layouts.header')
@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(document).on("click", ".status", function () {
                var ida = $(this).parent().attr('id');
                var url = '';
                if ($(this).prop('checked') == true) {
                    url = 'imei/status/active';
                } else if ($(this).prop('checked') == false) {
                    url = 'imei/status/inactive';
                }
                $.ajax({
                    url: url,
                    type: 'get',
                    data: 'id=' + ida,
                    success: function (result) {
                        $.NotificationApp.send("Status", result, 'top-right', 'Background color', 'Icon');
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
        }, false);
    </script>
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Imei Order</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>Service Group</strong>
                                        <select class="form-control" name="group_name">
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
                                            <option value="api" @if($cachesearch->type == 'api')selected @endif>API
                                            </option>
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
                                            <option value="inactive"
                                                    @if($cachesearch->status == 'inactive')selected @endif>
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
                                    <div class="col-md-2">
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
                                        <a href="{{ asset('imeisales') }}?group_name={{$cachesearch->group_name}}"
                                           class="fancybox fancybox.iframe btn btn-info">Sales</a>
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
                                <th>PC (Net)</th>
                                <th>Default</th>
                                @foreach($usergroup as $u)
                                    <th>{{$u->group_name}}</th>
                                @endforeach
                                @if(CUtil::apAdmin())
                                    <th></th>
                                    <th></th>
                                @endif
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
                                                <td>{{$v->id}}</td>
                                                <td @if($v->status == 'soft_deleted' )style="text-decoration: line-through;"
                                                    @endif @if(!CUtil::apStaff())contenteditable="true" @endif
                                                    onBlur="saveToDatabase(this,'service_name','services','{{$v->id}}')"
                                                    onClick="showEdit(this);">
                                                    <a href="https://s-unlock.com/admin/imei-service/edit/{{$v->id}}"
                                                       class="max-lines @if($v->imeipricing->sale >0) badge1 @endif"
                                                       data-toggle="tooltip" data-placement="top"
                                                       data-original-title="{{$v->service_name}}"
                                                       @if($v->imeipricing->sale >0) data-badge="{{$v->imeipricing->sale}}"
                                                       @endif target="_blank">{{$v->service_name}}</a></td>
                                                <td>@if($v->api_id ==! null)<span
                                                            class="badge badge-pill badge-success">API<span>  @else<span
                                                                    class="badge badge-pill badge-info">Manual<span>  @endif
                                                </td>
                                                <td>
                                                    <div class="togglebutton">
                                                        <label id="{{$v->id}}" class="custom-toggle">
                                                            <input class="status"
                                                                   id="check{{$v->id}}" type="checkbox"
                                                                   @if($v->status == 'active' )checked="" @endif>
                                                            <span class="custom-toggle-slider rounded-circle"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>@if($v->imeipricing->nhacungcap ==! null)
                                                        @if (CUtil::issuperadmin())
                                                            {{$v->imeipricing->nhacungcap->name}}
                                                        @else
                                                            Supplier #{{$v->imeipricing->nhacungcap->id}}
                                                        @endif
                                                    @else
                                                        <a class="badge badge-pill badge-success">New</a>
                                                    @endif</td>
                                                @if($v->api_id ==! null)
                                                    <td>@if($v->apiserverservices ==! null)<a data-toggle="tooltip"
                                                                                              data-placement="top"
                                                                                              data-original-title="{{number_format($v->apiserverservices->credits*$exchangerate->exchange_rate_static)}} đ">{{number_format($v->apiserverservices->credits, 2)}}</a>@endif
                                                    </td>
                                                @else
                                                    <td><a data-toggle="tooltip" data-placement="top"
                                                           data-original-title="{{number_format($v->imeipricing->purchasecost*$exchangerate->exchange_rate_static)}} đ">{{ number_format($v->imeipricing->purchasecost, 2)}}</a>
                                                    </td>
                                                @endif


                                                <td><a data-toggle="tooltip" data-placement="top"
                                                       data-original-title="{{number_format($v->purchase_cost*$exchangerate->exchange_rate_static)}} đ">{{number_format($v->purchase_cost, 2)}}</a>
                                                </td>
                                                <td @if(!CUtil::apStaff())contenteditable="true" @endif
                                                onBlur="saveToDatabase(this,'credit','services','{{$v->id}}')"
                                                    onClick="showEdit(this);">
                                                    @if($v->purchase_cost == $v->credit)
                                                        <span class="badge badge-pill badge-warning">{{number_format($v->credit, 2)}}<span>
                                                @elseif($v->purchase_cost > $v->credit)
                                                                    <span class="badge badge-pill badge-danger">{{number_format($v->credit, 2)}}<span>
                                                   @else
                                                                                <a data-toggle="tooltip"
                                                                                   data-placement="top"
                                                                                   data-original-title="{{number_format($v->credit*$exchangerate->exchange_rate_static)}} đ">{{ number_format($v->credit, 2) }}</a>
                                                    @endif
                                                </td>
                                                @foreach($usergroup as $u)
                                                    <td @if(!CUtil::apStaff())contenteditable="true" @endif
                                                    onBlur="saveToDatabase(this,'discount','price','{{$v->id}}','{{$u->id}}')"
                                                        onClick="showEdit(this);">
                                                        @foreach($v->clientgroupprice->where('currency',$currenciessite->config_value)->where('service_type','imei')->where('group_id',$u->id) as $cl)
                                                            @if(round($v->purchase_cost,2) == round($v->credit + $cl->discount,2))
                                                                <span class="badge badge-pill badge-warning"><a
                                                                            data-toggle="tooltip" data-placement="top"
                                                                            data-original-title="{{number_format(($v->credit + $cl->discount)*$exchangerate->exchange_rate_static)}} đ">@if($cachesearch->currency == $exchangerate->currency_code) {{number_format(($v->credit + $cl->discount)*$exchangerate->exchange_rate_static)}}  @else{{round($v->credit + $cl->discount,2)}}@endif</a><span>
                                                            @elseif(round($v->purchase_cost,4) > round($v->credit + $cl->discount,4))
                                                                            <span class="badge badge-pill badge-danger"><a
                                                                                        data-toggle="tooltip"
                                                                                        data-placement="top"
                                                                                        data-original-title="{{number_format(($v->credit + $cl->discount)*$exchangerate->exchange_rate_static)}} đ">@if($cachesearch->currency == $exchangerate->currency_code) {{number_format(($v->credit + $cl->discount)*$exchangerate->exchange_rate_static)}}  @else{{round($v->credit + $cl->discount,2)}}@endif</a><span>
                                                                    @else
                                                                                        <a data-toggle="tooltip"
                                                                                           data-placement="top"
                                                                                           data-original-title="{{number_format(($v->credit + $cl->discount)*$exchangerate->exchange_rate_static)}} đ">@if($cachesearch->currency == $exchangerate->currency_code) {{number_format(($v->credit + $cl->discount)*$exchangerate->exchange_rate_static)}}  @else{{round($v->credit + $cl->discount,2)}}@endif</a>
                                                            @endif
                                                        @endforeach</td>
                                                @endforeach
                                                @if(!CUtil::apStaff() )
                                                    <td>@if($v->status == 'active')<a
                                                                class="fancybox fancybox.iframe"
                                                                href="{{ asset('') }}imei/{{$v->id}}"><i class="mdi mdi-eye"></i></a>@endif</td>
                                                    <td><a href="{{ asset('') }}imeidelete/{{$v->id}}"
                                                           onclick="return confirm('OK to delete!');"><i class="mdi mdi-delete"></i></a></td>
                                                @endif
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
            <script>
                function showEdit(editableObj) {
                    $(editableObj).css("background", "#FFF");
                }

                function saveToDatabase(editableObj, column, type, id, idgr) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $(editableObj).css("background", "#FFF url(loaderIcon.gif) no-repeat right");
                    $.ajax({
                        url: "imeiquickedit",
                        type: "POST",
                        data: {column: column, type: type, value: editableObj.innerText, id: id, idgr: idgr},
                        success: function (data) {
                            console.log(data);
                            $(editableObj).css("background", "#FDFDFD");
                        }
                    });
                }
            </script>
@endsection
