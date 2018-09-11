@extends('layouts.app')
@section('content')
    @if($imei->id_supplier ==!null)
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
                                               onchange="conversecurrency();">
                                    </div>
                                    <div class="col-md-2">
                                        <strong>From</strong>
                                        <select class="form-control" id="valueFrom">
                                            @foreach($allcurrencies as $ac)
                                                <option value="{{$ac->currency_code}}">{{$ac->currency_code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <strong>To</strong>
                                        <select class="form-control" id="valueTo">
                                            @foreach($allcurrencies as $ac)
                                                <option @if($ac->currency_code == $exchangerate->currency_code) selected
                                                        @endif value="{{$ac->currency_code}}">{{$ac->currency_code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <strong>Result</strong>
                                        <input id=results class="form-control" readonly>
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
                                    <div class="col-md-4">
                                        <strong>Default Credit</strong>
                                        <input type="text" name="credit" id="credit" class="form-control"
                                               value="{{$imei->imei->credit}}"
                                               placeholder="Credit" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Purchase Cost </strong>
                                        <input type="text" name="purchasecost" id="purchasecost" class="form-control"
                                               onchange="Purchasenet();"
                                               value="@if($imei->imei->apiserverservices ==! null){{$imei->imei->apiserverservices->credits}}@elseif($imei->purchasecost == null){{$imei->imei->purchase_cost}}@else{{$imei->purchasecost}}@endif"
                                               placeholder="Giá nhập" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
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
                                        @foreach($pricegroup as $c)
                                            @if($c->chietkhau ==! null)
                                                <th>{{$c->chietkhau->group_name}}</th>
                                            @endif
                                        @endforeach
                                        </thead>
                                        <tbody>
                                        @foreach($pricegroup as $c)
                                            @if($c->chietkhau ==! null)
                                                <td><input type="text" name="giabanle{{$c->group_id}}"
                                                           id="chietkhau{{$c->group_id}}"
                                                           @if($c->group_id== $cliendefault->id) onchange="Chietkhau();"
                                                           onclick="Enabled=true;Chietkhau();" @endif
                                                           @if($c->group_id !== $cliendefault->id) onclick="Enabled=false;Chietkhau();"
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
            var tipurchasecost = {{$imei->nhacungcap->exchangerate}};
            var transactionfeegd = {{$imei->nhacungcap->transactionfee}};
            var exchangerategoc = {{$exchangerate->exchange_rate_static}};

            function Purchasenet() {
                var purchasecost = document.getElementById("purchasecost").value;
                var giatransactionfee = (tipurchasecost * purchasecost) / exchangerategoc + ((purchasecost / 100) * transactionfeegd);
                document.getElementById('purchasenet').value = giatransactionfee.toFixed(2);
                Chietkhau();
            }

            var Enabled = true;

            function Chietkhau() {
                if (Enabled == true) {
                    //tính giá đã bao gồm phí chuyển đổi
                    var giatransactionfee = document.getElementById("purchasenet").value;
                    //Lấy giá trị từ giá bán lẻ
                    var user = document.getElementById("chietkhau{{$cliendefault->id}}").value;
                    @foreach($clien as $pri)
                        result{{$pri->id}} = (user - (((user - giatransactionfee) / 100) *{{$pri->chietkhau}}));
                    @if($pri->id !== $cliendefault->id)
                    document.getElementById('chietkhau{{$pri->id}}').value = result{{$pri->id}}.toFixed(2);
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
                                <i class="material-icons">edit</i>
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

