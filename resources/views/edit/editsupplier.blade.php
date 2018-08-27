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
                        <h4 class="card-title ">Edit {{$supplier->name}}</h4>

                    </div>
                    <div class="card-body">
                        <form action="{{ url('supplier') }}/{{$supplier->id}}" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);">
                            {{ csrf_field() }}
                            <div class="col-md-12">
                                <strong>Tỉ giá</strong>
                                <input type="text" name="exchangerate"  class="form-control" value="{{$supplier->exchangerate}}" placeholder="%" autocomplete="off" required>
                            </div>
                            <div class="col-md-12">
                                <strong>Phí GD</strong>
                                <input type="text" name="transactionfee"  class="form-control" value="{{$supplier->transactionfee}}" placeholder="%" autocomplete="off" required>
                            </div>
                            <input class="btn btn-primary pull-right" type="submit" name="myButton" value="Sửa">
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>

            </div>
        </div>


        </div>





@endsection

