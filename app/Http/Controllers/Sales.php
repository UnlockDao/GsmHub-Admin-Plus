<?php

namespace App\Http\Controllers;


use App\Models\Clientgroup;
use App\Models\Clientgroupprice;
use App\Models\Config;
use App\Models\Currencie;
use App\Models\Currenciepricing;
use App\Models\Imeiservice;
use App\Models\Imeiservicegroup;
use App\Models\Serverservice;
use App\Models\Serverservicegroup;
use App\Models\Supplier;
use Illuminate\Http\Request;

class Sales
{
    public function exportimei(Request $request)
    {
        $currenciessite = Config::where('config_var', 'site_default_currency')->first();
        //
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        //

        $groupsearch = Imeiservicegroup::get();

        $usergroup = Clientgroup::where('status', 'active')->where('status', 'active')->orderBy('chietkhau')->get();
        $supplier = Supplier::get();
        $suppliersearch = $request->supplier;
        $cachesearch = $request;
        $currencies = Currencie::where('display_currency', 'Yes')->get();

        $group = Imeiservicegroup::orderBy('display_order', 'desc')->where('id', 'LIKE', $request->group_name)->get();
        if ($request->type == 'api') {
            $imei_service = Imeiservice::orderBy('service_name')
                ->where('status', 'LIKE', $request->status)
                ->whereHas('imeipricing', function ($query) use ($suppliersearch) {
                    if ($suppliersearch == !null) {
                        $query->where('id_supplier', 'LIKE', $suppliersearch);
                    }
                })
                ->where('api', '>', '0')
                ->get();
        } elseif ($request->type == 'manual') {
            $imei_service = Imeiservice::orderBy('service_name')
                ->where('status', 'LIKE', $request->status)
                ->whereHas('imeipricing', function ($query) use ($suppliersearch) {
                    if ($suppliersearch == !null) {
                        $query->where('id_supplier', 'LIKE', $suppliersearch);
                    }
                })
                ->where('api', '')
                ->get();
        } else {
            $imei_service = Imeiservice::orderBy('service_name')
                ->where('status', 'LIKE', $request->status)
                ->whereHas('imeipricing', function ($query) use ($suppliersearch) {
                    if ($suppliersearch == !null) {
                        $query->where('id_supplier', 'LIKE', $suppliersearch);
                    }
                })
                ->get();
        }
        return view('sale.imeiexport', compact('imei_service', 'group', 'usergroup', 'exchangerate', 'groupsearch', 'supplier', 'cachesearch', 'currencies', 'currenciessite'));
    }

    public function updateimei(Request $request)
    {
        $chk = $request->chk;
        $sales = $request->sales;
        $cliendefault = Clientgroup::where('status', 'active')->where('chietkhau', '0')->first();
        $currenciessite = Config::where('config_var','site_default_currency')->first();

        if ($cliendefault == !null && $chk ==! null) {
            foreach ($chk as $c) {
                $imei = Imeiservice::find($c);
                $imeiprice = Clientgroupprice::where('service_id', $c)->where('group_id', $cliendefault->id)->where('currency', $currenciessite->config_value)->first();
                echo $giabanle = $imei->credit + $imeiprice->discount;
                echo '<br>';
                echo 'purchase_cost:'.$imei->purchase_cost;
                echo '<br>';
                echo 'profit:'.($giabanle-$imei->purchase_cost);
                echo '<br>';
                echo 'sales:'.($giabanle-$imei->purchase_cost)*((100-$sales)/100);
                echo '<hr>';

            }
        }

    }

    public function exportserver(Request $request)
    {
        $currenciessite = Config::where('config_var', 'site_default_currency')->first();
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        $defaultcurrency = Currenciepricing::where('type', '1')->first();
        $exchangerate = Currencie::find($defaultcurrency->currency_id);
        //
        $cachesearch = $request;
        $clientgroup = Clientgroup::where('status', 'active')->orderBy('chietkhau')->get();
        $supplier = Supplier::get();
        $suppliersearch = $request->supplier;
        $groupsearch = Serverservicegroup::get();
        //search
        $server_service_group = Serverservicegroup::orderBy('display_order', 'desc')->where('id', 'LIKE', $request->group_name)->get();
        if ($request->type == 'api') {
            $serverservice = Serverservice::orderBy('service_name')
                ->where('status', 'LIKE', $request->status)
                ->whereHas('servicepricing', function ($query) use ($suppliersearch) {
                    if ($suppliersearch == !null) {
                        $query->where('id_supplier', 'LIKE', $suppliersearch);
                    }
                })
                ->where('api', '>', '0')
                ->get();
        } elseif ($request->type == 'manual') {
            $serverservice = Serverservice::orderBy('service_name')
                ->where('status', 'LIKE', $request->status)
                ->whereHas('servicepricing', function ($query) use ($suppliersearch) {
                    if ($suppliersearch == !null) {
                        $query->where('id_supplier', 'LIKE', $suppliersearch);
                    }
                })
                ->where('api', '')
                ->get();
        } else {
            $serverservice = Serverservice::orderBy('service_name')
                ->where('status', 'LIKE', $request->status)
                ->whereHas('servicepricing', function ($query) use ($suppliersearch) {
                    if ($suppliersearch == !null) {
                        $query->where('id_supplier', 'LIKE', $suppliersearch);
                    }
                })
                ->get();
        }
        return view('sale.serverexport', compact('serverservice', 'server_service_group', 'clientgroup', 'exchangerate', 'supplier', 'groupsearch', 'cachesearch', 'currencies', 'currenciessite'));
    }
}