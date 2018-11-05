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
                        //$.notify({icon: "notifications", message: result});
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
                        <h3 class="mb-0">Role</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
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
                                            <label id="{{$v->id}}" class="custom-toggle">
                                                <input class="status" id="check{{$v->id}}" type="checkbox" @if($v->user_id == '1' || $v->user->is_super_admin == '1') disabled checked @endif
                                                @if($v->role_adminplus == '1' )checked="" @endif>
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                    </td>
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
