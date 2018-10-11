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
    <!-- Page content -->
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-6 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Clientgroup</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-6 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">Add</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
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
                                                        <label id="{{$v->id}}" class="custom-toggle">
                                                            <input class="status" id="check{{$v->id}}" type="checkbox"
                                                                   @if($v->status == 'active')checked="" @endif>
                                                            <span class="custom-toggle-slider rounded-circle"></span>
                                                        </label>
                                                    </div></td>
                                                <td>{{$v->chietkhau}}</td>
                                                <td><a class="material-icons "
                                                       href="{{ asset('') }}clientgroup/{{$v->id}}"><i class="ni ni-zoom-split-in"></i></a></td>
                                                <td><a href="{{ asset('') }}clientgroupdelete/{{$v->id}}" onclick="return confirm('OK to delete!');"><i class="ni ni-fat-remove"></i></a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
