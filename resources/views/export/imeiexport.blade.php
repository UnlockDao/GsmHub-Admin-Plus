@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Service Group</strong>
                            <select class="form-control form-control-alternative" name="group_name">
                                <option value="">...</option>
                                @foreach($groupsearch as $g )
                                    <option value="{{$g->id}}"
                                            @if($cachesearch->group_name == $g->id) selected @endif>{{$g->group_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Service Type</strong>
                            <select class="form-control form-control-alternative" name="type">
                                <option value="">...</option>
                                <option value="api" @if($cachesearch->type == 'api')selected @endif>API</option>
                                <option value="manual" @if($cachesearch->type == 'manual')selected @endif>MANUAL
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Status</strong>
                            <select class="form-control form-control-alternative" name="status">
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
                            <select class="form-control form-control-alternative" name="supplier">
                                <option value="">...</option>
                                @foreach($supplier as $s)
                                    <option value="{{$s->id}}"
                                            @if($cachesearch->supplier == $s->id)selected @endif>{{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Currency</strong>
                            <select class="form-control form-control-alternative" name="currency">
                                <option value="">...</option>
                                @foreach($currencies as $c)
                                    <option value="{{$c->id}}"
                                            @if($cachesearch->currency == $c->id)selected @endif>{{$c->currency_code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <br>
                            <button class="btn btn-info" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
                <div class="card-body">
                    <table id="imeiquickedit" class="table align-items-center table-flush">
                        <thead class="text-primary" id="myHeader">
                        <th></th>
                        <th>Service Name</th>
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
                                <td colspan="4"><strong style="font-weight:700;">{{$g->group_name}}</strong></td>
                                @foreach($usergroup as $u)
                                    <td></td>
                                @endforeach
                            </tr>
                            @foreach($imei_service as $v)
                                @if($v->imei_service_group_id == $g->id )
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td @if($v->status == 'soft_deleted' )style="text-decoration: line-through;"@endif>
                                            {{$v->service_name}}</td>
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
                </div>


            </div>
        </div>
    </div>

    <script src="js/jquery.tabledit.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#imeiquickedit').Tabledit({
                url: 'imeiquickedit',
                editButton: false,
                deleteButton: false,
                hideIdentifier: true,
                columns: {
                    identifier: [0, 'id'],
                    editable: [[1, 'service_name'], [2, 'purchase_cost'], [3, 'credit']]
                },
                onDraw: function() {
                    console.log('onDraw()');
                },
                onSuccess: function(data, textStatus, jqXHR) {
                    console.log('onSuccess(data, textStatus, jqXHR)');
                    console.log(data);
                    console.log(textStatus);
                    console.log(jqXHR);
                },
                onFail: function(jqXHR, textStatus, errorThrown) {
                    console.log('onFail(jqXHR, textStatus, errorThrown)');
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                onAlways: function() {
                    console.log('onAlways()');
                },
                onAjax: function(action, serialize) {
                    console.log('onAjax(action, serialize)');
                    console.log(action);
                    console.log(serialize);
                }
            });


        });

    </script>




@endsection

