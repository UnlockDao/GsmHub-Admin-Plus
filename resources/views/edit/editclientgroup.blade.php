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
                        <h4 class="card-title ">Edit</h4>

                    </div>
                    <div class="card-body">
                        <form action="{{ url('clientgroup') }}/{{$client->id}}" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);">
                            {{ csrf_field() }}
                            <div class="col-md-12">
                                <strong>Phần trăm chiết khấu</strong>
                                <input type="number" name="chietkhau" max="100" min="0" class="form-control" value="{{$client->chietkhau}}" placeholder="%" autocomplete="off" required>
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

