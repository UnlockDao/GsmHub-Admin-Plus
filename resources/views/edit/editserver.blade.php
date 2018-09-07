@extends('layouts.app')
@section('content')
    @if($serverservice->servicepricing->id_supplier == null)
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
                            <div class="card-icon">
                                <i class="material-icons">edit</i>
                            </div>
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
                                <input class="btn btn-primary pull-right" type="submit" value="Add">
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @else











        @if(!$serverservice->serverservicequantityrange->isEmpty())

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-icon card-header-rose">
                                <div class="card-icon">
                                    <i class="material-icons">edit</i>
                                </div>
                                <h4 class="card-title ">Edit {{$serverservice->service_name}}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ url('serverservice') }}/{{$serverservice->id}}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5">Purchase Cost : </h5>
                                    </div>
                                    <div class="col-md-3">
                                        <input autocomplete="off" id="purchasecost" class="form-control" name="purchase_cost" type="text" onchange="Purchasenet();"@if($serverservice->api_id ==! null) readonly @endif
                                               value="@if($serverservice->api_id ==! null){{$serverservice->apiserverservices->credits}}@elseif($serverservice->servicepricing->purchasecost == null){{number_format($serverservice->purchase_cost,2)}}@else{{number_format($serverservice->servicepricing->purchasecost,2)}}@endif">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>Purchase Cost (Vip) : </h5>
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
                                                <td>@foreach($serverservicequantityrange->serverserviceusercredit as $serverserviceusercredit)@if($serverserviceusercredit->currency == 'USD')<input class="form-control" name="credit_{{$serverservicequantityrange->id}}" autocomplete="off" value="{{number_format($serverserviceusercredit->credit,2)}}" type="text">@endif @endforeach</td>
                                                @foreach($clientgroup as $cg)
                                                    <td>@foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
                                                            @if($serverserviceclientgroupcredit->currency=='USD' && $serverserviceclientgroupcredit->client_group_id==$cg->id )
                                                                <input id="sel_client_group_{{$serverserviceclientgroupcredit->client_group_id}}_{{$serverservicequantityrange->id}}"
                                                                       class="form-control" @if($serverserviceclientgroupcredit->client_group_id == $cliendefault->id) onchange="Chietkhau();" @endif
                                                                       name="client_group_{{$serverserviceclientgroupcredit->client_group_id}}_{{$serverserviceclientgroupcredit->id}}"
                                                                       type="text" autocomplete="off"
                                                                       value="{{number_format($serverserviceclientgroupcredit->credit,2)}}">@endif
                                                        @endforeach
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                    <input class="btn btn-primary pull-right" type="submit" name="myButton"
                                           onClick="parent.$.fancybox.close();" value="Save">
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


                function Chietkhau() {
                        var giatransactionfee = document.getElementById("purchasenet").value;
                    @foreach($serverservice->serverservicequantityrange as $serverservicequantityrange )
                        var priceuser_{{$serverservicequantityrange->id}} = document.getElementById("sel_client_group_{{$cliendefault->id}}_{{$serverservicequantityrange->id}}").value;

                        @foreach($clientgroup as $cg)
                               @foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
                                    @if($serverserviceclientgroupcredit->currency=='USD' && $serverserviceclientgroupcredit->client_group_id==$cg->id )
                                        sel_client_group_{{$cg->id}}_{{$serverserviceclientgroupcredit->id}} = (priceuser_{{$serverservicequantityrange->id}} - (((priceuser_{{$serverservicequantityrange->id}} - giatransactionfee) / 100) *{{$cg->chietkhau}}));
                                         console.log(sel_client_group_{{$cg->id}}_{{$serverserviceclientgroupcredit->id}});
                                             document.getElementById('sel_client_group_{{$cg->id}}_{{$serverservicequantityrange->id}}').value = sel_client_group_{{$cg->id}}_{{$serverserviceclientgroupcredit->id}};
                                    @endif

                                @endforeach
                        @endforeach
                    @endforeach

                }

                document.addEventListener('DOMContentLoaded', function () {
                    Purchasenet();
                }, false);

            </script>











        @else
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-icon card-header-rose">
                                <div class="card-icon">
                                    <i class="material-icons">edit</i>
                                </div>
                                <h4 class="card-title ">Edit {{$serverservice->service_name}}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ url('serverservicewise') }}/{{$serverservice->id}}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                <div class="table-responsive table-full-width table-hover">
                                    <table id="testTable" class="table table-striped">
                                        <thead class="text-primary">
                                        <th width="2%">ID</th>
                                        <th>Type</th>
                                        <th>ApiCredits</th>
                                        <th>Purchase Cost (VIP)</th>
                                        @foreach($clientgroup as $cg)
                                            <th>{{$cg->group_name}}</th>
                                        @endforeach
                                        </thead>
                                        <tbody>
                                        @foreach($serverservice->serverservicetypewiseprice as $a)
                                            <tr>
                                                <td>{{$a->id}}</td>
                                                <td>{{$a->service_type}}</td>
                                                <td>@foreach($serverservice->apiserverservicetypeprice as $apiserverservicetypeprice)
                                                        @if($apiserverservicetypeprice->service_type == $a->service_type)
                                                            {{$apiserverservicetypeprice->api_price}}
                                                        @endif
                                                    @endforeach</td>
                                                <td><input
                                                            id="purchase_cost_vip_{{$a->id}}"
                                                            class="form-control"
                                                            name="purchase_cost_vip_{{$a->id}}"
                                                            type="text" readonly
                                                            autocomplete="off"
                                                            value="{{ number_format($a->purchase_cost, 2) }}"> </td>
                                                @foreach($clientgroup as $cg)
                                                <td>@foreach($a->serverservicetypewisegroupprice as $serverservicetypewisegroupprice)
                                                        @if($serverservicetypewisegroupprice->service_type_id == $a->id &&$serverservicetypewisegroupprice->group_id == $cg->id)
                                                            <input
                                                                    id="client_group_amount_{{$serverservicetypewisegroupprice->group_id}}_{{$a->id}}"
                                                                    class="form-control"
                                                                    name="client_group_amount_{{$serverservicetypewisegroupprice->id}}_{{$serverservicetypewisegroupprice->group_id}}"
                                                                    type="text" @if($serverservicetypewisegroupprice->group_id == $cliendefault->id) onchange="Chietkhau();" @endif
                                                                    autocomplete="off"
                                                                    value="{{ number_format($serverservicetypewisegroupprice->amount, 2) }}">
                                                        @endif
                                                    @endforeach
                                                </td>
                                                @endforeach

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                    <input class="btn btn-primary pull-right" type="submit" name="myButton"
                                           onClick="parent.$.fancybox.close();" value="Save">
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

                function Chietkhau() {
                     @foreach($serverservice->serverservicetypewiseprice as $a)
                         var purchase_cost_vip_{{$a->id}} = document.getElementById("purchase_cost_vip_{{$a->id}}").value;
                         var priceuser_{{$a->id}} = document.getElementById("client_group_amount_{{$cliendefault->id}}_{{$a->id}}").value;
                        @foreach($clientgroup as $cg)
                            @foreach($a->serverservicetypewisegroupprice as $serverservicetypewisegroupprice)
                                    @if($serverservicetypewisegroupprice->service_type_id == $a->id &&$serverservicetypewisegroupprice->group_id == $cg->id)
                                        var client_group_amount_{{$cg->id}}_{{$serverservicetypewisegroupprice->id}} = (priceuser_{{$a->id}} - (((priceuser_{{$a->id}} - purchase_cost_vip_{{$a->id}}) / 100) *{{$cg->chietkhau}}));
                                      document.getElementById('client_group_amount_{{$serverservicetypewisegroupprice->group_id}}_{{$a->id}}').value = client_group_amount_{{$cg->id}}_{{$serverservicetypewisegroupprice->id}};
                                    @endif
                            @endforeach
                        @endforeach
                     @endforeach

                }

                document.addEventListener('DOMContentLoaded', function () {
                }, false);

            </script>


        @endif
    @endif

@endsection

