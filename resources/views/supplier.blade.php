@extends('layouts.header')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-tabs card-header-rose">
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper">
                                <ul class="nav nav-tabs" data-tabs="tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#profile" data-toggle="tab">
                                            <i class="material-icons">list</i>
                                            Supplier
                                            <div class="ripple-container"></div></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#cat" data-toggle="tab">
                                            <i class="material-icons">code</i>
                                            Add Supplier
                                            <div class="ripple-container"></div><div class="ripple-container"></div></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile">
                                <div class="table-responsive">
                                    <table id="testTable" class="table">
                                        <thead class="text-primary">
                                        <th width="2%">ID</th>
                                        <th>Source</th>
                                        <th>Exchange rate </th>
                                        <th> Transaction fee</th>
                                        <th> Edit </th>

                                        </thead>
                                        <tbody>
                                        @foreach($supplier as $v)
                                            <tr>
                                                <td>{{$v->id}}</td>
                                                <td>{{$v->name}}</td>
                                                <td><?php echo number_format($v->exchangerate) ?></td>
                                                <td>{{$v->transactionfee}} %</td>
                                                <td><a class="material-icons " href="{{ asset('') }}supplier/{{$v->id}}">edit</a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="cat">
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

