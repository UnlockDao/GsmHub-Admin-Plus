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
                    <table class="table align-items-center table-flush">
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
                                        <td @if($v->status == 'soft_deleted' )style="text-decoration: line-through;"@endif  contenteditable="true" onBlur="saveToDatabase(this,'service_name','services','{{$v->id}}')" onClick="showEdit(this);">{{$v->service_name}}</td>
                                        <td contenteditable="true" onBlur="saveToDatabase(this,'purchase_cost','services','{{$v->id}}')" onClick="showEdit(this);">{{round($v->purchase_cost, 2)}}</td>
                                        <td contenteditable="true" onBlur="saveToDatabase(this,'credit','services','{{$v->id}}')" onClick="showEdit(this);">{{round($v->credit, 2)}}</td>
                                        @foreach($usergroup as $u)
                                              @foreach($v->clientgroupprice->where('service_type','imei')->where('group_id',$u->id)->where('currency',$currenciessite->config_value) as $cl)
                                                    <td contenteditable="true" onBlur="saveToDatabase(this,'discount','price','{{$v->id}}','{{$u->id}}')" onClick="showEdit(this);">{{round($v->credit + $cl->discount, 2)}}</td>
                                                @endforeach
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


    <script>
        function showEdit(editableObj) {
            $(editableObj).css("background","#FFF");
        }

        function saveToDatabase(editableObj,column,type,id,idgr) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
            $.ajax({
                url: "imeiquickedit",
                type: "POST",
                data:{ column: column, type : type, value : editableObj.innerText, id : id, idgr : idgr} ,
                success: function(data){
                    console.log(data);
                    $(editableObj).css("background","#FDFDFD");
                }
            });
        }
    </script>

@endsection

