<?php

namespace App\Http\Controllers;

use App\Clientgroup;
use App\Currencie;
use App\Currenciepricing;
use App\Serverservice;
use App\Serverserviceclientgroupcredit;
use App\Serverservicegroup;
use App\Serverservicetypewisegroupprice;
use App\Serviceservicepricing;
use App\Supplier;
use Illuminate\Http\Request;

class ServerserviceController extends Controller
{

    public function index(Request $request)
    {
        $this->checkServer();
        $server_service_group = Serverservicegroup::get();
        $serverservice = Serverservice::get();
        $clientgroup = Clientgroup::get();
        return view('serverservice', compact('serverservice','server_service_group','clientgroup'));
    }

    public function checkServer()
    {
        $imei_services = Serverservice::orderBy('id')->get();
        foreach ($imei_services as $v) {
            $check = Serviceservicepricing::where('id', $v->id)->first();
            if ($check == null) {
                $imei_service = new Serviceservicepricing();
                $imei_service->id = $v->id;
                $imei_service->save();
            }
        }
    }

    public function show(Request $request, $id)
    {
        //find default currency
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        //find default price ck 0
        $cliendefault = Clientgroup::where('chietkhau', '0')->first();

        $supplier = Supplier::get();
        $serverservice = Serverservice::find($id);
        return view('edit.editserver', compact('serverservice', 'supplier', 'exchangerate'));
    }

    public function updatesupplier($id, Request $request)
    {
        $imei = Serviceservicepricing::find($id);
        $imei->id_supplier = $request->id_supplier;
        $imei->save();
        return back();
    }

    public function edit($id, Request $request)
    {

        //lưu giá trị nhập vào bộ nhớ tạm
        $servicepricing = Serviceservicepricing::find($id);
        $servicepricing->purchasecost = $request->purchase_cost;
        $servicepricing->save();
        // cập nhập  purchasecost net
        $purchasecostnet = Serverservice::find($id);
        $purchasecostnet->purchase_cost = $request->purchasenet;
        $purchasecostnet->save();
        $clientgroup = Clientgroup::get();
        $range = Serverserviceclientgroupcredit::get();
        foreach ($range as $ra) {
            foreach ($clientgroup as $cli) {
                $u = $request->input('client_group_' . $cli->id . '_' . $ra->id);
                if ($u == !null) {
                    $update = Serverserviceclientgroupcredit::where('id', $ra->id)
                        ->where('client_group_id', $cli->id)
                        ->update(['credit' => $u]);
                }
            }
        }
        return back();
    }

    public function editwise($id, Request $request)
    {
        $clientgroup = Clientgroup::get();
        $server_service_type_wise_groupprice = Serverservicetypewisegroupprice::where('server_service_id', $id)->get();
        foreach ($server_service_type_wise_groupprice as $sgp) {
            foreach ($clientgroup as $cli) {
                $u = $request->input('client_group_amount_' . $sgp->id . '_' . $cli->id);
                if ($u == !null) {
                    echo 'client_group_amount_' . $sgp->id . '_' . $cli->id . ' | ' . $u . '<br>';
                    $update = Serverservicetypewisegroupprice::where('id', $sgp->id)
                        ->where('group_id', $cli->id)
                        ->update(['amount' => $u]);
                }
            }

        }
        return back();
    }

    public function status($par = NULL, $par2 = NULL)
    {
        if ($par == "status") {
            $id = $_GET['id'];
            $imei = Serverservice::find($id);
            $imei->status = $par2;
            $imei->save();
            if ($par2 == 'active') {
                echo 'Active Server Services';
            } else {
                echo 'Disable Server Services';
            }
            exit();
        }
        return;
    }
}
