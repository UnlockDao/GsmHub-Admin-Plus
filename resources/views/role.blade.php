@extends('layouts.header')
@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(document).on("click", ".status", function () {
                var ida = $(this).parent().attr('id');
                var url = '';
                if ($(this).prop('checked') == true) {
                    url = 'role/status/1';
                }
                else if ($(this).prop('checked') == false) {
                    url = 'role/status/0';
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
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-rose">
                        <div class="card-icon">
                            <i class="material-icons">monetization_on</i>
                        </div>
                        <h4 class="card-title ">Currency</h4>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="testTable" class="table">
                                <thead class="text-primary">
                                <th width="2%">ID</th>
                                <th>Name</th>
                                <th>Role</th>


                                </thead>
                                <tbody>
                                @foreach($administrator as $v)
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->user->user_name}}</td>
                                        <td>
                                            <div class="togglebutton">
                                                <label id="{{$v->id}}">
                                                    <input class="status" id="check{{$v->id}}" type="checkbox" @if($v->administrator_role_id == '1') disabled checked @endif
                                                           @if($v->role_adminplus == '1' )checked="" @endif>
                                                    <span class="toggle"></span>
                                                </label>
                                            </div>
                                        </td>
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

