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
                        $.NotificationApp.send("Status", result, 'top-right', 'Background color', 'Icon');
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
        }, false);
    </script>
    <!-- Page content -->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Client Group</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table id="testTable" class="table">
                                <thead>
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
                                                <label id="{{$v->id}}" class="custom-toggle">
                                                    <input class="status" id="check{{$v->id}}" type="checkbox"
                                                           @if($v->status == 'active')checked="" @endif>
                                                    <span class="custom-toggle-slider rounded-circle"></span>
                                                </label>
                                            </div></td>
                                        <td>{{$v->chietkhau}}</td>
                                        <td><a class=" "
                                               href="{{ asset('') }}clientgroup/{{$v->id}}"><i class="mdi mdi-eye"></i></a></td>
                                        <td><a href="{{ asset('') }}clientgroupdelete/{{$v->id}}" onclick="return confirm('OK to delete!');"><i class="mdi mdi-delete"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->

        </div>




@endsection
