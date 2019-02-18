@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Service Group</strong>
                            <select class="form-control form-control-alternative" name="group_name">
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
                <div class="card-body">
                    <table id="" class="table align-items-center table-flush">
                        <thead class="text-primary" id="myHeader">
                        <th></th>
                        <th>Service Name</th>
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
                                <td colspan="4"><strong style="font-weight:700;">{{$g->group_name}}</strong></td>
                                @foreach($clientgroup as $cg)
                                    <td></td>
                                @endforeach
                            </tr>
                            @foreach($serverservice as $v)
                                @if($v->server_service_group_id == $g->id )
                                    <tr class="table-info">
                                        <td>{{$v->id}}</td>
                                        <td contenteditable="true"
                                            onBlur="saveName(this,'services','service_name','{{$v->id}}')"
                                            onClick="showEdit(this);">{{$v->service_name}}</td>
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
                                                <td>{{$a->id}}</td>
                                                <td colspan="1"><a>{{$a->service_type}}</a></td>
                                                @if($v->api_id ==! null)
                                                    @foreach($v->apiserverservicetypeprice as $apiserverservicetypeprice)@if($apiserverservicetypeprice->service_type==$a->service_type)
                                                        <td>{{$apiserverservicetypeprice->api_price}}</td>
                                                    @endif
                                                    @endforeach
                                                @else{{ round($a->purchase_cost_not_net, 2) }}@endif
                                                <td>{{ round($a->purchase_cost, 2) }}</td>
                                                <td contenteditable="true"
                                                    onBlur="savePrice(this,'creditwise','amount','{{$a->id}}')"
                                                    onClick="showEdit(this);">{{ round( $a->amount , 2) }}</td>
                                                @foreach($clientgroup as $cg)
                                                    @foreach($a->serverservicetypewisegroupprice->where('service_type_id',$a->id)->where('group_id',$cg->id) as $serverservicetypewisegroupprice)
                                                        <td contenteditable="true"
                                                            onBlur="savePrice(this,'pricewise','amount','{{$serverservicetypewisegroupprice->id}}')"
                                                            onClick="showEdit(this);">{{ round( $serverservicetypewisegroupprice->amount , 2) }}</td>
                                                    @endforeach

                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach($v->serverservicequantityrange as $serverservicequantityrange )
                                            <tr>

                                                <td></td>
                                                <td>{{$serverservicequantityrange->from_range}}
                                                    - {{$serverservicequantityrange->to_range}}</td>
                                                @if($v->api_id ==! null)
                                                    <td>{{round($v->apiserverservices->credits,2)}}</td>
                                                @elseif($v->servicepricing->purchasecost == null)
                                                    <td>{{round($v->purchase_cost,2)}}</td>
                                                @else
                                                    <td>{{round($v->servicepricing->purchasecost,2)}}</td>
                                                @endif
                                                <td>{{round($v->purchase_cost,2)}}</td>
                                                @foreach($serverservicequantityrange->serverserviceusercredit->where('currency',$currenciessite->config_value) as $serverserviceusercredit)
                                                    <td contenteditable="true"
                                                        onBlur="savePrice(this,'creditrange','amount','{{$serverserviceusercredit->id}}')"
                                                        onClick="showEdit(this);">{{round($serverserviceusercredit->credit,2)}}</td>
                                                @endforeach
                                                @foreach($clientgroup as $cg)
                                                    @foreach($serverservicequantityrange->serverserviceclientgroupcredit->where('currency',$currenciessite->config_value)->where('client_group_id',$cg->id) as $serverserviceclientgroupcredit)
                                                        <td contenteditable="true"
                                                            onBlur="savePrice(this,'pricerange','amount','{{$serverserviceclientgroupcredit->id}}')"
                                                            onClick="showEdit(this);">{{round($serverserviceclientgroupcredit->credit,2)}}</td>
                                                    @endforeach

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

            </div>
        </div>
    </div>


    <script>
        function showEdit(editableObj) {
            $(editableObj).css("background", "#FFF");
        }

        function saveName(editableObj, type, column, id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(editableObj).css("background", "#FFF url(loaderIcon.gif) no-repeat right");
            $.ajax({
                url: "serverquickedit",
                type: "POST",
                data: { column: column, type : type, value : editableObj.innerText, id : id} ,
                success: function (data) {
                    console.log(data);
                    $(editableObj).css("background", "#a2e5fd");
                }
            });
        }

        function savePrice(editableObj, type, column, id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(editableObj).css("background", "#FFF url(loaderIcon.gif) no-repeat right");
            $.ajax({
                url: "serverquickedit",
                type: "POST",
                data: { column: column, type : type, value : editableObj.innerText, id : id} ,
                success: function (data) {
                    console.log(data);
                    $(editableObj).css("background", "#a2e5fd");
                }
            });
        }
    </script>



@endsection

