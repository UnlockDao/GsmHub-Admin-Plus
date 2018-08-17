@extends('layouts.header')
@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-rose">
                        <div class="card-icon">
                            <i class="material-icons">monetization_on</i>
                        </div>
                        <button type="button" onclick="tableToExcel('testTable', 'W3C Example Table')"
                                class="btn btn-info pull-right"><i class="material-icons">cloud_download</i>
                        </button>
                        <h4 class="card-title ">IMEI Service</h4>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="testTable" class="table">
                                <thead class="text-primary">
                                <th width="2%">ID</th>
                                <th>Source</th>
                                <th> Mony </th>
                                <th> Phí GD </th>
                                <th> Sửa </th>

                                </thead>
                                <tbody>
                                @foreach($apiserver as $v)
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->server_name}}</td>
                                        <td>{{$v->tigia}}</td>
                                        <td>{{$v->phi}}</td>
                                        <td><a class="material-icons " href="{{ asset('') }}tigia/{{$v->id}}">edit</a></td>
                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        </div>





@endsection

