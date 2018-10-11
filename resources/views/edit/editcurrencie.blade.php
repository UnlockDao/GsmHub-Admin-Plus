@extends('layouts.header')
@section('content')
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Currencie</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('currencie') }}/{{$currencie->id}}" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);">
                            {{ csrf_field() }}
                            <div class="col-md-12">
                                <strong>Exchange Rate</strong>
                                <input type="text" name="exchange_rate_static"  class="form-control" value="{{$currencie->exchange_rate_static}}" placeholder="Exchange Rate" autocomplete="off" required>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <input class="btn btn-primary pull-right" type="submit" name="myButton" value="Save">
                            </div>

                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>

            </div>
        </div>






@endsection

