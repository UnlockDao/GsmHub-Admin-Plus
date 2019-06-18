@extends('layouts.header')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Check Transaction</h4>
                </div>
            </div>
        </div>
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="simpleinput"></label>
                                    <select class="form-control form-control-alternative" name="receiver_email">
                                        @foreach($paypal_receiver_details as $v)
                                            <option>
                                                {{$v->receiver_email}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> <!-- end col -->

                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="simpleinput">ID Transaction</label>
                                    <input type="text" name="idTransaction" value="{{$cache->idTransaction}}"
                                           id="simpleinput" class="form-control">
                                </div> <!-- end col -->
                                <div class="col-lg-2">
                                    <label for="simpleinput">Check</label>
                                    <input type="submit" value="Submit" class="btn-success form-control">
                                </div> <!-- end col -->

                            </div>
                        </form>
                        <!-- end row-->

                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div><!-- end col -->
        </div>
        @if($data)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Invoice Logo-->
                            <div class="clearfix">
                                <div class="float-right">
                                    <h4 class="m-0 d-print-none">Transaction details {{$data['PAYERSTATUS']}}</h4>
                                </div>
                            </div>

                            <!-- Invoice Detail-->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="float-left mt-3">
                                        <p><b>Payment received
                                                from </b>
                                        </p>
                                        <h6>Billing Address</h6>
                                        <address>
                                            {{$data['EMAIL']}}
                                            <br>
                                            <span class="badge badge-success">{{$data['PAYERSTATUS']}}</span>
                                        </address>

                                    </div>

                                </div><!-- end col -->
                                <div class="col-sm-4 offset-sm-2">
                                    <div class="mt-3 float-sm-right">
                                        <p class="font-13"><strong>Payment Date: {{$data['TIMESTAMP']}} </strong><span
                                                    class="float-right"></span></p>
                                        <p class="font-13"><strong>Payment Status: </strong> <span
                                                    class="badge badge-success float-right">{{$data['PAYMENTSTATUS']}}</span>
                                        </p>
                                        <p class="font-13"><strong>Transaction ID: {{$data['TRANSACTIONID']}}</strong>
                                            <span
                                                    class="float-right"></span></p>
                                        <p class="font-13"><strong>Payment ID: {{$data['PAYERID']}}</strong> <span
                                                    class="float-right"></span></p>
                                    </div>
                                </div><!-- end col -->
                            </div>
                            <!-- end row -->
                            <!-- end row -->
                            <!-- end row -->

                            <div class="row">
                                <div class="col-sm-6">

                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="float-right mt-3 mt-sm-0">
                                        <p><b>Sub-total:</b> <span
                                                    class="float-right"> {{$data['AMT']}}</span>
                                        </p>
                                        <p><b>Fee:</b> <span
                                                    class="float-right"> {{$data['FEEAMT']}}</span>
                                        </p>
                                        <h3>{{$data['AMT']-$data['FEEAMT']}}</h3>
                                    </div>
                                    <div class="clearfix"></div>
                                </div> <!-- end col -->
                            </div>
                            <!-- end row-->


                            <!-- end buttons -->

                        </div> <!-- end card-body-->
                    </div> <!-- end card -->
                </div> <!-- end col-->
            </div>
        @endif


    </div>
@endsection
