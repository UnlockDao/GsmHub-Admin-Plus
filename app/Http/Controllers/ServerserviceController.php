<?php

namespace App\Http\Controllers;

use App\Clientgroup;
use App\Currencie;
use App\Currenciepricing;
use App\Serverservice;
use App\Serverserviceclientgroupcredit;
use App\Serverservicegroup;
use App\Serverservicetypewisegroupprice;
use App\Serverservicetypewiseprice;
use App\Serverserviceusercredit;
use App\Serviceservicepricing;
use App\Supplier;
use Illuminate\Http\Request;

class ServerserviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->checkServer();
        $this->UpdatePurchaseCostVip();
        $this->checkApiToNull();
        $server_service_group = Serverservicegroup::get();
        $serverservice = Serverservice::get();
        $clientgroup = Clientgroup::orderBy('chietkhau')->get();
        return view('serverservice', compact('serverservice','server_service_group','clientgroup'));
    }

    public function checkServer()
    {
        $server_services = Serverservice::orderBy('id')->get();
        foreach ($server_services as $v) {
            $check = Serviceservicepricing::where('id', $v->id)->first();
            if ($check == null) {
                $server_services = new Serviceservicepricing();
                $server_services->id = $v->id;
                $server_services->save();
            }
        }
        $server_servicesdel = Serviceservicepricing::get();
        foreach ($server_servicesdel as $v) {
            $check = Serverservice::where('id', $v->id)->first();
            if ($check == null) {
                Serviceservicepricing::where('id', $v->id)->delete();
            }
        }
    }

    public function checkApiToNull(){
        $checkenableapi = Serverservicetypewiseprice::where('pricefromapi', '<>', '0')->get();
        foreach ($checkenableapi as $ci) {
            $updateenableapi = Serverservicetypewiseprice::where('id', $ci->id)->update(['pricefromapi' => '0']);
        }

        $checkenableapi2 = Serverservice::where('pricefromapi', '<>', '0')->get();
        foreach ($checkenableapi2 as $cii) {
            $updateenableapi2 = Serverservice::where('id', $cii->id)->update(['pricefromapi' => '0']);
        }


    }

    public function UpdatePurchaseCostVip(){
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        $serviceservicepricing = Serviceservicepricing::get();
        foreach ($serviceservicepricing as $sr){
            if($sr->id_supplier ==!null){
                $serverservice= Serverservice::find($sr->id);
                $tipurchasecost = $serverservice->servicepricing->nhacungcap->exchangerate;
                $transactionfeegd = $serverservice->servicepricing->nhacungcap->transactionfee;
                $exchangerategoc = $exchangerate->exchange_rate_static;
                if(!$serverservice->serverservicequantityrange->isEmpty()){
                    if($serverservice->api_id ==! null){
                        $purchasecost = $serverservice->apiserverservices->credits;
                        $giatransactionfee = ($tipurchasecost * $purchasecost) / $exchangerategoc + (($purchasecost / 100) * $transactionfeegd);
                        Serverservice::where('id', $sr->id)->update(['purchase_cost' => $giatransactionfee]);
                    }
                }else{
                    if($serverservice->api_id ==! null){
                         foreach($serverservice->serverservicetypewiseprice as $a){
                             foreach($serverservice->apiserverservicetypeprice as $apiserverservicetypeprice){
                                 if($apiserverservicetypeprice->service_type==$a->service_type){
                                     $purchasecost=  $apiserverservicetypeprice->api_price;
                                 }}

                             $giatransactionfee = ($tipurchasecost * $purchasecost) / $exchangerategoc + (($purchasecost / 100) * $transactionfeegd);
                             Serverservicetypewiseprice::where('id', $a->id)->update(['purchase_cost' => $giatransactionfee]);

                         }
                    }
                }
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

        $clientgroup = Clientgroup::orderBy('chietkhau')->get();

        $supplier = Supplier::get();
        $serverservice = Serverservice::find($id);
        return view('edit.editserver', compact('serverservice', 'supplier', 'exchangerate','clientgroup','cliendefault'));
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
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        foreach ($range as $ra) {
            foreach ($clientgroup as $cli) {
                $u = $request->input('client_group_' . $cli->id . '_' . $ra->id);

                if ($u == !null) {
                    $update = Serverserviceclientgroupcredit::where('id', $ra->id)
                        ->where('client_group_id', $cli->id)
                        ->update(['credit' => $u]);
                }


            }
            $c = $request->input('credit_'.$ra->id);
            if ($c == !null) {
                foreach ($currencies as $cu) {
                    $credit= Serverserviceusercredit::where('server_service_range_id',$ra->id)->where('currency',$cu->currency_code)->update(['credit' => $c * $cu->exchange_rate_static]);
                }

            }
        }
        return back();
    }

    public function editwise($id, Request $request)
    {
        $clientgroup = Clientgroup::get();
        $server_service_type_wise_price = Serverservicetypewiseprice::get();
        foreach ($server_service_type_wise_price as $sp){
            $pc = $request->input('purchase_cost_vip_' . $sp->id);
            if ($pc == !null) {
                Serverservicetypewiseprice::where('id', $sp->id)->update(['purchase_cost' => $pc]);
            }
            $amount = $request->input('amount_' . $sp->id);
            if ($amount == !null) {
                Serverservicetypewiseprice::where('id', $sp->id)->update(['amount' => $amount]);
            }
        }


        $server_service_type_wise_groupprice = Serverservicetypewisegroupprice::where('server_service_id', $id)->get();
        foreach ($server_service_type_wise_groupprice as $sgp) {
            foreach ($clientgroup as $cli) {
                $u = $request->input('client_group_amount_' . $sgp->id . '_' . $cli->id);
                if ($u == !null) {
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
