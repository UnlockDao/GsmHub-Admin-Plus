<?php

namespace App\Http\Controllers;


use App\Models\Clientgroup;
use App\Models\Clientgroupprice;
use App\Models\Config;
use App\Models\Currencie;
use App\Models\Currenciepricing;
use App\Models\Imeiservice;
use App\Models\Imeiservicecredit;
use App\Models\Imeiservicegroup;
use App\Models\Imeiservicepricing;
use App\Models\Serverservice;
use App\Models\Serverserviceclientgroupcredit;
use App\Models\Serverservicegroup;
use App\Models\Serverservicequantityrange;
use App\Models\Serverservicetypewisegroupprice;
use App\Models\Serverservicetypewiseprice;
use App\Models\Serverserviceusercredit;
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
        $type = $request->type;
        if ($cliendefault == !null && $chk == !null) {
            foreach ($chk as $c) {
                $imei = Imeiservice::find($c);
                $i = Imeiservice::find($c);
                $saveimeipricing = Imeiservicepricing::find($c);
                $imeiprice = Clientgroupprice::where('service_id', $c)->where('group_id', $cliendefault->id)->where('currency', $currenciessite->config_value)->first();

                if ($request->sales == 0) {
                    if ($saveimeipricing->sale == 0) {
                        $saveimeipricing->pricing_sale = $i->credit + $imeiprice->discount;
                        $saveimeipricing->pricingdefault_sale = $i->credit;
                        $saveimeipricing->save();
                    }
                    $saveimeipricing->sale = $request->sales;
                    $saveimeipricing->save();
                    $i->credit = $saveimeipricing->pricingdefault_sale;
                    $i->save();
                    foreach ($currencies as $cu) {
                        Imeiservicecredit::where('service_id', $c)
                            ->where('currency', $cu->currency_code)
                            ->update(['credit' => $saveimeipricing->pricingdefault_sale * Utility::exchangeRateStatic($cu->currency_code)]);
                    }
                    if ($cliendefault == !null) {
                        foreach ($currencies as $cs) {
                            Clientgroupprice::where('group_id', $cliendefault->id)
                                ->where('service_type', 'imei')
                                ->where('currency', $cs->currency_code)
                                ->where('service_id', $i->id)
                                ->update(['discount' => ($saveimeipricing->pricing_sale - $i->credit) * Utility::exchangeRateStatic($cs->currency_code)]);
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
                                        ->update(['discount' => $y * Utility::exchangeRateStatic($c->currency_code)]);
                                }
                            }
                        }
                    }

                } else {
                    if ($saveimeipricing->sale == 0) {
                        $saveimeipricing->pricing_sale = $i->credit + $imeiprice->discount;
                        $saveimeipricing->pricingdefault_sale = $i->credit;
                        $saveimeipricing->save();
                    }
                    $saveimeipricing->sale = $request->sales;
                    $saveimeipricing->save();
                    if ($cliendefault == !null) {
                        if ($type == 1) {
                            $i->credit = $saveimeipricing->pricingdefault_sale * ((100 - $sales) / 100);
                            $i->save();
                            foreach ($currencies as $cu) {
                                Imeiservicecredit::where('service_id', $c)
                                    ->where('currency', $cu->currency_code)
                                    ->update(['credit' => ($saveimeipricing->pricingdefault_sale * ((100 - $sales) / 100)) * Utility::exchangeRateStatic($cu->currency_code)]);
                            }
                            foreach ($currencies as $cs) {
                                Clientgroupprice::where('group_id', $cliendefault->id)
                                    ->where('service_type', 'imei')
                                    ->where('currency', $cs->currency_code)
                                    ->where('service_id', $i->id)
                                    ->update(['discount' => (($saveimeipricing->pricing_sale * ((100 - $sales) / 100)) - $i->credit) * Utility::exchangeRateStatic($cs->currency_code)]);
                            }
                        }
                        if ($type == 2) {
                            $i->credit = ($saveimeipricing->pricingdefault_sale - ((($saveimeipricing->pricingdefault_sale - $i->purchase_cost) / 100) * $sales));
                            $i->save();
                            foreach ($currencies as $cu) {
                                Imeiservicecredit::where('service_id', $c)
                                    ->where('currency', $cu->currency_code)
                                    ->update(['credit' => ($saveimeipricing->pricingdefault_sale - ((($saveimeipricing->pricingdefault_sale - $i->purchase_cost) / 100) * $sales)) * Utility::exchangeRateStatic($cu->currency_code)]);
                            }
                            foreach ($currencies as $cs) {
                                $xx = ($saveimeipricing->pricing_sale - ((($saveimeipricing->pricing_sale - $i->purchase_cost) / 100) * $sales));
                                Clientgroupprice::where('group_id', $cliendefault->id)
                                    ->where('service_type', 'imei')
                                    ->where('currency', $cs->currency_code)
                                    ->where('service_id', $i->id)
                                    ->update(['discount' => (($xx - $i->credit) * Utility::exchangeRateStatic($cs->currency_code))]);
                            }
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
                                        ->update(['discount' => $y * Utility::exchangeRateStatic($c->currency_code)]);
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
        $type = $request->type;
        $cliendefault = Clientgroup::where('status', 'active')->where('chietkhau', '0')->first();
        $currenciessite = Config::where('config_var', 'site_default_currency')->first();
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        if ($cliendefault == !null && $chkrange == !null) {
            foreach ($chkrange as $crm) {
                $serverservicequantityrange = Serverservicequantityrange::where('server_service_id', $crm)->get();
                foreach ($serverservicequantityrange as $crs) {
                    $cr = $crs->id;
                    $creditdefault = Serverserviceclientgroupcredit::where('server_service_range_id', $cr)->where('client_group_id', $cliendefault->id)->where('currency', $currenciessite->config_value)->first();
                    $ra = Serverservicequantityrange::find($cr);
                    $racredit = Serverserviceusercredit::where('server_service_range_id', $cr)->where('currency', 'USD')->first();
                    if ($request->sales == 0) {
                        if ($ra->sale == 0) {
                            $ra->pricing_sale = $creditdefault->credit;
                            $ra->save();
                            foreach ($currencies as $c) {
                                $run = Serverserviceusercredit::where('server_service_range_id', $cr)
                                    ->where('currency', $c->currency_code)
                                    ->update(['pricingdefault_sale' => $racredit->credit * Utility::exchangeRateStatic($c->currency_code)]);
                            }
                        }
                        $ra->sale = $request->sales;
                        $ra->save();
                        foreach ($currencies as $c) {
                            $run2 = Serverserviceclientgroupcredit::where('server_service_range_id', $cr)
                                ->where('client_group_id', $cliendefault->id)
                                ->where('currency', $c->currency_code)
                                ->update(['credit' => $ra->pricing_sale * Utility::exchangeRateStatic($c->currency_code)]);
                            $run3 = Serverserviceusercredit::where('server_service_range_id', $cr)
                                ->where('currency', $c->currency_code)
                                ->update(['credit' => $racredit->pricingdefault_sale * Utility::exchangeRateStatic($c->currency_code)]);
                        }

                        $this->updatrangeserver($cr);

                    } else {
                        if ($ra->sale == 0) {
                            $ra->pricing_sale = $creditdefault->credit;
                            $ra->save();
                            foreach ($currencies as $c) {
                                $run = Serverserviceusercredit::where('server_service_range_id', $cr)
                                    ->where('currency', $c->currency_code)
                                    ->update(['pricingdefault_sale' => $racredit->credit * Utility::exchangeRateStatic($c->currency_code)]);
                            }
                        }
                        $ra->sale = $request->sales;
                        $ra->save();

                        if ($type == 1) {
                            foreach ($currencies as $c) {
                                $run = Serverserviceclientgroupcredit::where('server_service_range_id', $cr)
                                    ->where('client_group_id', $cliendefault->id)
                                    ->where('currency', $c->currency_code)
                                    ->update(['credit' => ($ra->pricing_sale * ((100 - $sales) / 100)) * Utility::exchangeRateStatic($c->currency_code)]);
                                $run2 = Serverserviceusercredit::where('server_service_range_id', $cr)
                                    ->where('currency', $c->currency_code)
                                    ->update(['credit' => ($racredit->pricingdefault_sale * ((100 - $sales) / 100)) * Utility::exchangeRateStatic($c->currency_code)]);
                            }

                        }
                        if ($type == 2) {
                            foreach ($currencies as $c) {
                                $xx = ($ra->pricing_sale - ((($ra->pricing_sale - $ra->serverservicequantityrange->purchase_cost) / 100) * $sales));
                                $run = Serverserviceclientgroupcredit::where('server_service_range_id', $cr)
                                    ->where('client_group_id', $cliendefault->id)
                                    ->where('currency', $c->currency_code)
                                    ->update(['credit' => $xx * Utility::exchangeRateStatic($c->currency_code)]);
                                $run2 = Serverserviceusercredit::where('server_service_range_id', $cr)
                                    ->where('currency', $c->currency_code)
                                    ->update(['credit' => ($racredit->pricingdefault_sale - ((($racredit->pricingdefault_sale - $ra->serverservicequantityrange->purchase_cost) / 100) * $sales)) * Utility::exchangeRateStatic($c->currency_code)]);

                            }

                        }

                        $this->updatrangeserver($cr);
                    }
                }
            }
        }

        //
        if ($cliendefault == !null && $chkwise == !null) {
            foreach ($chkwise as $cem) {

                $serverservicetypewiseprice = Serverservicetypewiseprice::where('server_service_id', $cem)->get();
                foreach ($serverservicetypewiseprice as $ces) {
                    $ce = $ces->id;
                    if ($request->sales == 0) {
                        $wi = Serverservicetypewiseprice::find($ce);
                        $giabanle = $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
                        if ($wi->sale == 0) {
                            $wi->pricing_sale = $giabanle;
                            $wi->pricingdefault_sale = $wi->amount;
                            $wi->save();
                        }
                        $wi->sale = $request->sales;
                        $wi->save();
                        $runpricingdefault = Serverservicetypewiseprice::where('id', $ce)->update(['amount' => $wi->pricingdefault_sale]);
                        $run = Serverservicetypewisegroupprice::where('service_type_id', $ce)->where('group_id', $cliendefault->id)->update(['amount' => $wi->pricing_sale]);
                        $this->updatewiseserver($ce);

                    } else {
                        $wi = Serverservicetypewiseprice::find($ce);
                        $giabanle = $wi->servicetypegroupprice->where('group_id', $cliendefault->id)->first()->amount;
                        if ($wi->sale == 0) {
                            $wi->pricing_sale = $giabanle;
                            $wi->pricingdefault_sale = $wi->amount;
                            $wi->save();
                        }
                        $wi->sale = $request->sales;
                        $wi->save();
                        if ($type == 1) {
                            $runpricingdefault = Serverservicetypewiseprice::where('id', $ce)->update(['amount' => $wi->pricingdefault_sale * ((100 - $sales) / 100)]);
                            $run = Serverservicetypewisegroupprice::where('service_type_id', $ce)->where('group_id', $cliendefault->id)->update(['amount' => $wi->pricing_sale * ((100 - $sales) / 100)]);
                        }
                        if ($type == 2) {
                            $runpricingdefault = Serverservicetypewiseprice::where('id', $ce)->update(['amount' => $wi->pricingdefault_sale - ((($wi->pricingdefault_sale - $wi->purchase_cost) / 100) * $sales)]);
                            $xx = ($wi->pricing_sale - ((($wi->pricing_sale - $wi->purchase_cost) / 100) * $sales));
                            $run = Serverservicetypewisegroupprice::where('service_type_id', $ce)->where('group_id', $cliendefault->id)->update(['amount' => $xx]);
                        }
                        $this->updatewiseserver($ce);
                    }
                }
            }
        }
        return back();
    }

    public function updatrangeserver($cr)
    {
        $cliengroup = Clientgroup::get();
        $cliendefault = Clientgroup::where('status', 'active')->where('chietkhau', '0')->first();
        $currenciessite = Config::where('config_var', 'site_default_currency')->first();
        $currencies = Currencie::where('display_currency', 'Yes')->get();
        if ($cliendefault == !null) {
            foreach ($cliengroup as $clg) {
                $range = Serverserviceclientgroupcredit::where('client_group_id', $clg->id)->where('server_service_range_id', $cr)->where('currency', $currenciessite->config_value)->get();
                foreach ($range as $ra) {
                    $i = Serverserviceclientgroupcredit::where('client_group_id', $cliendefault->id)
                        ->where('currency', $currenciessite->config_value)
                        ->where('server_service_range_id', $ra->server_service_range_id)
                        ->first();
                    $giabanle = $i->credit;
                    if ($ra->serverservicequantityrange->serverservicequantityrange == !null) {
                        $chietkhau = ($giabanle - ((($giabanle - $ra->serverservicequantityrange->serverservicequantityrange->purchase_cost) / 100) * $clg->chietkhau));
                        foreach ($currencies as $cu) {
                            $update = Serverserviceclientgroupcredit::where('server_service_range_id', $ra->server_service_range_id)
                                ->where('client_group_id', $clg->id)
                                ->where('currency', $cu->currency_code)
                                ->update(['credit' => $chietkhau * Utility::exchangeRateStatic($cu->currency_code)]);
                        }
                    }
                }
            }
        }
    }

    public function updatewiseserver($ce)
    {
        $cliendefault = Clientgroup::where('status', 'active')->where('chietkhau', '0')->first();
        $wi = Serverservicetypewiseprice::find($ce);
        if ($wi->adminplus_service == !null) {
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
