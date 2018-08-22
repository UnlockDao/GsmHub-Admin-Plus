<?php

namespace App\Http\Controllers;

use App\Clientgroup;
use App\Clientgroupprice;
use App\Currencie;
use App\Imeiservice;
use App\Imeiservicegroup;
use App\Imeiservicepricing;
use App\Supplier;
use Illuminate\Http\Request;

class IMEIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //xử lí get dữ liệu về data tạm
        $imei_service = Imeiservice::orderBy('id')->get();
        foreach ($imei_service as $v) {
            $check = Imeiservicepricing::where('id', $v->id)->first();
            if ($check == null) {
                $imei_service = new Imeiservicepricing();
                $imei_service->id = $v->id;
                $imei_service->save();
            } else {
                $imei_serviceud = Imeiservicepricing::where('id', $v->id)->update(['id' => $v->id]);
            }
        }
        return redirect('/imei');
    }

    public function checkapi(){
        $exchangerate = Currencie::where('currency_code','VND')->first();
        $checkapi = Imeiservicepricing::get();
        foreach($checkapi as $c){
            if($c->imei->api_id ==! null){
                if($c->nhacungcap ==! null){
                $giaphi = ($c->nhacungcap->tigia * $c->imei->apiserverservices->credits) / $exchangerate->exchange_rate_static + (($c->imei->apiserverservices->credits / 100) * $c->nhacungcap->phi);
                $updategiaphi = Imeiservice::where('id', $c->id)->update(['purchase_cost' => $giaphi]);
                }
            }
            elseif($c->imei->gianhap ==! null){
                if($c->nhacungcap ==! null) {
                    $giaphi = ($c->nhacungcap->tigia * $c->imei->gianhap) / $exchangerate->exchange_rate_static + (($c->imei->gianhap / 100) * $c->nhacungcap->phi);
                    $updategiaphi = Imeiservice::where('id', $c->id)->update(['purchase_cost' => $giaphi]);
                }
            }
        }
        $checkenableapi = Imeiservice::where('pricefromapi','<>','0')->get();
        foreach ($checkenableapi as $ci){
            $updateenableapi  = Imeiservice::where('id', $ci->id)->update(['pricefromapi' => '0']);
        }
    }

    public function imei(Request $request)
    {
        $this->checkapi();
        $imei_service = Imeiservice::orderBy('id')->get();
        foreach ($imei_service as $v) {
            $check = Imeiservicepricing::where('id', $v->id)->first();
            if ($check == null) {
                $imei_service = new Imeiservicepricing();
                $imei_service->id = $v->id;
                $imei_service->save();
            } else {
                $imei_serviceud = Imeiservicepricing::where('id', $v->id)->update(['id' => $v->id]);
            }
        }

        $group = Imeiservicegroup::get();
        $imei_service = Imeiservicepricing::get();
        $usergroup = Clientgroup::where('status', 'active')->orderBy('chietkhau')->get();

        return view('imeiservice', compact('imei_service', 'group', 'usergroup'));
    }


    public function show($id)
    {
        $clien = Clientgroup::get();
        $pg = Clientgroupprice::where('service_id', $id)->get();
        foreach ($pg as $p) {
            foreach ($clien as $c) {
                $check = Clientgroupprice::where('group_id', $c->id)->where('service_id', $id)->first();
                if ($check == null) {
                    $newx = new Clientgroupprice();
                    $newx->service_id = $id;
                    $newx->group_id = $c->id;
                    $newx->currency = 'USD';
                    $newx->save();
                }
            }
        }

        $exchangerate = Currencie::where('currency_code', 'VND')->first();

        $imei = Imeiservicepricing::with('nhacungcap')->with(['imei' => function ($query) {
        }, 'imei.clientgroupprice' => function ($query) {
            $query->where('currency', 'USD')->where('group_id', '19');
        }])->find($id);
        $nhacungcap = Supplier::get();

        $imeiservice = Imeiservice::find($id);

        $pricegroup = Clientgroupprice::orderBy('group_id', 'desc')->where('currency', 'USD')->where('service_type', 'imei')->where('service_id', $id)->get();

        $price = Clientgroupprice::where('currency', 'USD')->where('group_id', '19')->where('service_id', $id)->first();

        return view('edit.editimei', compact('imei', 'nhacungcap', 'clien', 'pricegroup', 'price', 'exchangerate'));
    }

    public function edit($id, Request $request)
    {

        $gia = $request->gianhap;
        //lưu giá trị nhập vào bộ nhớ tạm
        $imei = Imeiservicepricing::find($id);
        $imei->gianhap = $gia;
        $imei->id_nhacungcap = $request->id_nhacungcap;
        $imei->save();
        //lấy dữ liệu nhập thủ công
        $giabanle = $request->giabanle;
        $updatetigia = Imeiservice::where('id', $id)->update(['purchase_cost' => $request->purchasenet, 'credit' => $request->credit]);
        //lấy dữ liệu imei server
        $getimei = Imeiservice::find($id);
        //gọi nhóm user
        $group_user = Clientgroup::get();
        //ghi dữ liệu giá vào nhóm user
        foreach ($group_user as $u) {
            $idclient = $u->id;
            $u = $request->input('giabanle' . $idclient);
            $y = $u - $getimei->credit;
            $currencies = Currencie::where('display_currency', 'Yes')->get();
            foreach ($currencies as $c) {
                $updategiause = Clientgroupprice::where('group_id', $idclient)
                    ->where('service_type', 'imei')
                    ->where('currency', $c->currency_code)
                    ->where('service_id', $id)
                    ->update(['discount' => $y * $c->exchange_rate_static]);
            }
        }
        return;
    }

    public function updatesupplier($id, Request $request)
    {

        $imei = Imeiservicepricing::find($id);
        $imei->id_nhacungcap = $request->id_nhacungcap;
        $imei->save();

        //tính giá + phí
        $exchangerate = Currencie::where('currency_code', 'VND')->first();
        $imeiprice = Imeiservicepricing::where('id_nhacungcap', $request->id_nhacungcap)->find($id);
        if ($imeiprice->imei->api_id == !null && $imeiprice->imei->apiserverservices == !null) {
            $giaphi = ($imeiprice->nhacungcap->tigia * $imeiprice->imei->apiserverservices->credits) / $exchangerate->exchange_rate_static + (($imeiprice->imei->apiserverservices->credits / 100) * $imeiprice->nhacungcap->phi);
            $updategiaphi = Imeiservice::where('id', $id)->update(['purchase_cost' => $giaphi]);
        }


        return back();
    }

    public function status($par = NULL, $par2 = NULL)
    {
        if ($par == "status") {
            $id = $_GET['id'];
            $imei = Imeiservice::find($id);
            $imei->status = $par2;
            $imei->save();
            if ($par2 == 'active') {
                echo 'Active IMEI Services';
            } else {
                echo 'Disable IMEI Services';
            }
            exit();
        }
        return;
    }

}
