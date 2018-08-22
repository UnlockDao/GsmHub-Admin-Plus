@extends('layouts.header')
@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-rose">
                        <div class="card-icon">
                            <i class="material-icons">edit</i>
                        </div>
                        <h4 class="card-title ">Edit {{$currencie->currency_name}}</h4>

                    </div>
                    <div class="card-body">
                        <form action="{{ url('currencie') }}/{{$currencie->id}}" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);">
                            {{ csrf_field() }}
                            <div class="col-md-12">
                                <strong>Exchange Rate</strong>
                                <input type="text" name="exchange_rate_static"  class="form-control" value="{{$currencie->exchange_rate_static}}" placeholder="Exchange Rate" autocomplete="off" required>
                            </div>
                            <input class="btn btn-primary pull-right" type="submit" name="myButton" value="Save">
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>

            </div>
        </div>


        </div>





@endsection

