@extends('layouts.salehead')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Service Group</strong>
                            <select class="form-control" data-live-search="true" name="group_name">
                                <option value="">...</option>
                                @foreach($groupsearch as $g )
                                    <option value="{{$g->id}}"
                                            @if($cachesearch->group_name == $g->id) selected @endif>{{$g->group_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Service Type</strong>
                            <select class="form-control" data-live-search="true" name="type">
                                <option value="">...</option>
                                <option value="api" @if($cachesearch->type == 'api')selected @endif>API</option>
                                <option value="manual" @if($cachesearch->type == 'manual')selected @endif>MANUAL
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Status</strong>
                            <select class="form-control" data-live-search="true" name="status">
                                <option value="">...</option>
                                <option value="active" @if($cachesearch->status == 'active')selected @endif>Active
                                </option>
                                <option value="inactive" @if($cachesearch->status == 'inactive')selected @endif>
                                    Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Supplier</strong>
                            <select class="form-control" data-live-search="true" name="supplier">
                                <option value="">...</option>
                                @foreach($supplier as $s)
                                    <option value="{{$s->id}}"
                                            @if($cachesearch->supplier == $s->id)selected @endif>{{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Currency</strong>
                            <select class="form-control" data-live-search="true" name="currency">
                                <option value="">...</option>
                                @foreach($currencies as $c)
                                    <option value="{{$c->id}}"
                                            @if($cachesearch->currency == $c->id)selected @endif>{{$c->currency_code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <strong>Search</strong><br>
                            <input value="Search" type="submit" class="btn btn-primary">
                        </div>
                    </div>
                </form>
                <div class="card-body">
                    <form action="" method="post">
                        {{ csrf_field() }}
                        <table class="table align-items-center table-flush">
                            <thead class="text-primary" id="myHeader">
                            <th style="width:20px;"><input type="checkbox"></th>
                            <th></th>
                            <th>Service Name</th>
                            <th>Type</th>
                            <th>PC (Net)</th>
                            <th>Default</th>
                            @foreach($usergroup as $u)
                                <th>{{$u->group_name}}</th>
                            @endforeach
                            </thead>
                            <tbody>
                            @foreach($group->where('imeigroup','<>','') as $g)
                                <tr class="table-warning">
                                    <td><i class="ni ni-ungroup"></i></td>
                                    <td colspan="5"><strong style="font-weight:700;">{{$g->group_name}}</strong></td>
                                    @foreach($usergroup as $u)
                                        <td></td>
                                    @endforeach
                                </tr>
                                @foreach($imei_service as $v)
                                    @if($v->imei_service_group_id == $g->id )
                                        <tr>
                                            <td><input type="checkbox" name="chk[]" value="{{$v->id}}"></td>
                                            <td>{{$v->id}}</td>
                                            <td @if($v->status == 'soft_deleted' )style="text-decoration: line-through;"@endif>
                                                <a class="@if($v->imeipricing->sale >0) badge1 @endif" @if($v->imeipricing->sale >0) data-badge="{{$v->imeipricing->sale}}" @endif > {{$v->service_name}}</a></td>
                                            <td>@if($v->api_id ==! null)<span
                                                        class="badge badge-pill badge-success">API<span>  @else<span
                                                                class="badge badge-pill badge-info">Manual<span>  @endif
                                            </td>
                                            <td>{{number_format($v->purchase_cost, 2)}}</td>
                                            <td>{{number_format($v->credit, 2)}}</td>
                                            @foreach($usergroup as $u)
                                                <td>  @foreach($v->clientgroupprice as $cl)
                                                        @if($cl->currency == $currenciessite->config_value && $cl->service_type == 'imei' && $cl->group_id == $u->id )
                                                            @if($cachesearch->currency == null)
                                                                {{number_format($v->credit + $cl->discount, 2)}}
                                                            @else
                                                                {{number_format(($v->credit + $cl->discount)*$currencies->where('id',$cachesearch->currency)->first()->exchange_rate_static)}}
                                                            @endif

                                                        @endif
                                                    @endforeach</td>
                                            @endforeach

                                        </tr>

                                    @endif
                                @endforeach
                            @endforeach


                            </tbody>
                        </table>

                        <div class="form-group">
                            <label for="sales">Sales %</label>
                            <input name="sales" type="number" value="" min="0" max="100" required placeholder="Phần trăm giảm giá" class="form-control" id="sales">
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" name="type">
                                <option value="1">Giảm theo % giá</option>
                                <option value="2">Giảm theo % lợi nhuận</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>


            </div>
        </div>
    </div>

@endsection

