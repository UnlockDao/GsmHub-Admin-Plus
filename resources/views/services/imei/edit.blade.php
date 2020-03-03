@extends('layouts.app')
@section('content')
    @if($imei->id_supplier ==!null)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('msg'))
                                <div class="alert alert-success">
                                    {!! Session::get('msg') !!}
                                </div>
                            @endif
                            <form action="{{route('admin.imei.update',$imeiService->id)}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Name IMEI Services</strong>
                                        <input type="text" name="service_name" id="service_name" class="form-control"
                                               value="{{$imeiService->service_name}}"
                                               placeholder="Name IMEI Services" autocomplete="off">
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Service Listed In Group</strong>
                                        <select name="imei_service_group_id" class="form-control">
                                            @foreach($imeiGroup as $v)
                                                <option value="{{$v->id}}"
                                                        @if($imeiService->imei_service_group_id == $v->id) selected @endif>{{$v->group_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Supplier</strong>
                                        <select name="id_supplier" class="form-control" id="id_supplier">
                                            @foreach($supplier as $v)
                                                <option value="{{$v->id}}"
                                                        @if($imei->id_supplier == $v->id) selected @endif >{{$v->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <strong>Delivery Time</strong>
                                        <input type="text" name="process_time" id="process_time" class="form-control"
                                               value="{{$imeiService->process_time}}"
                                               placeholder="process_time" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Time</strong>
                                        <select name="time_unit" class="form-control">
                                            <option value="Minutes"
                                                    @if($imeiService->time_unit == 'Minutes') selected @endif>Minutes
                                            </option>
                                            <option value="Hours"
                                                    @if($imeiService->time_unit == 'Hours') selected @endif>Hours
                                            </option>
                                            <option value="Weeks"
                                                    @if($imeiService->time_unit == 'Weeks') selected @endif>Weeks
                                            </option>
                                            <option value="Days" @if($imeiService->time_unit == 'Days') selected @endif>
                                                Days
                                            </option>
                                            <option value="Months"
                                                    @if($imeiService->time_unit == 'Months') selected @endif>Months
                                            </option>
                                            <option value="Instant"
                                                    @if($imeiService->time_unit == 'Instant') selected @endif>Instant
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Info</strong>
                                        <textarea class="form-control" id="infoservices"
                                                  name="service_information"> {!! $imeiService->service_information !!}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Purchase Cost </strong>
                                        <input type="text" name="purchasecost" id="purchasecost" class="form-control"
                                               onkeyup="Purchasenet();" @if($imeiService->apiserverservices ==! null) readonly @endif
                                               value="@if($imeiService->apiserverservices ==! null){{$imeiService->apiserverservices->credits}}@elseif($imei->purchasecost == null){{$imeiService->purchase_cost}}@else{{$imei->purchasecost}}@endif"
                                               placeholder="Giá nhập" autocomplete="off">
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Purchase Cost(Net) </strong>
                                        <input type="text" name="purchase_cost" id="purchasenet" class="form-control"
                                               value="{{$imeiService->purchase_cost}}"
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
                                                   value="{{$imeiService->credit}}"
                                                   @if($clienDefault == null) onkeyup="Chietkhau();"
                                                   onfocus="Enabled=true;Chietkhau();" @endif
                                                   placeholder="Credit" autocomplete="off"></td>
                                        @foreach($pricegroup as $c)
                                            @if($c->chietkhau ==! null)
                                                <td><input type="text" name="groupPrice[{{$loop->index}}][price]"
                                                           id="chietkhau{{$c->group_id}}"
                                                           @if($clienDefault ==! null)
                                                           @if($c->group_id== $clienDefault->id) onkeyup="Chietkhau();"
                                                           onfocus="Enabled=true; Chietkhau();"
                                                           @endif
                                                           @if($c->group_id !== $clienDefault->id) onfocus="Enabled=false;Chietkhau();"
                                                           @endif
                                                           @else
                                                           onfocus="Enabled=false;Chietkhau();"
                                                           @endif
                                                           class="form-control"
                                                           value="{{$imeiService->credit + $c->discount}}"
                                                           placeholder="Giá bán lẻ" autocomplete="off">
                                                <input hidden name="groupPrice[{{$loop->index}}][group_id]" value="{{$c->group_id}}">
                                                <input hidden name="groupPrice[{{$loop->index}}][id]" value="{{$c->id}}">
                                                </td>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <input class="btn btn-primary pull-right" type="submit" value="Save">
                                <input class="btn btn-danger pull-right"
                                       onfocus="parent.$.fancybox.close();" type="button" value="Close">
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
            var exchangerategoc = {{$exchangeRate->exchange_rate_static}};

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
                            @if($clienDefault ==! null)
                    var user = document.getElementById("chietkhau{{$clienDefault->id}}").value;
                            @else
                    var user = document.getElementById("credit").value;
                    @endif
                            @foreach($userGroup as $pri)
                        result{{$pri->id}} = (user - (((user - giatransactionfee) / 100) *{{$pri->chietkhau}}));
                    @if($clienDefault == null)
                    document.getElementById('chietkhau{{$pri->id}}').value = result{{$pri->id}}.toFixed(4);
                    @else
                    @if($pri->id !== $clienDefault->id)
                    document.getElementById('chietkhau{{$pri->id}}').value = result{{$pri->id}}.toFixed(4);
                    @endif
                    @endif

                    @endforeach
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Purchasenet();
            }, false);

        </script>
        <script>
            $(document).ready(function () {
                $('#infoservices').summernote({
                    toolbar: [
                        ['style', ['style']],
                        ['fontsize', ['fontsize']],
                        ['font', ['bold', 'italic', 'underline', 'clear','strikethrough']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'hr']],
                        ['view', ['fullscreen', 'codeview']],
                        ['help', ['help']]
                    ],
                    placeholder: 'Write description...',
                    height: 230
                });
            });
        </script>
    @else
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
                            <h4 class="card-title ">Edit {{$imeiService->service_name}} </h4>

                        </div>
                        <div class="card-body">
                            <form action="{{ url('updatesupplier') }}/{{$imei->id}}" method="POST"
                                  enctype="multipart/form-data"
                                  onsubmit="return checkForm(this);">
                                @csrf
                                <div class="col-md-12">
                                    <strong>Supplier</strong>
                                    <select name="id_supplier" class="form-control">
                                        @foreach($supplier as $v)
                                            <option value="{{$v->id}}"
                                                    @if($imei->id_supplier == $v->id) selected @endif>{{$v->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <input class="btn btn-primary pull-right" type="submit" value="Edit">
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    @endif

@endsection

