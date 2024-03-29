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
                        $.NotificationApp.send("Status", result, 'top-right', 'Background color', 'Icon');
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
        }, false);
    </script>

    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Role</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="text-primary">
                                <th width="2%">ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Access</th>
                                </thead>
                                <tbody>
                                @foreach($administrator as $v)
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->user->user_name}}</td>
                                        <td>
                                            <div class="togglebutton">
                                                <label id="{{$v->id}}" class="custom-toggle">
                                                    <input class="status" id="check{{$v->id}}" type="checkbox" @if($v->user_id == '1' || $v->user->is_super_admin == '1') disabled checked @endif
                                                    @if($v->role_adminplus == '1' )checked="" @endif>
                                                    <span class="custom-toggle-slider rounded-circle"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-alternative"  onchange="saveToDatabase(this.value,'{{$v->id}}')" name="supplier_access">
                                                <option @if($v->supplier_access == 'Saff') selected @endif value="Saff">Saff</option>
                                                <option @if($v->supplier_access == 'ServiceManager') selected @endif value="ServiceManager">ServiceManager</option>
                                                <option @if($v->supplier_access == 'Admin') selected @endif value="Admin">Admin</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->

    <script>

        function saveToDatabase(editableObj, id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "supplier_access",
                type: "POST",
                data: {value: editableObj, id: id},
                success: function (data) {
                    $(editableObj).css("background", "#FDFDFD");
                    console.log(data);
                }
            });
        }
    </script>
@endsection
