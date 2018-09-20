@extends('layouts.header')
@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(document).on("click", ".status", function () {
                var ida = $(this).parent().attr('id');
                var url = '';
                if ($(this).prop('checked') == true) {
                    url = 'clientgroup/status/active';
                }
                else if ($(this).prop('checked') == false) {
                    url = 'clientgroup/status/inactive';
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
                        <button type="button" onfocus="tableToExcel('testTable', 'W3C Example Table')"
                                class="btn btn-info pull-right"><i class="material-icons">cloud_download</i>
                        </button>
                        <h4 class="card-title ">IMEI Service</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="testTable" class="table">
                                <thead class="text-primary">
                                <th width="2%">ID</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>%</th>
                                <th>Edit</th>
                                <th>Delete</th>

                                </thead>
                                <tbody>
                                @foreach($client as $v)
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->group_name}}</td>
                                        <td><div class="togglebutton">
                                                <label id="{{$v->id}}">
                                                    <input class="status" id="check{{$v->id}}" type="checkbox"
                                                    @if($v->status == 'active')checked="" @endif>
                                                    <span class="toggle"></span>
                                                </label>
                                            </div></td>
                                        <td>{{$v->chietkhau}}</td>
                                        <td><a class="material-icons "
                                               href="{{ asset('') }}clientgroup/{{$v->id}}">edit</a></td>
                                        <td><a href="{{ asset('') }}clientgroupdelete/{{$v->id}}" onclick="return confirm('OK to delete!');"><i class="material-icons">close</i></a></td>
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

