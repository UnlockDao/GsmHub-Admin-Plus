@extends('layouts.header')
@section('content')
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body">
                        <form action="{{ url('clientgroup') }}/{{$client->id}}" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);">
                            {{ csrf_field() }}
                            <div class="col-md-12">
                                <strong>Phần trăm chiết khấu</strong>
                                <input type="number" name="chietkhau" max="100" min="0" class="form-control" value="{{$client->chietkhau}}" placeholder="%" autocomplete="off" required>
                                <br>
                                <input class="btn btn-primary pull-right" type="submit" name="myButton" value="Sửa">
                            </div>

                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>

            </div>
        </div>






@endsection

