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
use App\Models\Serverservicegroup;
use App\Models\Serverservicetypewisegroupprice;
use App\Models\Serverservicetypewiseprice;
use App\Models\Supplier;
use Illuminate\Http\Request;

class Sales
{
    public function salesimei(Request $request)
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
        $currenciessite = Config::where('config_var', 'site_default_currency')->first();
        $cliengroup = Clientgroup::get();
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        if ($cliendefault == !null && $chk == !null) {
            foreach ($chk as $c) {
                $imei = Imeiservice::find($c);
                $i = Imeiservice::find($c);
                $saveimeipricing = Imeiservicepricing::find($c);
                $imeiprice = Clientgroupprice::where('service_id', $c)->where('group_id', $cliendefault->id)->where('currency', $currenciessite->config_value)->first();

                if ($request->sales == 0) {
                    if ($saveimeipricing->sale == 0) {
                        $saveimeipricing->pricing_sale = $i->credit + $imeiprice->discount;
                        $saveimeipricing->save();
                    }
                    $saveimeipricing->sale = $request->sales;
                    $saveimeipricing->save();

                    if ($cliendefault == !null) {
                        foreach ($currencies as $cs) {
                            Clientgroupprice::where('group_id', $cliendefault->id)
                                ->where('service_type', 'imei')
                                ->where('currency', $cs->currency_code)
                                ->where('service_id', $i->id)
                                ->update(['discount' => ($saveimeipricing->pricing_sale - $i->credit) * $cs->exchange_rate_static]);
                        }
                        foreach ($cliengroup as $clg) {
                            $imeiprice = Clientgroupprice::where('service_id', $i->id)->where('group_id', $cliendefault->id)->where('currency', $currenciessite->config_value)->first();
                            if ($imeiprice == !null) {
                                $giabanle = $i->credit + $imeiprice->discount;
                                $chietkhau = ($giabanle - ((($giabanle - $i->purchase_cost) / 100) * $clg->chietkhau));
                                $y = $chietkhau - $i->credit;
                                foreach ($currencies as $c) {
                                    $updatepriceuse = Clientgroupprice::where('group_id', $clg->id)
                                        ->where('service_type', 'imei')
                                        ->where('currency', $c->currency_code)
                                        ->where('service_id', $i->id)
                                        ->update(['discount' => $y * $c->exchange_rate_static]);
                                }
                            }
                        }
                    }

                } else {
                    if ($saveimeipricing->sale == 0) {
                        $saveimeipricing->pricing_sale = $i->credit + $imeiprice->discount;
                        $saveimeipricing->save();
                    }
                    $saveimeipricing->sale = $request->sales;
                    $saveimeipricing->save();
                    if ($cliendefault == !null) {
                        foreach ($currencies as $cs) {
                            Clientgroupprice::where('group_id', $cliendefault->id)
                                ->where('service_type', 'imei')
                                ->where('currency', $cs->currency_code)
                                ->where('service_id', $i->id)
                                ->update(['discount' => (($saveimeipricing->pricing_sale * ((100 - $sales) / 100)) - $i->credit) * $cs->exchange_rate_static]);
                        }
                        foreach ($cliengroup as $clg) {
                            $imeiprice = Clientgroupprice::where('service_id', $i->id)->where('group_id', $cliendefault->id)->where('currency', $currenciessite->config_value)->first();
                            if ($imeiprice == !null) {
                                $giabanle = $i->credit + $imeiprice->discount;
                                $chietkhau = ($giabanle - ((($giabanle - $i->purchase_cost) / 100) * $clg->chietkhau));
                                $y = $chietkhau - $i->credit;
                                foreach ($currencies as $c) {
                                    $updatepriceuse = Clientgroupprice::where('group_id', $clg->id)
                                        ->where('service_type', 'imei')
                                        ->where('currency', $c->currency_code)
                                        ->where('service_id', $i->id)
                                        ->update(['discount' => $y * $c->exchange_rate_static]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return back();

    }

    public function salesserver(Request $request)
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

    public function updateserver(Request $request)
    {
        $chkrange = $request->chkrange;
        $chkwise = $request->chkwise;
        $sales = $request->sales;
        $cliendefault = Clientgroup::where('status', 'active')->where('chietkhau', '0')->first();
        $currenciessite = Config::where('config_var', 'site_default_currency')->first();
        $cliengroup = Clientgroup::get();
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        if ($cliendefault == !null && $chkrange == !null) {
            foreach ($chkrange as $cr) {
                echo $cr . '<br>';
            }
        }

        //
        if ($cliendefault == !null && $chkwise == !null) {
            foreach ($chkwise as $ce) {
                if ($request->sales == 0) {
                    $wi = Serverservicetypewiseprice::find($ce);
                    $giabanle = $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
                    if ($wi->sale == 0) {
                        $wi->pricing_sale = $giabanle;
                        $wi->save();
                    }
                    $wi->sale = $request->sales;
                    $wi->save();
                    $run = Serverservicetypewisegroupprice::where('service_type_id', $ce)->where('group_id', $cliendefault->id)->update(['amount' => $wi->pricing_sale]);
                    $this->updatewiseserver($ce);

                } else {
                    $wi = Serverservicetypewiseprice::find($ce);
                    $giabanle = $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
                    if ($wi->sale == 0) {
                        $wi->pricing_sale = $giabanle;
                        $wi->save();
                    }
                    $wi->sale = $request->sales;
                    $wi->save();
                    $run = Serverservicetypewisegroupprice::where('service_type_id', $ce)->where('group_id', $cliendefault->id)->update(['amount' => $wi->pricing_sale * ((100 - $sales) / 100)]);
                    $this->updatewiseserver($ce);
                }
            }
        }
        return back();
    }

    public function updatewiseserver($ce)
    {
        $cliendefault = Clientgroup::where('status', 'active')->where('chietkhau', '0')->first();
        $wi = Serverservicetypewiseprice::find($ce);
        if ($wi->adminplus_service == !null) {
            $giabanle = $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
            echo '<br>';
            foreach ($wi->servicetypegroupprice as $groupprice) {
                if ($groupprice->clientgroup == !null) {
                    $chietkhau = ($giabanle - ((($giabanle - $wi->purchase_cost) / 100) * $groupprice->clientgroup->chietkhau));
                    echo '|' . $groupprice->clientgroup->group_name . '|' . $groupprice->id;
                    Serverservicetypewisegroupprice::where('id', $groupprice->id)
                        ->update(['amount' => $chietkhau]);
                }
            }
        }
    }
}