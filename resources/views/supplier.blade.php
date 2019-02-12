@extends('layouts.header')
@section('content')

    <!-- Page content -->
    <div class="container-fluid mt--7">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-6 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Supplier</a>
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
                                        <th>Name</th>
                                        @if(CUtil::issuperadmin())
                                        <th>Username </th>
                                        <th>Password </th>
                                        <th>Site </th>
                                        @endif
                                        <th>Exchange rate </th>
                                        <th> Transaction fee</th>
                                        <th> Edit </th>
                                        <th> Delete </th>

                                        </thead>
                                        <tbody>
                                        @foreach($supplier as $v)
                                            <tr>
                                                <td>{{$v->id}}</td>
                                                <td>{{$v->name}}</td>
                                                @if(CUtil::issuperadmin())
                                                <td>{{$v->site_username}}</td>
                                                <td>{{$v->site_password}}</td>
                                                <td><a href="{{$v->site_url}}" target="_blank">{{$v->site_url}}</a></td>
                                                @endif
                                                <td><?php echo number_format($v->exchangerate) ?></td>
                                                <td>{{$v->transactionfee}} %</td>
                                                @if(CUtil::issuperadmin())
                                                <td><a class="material-icons " href="{{ asset('') }}supplier/{{$v->id}}"><i class="ni ni-zoom-split-in"></i></a></td>
                                                <td><a href="{{ asset('') }}supplierdelete/{{$v->id}}" onclick="return confirm('OK to delete!');"><i class="ni ni-fat-remove"></i></a></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <form action="{{ url('addsupplier') }}" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group bmd-form-group">
                                                    <label class="bmd-label-floating"></label>
                                                    <input type="text" name="name" placeholder="Name" class="form-control" autocomplete="off" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group bmd-form-group">
                                                    <label class="bmd-label-floating"></label>
                                                    <input type="text" name="exchangerate" placeholder="Exchange rate" class="form-control" autocomplete="off" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group bmd-form-group">
                                                    <label class="bmd-label-floating"></label>
                                                    <input type="number" name="transactionfee" placeholder="Transaction fee" class="form-control" autocomplete="off" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-rose pull-right">Add</button>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
