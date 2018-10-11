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
                    error: function (result) {
                        alert('error');
                    }
                });
            });


            $(document).on("click", ".defaultcurrency", function () {
                var ida = $(this).parent().attr('id');
                var url = '';
                if ($(this).prop('checked') == true) {
                    url = 'defaultcurrency/status/yes';
                }
                else if ($(this).prop('checked') == false) {
                    url = 'defaultcurrency/status/no';
                }
                $.ajax({
                    url: url,
                    type: 'get',
                    data: 'id=' + ida,
                    success: function (result) {
                        $.notify({icon: "notifications", message: result});
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
        }, false);
    </script>
    <!-- Page content -->
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0">Currencie</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="text-primary">
                            <th width="2%">ID</th>
                            <th>Currency Code</th>
                            <th>Currency Name</th>
                            <th>Exchange Rate</th>
                            <th>Status</th>
                            <th>Display</th>
                            <th>Default</th>
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
                                    <td>
                                        <div class="togglebutton">
                                            <label id="{{$v->id}}" class="custom-toggle">
                                                <input class="status" id="check{{$v->id}}" type="checkbox"
                                                       @if($v->display_currency == 'Yes' )checked="" @endif>
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <label class="custom-toggle" id="{{$v->id}}">
                                                <input class="form-check-input defaultcurrency" id="defaultcurrency{{$v->id}}" type="checkbox" @if($v->currenciepricing ==! null  )checked @endif>
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td><a class="material-icons "
                                           href="{{ asset('') }}currencie/{{$v->id}}"><i class="ni ni-zoom-split-in"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav aria-label="...">
                            <ul class="pagination justify-content-end mb-0">

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

@endsection
