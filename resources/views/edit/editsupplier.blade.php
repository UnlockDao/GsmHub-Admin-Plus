@extends('layouts.header')
@section('content')

    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0">
                            Edit @if($supplier->type == 0){{$supplier->name}} @else {{$supplier->userSupplier->user_name}} @endif</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('supplier') }}/{{$supplier->id}}" method="POST"
                              enctype="multipart/form-data" onsubmit="return checkForm(this);">
                            {{ csrf_field() }}
                            @if($supplier->type == 0)
                                <div class="col-md-12">
                                    <strong>Username</strong>
                                    <input type="text" name="site_username" class="form-control"
                                           value="{{$supplier->site_username}}" placeholder="site_username"
                                           autocomplete="off" required>
                                </div>
                                <div class="col-md-12">
                                    <strong>Password</strong>
                                    <input type="text" name="site_password" class="form-control"
                                           value="{{$supplier->site_password}}" placeholder="site_password"
                                           autocomplete="off" required>
                                </div>
                                <div class="col-md-12">
                                    <strong>Website</strong>
                                    <input type="text" name="site_url" class="form-control"
                                           value="{{$supplier->site_url}}" placeholder="site_url" autocomplete="off"
                                           required>
                                </div>
                                <div class="col-md-12">
                                    <strong>Info</strong>
                                    <textarea class="form-control" name="info" rows="3"
                                              placeholder="Write info text here ..."></textarea>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <strong>Exchange rate</strong>
                                <input type="text" name="exchangerate" class="form-control"
                                       value="{{$supplier->exchangerate}}" placeholder="%" autocomplete="off" required>
                            </div>
                            <div class="col-md-12">
                                <strong>Transaction fee</strong>
                                <input type="number" max="100" min="0" name="transactionfee" class="form-control"
                                       value="{{$supplier->transactionfee}}" placeholder="%" autocomplete="off"
                                       required>
                            </div>

                            <div class="col-md-12">
                                <br>
                                <input class="btn btn-primary pull-right" type="submit" name="myButton" value="Save">
                                <div class="clearfix"></div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>


    </div>





@endsection

