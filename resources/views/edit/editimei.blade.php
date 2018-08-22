@extends('layouts.app')
@section('content')
    @if($imei->id_nhacungcap ==!null)
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
                            <form action="{{ url('imei') }}/{{$imei->id}}" method="POST" enctype="multipart/form-data"
                                  onsubmit="return checkForm(this);">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <strong>USD To VND</strong>
                                    <input type="text" id="vnd" class="form-control" value="" placeholder="Giá nhập"
                                           onkeyup="VNtoUSD();" onclick="Enabled=true;Chietkhau();" autocomplete="off">
                                </div>
                                <div class="col-md-12">
                                    <strong>Default Credit</strong>
                                    <input type="text" name="credit" id="credit" class="form-control"
                                           value="{{$imei->imei->credit}}"
                                           placeholder="Credit" autocomplete="off">
                                </div>
                                <div class="col-md-12">
                                    <strong>Purchase Cost </strong>
                                    <input type="text" name="gianhap" id="gianhap" class="form-control"
                                           onchange="Purchasenet();"
                                           value="@if($imei->imei->apiserverservices ==! null){{$imei->imei->apiserverservices->credits}}@elseif($imei->gianhap == null){{$imei->imei->purchase_cost}}@else{{$imei->gianhap}}@endif"
                                           placeholder="Giá nhập" autocomplete="off">
                                </div>
                                <div class="col-md-12">
                                    <strong>Purchase Cost(Net) </strong>
                                    <input type="text" name="purchasenet" id="purchasenet" class="form-control"
                                           value=""
                                           placeholder="Purchase Cost(Net)" autocomplete="off" readonly>
                                </div>
                                <div class="col-md-12">
                                    <strong>User</strong>
                                    <input type="text" name="giabanle" id="usd" onchange="Chietkhau();"
                                           onkeyup="USDtoVND();" class="form-control" readonly
                                           onclick="Enabled=true;Chietkhau();"
                                           value="<?php
                                           if ($price == null) {
                                               $imei->imei->credit;
                                           } else {
                                               echo $imei->imei->credit + $price->discount;
                                           }
                                           ?>"
                                           placeholder="Giá bán lẻ" autocomplete="off">
                                </div>
                                <div class="col-md-12">
                                    <strong>Supplier</strong>
                                    <select name="id_nhacungcap" class="form-control">
                                        @foreach($nhacungcap as $v)
                                            <option value="{{$v->id}}"
                                                    @if($imei->id_nhacungcap == $v->id) selected @endif>{{$v->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <hr>
                                @foreach($pricegroup as $c)
                                    @if($c->chietkhau ==! null)
                                        <div class="col-md-12">
                                            <strong>{{$c->chietkhau->group_name}}</strong>
                                            <input type="text" name="giabanle{{$c->group_id}}"
                                                   id="chietkhau{{$c->group_id}}"
                                                   class="form-control" onclick="Enabled=false;Chietkhau();"
                                                   value="<?php echo $imei->imei->credit + $c->discount  ?>"
                                                   placeholder="Giá bán lẻ" autocomplete="off">
                                        </div>
                                    @endif
                                @endforeach
                                <input class="btn btn-primary pull-right" type="submit" name="myButton"
                                       onClick="parent.$.fancybox.close();" value="Edit">
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>


        </div>

        <script>
            //gọi dữ liệu nhà cung cấp (tỉ giá, phí )
            var tigianhap = {{$imei->nhacungcap->tigia}};
            var phigd = {{$imei->nhacungcap->phi}};
            var tigiagoc = {{$exchangerate->exchange_rate_static}};


            function VNtoUSD() {
                var vnd, usd;
                //Lấy text từ thẻ input title
                vnd = document.getElementById("vnd").value;
                vnd = vnd.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '.');
                document.getElementById('vnd').value = vnd;
                var result = parseFloat(vnd) / tigiagoc;
                document.getElementById('usd').value = result;
            }

            function Purchasenet() {
                var gianhap = document.getElementById("gianhap").value;
                var giaphi = (tigianhap * gianhap) / tigiagoc + ((gianhap / 100) * phigd);
                document.getElementById('purchasenet').value = giaphi;
                Chietkhau();
            }

            function USDtoVND() {
                var usd;
                usd = document.getElementById("usd").value;
                usd = usd.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '.');
                document.getElementById('usd').value = usd;
                var b = parseFloat(usd) * tigiagoc;
                document.getElementById('vnd').value = b;
            }

            var Enabled = true;

            function Chietkhau() {
                if (Enabled == true) {
                    //tính giá đã bao gồm phí chuyển đổi
                    var giaphi = document.getElementById("purchasenet").value;
                    //Lấy giá trị từ giá bán lẻ
                    var user = document.getElementById("usd").value;
                    @foreach($clien as $pri)
                        result{{$pri->id}} = (user - (((user - giaphi) / 100) *{{$pri->chietkhau}}));
                    document.getElementById('chietkhau{{$pri->id}}').value = result{{$pri->id}};
                    @endforeach
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                USDtoVND();
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
                                    <select name="id_nhacungcap" class="form-control">
                                        @foreach($nhacungcap as $v)
                                            <option value="{{$v->id}}"
                                                    @if($imei->id_nhacungcap == $v->id) selected @endif>{{$v->name}}</option>
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

