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

class Export
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
        return view('export.imeiexport', compact('imei_service', 'group', 'usergroup', 'exchangerate', 'groupsearch', 'supplier', 'cachesearch', 'currencies', 'currenciessite'));
    }

    public function imeiquickedit(Request $request)
    {
        $imeiservice = Imeiservice::find($request->id);
        if ($request->purchase_cost) {
            $imeiservice->purchase_cost = $request->purchase_cost;
        }
        if ($request->credit) {
            $imeiservice->credit = $request->credit;
        }
        if ($request->service_name) {
            $imeiservice->service_name = $request->service_name;
        }
        $imeiservice->save();


        $currencies = Currencie::where('display_currency', 'Yes')->get();
        $getimei = Imeiservice::find($request->id);
        //gọi nhóm user
        $group_user = Clientgroup::where('status', 'active')->get();
        //ghi dữ liệu giá vào nhóm user
        foreach ($group_user as $u) {
            $idclient = $u->id;
            if ($request->input('group' . $idclient)) {
                $u = $request->input('group' . $idclient);
                $y = $u - $getimei->credit;

                foreach ($currencies as $c) {
                    Clientgroupprice::where('group_id', $idclient)
                        ->where('service_type', 'imei')
                        ->where('currency', $c->currency_code)
                        ->where('service_id', $request->id)
                        ->update(['discount' => $y * $c->exchange_rate_static]);
                }
            }
        }
        return;

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
        return view('export.serverexport', compact('serverservice', 'server_service_group', 'clientgroup', 'exchangerate', 'supplier', 'groupsearch', 'cachesearch', 'currencies', 'currenciessite'));
    }
}