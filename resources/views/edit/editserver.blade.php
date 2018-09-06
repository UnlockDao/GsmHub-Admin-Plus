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
                                               value="@if($serverservice->api_id ==! null){{$serverservice->api_credit}}@elseif($serverservice->servicepricing->purchasecost == null){{number_format($serverservice->purchase_cost,2)}}@else{{number_format($serverservice->servicepricing->purchasecost,2)}}@endif">
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
                                        <th>Range</th>
                                        <th>Credit</th>
                                        <th>Admin</th>
                                        <th>User</th>
                                        <th>VIP</th>
                                        <th>Reseller</th>
                                        <th>Distributor</th>
                                        </thead>
                                        <tbody>
                                        @foreach($serverservice->serverservicequantityrange as $serverservicequantityrange )
                                            <tr>
                                                <td>{{$serverservicequantityrange->from_range}}
                                                    - {{$serverservicequantityrange->to_range}}</td>
                                                <td></td>
                                                @foreach($serverservicequantityrange->serverserviceclientgroupcredit as $serverserviceclientgroupcredit)
                                                    @if($serverserviceclientgroupcredit->currency=='USD')
                                                        <td>
                                                            <input id="sel_client_group_{{$serverserviceclientgroupcredit->client_group_id}}_{{$serverserviceclientgroupcredit->id}}"
                                                                   class="form-control"
                                                                   name="client_group_{{$serverserviceclientgroupcredit->client_group_id}}_{{$serverserviceclientgroupcredit->id}}"
                                                                   type="text"
                                                                   value="{{number_format($serverserviceclientgroupcredit->credit,2)}}">

                                                        </td>@endif
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
                                        <th>Purchase Cost</th>
                                        <th>Admin</th>
                                        <th>User</th>
                                        <th>VIP</th>
                                        <th>Reseller</th>
                                        <th>Distributor</th>
                                        </thead>
                                        <tbody>
                                        @foreach($serverservice->serverservicetypewiseprice as $a)
                                            <?php $server_service_type_wise_groupprice = DB::table('server_service_type_wise_groupprice')
                                                ->where('service_type_id', $a->id)
                                                ->where('server_service_id', $serverservice->id)
                                                ->get();
                                            ?>
                                            <tr>
                                                <td>{{$a->id}}</td>
                                                <td>{{$a->service_type}}</td>
                                                <td>@foreach($serverservice->apiserverservicetypeprice as $apiserverservicetypeprice)
                                                        @if($apiserverservicetypeprice->service_type == $a->service_type)
                                                            {{$apiserverservicetypeprice->api_price}}
                                                        @endif
                                                    @endforeach</td>
                                                <td>{{ number_format($a->purchase_cost, 2) }}</td>

                                                @foreach($server_service_type_wise_groupprice as $s)
                                                    <td>
                                                       <input
                                                                    id="client_group_amount_{{$s->id}}_{{$s->group_id}}"
                                                                    class="form-control"
                                                                    name="client_group_amount_{{$s->id}}_{{$s->group_id}}"
                                                                    type="text"
                                                                    autocomplete="off"
                                                                    value="{{ number_format($s->amount, 2) }}">

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


        @endif
    @endif

@endsection

