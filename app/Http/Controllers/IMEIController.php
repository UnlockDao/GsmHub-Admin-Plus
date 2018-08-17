<?php

namespace App\Http\Controllers;

use App\Clientgroup;
use App\Clientgroupprice;
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
            $check = Imeiservicepricing::where('id_imei', $v->id)->first();
            if ($check == null) {
                $imei_service = new Imeiservicepricing();
                $imei_service->id_imei = $v->id;
                $imei_service->save();
            } else {
                $imei_serviceud = Imeiservicepricing::where('id_imei', $v->id)->update(['id_imei' => $v->id]);
            }
        }
        return $imei_service;
    }

    public function imei(Request $request)
    {
        $group = Imeiservicegroup::get();
        $imei_service = Imeiservicepricing::get();
        $usergroup = Clientgroup::where('status', 'active')->orderBy('chietkhau')->get();

        return view('imeiservice', compact('imei_service', 'group', 'usergroup'));
    }

    public function json()
    {

        //data kiểm tra dữ liệu qua json
        $serverid = 1;
        $giabanle = 43.18;
        $gia = 34;


        $getimei = Imeiservice::get();
        $tigiagoc = 22000;
//        $imeidata = Imeiservicepricing::with('nhacungcap')->with(['imei' => function ($query) {
//        }, 'imei.clientgroupprice' => function ($query) {
//            $query->where('currency', 'USD')->where('group_id', '19');
//        }])->find($serverid);
//
//        $tigianhap = $imeidata->nhacungcap->tigia;
//        $phigd = $imeidata->nhacungcap->phi;
//        $giaphi = ($tigianhap * $gia) / $tigiagoc + (($gia / 100) * $phigd);
//
//        $updatetigia = Imeiservice::where('id', $imeidata->imei->id)->update(['purchase_cost' => $giaphi]);
//
//        $getgiauser = $giabanle - $getimei->credit;
//
//        $updategiause = Clientgroupprice::where('group_id', '19')->where('currency', 'USD')->where('service_id', $serverid)->update(['discount' => $getgiauser]);

        return $getimei;
    }

    public function show($id)
    {

//        $bakimei= Imeiservicepricing::get();
//        $clien= Clientgroup::get();
//        $imei = Imeiservice::where('currency', 'USD')->where('group_id', '19')->find($bakimei->id_imei);
//        $nhacungcap = Supplier::get();
//        return view('edit.editimei', compact('imei', 'nhacungcap','clien'));

        $clien = Clientgroup::get();
        $imei = Imeiservicepricing::with('nhacungcap')->with(['imei' => function ($query) {
        }, 'imei.clientgroupprice' => function ($query) {
            $query->where('currency', 'USD')->where('group_id', '19');
        }])->find($id);
        $nhacungcap = Supplier::get();

        $imeiservice = Imeiservice::find($imei->id_imei);

        $pricegroup = Clientgroupprice::orderBy('group_id', 'desc')->where('currency', 'USD')->where('service_id', $imeiservice->id)->get();
        return view('edit.editimei', compact('imei', 'nhacungcap', 'clien', 'pricegroup'));
    }

    public function edit($id, Request $request)
    {

        $gia = $request->gianhap;
        //lưu giá trị nhập vào bộ nhớ tạm
        $imei = Imeiservicepricing::find($id);
        $imei->gianhap = $gia;
        $imei->id_nhacungcap = $request->id_nhacungcap;
        $imei->save();

        //lấy id imei server
        $serverid = $imei->imei->id;
        //lấy dữ liệu nhập thủ công
        $giabanle = $request->giabanle;

        //lấy dữ liệu imei server
        $getimei = Imeiservice::find($serverid);
        //đặt tỉ  giá gốc
        $tigiagoc = 22000;

        //gọi nhóm user
        $group_user = Clientgroup::get();

        //ghi dữ liệu giá vào nhóm user
        foreach ($group_user as $u) {
            //gọi id nhóm user
            $idclient = $u->id;
            // tìm nhà cung cấp lấy chiết khấu, phần trăm
            $imeidata = Imeiservicepricing::with('nhacungcap')->with(['imei' => function ($query) {
            }, 'imei.clientgroupprice' => function ($query) use ($idclient) {
                $query->where('currency', 'USD')->where('group_id', $idclient);
            }])->find($id);

            //gọi dữ liệu nhà cung cấp (tỉ giá, phí )
            $tigianhap = $imeidata->nhacungcap->tigia;
            $phigd = $imeidata->nhacungcap->phi;
            //tính giá đã bao gồm phí chuyển đổi
            $giaphi = ($tigianhap * $gia) / $tigiagoc + (($gia / 100) * $phigd);
            //cập nhập giá gốc vào data
            $updatetigia = Imeiservice::where('id', $imeidata->imei->id)->update(['purchase_cost' => $giaphi]);

            //tính chiết khấu từng nhóm user
            // $getgiauser = ($giabanle - ((($giabanle - $giaphi) / 100) * $u->chietkhau)) - $getimei->credit;
            // cập nhập giá cho user
            // $updategiause = Clientgroupprice::where('group_id', $idclient)->where('currency', 'USD')->where('service_id', $serverid)->update(['discount' => $getgiauser]);
            // $updategiause = Clientgroupprice::where('group_id', $idclient)->where('currency', 'VND')->where('service_id', $serverid)->update(['discount' => $getgiauser]);

            $u = $request->input('giabanle' . $idclient);
            $y = $u - $getimei->credit;
            $updategiause = Clientgroupprice::where('group_id', $idclient)->where('currency', 'USD')->where('service_id', $serverid)->update(['discount' => $y]);
        }
        return;
    }

    public function updatesupplier($id, Request $request)
    {

        $imei = Imeiservicepricing::find($id);
        $imei->id_nhacungcap = $request->id_nhacungcap;
        $imei->save();

        return back();
    }

}
