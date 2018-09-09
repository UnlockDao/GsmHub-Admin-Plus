<?php

namespace App\Http\Controllers;

use App\Clientgroup;
use App\Clientgroupprice;
use App\Currencie;
use App\Currenciepricing;
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
            }
        }
        return redirect('/imei');
    }

    public function imei(Request $request)
    {
        $this->checkdelete();
        $this->checkimei();
        $this->checkapi();
        //
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        //
        $group = Imeiservicegroup::get();
        $imei_service = Imeiservicepricing::get();
        $usergroup = Clientgroup::where('status', 'active')->orderBy('chietkhau')->get();

        return view('imeiservice', compact('imei_service', 'group', 'usergroup','exchangerate'));
    }

    public function checkdelete()
    {
        $imei_services = Imeiservicepricing::get();
        foreach ($imei_services as $v) {
            $check = Imeiservice::where('id', $v->id)->first();
            if ($check == null) {
                Imeiservicepricing::where('id', $v->id)->delete();
            }
        }
    }

    public function checkimei()
    {
        $imei_services = Imeiservice::orderBy('id')->get();
        foreach ($imei_services as $v) {
            $check = Imeiservicepricing::where('id', $v->id)->first();
            if ($check == null) {
                $imei_service = new Imeiservicepricing();
                $imei_service->id = $v->id;
                $imei_service->save();
            }
        }
    }

    public function checkapi()
    {
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        $checkapi = Imeiservicepricing::get();
        foreach ($checkapi as $c) {
            if ($c->imei->api_id == !null) {
                if ($c->nhacungcap == !null) {
                    $giatransactionfee = ($c->nhacungcap->exchangerate * $c->imei->apiserverservices->credits) / $exchangerate->exchange_rate_static + (($c->imei->apiserverservices->credits / 100) * $c->nhacungcap->transactionfee);
                    $updategiatransactionfee = Imeiservice::where('id', $c->id)->update(['purchase_cost' => $giatransactionfee]);
                }
            } elseif ($c->imei->purchasecost == !null) {
                if ($c->nhacungcap == !null) {
                    $giatransactionfee = ($c->nhacungcap->exchangerate * $c->imei->purchasecost) / $exchangerate->exchange_rate_static + (($c->imei->purchasecost / 100) * $c->nhacungcap->transactionfee);
                    $updategiatransactionfee = Imeiservice::where('id', $c->id)->update(['purchase_cost' => $giatransactionfee]);
                }
            }
        }
        $checkenableapi = Imeiservice::where('pricefromapi', '<>', '0')->get();
        foreach ($checkenableapi as $ci) {
            $updateenableapi = Imeiservice::where('id', $ci->id)->update(['pricefromapi' => '0']);
        }
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

        //find default currency
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        //find default price ck 0
        $cliendefault = Clientgroup::where('chietkhau', '0')->first();

        $imei = Imeiservicepricing::with('nhacungcap')->with(['imei' => function ($query) {
        }, 'imei.clientgroupprice' => function ($query) use ($cliendefault) {
            $query->where('currency', 'USD')->where('group_id', $cliendefault->id);
        }])->find($id);
        $nhacungcap = Supplier::get();

        $imeiservice = Imeiservice::find($id);

        $pricegroup = Clientgroupprice::orderBy('group_id', 'desc')->where('currency', 'USD')->where('service_type', 'imei')->where('service_id', $id)->get();

        $price = Clientgroupprice::where('currency', 'USD')->where('group_id', $cliendefault->id)->where('service_id', $id)->first();

        return view('edit.editimei', compact('imei', 'nhacungcap', 'clien', 'pricegroup', 'price', 'exchangerate'));
    }

    public function edit($id, Request $request)
    {

        $gia = $request->purchasecost;
        //lưu giá trị nhập vào bộ nhớ tạm
        $imei = Imeiservicepricing::find($id);
        $imei->purchasecost = $gia;
        $imei->id_supplier = $request->id_supplier;
        $imei->save();
        //lấy dữ liệu nhập thủ công
        $giabanle = $request->giabanle;
        $updateexchangerate = Imeiservice::where('id', $id)->update(['purchase_cost' => $request->purchasenet, 'credit' => $request->credit]);
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
        $imei->id_supplier = $request->id_supplier;
        $imei->save();

        //tính giá + phí
        $exchangerate = Currencie::where('currency_code', 'VND')->first();
        $imeiprice = Imeiservicepricing::where('id_supplier', $request->id_supplier)->find($id);
        if ($imeiprice->imei->api_id == !null && $imeiprice->imei->apiserverservices == !null) {
            $giatransactionfee = ($imeiprice->nhacungcap->exchangerate * $imeiprice->imei->apiserverservices->credits) / $exchangerate->exchange_rate_static + (($imeiprice->imei->apiserverservices->credits / 100) * $imeiprice->nhacungcap->transactionfee);
            $updategiatransactionfee = Imeiservice::where('id', $id)->update(['purchase_cost' => $giatransactionfee]);
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
