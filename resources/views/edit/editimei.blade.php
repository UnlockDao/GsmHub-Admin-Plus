@extends('layouts.app')
@section('content')
    @if($imei->id_supplier ==!null)
        <script>
            function VNtoUSD() {
                if(Enabled == 1) {
                    var vnd, usd;
                    //Lấy text từ thẻ input title
                    vnd = document.getElementById("valueExchangerates").value;
                    var b = parseFloat(vnd) / {{$exchangerate->exchange_rate_static}};
                    result = b;
                    document.getElementById('results').value = result;
                }
            }
            function USDtoVND() {
                if(Enabled == 2)
                {
                var vnd, usd;
                //Lấy text từ thẻ input title
                usd = document.getElementById("results").value;
                var b = parseFloat(usd) * {{$exchangerate->exchange_rate_static}};
                document.getElementById('valueExchangerates').value = b;
                }
            }
        </script>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('imei') }}/{{$imei->id}}" method="POST" enctype="multipart/form-data"
                                  onsubmit="return checkForm(this);">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Exchangerates</strong>
                                        <input id="valueExchangerates" class="form-control" autocomplete="off"
                                               onchange="VNtoUSD();" onfocus="Enabled=1;USDtoVND();">
                                    </div>
                                    <div class="col-md-2">
                                        <strong>From</strong>
                                        <select class="form-control" id="valueFrom">
                                            <option value="VND">VND</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>To</strong>
                                        <select class="form-control" id="valueTo">
                                            <option value="USD">USD</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Result</strong>
                                        <input onchange="USDtoVND();" onfocus="Enabled=3;VNtoUSD();Enabled=2;USDtoVND();" id=results class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Name IMEI Services</strong>
                                        <input type="text" name="service_name" id="service_name" class="form-control"
                                               value="{{$imei->imei->service_name}}"
                                               placeholder="Name IMEI Services" autocomplete="off">
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Service Listed In Group</strong>
                                        <select name="service_group" class="form-control">
                                            @foreach($group as $v)
                                                <option value="{{$v->id}}"
                                                        @if($imei->imei->imei_service_group_id == $v->id) selected @endif>{{$v->group_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Supplier</strong>
                                        <select name="id_supplier" class="form-control">
                                            @foreach($nhacungcap as $v)
                                                <option value="{{$v->id}}"
                                                        @if($imei->id_supplier == $v->id) selected @endif>{{$v->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <strong>Delivery Time</strong>
                                        <input type="text" name="process_time" id="process_time" class="form-control"
                                               value="{{$imei->imei->process_time}}"
                                               placeholder="process_time" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Time</strong>
                                        <select name="time_unit" class="form-control">
                                                <option value="Minutes" @if($imei->imei->time_unit == 'Minutes') selected @endif>Minutes</option>
                                                <option value="Hours" @if($imei->imei->time_unit == 'Hours') selected @endif>Hours</option>
                                                <option value="Weeks" @if($imei->imei->time_unit == 'Weeks') selected @endif>Weeks</option>
                                                <option value="Days" @if($imei->imei->time_unit == 'Days') selected @endif>Days</option>
                                                <option value="Months" @if($imei->imei->time_unit == 'Months') selected @endif>Months</option>
                                                <option value="Instant" @if($imei->imei->time_unit == 'Instant') selected @endif>Instant</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Purchase Cost </strong>
                                        <input type="text" name="purchasecost" id="purchasecost" class="form-control"
                                               onchange="Purchasenet();"
                                               value="@if($imei->imei->apiserverservices ==! null){{$imei->imei->apiserverservices->credits}}@elseif($imei->purchasecost == null){{$imei->imei->purchase_cost}}@else{{$imei->purchasecost}}@endif"
                                               placeholder="Giá nhập" autocomplete="off">
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Purchase Cost(Net) </strong>
                                        <input type="text" name="purchasenet" id="purchasenet" class="form-control"
                                               value=""
                                               placeholder="Purchase Cost(Net)" autocomplete="off" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive table-full-width table-hover">
                                    <table id="testTable" class="table table-striped">
                                        <thead class="text-primary">
                                        <th>Credit</th>
                                        @foreach($pricegroup as $c)
                                            @if($c->chietkhau ==! null)
                                                <th>{{$c->chietkhau->group_name}}</th>
                                            @endif
                                        @endforeach
                                        </thead>
                                        <tbody>
                                        <td><input type="text" name="credit" id="credit" class="form-control"
                                                   value="{{$imei->imei->credit}}" @if($cliendefault == null) onchange="Chietkhau();" onfocus="Enabled=true;Chietkhau();" @endif
                                                   placeholder="Credit" autocomplete="off"></td>
                                        @foreach($pricegroup as $c)
                                            @if($c->chietkhau ==! null)
                                                <td><input type="text" name="giabanle{{$c->group_id}}"
                                                           id="chietkhau{{$c->group_id}}"
                                                           @if($cliendefault ==! null)
                                                               @if($c->group_id== $cliendefault->id) onchange="Chietkhau();"
                                                               onfocus="Enabled=true; Chietkhau();"
                                                               @endif
                                                               @if($c->group_id !== $cliendefault->id) onfocus="Enabled=false;Chietkhau();"
                                                               @endif
                                                           @else
                                                                onfocus="Enabled=false;Chietkhau();"
                                                           @endif
                                                           class="form-control"
                                                           value="<?php echo $imei->imei->credit + $c->discount  ?>"
                                                           placeholder="Giá bán lẻ" autocomplete="off"></td>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <input class="btn btn-primary pull-right" type="submit"
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
            var tipurchasecost = {{$imei->nhacungcap->exchangerate}};
            var transactionfeegd = {{$imei->nhacungcap->transactionfee}};
            var exchangerategoc = {{$exchangerate->exchange_rate_static}};

            function Purchasenet() {
                var purchasecost = document.getElementById("purchasecost").value;
                var giatransactionfee = (tipurchasecost * purchasecost) / exchangerategoc + ((purchasecost / 100) * transactionfeegd);
                document.getElementById('purchasenet').value = giatransactionfee.toFixed(4);
                Chietkhau();
            }

            var Enabled = true;

            function Chietkhau() {
                if (Enabled == true) {
                    //tính giá đã bao gồm phí chuyển đổi
                    var giatransactionfee = document.getElementById("purchasenet").value;
                    //Lấy giá trị từ giá bán lẻ
                    @if($cliendefault ==! null)
                    var user = document.getElementById("chietkhau{{$cliendefault->id}}").value;
                    @else
                    var user = document.getElementById("credit").value;
                    @endif
                    @foreach($clien as $pri)
                        result{{$pri->id}} = (user - (((user - giatransactionfee) / 100) *{{$pri->chietkhau}}));
                        @if($cliendefault == null)
                        document.getElementById('chietkhau{{$pri->id}}').value = result{{$pri->id}}.toFixed(4);
                                @else
                                    @if($pri->id !== $cliendefault->id)
                                        document.getElementById('chietkhau{{$pri->id}}').value = result{{$pri->id}}.toFixed(4);
                                    @endif
                                @endif

                    @endforeach
                }
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
                                <i class="">edit</i>
                            </div>
                            <h4 class="card-title ">Edit {{$imei->imei->service_name}} </h4>

                        </div>
                        <div class="card-body">
                            <form action="{{ url('updatesupplier') }}/{{$imei->id}}" method="POST"
                                  enctype="multipart/form-data"
                                  onsubmit="return checkForm(this);">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <strong>Supplier</strong>
                                    <select name="id_supplier" class="form-control">
                                        @foreach($nhacungcap as $v)
                                            <option value="{{$v->id}}"
                                                    @if($imei->id_supplier == $v->id) selected @endif>{{$v->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input class="btn btn-primary pull-right" type="submit" name="myButton" value="Edit">
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    @endif

@endsection

