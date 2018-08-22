@extends('layouts.header')
@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(document).on("click", ".status", function () {
                var ida = $(this).parent().attr('id');
                var url = '';
                if ($(this).prop('checked') == true) {
                    url = 'currencie/status/yes';
                }
                else if ($(this).prop('checked') == false) {
                    url = 'currencie/status/no';
                }
                $.ajax({
                    url: url,
                    type: 'get',
                    data: 'id=' + ida,
                    success: function (result) {
                        $.notify({icon: "notifications", message: result});
                    },
                    error: function(result) {
                        alert('error');
                    }
                });
            });
        }, false);
    </script>
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
                        <h4 class="card-title ">Currency</h4>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="testTable" class="table">
                                <thead class="text-primary">
                                <th width="2%">ID</th>
                                <th>Currency Code</th>
                                <th>Currency Name</th>
                                <th>Exchange Rate</th>
                                <th>Status</th>
                                <th>Display</th>
                                <th>Edit</th>

                                </thead>
                                <tbody>
                                @foreach($currencie as $v)
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->currency_code}}</td>
                                        <td>{{$v->currency_name}}</td>
                                        <td>{{$v->exchange_rate_static}}</td>
                                        <td>{{$v->status}}</td>
                                        <td><div class="togglebutton">
                                                <label id="{{$v->id}}">
                                                    <input class="status" id="check{{$v->id}}" type="checkbox" @if($v->display_currency == 'Yes' )checked="" @endif>
                                                    <span class="toggle"></span>
                                                </label>
                                            </div></td>
                                        <td><a class="material-icons "
                                               href="{{ asset('') }}currencie/{{$v->id}}">edit</a></td>
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

