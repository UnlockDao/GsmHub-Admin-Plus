@extends('layouts.header')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Supplier</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Supplier</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile" data-toggle="tab" aria-expanded="true" class="nav-link">
                                    <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Add</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane show active" id="home">
                                <table class="table">
                                    <thead class="text-primary">
                                    <th width="2%">ID</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Site</th>
                                    <th>API Key</th>
                                    <th>Info</th>
                                    <th>Exchange rate</th>
                                    <th>Transaction fee %</th>

                                    </thead>
                                    <tbody>
                                    @foreach($supplier as $v)
                                        <tr>
                                            <td>{{$v->id}}</td>
                                            <td contenteditable="true"
                                                onBlur="saveToDatabase(this,'name','{{$v->id}}')"
                                                onClick="showEdit(this);">@if($v->type == 0){{$v->name}} @else @if($v->userSupplier) {{$v->userSupplier->user_name}} @endif @endif</td>
                                            <td contenteditable="true"
                                                onBlur="saveToDatabase(this,'site_username','{{$v->id}}')"
                                                onClick="showEdit(this);">{{$v->site_username}}</td>
                                            <td contenteditable="true"
                                                onBlur="saveToDatabase(this,'site_password','{{$v->id}}')"
                                                onClick="showEdit(this);">{{$v->site_password}}</td>
                                            <td contenteditable="true"
                                                onBlur="saveToDatabase(this,'site_url','{{$v->id}}')"
                                                onClick="showEdit(this);">{{$v->site_url}}</td>
                                            <td contenteditable="true"
                                            <td contenteditable="true"
                                                onBlur="saveToDatabase(this,'api_key','{{$v->id}}')"
                                                onClick="showEdit(this);">{{$v->api_key}}</td>
                                            <td contenteditable="true"
                                                onBlur="saveToDatabase(this,'info','{{$v->id}}')"
                                                onClick="showEdit(this);">{!! $v->info !!}</td>
                                            <td contenteditable="true"
                                                onBlur="saveToDatabase(this,'exchangerate','{{$v->id}}')"
                                                onClick="showEdit(this);">{{ $v->exchangerate }}</td>
                                            <td contenteditable="true"
                                                onBlur="saveToDatabase(this,'transactionfee','{{$v->id}}')"
                                                onClick="showEdit(this);">{{$v->transactionfee}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="profile">
                                <form action="{{ url('addsupplier') }}" method="POST"
                                      enctype="multipart/form-data"
                                      onsubmit="return checkForm(this);">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group bmd-form-group">
                                                <label class="bmd-label-floating">Name
                                                    Supplier</label>
                                                <input type="text" name="name" placeholder="Name"
                                                       class="form-control" autocomplete="off"
                                                       required="">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group bmd-form-group">
                                                <label class="bmd-label-floating">Exchange
                                                    rate</label>
                                                <input type="text" name="exchangerate"
                                                       placeholder="Exchange rate"
                                                       class="form-control" autocomplete="off"
                                                       required="">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group bmd-form-group">
                                                <label class="bmd-label-floating">Transaction
                                                    fee</label>
                                                <input type="number" name="transactionfee"
                                                       placeholder="Transaction fee"
                                                       class="form-control"
                                                       autocomplete="off" required="">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-md-12">
                                            <label class="bmd-label-floating">Username</label>
                                            <input type="text" name="site_username"
                                                   class="form-control"
                                                   value="" placeholder="site_username"
                                                   autocomplete="off" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="bmd-label-floating">Password</label>
                                            <input type="text" name="site_password"
                                                   class="form-control"
                                                   value="" placeholder="site_password"
                                                   autocomplete="off" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="bmd-label-floating">Website</label>
                                            <input type="text" name="site_url" class="form-control"
                                                   value="" placeholder="site_url"
                                                   autocomplete="off" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="bmd-label-floating">Info</label>
                                            <textarea class="form-control" name="info" rows="3"
                                                      placeholder="Write info text here ..."></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-success pull-right">Add
                                    </button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

    <script>
        function showEdit(editableObj) {
            $(editableObj).css("background", "#FFF");
        }

        function saveToDatabase(editableObj, column, id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(editableObj).css("background", "#FFF url(loaderIcon.gif) no-repeat right");
            $.ajax({
                url: "supplierquickedit",
                type: "POST",
                data: {column: column, editval: editableObj.innerHTML, id: id},
                success: function (data) {
                    $(editableObj).css("background", "#FDFDFD");
                }
            });
        }
    </script>

@endsection
