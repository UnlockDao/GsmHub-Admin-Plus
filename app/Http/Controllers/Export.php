<?php

namespace App\Http\Controllers;


use App\Models\Clientgroup;
use App\Models\Clientgroupprice;
use App\Models\Config;
use App\Models\Currencie;
use App\Models\Currenciepricing;
use App\Models\Imeiservice;
use App\Models\Imeiservicegroup;
use App\Models\Imeiservicepricing;
use App\Models\Serverservice;
use App\Models\Serverserviceclientgroupcredit;
use App\Models\Serverservicegroup;
use App\Models\Serverservicetypewisegroupprice;
use App\Models\Serverservicetypewiseprice;
use App\Models\Serverserviceusercredit;
use App\Models\Serviceservicepricing;
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
        $getimei = Imeiservice::find($request->id);
        $imeiservice = Imeiservice::find($request->id);
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        $cliengroup = Clientgroup::get();
        $currenciessite = Config::where('config_var', 'site_default_currency')->first();
        $cliendefault = Clientgroup::where('status', 'active')->where('chietkhau', '0')->first();

        if ($request->type == "services") {
            if ($request->column == 'service_name') {
                $imeiservice->service_name = $request->value;
            }
            if ($request->column == 'purchasecostnet') {
                $imeiservice->purchase_cost = $request->value;
            }
            if ($request->column == 'credit') {
                $imeiservice->credit = $request->value;
            }
            if ($request->column == 'service_group') {
                $imeiservice->imei_service_group_id = $request->value;
            }
            if ($request->column == 'process_time') {
                $imeiservice->process_time = $request->value;
            }
            if ($request->column == 'time_unit') {
                $imeiservice->time_unit = $request->value;
            }
            if ($request->column == 'service_information') {
                $imeiservice->service_information = $request->value;
            }
            $imeiservice->save();
        }

        if ($request->type == "services") {
            if ($request->column == 'id_supplier') {
                $updatesupplier = Imeiservicepricing::find($request->id);
                $updatesupplier->id_supplier = $request->value;
                $updatesupplier->save();
                //tính giá + phí
                $exchangerate = Currencie::where('currency_code', 'VND')->first();
                $imeiprice = Imeiservicepricing::where('id_supplier', $request->value)->find($request->id);
                if ($imeiprice->imei->api_id == !null && $imeiprice->imei->apiserverservices == !null) {
                    $giatransactionfee = ($imeiprice->nhacungcap->exchangerate * $imeiprice->imei->apiserverservices->credits) / $exchangerate->exchange_rate_static + (($imeiprice->imei->apiserverservices->credits / 100) * $imeiprice->nhacungcap->transactionfee);
                    Imeiservice::where('id', $request->id)->update(['purchase_cost' => $giatransactionfee]);
                } else {
                    $defaultcurrency = Currenciepricing::where('type', '1')->first();
                    $exchangerate = Currencie::find($defaultcurrency->currency_id);
                    $c = Imeiservicepricing::find($request->id);
                    if ($c->nhacungcap == !null) {
                        $giatransactionfee = ($c->nhacungcap->exchangerate * $c->purchasecost) / $exchangerate->exchange_rate_static + (($c->purchasecost / 100) * $c->nhacungcap->transactionfee);
                        Imeiservice::where('id', $c->id)->update(['purchase_cost' => $giatransactionfee]);
                    }
                }
            }

            if ($request->column == 'purchase_cost') {
                $imeipricing = Imeiservicepricing::find($request->id);
                $imeipricing->purchasecost = $request->value;
                $imeipricing->save();

                $defaultcurrency = Currenciepricing::where('type', '1')->first();
                $exchangerate = Currencie::find($defaultcurrency->currency_id);
                $c = Imeiservicepricing::find($request->id);
                if ($c->nhacungcap == !null) {
                    $giatransactionfee = ($c->nhacungcap->exchangerate * $c->purchasecost) / $exchangerate->exchange_rate_static + (($c->purchasecost / 100) * $c->nhacungcap->transactionfee);
                    Imeiservice::where('id', $c->id)->update(['purchase_cost' => $giatransactionfee]);
                }
            }
        }

        if ($request->type == "price") {
            $y = $request->value - $getimei->credit;
            foreach ($currencies as $c) {
                Clientgroupprice::where('group_id', $request->idgr)
                    ->where('service_type', 'imei')
                    ->where('currency', $c->currency_code)
                    ->where('service_id', $request->id)
                    ->update(['discount' => $y * $c->exchange_rate_static]);
            }
            if ($cliendefault->id == $request->idgr) {
                foreach ($cliengroup as $clg) {
                    $imeiprice = Clientgroupprice::where('service_id', $request->id)->where('group_id', $cliendefault->id)->where('currency', $currenciessite->config_value)->first();
                    if ($imeiprice == !null) {
                        $giabanle = $getimei->credit + $imeiprice->discount;
                        $chietkhau = ($giabanle - ((($giabanle - $getimei->purchase_cost) / 100) * $clg->chietkhau));
                        $y = $chietkhau - $getimei->credit;
                        foreach ($currencies as $c) {
                            $updatepriceuse = Clientgroupprice::where('group_id', $clg->id)
                                ->where('service_type', 'imei')
                                ->where('currency', $c->currency_code)
                                ->where('service_id', $getimei->id)
                                ->update(['discount' => $y * $c->exchange_rate_static]);
                        }
                    }
                }
            }
        }

        return $request;

    }

    public function serverquickedit(Request $request)
    {
        $cliendefault = Clientgroup::where('status', 'active')->where('chietkhau', '0')->first();

        $serverservice = Serverservice::find($request->id);
        if ($request->type == "services") {
            if ($request->column == 'service_name') {
                $serverservice->service_name = $request->value;
            }
            if ($request->column == 'purchase_cost') {
                $serverservice->purchase_cost = $request->value;
            }
            if ($request->column == 'service_group') {
                $serverservice->server_service_group_id = $request->value;
            }
            if ($request->column == 'process_time') {
                $serverservice->process_time = $request->value;
            }
            if ($request->column == 'time_unit') {
                $serverservice->time_unit = $request->value;
            }
            if ($request->column == 'service_information') {
                $serverservice->service_information = $request->value;
            }
            $serverservice->save();
            if ($request->column == 'id_supplier') {
                $servicepricing = Serviceservicepricing::find($request->id);
                $servicepricing->id_supplier = $request->value;
                $servicepricing->save();
            }
        }


        if ($request->type == "pricewise") {
            $serverservice = Serverservicetypewisegroupprice::find($request->id);
            $serverservice->amount = $request->value;
            $serverservice->save();

            if ($cliendefault->id == $serverservice->group_id) {
                $wise = Serverservicetypewiseprice::where('server_service_id', $serverservice->server_service_id)->get();
                foreach ($wise as $wi) {
                    if ($wi->adminplus_service == !null) {
                        $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
                        $giabanle = $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
                        foreach ($wi->servicetypegroupprice as $groupprice) {
                            if ($groupprice->clientgroup == !null) {
                                $chietkhau = ($giabanle - ((($giabanle - $wi->purchase_cost) / 100) * $groupprice->clientgroup->chietkhau));
                                Serverservicetypewisegroupprice::where('id', $groupprice->id)
                                    ->update(['amount' => $chietkhau]);
                            }

                        }
                    }
                }
            }
        }
        if ($request->type == "pricerange") {
            $serverservice = Serverserviceclientgroupcredit::find($request->id);
            $serverservice->credit = $request->value;
            $serverservice->save();
        }
        if ($request->type == "creditwise") {
            $serverservice = Serverservicetypewiseprice::find($request->id);
            $serverservice->amount = $request->value;
            $serverservice->save();
        }
        if ($request->type == "creditrange") {
            $serverservice = Serverserviceusercredit::find($request->id);
            $serverservice->credit = $request->value;
            $serverservice->save();
        }
        return $request;
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