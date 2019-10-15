<?php

namespace App\Http\Controllers;

use App\Models\Clientgroup;
use App\Models\Config;
use App\Models\Currencie;
use App\Models\Currenciepricing;
use App\Models\Serverservice;
use App\Models\Serverserviceclientgroupcredit;
use App\Models\Serverservicegroup;
use App\Models\Serverservicequantityrange;
use App\Models\Serverservicetypewisegroupprice;
use App\Models\Serverservicetypewiseprice;
use App\Models\Serverserviceusercredit;
use App\Models\Serviceservicepricing;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ServerserviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkNullUser(){

        $clientgroup = Clientgroup::where('status','active')->orderBy('chietkhau')->get();
        $currenciessite = Config::where('config_var','site_default_currency')->first();
        $serverservices = Serverservice::get();
        foreach ($serverservices as $serverservice){
            //add price user null wise
            foreach($serverservice->serverservicetypewiseprice as $a){
                foreach ($clientgroup as $cg) {
                    $check = Serverservicetypewisegroupprice::where('group_id', $cg->id)->where('server_service_id', $serverservice->id)->where('service_type_id', $a->id)->first();
                    if ($check == null) {
                        $create = Serverservicetypewisegroupprice::firstOrCreate(['server_service_id'=>$serverservice->id,
                            'service_type_id'=>$a->id,
                            'group_id'=>$cg->id,
                            'currency'=>$currenciessite->config_value,
                            'amount'=>$a->amount]);
                    }
                }
            }
            //add price user null
            foreach($serverservice->serverservicequantityrange as $sr){
                foreach ($clientgroup as $cg) {
                    $check = Serverserviceclientgroupcredit::where('client_group_id', $cg->id)->where('server_service_range_id', $sr->id)->first();
                    if ($check == null) {
                        $currencies = Currencie::where('display_currency', 'Yes')->get();
                        foreach ($currencies as $c) {
                            if( $sr->serverserviceusercredit->where('currency',$currenciessite->config_value)->first() ==! null){
                            $create = Serverserviceclientgroupcredit::firstOrCreate(['server_service_range_id' => $sr->id,
                                'client_group_id' => $cg->id,
                                'credit' => $sr->serverserviceusercredit->where('currency',$currenciessite->config_value)->first()->credit* $c->exchange_rate_static,
                                'currency' => $c->currency_code]);
                            }
                        }
                    }
                }
            }
        }
    }
    public function runStart(){
        $this->checkServer();
        $this->checkNullUser();
        $this->UpdatePurchaseCostNetServer();
        $this->checkApiToNull();
        return 'done';
    }
    public function index(Request $request)
    {
        $currenciessite = Config::where('config_var','site_default_currency')->first();
        //
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        //
        $cachesearch = $request;
        $clientgroup = Clientgroup::where('status','active')->orderBy('chietkhau')->get();
        $supplier =Supplier::get();
        $suppliersearch =$request->supplier;
        $groupsearch = Serverservicegroup::get();

        //search
        $server_service_group = Serverservicegroup::orderBy('display_order','desc')->where('id','LIKE',$request->group_name)->get();
        if($request->type == 'api'){
            $serverservice = Serverservice::orderBy('service_name')
                ->where('status','LIKE',$request->status)
                ->whereHas('servicepricing', function($query) use ($suppliersearch) {
                    if($suppliersearch ==! null){
                        $query->where('id_supplier','LIKE',$suppliersearch);
                    }
                })
                ->where('api','>','0')
                ->get();
        }
        elseif($request->type == 'manual'){
            $serverservice = Serverservice::orderBy('service_name')
                ->where('status','LIKE',$request->status)
                ->whereHas('servicepricing', function($query) use ($suppliersearch) {
                    if($suppliersearch ==! null){
                        $query->where('id_supplier','LIKE',$suppliersearch);
                    }
                })
                ->where('api','')
                ->get();
        }else{
            $serverservice = Serverservice::orderBy('service_name')
                ->where('status','LIKE',$request->status)
                ->whereHas('servicepricing', function($query) use ($suppliersearch) {
                    if($suppliersearch ==! null){
                        $query->where('id_supplier','LIKE',$suppliersearch);
                    }
                })
                ->get();
        }

        return view('services.serverservice', compact('serverservice','server_service_group','clientgroup','exchangerate','supplier','groupsearch','cachesearch','currenciessite'));
    }

    public function checkServer()
    {
        $server_services = Serverservice::orderBy('id')->get();
        foreach ($server_services as $v) {
            $add = Serviceservicepricing::firstOrCreate(['id'=>$v->id]);

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

    public function UpdatePurchaseCostNetServer()
    {
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        $serviceservicepricing = Serviceservicepricing::get();
        foreach ($serviceservicepricing as $sr) {
            if ($sr->id_supplier == !null) {
                $serverservice = Serverservice::find($sr->id);
                $tipurchasecost = $serverservice->servicepricing->nhacungcap->exchangerate;
                $transactionfeegd = $serverservice->servicepricing->nhacungcap->transactionfee;
                $exchangerategoc = $exchangerate->exchange_rate_static;
                if (!$serverservice->serverservicequantityrange->isEmpty()) {
                    if ($serverservice->api_id == !null) {
                        $purchasecost = $serverservice->apiserverservices->credits;
                        $giatransactionfee = ($tipurchasecost * $purchasecost) / $exchangerategoc + (($purchasecost / 100) * $transactionfeegd);
                        Serverservice::where('id', $sr->id)->update(['purchase_cost' => $giatransactionfee]);
                    }
                }
                if (!$serverservice->serverservicetypewiseprice->isEmpty()){
                    if ($serverservice->api_id == !null) {
                        foreach ($serverservice->serverservicetypewiseprice as $a) {
                            foreach ($a->apiservicetypewisepriceid as $apiserverservicetypeprice) {
                                if ($apiserverservicetypeprice->id == $a->api_service_type_wise_price_id) {
                                    $purchasecost = $apiserverservicetypeprice->api_price;
                                }
                            }
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
        $cliendefault = Clientgroup::where('status','active')->where('chietkhau', '0')->first();

        $clientgroup = Clientgroup::where('status','active')->orderBy('chietkhau')->get();

        $supplier = Supplier::get();
        $serverservice = Serverservice::find($id);

        $allcurrencies = Currencie::get();

        $currenciessite = Config::where('config_var','site_default_currency')->first();

        $group = Serverservicegroup::get();

        return view('edit.editserver', compact('serverservice', 'supplier', 'exchangerate','clientgroup','cliendefault','allcurrencies','currenciessite','group'));
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
        $servicepricing->id_supplier = $request->id_supplier;
        $servicepricing->save();
        // cập nhập  purchasecost net
        $purchasecostnet = Serverservice::find($id);
        $purchasecostnet->purchase_cost = $request->purchasenet;
        $purchasecostnet->service_name = $request->service_name;
        $purchasecostnet->save();
        $clientgroup = Clientgroup::where('status','active')->get();
        $range = Serverserviceclientgroupcredit::get();
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        foreach ($range as $ra) {
            foreach ($clientgroup as $cli) {
                $u = $request->input('client_group_' . $cli->id . '_' . $ra->id);

                if ($u == !null) {
                    foreach ($currencies as $cu) {
                             $update = Serverserviceclientgroupcredit::where('server_service_range_id',$ra->server_service_range_id)
                                                                    ->where('client_group_id', $cli->id)
                                                                    ->where('currency',$cu->currency_code)
                                                                    ->update(['credit' => $u* $cu->exchange_rate_static]);
                    }
                }


            }
            $c = $request->input('credit_'.$ra->server_service_range_id);
            if ($c == !null) {
                foreach ($currencies as $cu) {
                    $credit= Serverserviceusercredit::where('server_service_range_id',$ra->server_service_range_id)->where('currency',$cu->currency_code)->update(['credit' => $c * $cu->exchange_rate_static]);
                }
            }
        }
        if($request->credit_new ==! null){
            $newquantityrange = new Serverservicequantityrange();
            $newquantityrange->server_service_id = $id;
            $newquantityrange->from_range = 1;
            $newquantityrange->to_range = $request->to_range;
            $newquantityrange->save();
            foreach ($currencies as $cu) {
                $server_service_user_credit = new Serverserviceusercredit();
                $server_service_user_credit->server_service_range_id = $newquantityrange->id;
                $server_service_user_credit->credit = $request->credit_new * $cu->exchange_rate_static;
                $server_service_user_credit->currency = $cu->currency_code;
                $server_service_user_credit->save();
            }
            foreach ($clientgroup as $cli) {
                $newcredituser = $request->input('sel_client_group_'.$cli->id.'_new');
                if ($newcredituser ==! null) {
                    foreach ($currencies as $cu) {
                        $addcredituser = new Serverserviceclientgroupcredit();
                        $addcredituser->server_service_range_id = $newquantityrange->id;
                        $addcredituser->client_group_id = $cli->id;
                        $addcredituser->credit = $newcredituser * $cu->exchange_rate_static;
                        $addcredituser->currency = $cu->currency_code;
                        $addcredituser->save();
                    }
                }
            }

        }
        return back();
    }

    public function editwise($id, Request $request)
    {

        //save infoserver service
        $servicepricing = Serviceservicepricing::find($id);
        $servicepricing->id_supplier = $request->id_supplier;
        $servicepricing->save();
        $purchasecostnet = Serverservice::find($id);
        $purchasecostnet->service_name = $request->service_name;
        $purchasecostnet->save();


        $clientgroup = Clientgroup::where('status','active')->get();
        $server_service_type_wise_price = Serverservicetypewiseprice::where('server_service_id', $id)->get();
        foreach ($server_service_type_wise_price as $sp){
            $pc = $request->input('purchase_cost_vip_' . $sp->id);
            if ($pc == !null) {
                Serverservicetypewiseprice::where('id', $sp->id)->update(['purchase_cost' => $pc]);
            }
            $amount = $request->input('amount_' . $sp->id);
            if ($amount == !null) {
                Serverservicetypewiseprice::where('id', $sp->id)->update(['amount' => $amount]);
            }
            $purchasenotvip = $request->input('purchase_cost_not_net_' . $sp->id);
            if ($purchasenotvip == !null) {
                Serverservicetypewiseprice::where('id', $sp->id)->update(['purchase_cost_not_net' => $purchasenotvip]);
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
    public function delete($id)
    {
        Serverservice::find($id)->delete();
        return back();
    }
}
