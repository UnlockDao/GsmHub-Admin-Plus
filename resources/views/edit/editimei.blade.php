@extends('layouts.app')
@section('content')
    @if($imei->id_supplier ==!null)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="#">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Name IMEI Services</strong>
                                        <input type="text" name="service_name" id="service_name" class="form-control"
                                               value="{{$imei->imei->service_name}}"
                                               onBlur="saveToDatabase(this,'service_name','services','{{$imei->id}}')"
                                               onClick="showEdit(this);"
                                               placeholder="Name IMEI Services" autocomplete="off">
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Service Listed In Group</strong>
                                        <select name="service_group" class="form-control"
                                                onBlur="saveToDatabase(this,'service_group','services','{{$imei->id}}')"
                                                onClick="showEdit(this);">
                                            @foreach($group as $v)
                                                <option value="{{$v->id}}"
                                                        @if($imei->imei->imei_service_group_id == $v->id) selected @endif>{{$v->group_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Supplier</strong>
                                        <select name="id_supplier" class="form-control" id="id_supplier"
                                                onchange="saveToDatabase(this,'id_supplier','services','{{$imei->id}}')"
                                                onClick="showEdit(this);">
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
                                               onBlur="saveToDatabase(this,'process_time','services','{{$imei->id}}')"
                                               onClick="showEdit(this);"
                                               placeholder="process_time" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Time</strong>
                                        <select name="time_unit" class="form-control"
                                                onchange="saveToDatabase(this,'time_unit','services','{{$imei->id}}')"
                                                onClick="showEdit(this);">
                                            <option value="Minutes"
                                                    @if($imei->imei->time_unit == 'Minutes') selected @endif>Minutes
                                            </option>
                                            <option value="Hours"
                                                    @if($imei->imei->time_unit == 'Hours') selected @endif>Hours
                                            </option>
                                            <option value="Weeks"
                                                    @if($imei->imei->time_unit == 'Weeks') selected @endif>Weeks
                                            </option>
                                            <option value="Days" @if($imei->imei->time_unit == 'Days') selected @endif>
                                                Days
                                            </option>
                                            <option value="Months"
                                                    @if($imei->imei->time_unit == 'Months') selected @endif>Months
                                            </option>
                                            <option value="Instant"
                                                    @if($imei->imei->time_unit == 'Instant') selected @endif>Instant
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Purchase Cost </strong>
                                        <input type="text" name="purchasecost" id="purchasecost" class="form-control"
                                               onchange="Purchasenet();" @if($imei->imei->apiserverservices ==! null) readonly @endif
                                               onBlur="saveToDatabase(this,'purchase_cost','services','{{$imei->id}}')"
                                               onClick="showEdit(this);"
                                               value="@if($imei->imei->apiserverservices ==! null){{$imei->imei->apiserverservices->credits}}@elseif($imei->purchasecost == null){{$imei->imei->purchase_cost}}@else{{$imei->purchasecost}}@endif"
                                               placeholder="Giá nhập" autocomplete="off">
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Purchase Cost(Net) </strong>
                                        <input type="text" name="purchasenet" id="purchasenet" class="form-control"
                                               value="{{$imei->imei->purchase_cost}}"
                                               onchange="saveToDatabase(this,'purchasecostnet','services','{{$imei->id}}')"
                                               onClick="showEdit(this);"
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
                                                   value="{{$imei->imei->credit}}"
                                                   @if($cliendefault == null) onchange="Chietkhau();"
                                                   onfocus="Enabled=true;Chietkhau();" @endif
                                                   onChange="saveToDatabase(this,'credit','services','{{$imei->id}}')"
                                                   onClick="showEdit(this);"
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
                                                           placeholder="Giá bán lẻ" autocomplete="off"
                                                           onBlur="saveToDatabase(this,'discount','price','{{$imei->id}}','{{$c->group_id}}')"
                                                           onClick="showEdit(this);"></td>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <input class="btn btn-primary pull-right" type="button" value="Save">
                                <input class="btn btn-danger pull-right"
                                       onfocus="parent.$.fancybox.close();" type="button" value="Close">
                                <div class="clearfix"></div>

                                <div class="card-body">
                                    <h4 class="header-title">Service Information</h4>
                                    <!-- basic summernote-->
                                    <div id="summernote-basic">{!! $imei->imei->service_information !!}</div>
                                </div>
                                <script>
                                    $(function () {
                                        $(document).on("blur",".note-editable",function () {
                                            saveinformation(this,'service_information','services','{{$imei->id}}')

                                        });

                                    });
                                </script>
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
                // Purchasenet();
            }, false);

        </script>
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
                    url: "{{ url('imeiquickedit') }}",
                    type: "POST",
                    data: {column: column, type: type, value: editableObj.value, id: id, idgr: idgr},
                    success: function (data) {
                        console.log(data);
                        $(editableObj).css("background", "#FDFDFD");
                    }
                });
            }

            function saveinformation(editableObj, column, type, id, idgr) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $(editableObj).css("background", "#FFF url({{ url('loaderIcon.gif') }}) no-repeat right");
                $.ajax({
                    url: "{{ url('imeiquickedit') }}",
                    type: "POST",
                    data: {column: column, type: type, value: editableObj.innerHTML, id: id, idgr: idgr},
                    success: function (data) {
                        console.log(data);
                        $(editableObj).css("background", "#FDFDFD");
                    }
                });
            }
        </script>
    @else
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
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

