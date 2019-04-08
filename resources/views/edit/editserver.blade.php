@extends('layouts.app')
@section('content')
    @if($serverservice->servicepricing->id_supplier == null)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
                            <h4 class="card-title ">Edit {{$serverservice->service_name}} </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('updatesupplierserver') }}/{{$serverservice->id}}" method="POST"
                                  enctype="multipart/form-data"
                                  onsubmit="return checkForm(this);">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <strong>Supplier</strong>
                                    <select name="id_supplier" class="form-control">
                                        @foreach($supplier as $v)
                                            <option value="{{$v->id}}"
                                                    @if($serverservice->servicepricing->id_supplier == $v->id) selected @endif>{{$v->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input class="btn btn-primary pull-right" type="submit" value="Save">
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @else
        <!-- service-->
        @if(!$serverservice->serverservicetypewiseprice->isEmpty())
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-icon card-header-rose">
                                <h4 class="card-title ">Edit {{$serverservice->service_name}}</h4>
                            </div>
                            <div class="card-body">
                                <form action="#">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>Name Server Services</strong>
                                            <input type="text" name="service_name" id="service_name"
                                                   class="form-control"
                                                   value="{{$serverservice->service_name}}"
                                                   onBlur="saveToDatabase(this,'service_name','services','{{$serverservice->id}}')"
                                                   onClick="showEdit(this);"
                                                   placeholder="Name IMEI Services" autocomplete="off">
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Service Listed In Group</strong>
                                            <select name="service_group" class="form-control"
                                                    onBlur="saveToDatabase(this,'service_group','services','{{$serverservice->id}}')"
                                                    onClick="showEdit(this);">
                                                @foreach($group as $v)
                                                    <option value="{{$v->id}}"
                                                            @if($serverservice->server_service_group_id == $v->id) selected @endif>{{$v->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Supplier</strong>
                                            <select name="id_supplier" class="form-control"
                                                    onchange="saveToDatabase(this,'id_supplier','services','{{$serverservice->id}}')"
                                                    onClick="showEdit(this);">
                                                @foreach($supplier as $v)
                                                    <option value="{{$v->id}}"
                                                            @if($serverservice->servicepricing->id_supplier == $v->id) selected @endif>{{$v->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <strong>Delivery Time</strong>
                                            <input type="text" name="process_time" id="process_time" class="form-control"
                                                   value="{{$serverservice->process_time}}"
                                                   onBlur="saveToDatabase(this,'process_time','services','{{$serverservice->id}}')"
                                                   onClick="showEdit(this);"
                                                   placeholder="process_time" autocomplete="off">
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Time</strong>
                                            <select name="time_unit" class="form-control"
                                                    onchange="saveToDatabase(this,'time_unit','services','{{$serverservice->id}}')"
                                                    onClick="showEdit(this);">
                                                <option value="Minutes"
                                                        @if($serverservice->time_unit == 'Minutes') selected @endif>Minutes
                                                </option>
                                                <option value="Hours"
                                                        @if($serverservice->time_unit == 'Hours') selected @endif>Hours
                                                </option>
                                                <option value="Weeks"
                                                        @if($serverservice->time_unit == 'Weeks') selected @endif>Weeks
                                                </option>
                                                <option value="Days" @if($serverservice->time_unit == 'Days') selected @endif>
                                                    Days
                                                </option>
                                                <option value="Months"
                                                        @if($serverservice->time_unit == 'Months') selected @endif>Months
                                                </option>
                                                <option value="Instant"
                                                        @if($serverservice->time_unit == 'Instant') selected @endif>Instant
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="table-responsive table-full-width table-hover">
                                        <table id="testTable" class="table table-striped">
                                            <thead class="text-primary">
                                            <th width="2%">ID</th>
                                            <th>Type</th>
                                            <th>Purchase Cost</th>
                                            <th>PC (Net)</th>
                                            <th>Credit</th>
                                            @foreach($clientgroup as $cg)
                                                <th>{{$cg->group_name}}</th>
                                            @endforeach
                                            </thead>
                                            <tbody>
                                            @foreach($serverservice->serverservicetypewiseprice as $a)
                                                <tr>
                                                    <td>{{$a->id}}</td>
                                                    <td>{{$a->service_type}}</td>
                                                    <td>@if($serverservice->api_id ==! null)
                                                            @foreach($a->apiservicetypewisepriceid as $apiserverservicetypeprice)
                                                                @if($apiserverservicetypeprice->id == $a->api_service_type_wise_price_id)
                                                                    <input
                                                                            id="purchase_cost_{{$a->id}}"
                                                                            class="form-control"
                                                                            name="purchase_cost_{{$a->id}}"
                                                                            type="text" onchange="Purchasenet();"
                                                                            autocomplete="off" readonly
                                                                            value="{{$apiserverservicetypeprice->api_price}}">
                                                                @endif
                                                            @endforeach</td>
                                                    @else
                                                        <input
                                                                id="purchase_cost_{{$a->id}}"
                                                                class="form-control"
                                                                name="purchase_cost_not_net_{{$a->id}}"
                                                                type="text" onchange="Purchasenet();"
                                                                autocomplete="off"
                                                                value="{{ number_format($a->purchase_cost_not_net, 2) }}">
                                                    @endif
                                                    <td><input
                                                                id="purchase_cost_vip_{{$a->id}}"
                                                                class="form-control"
                                                                name="purchase_cost_vip_{{$a->id}}"
                                                                type="text" readonly
                                                                autocomplete="off" onchange="Chietkhau();"
                                                                value="{{ $a->purchase_cost }}"></td>
                                                    <td><input id="amount_{{$a->id}}"
                                                               class="form-control"
                                                               name="amount_{{$a->id}}"
                                                               type="text"
                                                               class="form-control"
                                                               autocomplete="off"
                                                               onBlur="savePrice(this,'creditwise','amount','{{$a->id}}')"
                                                               onClick="showEdit(this);"
                                                               value="{{ $a->amount }}"></td>
                                                    @foreach($clientgroup as $cg)
                                                        <td>@foreach($a->serverservicetypewisegroupprice as $serverservicetypewisegroupprice)
                                                                @if($serverservicetypewisegroupprice->service_type_id == $a->id &&$serverservicetypewisegroupprice->group_id == $cg->id)
                                                                    <input
                                                                            id="client_group_amount_{{$serverservicetypewisegroupprice->group_id}}_{{$a->id}}"
                                                                            class="form-control"
                                                                            name="client_group_amount_{{$serverservicetypewisegroupprice->id}}_{{$serverservicetypewisegroupprice->group_id}}"
                                                                            type="text"
                                                                            onBlur="savePrice(this,'pricewise','amount','{{$serverservicetypewisegroupprice->id}}')"
                                                                            onClick="showEdit(this);"
                                                                            @if($serverservicetypewisegroupprice->group_id == $cliendefault->id) onchange="Chietkhau();"
                                                                            onfocus="Enabled=true;Chietkhau();" @endif
                                                                            autocomplete="off"
                                                                            @if($serverservicetypewisegroupprice->group_id !== $cliendefault->id) onfocus="Enabled=false;Chietkhau();"
                                                                            @endif
                                                                            value="{{ $serverservicetypewisegroupprice->amount }}">
                                                                    <span hidden
                                                                          id="client_group_amount_{{$serverservicetypewisegroupprice->group_id}}_{{$a->id}}">{{ $serverservicetypewisegroupprice->amount }}</span>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endforeach

                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <input class="btn btn-primary pull-right" type="button" value="Save">
                                    <input class="btn btn-danger pull-right" type="button"
                                           onfocus="parent.$.fancybox.close();" value="Close">
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                //gọi dữ liệu nhà cung cấp (tỉ giá, phí )
                var tipurchasecost = {{$serverservice->servicepricing->nhacungcap->exchangerate}};
                var transactionfeegd = {{$serverservice->servicepricing->nhacungcap->transactionfee}};
                var exchangerategoc = {{$exchangerate->exchange_rate_static}};

                function Purchasenet() {
                            @foreach($serverservice->serverservicetypewiseprice as $a)
                    var purchase_cost_{{$a->id}} = document.getElementById("purchase_cost_{{$a->id}}").value;
                    var giatransactionfee = (tipurchasecost * purchase_cost_{{$a->id}}) / exchangerategoc + ((purchase_cost_{{$a->id}} / 100) * transactionfeegd);
                    document.getElementById('purchase_cost_vip_{{$a->id}}').value = giatransactionfee.toFixed(4);
                    @endforeach
                }

                var Enabled = true;

                function Chietkhau() {
                    if (Enabled == true) {
                                @foreach($serverservice->serverservicetypewiseprice as $a)
                        var purchase_cost_vip_{{$a->id}} = document.getElementById("purchase_cost_vip_{{$a->id}}").value;
                        var priceuser_{{$a->id}} = document.getElementById("client_group_amount_{{$cliendefault->id}}_{{$a->id}}").value;

                                @foreach($clientgroup as $cg)
                                @foreach($a->serverservicetypewisegroupprice as $serverservicetypewisegroupprice)
                                @if($serverservicetypewisegroupprice->service_type_id == $a->id &&$serverservicetypewisegroupprice->group_id == $cg->id)
                        var client_group_amount_{{$cg->id}}_{{$serverservicetypewisegroupprice->id}} = (priceuser_{{$a->id}} - (((priceuser_{{$a->id}} - purchase_cost_vip_{{$a->id}}) / 100) *{{$cg->chietkhau}}));
                        @if($cg->id !== $cliendefault->id)
                        document.getElementById('client_group_amount_{{$serverservicetypewisegroupprice->group_id}}_{{$a->id}}').value = client_group_amount_{{$cg->id}}_{{$serverservicetypewisegroupprice->id}}.toFixed(4);
                        @endif
                        @endif
                        @endforeach
                        @endforeach
                        @endforeach
                    }
                }

                document.addEventListener('DOMContentLoaded', function () {
                }, false);
            </script>
        @else
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ url('serverservice') }}/{{$serverservice->id}}" method="POST"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>Name Server Services</strong>
                                            <input type="text" name="service_name" id="service_name"
                                                   class="form-control"
                                                   value="{{$serverservice->service_name}}"
                                                   onBlur="saveToDatabase(this,'service_name','services','{{$serverservice->id}}')"
                                                   onClick="showEdit(this);"
                                                   placeholder="Name IMEI Services" autocomplete="off">
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Service Listed In Group</strong>
                                            <select name="service_group" class="form-control"
                                                    onBlur="saveToDatabase(this,'service_group','services','{{$serverservice->id}}')"
                                                    onClick="showEdit(this);">
                                                @foreach($group as $v)
                                                    <option value="{{$v->id}}"
                                                            @if($serverservice->server_service_group_id == $v->id) selected @endif>{{$v->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Supplier</strong>
                                            <select name="id_supplier" class="form-control"
                                                    onchange="saveToDatabase(this,'id_supplier','services','{{$serverservice->id}}')"
                                                    onClick="showEdit(this);">
                                                @foreach($supplier as $v)
                                                    <option value="{{$v->id}}"
                                                            @if($serverservice->servicepricing->id_supplier == $v->id) selected @endif>{{$v->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <strong>Delivery Time</strong>
                                            <input type="text" name="process_time" id="process_time" class="form-control"
                                                   value="{{$serverservice->process_time}}"
                                                   onBlur="saveToDatabase(this,'process_time','services','{{$serverservice->id}}')"
                                                   onClick="showEdit(this);"
                                                   placeholder="process_time" autocomplete="off">
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Time</strong>
                                            <select name="time_unit" class="form-control"
                                                    onchange="saveToDatabase(this,'time_unit','services','{{$serverservice->id}}')"
                                                    onClick="showEdit(this);">
                                                <option value="Minutes"
                                                        @if($serverservice->time_unit == 'Minutes') selected @endif>Minutes
                                                </option>
                                                <option value="Hours"
                                                        @if($serverservice->time_unit == 'Hours') selected @endif>Hours
                                                </option>
                                                <option value="Weeks"
                                                        @if($serverservice->time_unit == 'Weeks') selected @endif>Weeks
                                                </option>
                                                <option value="Days" @if($serverservice->time_unit == 'Days') selected @endif>
                                                    Days
                                                </option>
                                                <option value="Months"
                                                        @if($serverservice->time_unit == 'Months') selected @endif>Months
                                                </option>
                                                <option value="Instant"
                                                        @if($serverservice->time_unit == 'Instant') selected @endif>Instant
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5>Purchase Cost : </h5>
                                        </div>
                                        <div class="col-md-3">
                                            <input autocomplete="off" id="purchasecost" class="form-control"
                                                   name="purchase_cost" type="text" onchange="Purchasenet();"
                                                   @if($serverservice->api_id ==! null) readonly @endif
                                                   value="@if($serverservice->api_id ==! null){{$serverservice->apiserverservices->credits}}@elseif($serverservice->servicepricing->purchasecost == null){{number_format($serverservice->purchase_cost,2)}}@else{{number_format($serverservice->servicepricing->purchasecost,2)}}@endif">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5>Purchase Cost (Net) : </h5>
                                        </div>
                                        <div class="col-md-3">
                                            <input id="purchasenet" class="form-control" name="purchasenet" type="text"
                                                   value="{{number_format($serverservice->purchase_cost,2)}}" readonly>
                                        </div>
                                    </div>
                                    <div class="table-responsive table-full-width table-hover">
                                        <table id="testTable" class="table table-striped">
                                            <thead class="text-primary">
                                            <th>ID</th>
                                            <th>Range</th>
                                            <th>Credit</th>
                                            @foreach($clientgroup as $cg)
                                                <th>{{$cg->group_name}}</th>
                                            @endforeach
                                            </thead>
                                            <tbody>
                                            @foreach($serverservice->serverservicequantityrange as $serverservicequantityrange )
                                                <tr>
                                                    <td>{{$serverservicequantityrange->id}}</td>
                                                    <td>{{$serverservicequantityrange->from_range}}
                                                        - {{$serverservicequantityrange->to_range}}</td>
                                                    <td>@foreach($serverservicequantityrange->serverserviceusercredit as $serverserviceusercredit)@if($serverserviceusercredit->currency == $currenciessite->config_value)
                                                            <input class="form-control"
                                                                   name="credit_{{$serverservicequantityrange->id}}"
                                                                   id="credit_{{$serverservicequantityrange->id}}"
                                                                   autocomplete="off"
                                                                   value="{{$serverserviceusercredit->credit}}"
                                                                   @if($cliendefault == null)
                                                                   onfocus="Enabled=true;Chietkhau();"
                                                                   onchange="Chietkhau();"
                                                                   @endif
                                                                   type="text">@endif @endforeach</td>
                                                    @foreach($clientgroup as $cg)
                                                        <td>@foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
                                                                @if($serverserviceclientgroupcredit->currency== $currenciessite->config_value && $serverserviceclientgroupcredit->client_group_id==$cg->id )
                                                                    <input id="sel_client_group_{{$serverserviceclientgroupcredit->client_group_id}}_{{$serverservicequantityrange->id}}"
                                                                           class="form-control"
                                                                           @if($cliendefault ==! null)
                                                                           @if($serverserviceclientgroupcredit->client_group_id == $cliendefault->id) onchange="Chietkhau();"
                                                                           onfocus="Enabled=true;Chietkhau();" @endif
                                                                           @if($serverserviceclientgroupcredit->client_group_id !== $cliendefault->id) onfocus="Enabled=false;Chietkhau();"
                                                                           @endif
                                                                           @else
                                                                           onfocus="Enabled=false;Chietkhau();"
                                                                           @endif
                                                                           name="client_group_{{$serverserviceclientgroupcredit->client_group_id}}_{{$serverserviceclientgroupcredit->id}}"
                                                                           type="text" autocomplete="off"
                                                                           value="{{number_format($serverserviceclientgroupcredit->credit,2)}}">@endif
                                                            @endforeach
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            @if($serverservice->serverservicequantityrange->isEmpty())
                                                <tr>
                                                    <td></td>
                                                    <td><span>1-</span>
                                                        <span class="pull-right">
                                                        <input class="form-control"
                                                               name="to_range"
                                                               autocomplete="off"
                                                               value="1"
                                                               type="text">
                                                        </span></td>
                                                    <td><input class="form-control"
                                                               name="credit_new"
                                                               autocomplete="off"
                                                               value=""
                                                               type="text"></td>
                                                    @foreach($clientgroup as $cg)
                                                        <td><input class="form-control"
                                                                   name="sel_client_group_{{$cg->id}}_new"
                                                                   autocomplete="off"
                                                                   value=""
                                                                   type="text"></td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <input class="btn btn-primary pull-right" type="submit" name="myButton"
                                           onfocus="parent.$.fancybox.close();" value="Save">
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                //gọi dữ liệu nhà cung cấp (tỉ giá, phí )
                var tipurchasecost = {{$serverservice->servicepricing->nhacungcap->exchangerate}};
                var transactionfeegd = {{$serverservice->servicepricing->nhacungcap->transactionfee}};
                var exchangerategoc = {{$exchangerate->exchange_rate_static}};

                function Purchasenet() {
                    var purchasecost = document.getElementById("purchasecost").value;
                    var giatransactionfee = (tipurchasecost * purchasecost) / exchangerategoc + ((purchasecost / 100) * transactionfeegd);
                    document.getElementById('purchasenet').value = giatransactionfee;
                }

                var Enabled = true;

                function Chietkhau() {
                    if (Enabled == true) {
                        var giatransactionfee = document.getElementById("purchasenet").value;
                                @foreach($serverservice->serverservicequantityrange as $serverservicequantityrange )
                                @if($cliendefault == null)
                        var priceuser_{{$serverservicequantityrange->id}} = document.getElementById("credit_{{$serverservicequantityrange->id}}").value;
                                @else
                        var priceuser_{{$serverservicequantityrange->id}} = document.getElementById("sel_client_group_{{$cliendefault->id}}_{{$serverservicequantityrange->id}}").value;
                        @endif


                                @foreach($clientgroup as $cg)
                                @foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
                                @if($serverserviceclientgroupcredit->currency== $currenciessite->config_value && $serverserviceclientgroupcredit->client_group_id==$cg->id )
                            sel_client_group_{{$cg->id}}_{{$serverserviceclientgroupcredit->id}} = (priceuser_{{$serverservicequantityrange->id}} - (((priceuser_{{$serverservicequantityrange->id}} - giatransactionfee) / 100) *{{$cg->chietkhau}}));


                        @if($cliendefault == null)
                        document.getElementById('sel_client_group_{{$cg->id}}_{{$serverservicequantityrange->id}}').value = sel_client_group_{{$cg->id}}_{{$serverserviceclientgroupcredit->id}}.toFixed(4);
                        @else
                        @if($cg->id !== $cliendefault->id)
                        document.getElementById('sel_client_group_{{$cg->id}}_{{$serverservicequantityrange->id}}').value = sel_client_group_{{$cg->id}}_{{$serverserviceclientgroupcredit->id}}.toFixed(4);
                        @endif
                        @endif

                        @endif

                        @endforeach
                        @endforeach
                        @endforeach
                    }
                }

                document.addEventListener('DOMContentLoaded', function () {
                    Purchasenet();
                }, false);

            </script>
            <!--wise service-->
        @endif
    @endif
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
            $(editableObj).css("background", "#FFF url({{ url('loaderIcon.gif') }}) no-repeat right");
            $.ajax({
                url: "{{ url('serverquickedit') }}",
                type: "POST",
                data: {column: column, type: type, value: editableObj.value, id: id, idgr: idgr},
                success: function (data) {
                    console.log(data);
                    $(editableObj).css("background", "#FDFDFD");
                }
            });
        }

        function savePrice(editableObj, type, column, id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(editableObj).css("background", "#FFF url({{url('loaderIcon.gif')}}) no-repeat right");
            $.ajax({
                url: "{{url('serverquickedit')}}",
                type: "POST",
                data: {column: column, type: type, value: editableObj.value, id: id},
                success: function (data) {
                    console.log(data);
                    $(editableObj).css("background", "#a2e5fd");
                }
            });
        }
    </script>
@endsection

