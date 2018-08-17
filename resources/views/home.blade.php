@extends('layouts.header')
@section('content')
    <?php $gianhap=22000;
          $gia = 34;
          $giabanle = 43.18;
          ?>
    <div class="container-fluid">


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-rose">
                        <div class="card-icon">
                            <i class="material-icons">monetization_on</i>
                        </div>
                        <button type="button" onclick="tableToExcel('testTable', 'W3C Example Table')"
                                class="btn btn-info pull-right"><i class="material-icons">cloud_download</i>
                        </button>
                        <h4 class="card-title ">IMEI Service</h4>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="testTable" class="table">
                                    <thead class="text-primary">
                                    <th width="2%">ID</th>
                                    <th>Service Mame</th>
                                    <th>Status</th>
                                    <th>Giá</th>
                                    <th>Nguồn</th>
                                    <th>Tỉ giá nhập</th>
                                    <th>Tỉ giá gốc</th>
                                    <th>Phí</th>
                                    <th>Giá nhập</th>



                                    <th>Reseller</th>
                                    <th>Distributor</th>
                                    <th>VIP</th>
                                    <th>User</th>

                                    </thead>
                                <tbody>
                                @foreach($imei_service as $v)
                                    <tr>

                                        @if($v->tigia == null)
                                            @else
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->service_name}}</td>
                                        <td>{{$v->status}}</td>
                                        <td>{{$gia}}</td>
                                        <td>{{$v->tigia->server_name}}</td>
                                        <td>{{$v->tigia->tigia}}</td>
                                        <td>{{$gianhap}}</td>
                                        <td>{{$v->tigia->phi}}</td>
                                        <td>
                                                        <?php


                                                        $tigianhap =$v->tigia->tigia;
                                                        $tigia = $gianhap;
                                                        $phigd=$v->tigia->phi;

                                                        $giaphi =($tigianhap*$gia)/$tigia+(($gia/100)*$phigd);
                                                        echo number_format($giaphi,2);
                                                        ?>

                                        </td>



                                        <td>@if($v->clientgroupprice == null)
                                            @else
                                            @foreach($v->clientgroupprice as $i)
                                                @if($i->currency == 'USD' && $i->service_type== 'imei'&& $i->group_id== '7' )
                                                        <?php echo number_format($giabanle-((($giabanle-$giaphi)/100)*$i->chietkhau->chietkhau ),2) ?>
                                                @endif

                                            @endforeach
                                            @endif</td>

                                        <td>@if($v->clientgroupprice == null)
                                            @else
                                            @foreach($v->clientgroupprice as $i)
                                                @if($i->currency == 'USD' && $i->service_type== 'imei'&& $i->group_id== '17' )
                                                        <?php echo number_format($giabanle-((($giabanle-$giaphi)/100)*$i->chietkhau->chietkhau ),2) ?>
                                                @endif

                                            @endforeach
                                            @endif</td>

                                        <td>@if($v->clientgroupprice == null)
                                            @else
                                            @foreach($v->clientgroupprice as $i)
                                                @if($i->currency == 'USD' && $i->service_type== 'imei'&& $i->group_id== '18' )
                                                        <?php echo number_format($giabanle-((($giabanle-$giaphi)/100)*$i->chietkhau->chietkhau ),2) ?>
                                                @endif

                                            @endforeach
                                            @endif</td>

                                        <td>@if($v->clientgroupprice == null)
                                            @else
                                            @foreach($v->clientgroupprice as $i)
                                                @if($i->currency == 'USD' && $i->service_type== 'imei'&& $i->group_id== '19' )
                                                        <?php echo number_format($giabanle-((($giabanle-$giaphi)/100)*$i->chietkhau->chietkhau ),2) ?>
                                                @endif

                                            @endforeach
                                            @endif

                                        </td>
                                            @endif
                                        </tr>
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

