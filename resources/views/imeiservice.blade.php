@extends('layouts.header')
@section('content')
    <div class="container-fluid">
        <?php
        $tigiagoc = 22000;
        $giaphi = '';
        ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
                            <div class="card-icon">
                                <i class="material-icons">monetization_on</i>
                            </div>
                            <h3 class="card-title ">IMEI Services</h3>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="testTable" class="table">
                                    <thead class="text-primary">
                                    <th width="2%"></th>
                                    <th>Service Name</th>
                                    <th>Service Type</th>
                                    <th>Supplier</th>
                                    <th>Purchase Cost</th>
                                    <th>Purchase Cost (Net)</th>
                                    <th>Default</th>
                                    @foreach($usergroup as $u)
                                        <th>{{$u->group_name}}</th>
                                    @endforeach
                                    <th>Edit</th>

                                    </thead>
                                    <tbody>
                                    @foreach($group as $g)
                                        <tr><td><i class="material-icons">monetization_on</i></td><td><strong style="font-weight:700;">{{$g->group_name}}</strong></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    @foreach($imei_service as $v)
                                        @if($v->imei->imei_service_group_id == $g->id)
                                            <tr id="{{$v->id_imei}}">
                                                <td>{{$v->id_imei}}</td>
                                                <td @if($v->imei->status == 'soft_deleted' )style="text-decoration: line-through;"@endif>{{$v->imei->service_name}}</td>
                                                <td>@if($v->imei->pricefromapi == 1) API @else Manual @endif</td>
                                                <td>@if($v->nhacungcap ==! null){{$v->nhacungcap->name}}@endif</td>
                                                @if($v->imei->pricefromapi == 1)
                                                    <td>{{$v->imei->api_credit}}</td>
                                                @else
                                                    <td>{{$v->gianhap}}</td>
                                                @endif


                                                <td>{{$v->imei->purchase_cost}}</td>
                                                <td>{{$v->imei->credit}}</td>
                                                @foreach($usergroup as $u)
                                                    @foreach($v->imei->clientgroupprice as $cl)
                                                        @if($cl->currency == 'USD' && $cl->service_type == 'imei' && $cl->group_id == $u->id )
                                                            <td> <?php echo number_format($v->imei->credit + $cl->discount,2); ?> </td>
                                                        @endif
                                                    @endforeach
                                                @endforeach


                                                <td>@if($v->imei->status == 'active')<a
                                                            class="material-icons fancybox fancybox.iframe"
                                                            href="{{ asset('') }}imei/{{$v->id}}">edit</a>@endif</td>
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
            </div>


    </div>





@endsection

